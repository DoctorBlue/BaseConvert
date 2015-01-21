<?php
	require __DIR__ . "/../src/BaseConvert.php";
	use \DoctorBlue\BaseConvert;

	class BaseConvertTest extends PHPUnit_Framework_TestCase {
		public function testEncode() {
			$alphabet = "aBCFWHesliRX";
			$decimal = 73686;
			$result = BaseConvert::encode($decimal, $alphabet);
			$this->assertEquals($result, "Fesle");
		}
		
		public function testDecode() {
			$alphabet = "aBCFWHesliRX";
			$string = "Fesle";
			$result = BaseConvert::decode($string, $alphabet);
			$this->assertEquals($result, 73686);
		}
		
		/**
		 * @depends testEncode
		 */
		public function testDec_hex() {
			$decimal = 73686;
			$result = BaseConvert::dec_hex($decimal);
			$this->assertEquals($result, "11FD6");
		}
		
		/**
		 * @depends testDecode
		 */
		public function testHex_dec() {
			$hex = "11FD6";
			$result = BaseConvert::hex_dec($hex);
			$this->assertEquals($result, 73686);
		}
		
		/**
		 * @depends testEncode
		 */
		public function testDec_base58() {
			$dec = 73686;
			$result = BaseConvert::dec_base58($dec);
			$this->assertEquals($result, "NuT");
		}
		
		/**
		 * @depends testDecode
		 */
		public function testBase58_dec() {
			$b58 = "NuT";
			$result = BaseConvert::base58_dec($b58);
			$this->assertEquals($result, 73686);
		}
		
		/**
		 * @depends testDec_hex
		 * @depends testBase58_dec
		 */
		public function testHex_base58() {
			$hex = "11FD6";
			$result = BaseConvert::hex_base58($hex);
			$this->assertEquals($result, "NuT");
		}
		
		/**
		 * @depends testBase58_dec
		 * @depends testDec_hex
		 */
		public function testBase58_hex() {
			$b58 = "NuT";
			$result = BaseConvert::base58_hex($b58);
			$this->assertEquals($result, "11FD6");
		}
	}