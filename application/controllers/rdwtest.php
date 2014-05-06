<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rdwtest extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('rdw');
	}
	
	public function lookup($license = NULL)
	{
		if ($license == NULL)
		{
			echo "Error: no license sepcified.";
		}
		else 
		{
			// clean up the license plate number
			$license = preg_replace('/[^A-Z0-9]/', '', strtoupper($license)); 

			$parser = new Rdw();
			$entries = $parser->GetEntries($license);
			
			// Print the results in an array dump
			echo '<pre>';
			print_r($entries);
			echo '</pre>';
		}
	}
}

/* End of file rdwtest.php */
/* Location: ./application/controllers/rdwtest.php */