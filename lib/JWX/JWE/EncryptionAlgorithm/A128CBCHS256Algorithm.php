<?php

namespace JWX\JWE\EncryptionAlgorithm;

use JWX\JWA\JWA;


/**
 * Implements AES with 128-bit key in CBC mode with HMAC SHA-256 authentication.
 *
 * @link https://tools.ietf.org/html/rfc7518#section-5.2.3
 */
class A128CBCHS256Algorithm extends AESCBCAlgorithm
{
	public function keySize() {
		return 32;
	}
	
	public function encryptionAlgorithmParamValue() {
		return JWA::ALGO_A128CBC_HS256;
	}
	
	protected function _cipherMethod() {
		return "AES-128-CBC";
	}
	
	protected function _hashAlgo() {
		return "sha256";
	}
	
	protected function _encKeyLen() {
		return 16;
	}
	
	protected function _macKeyLen() {
		return 16;
	}
	
	protected function _tagLen() {
		return 16;
	}
}
