<?php 
/*

|	login.inc.php
|   ========================================
|	Admin Session Start	
+--------------------------------------------------------------------------
*/

if(!defined('CC_INI_SET')){ die("Access Denied"); }

if($_GET['ccSSL']==1){
	$enableSSl = 1;
}
include_once("includes".CC_DS."sslSwitch.inc.php");

if (isset($_POST['username']) && isset($_POST['password'])){
	
	$result = $admin_session->login($_POST['username'], $_POST['password']);
	// data for admin session log
	$data["username"] = $db->mySQLSafe($_POST['username']);
	$data["time"] = time();
	$data["ipAddress"] = $db->mySQLSafe(get_ip_address());		
	
	if($result == true) {
		// First level of brute force attack prevention
		if($db->blocker($_POST['username'],$ini['bfattempts'],$ini['bftime'],true,"b")==true){
			$blocked = true; 
		} else {
		
			$data['success'] = '1';
			// Reset fail level
			$newdata['failLevel'] = '0';
			$newdata['blockTime'] = '0';
			$newdata['noLogins'] = "noLogins+1";
			
			$db->update($glob['dbprefix']."ImeiUnlock_admin_users", $newdata, "adminId=".$result[0]['adminId'],$stripQuotes="");
		
		}
	
	} else {
		// First level of brute force attack prevention
		$blocked = $db->blocker($_POST['username'],$ini['bfattempts'],$ini['bftime'],false,"b");

		if($blocked==false) {
			
			// check user exists
			$query = sprintf("SELECT adminId, failLevel, blockTime, username, lastTime FROM ".$glob['dbprefix']."ImeiUnlock_admin_users WHERE username = %s", 
			$db->mySQLSafe($_POST['username']));
	 
			$user = $db->select($query);
			
			// Second level of brute force attack prevention
			if($user==true) {
				
				if($user[0]['blockTime']>0 && $user[0]['blockTime']<time()) {
					// reset fail level and time
					$newdata['failLevel'] = '1';
					$newdata['blockTime'] = '0';
				} elseif($user[0]['failLevel']==($ini['bfattempts']-1)) {
					
					$timeAgo = time() - $ini['bftime'];
					
					if($user[0]['lastTime']<$timeAgo) {
						$newdata['failLevel'] = 1;
						$newdata['blockTime'] = 0;
					} else {
					
						// block the account
						$newdata['failLevel'] = $ini['bfattempts'];
						$newdata['blockTime'] = time()+$ini['bftime'];
					
					}
				
				} elseif($user[0]['blockTime']<time()) {
					
					$timeAgo = time() - $ini['bftime'];
					if($user[0]['lastTime']<$timeAgo) {
						$newdata['failLevel'] = 1;
					} else {
						// set fail level + 1
						$newdata['failLevel'] = $user[0]['failLevel']+1;
					}
					
					$newdata['blockTime'] = 0;
				} else {
					$msg = "<p class='warnText'>".sprintf($lang['admin_common']['blocked'],($ini['bftime']/60))."</p>";
					$blocked = true;
				}
				
				if(is_array($newdata)) {
					$newdata['lastTime'] = time();
					$db->update($glob['dbprefix']."ImeiUnlock_admin_users", $newdata, "adminId=".$user[0]['adminId'],$stripQuotes="");
				}
			
			} 
		
		} else {
			// login failed message
			$msg = "<p class='warnText'>".$lang['admin_common']['login_failed']."</p>";

		}
		
	}	
	
	if($blocked==true) {
		$msg = "<p class='warnText'>".sprintf($lang['admin_common']['blocked'],sprintf("%.0f",($ini['bftime']/60)))."</p>";
	} else {
		
		$insert = $db->insert($glob['dbprefix']."ImeiUnlock_admin_sessions", $data);
			
		// if there is over max amount of login records delete last one
		// this prevents database attacks of bloating
		if($db->numrows("SELECT loginId FROM ".$glob['dbprefix']."ImeiUnlock_admin_sessions")>250) {
			$loginId = $db->select("SELECT min(loginId) as id FROM ".$glob['dbprefix']."ImeiUnlock_admin_sessions");
			$db->delete($glob['dbprefix']."ImeiUnlock_admin_sessions","loginId='".$loginId[0]['id']."'");
		}
	
	}
	
	
	if($result == true && $blocked==false) {
		$admin_session->createSession($result[0]['adminId']);		
		
		if(isset($_GET['goto']) && !empty($_GET['goto'])){
			// check redirect URL is safe!
			if (eregi("^http://|^https://",$_GET['goto']) && !eregi("^".$glob['storeURL']."|^".$config['storeURL_SSL'],$_GET['goto'])) {
				httpredir($GLOBALS['rootRel'].$glob['adminFile']);
			} else {
				httpredir(sanitizeVar(urldecode($_GET['goto'])));
			}
		} else {
			httpredir($GLOBALS['rootRel'].$glob['adminFile']);
		}
		
	}

}
if(isset($_GET['email'])) {
	$msg = "<p class='infoText'>".$lang['admin_common']['other_new_pass_sent']." ".sanitizeVar(urldecode($_GET['email']))."</p>";
}

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

