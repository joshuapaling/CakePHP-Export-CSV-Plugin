## Deprecated 2 July 2013 ##

Hmm... that was a short life cycle! Shortly after making this plugin, I found [Jose's CSV View Plugin](https://github.com/josegonzalez/CsvView), which is more flexible but is (or was) more long-winded in terms of exporting CSV's. So, I wrote a component for Jose's plugin to merge the best features of mine into his.

Jose has accepted the pull request, so I suggest you use his plugin, and use it's CsvView Component to do super quick exports with only a couple of lines of code.

# CakePHP Export as CSV Plugin #

	var $components = array('Export.Export');

	public function export_data() {
		$data = $this->MyModel->find('all');
		$this->Export->exportCsv($data);
	}

A CakePHP 2.X plugin to export / download data as a CSV file. Pass it the result of a `$this->MyModel->find('all');` call, and it'll flatten it and download it as a .csv file.

It handles nested `belongsTo` associations just fine. As for `hasMany` (and other) associations, I don't think they can (or ever need to be) handled gracefully in a single CSV export. If you think differently, I'm open to suggestions or pull requests.


## Installation ##

Sympathising with my former (CakePHP n00b) self, verbose instructions follow. If you're not new to Cake, just install the plugin and skip to the final step.

### 1. Install the plugin into your app/Plugin/Export directory ###

	git submodule add git@github.com:joshuapaling/CakePHP-Export-CSV-Plugin.git app/Plugin/Export

or download it from https://github.com/joshuapaling/CakePHP-Export-CSV-Plugin

### 2. Load the Plugin ###

In app/Config/bootstrap.php, at the bottom, add a line to load the plugin - either:

	CakePlugin::load('Export'); // Loads only the Export plugin

or

	CakePlugin::loadAll(); // Loads all plugins at once

### 3. Add the Export Component to your Components array ###

Add 'Export.Export' to your Components array of the relevant controller (the first 'Export' refers to the name of the plugin, the second to the name of the component itself)

If you added it to your AppController.php, it might start something like this:

	class AppController extends Controller {
		var $components = array('Export.Export', 'Auth', 'Session', 'Cookie', 'RequestHandler', 'Security');

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
		$this->Export->exportCsv($data, 'cities.csv');
		// a CSV file called myExport.csv will be downloaded by the browser.
	}

### Options ###

The `exportCsv()` function has 5 params:

1. `$data` - an array of data to export. This array should be of the format returned by a call to $this->MyModel->find('all');
2. `$fileName` (optional) - the name of the file to download. If blank, it will use a date-stamped name like export_2013-09-24.csv
3. `$maxExecutionSeconds` (optional) - if set, this will change the PHP max_execution_time. Useful when dealing with large amounts of data.
4. `$delimiter` (optional) - The delimiter for your CSV. Defaults to comma (,).
5. `$enclosure` (optional) - The enclosure for your CSV. Defaults to double-quote (").

## Example input / output ##

Lets say City `belongsTo` State, which `belongsTo` country. You might fetch data from the City model looking something like this:

	array(
		0 => array(
			'City' => array(
				'name' => 'Sydney',
				'population' => '4.6m'
			),
			'State' => array(
				'name' => 'NSW',
				'Country' => array(
					'name' => 'Australia',
				)
			)
		),
		1 => array(
			'City' => array(
				'name' => 'Melbourne',
				'population' => '4.1m'
			),
			'State' => array(
				'name' => 'VIC',
				'Country' => array(
					'name' => 'Australia',
				)
			)
		),
	)

And the export component will output a CSV like this:

<table cellpadding="7" >
	<tr>
		<th>City.name</th>
		<th>City.population</th>
		<th>State.name</th>
		<th>State.Country.name</th>
	</tr>
	<tr>
		<td>Sydney</td>
		<td>4.6m</td>
		<td>NSW</td>
		<td>Australia</td>
	</tr>
	<tr>
		<td>Melbourne</td>
		<td>4.1m</td>
		<td>VIC</td>
		<td>Australia</td>
	</tr>
</table>

## Supported CakePHP versions ##

Tested with CakePHP 2.3. Should work with all CakePHP 2.X.

## License ##

MIT - http://opensource.org/licenses/MIT