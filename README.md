# CakePHP Export Plugin #

	public function export_data() {
		$data = $this->MyModel->find('all');
		$this->Export->exportCsv($data);
	}

A Export plugin for CakePHP 2.X - pass it an array which is the result of a CakePHP Model find('all') call (or other similar method), and it'll flatten it and download it as a .csv file.

You can pass it a multi-level nested array (eg. when using containable, or with recursive set > -1), and it'll handle your belongsTo associations gracefully. If there's missing data in some rows - eg. the belongsTo association for some rows is blank, it'll handle that OK too.

As for hasMany (and other) associations, I don't think they can (or ever need to be) handled gracefully in a single CSV export. If you think differently, I'm open to suggestions or pull requests.

## Introduction ##

The plugin is quick and easy to install. The installation instructions are somewhat long - but that's just to provide the clarity I wished stuff had when I was a CakePHP n00b. If you're not new to Cake, then install the plugin / component and skip to the final step.

## Example input / output ##

Coming soon...

## Supported CakePHP versions ##

* It's tested with CakePHP 2.3. It should work with all CakePHP 2.X, or even 1.X with a little modification.

## Installation ##

### 1. Copy the plugin into your app/Plugin/Export directory ###

	git submodule add git@github.com:joshuapaling/CakePHP-Export-Plugin.git app/Plugin/Export

or download from [https://github.com/joshuapaling/CakePHP-Export-Plugin](https://github.com/joshuapaling/CakePHP-Export-Plugin)

### 2. Load the Plugin ###

In app/Config/bootstrap.php, at the bottom, add a line to load the plugin - either:

	CakePlugin::load('Export'); // Loads only the Export plugin

or

	CakePlugin::loadAll(); // Loads all plugins at once

### 3. Add the Export Component to your Components array ###

Add 'Export.Export' to your Components array of the relevant controller (the first 'Export' refers to the name of the plugin, the second to the name of the component itself)

If you added it to your AppController.php, it might start something like this:

	class AppController extends Controller {
		var $components = array('Auth', 'Session', 'Cookie', RequestHandler', 'Security', 'Export.Export');

### 4. Start Exporting your Data! Example: ###

Say you had a model / controller for Cities. And say that a City belongsTo a State, which belongsTo a country. Your export function in your Cities controller might look like this:

	public function export_cities() {
		// It's OK to use containable or recursive in the export data
		$this->City->contain(array(
			'State' => array(
				'Country'
			)
		));
		$data = $this->City->find('all');
		$this->Export->exportCsv($data, 'cities.csv'); // a CSV file called myExport.csv will be downloaded by the browser.
	}

### Options ###

The exportCsv method can take up to 5 params, though mostly you'll only need the first two:

1. $data - an array of data to export. This array should be of the format returned by a call to $this->MyModel->find('all');
2. $fileName (optional) - the name of the file to download. If blank, it will use a date-stamped name like export_2013-09-24.csv
3. $maxExecutionSeconds (optional) - if set, this will change the PHP max_execution_time. Useful when dealing with large amounts of data.
4. $delimiter (optional) - The delimiter for your CSV. Defaults to comma (,).
5. $enclosure (optional) - The enclosure for your CSV. Defaults to double-quote (").


## License ##

MIT - http://opensource.org/licenses/MIT