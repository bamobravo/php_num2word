<?php 
/**
* This class help convert a numberic value to it word equivalent
*/

//code to test the function
$obj = new NumberToTextConverter();
$val = "999999999909909";
echo $obj->convertToWord($val)."\n";
class NumberToTextConverter 
{
	private $highValues ;
	function __construct($high=array('thousand','million','billion','trillion')){
		$this->highValues=$high;
	}
	public function validateInput($input){
		$maxLength = (count($this->highValues) + 1) *3;
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
			$separator =' ';
			return trim($firstPart.' '.$secondPart);
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
		$value=$this->transformInput($value);
		$len = strlen($value);
		$prefix ='and ';
		if ($len <=2) {
			return $this->getTens($value);
		}
		if ($len ==3) {
			return $this->getHundred($value,$prefix,$isRecurse);
		}
		return $this->getHigherDigitText($value,$prefix,$isRecurse);
	}

	private function getHigherDigitText($value,$prefix,$isRecurse){
		$len = strlen($value);
		$index = (int)(($len-1)/3)-1;
		$name = $this->highValues[$index];
		$nameSize = ($index+1)*3;
		$firstPart = substr($value,0, $len-$nameSize);
		$first = $this->getMaxUnitValue($firstPart);
		$first = $first.' '.$name;
		$secondPart = substr($value, $len-$nameSize);
		$second = $this->getMaxUnitValue($secondPart,true);
		$second = $this->processConjunction($second,$prefix,$isRecurse);
		return $prefix.$first.$second;
	}
	private function processConjunction($second,&$prefix,$isRecurse){
		if ($isRecurse==false) {
			$prefix='';
		}
		if (!empty($second)) {
			if (strpos($second, 'and')===false) {
				$second='and '.$second;
			}
			$second =' '.$second;
			$prefix='';
		}
		return $second;
	}
	private function getHundred($value,$prefix,$isRecurse){
		$first = $this->getTens($value[0]);
		$hundred = $first.' hundred';
		$tens = $this->getTens(substr($value, 1));
		$tens = empty($tens)?$tens:' and '.$tens;
		if ($isRecurse==false || !empty($tens)) {
			$prefix='';
		}
		return $prefix.$hundred.$tens;
	}
}
 ?>
