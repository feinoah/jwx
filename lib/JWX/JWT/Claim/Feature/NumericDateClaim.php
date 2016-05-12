<?php

namespace JWX\JWT\Claim\Feature;


/**
 * Trait for claims having NumericDate value.
 */
trait NumericDateClaim
{
	/**
	 * Initialize instance from date/time string.
	 *
	 * @param string $time <code>strtotime</code> compatible time string
	 * @param string $tz Default timezone
	 * @throws \RuntimeException
	 * @return static
	 */
	public static function fromString($time, $tz = "UTC") {
		try {
			$dt = new \DateTimeImmutable($time, self::_createTimeZone($tz));
			return new static($dt->getTimestamp());
		} catch (\Exception $e) {
			throw new \RuntimeException(
				"Failed to create DateTime: " .
					 self::_getLastDateTimeImmutableErrorsStr(), 0, $e);
		}
	}
	
	/**
	 * Get date as a unix timestamp.
	 *
	 * @return int
	 */
	public function timestamp() {
		return (int) $this->_value;
	}
	
	/**
	 * Get date as a datetime object.
	 *
	 * @param string $tz Timezone
	 * @throws \RuntimeException
	 * @return \DateTimeImmutable
	 */
	public function dateTime($tz = "UTC") {
		$dt = \DateTimeImmutable::createFromFormat("!U", $this->_value, 
			self::_createTimeZone($tz));
		if (false === $dt) {
			throw new \RuntimeException(
				"Failed to create DateTime: " .
					 self::_getLastDateTimeImmutableErrorsStr());
		}
		return $dt;
	}
	
	/**
	 * Create DateTimeZone object from string.
	 *
	 * @param string $tz
	 * @throws \UnexpectedValueException
	 * @return \DateTimeZone
	 */
	private static function _createTimeZone($tz) {
		try {
			return new \DateTimeZone($tz);
		} catch (\Exception $e) {
			throw new \UnexpectedValueException("Invalid timezone.", 0, $e);
		}
	}
	
	/**
	 * Get last error caused by DateTimeImmutable.
	 *
	 * @return string
	 */
	private static function _getLastDateTimeImmutableErrorsStr() {
		$errors = \DateTimeImmutable::getLastErrors()["errors"];
		return implode(", ", $errors);
	}
}
