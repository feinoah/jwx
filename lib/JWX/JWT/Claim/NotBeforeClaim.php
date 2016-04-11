<?php

namespace JWX\JWT\Claim;

use JWX\JWT\Claim\Feature\NumericDateClaim;
use JWX\JWT\Claim\Validator\LessOrEqualValidator;
use JWX\JWT\Claim\Feature\ReferenceTimeValidation;


/**
 * Implements 'nbf' claim specified in rfc7519 section 4.1.5
 *
 * @link https://tools.ietf.org/html/rfc7519#section-4.1.5
 */
class NotBeforeClaim extends RegisteredClaim
{
	use NumericDateClaim;
	use ReferenceTimeValidation;
	
	/**
	 * Constructor
	 *
	 * @param int $not_before Not before time
	 */
	public function __construct($not_before) {
		parent::__construct(self::NAME_NOT_BEFORE, $not_before, 
			new LessOrEqualValidator());
	}
}
