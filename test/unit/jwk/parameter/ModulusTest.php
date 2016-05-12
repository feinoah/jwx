<?php

use JWX\JWK\Parameter\JWKParameter;
use JWX\JWK\Parameter\ModulusParameter;
use JWX\JWK\Parameter\RegisteredJWKParameter;


/**
 * @group jwk
 * @group parameter
 */
class ModulusParameterTest extends PHPUnit_Framework_TestCase
{
	public function testCreate() {
		$param = ModulusParameter::fromNumber(123);
		$this->assertInstanceOf(ModulusParameter::class, $param);
		return $param;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param JWKParameter $param
	 */
	public function testParamName(JWKParameter $param) {
		$this->assertEquals(RegisteredJWKParameter::PARAM_MODULUS, 
			$param->name());
	}
}