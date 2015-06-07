<?php
/*
+--------------------------------------------------------------------------
|	cc_session.php
|   ========================================
|	Front Session Class
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

class session {

	var $ccUserData;
	var $ccUserBlocked = false;
	
	var $config;
	var $db;
	var $glob;
	var $ini;
	
	function session() {
	#	$this->__construct();
	#}
	
	#function __construct() {
		global $config, $db, $glob, $ini;
		
		$this->config	= $config;
		$this->db		= $db;
		$this->glob		= $glob;
		$this->ini		= $ini;
		
		if (isset($_GET[CC_SESSION_NAME])) {
			$this->set_cc_cookie(CC_SESSION_NAME, $_GET[CC_SESSION_NAME], $this->config['sqlSessionExpiry']);
		} else {
			## see if session is still in db
			$query = sprintf("SELECT sessId FROM %sImeiUnlock_sessions WHERE sessId=%s", $this->glob['dbprefix'], $this->db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
			
			$results = $this->db->select($query);
			
			## !empty($results[0]['sessId']) critical in case results=true if session DB table has an empty sessionId!!
			if ($results && !empty($results[0]['sessId'])) {
				$data["timeLast"] = $this->db->mySQLSafe(time());
				$data["location"] = $this->db->mySQLSafe(currentPage());
				$update = $this->db->update($this->glob['dbprefix']."ImeiUnlock_sessions", $data, "sessId=".$this->db->mySQLSafe($results[0]['sessId']));
			} else {
				$this->makeSession();
			}
		}
		
		## get all session data and store as class array
		$query = sprintf("SELECT * FROM %1\$sImeiUnlock_sessions LEFT JOIN %1\$sImeiUnlock_customer ON %1\$sImeiUnlock_sessions.customer_id = %1\$sImeiUnlock_customer.customer_id WHERE sessId = %2\$s", $this->glob['dbprefix'], $this->db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
		$result = $this->db->select($query);
		// security checks
		
		/*
		$client_ip = get_ip_address();
		if (strpos($_SERVER['HTTP_USER_AGENT'],'AOL') == false && !empty($result[0]['ip']) && ($result[0]['ip'] !== $client_ip || $result[0]['browser'] !== $_SERVER['HTTP_USER_AGENT'])) {
			$this->destroySession($GLOBALS[CC_SESSION_NAME]);
		}
		*/
		$this->ccUserData = $result[0];
		if (empty($this->ccUserData['email']) && isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
			$this->authenticate($_COOKIE['username'], $_COOKIE['password'], true, true);	
		}
		
		if (empty($result[0]['lang'])) {
			define("LANG_FOLDER", $this->config['defaultLang']);
		} else {
			define("LANG_FOLDER", $result[0]['lang']);
		}
		
		if (empty($result[0]['skin'])) {
			$ismobile = check_user_agent('mobile');
			if($ismobile && $config['mobilesking']) {
			//define("SKIN_FOLDER", 'mobile');
			} else {
			if(!defined("SKIN_FOLDER")) define("SKIN_FOLDER", $config['skinDir']);
			}
		} else  {
			define("SKIN_FOLDER", $result[0]['skin']);
		}
	}	

	function destroySession($sessionId) {
		
		## removed to keep basket data
		// $this->set_cc_cookie(CC_SESSION_NAME, '', time()-3600); 
		$this->set_cc_cookie('username', '', time()-3600);
		$this->set_cc_cookie('password', '', time()-3600);
		
		$data["customer_id"] = '0';
		$update = $this->db->update($this->glob['dbprefix']."ImeiUnlock_sessions", $data,"sessId=".$this->db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
		return ($update) ? true : false;
	}

	function makeSession() {
		$sessionId = $this->makeSessId();
		$this->set_cc_cookie(CC_SESSION_NAME, $sessionId, $this->config['sqlSessionExpiry']);
		
		## set session global var because cookie won't show until next page load
		$GLOBALS[CC_SESSION_NAME] = $sessionId;
		
		## insert sessionId into db
		$data["sessId"] 		= 	$this->db->mySQLSafe($sessionId);		
		$timeNow 				= 	$this->db->mySQLSafe(time());
		$data["timeStart"] 		= 	$timeNow;	
		$data["timeLast"] 		= 	$timeNow;
		$data["customer_id"] 	= 	0;
		$data["ip"] 			= 	$this->db->mySQLSafe(get_ip_address());
		$data["browser"] 		= 	$this->db->mySQLSafe($_SERVER['HTTP_USER_AGENT']);
		
		$insert = $this->db->insert($this->glob['dbprefix']."ImeiUnlock_sessions", $data);
		$this->deleteOldSessions();
	}
	
	function deleteOldSessions() {
		$expiredSessTime = time() - $this->config['sqlSessionExpiry'];
		## delete sessions older than time set in config file
		$delete = $this->db->delete($this->glob['dbprefix']."ImeiUnlock_sessions", "timeLast<".$expiredSessTime);
	}
	
	function createSalt($user,$pass,$remember) {
		$salt = randomPass(6);
		$pass_hash = md5(md5($salt).md5($pass));
		$this->db->update($this->glob['dbprefix']."ImeiUnlock_customer", array("password" => $this->db->mySQLSafe($pass_hash),"salt" => $this->db->mySQLSafe($salt)),"email=".$this->db->mySQLSafe($user));
		$this->authenticate($user,$pass,$remember);
	}

	function authenticate($user, $pass, $remember, $redirlogin = false, $cookie_login = false, $social = false) {
		if ($cookie_login) {
			$user		= sanitizeVar($_COOKIE['username']);
			$passMD5	= sanitizeVar($_COOKIE['password']); 
		} else {
			$user		= sanitizeVar($user);
			$passMD5	= md5(sanitizeVar($pass));
		}
		if($social == 1){
			$query = "SELECT `customer_id`, `block` FROM ".$this->glob['dbprefix']."ImeiUnlock_customer WHERE email=".$this->db->mySQLSafe($user)." AND issocial = ".$this->db->mySQLSafe(1)." AND type>0";
			$customer = $this->db->select($query);
			}elseif($social == 2){
			$query = "SELECT `customer_id`, `block` FROM ".$this->glob['dbprefix']."ImeiUnlock_customer WHERE username=".$this->db->mySQLSafe($user)." AND issocial = ".$this->db->mySQLSafe(1)." AND type>0";
			$customer = $this->db->select($query);
			}else{
		
		$query = "SELECT `customer_id`, `salt` FROM ".$this->glob['dbprefix']."ImeiUnlock_customer WHERE `type`>0 AND `email`=".$this->db->mySQLSafe($user);
		$salt = $this->db->select($query);
		
		if($salt[0]['customer_id']>0 && empty($salt[0]['salt']) && $cookie_login == false) {
			$query = "SELECT `customer_id` FROM ".$this->glob['dbprefix']."ImeiUnlock_customer WHERE email=".$this->db->mySQLSafe($user)." AND `password` = ".$this->db->mySQLSafe($passMD5)." AND type>0";
			if($customer = $this->db->select($query)) {
				$this->createSalt($user,$pass,$remember);
			} else {
				return false;
			}
		} else {
			$passMD5 = md5(md5($salt[0]['salt']).md5($pass));
			$query = "SELECT `customer_id`, `block`, `lastTime` FROM ".$this->glob['dbprefix']."ImeiUnlock_customer WHERE email=".$this->db->mySQLSafe($user)." AND password = ".$this->db->mySQLSafe($passMD5)." AND type>0";
			$customer = $this->db->select($query);
		}
			}
		if (!$customer) {
			if ($this->db->blocker($user, $this->ini['bfattempts'], $this->ini['bftime'], false, 'f')) {
				$this->ccUserBlocked = true; 	
			}
		} else if ($customer[0]['customer_id']>0) {
		//$query = "SELECT `block` FROM ".$this->glob['dbprefix']."ImeiUnlock_customer WHERE customer_id=".$this->db->mySQLSafe($customer[0]['customer_id']);
		//$block = $this->db->select($query);
		// remember user for as long as sessions are allowed in DB
			if ($remember == true) {
				$this->set_cc_cookie('username', $user, $this->config['sqlSessionExpiry']);
				$this->set_cc_cookie('password', $passMD5, $this->config['sqlSessionExpiry']); 
				$this->set_cc_cookie(CC_SESSION_NAME, $GLOBALS[CC_SESSION_NAME], $this->config['sqlSessionExpiry']);	
			}
			
			if ($this->db->blocker($user, $this->ini['bfattempts'], $this->ini['bftime'], true, 'f')) {
				$this->ccUserBlocked = true;
			} else if($customer[0]['block'] > 0 ){
				$this->ccUserPBlocked = true;
			}else {
				$newdata['lastTime'] = $this->db->mySQLSafe(time());
				$newdata['lastTime2'] = $this->db->mySQLSafe($customer[0]['lastTime']);
				$this->db->update($glob['dbprefix']."ImeiUnlock_customer", $newdata, " customer_id= ".$this->db->mySQLSafe($customer[0]['customer_id']));
				$userimg['customerId'] = $this->db->mySQLSafe($customer[0]['customer_id']);
				$userimg['session_id'] = $this->db->mySQLSafe();
				$this->db->update($glob['dbprefix']."ImeiUnlock_user_images", $userimg, " session_id= ".$this->db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
				$this->db->update($glob['dbprefix']."ImeiUnlock_user_images_success", $userimg, " session_id= ".$this->db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
				$data["customer_id"] 	= $customer[0]['customer_id'];
				$data["ip"] 			= $this->db->mySQLSafe(get_ip_address());
				$data["browser"] 		= $this->db->mySQLSafe($_SERVER['HTTP_USER_AGENT']); 
				$update = $this->db->update($this->glob['dbprefix']."ImeiUnlock_sessions", $data,"sessId=".$this->db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
				
				## Make sure customer is type 1 & not ghost type 2 (if it is first login from express checkout welcome email)
				$update = $this->db->update($this->glob['dbprefix']."ImeiUnlock_customer", array("type"=>1),"customer_id=".$customer[0]['customer_id']);
				
				## "login","reg","unsubscribe","forgotPass" etc..
				if($redirlogin == 1){
				httpredir($GLOBALS['rootRel']."index.php?_g=co&_a=step2");
					}elseif($redirlogin == 2){
				httpredir($GLOBALS['rootRel']."Gallery.html");
					}
					else{
				$redir = sanitizeVar(urldecode($_GET['redir']));
					}
				## prevent phishing attacks
				if (eregi("^http://|^https://",$redir) && !eregi("^".$this->glob['storeURL']."|^".$this->config['storeURL_SSL'], $redir)) {
					httpredir($GLOBALS['rootRel']."index.php");
				}
				
			 if (isset($_GET['redir']) && !empty($_GET['redir']) && !eregi("logout|login|forgotPass|changePass", $redir)) {
					httpredir($redir);
				} else {
					httpredir($GLOBALS['rootRel']."index.php");
				}
			}
		} else if (eregi("step1", urldecode($_GET['redir']))) {
			httpredir($GLOBALS['rootRel']."index.php?_g=co&_a=step1");	
		} 
	}
	
	
	function makeSessId() {
		session_start();
		session_regenerate_id(true);
		return session_id();
	}
	
	/* defunct
	function get_cookie_domain($domain) {
		$cookie_domain = str_replace(array('http://', 'https://', 'www.'), '', strtolower($domain));
		$cookie_domain = explode("/", $cookie_domain);
		$cookie_domain = explode(":", $cookie_domain[0]);
		return '.'.$cookie_domain[0];
	}
	*/
	
	function set_cc_cookie($name, $value, $length = 0) {
		## only set the cookie if the visitor is not a spider or search engine system is off
		if (!$this->user_is_search_engine() || $this->config['sef'] == false) {
			$expires = ($length>0) ? (time()+$length) : 0;
			$urlParts = parse_url($GLOBALS['storeURL']);
			$domain = (empty($urlParts['host']) || !strpos($urlParts['host'], ".")) ? false : str_replace("www.",".",$urlParts['host']);
			
			setcookie($name, $value, $expires, $GLOBALS['rootRel'], $domain);
		}
	}
	
	function user_is_search_engine() {
		$user_agent		= strtolower($_SERVER['HTTP_USER_AGENT']);
		if (($user_agent != '') && (strtolower($user_agent) != 'null') && (strlen(trim($user_agent)) > 0)) {
			$spiders	= file(CC_ROOT_DIR.CC_DS.'spiders.txt');
			foreach ($spiders as $spider) {
				if (($spider != '') && (strtolower($spider) != 'null') && (strlen(trim($spider)) > 0)) {
					if (strpos($user_agent, trim($spider)) !== false) {
						$spider_flag	= true;
						break;
					}
				}
			}
		}
		return (isset($spider_flag)) ? true : false;
	}
}

?>
