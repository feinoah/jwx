<?php

namespace JWX\JWT\Parameter;


/**
 * Type parameter
 *
 * @link https://tools.ietf.org/html/rfc7515#section-4.1.9
 */
class TypeParameter extends RegisteredJWTParameter
{
	/**
	 * Constructor
	 *
	 * @param string $type
	 */
	public function __construct($type) {
		parent::__construct(self::PARAM_TYPE, (string) $type);
	}
}
