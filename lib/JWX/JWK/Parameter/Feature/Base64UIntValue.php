<?php

namespace JWX\JWK\Parameter\Feature;

use JWX\JWT\Parameter\Feature\Base64URLValue;
use JWX\Util\Base64;
use JWX\Util\BigInt;


/**
 * Trait for parameter having Base64urlUInt value.
 *
 * @link https://tools.ietf.org/html/rfc7518#section-2
 */
trait Base64UIntValue
{
	use Base64URLValue;
	
	/**
	 * Get the parameter value.
	 *
	 * @return string
	 */
	abstract public function value();
	
	/**
	 * Initialize parameter from base10 number.
	 *
	 * @param int|string $number
	 * @return self
	 */
	public static function fromNumber($number) {
		$data = BigInt::fromBase10($number)->base256();
		return self::fromString($data);
	}
	
	/**
	 * Get value as a number.
	 *
	 * @return BigInt
	 */
	public function number() {
		return BigInt::fromBase256(Base64::urlDecode($this->value()));
	}
}
