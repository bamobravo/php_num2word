<?php 
/**
* This class help convert a numberic value to it word equivalent
*/
class NumberToTextConverter 
{

	public function validateInput($input){
		$maxLength = 12;
		$signs = array('-','+');
		$input= (string)$input;
		if (in_array($input[0], $signs)) {
			$maxLength+=1;
		}
		$isNumeric =is_numeric($input);
		$isWithinLength = strlen($input)  <=$maxLength;
		return $isNumeric && $isWithinLength;
	}

	public function transformInput($input){
		$result = ltrim($input,' 0-+');
		return $result;
	}

	public function getlessThanTwenty($input){
		$units = array('','one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve','thirteen','fourteen','fifteen','sixteen','seventeen','eighteen','nineteen');
		$index = (int)$input;
		if ($index < 20) {
			return $units[$index];
		}
		return false;
	}
	public function getTens($input){
		if (strlen($input) <=2) {
			if ($input < 20) {
				return $this->getlessThanTwenty($input);
			}
			$tens=array('twenty','thirty','forty','fifty','sixty','seventy','eighty','ninety');
			$first = (int)$input[0];
			$firstPart = $tens[$first-2];
			$secondPart = $this->getlessThanTwenty($input[1]);
			return $firstPart.' '.$secondPart;
		}
		return false;
	}
	public function convertToWord($input){
		if ($this->validateInput($input)) {
			return $this->getMaxUnitValue($input);
		}
		return false;
	}

	public function getMaxUnitValue($value,$isRecurse=false){
		$highValues = array('thousand','million','billion');
		$value=$this->transformInput($value);
		$len = strlen($value);
		$prefix ='and ';
		if ($len <=2) {
			return $this->getTens($value);
		}
		if ($len ==3) {
			
			$first = $this->getTens($value[0]);
			$hundred = $first.' hundred';
			$tens = $this->getTens(substr($value, 1));
			$tens = empty($tens)?$tens:' and '.$tens;
			if ($isRecurse==false || !empty($tens)) {
				$prefix='';
			}
			return $prefix.$hundred.$tens;
		} 
		// $index = (($len-1)/2)-1;
		if ($len > 3 and $len <7) {
			$first = substr($value, 0,$len-3);
			$first = $this->getMaxUnitValue($first);
			$thousand= $first.' thousand';
			$hundred = $this->getMaxUnitValue(substr($value, 3),true);
			if ($isRecurse==false) {
					$prefix='';
				}
			if (!empty($hundred)) {
				if (strpos($hundred, 'and')===false) {
					$hundred='and '.$hundred;
				}
				$hundred =' '.$hundred;
				$prefix='';
			}
			return $prefix.$thousand.$hundred;
		}
		if ($len > 6 and $len <10) {
			$first = substr($value, 0,$len-6);
			$first = $this->getMaxUnitValue($first);
			$million= $first.' million';
			$thousand = $this->getMaxUnitValue(substr($value, -6),true);
			if ($isRecurse==false) {
					$prefix='';
				}
			if (!empty($thousand)) {
				if (strpos($thousand, 'and')===false) {
					$thousand='and '.$thousand;
				}
				$thousand =' '.$thousand;
				$prefix='';
			}
			return $prefix.$million.$thousand;
		}
		if ($len > 9 and $len <13) {
			$first = substr($value, 0,$len-9);
			$first = $this->getMaxUnitValue($first);
			$billion= $first.' billion';
			$million = $this->getMaxUnitValue(substr($value, -9),true);
			if ($isRecurse==false) {
					$prefix='';
				}
			if (!empty($million)) {
				if (strpos($million, 'and')===false) {
					$million='and '.$million;
				}
				$million =' '.$million;
				$prefix='';
			}
			return $prefix.$billion.$million;
		}
	}


}
 ?>