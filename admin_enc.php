<?php
/*
+--------------------------------------------------------------------------*
class iono_keys
{
		var $license_key;
		var $home_url_site = ""; //http://cp.ImeiUnlock.com
		var $home_url_port = 80;
		var $home_url_iono = "/remote.php";
		var $key_location;
		var $remote_auth;
		var $key_age;
		var $key_data;
		var $now;
		var $result;
		function iono_keys($license_key, $remote_auth, $key_location = "key.php", $key_age = 1209600)
		{
				$this->license_key = $license_key;
				$this->remote_auth = $remote_auth;
				$this->key_location = $key_location;
				$this->key_age = $key_age;
				$this->now = time();
				if (file_exists($this->key_location))
				{
						clearstatcache();
						$this->result = $this->read_key();
				}
				else
				{
						clearstatcache();
						$this->result = $this->generate_key();
						if (empty($this->result))
						{
								$this->result = $this->read_key();
						}
				}
				unset($this->remote_auth);
		}
		function generate_key()
		{
				global $glob;
				$server_name = str_replace("www.", "", $_SERVER['SERVER_NAME']);
				$request = "remote=licenses&type=5&license_key=" . urlencode(base64_encode($this->license_key));
				$request .= "&host_ip=" . urlencode(base64_encode($_SERVER['SERVER_ADDR']));
				$request .= "&host_name=" . urlencode(base64_encode($server_name));
				$request .= "&hash=" . urlencode(base64_encode(md5($request)));
				$request = $this->home_url_iono . "?" . $request;
				if (function_exists("curl_init"))
				{
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $this->home_url_site . $request);
						curl_setopt($ch, CURLOPT_PORT, $this->home_url_port);
						curl_setopt($ch, CURLOPT_HEADER, false);
						curl_setopt($ch, CURLOPT_TIMEOUT, 15);
						curl_setopt($ch, CURLOPT_USERAGENT, "iono");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_FAILONERROR, true);
						curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
						if (isset($glob) && $glob['proxyEnable'] == true)
						{
								if (!empty($glob['proxyUser']))
								{
										curl_setopt($ch, CURLOPT_PROXYUSERPWD, $glob['proxyUser'] . ":" . $glob['proxyPass']);
								}
								$headers = array("Host: " . $this->home_url_site);
								curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
								curl_setopt($ch, CURLOPT_PROXY, $glob['proxyHost'] . ":" . $glob['proxyPort']);
						}
						$string = curl_exec($ch);
						$chResult = curl_error($ch);
						if ($chResult)
						{
								$this->commError = "<strong>cURL Error:</strong> " . $chResult;
								return 12;
						}
						$exploded = explode("|", $string);
						curl_close($ch);
				}
				else
				{
						$header = "GET " . $request . " HTTP/1.0\r\nHost: {$this->home_url_site}\r\nConnection: Close\r\nUser-Agent: iono\r\n";
						$header .= "\r\n\r\n";
						$fpointer = fsockopen($this->home_url_site, $this->home_url_port, &$errno, &$errstr, 5);
						$return = "";
						if ($fpointer)
						{
								fwrite($fpointer, $header);
								while (!feof($fpointer))
								{
										$return .= fread($fpointer, 1024);
								}
								fclose($fpointer);
						}
						else
						{
								$this->commError = $errstr . " (" . $errno . ")";
								return 12;
						}
						$content = explode("\r\n\r\n", $return);
						$content = explode($content[0], $return);
						$string = urldecode($content[1]);
						$exploded = explode("|", $string);
				}
				switch ($exploded[0])
				{
						case 0:
								return 8;
						case 2:
								return 9;
						case 3:
								return 5;
				}
				$data['license_key'] = $exploded[1];
				$data['expiry'] = $exploded[2];
				$data['hostname'] = $exploded[3];
				$data['ip'] = $exploded[4];
				$data['timestamp'] = $this->now;
				$data_encoded = serialize($data);
				$data_encoded = base64_encode($data_encoded);
				$data_encoded = md5($this->now . $this->remote_auth) . $data_encoded;
				$data_encoded = strrev($data_encoded);
				$data_encoded_hash = sha1($data_encoded . $this->remote_auth);
				$fp = fopen($this->key_location, "w");
				if ($fp)
				{
						$fp_write = fwrite($fp, wordwrap($data_encoded . $data_encoded_hash, 40, "\n", true));
						if (!$fp_write)
						{
								return 11;
						}
						fclose($fp);
				}
				return 10;
		}
		function read_key()
		{
				$key = file_get_contents($this->key_location);
				if ($key)
				{
						$key = str_replace("\n", "", $key);
						$key_string = substr($key, 0, strlen($key) - 40);
						$key_sha_hash = substr($key, strlen($key) - 40, strlen($key));
						if (sha1($key_string . $this->remote_auth) == $key_sha_hash)
						{
								$key = strrev($key_string);
								$key_hash = substr($key, 0, 32);
								$key_data = substr($key, 32);
								$key_data = base64_decode($key_data);
								$key_data = unserialize($key_data);
								if (md5($key_data['timestamp'] . $this->remote_auth) == $key_hash)
								{
										if ($this->key_age <= $this->now - $key_data['timestamp'])
										{
												unlink($this->key_location);
												$this->result = $this->generate_key();
												if (empty($this->result))
												{
														$this->result = $this->read_key();
												}
												return 1;
										}
										$this->key_data = $key_data;
										if ($key_data['license_key'] != $this->license_key)
										{
												return 4;
										}
										if ($key_data['expiry'] <= $this->now && $key_data['expiry'] != 1)
										{
												return 5;
										}
										$server_name = str_replace("www.", "", $_SERVER['SERVER_NAME']);
										$server_name_registered = str_replace("www.", "", $key_data['hostname']);
										if (substr_count($server_name_registered, ",") == 0)
										{
												if ($server_name_registered != $server_name && !empty($server_name_registered))
												{
														return 6;
												}
										}
										else
										{
												$hostnames = explode(",", $server_name_registered);
												if (!in_array($server_name, $hostnames))
												{
														return 6;
												}
										}
										return 1;
								}
								return 3;
						}
						return 2;
				}
				return 0;
		}
		function get_data()
		{
				return $this->key_data;
		}
		function status($type)
		{
				clearstatcache();
				switch ($type)
				{
						case "keyfolder":
								$path = "includes" . CC_DS . "extra";
								@chmod($path, 511);
								if (!file_exists($path))
								{
										return "<span style='color: red;'>doesn't exist!</span>";
								}
								if (is_writable($path))
								{
										return "<span style='color: green;'>exists and is writable.</span>";
								}
								return "<span style='color: red;'>is NOT writable.</span>";
						case "keyfile":
								$path = "includes" . CC_DS . "extra" . CC_DS . "key.php";
								@chmod($path, 511);
								if (!file_exists($path))
								{
										return "<span style='color: red;'>doesn't exist!</span>";
								}
								if (is_writable($path))
								{
										return "<span style='color: green;'>exists and is writable.</span> (<a href='?keyDel=1'>DELETE</a>)";
								}
								return "<span style='color: red;'>is NOT writable.</span> (<a href='?keyDel=1'>DELETE</a>)";
				}
		}
		function error_out($errorNo, $errorMsg, $solutionMsg)
		{
				exit("<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n<title>Software License Error #" . $errorNo . "</title>\n<style type=\"text/css\">\n<!--\nbody {\n\tbackground-color: #CCCCCC;\n\tmargin: auto;\n\twidth: 760px;\n\tfont-family: Arial, Helvetica, sans-serif;\n\tfont-size: 75%;\n}\n#page {\n\tborder: 3px black solid;\n\tbackground-color: #FFFFFF;\n\tpadding: 0px 10px 10px 10px;\n}\n\na {\n\tcolor: #0066FF;\n}\n\n-->\n</style>\n</head>\n\n<body>\n<div id=\"page\">\n\t<h1>Software License Error #" . $errorNo . "</h1>\n\t<h2 style='color: red;'><em>" . $errorMsg . "</em></h2>\n\t<p>\n\t<strong>Status:</strong><br />\n\tincludes/extra folder " . $this->status("keyfolder") . "<br />\n\tincludes/extra/key.php " . $this->status("keyfile") . "<br />\n\t<em style='color: grey;'>Note: The key.php file can be deleted at anytime and the store will attempt to regenerate it. Your software license key is held in the includes/global.inc.php file.</em> \n\t</p>\n\t<hr />\n\t<h2 style='color: green;'>Solution:</h2>\n" .
						nl2br($solutionMsg) . "\n</div>\n</body>\n</html>");
		}
}
if ($_GET['keyDel'] == 1)
{
		@unlink("includes" . CC_DS . "extra" . CC_DS . "key.php");
		header("Location: " . $glob['adminFile']);
		exit();
}
$keyfile = CC_ROOT_DIR . CC_DS . "includes" . CC_DS . "extra" . CC_DS . "key.php";
$key = new iono_keys($glob['license_key'], "264d1ede8793", $keyfile);
switch ($key->result)
{
		case 0:
				$errorMsg = "Unable to read includes/extra/key.php file. Either the file doesn't exists or it is corrupt.";
				$solutionMsg = "Please access your website files using an FTP client or with the use of a file manager if available via your web hosting control panel. Browse to the includes/extra folder. If the key.php file does exist delete it and then refresh this page (alternatively try the delete button above which works in some cases). If the key.php files doesn't exist move up one level and check that the includes/extra folder is writable. On a Linux/Unix hosting environment it will need file permissions of 0777 (this is known as it's CHMOD value). Once the file permissions are correct refresh this page again.\n\t\t\nIf the issue persists, please contact technical support with FTP access information to your store.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 1:
		case 2:
		case 3:
				$errorMsg = "The inlcudes/extra/key.php file is invalid!";
				$solutionMsg = "Please access your website files using an FTP client or with the use of a file manager if available via your web hosting control panel. Browse to the includes/extra folder. If the key.php file does exist delete it and then refresh this page. It may also be possible to delete the key.php file using the delete link above on some servers.\n\t\t\nIf the issue persists, please contact technical support with FTP access information to your store.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 4:
				$errorMsg = "License key does not match key string in includes/extra/key.php file.";
				$solutionMsg = "Please access your website files using an FTP client or with the use of a file manager if available via your web hosting control panel. Browse to the includes/extra folder. If the key.php file does exist delete it and then refresh this page. (Alternatively try the delete link above which may work on some servers).\n\t\t\nIf the issue persists, please contact technical support with FTP access information to your store.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 5:
				$errorMsg = "Your license key has expired.";
				$solutionMsg = "Please check the value of your software license key in the includes/global.inc.php file. If you installed your store using a trial software license key you will need to purchase full software license key to continue to use this software. If you have already purchased one or after you have purchased one there is no need to reinstall the software. It is possible to continue from where you left off. \n\t\t\nUsing an FTP client please edit the file: /includes/global.inc.php\n\nThe contents should look something like:\n\n<span style='font-family: \"Courier New\", Courier, monospace; color: blue;'>&lt;?php\n\$glob['adminFile'] = 'admin.php';\n\$glob['adminFolder'] = 'admin';\n\$glob['dbdatabase'] = 'ImeiUnlock_db';\n\$glob['dbhost'] = 'localhost';\n\$glob['dbpassword'] = '******';\n\$glob['dbprefix'] = '';\n\$glob['dbusername'] = 'ImeiUnlock_user';\n\$glob['installed'] = '1';\n\$glob['license_key'] = '<span style=\"color: red;\">xxxx-xxxx-x-xxxxxxxxxx-xxxxxxxx</span>';\n\$glob['rootRel'] = '/';\n\$glob['storeURL'] = 'http://www.example.com';\n?&gt;</span>\n\nPlease locate your new full software license key from your ImeiUnlock customer control panel. You then need to edit the global.inc.php file so that it has the new software license key and upload this back to the server. \n\ne.g.\n<span style='font-family: \"Courier New\", Courier, monospace; color: blue;'>&lt;?php\n\$glob['adminFile'] = 'admin.php';\n\$glob['adminFolder'] = 'admin';\n\$glob['dbdatabase'] = 'ImeiUnlock_db';\n\$glob['dbhost'] = 'localhost';\n\$glob['dbpassword'] = '******';\n\$glob['dbprefix'] = '';\n\$glob['dbusername'] = 'ImeiUnlock_user';\n\$glob['installed'] = '1';\n\$glob['license_key'] = '<span style=\"color: green;\">yyyy-yyyy-y-yyyyyyyyyy-yyyyyyyy</span>';\n\$glob['rootRel'] = '/';\n\$glob['storeURL'] = 'http://www.example.com';\n?&gt;</span>\n\nPlease now delete the /includes/extra/key.php file if it exists and then refresh this page. You may be able to do this using the delete link a the top of this page which works on some servers.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 6:
				$errorMsg = "Server host name doesn't match value in includes/extra/key.php file.";
				$solutionMsg = "This error may occur if you have attempted to install your store on more than one domain or sub-domain or if you have moved your store to a new server or domain. Please login to your customer control panel <a href='https://www.ImeiUnlock.com/site/customers'>https://www.ImeiUnlock.com/site/customers</a> under &quot;Active Software Licenses&quot; click the link to &quot;view full details&quot;. Please make sure you have selected the correct software license if you have more than one and click the link to &quot;Unlock&quot; the key. Once you have done that and BEFORE you refresh this page delete the includes/extra/key.php file. (You may be able to do that using the delete link above). After that has been done refresh this page and the store will be relicensed correctly.\n\t\t\n\t\tIf the issue persists, please contact technical support with FTP access information to your store.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 7:
		case 8:
				$errorMsg = "Software License Key has been disabled.";
				$solutionMsg = "Please contact our sales staff and they will investigate this for you.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 9:
				$errorMsg = "Software License Key has been suspended.";
				$solutionMsg = "Please contact our sales staff and they will investigate this for you.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 10:
		case 11:
				$errorMsg = "The includes/extra/key.php file doesn't exist and server was unable to write it.";
				$solutionMsg = "Please access your website files using an FTP client or with the use of a file manager if available via your web hosting control panel. Browse to the includes/extra folder. Please check that the includes/extra folder is writable. On a Linux/Unix hosting environment it will need file permissions of 0777 (this is known as it's CHMOD value). Once the file permissions are correct refresh this page again.\n\t\t\nIf the issue persists, please contact technical support with FTP access information to your store.";
				$key->error_out($key->result, $errorMsg, $solutionMsg);
				break;
		case 12:
				$errorMsg = "Communication Error.";
				$solutionMsg = "There has been a connectivity issue attempting to license your store. Please contact your hosting company to make sure either 'cURL' or 'fsockopen' is enabled on the server or if your site is behind a proxy server. If it is behind a proxy server you will need to edit the admin.php file with the proxy address and port provided by your hosting company. If this issue remains please contact ImeiUnlock technical support with FTP access to your store so that our staff can investigate this further. If you see an error message below please speecify this also in order to help us resolve this quickly for you.";
				if (0 < strlen($key->commError))
				{
						$solutionMsg .= "<p>" . $key->commError . "</p>";
				}
				$key->error_out($key->result, $errorMsg, $solutionMsg);
}
**/
require ("classes" . CC_DS . "db" . CC_DS . "db.php");
$db = new db();
require ("classes" . CC_DS . "cache" . CC_DS . "cache.php");
$config = fetchdbconfig("config");
if (detectssl())
{
		$GLOBALS['storeURL'] = $config['storeURL_SSL'];
		$GLOBALS['rootRel'] = $config['rootRel_SSL'];
}
else
{
		$GLOBALS['storeURL'] = $glob['storeURL'];
		$GLOBALS['rootRel'] = $glob['rootRel'];
}
$lang = getlang("admin" . CC_DS . "admin_common.inc.php");
include_once ("classes" . CC_DS . "session" . CC_DS . "cc_admin_session.php");
$admin_session = new admin_session();
if (!in_array($_GET['_g'], array("logout", "login", "requestPass")))
{
		$ccAdminData = $admin_session->get_session_data();
}
if (isset($_GET['_g']))
{
		if (!($_GET['_g'] == "modules") && !empty($_GET['module']) && substr($_GET['_g'], 0, 7) == "modules")
		{
				if ($_GET['_g'] == "modules" && !empty($_GET['module']))
				{
						$moduleData = explode("/", $_GET['module']);
						$module = $moduleData[0];
						$moduleType = $moduleData[0];
						$moduleName = $moduleData[1];
						$moduleScript = isset($moduleData[2]) ? $moduleData[2] : "index";
				}
				$moduleFile = CC_ROOT_DIR . CC_DS . "modules" . CC_DS . $module . CC_DS . $moduleName . CC_DS . CC_DS . "admin" . CC_DS . $moduleScript . ".inc.php";
				require_once (file_exists($moduleFile) ? $moduleFile : CC_ROOT_DIR . CC_DS . $glob['adminFolder'] . CC_DS . "modules" . CC_DS . "index.inc.php");
		}
		else
		{
				if ($_GET['_g'] == "modules" && !empty($_GET['module']))
				{
						$moduleData = explode("/", $_GET['module']);
						$module = $moduleData[0];
						$moduleType = $moduleData[0];
						$moduleName = $moduleData[1];
						$moduleScript = isset($moduleData[2]) ? $moduleData[2] : "index";
						$moduleFile = CC_ROOT_DIR . CC_DS . "modules" . CC_DS . $module . CC_DS . $moduleName . CC_DS . CC_DS . "admin" . CC_DS . $moduleScript . ".inc.php";
						require_once (file_exists($moduleFile) ? $moduleFile : CC_ROOT_DIR . CC_DS . $glob['adminFolder'] . CC_DS . "modules" . CC_DS . "index.inc.php");
				}
				else
				{
						require_once (mkpath($_GET['_g']));
				}
		}
}
else
{
		require_once ($glob['adminFolder'] . CC_DS . "sources" . CC_DS . "home" . CC_DS . "index.inc.php");
}
if (!isset($skipFooter))
{
		require_once ($glob['adminFolder'] . CC_DS . "includes" . CC_DS . "footer.inc.php");
}
$db->close();
?>