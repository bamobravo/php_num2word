<?php 
require "src/NumberToTextConverter.php";
	use PHPUnit\Framework\TestCase;
	/**
	* The is a test class for the number to string converter, this is just
	* a programming exercise  for test driven development training.
	*/
	class NumberToTextConvertTest extends TestCase
	{
		
		//function to generate the object needed so that object recreation will not be necessary
		//seet of function to test the input validation
		private function createObject($val=false){
			$result = $val?new NumberToTextConverter($val):new NumberToTextConverter();
			return $result;
		}
		public function testValidateInputNonNumeric(){
			$object =  $this->createObject();
			$value = '676r6';
			$this->assertFalse($object->validateInput($value));
		}
		public function testValidateInputIsOk(){
			$obj = $this->createObject();
			$value ='1234';
			$this->assertTrue($obj->validateInput($value));
		}
		public function testValidateInputWithinSize(){
			$obj =$this->createObject();
			$value='999999999999';
			$this->assertTrue($obj->validateInput($value));
		}
		public function testValidateInputOutOfRange(){
			$obj =$this->createObject();
			$value='99999999999900';
			$this->assertFalse($obj->validateInput($value));
		}
		public function testValidateInputWithNegativeSign(){
			$obj = $this->createObject();
			$value = '-999999999999';
			$this->assertTrue($obj->validateInput($value));
		}
		public function testValidateInputWithNegativeSignAndOutOfRange(){
			$obj = $this->createObject();
			$value = '-9999999999990900';
			$this->assertFalse($obj->validateInput($value));
		}
		public function testValidateInputWithPositiveSign(){
			$obj = $this->createObject();
			$value = '+999999999999';
			$this->assertTrue($obj->validateInput($value));
		}
		public function testValidateInputWithPositiveSignAndOutOfRange(){
			$obj = $this->createObject();
			$value = '+99999999999999';
			$this->assertFalse($obj->validateInput($value));
		}

		//set of function to validate the transformation of input to usable one
		public function testReformatInputwithLeadingOneZero(){
			$obj = $this->createObject();
			$value ='0567567';
			$expected = '567567';
			$actual = $obj->transformInput($value);
			$output = $expected ===$expected;
			$this->assertTrue($output);
		}

		public function testReformatInputWithLeadingManyZeros(){
			$obj = $this->createObject();
			$value ='0000010000';
			$expected = '10000';
			$actual = $obj->transformInput($value);
			$output = $expected===$actual;
			$this->assertTrue($output);
		}
		public function testReformatInputwithLeadingManyZeroWithPositiveSign(){
				$obj = $this->createObject();
				$value ='+0000010000';
				$expected = '10000';
				$actual = $obj->transformInput($value);
				$output = $expected===$actual;
				$this->assertTrue($output);
		}

		//set of function for breaking the input into different unit
		public function testSingeUnitOutputTen(){
			$obj = $this->createObject();
			$value = 7;
			$expected = 'seven';
			$actual=$obj->getlessThanTwenty($value);
			$this->assertEquals($expected,$actual);
		}

		public function testTwoUnitOutput(){
			$obj = $this->createObject();
			$value= '13';
			$expected='thirteen';
			$actual= $obj->getlessThanTwenty($value);
			$this->assertEquals($expected,$actual);
		}
		public function testTwoUnitOutputTwentyAbove(){
			$obj = $this->createObject();
			$value= '23';
			$expected='twenty three';
			$actual= $obj->getTens($value);
			$this->assertEquals($expected,$actual);
		}

		public function testTwoUnitMaxValue(){
			$obj = $this->createObject();
			$value= '99';
			$expected='ninety nine';
			$actual= $obj->getTens($value);
			$this->assertEquals($expected,$actual);
		}
		public function testTwoDigitOutOfRange(){
			$obj = $this->createObject();
			$value= '100';
			$expected=false;
			$actual= $obj->getTens($value);
			$this->assertEquals($expected,$actual);
		}

		public function testThreeDigitsBeginning(){
			$obj = $this->createObject();
			$value= '100';
			$expected='one hundred';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}
		public function testThreeDigitsEnd(){
			$obj = $this->createObject();
			$value= '999';
			$expected='nine hundred and ninety nine';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}
		public function testFourDigitBeginning(){
			$obj = $this->createObject();
			$value= '1000';
			$expected='one thousand';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}

		public function testFourDigitEnd(){
			$obj = $this->createObject();
			$value= '999999';
			$expected='nine hundred and ninety nine thousand nine hundred and ninety nine';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}

		public function testDigitsLikeRandom(){
			$obj = $this->createObject();
			$value= '100100';
			$expected='one hundred thousand and one hundred';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}


		public function testMillionStart(){
			$obj = $this->createObject();
			$value= '1000000';
			$expected='one million';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}
		public function testMillionEnd(){
			$obj = $this->createObject();
			$value= '999000001';
			$expected='nine hundred and ninety nine million and one';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}

		public function testBillionStart(){
			$obj = $this->createObject();
			$value= '1000000000';
			$expected='one billion';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}
		public function testBillionEnd(){
			$obj = $this->createObject();
			$value= '999000000001';
			$expected='nine hundred and ninety nine billion and one';
			$actual= $obj->getMaxUnitValue($value);
			$this->assertEquals($expected,$actual);
		}
		public function testOverallMethod(){
			$obj = $this->createObject();
			$value= '999000000001';
			$expected='nine hundred and ninety nine billion and one';
			$actual= $obj->convertToWord($value);
			$this->assertEquals($expected,$actual);
		}
		public function testInputValidationWithConversionResult(){
			$obj = $this->createObject();
			$value= '999000000001987';
			$actual= $obj->convertToWord($value);
			$this->assertFalse($actual);
		}
		public function testInputSizeExpansion(){
			$obj = $this->createObject(array('thousand','million','billion','trillion'));
			$value= '990000100001987';
			$actual= $obj->convertToWord($value);
			$expected="nine hundred and ninety trillion one hundred million one thousand nine hundred and eighty seven";
			$this->assertEquals($expected,$actual);
		}
	}

 ?>
