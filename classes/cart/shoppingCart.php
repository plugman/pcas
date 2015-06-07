<?php
/*
+--------------------------------------------------------------------------
|	shoppingCart.php
|   ========================================
|	The Shopping Cart Class	
+--------------------------------------------------------------------------
*/
if (!defined('CC_INI_SET')) die("Access Denied");

class cart {

	var $cartArray;
	
	function cartContents($sqlValue) {
		if (!empty($sqlValue)) 		{
			$this->cartArray = unserialize(stripslashes($sqlValue));
			return $this->cartArray;
		}
		return false;
	}
	
	function add($productId, $quantity = 1, $options, $imei) {
		global $config, $db, $glob;
		$quantity	= (is_numeric($quantity) && $quantity > 0) ? $quantity : 1;
		$productKey	= $productId.':'.$imei.':'.$this->buildOptions($options);
		if (is_array($this->cartArray['conts'])) {
			$hash	= md5($productKey);
			foreach ($this->cartArray['conts'] as $key => $array) {
				if (md5($key) == $hash) {
					$this->cartArray['conts'][$key]['quantity'] += $quantity;
					$exists	= true;
					break;
				}
			}
			if (!isset($exists)) {
				$this->cartArray['conts'][$productKey]['quantity'] = $quantity;
			}
		} else {
			$this->cartArray['conts'][$productKey]['quantity'] = $quantity;
		}
		## New addition of timestamp!
		$this->cartArray['conts'][$productKey]['timestamp'] = time();
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}
	function addimei($productId, $quantity = 1, $options, $imei) {
		global $config, $db, $glob;
		$quantity	= (is_numeric($quantity) && $quantity > 0) ? $quantity : 1;
		$productKey	= $productId.':'.$imei.':'.$this->buildOptions($options);
		if (is_array($this->cartArray['conts'])) {
			$hash	= md5($productKey);
			foreach ($this->cartArray['conts'] as $key => $array) {
				if (md5($key) == $hash) {
					$this->cartArray['conts'][$key]['quantity'] = 1;
					$exists	= true;
					break;
				}
			}
			if (!isset($exists)) {
				$this->cartArray['conts'][$productKey]['quantity'] = $quantity;
			}
		} else {
			$this->cartArray['conts'][$productKey]['quantity'] = $quantity;
		}
		## New addition of timestamp!
		$this->cartArray['conts'][$productKey]['timestamp'] = time();
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}

	
	function removeLastItem() {
		$maxTimestamp = 0;
		if(is_array($this->cartArray['conts'])){
			foreach($this->cartArray['conts'] as $key => $value){
				$removalKey = ($value['timestamp']>$maxTimestamp) ? $key : $removalKey;
			}
		}
		$this->remove(md5($removalKey));	
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}

	function addCert($certArray) {
		
		// SO: FIX FOR CHECKOUT FLOW
		/* AL: Removed for 1 step CO
		$this->setVar(1,"currentStep");
		$this->setVar(2,"stepLimit");
		*/
		// EO: FIX FOR CHECKOUT FLOW
		
		$randCode = 'v'.time().'-'.rand(1000, 9999);
		$this->cartArray['conts'][$randCode]['custom']		= 1;
		$this->cartArray['conts'][$randCode]['quantity']	= 1;
		array_walk($certArray, array(&$this, 'sanitize'));
		$this->cartArray['conts'][$randCode]['gcInfo']		= $certArray;
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}
		function addCase($certArray) {
		$randCode = 'v'.time().'-'.rand(1000, 9999);
		$this->cartArray['conts'][$randCode]['custom']		= 2;
		$this->cartArray['conts'][$randCode]['quantity']	= 1;
		array_walk($certArray, array(&$this, 'sanitize'));
		$this->cartArray['conts'][$randCode]['caseInfo']		= $certArray;
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}
	
	function sanitize(&$value, $key) {
		$value = stripslashes(html_entity_decode_utf8($value, ENT_COMPAT, 'UTF-8'));
		$value = htmlentities(strip_tags($value), ENT_QUOTES, 'UTF-8');
	}
	
	function setVar($var, $varName, $arrayName = '',$i = '') {
		
		## unset old delivery address and add new
		if (is_array($var)) {
			foreach ($var as $key => $value) {
				//$var[$key] = htmlspecialchars(stripslashes(strip_tags($value)), ENT_QUOTES, 'UTF-8');
				$var[$key] = stripslashes(strip_tags(str_replace(array("\'", "'"), "&#39;",$value)));
			} 
		} elseif(!empty($value)) {
		#	$var = str_replace(array("\'", "'"), "&#39;", stripslashes(strip_tags($var)));
			//$var = htmlspecialchars(stripslashes(strip_tags($var)), ENT_QUOTES, 'UTF-8');
			$var[$key] = stripslashes(strip_tags($value));
		}
		
		if (empty($arrayName)) {
			unset($this->cartArray[$varName]);
			$this->cartArray[$varName] = $var;
			
			return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
		} else {
			if (isset($this->cartArray[$arrayName][$i][$varName])) { 
				unset($this->cartArray[$arrayName][$i][$varName]); 
			}
			$this->cartArray[$arrayName][$i][$varName] = $var;
			return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
		}
	}
	
