<?php

namespace JWX\JWE\KeyAlgorithm;

use AESKW\AESKW256;
use JWX\JWA\JWA;


/**
 * Implements AES key wrap with 256-bit key.
 *
 * @link https://tools.ietf.org/html/rfc7518#section-4.4
 */
class A256KWAlgorithm extends AESKWAlgorithm
{
	protected function _kekSize() {
		return 32;
	}
	
	protected function _AESKWAlgo() {
		return new AESKW256();
	}
	
	public function algorithmParamValue() {
		return JWA::ALGO_A256KW;
	}
}
