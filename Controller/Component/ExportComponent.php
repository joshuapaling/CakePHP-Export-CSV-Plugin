<?php
App::uses('Component', 'Controller');
class ExportComponent extends Component {

/**
 * The calling Controller
 *
 * @var Controller
 */
	public $controller;

/**
 * Starts up ExportComponent for use in the controller
 *
 * @param Controller $controller A reference to the instantiating controller object
 * @return void
 */
	public function startup(Controller $controller) {
		$this->controller = $controller;
	}

	function exportCsv($data, $fileName = '', $maxExecutionSeconds = null, $delimiter = ',', $enclosure = '"') {

		$this->controller->autoRender = false;

		// Flatten each row of the data array
		$flatData = array();
		foreach($data as $numericKey => $row){
			$flatRow = array();
			$this->flattenArray($row, $flatRow);
			$flatData[$numericKey] = $flatRow;
		}

		$headerRow = $this->getKeysForHeaderRow($flatData);
		$flatData = $this->mapAllRowsToHeaderRow($headerRow, $flatData);

		if(!empty($maxExecutionSeconds)){
			ini_set('max_execution_time', $maxExecutionSeconds); //increase max_execution_time if data set is very large
		}

		if(empty($fileName)){
			$fileName = "export_".date("Y-m-d").".csv";
		}

		$csvFile = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');

		fputcsv($csvFile,$headerRow, $delimiter, $enclosure);
		foreach ($flatData as $key => $value) {
			fputcsv($csvFile, $value, $delimiter, $enclosure);
		}
		fclose($csvFile);
	}

	public function flattenArray($array, &$flatArray, $parentKeys = ''){
		foreach($array as $key => $value){
			$chainedKey = ($parentKeys !== '')? $parentKeys.'.'.$key : $key;
			if(is_array($value)){
				$this->flattenArray($value, $flatArray, $chainedKey);
			} else {
				$flatArray[$chainedKey] = $value;
			}
		}
	}

	public function getKeysForHeaderRow($data){
		$headerRow = array();
		foreach($data as $key => $value){
			foreach($value as $fieldName => $fieldValue){
				if(array_search($fieldName, $headerRow) === false){
					$headerRow[] = $fieldName;
				}
			}
		}

		return $headerRow;
	}

	public function mapAllRowsToHeaderRow($headerRow, $data){
		$newData = array();
		foreach($data as $intKey => $rowArray){
			foreach($headerRow as $headerKey => $columnName){
				if(!isset($rowArray[$columnName])){
					//$rowArray[$columnName] = '';
					$newData[$intKey][$columnName] = '';
				} else {
					$newData[$intKey][$columnName] = $rowArray[$columnName];
				}
			}
		}

		return $newData;
	}



}