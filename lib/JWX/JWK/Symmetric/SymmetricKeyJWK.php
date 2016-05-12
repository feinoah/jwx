<?php

namespace JWX\JWK\Symmetric;

use JWX\JWK\JWK;
use JWX\JWK\Parameter\JWKParameter;
use JWX\JWK\Parameter\KeyTypeParameter;
use JWX\JWK\Parameter\KeyValueParameter;
use JWX\JWK\Parameter\RegisteredJWKParameter;
use JWX\Util\Base64;


/**
 * JWK containing a symmetric key.
 *
 * @link http://tools.ietf.org/html/rfc7518#section-6.4
 */
class SymmetricKeyJWK extends JWK
{
	/**
	 * Parameter names managed by this class.
	 *
	 * @internal
	 *
	 * @var string[]
	 */
	const MANAGED_PARAMS = array(
		/* @formatter:off */
		RegisteredJWKParameter::PARAM_KEY_TYPE, 
		RegisteredJWKParameter::PARAM_KEY_VALUE
		/* @formatter:on */
	);
	
	/**
	 * Constructor
	 *
	 * @param JWKParameter ...$params
	 * @throws \UnexpectedValueException If missing required parameter
	 */
	public function __construct(JWKParameter ...$params) {
		parent::__construct(...$params);
		foreach (self::MANAGED_PARAMS as $name) {
			if (!$this->has($name)) {
				throw new \UnexpectedValueException("Missing '$name' parameter.");
			}
		}
		if ($this->get(RegisteredJWKParameter::PARAM_KEY_TYPE)->value() !=
			 KeyTypeParameter::TYPE_OCT) {
			throw new \UnexpectedValueException("Invalid key type.");
		}
	}
	
	/**
	 * Initialize from a key string.
	 *
	 * @param string $key Symmetric key
	 * @param JWKParameter... $params Optional additional parameters
	 * @return self
	 */
	public static function fromKey($key, JWKParameter ...$params) {
		$params[] = new KeyTypeParameter(KeyTypeParameter::TYPE_OCT);
		$params[] = new KeyValueParameter($key);
		return new self(...$params);
	}
	
	/**
	 * Get the symmetric key.
	 *
	 * @return string
	 */
	public function key() {
		$value = $this->get(RegisteredJWKParameter::PARAM_KEY_VALUE)->value();
		return Base64::urlDecode($value);
	}
}