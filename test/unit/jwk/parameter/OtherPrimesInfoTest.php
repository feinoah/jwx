<?php

use JWX\JWK\Parameter\JWKParameter;
use JWX\JWK\Parameter\OtherPrimesInfoParameter;
use JWX\JWK\Parameter\RegisteredJWKParameter;


/**
 * @group jwk
 * @group parameter
 */
class OtherPrimesInfoParameterTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$param = new OtherPrimesInfoParameter();
		$this->assertInstanceOf(OtherPrimesInfoParameter::class, $param);
		return $param;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param JWKParameter $param
	 */
	public function testParamName(JWKParameter $param) {
		$this->assertEquals(RegisteredJWKParameter::PARAM_OTHER_PRIMES_INFO, 
			$param->name());
	}
}