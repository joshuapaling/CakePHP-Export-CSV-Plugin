# WORK IN PROGRESS - NOT YET READY FOR USE!!! #




# CakePHP Export Plugin #

A Export plugin for CakePHP 2.X - pass it an array which is the result of a CakePHP Model find('all') call, and it'll flatten it and download it as a .csv file.

Exporting as csv is nothing special - the cool thing is that you can pass it a multi-level nested array, and it'll handle your belongsTo associations gracefully.

As for hasMany (and other) associations, it'll just include them inside one csv 'cell' as a json version of the array. I'm yet to think of a graceful solution for handling nested hasMany's - though I'm open to suggestions, or pull requests.


## Introduction ##

The plugin is quick and easy to install. The installation instructions are somewhat long - but that's just to provide the clarity I wished stuff had when I was a CakePHP n00b. If you're not new to Cake, then install the component and skip to the final step.


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

### 4. Start Exporting your Data! ###

	public function export_data() {
		$data = $this->myModel->find('all');
		$this->Export->exportCsv($data);
	}


## License ##

MIT - http://opensource.org/licenses/MIT