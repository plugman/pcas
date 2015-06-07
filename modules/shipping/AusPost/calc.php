<?php

function AusPost() {
	
	$module		= fetchDbConfig('AusPost');
	$taxVal		= taxRate($module['tax']);
	
	if ($module['status'] == true && function_exists('curl_init')) {
		$ch = curl_init('http://drc.edeliver.com.au/ratecalc.asp');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		## Build request elements
		$request = array(
			'Height'				=> $module['height']*10,
			'Length'				=> $module['length']*10,
			'Width'					=> $module['width']*10,
			'Weight'				=> $GLOBALS['totalWeight']*1000,
			'Quantity'				=> 1,
			'Pickup_Postcode'		=> $module['postcode'],
			'Destination_Postcode'	=> $GLOBALS['basket']['delInf']['postcode'],
			'Country'				=> getCountryFormat($GLOBALS['basket']['delInf']['country'], 'id', 'iso'),
		);
		
		if ($request['Country'] == 'AU') {
			$options = array(
				'STANDARD'	=> 'Standard Delivery',
				'EXPRESS'	=> 'Express Delivery',
			);
		} else {
			$options = array(
				'Air'	=> 'Air Mail',
				'Sea'	=> 'Sea Mail',
				'ECI_D'	=> 'Express Courier International (Document)',
				'ECI_M'	=> 'Express Courier International (Mechandise)',
				'EPI'	=> 'Express Post International',
			);
		}		
		
		$i = 0;
		
		foreach ($options as $option => $name) {
			$ch = curl_init('http://drc.edeliver.com.au/ratecalc.asp');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 60);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			
			## Set the Service_Type
			$request['Service_Type'] = $option;
			
			if (function_exists('http_build_query')) {
				$requestString = http_build_query($request, '', '&');
			} else {
				foreach ($request as $key => $val)  {
					$array[] = sprintf('%s=%s', $key, $val);
				}
				$requestString = implode('&', $array);
				unset($array);
			}
			curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
			$returnData = curl_exec($ch);
			
			$returnData = str_replace("\n", '&', $returnData);
			parse_str($returnData, $result);
			
			if ($result['charge'] > 0) {
				$result['charge']+=$module['handling'];
				$out[$i] = array(
					'value'		=> $result['charge'],
					'desc'		=> sprintf('%s %s', priceFormat($result['charge'], true), $name),
					'method'	=> sprintf('Australia Post (%s) %s', $name, priceFormat($result['charge'], true)),
					'taxId'		=> $module['tax'],
					'taxAmount' => $taxVal > 0 ? $taxVal/100 * $result['charge'] : 0,
				);
				$i++;
			}
			curl_close($ch);
		}
		
		return (isset($out) && is_array($out)) ? $out : false;
	}
	return false;
}

$shipArray[] = AusPost();
?>