	function unsetVar($varName) {
		unset($this->cartArray[$varName]);
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}
	
	function remove($productKey) {
		global $config, $db, $glob;
		
		if (is_array($this->cartArray['conts'])) {
			foreach ($this->cartArray['conts'] as $key => $array) {
				if ($productKey == md5($key)) {
					unset($this->cartArray['conts'][$key]);
					break;
				}
			}
		}
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
		
		## Old Code
		/*
		$productId = $this->getProductId($productKey);
		$quantity = $this->cartArray['conts'][$productKey]['quantity'];
		## stock on add to basket for later version maybe
		//this->stock($productId, $quantity,"+");
		unset($this->cartArray['conts'][$productKey]);
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
		*/
	}
	
	function update($productKey, $quantity) {
		$quantity		= ceil($quantity);
		$productId		= $this->getProductId($productKey);		
		$quantityOld	= $this->cartArray['conts'][$productKey]['quantity'];
		
		if (is_array($this->cartArray['conts'])) {
			foreach ($this->cartArray['conts'] as $key => $array) {
				if ($productKey == md5($key)) {
					if ($quantity <= 0) {
						unset($this->cartArray['conts'][$key]);
					} else {
						$this->cartArray['conts'][$key]['quantity'] = $quantity;
					}
					break;
				}
			}
		} 
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
		
		/*
		if ($quantity<$quantityOld) {
			## put them back
			$difference = $quantityOld - $quantity;
			$sign = "+";
		} else if ($quantity>$quantityOld) {
			## take some more
			$difference = $quantity - $quantityOld;
			$sign = "-";
		}
		$this->stock($productId, $difference, $sign);
		*/
		
		/*
		if ($quantity > 0) {
			$this->cartArray['conts'][$productKey]['quantity'] = $quantity;
		} else {
			unset($this->cartArray['conts'][$productKey]);
		}
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
		*/
	}
	
