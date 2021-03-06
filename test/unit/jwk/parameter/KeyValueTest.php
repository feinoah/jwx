<?php

use JWX\JWK\Parameter\JWKParameter;
use JWX\JWK\Parameter\KeyValueParameter;


/**
 * @group jwk
 * @group parameter
 */
class KeyValueParameterTest extends PHPUnit_Framework_TestCase
{
	const KEY = "password";
	
	public function testCreate() {
		$param = KeyValueParameter::fromString(self::KEY);
		$this->assertInstanceOf(KeyValueParameter::class, $param);
		return $param;
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param JWKParameter $param
	 */
	public function testParamName(JWKParameter $param) {
		$this->assertEquals(JWKParameter::PARAM_KEY_VALUE, $param->name());
	}
	
	/**
	 * @depends testCreate
	 *
	 * @param KeyValueParameter $param
	 */
	public function testKey(KeyValueParameter $param) {
		$this->assertEquals(self::KEY, $param->key());
	}
}
