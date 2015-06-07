<?php

/**

 *	@author Dhru.com

 *	@APi kit version 2.0 March 01, 2012

 *	@Copyleft GPL 2001-2011, Dhru.com

 **/
require ('header.php');
include ('dhrufusionapi.class.php');
define("REQUESTFORMAT", "JSON");
define('DHRUFUSION_URL', "http://gsmeasy.biz/api/index.php");
define("USERNAME", "imeiunlock");
define("API_ACCESS_KEY", "DBN-88H-Q57-IKW-VO7-T5D-QQG-F8P");

$api = new DhruFusion();
// Debug on
$api->debug = true;


$para['ID'] = "44"; // got from 'imeiservicelist' [SERVICEID]
$request = $api->action('providerlist', $para);


echo '<PRE>';
print_r($request);
echo '</PRE>';
/*$student_info = array($request);

// creating object of SimpleXMLElement
$xml_student_info = new SimpleXMLElement("<?xml version=\"1.0\"?><student_info></student_info>");

// function call to convert array to xml
array_to_xml($student_info,$xml_student_info);

//saving generated xml file
$xml_student_info->asXML('d:/pro.xml');


// function defination to convert array to xml
function array_to_xml($student_info, &$xml_student_info) {
    foreach($student_info as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_student_info->addChild("$key");
                array_to_xml($value, $subnode);
            }
            else{
                $subnode = $xml_student_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }
        else {
            $xml_student_info->addChild("$key","$value");
        }
    }
}
echo '</PRE>';*/
?>