# CI_AzureRDW

A CodeIgniter library to access RDW automobile data (public Dutch national vehicle database)

The Dutch governmental agency for vehicle registration (RDW) has opened up the national license plate register for querying, using the Microsoft Azure Marketplace DataMarket (https://datamarket.azure.com/).
This library allows you to add license plate lookup functionality to your CodeIgniter application

## Install

* Download the zipfile from the [releases](https://github.com/kathmann/CI_AzureRDW/releases) page
* Extract the three folders in the zipfile to your CI application root folder

Or: 
 
* Checkout the source: `git clone git://github.com/kathmann/CI_AzureRDW.git` and install it yourself.

## Configuration

After installation your application's config directory contains a new config file, **rdw.php**.
Open this file in your favorite editor and insert your own key in the **$config['rdw_key']** variable.
Save. You're done.

## Getting Started

To use the library, load it in your own controller's constructor:

```php
public function __construct()
{
	parent::__construct();
	$this->load->library('rdw');
}
```

Now all you have to do is initiate the Rdw class and you're ready to call the lookup methods:

```php
$parser = new Rdw();
```

## License

This library is licenced under the GNU Lesser General Public License version 3.
Share and enjoy, open source works.

## Credits

This library was initially based on the example PHP code by Elisa Flasko of Microsoft, as outlined in her [blog post](http://blogs.msdn.com/b/datamarket/archive/2011/05/27/an-introduction-to-datamarket-with-php.aspx) of 27 May 2011.
The RDW data wrangling, CI library structure etc. is my own fiddling.
