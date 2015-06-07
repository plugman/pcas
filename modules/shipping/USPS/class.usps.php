<?php
class USPS
{
	var $user_id;
	var $password;
	var $api;
	var $request_xml;
	var $package_index = 0;
	var $current_result = array();

	var $country_list = array(
	"Great Britain",
	"United States",
	"Afghanistan",
	"Albania",
	"Algeria",
	"Andorra",
	"Angola",
	"Anguilla",
	"Antigua and Barbadua",
	"Argentina",
	"Armenia",
	"Aruba",
	"Ascension",
	"Australia",
	"Austria",
	"Azerbaijan",
	"Bahamas",
	"Bahrain",
	"Bangladesh",
	"Barbados",
	"Belarus",
	"Belgium",
	"Belize",
	"Benin",
	"Bermuda",
	"Bhutan",
	"Bolivia",
	"Bosnia-Herzegovina",
	"Botswana",
	"Brazil",
	"British Virgin Islands",
	"Brunei Darussalam",
	"Bulgaria",
	"Burkina Faso",
	"Burma",
	"Burundi",
	"Cambodia",
	"Cameroon",
	"Canada",
	"Cape Verde",
	"Cayman Islands",
	"Central African Republic",
	"Chad",
	"Chile",
	"China",
	"Colombia",
	"Comoros",
	"Democratic Republic of the Congo",
	"Republic of the Congo",
	"Costa Rica",
	"Ivory Coast",
	"Croatia",
	"Cuba",
	"Cyprus",
	"Czech Republic",
	"Denmark",
	"Djibouti",
	"Dominica",
	"Dominican Republic",
	"Ecuador",
	"Egypt",
	"El Salvador",
	"Equitorial Guinea",
	"Eritrea",
	"Estonia",
	"Ethiopia",
	"Falkland Islands",
	"Faroe Islands",
	"Fiji",
	"Finland",
	"France",
	"French Guiana",
	"French Polynesia",
	"Gabon",
	"Gambia",
	"Republic of Georgia",
	"Germany",
	"Ghana",
	"Gibraltar",
	"Great Britain and Northern Ireland",
	"Greece",
	"Greenland",
	"Grenanda",
	"Guadeloupe",
	"Guatemala",
	"Guinea",
	"Guinea-Bissau",
	"Guyana",
	"Haiti",
	"Honduras",
	"Hong Kong",
	"Hungary",
	"Iceland",
	"India",
	"Indonesia",
	"Iran",
	"Iraq",
	"Ireland",
	"Israel",
	"Italy",
	"Jamaica",
	"Japan",
	"Jordan",
	"Kazakhstan",
	"Kenya",
	"Kiribati",
	"Democratic People's Republic of Korea",
	"Republic of Korea",
	"Kuwait",
	"Kyrgyzstan",
	"Laos",
	"Latvia",
	"Lebanon",
	"Lesotho",
	"Liberia",
	"Libya",
	"Liechtenstein",
	"Lithuania",
	"Luxembourg",
	"Macao",
	"Macedonia",
	"Madagascar",
	"Malawi",
	"Malaysia",
	"Maldives",
	"Mali",
	"Malta",
	"Martinique",
	"Mauritania",
	"Mauritius",
	"Mexico",
	"Moldova",
	"Mongolia",
	"Montserrat",
	"Morocco",
	"Mozambique",
	"Namibia",
	"Nauru",
	"Nepal",
	"Netherlands",
	"Netherlands Antilles",
	"New Caledonia",
	"New Zealand",
	"Nicaragua",
	"Niger",
	"Nigeria",
	"Norway",
	"Oman",
	"Pakistan",
	"Panama",
	"Papua New Guinea",
	"Paraguay",
	"Peru",
	"Philippines",
	"Pitcairn Island",
	"Poland",
	"Portugal",
	"Qatar",
	"Reunion",
	"Romania",
	"Russia",
	"Rwanda",
	"St. Christopher and Nevis",
	"St. Helena",
	"St. Lucia",
	"St. Pierre and Miquelon",
	"St. Vincent and the Grenadines",
	"San Marino",
	"Sao Tome and Principe",
	"Saudi Arabia",
	"Senegal",
	"Serbia-Montenegro",
	"Seychelles",
	"Sierra Leone",
	"Singapore",
	"Slovak Republic",
	"Slovenia",
	"Solomon Islands",
	"Somalia",
	"South Africa",
	"Spain",
	"Sri Lanka",
	"Sudan",
	"Suriname",
	"Swaziland",
	"Sweden",
	"Switzerland",
	"Syria",
	"Taiwan",
	"Tajikistan",
	"Tanzania",
	"Thailand",
	"Togo",
	"Tonga",
	"Trinidad and Tobago",
	"Tristan de Cunha",
	"Tunisia",
	"Turkey",
	"Turkmenistan",
	"Turks and Caicos Islands",
	"Tuvalu",
	"Uganda",
	"Ukraine",
	"United Arab Emirates",
	"Uruguay",
	"Uzbekistan",
	"Vanuatu",
	"Vatican City",
	"Venezuela",
	"Vietnam",
	"Wallis and Futuna Islands",
	"Western Samoa",
	"Yemen",
	"Zambia",
	"Zimbabwe");

