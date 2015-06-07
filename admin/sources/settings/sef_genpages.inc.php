<?php
/*
+--------------------------------------------------------------------------
|	sef_genpages.inc.php
|   ========================================
|	Build Static(ish) SEO Pages
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

ini_set('ignore_user_abort', true);
ignore_user_abort(true);

## defunct as added to config file
## require("includes".CC_DS."ftp.inc.php");

$lang = getLang("admin".CC_DS."admin_settings.inc.php");

permission("settings","write", true);

include_once("includes".CC_DS."sef_urls.inc.php");

require($glob['adminFolder'].CC_DS."includes".CC_DS."header.inc.php");

?>
<p class="pageTitle"><?php echo $lang['admin']['settings_ftp_seo_title'];?></p>
<span class='copyText'>
<?php 

if(substr($config['ftp_root_dir'], -1, 1)!=="/") { $config['ftp_root_dir'] = $config['ftp_root_dir']."/"; }

// connect to FTP server
$conn_id = FTPConnect($config['ftp_server'], $config['ftp_username'], $config['ftp_password']);
if (!$conn_id) {
	echo "<p>".$lang['admin']['settings_ftp_conn_fail']."</p>"; 
	return;
}

// ok time to generate the product pages
$exist = $db->select("SELECT productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory order by productId");
if( $exist ) {
	echo "<p><strong>".$lang['admin']['settings_ftp_writeing']."</strong></p>";
	for($x = 0; $x < count($exist); $x++) {
		$prodId = $exist[$x]['productId'];
		$producturl = generateProductUrl($prodId);
		$tempfilename = explode("?", basename($producturl));
		$prodfilename = $tempfilename[0];		
		$proddirectory = dirname($producturl) . "/";
		generatePage($config['ftp_root_dir'], $proddirectory, $prodfilename, $prodId, "prod");
		echo sprintf($lang['admin']['settings_ftp_created'],$config['ftp_root_dir'].$proddirectory.$prodfilename)." <br />";
	}

}

// ok time to generate the tellafriend pages
$exist = $db->select("SELECT productId FROM ".$glob['dbprefix']."ImeiUnlock_inventory order by productId");
if( $exist ) {
	echo "<p><strong>".$lang['admin']['settings_writing_taf']."</strong></p>";
	for($x = 0; $x < count($exist); $x++) {
		$prodId = $exist[$x]['productId'];
		$producturl = generateTellFriendUrl($prodId);
		$tempfilename = explode("?", basename($producturl));
		$prodfilename = $tempfilename[0];	 		
		$proddirectory = dirname($producturl) . "/";
		generatePage($config['ftp_root_dir'], $proddirectory, $prodfilename, $prodId, "taf");
		echo sprintf($lang['admin']['settings_ftp_created'],$config['ftp_root_dir'].$proddirectory.$prodfilename)." <br />";
	}
	
}

// ok time to generate the category pages
$exist = $db->select("SELECT cat_id FROM ".$glob['dbprefix']."ImeiUnlock_category order by cat_id");
if( $exist ) {
	echo "<p><strong>".$lang['admin']['settings_writing_cats']."</strong></p>";
	for($x = 0; $x < count($exist); $x++) {
		$catId = $exist[$x]['cat_id'];
		$categoryurl = generateCategoryUrl($catId);
		$tempfilename = explode("?", basename($categoryurl));
		$catfilename = $tempfilename[0];	 		
		$catdirectory = dirname($categoryurl) . "/";
		generatePage($config['ftp_root_dir'], $catdirectory, $catfilename, $catId, "cat");
		echo sprintf($lang['admin']['settings_ftp_created'],$config['ftp_root_dir'].$catdirectory.$catfilename)." <br />";
	}
	// handle the sale item category page as a special case
	$catId = "saleItems";
	$categoryurl = generateCategoryUrl($catId);
	$tempfilename = explode("?", basename($categoryurl));
	$catfilename = $tempfilename[0];		
	$catdirectory = "";
	generatePage($config['ftp_root_dir'], $catdirectory, $catfilename, $catId, "cat");
	echo sprintf($lang['admin']['settings_ftp_created'],$config['ftp_root_dir'].$catdirectory.$catfilename)." <br />";	
	
}

// ok time to generate the document pages
$exist = $db->select("SELECT doc_id FROM ".$glob['dbprefix']."ImeiUnlock_docs order by doc_id");
if( $exist ) {
	echo "<p><strong>".$lang['admin']['settings_writing_docs']."</strong></p>";
	for($x = 0; $x < count($exist); $x++) {
		$docId = $exist[$x]['doc_id'];
		$documenturl = generateDocumentUrl($docId);
		$tempfilename = explode("?", basename($documenturl));
		$docfilename = $tempfilename[0];			
		$docdirectory = dirname($documenturl) . "/";
		generatePage($config['ftp_root_dir'], $docdirectory, $docfilename, $docId, "doc");
		echo sprintf($lang['admin']['settings_ftp_created'],$config['ftp_root_dir'].$docdirectory.$docfilename)." <br />";
	}
}

// disconnect from FTP server
FTPDisconnect($conn_id);

echo "<p><strong>".$lang['admin']['settings_ftp_complete']."</strong></p>";


// END OF SCRIPT!!



/**************** functions *****************************/

