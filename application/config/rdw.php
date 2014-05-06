<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Base API URL
|--------------------------------------------------------------------------
|
| URL to the RDW opendata API root
|
| Normally this should not need changing, but you can find it in the RDW's
| Azure DataMarket "details" page.
|
*/
$config['rdw_base_url']	= 'https://api.datamarket.azure.com/Data.ashx/opendata.rdw/VRTG.Open.Data/v1/KENT_VRTG_O_DAT';

/*
|--------------------------------------------------------------------------
| Your key
|--------------------------------------------------------------------------
|
| Yur personal access key, can be found on the RDW's Azure DataMarket 
| "use" page or on your personal profile page for the Azure DataMarket.
|
*/
$config['rdw_key']      = 'PutYourAPIKeyHere';

/* End of file rdw.php */
/* Location: ./application/config/rdw.php */