	function sqlValue() {
		global $db, $glob;
		$cartData['basket'] = "'".serialize($this->cartArray)."'";
		## sync database to array
		$update		= $db->update($glob['dbprefix']."ImeiUnlock_sessions", $cartData, 'sessId='.$db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
		$query		= sprintf("SELECT basket FROM %sImeiUnlock_sessions WHERE `sessId`=%s", $glob['dbprefix'], $db->mySQLSafe($GLOBALS[CC_SESSION_NAME]));
        $checkCart	= $db->select($query);
        return ($checkCart && unserialize($checkCart[0]['basket'])===$this->cartArray) ? true : false;
	}
	
	function noItems() {
		$total = 0;
		if (is_array($this->cartArray['conts'])) {
			foreach ($this->cartArray['conts'] as $key => $value) {
				$total = $this->cartArray['conts'][$key]['quantity'] + $total;
			}
			return $total;
		}
		return $total;
	}
	
	function buildOptions($options) {
		if (is_array($options)) {			
			foreach ($options as $key => $value) {
				if (!empty($value)) {
					$value = htmlentities(stripslashes($value),ENT_QUOTES,"UTF-8");
					$value = str_replace(array('{@}','{|}'),array('@','|'),$value);
					$option[] = $key.'{@}'.$value;
				}
			}
			
			if (!empty($option)) {
				return implode('{|}', $option);
			}
		}
		return false;
	}
	
	function getOptions($productKey) {
		$options = explode(":", $productKey);
		return html_entity_decode($options[2],ENT_QUOTES,"UTF-8");
	}
	
	function getProductId($productKey) {
		$options = explode(":", $productKey);
		return $options[0];
	}
	
	function getIMEI($productKey) {
		$options = explode(":", $productKey);
		return $options[1];
	}	
	/* Maybe for a later version gets v complex 
	function returnStock() {
		global $db, $glob;
		if (is_array($this->cartArray['conts'])) {
			foreach ($this->cartArray['conts'] as $key => $value) {
				
				$prodId = $this->getProductId($key);
		
				## put the products back
				$useStock = $db->select("SELECT useStockLevel FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productId = ".$prodId);
						
				if($useStock[0]['useStockLevel']==1 && $config['stock_change_time']==0){
					$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET stock_level = stock_level + ".$this->cartArray['conts'][$key]['quantity']." WHERE productId = ".$prodId;
					$db->misc($query);
				}
			}
		}
	}
	*/
	function emptyCart($keepStock = false) {
		global $config;
		
		## lets see if we need to return stock
		/* For a later release maybe
		if ($keepStock == false && $config['stock_change_time'] == 1) {
			$this->returnStock();
		}
		*/
		if (isset($this->cartArray['code']) && !empty($this->cartArray['code'])) {
			$this->removeCoupon($this->cartArray['code']);
		}
		unset($this->cartArray);
		return (!$this->sqlValue()) ? $this->error() : $this->cartArray;
	}
	
	function addCoupon($code) {
		global $glob,$config,$db;	
		
		## Look up code
		$coupon = $db->select("SELECT * FROM ".$glob['dbprefix']."ImeiUnlock_Coupons WHERE code = ".$db->mySQLSafe($code)." AND status = 1");
		## Validate
		if ($coupon) {
			if($coupon[0]['discount_percent'] == 0 && $coupon[0]['discount_price'] == 0) {
				## coupon expired
				$this->setVar(2, "codeResult");
			} elseif ($coupon[0]['allowed_uses'] > 0 && $coupon[0]['count'] == $coupon[0]['allowed_uses']) {
				## used too many times
				$this->setVar(1, "codeResult");
			} else if (!empty($coupon[0]['expires']) && (strtotime($coupon[0]['expires']) < time())) {
				## coupon expired
				$this->setVar(2, "codeResult");
			} else {
				## success
				$this->setVar(0,"codeResult");
				$this->setVar($coupon[0]['code'],"code");
				$this->setVar($coupon[0]['discount_percent'],"discount_percent");
				$this->setVar($coupon[0]['discount_price'],"discount_price");
				
				## Will have a cart id if it is a gift certificate therefore if subtotal < gift cert it needs to retan value
				if(!empty($coupon[0]['cart_order_id'])) {
					$this->setVar(true,"code_is_purchased");
				}
				
				## add count = count + 1
				$record['count'] = "count + 1";
				$where = "id = ".$coupon[0]['id'];
				$update = $db->update($glob['dbprefix']."ImeiUnlock_Coupons", $record, $where);
			}
		} else {
			## coupon not found
			$this->setVar(3, "codeResult");
		}
		return $this->cartArray;
	}
	
	function removeCoupon($code) {
		global $glob, $config, $db;
		
		if ($this->cartArray['code_is_purchased']) {
			$record['discount_price'] = $db->mySQLSafe($this->cartArray["discount_price"]);	
		}
		## subtract count = count - 1
		$record['count'] = "count - 1";
		
		$where = "code = ".$db->mySQLSafe(base64_decode($code));
		$update = $db->update($glob['dbprefix']."ImeiUnlock_Coupons", $record, $where);
		
		$this->unsetVar("codeResult");
		$this->unsetVar("code");
		$this->unsetVar("discount_percent");
		$this->unsetVar("discount_price");
		$this->unsetVar("code_is_purchased");
		
		return $this->cartArray;
	}
	
	function addByCode($code) {
		global $glob, $config, $db;
		$result = $db->select("SELECT productId, stock_level FROM ".$glob['dbprefix']."ImeiUnlock_inventory WHERE productCode = ".$db->mySQLSafe($code));
		if ($result) {
			## check for product options (if so go to view product page)
			$noOpts = $db->numrows("SELECT product FROM ".$glob['dbprefix']."ImeiUnlock_options_bot WHERE product = ".$db->mySQLSafe($result[0]['productId']));
			if ($noOpts > 0) {
				if ($config['sef']) {
					httpredir(generateProductUrl($result[0]['productId']));
				} else {
					httpredir('index.php?_a=viewProd&productId='.$result[0]['productId'].'&notice=1');
				}
			} else {
				## Check 
				if (($config['outofstockPurchase'] || $result[0]['useStockLevel']) && $result[0]['stock_level'] <= 0) {
					if ($config['sef']) {
						httpredir(generateProductUrl($result[0]['productId']));
					} else {
						httpredir('index.php?_a=viewProd&productId='.$result[0]['productId'].'&notice=1');
					}
				} else {
					$this->add($result[0]['productId'], 1, null);
					httpredir(currentPage());
				}
			}
		}
	}
	
	/* maybe for a later version
	function stock($productId, $quantity, $sign) {
		global $config,$glob,$db;
		if ($quantity>0) {
			## check product is set to use stock control
			$stock = $db->select("SELECT useStockLevel, stock_level FROM ".$glob['dbprefix']."ImeiUnlock_inventory","productId = ".$db->mySQLSafe($productId));
			## change stock if product is set to use stock control
			if($config['stock_change_time']==1 && $stock[0]['useStockLevel']==1) {
				$query = "UPDATE ".$glob['dbprefix']."ImeiUnlock_inventory SET stock_level = stock_level ".$sign." ".$quantity." WHERE productId = ".$db->mySQLSafe($productId);
				$update = $db->misc($query);
			}
		}
	}
	*/
	
	function error() {
		return "<b style='font-family: Arial, Helvetica, sans-serif; color: #0B70CE;'>Cart Error</b><br />\n<span style='font-family: Arial, Helvetica, sans-serif; color: #000000;'>There was an error updating the basket.</span><br />\n";
	}
	
}
?>