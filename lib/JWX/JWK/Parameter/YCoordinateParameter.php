<?php

namespace JWX\JWK\Parameter;


/**
 * Implements 'Y Coordinate' parameter.
 *
 * @link https://tools.ietf.org/html/rfc7518#section-6.2.1.3
 */
class YCoordinateParameter extends CoordinateParameter
{
	/**
	 * Constructor
	 *
	 * @param string $coord Y coordinate in base64url encoding
	 */
	public function __construct($coord) {
		$this->_validateEncoding($coord);
		parent::__construct(self::PARAM_Y_COORDINATE, $coord);
	}
}
