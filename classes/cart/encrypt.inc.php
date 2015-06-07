<?php
/*
+--------------------------------------------------------------------------
|	encrypt.inc.php
|   ========================================
|	Class Encrypts Data
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

class encryption {
	var $td;
	var $iv;
	var $ks;
	var $key;
	
	##############################################

	function __construct($keyArray = null) {
		$this->encryption($keyArray);
	}
	
	function encryption($keyArray = null) {
		$this->td	= mcrypt_module_open(MCRYPT_RIJNDAEL_256, '', 'ecb', '');
		$this->iv	= mcrypt_create_iv(mcrypt_enc_get_iv_size($this->td), MCRYPT_RAND);
		$this->ks	= mcrypt_enc_get_key_size($this->td);
		if (!is_null($keyArray) && is_array($keyArray)) $this->generateKey($keyArray);
	}
	
	##############################################
	
	function __destruct() {
		$this->close();
	}
	
	function close() {
		@mcrypt_module_close($this->td);
	}
	
	##############################################
	
	function generateKey($keyArray) {
		$this->key	= substr(md5(implode('@', $keyArray)), 0, $this->ks);
	}
	
	function encrypt($data) {
		mcrypt_generic_init($this->td, $this->key, $this->iv);
		$stringEncrypted = mcrypt_generic($this->td, $data);
		mcrypt_generic_deinit($this->td);
		return $stringEncrypted;
	}
	
	function decrypt($stringEncrypted) {
		if (!empty($stringEncrypted)) {
			mcrypt_generic_init($this->td, $this->key, $this->iv);
			$stringDecrypted = mdecrypt_generic($this->td, $stringEncrypted);
			mcrypt_generic_deinit($this->td);
			return trim($stringDecrypted);
		}
	}
}
?>