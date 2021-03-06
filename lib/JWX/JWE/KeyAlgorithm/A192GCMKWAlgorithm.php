<?php

namespace JWX\JWE\KeyAlgorithm;

use GCM\Cipher\AES\AES192Cipher;
use JWX\JWA\JWA;


/**
 * Implements key encryption with AES GCM using 192-bit key.
 *
 * @link https://tools.ietf.org/html/rfc7518#section-4.7
 */
class A192GCMKWAlgorithm extends AESGCMKWAlgorithm
{
	protected function _getGCMCipher() {
		return new AES192Cipher();
	}
	
	protected function _keySize() {
		return 24;
	}
	
	public function algorithmParamValue() {
		return JWA::ALGO_A192GCMKW;
	}
}
