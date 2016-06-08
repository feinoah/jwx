<?php

namespace JWX\JWT;

use JWX\JWE\CompressionAlgorithm;
use JWX\JWE\ContentEncryptionAlgorithm;
use JWX\JWE\JWE;
use JWX\JWE\KeyManagementAlgorithm;
use JWX\JWS\Algorithm\NoneAlgorithm;
use JWX\JWS\JWS;
use JWX\JWS\SignatureAlgorithm;
use JWX\JWT\Claims;
use JWX\JWT\Exception\ValidationException;
use JWX\JWT\Header\Header;
use JWX\JWT\Header\JOSE;
use JWX\JWT\Parameter\ContentTypeParameter;
use JWX\JWT\Parameter\RegisteredJWTParameter;
use JWX\Util\Base64;


/**
 * Represents a token as a JWS or a JWE compact serialization with claims
 * as a payload.
 *
 * @link https://tools.ietf.org/html/rfc7519#section-3
 */
class JWT
{
	// JWT type enumerations
	const TYPE_JWS = 0;
	const TYPE_JWE = 1;
	
	/**
	 * JWT parts.
	 *
	 * @var string[] $_parts
	 */
	protected $_parts;
	
	/**
	 * JWT type.
	 *
	 * @var int $_type
	 */
	protected $_type;
	
	/**
	 * Constructor
	 *
	 * @param string $token JWT string
	 * @throws \UnexpectedValueException
	 */
	public function __construct($token) {
		$this->_parts = explode(".", $token);
		if (3 == count($this->_parts)) {
			$this->_type = self::TYPE_JWS;
		} else if (5 == count($this->_parts)) {
			$this->_type = self::TYPE_JWE;
		} else {
			throw new \UnexpectedValueException("Not a JWT token.");
		}
	}
	
	/**
	 * Convert claims set to an unsecured JWT.
	 *
	 * @param Claims $claims Claims set
	 * @param Header|null $header Optional header
	 * @throws \RuntimeException For generic errors
	 * @return self
	 */
	public static function unsecuredFromClaims(Claims $claims, 
			Header $header = null) {
		return self::signedFromClaims($claims, new NoneAlgorithm(), $header);
	}
	
	/**
	 * Convert claims set to a signed JWS token.
	 *
	 * @param Claims $claims Claims set
	 * @param SignatureAlgorithm $algo Signature algorithm
	 * @param Header|null $header Optional header
	 * @throws \RuntimeException For generic errors
	 * @return self
	 */
	public static function signedFromClaims(Claims $claims, 
			SignatureAlgorithm $algo, Header $header = null) {
		$payload = $claims->toJSON();
		$jws = JWS::sign($payload, $algo, $header);
		return new self($jws->toCompact());
	}
	
	/**
	 * Convert claims set to an encrypted JWE token.
	 *
	 * @param Claims $claims Claims set
	 * @param KeyManagementAlgorithm $key_algo Key management algorithm
	 * @param ContentEncryptionAlgorithm $enc_algo Content encryption algorithm
	 * @param CompressionAlgorithm|null $zip_algo Optional compression algorithm
	 * @param Header|null $header Optional header
	 * @throws \RuntimeException For generic errors
	 * @return self
	 */
	public static function encryptedFromClaims(Claims $claims, 
			KeyManagementAlgorithm $key_algo, 
			ContentEncryptionAlgorithm $enc_algo, 
			CompressionAlgorithm $zip_algo = null, Header $header = null) {
		$payload = $claims->toJSON();
		$jwe = JWE::encrypt($payload, $key_algo, $enc_algo, $zip_algo, $header);
		return new self($jwe->toCompact());
	}
	
	/**
	 * Whether JWT is a JWS.
	 *
	 * @return bool
	 */
	public function isJWS() {
		return $this->_type == self::TYPE_JWS;
	}
	
	/**
	 * Get JWT as a JWS.
	 *
	 * @throws \LogicException
	 * @return JWS
	 */
	public function JWS() {
		if (!$this->isJWS()) {
			throw new \LogicException("Not a JWS.");
		}
		return JWS::fromParts($this->_parts);
	}
	
	/**
	 * Whether JWT is a JWE.
	 *
	 * @return bool
	 */
	public function isJWE() {
		return $this->_type == self::TYPE_JWE;
	}
	