if(isset($msg)) {
	
	echo msg($msg,FALSE); 
} elseif(!isset($GLOBALS[CC_ADMIN_SESSION_NAME]) && !isset($_POST['username']) && !isset($_POST['password'])) { 
?>
<p class="infoText"><?php echo  $lang['admin_common']['other_no_admin_sess'];?></p>
<?php } elseif (isset($_POST['username']) && isset($_POST['password'])){ ?>
<p class="warnText"><?php echo  $lang['admin_common']['other_login_fail_2'];?></p>
<?php } 

$goTo = sanitizeVar($_GET['goto']);

if(detectSSL()) {
	// make sure goto URL is HTTPS rather than HTTP
	$goTo = str_replace($glob['storeURL'], $config['storeURL_SSL'],$goTo); 
	$onclickurl = $glob['storeURL']."/".$glob['adminFile']."?_g=login";
	$postUrl = $config['storeURL_SSL']."/".$glob['adminFile']."?_g=login&amp;ccSSL=1";
} else {
	// make sure goto URL is HTTP rather than HTTPS
	$goTo = str_replace($config['storeURL_SSL'], $glob['storeURL'],$goTo);
	$onclickurl = $config['storeURL_SSL']."/".$glob['adminFile']."?_g=login&amp;ccSSL=1";
	$postUrl = $glob['storeURL']."/".$glob['adminFile']."?_g=login";
}

if(!empty($goTo)){
	$onclickurl .= "&amp;goto=".urlencode($goTo);
	$postUrl .= "&amp;goto=".urlencode($goTo);
}
?>
<div class="bg">
<form action="<?php echo  $postUrl; ?>" method="post" enctype="multipart/form-data" name="ccAdminLogin" target="_self"  onsubmit="disableSubmit(document.getElementById('login'),'<?php echo  $lang['admin_common']['please_wait']; ?>');" >

<div class="loginbox">
<div class="toplogin">
	<?php echo  $lang['admin_common']['other_login_below'];?>
</div>
<table border="0" align="center" width="822" cellpadding="0" cellspacing="0" class="mainTable2">
  
  <tr>
    <td  colspan="2">
	<div class="inner">
    <div class="inner2">
	<span class="txt14 label txt-grey"><?php echo  $lang['admin_common']['other_username'];?></span><br />
    
    <input name="username" type="text" id="username" class="textbox" value="<?php if(isset($_POST['username'])) echo sanitizeVar($_POST['username']); ?>" />
    </div>
  	 <div class="inner2" style="padding-left:20px;">
     <span class="txt14 label txt-grey" > <?php echo  $lang['admin_common']['other_password'];?></span><br />
    <input name="password" type="password" id="password" class="textbox"  />
      </div>
    </div>
    </td>
  </tr>
  <?php
  if($config['ssl'] && !$config['force_ssl']) {	  
?>
	  <tr>
		<td>&nbsp;</td>
		<td class="tdText"><?php echo  $lang['admin_common']['other_login_ssl'];?> <input type="checkbox" name="ccSSL" value="1" <?php if($_GET['ccSSL']==1) { echo "checked='checked'"; }?> 
		onclick="parent.location='<?php echo  $onclickurl; ?>'" /></td>
	  </tr>
	  <?php
  }
  ?>
  
  <tr>
    <td><a href="<?php echo  $glob['adminFile']; ?>?_g=requestPass" class="forget"><?php echo  $lang['admin_common']['other_request_pass'];?></a></td>
    <td>
	<input name="login" type="submit" id="login" value="<?php echo  $lang['admin_common']['other_login'];?>" class="submit2" />	</td>
  </tr>
</table>
<p  class="powered">This system is Powered by <span>IMEI Unlock Team</span></p>
</div>

</form>
</div>