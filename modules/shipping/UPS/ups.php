<?php
/*
+--------------------------------------------------------------------------
Copyright (c) 2000, Jason Costomiris
All rights reserved.

Props to shawnblue@radiotakeover.com for the original idea which I have cannibalized herein.  You go boy.

Don't be scared, it's just a BSD-ish license.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:

1. Redistributions of source code must retain the above copyright notice,
   this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright notice,
   this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. All advertising materials mentioning features or use of this software
   must display the following acknowledgement:
   This product includes software developed by Jason Costomiris.
4. The name of the author may not be used to endorse or promote products
   derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE AUTHOR COPYRIGHT HOLDERS AND CONTRIBUTORS
``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/


  class Ups {

    function upsProduct($prod){
       /*

     1DM == Next Day Air Early AM
     1DA == Next Day Air
     1DP == Next Day Air Saver
     2DM == 2nd Day Air Early AM
     2DA == 2nd Day Air
     3DS == 3 Day Select
     GND == Ground
     STD == Canada Standard
     XPR == Worldwide Express
     XDM == Worldwide Express Plus
     XPD == Worldwide Expedited
      
    */
      $this->upsProductCode = $prod;
    }
    
    function origin($postal, $country){
      $this->originPostalCode = $postal;
      $this->originCountryCode = $country;
    }

    function dest($postal, $country){
      $this->destPostalCode = $postal;
          $this->destCountryCode = $country;
    }

    function rate($foo){
      switch(strtoupper($foo)){
        case "RDP":
          $this->rateCode = "Regular+Daily+Pickup";
          break;
        case "OCA":
          $this->rateCode = "On+Call+Air";
          break;
        case "OTP":
          $this->rateCode = "One+Time+Pickup";
          break;
        case "LC":
          $this->rateCode = "Letter+Center";
          break;
        case "CC":
          $this->rateCode = "Customer+Counter";
          break;
      }
    }

    function container($foo){
          switch(strtoupper($foo)){
        case "CP":            // Customer Packaging
          $this->containerCode = "00";
          break;
        case "ULE":        // UPS Letter Envelope
          $this->containerCode = "01";        
          break;
        case "UT":            // UPS Tube
          $this->containerCode = "03";
          break;
        case "UEB":            // UPS Express Box
          $this->containerCode = "21";
          break;
        case "UW25":        // UPS Worldwide 25 kilo
          $this->containerCode = "24";
          break;
        case "UW10":        // UPS Worldwide 10 kilo
          $this->containerCode = "25";
          break;
      }
    }
    
    function weight($foo){
      $this->packageWeight = $foo;
    }

    function rescom($foo){
          switch(strtoupper($foo)){
        case "RES":            // Residential Address
          $this->resComCode = "1";
          break;
        case "COM":            // Commercial Address
          $this->resComCode = "0";
          break;
          }
    }

	function getQuote(){
	
		global $config;
	
		$upsAction = "3"; // You want 3.  Don't change unless you are sure.
		$url = join("&",
			array("http://www.ups.com/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes",
				"10_action=$upsAction",
				"13_product=$this->upsProductCode",
				"14_origCountry=$this->originCountryCode",
				"15_origPostal=$this->originPostalCode",
				"19_destPostal=$this->destPostalCode",
				"22_destCountry=$this->destCountryCode",
				"23_weight=$this->packageWeight",
				"47_rate_chart=$this->rateCode",
				"48_container=$this->containerCode",
				"49_residential=$this->resComCode"
			)
		);
		
		if (function_exists('curl_init')) {
			
			$ch = curl_init();
			
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			if($config['proxy']==1){
	  			curl_setopt ($ch, CURLOPT_PROXY, $config['proxyHost'].":".$config['proxyPort']); 
	  		}
			
			$data = curl_exec($ch);
			
			if (!empty($data)) {				
				$dataline = explode("\n", $data);
				foreach ($dataline as $result) {
					if (strstr($result, 'Origin postal code must have five digits')) {
						echo 'Origin postal code must have five digits to use UPS. Please update this in the module settings.';
						exit;
					} else if (strstr($result, 'Unsupported country specified')) {
						echo 'Unsupported country specified to use UPS. Please update this in the general settings.';
						exit;
					}
					$result = explode('%', $result);
					$errcode = substr($result[0], -1);
					switch($errcode){
						case 3:
							$returnval = $result[8];
							break;
						case 4:
							$returnval = $result[8];
							break;
						case 5:
							$returnval = $result[1];
							break;
						case 6:
							$returnval = $result[1];
							break;
					}
				}
			}
		} else {
			$fp = fopen($url, 'r');
			while(!feof($fp)) {
				$result = fgets($fp, 500);
				if (strstr($result, 'Origin postal code must have five digits')) {
					echo 'Origin postal code must have five digits to use UPS. Please update this in the module settings.';
					exit;
				} else if (strstr($result, 'Unsupported country specified')) {
					echo 'Unsupported country specified to use UPS. Please update this in the general settings.';
					exit;
				}
				$result = explode('%', $result);
				$errcode = substr($result[0], -1);
				switch($errcode){
					case 3:
						$returnval = $result[8];
						break;
					case 4:
						$returnval = $result[8];
						break;
					case 5:
						$returnval = $result[1];
						break;
					case 6:
						$returnval = $result[1];
						break;
				}
			}
			fclose($fp);
		}
		return (!$returnval) ? 'error' : $returnval;
	}
}

?>