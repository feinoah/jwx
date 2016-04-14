<?php

namespace JWX\JWT\Parameter;


/**
 * Content Type parameter
 *
 * @link https://tools.ietf.org/html/rfc7515#section-4.1.10
 */
class ContentTypeParameter extends RegisteredJWTParameter
{
	/**
	 * Constructor
	 *
	 * @param string $type
	 */
	public function __construct($type) {
		parent::__construct(self::PARAM_CONTENT_TYPE, $type);
	}
}
