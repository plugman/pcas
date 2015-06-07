<?php 
//header("Access-Control-Allow-Origin: *");
	class API {

		function __construct(){
		}
		
		public static function send($url, $type, $data){
			$header = array(
				'Content_type: application/x-www-form-urlencoded'
			);

			$return = array();
		 	$return['url'] = self::generateUrl($url, $data);
			$return['header'] = $header;
			$return['data'] = $data;
			
			try{
				 $return['result'] = self::curl( $return['url'], $type, $header, $data );
				 
			}catch(Exception $e){
				$return['error'] = $e->getMessage();
			}
			die(json_encode($return));
		}	

		private static function generateUrl($url, $data){
			$endpoint = 'https://api.instagram.com/v1';
			//$url = $endpoint . $url;
			 $url = $endpoint . $url . '?';
			$keys = array_keys($data);
			 foreach ($keys as $key ) {
			 	$url .= $key . '=' . $data[$key] . '&';
			 }
		
			return $url;
		}

		private static function curl($url, $type, $header, $data = null){
			//echo $url;die();
		    if(function_exists('curl_init')){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        $output = curl_exec($ch);
        echo curl_error($ch);
        curl_close($ch);
        return json_decode($output);
    }else{
        return json_decode(file_get_contents($url));
    }
		}
	}

	if(isset($_POST)){
		$url = $_POST['url'];
		$type = $_POST['method'];
		$data = $_POST['params'];
		API::send( $url, $type, $data );
	}
 ?>