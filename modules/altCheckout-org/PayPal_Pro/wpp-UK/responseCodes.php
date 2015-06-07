<?php
if (!defined('CC_INI_SET')) die("Access Denied");

function basicResponse($in) {
	switch ($in) {
		case "Y":
			$out = "Matches.";
			break;
		case "N":
			$out = "Does not match.";
			break;
		case "X":
			$out = "The cardholder's bank does not support this service.";
			break;
		default:
			$out = "No Response.";
	}
	return $out;
}