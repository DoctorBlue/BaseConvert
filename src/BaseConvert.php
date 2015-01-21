<?php
	namespace DoctorBlue;

	/**
	 * This library facilitates conversions between base 10 and 16
	 * along with Bitcoin compatible base58. Additionally the library
	 * exposes a method that can be used to convert using an arbitrary
	 * alphabet. For example, decimal 60 can be converted to octal like so:
	 * BaseConvert::encode(60, "01234567"); // Returns "74"
	 *
	 * Octal 74 (decimal 60) can be converted back to decimal:
	 * BaseConvert::decode(74, "01234567"); // Returns "60"
	 */
	class BaseConvert {
		/**
		 * encode
		 * Allows encoding a decimal integer using an arbitrary base string
		 * 
		 * @param	int $num
		 * @param	string $basestr
		 * @return	string
		 */
		public static function encode($num, $basestr) {
			if( ! function_exists('bcadd') ) {
				Throw new Exception('You need the BCmath extension.');
			}

			$base = strlen($basestr);
			$rep = '';

			while( true ){
				if( strlen($num) < 2 ) {
					if( intval($num) <= 0 ) {
						break;
					}
				}
				$rem = bcmod($num, $base);
				$rep = $basestr[intval($rem)] . $rep;
				$num = bcdiv(bcsub($num, $rem), $base);
			}
			return $rep;
		}

		/**
		 * decode
		 * Allows decoding a string to decimal using an arbitrary base string
		 * 
		 * @param	string $num
		 * @param	string $basestr
		 * @return	int
		 */
		public static function decode($num, $basestr) {
			if( ! function_exists('bcadd') ) {
				Throw new Exception('You need the BCmath extension.');
			}

			$base = strlen($basestr);
			$dec = '0';

			$num_arr = str_split((string)$num);
			$cnt = strlen($num);
			for($i=0; $i < $cnt; $i++) {
				$pos = strpos($basestr, $num_arr[$i]);
				if( $pos === false ) {
					Throw new Exception(sprintf('Unknown character %s at offset %d', $num_arr[$i], $i));
				}
				$dec = bcadd(bcmul($dec, $base), $pos);
			}
			return $dec;
		}
		
		/**
		 * dec_hex
		 * Convert a decimal integer to an uppercase hexadecimal string
		 * @param	int $num
		 * @return	string
		 */
		public static function dec_hex($num) {
			return self::encode($num, '0123456789ABCDEF');
		}
		
		/**
		 * hex_dec
		 * Convert an uppercase hexadecimal string to a decimal integer
		 * @param	string $num
		 * @return	int
		 */
		public static function hex_dec($num) {
			return self::decode(strtoupper($num), '0123456789ABCDEF');
		}
		
		/**
		 * dec_base58
		 * Convert a decimal integer to a bitcoin-compatible base58 string
		 * @param	int $num
		 * @return	string
		 */
		public static function dec_base58($num) {   
			return self::encode($num, '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz');
		}
		
		/**
		 * base58_dec
		 * Convert a bitcoin-compatible base58 string to a decimal integer
		 * @param	string $num
		 * @return	int
		 */
		public static function base58_dec($num) {
			return self::decode($num, '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz');
		}
		
		/**
		 * hex_base58
		 * Convert an uppercase hexadecimal string to a bitcoin-compatible base58 string
		 * @param	string $num
		 * @return	string
		 */
		public static function hex_base58($num){
			return self::dec_base58(self::hex_dec($num));
		}
		
		/**
		 * base58_hex
		 * Convert a bitcoin-compatible base58 string to an uppercase hexadecimal string
		 * @param	string $num
		 * @return	string
		 */
		public static function base58_hex($num){
			return self::dec_hex(self::base58_dec($num));
		}
	}