	function debug($error){
	
		global $module;
		if($module['debug']==1) { echo $error; exit; }
	
	}

	function USPS($user_id, $password, $api = 'RateV3') {
		if(empty($user_id) || empty($password)) {
		
			$this->debug("USPS ERROR: User ID or Password was empty. Please make sure this has been added correctly to the admin side of your store.");
			return false;
			
		} else {
			$this->user_id = $user_id;
			$this->password = $password;
			$this->api = $api;
			$this->request_xml = '<' . $api . 'Request USERID="' . $user_id . '" PASSWORD="' . $password . '">';
		}
	}

	function reset() {
		$this->api = '';
		$this->current_result = '';
		$this->request_xml = '';
		$this->package_index = 0;
	}

	function add_package($attribs = '') {
		
		if(!is_array($attribs)) { 
			$this->debug("USPS ERROR: Package array was empty.");
			return false;
		}

		//Check to make sure array has required values for API
		if($this->api == 'RateV3') {
			
			if(empty($attribs['service']) || empty($attribs['ziporigin']) || empty($attribs['zip_dest']) || (empty($attribs['size']) && strtolower($attribs['service'])!=="first class")) {

				echo "USPS ERROR: One of the following variables was empty. service = '".$attribs['service']."', ziporigin = '".$attribs['ziporigin']."', zip_dest = '".$attribs['zip_dest']."', size = '".$attribs['size']."'";
				return false;
			
			 }
			
			//Check service type
			if(empty($attribs['service'])) { 
				$this->debug("USPS ERROR: Service variable was empty.");

				return false;
			} else {
				
				switch(strtolower($attribs['service'])){
					case 'express':
					case 'first class':
					case 'priority':
					case 'parcel':
					case 'bpm':
					case 'library':
					case 'media':
					case 'all':
						break;
					default:
						$this->debug("USPS ERROR: Service variable was not recognised.");
						return false;
				}
			}

			//Check ZIP codes
			if(!isset($attribs['ziporigin'])) { $this->debug("USPS ERROR: Zip Origin was not set."); return false; }
			if(!isset($attribs['zip_dest'])) { $this->debug("USPS ERROR: Zip Destination was not set."); return false; }

			//Check weight
			if($attribs['pounds'] + $attribs['ounces'] == 0) {  $this->debug("USPS ERROR: No weight set."); return false; }
			

			//Check container for Express and Priority
			
			if(strtolower($attribs['service']) == 'express' || strtolower($attribs['service']) == 'priority') {
				if(!isset($attribs['container'])) {  
					$this->debug("USPS ERROR: Container for express or priority post was not set."); return false; 
				} /*else {
					switch(strtoupper($attribs['container'])) {
						case 'VARIABLE':
						case 'FLAT RATE BOX':
						case 'FLAT RATE ENVELOPE':
						case 'RECTANGULAR': 
						case 'NONRECTANGULAR':
							break;
						default:
							$this->debug("USPS ERROR: Container not recognised for ".$attribs['service'].".");
							return false;
					}
				}
				*/
			} 
			
			//Check size
			//if(!$attribs['size']) { 
			if(!$attribs['size'] && strtolower($attribs['service'])!=='first class') {
				$this->debug("USPS ERROR: Size empty."); return false;
			} elseif (strtolower($attribs['service'])!=='first class') {
				switch(strtolower($attribs['size'])){
					case 'regular':
					case 'large':
					case 'oversize':
						break;
					default:
						$this->debug("USPS ERROR: Size not recognised.");
						return false;
				}
			}

			//Add the package to the XML request
			$this->request_xml .= '<Package ID="' . $this->package_index . '">';
			
			$this->package_index++;
			
			$this->request_xml .= '<Service>' . strtoupper($attribs['service']) . '</Service>';
			
			if(strtolower($attribs['service']) == 'first class') {	
				$this->request_xml .= '<FirstClassMailType>' . $attribs['first_class_mail_type'] . '</FirstClassMailType>';
			}
			$this->request_xml .= '<ZipOrigination>' . substr(trim($attribs['ziporigin']),0,5) . '</ZipOrigination>';
			$this->request_xml .= '<ZipDestination>' . substr(trim($attribs['zip_dest']),0,5) . '</ZipDestination>';
			$this->request_xml .= '<Pounds>' . $attribs['pounds'] . '</Pounds>';
			$this->request_xml .= '<Ounces>' . $attribs['ounces'] . '</Ounces>';
			if(strtoupper($attribs['container'])!=="VARIABLE") {
				$this->request_xml .= '<Container>' . strtoupper($attribs['container']) . '</Container>';
			}
			$this->request_xml .= '<Size>' . ucfirst(strtolower($attribs['size'])) . '</Size>';
			
			if(strtolower($attribs['service']) == 'priority' && strtolower($attribs['size']) == 'large') {
				
				$this->request_xml .= '<Width>' .$attribs['width']. '</Width>';
				$this->request_xml .= '<Length>' .$attribs['length']. '</Length>';
				$this->request_xml .= '<Height>' .$attribs['height']. '</Height>';
				if(empty($attribs['width']) || empty($attribs['length']) || empty($attribs['height'])) { $this->debug("USPS ERROR: Width, length &amp; height for nonrectangular or rectangular priority mail not set."); return false; }
				
				if($attribs['container'] == "NONRECTANGULAR") {
				$this->request_xml .= '<Girth>' .$attribs['girth']. '</Girth>';
					if(empty($attribs['girth'])) { $this->debug("USPS ERROR: Girth for nonrectangular priority mail not set."); return false; }
				}
				
			}
			
			if(strtolower($attribs['service']) == 'all' || strtolower($attribs['service']) == 'parcel'  || (strtolower($attribs['service']) == 'first class' && $attribs['first_class_mail_type'] == 'LETTER') || strtolower($attribs['service']) == 'first class' && $attribs['first_class_mail_type'] == 'FLAT') {
				
				if(empty($attribs['machinable'])) { 
					$this->debug("USPS ERROR: Machinable variable for parcel service not set."); return false;
				}
				$this->request_xml .= '<Machinable>' . $attribs['machinable'] . '</Machinable>';
			}
			
			$this->request_xml .= '</Package>';
		
		} elseif($this->api == 'IntlRate') {
			
			if(!isset($attribs['pounds'])) { 
				$this->debug("USPS ERROR: International pounds not set."); 
				return false;
			}
			
			if(!isset($attribs['ounces'])) { 
				$this->debug("USPS ERROR: International ounces not set."); 
				return false; 
			}

			if(!$attribs['mail_type']) { 
				$this->debug("USPS ERROR: International mail type not set.");
				return false; 
			} else {
				switch(strtolower($attribs['mail_type'])) {
					case 'package':
					case 'postcards or aerogrammes':
					case 'matter for the blind':
					case 'envelope':
						break;
					default:
						$this->debug("USPS ERROR: Mail type not recognised.");
						return false;
				}
			}

			if(!isset($attribs['country'])) { 
				$this->debug("USPS ERROR: Country not set."); 
				return false; 
			}
			if(!in_array($attribs['country'], $this->country_list)) {
				$this->debug("USPS ERROR: Country not in list."); 
				return false; 
			}

			//Add the package to the XML request
			$this->request_xml .= '<Package ID="' . $this->package_index . '">';
			$this->package_index++;	
			$this->request_xml .= '<Pounds>' . $attribs['pounds'] . '</Pounds>';
			$this->request_xml .= '<Ounces>' . $attribs['ounces'] . '</Ounces>';
			$this->request_xml .= '<MailType>' . $attribs['mail_type'] . '</MailType>';
			$this->request_xml .= '<ValueOfContents>' .$attribs['value'] . '</ValueOfContents>';
			$this->request_xml .= '<Country>' . $attribs['country'] . '</Country>';
			$this->request_xml .= '</Package>';
		}

		return true;
	}

