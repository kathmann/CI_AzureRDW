<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class RdwCar
{
	public $Aantalcilinders;
	public $Aantalstaanplaatsen;
	public $Aantalzitplaatsen;
	public $BPM;
	public $Brandstofverbruikbuitenweg;
	public $Brandstofverbruikgecombineerd;
	public $Brandstofverbruikstad;
	public $Catalogusprijs;
	public $Cilinderinhoud;
	public $CO2uitstootgecombineerd;
	public $Datumaanvangtenaamstelling;
	public $DatumeersteafgifteNederland;
	public $Datumeerstetoelating;
	public $Eerstekleur;
	public $G3installatie;
	public $Handelsbenaming;
	public $Hoofdbrandstof;
	public $Inrichting;
	public $Kenteken;
	public $Laadvermogen;
	public $Massaleegvoertuig;
	public $Massarijklaar;
	public $Maximaleconstructiesnelheid;
	public $Maximumtetrekkenmassaautonoomgeremd;
	public $Maximumtetrekkenmassageremd;
	public $Maximumtetrekkenmassamiddenasgeremd;
	public $Maximumtetrekkenmassaongeremd;
	public $Maximumtetrekkenmassaopleggergeremd;
	public $Merk;
	public $Milieuclassificatie;
	public $Nevenbrandstof;
	public $Retrofitroetfilter;
	public $Toegestanemaximummassavoertuig;
	public $Tweedekleur;
	public $Vermogen;
	public $Vermogenbromsnorfiets;
	public $VervaldatumAPK;
	public $Voertuigsoort;
	public $Wachtopkeuren;
	public $WAMverzekerdgeregistreerd;
	public $Zuinigheidslabel;

	public function listProperties()
	{
		return get_object_vars($this);
	}
}

class Rdw
{
	// the reference to the core CodeIgniter object
	private $_CI;
	
	// the config options
	private $_uri;
	private $_key;
	
	// the actual URI used
	private $actualUri;
	
	// holders for use in the XML processing
	private $entries = array();
	private $count = 0;
	private $contentOpen = false;
	private $currentTag = "";
	
	// the result row divider
	const rowKey = '/FEED/ENTRY/ID';
	
	// the base data location definition
	const keyBase = '/FEED/ENTRY/CONTENT/M:PROPERTIES';
	
	// the data model properties
	private $properties = array();
	
	public function __construct()
	{
		// reference the core CodeIgniter instance
		$this->_CI =& get_instance();
		
		// read the config file
		$this->_CI->config->load('rdw');
		
		// read and store the config variables
		$this->_uri = $this->_CI->config->item('rdw_base_url');
		$this->_key = $this->_CI->config->item('rdw_key');
		
		// read the data model properties and generate the location strings
		$temp = new RdwCar();
		$this->properties = $temp->listProperties();
		foreach ($this->properties as $key => $value)
		{
			$this->properties[$key] = self::keyBase . '/D:' . strtoupper($key);
		}
		unset($temp);
	}
	
	private function OpenTag($xmlParser, $data)
	{
		$this->currentTag .= "/$data";
	}

	private function CloseTag($xmlParser, $data)
	{
		$tagKey = strrpos($this->currentTag, '/');
		$this->currentTag = substr($this->currentTag, 0, $tagKey);
	}
	
	private function DataHandler($xmlParser, $data)
	{
		// read the current tag
		$thisTag = strtoupper($this->currentTag);
		
		// did we encounter a result row divider?
		if ($thisTag == strtoupper(self::rowKey))
		{
			// is this the closer for an open element we're processing?
			if ($this->contentOpen)
			{
				// yes, close the open element marker 
				$this->count++;
				$this->contentOpen = false;
			}
			else
			{
				// no, start a new data object
				$this->entries[$this->count] = new RdwCar();
				$this->contentOpen = true;
			}
		}
		else 
		{
			// try to find a match with the data model properties
			foreach ($this->properties as $key => $value)
			{
				if ($thisTag == strtoupper($value))
				{
					$this->entries[$this->count]->$key = $data;
				}
			}
		}
	}
	
	private function ParseXML($xml)
	{
		$xmlParser = xml_parser_create(); 
		xml_set_element_handler($xmlParser, "self::OpenTag", "self::CloseTag"); 
		xml_set_character_data_handler($xmlParser, "self::DataHandler"); 

		if(!(xml_parse($xmlParser, $xml)))
		{ 
			die("Error on line " . xml_get_current_line_number($xmlParser)); 
		} 
		xml_parser_free($xmlParser); 
	}
	
	private function uriResponse()
	{
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $this->actualUri); 
		curl_setopt($ch, CURLOPT_USERPWD, ":" . $this->_key); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,  true); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$response = curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	public function GetEntries($license)
	{
		// update the URI to use
		$this->actualUri = $this->_uri . '?$filter=Kenteken%20eq%20%27' . $license . '%27';
		
		self::ParseXML(self::uriResponse());
		return $this->entries;
	}
}

/* End of file Rdw.php */
/* Location: ./application/libraries/Rdw.php */