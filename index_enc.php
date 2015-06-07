<?php

$debugTime['start'] = microtime();

header("X-Haiku: Haikus are easy, but sometimes they don't make sense. Refrigerator");

header("X-GLaDOS: You just keep on trying, til you run out of cake.");



require_once ("includes" . CC_DS . "functions.inc.php");

require_once ("classes" . CC_DS . "db" . CC_DS . "db.php");

$db = new db();

require_once ("classes" . CC_DS . "cache" . CC_DS . "cache.php");

$config = fetchdbconfig("config");

if (!$config['offLine'] && !(!$config['offLineAllowAdmin']))

{

		$offlineContent = false;

		$offlineFiles = glob("offline.{php,htm,html}", GLOB_BRACE);

		if (!empty($offlineFiles) || is_array($offlineFiles))

		{

				foreach ($offlineFiles as $file)

				{

						include ($file);

						exit();

				}

		}

		echo stripslashes(base64_decode($config['offLineContent']));

		exit();

}

require_once ("classes" . CC_DS . "xtpl" . CC_DS . "xtpl.php");

if ($_REQUEST['_g'] !== "rm")

{

		require_once ("includes" . CC_DS . "sef_urls.inc.php");

		require_once ("includes" . CC_DS . "sslSwitch.inc.php");

		require_once ("classes" . CC_DS . "session" . CC_DS . "cc_session.php");

		$cc_session = new session();

		$lang = getlang("common.inc.php");

}

require_once ("includes" . CC_DS . "currencyVars.inc.php");

switch ($_REQUEST['_g'])

{

		case "ajax":

		case "json":

		case "xmlhttp":

				$skipload = true;

				require_once ("xml.php");

				exit();

		case "co":

				require_once ("includes" . CC_DS . "global" . CC_DS . "cart.inc.php");

				break;

		case "sw":

				require_once ("includes" . CC_DS . "global" . CC_DS . "switch.inc.php");

				break;

		case "dl":

				require_once ("includes" . CC_DS . "global" . CC_DS . "download.inc.php");

				break;

		case "ex":

				require_once ("includes" . CC_DS . "global" . CC_DS . "extra.inc.php");

				exit();

		case "rm":

				require_once ("includes" . CC_DS . "remote" . CC_DS . "remote.inc.php");

				exit();

		case "cs":

				$decodedPath = get_magic_quotes_gpc() ? stripslashes(urldecode($_GET['_p'])):

				urldecode($_GET['_p']);

				if (in_array($decodedPath, $allowed_modules))

				{

						include_once ($decodedPath);

						exit();

				}

		default:

				require_once ("includes" . CC_DS . "global" . CC_DS . "index.inc.php");

}

if ($config['debug'])

{

		$debug = "<div style='margin-top: 15px; font-family: Courier New, Courier, mono; border: 1px dashed #666; padding: 10px; color: #000; background: #FFF'>";

		$debug .= "<strong>\$_POST Variables:</strong><br />" . cc_print_array($_POST) . "<hr size=1 />";

		$debug .= "<strong>\$_GET Variables:</strong><br />" . cc_print_array($_GET) . "<hr size=1 />";

		$debug .= "<strong>\$_COOKIE Variables:</strong><br />" . cc_print_array($_COOKIE) . "<hr size=1  />";

		$debug .= "<strong>\$basket Variables:</strong> (unserialize(\$cc_session->ccUserData['basket']))<br />" . cc_print_array(unserialize($cc_session->ccUserData['basket'])) . "<hr size=1  />";

		$debug .= "<strong>\$cc_session->ccUserData  Variables:</strong><br />" . cc_print_array($cc_session->ccUserData) . "<hr size=1  />";

		$debug .= "<strong>MySQL Queries (" . count($db->queryArray) . "):</strong><br />" . cc_print_array($db->queryArray);

		$debug .= "</div>";

}

if (isset($body) && is_object($body))

{

		$body->assign("PAGE_CONTENT", $page_content);

		$body->assign("VAL_ROOTREL", $glob['rootRel']);

		$body->assign("DEBUG_INFO", $debug);

		$body->parse("body");

		$htmlOut = $body->text("body");

		if (isset($config['lkv']) && 0 < $config['lkv'] && preg_match("#^([0-9]{6})+[-]+([0-9])+[-]+([0-9]{4})\$#", $config['lk']))

		{

				$copyRightBody = "";

				$copyRightTitle = "";

		}

		else

		{

				$copyRightBody = '

  

';

				$copyRightTitle = ' ';

		}

		if (isset($config['google_analytics2'], $config['google_analytics']))

		{

				$googleAnalytics = "

				<script type=\"text/javascript\">

					var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");

					document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));

				</script>

				<script type=\"text/javascript\">

					var pageTracker = _gat._getTracker(\"" . $config['google_analytics'] . "\");

					pageTracker._initData();

					pageTracker._trackPageview();

				</script>";

		}

		else

		{

				$googleAnalytics = "";

		}

		$htmlOut = preg_replace(array("/(\\<\\/body\\>)/i", "/(\\<\\/title\\>)/i", "/(\\<\\/head\\>)/i"), array($copyRightBody . "\$1", $copyRightTitle . "\$1", $googleAnalytics . "\$1"), $htmlOut);
		$htmlOut = preg_replace('/&(?![A-Za-z0-9#]{1,7};)/','&amp;',$htmlOut);
/*$htmlOut = preg_replace(
    array(
        '/ {2,}/',
        '/<!--.*?-->|\t|(?:\r?\n[ \t]*)+/s'
    ),
    array(
        ' ',
        ''
    ),
    $htmlOut
);*/
		echo $htmlOut;

}
$db->close();
?>