	function submit_request() {
		global $module, $config;

		$this->request_xml .= '</' . $this->api . 'Request>';

		//Create a cURL instance and retrieve XML response
		if(!is_callable("curl_exec")) die("USPS::submit_request: curl_exec is uncallable");
		/*
		if($module['test']==1){
			//$USPSURL = "http://testing.shippingapis.com/ShippingAPI.dll";
			//$USPSURL = "http://stg-production.shippingapis.com/shippingapi.dll";
		} else {
			//$USPSURL = "http://production.shippingapis.com/ShippingAPI.dll";
		}
		*/
		$USPSURL = "http://production.shippingapis.com/ShippingAPI.dll";
		$ch = curl_init($USPSURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "API=" . $this->api . "&XML=" . $this->request_xml);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		if($config['proxy']==1){
	  		curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
	  	}

		$return_xml = curl_exec($ch);
		
		if (stristr($return_xml, 'Authorization failure')) {
			
			echo "<strong>Authorization Error connecting to United States Postal Service Server:</strong> ".$USPSURL."<p>Please register at <a href='http://www.usps.com/webtools/'>http://www.usps.com/webtools/</a> and enter the correct username and password in the ImeiUnlock control panel. Please also make sure that USPS has granted your account access to the 'production server'.</p>
<p>Please go back and verify your login information.  Remember, you must have your USPS issued username entered and <i>something</i> in the password box.</p>";
			exit;
		
		} else if (stristr($return_xml, 'error') && !stristr($return_xml, 'Service not available.')) {
			
			preg_match('#<Description>(.+)</Description>#iu', $return_xml, $matches);
			echo 'USPS Error: '.$matches[1];
			if ($module['debug']) {
				echo "<hr /><strong>Request XML:</strong><hr />".nl2br(htmlspecialchars($this->request_xml)); 
				echo "<hr /><strong>Return XML:</strong><hr />".nl2br(htmlspecialchars($return_xml));
			}
			exit;
		}
		
		$xml = new SimpleXMLElement($return_xml);
				
		if ($xml->getName() == 'RateV3Response') {
			if (isset($xml->Package->Error)) {
				$this->current_result[0]['Error']['Description'] = (string)$xml->Package->Error;
				
			} else if (isset($xml->Package->ZipOrigination)) {
				foreach ($xml->Package->Postage as $key => $service) {
					$x_mailservice	= (string)$service->MailService;
					$x_rate			= (string)$service->Rate;
					
					$this->current_result[0]['Postage'][$x_mailservice] = $x_rate;
				}
			}
		} else if ($xml->getName() == 'IntlRateResponse') {
			if (isset($xml->Package->Error)) {
				$this->current_result[0]['Error']['Description'] = (string)$xml->Package->Error;
				
			} else if (isset($xml->Package->Service)) {
				foreach ($xml->Package->Service as $key => $service) {					
					$x_mailservice	= (string)$service->SvcDescription;
					$x_rate			= (string)$service->Postage;
					
					$this->current_result[0]['Postage'][$x_mailservice] = $x_rate;
				}
			}
		}
		return true;
	}

	function get_rates($package_id = 0) {
		if ($this->current_result[$package_id]['Error']) return $this->current_result[$package_id]['Error']['Description'];
#		if ($this->api == 'RateV3') {
			return $this->current_result[$package_id]['Postage'];
#		} else if ($this->api == 'IntlRate') {
			//SvcDescription and Postage
#			$result = array();

#			foreach($this->current_result[$package_id]['Service'] as $service){
#				$key = $service['SvcDescription'];
#				$result[$key] = $service['Postage'];
#			}
			return $result;
#		}
#		return false;
	}

	function get_prohibitions($package_id) {
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['Prohibitions'];
		else return false;
	}

	function get_restrictions($package_id) {
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['Restrictions'];
		else return false;
	}

	function get_observations($package_id) {
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['Observations'];
		else return false;
	}

	function get_areas_served($package_id) {
		if($this->api == 'IntlRate') return $this->current_result[$package_id]['AreasServed'];
		else return false;
	}

	function get_package_error($package_id) {
		if($this->current_result[$package_id]['Error']) return $this->current_result[$package_id]['Error'];
	}
}

?>