function generatePage($cubecartdir, $dir, $filename, $Id, $pagetype){
	global $conn_id, $config;

	// create directory structure
	FTPMkDir($conn_id, '/'.$config['ftp_root_dir'].$dir);

	// count directory deep levels
	$path		= split('/', $dir);
	$deep		= count($path);
	$homedir	= './';
	for ($i=1; $i<$deep; $i++) $homedir = $homedir . "../";

	// open file for writing
	// most likely it already exists need to delete it then so we can update it
	ftp_delete($conn_id, '/'.$config['ftp_root_dir'].$dir.$filename);
	$handle = fopen('ftp://'.$config['ftp_username'].':'.$config['ftp_password'].'@'.$config['ftp_server'].'/'.$cubecartdir.$dir.$filename, "w");
	if ($handle) {
		// generate correct page contents
		
		$head = "<?php\n//This is an automatic SEO Mod generated file. DO NOT MODIFY!\n\nchdir('$homedir');\n";
		$foot = "='".$Id."';\nrequire('index.php');\n?>";
		
		if (strcmp($pagetype, "prod") == 0) {
			$pagecontents = $head.'$_GET[\'_a\']=\'viewProd\';'."\n".'$_GET[\'productId\']'.$foot;
		} else if (strcmp($pagetype, "cat") == 0) {
			$pagecontents = $head.'$_GET[\'_a\']=\'viewCat\';'."\n".'$_GET[\'catId\']'.$foot;
		} else if (strcmp($pagetype, "taf") == 0) {
			$pagecontents = $head.'$_GET[\'_a\']=\'tellafriend\';'."\n".'$_GET[\'productId\']'.$foot;
		} else if (strcmp($pagetype, "doc") == 0) {
			$pagecontents = $head.'$_GET[\'_a\']=\'viewDoc\';'."\n".'$_GET[\'docId\']'.$foot;
		}
		
		// write and close file
		fwrite($handle, $pagecontents);
		fclose($handle);
	
		// lets change permissions to 755
		for ($i=0; $i<5; $i++) {
			## damn command can execute asychronous to above file creation meaning file might not be ready yet, simplest hack
			if (@ftp_site($conn_id, 'CHMOD 0755 '.'/'.$cubecartdir.$dir.$filename) == true) break;
			sleep(1);
		}
		// ok for some reason I can only assume the asychronous behaviour between the FTP library and fopen we get a filesize of 0
		// extremely rare but a pain in the neck nevertheless. If this happens redo this function. Simple hack
		if (@ftp_size($conn_id, '/'.$config['ftp_root_dir'].$dir.$filename) == 0) {
			generatePage($cubecartdir, $dir, $filename, $Id, $pagetype);
		}
		return true;
	}
	return false;
}

/***************** FTP functions ************************/

function FTPConnect($server, $username, $password) {
	$conn_id = ftp_connect($server);
	if ($conn_id) {
		if (@ftp_login($conn_id, $username, $password) == false) {
			@ftp_close($conn_id);
			$conn_id = false; // wrong details
		}
		return $conn_id;
	}
	return false;
}

function FTPDisconnect($conn_id){
	ftp_close($conn_id);
}

function FTPMkDir($conn_id, $path) {
	$dir	= split('/', $path);
	$path	= '';
	$ret	= true;
	for ($i=1; $i<count($dir); $i++) {
		$path .= '/'.$dir[$i];
		if (!ftp_chdir($conn_id, $path)) {
			ftp_chdir($conn_id, '/');
			if (!ftp_mkdir($conn_id, $path)) {
				$ret = false;
				break;
			}
			ftp_site($conn_id, 'CHMOD 0755 '.$path);
		}
	}
	return $ret;
} 

/* <rf> end mods */

?>
</span>