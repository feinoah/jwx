<?php

use JWX\JWA\JWA;
use JWX\JWE\EncryptionAlgorithm\A128CBCHS256Algorithm;
use JWX\JWT\Parameter\EncryptionAlgorithmParameter;
use JWX\JWT\Parameter\JWTParameter;


/**
 * @group jwt
 * @group parameter
 */
class EncryptionAlgorithmParameterTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$param = new EncryptionAlgorithmParameter(JWA::ALGO_A128CBC_HS256);
		$this->assertInstanceOf(EncryptionAlgorithmParameter::class, $param);
		return $param;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param JWKParameter $param
	 */
	public function testParamName(JWTParameter $param) {
		$this->assertEquals(JWTParameter::PARAM_ENCRYPTION_ALGORITHM, 
			$param->name());
	}
	
	public function testFromAlgo() {
		$param = EncryptionAlgorithmParameter::fromAlgorithm(
			new A128CBCHS256Algorithm());
		$this->assertInstanceOf(EncryptionAlgorithmParameter::class, $param);
	}
}
