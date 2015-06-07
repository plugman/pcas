<?php
/*
+--------------------------------------------------------------------------
|	validateCard.php
|   ========================================
|	Class to Validate Credit Card
+--------------------------------------------------------------------------
*/

if (!defined('CC_INI_SET')) die("Access Denied");

class validateCard {

	private $data;
	private $valid = false;
	private $issueDate = false;
	private $issueNo = false;
	
	public function check($cardNo, $issueNo='', $issueDate='', $issueFormat=4, $expireDate='', $expireFormat=4, $scReqd=false, $securityCode='') {
	
		## Card Validation RegEx
		$cardArray = array(
			'AMERICAN EXPRESS'		=> '#^[34|37|47]([0-9]{14}|[0-9]{13}|[0-9]{12})$#',
			'MASTERCARD'			=> '#^5[1-5][0-9]{14}$#',
			'VISA'					=> '#^4[0-9]{12}([0-9]{3})?$#',
			
			'AUSTRALIAN BANK CARD'	=> '#^5610([0-9]{12})?$#',
			'DELTA'					=> '#^(41373[3-7]{1}|4462[0-9]{2}|45397[8-9]{1}|454313|45443[2-5]{1}|454742|45672[5-9]{1}|45673[0-9]{1}|45674[0-5]{1}|4658[3-7]{1}[0-9]{1}|4659[0-5]{1}[0-9]{1}|4609[6-7]{1}[0-9]{1}|49218[1-2]{1}|498824)([0-9]{10})?$#',
			'DINERS'				=> '#^3(0[0-5]|[68][0-9])[0-9]{11}$#',
			'DISCOVER'				=> '#^6011[0-9]{12}$#',
			'ELECTRON'				=> '#^(450875|48440[6-9]{1}|4844[1-4]{1}[0-9]{1}|48445[0-5]{1}|4917[3-5]{1}[0-9]{1}|491880|5[1-5]{1})([0-9]{10}|[0-9]{14})?$#',
			
			'ENROUTE'				=> '#^(2014|2149)([0-9]{11})?$#',
			
			'JCB'					=> '#^(3[0-9]{4}|2131|1800)[0-9]{11}$#',
			'LASER'					=> '#^(6304|6706|6771|6709)([0-9]{12,15})$#',
			'MAESTRO'				=> '#^(5000[0-9]{2}|5[6-8]{1}|6[0-9]{5})([0-9]{10}|[0-9]{14})?$#',
			'SOLO'					=> '#^(6334[5-9]{1}[0-9]{1}|6767[0-9]{2}|3528[0-9]{2})([0-9]{10})?$#',
			'SWITCH'				=> '#^(49030[2-9]{1}|49033[5-9]{1}|49110[1-2]{1}|49117[4-9]{1}|49118[0-2]{1}|4936[0-9]{2}|564182|6333[1-4]{1}[0-9]{1}|6759[0-9]{2})([0-9]{10}|[0-9]{12}|[0-9]{13})?$#',
		
		);
		## List of cards requiring issue dates/numbers
		$issueDateArray = array(
			'MAESTRO',
			'SOLO',
			'SWITCH',
		);
		## List of card that DON'T need to be mod10'd
		$noChecksum = array(
			'AUSTRALIAN BANK CARD',
			'DELTA',
			'ELECTRON',
			'ENROUTE',
		);
		## Strip everything that isn't numeric
		$cardNo = trim(preg_replace('#[^0-9]#', '', $cardNo));
		## Assume success unless a rule is broken
		$this->data['response'] = "SUCCESS";
		## Check expire date (always required)
		$this->expireDate($expireDate,$expireFormat);
		## Check the security code
		if ($scReqd) $this->securityCode($securityCode);
		
		if (empty($cardNo)) {
			$this->error(6);
		} else {
			foreach ($cardArray as $type => $regex) {
				if (preg_match($regex, $cardNo)) {
					
					if (!in_array($type, $noChecksum) && strlen($cardNo) != 13) $this->mod10($cardNo);
					if (in_array($type, $issueDateArray) && (!$this->issueDate($issueDate,$issueFormat) || !$this->issueNo($issueNo))) $this->error(3);
					
					$this->data['cardType']	= $type;
					$this->valid			= true;
					break;
				}
			}
		}
		if (!$this->valid) $this->error(1);
		return $this->data;
	}
	
	private function mod10($cardnumber) {
		$cardnumber	= preg_replace("#[^0-9]#", "", $cardnumber);  # strip any non-digits
		$cardlength	= strlen($cardnumber);
		$parity		= $cardlength % 2;
		$sum		= 0;
		
		for ($i = 0; $i<$cardlength; $i++) {
			$digit = $cardnumber[$i];
			if ($i%2 == $parity) $digit = $digit*2;
			if ($digit>9) $digit -= 9;
			$sum += $digit;
		}
		if ($sum%10) {
			$this->error(5);
			return false;
		}
		return true;
	}
	
	private function expireDate($expireDate,$expireFormat){
		if (strlen($expireDate) !== $expireFormat) {
			$this->error(2);
			return false;
		} else if ($expireFormat == 4 && $expireDate<date("ym")) {
			$this->error(2);
			return false;
		} else if ($expireFormat == 6 && $expireDate<date("Ym")) {
			$this->error(2);
			return false;
		}
		return true;
	}
	
	private function issueDate($issueDate,$issueFormat) {
		if($this->issueNo) return true;
		if (strlen($issueDate) !== $issueFormat) {
			$this->error(3);
			return false;
		} else if ($issueFormat == 4 && $issueDate>date("ym")) {
			$this->error(3);
			return false;
		} else if ($issueFormat == 6 && $issueDate>date("Ym")) {
			$this->error(3);
			return false;
		}
		$this->issueDate = true;
		return true;
	}
	
	private function issueNo($issueNo) {
		if($this->issueDate) return true;
		if(is_numeric($issueNo) && $issueNo>0){
			$this->issueNo = true;
			return true;
		} else {
			$this->error(3);
			return false;
		}
	}
	
	private function securityCode($securityCode) {
		if (is_numeric($securityCode) && (strlen($securityCode)==3 || strlen($securityCode)==4)) {
			return true;
		} else {
			$this->error(4);
			return false;
		}
	}
	
	private function error($errorCode) {
		global $lang;
		$this->data['response'] = 'FAIL';
		switch($errorCode) {
			case 1:
				$this->data['error'][1] = "Card not recognised!";
				break;
			case 2:
				$this->data['error'][2] = "No expiry date was entered or it wasn't valid.";
				break;
			case 3:
				$this->data['error'][3] = "Please enter a valid issue number or date.";
				break;
			case 4:
				$this->data['error'][4] = "Please enter a valid security code.";
				break;
			case 5:
				$this->data['error'][5] = "Credit card number is not valid.";
				break;
			case 6:
				$this->data['error'][6] = "Please enter a card number.";
			break;
		}
	}
}

?>