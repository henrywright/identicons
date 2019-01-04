<?php
/**
 * Factory class
 *
 * @package Identicons
 * @subpackage Classes
 */

namespace Identicons;

/**
 * The Factory class definition.
 *
 * @since 1.0.0
 */
class Factory {

	/**
	 * Create a new instance of Identicon.
	 *
	 * @since 1.0.0
	 * 
	 * @param string $type The type of avatar.
	 * @param string $hash md5 hash of the user email.
	 * @return Identicon|null
	 */
	public static function make( string $type, string $hash ): ?Identicon {
		// Check if the type exists
		if ( \array_key_exists( $type, IDENTICON_TYPES ) ) {
			$class = __NAMESPACE__ . '\\' . IDENTICON_TYPES[$type];
			return new $class( $hash );
		} else {
			return null;
		}
	}
}