	/**
	 * Get JWT as a JWE.
	 *
	 * @throws \LogicException
	 * @return JWE
	 */
	public function JWE() {
		if (!$this->isJWE()) {
			throw new \LogicException("Not a JWE.");
		}
		return JWE::fromParts($this->_parts);
	}
	
	/**
	 * Check whether JWT contains another nested JWT.
	 *
	 * @return bool
	 */
	public function isNested() {
		$header = $this->header();
		if (!$header->has(RegisteredJWTParameter::P_CTY)) {
			return false;
		}
		$cty = $header->get(RegisteredJWTParameter::P_CTY)->value();
		if ($cty != ContentTypeParameter::TYPE_JWT) {
			return false;
		}
		return true;
	}
	
	/**
	 * Get JWT header.
	 *
	 * @return JOSE
	 */
	public function header() {
		$header = Header::fromJSON(Base64::urlDecode($this->_parts[0]));
		return new JOSE($header);
	}
	
	/**
	 * Get JWT as a string.
	 *
	 * @return string
	 */
	public function token() {
		return implode(".", $this->_parts);
	}
	
	/**
	 * Get claims from a signed JWS.
	 *
	 * @param SignatureAlgorithm $algo Signature algorithm
	 * @param ValidationContext $ctx Validation context
	 * @throws ValidationException If validation fails
	 * @throws \RuntimeException For generic errors
	 * @return Claims
	 */
	public function claimsFromJWS(SignatureAlgorithm $algo, 
			ValidationContext $ctx) {
		$jws = $this->JWS();
		if (!$jws->validate($algo)) {
			throw new ValidationException("JWS signature is invalid.");
		}
		$claims = Claims::fromJSON($jws->payload());
		$ctx->validate($claims);
		return $claims;
	}
	
	/**
	 * Get claims from an encrypted JWE.
	 *
	 * @param KeyManagementAlgorithm $key_algo Key management algorithm
	 * @param ContentEncryptionAlgorithm $enc_algo Content encryption algorithm
	 * @param ValidationContext $ctx Validation context
	 * @throws ValidationException If validation fails
	 * @throws \RuntimeException For generic errors
	 * @return Claims
	 */
	public function claimsFromJWE(KeyManagementAlgorithm $key_algo, 
			ContentEncryptionAlgorithm $enc_algo, ValidationContext $ctx) {
		$claims = Claims::fromJSON($this->JWE()->decrypt($key_algo, $enc_algo));
		$ctx->validate($claims);
		return $claims;
	}
	
	/**
	 * Encrypt JWT producing a nested JWT.
	 *
	 * @param KeyManagementAlgorithm $key_algo Key management algorithm
	 * @param ContentEncryptionAlgorithm $enc_algo Content encryption algorithm
	 * @param CompressionAlgorithm|null $zip_algo Optional compression algorithm
	 * @param Header|null $header Optional header
	 * @throws \RuntimeException For generic errors
	 * @return self
	 */
	public function encryptNested(KeyManagementAlgorithm $key_algo, 
			ContentEncryptionAlgorithm $enc_algo, 
			CompressionAlgorithm $zip_algo = null, Header $header = null) {
		if (!isset($header)) {
			$header = new Header();
		}
		// add JWT content type parameter
		$header = $header->withParameters(
			new ContentTypeParameter(ContentTypeParameter::TYPE_JWT));
		$payload = $this->token();
		$jwe = JWE::encrypt($payload, $key_algo, $enc_algo, $zip_algo, $header);
		return new self($jwe->toCompact());
	}
	
	/**
	 * Get nested JWT from an encrypted JWT.
	 *
	 * @param KeyManagementAlgorithm $key_algo Key management algorithm
	 * @param ContentEncryptionAlgorithm $enc_algo Content encryption algorithm
	 * @throws \RuntimeException For generic errors
	 * @return self
	 */
	public function nestedFromJWE(KeyManagementAlgorithm $key_algo, 
			ContentEncryptionAlgorithm $enc_algo) {
		if (!$this->isNested()) {
			throw new \UnexpectedValueException("Not a nested JWT token.");
		}
		$jwt = new JWT($this->JWE()->decrypt($key_algo, $enc_algo));
		return $jwt;
	}
	
	/**
	 * Convert JWT to string.
	 *
	 * @return string
	 */
	public function __toString() {
		return $this->token();
	}
}
