<?php

namespace JWX\JWK\Parameter;

use JWX\Parameter\Feature\Base64URLValue;


/**
 * Implements 'X.509 Certificate SHA-256 Thumbprint' parameter.
 *
 * @link https://tools.ietf.org/html/rfc7517#section-4.9
 */
class X509CertificateSHA256ThumbprintParameter extends JWKParameter
{
	use Base64URLValue;
	
	/**
	 * Constructor
	 *
	 * @param string $thumbprint Base64url encoded SHA-256 hash
	 */
	public function __construct($thumbprint) {
		$this->_validateEncoding($thumbprint);
		parent::__construct(self::PARAM_X509_CERTIFICATE_SHA256_THUMBPRINT, 
			(string) $thumbprint);
	}
}
