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
 * Initializes AuthComponent for use in the controller
 *
 * @param Controller $controller A reference to the instantiating controller object
 * @return void
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
	}

	function exportCsv($data) {
		unset($data[0]['Employee']);
		$this->controller->autoRender = false;
		debug($data);
		foreach($data as $key => $value){
			$flatArray = array();
			$this->flattenArray($value, $flatArray);
			$data[$key] = $flatArray;
		}

		$headerRow = array();
		foreach($data as $key => $value){
			if(!array_search($key, $headerRow)){
				$headerRow[] = $key;
			}
			// debug(array_keys($value));
			// $headerRow = array_merge($headerRow, array_keys($value));
		}

		debug($headerRow);
		debug($data); die;

		//debug($headerRow); die;

		$delimiter = ',';
		$enclosure = '"';
		ini_set('max_execution_time', 600); //increase max_execution_time to 10 min if data set is very large

		$fileName = "export_".date("Y.m.d").".csv";
		$csvFile = fopen('php://output', 'w');

		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename="'.$fileName.'"');

		fputcsv($csvFile,$headerRow, $delimiter, $enclosure);
		foreach ($data as $key => $value) {
			fputcsv($csvFile, $value, $delimiter, $enclosure);
		}

		fclose($csvFile);
	}

	public function flattenArray($array, &$flatArray, $parentKeys = ''){
		foreach($array as $key => $value){
			$chainedKey = ($parentKeys)? $parentKeys.'.'.$key : $key;
			if(is_array($value)){
				$this->flattenArray($value, $flatArray, $chainedKey);
			} else {
				$flatArray[$chainedKey] = $value;
			}
		}
	}



}