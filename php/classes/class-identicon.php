<?php
/**
 * Identicon class
 *
 * @package Identicons
 * @subpackage Classes
 */

namespace Identicons;

/**
 * The Identicon class definition.
 *
 * @since 1.0.0
 */
abstract class Identicon {

	/**
	 * md5 hash of the user email.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $hash;

	/**
	 * The basedir.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $basedir;

	/**
	 * The baseurl.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $baseurl;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * 
	 * @param string $hash md5 hash of the user email.
	 */
	public function __construct( string $hash ) {
		// Set the hash
		$this->hash = $hash;
		// Get the upload directory
		$wp_upload_dir = \wp_upload_dir();
		// Set the basedir
		$this->basedir = \trailingslashit( $wp_upload_dir['basedir'] ) . \trailingslashit( PLUGIN_SLUG );
		// Set the baseurl
		$this->baseurl = \trailingslashit( $wp_upload_dir['baseurl'] ) . \trailingslashit( PLUGIN_SLUG );
	}

	/**
	 * Create an indention.
	 *
	 * @since 1.0.0
	 * 
	 * @return bool
	 */
	abstract public function create(): bool;

	/**
	 * Get an indention.
	 *
	 * @since 1.0.0
	 * 
	 * @return string|null
	 */
	public function read(): ?string {
		// Check if the file exists
		if ( \file_exists( $this->basedir . \trailingslashit( $this->hash ) . static::FILENAME ) ) {
			return $this->baseurl . \trailingslashit( $this->hash ) . static::FILENAME;
		} else {
			return null;
		}
	}

	/**
	 * Set an identicon.
	 *
	 * @since 1.0.0
	 * 
	 * @param resource $image The image resource.
	 * @return bool
	 */
	public function update( resource $image ): bool {
		if ( \imagepng( $image, $this->basedir . \trailingslashit( $this->hash ) . static::FILENAME ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Delete an identicon.
	 *
	 * @since 1.0.0
	 * 
	 * @return bool
	 */
	public function delete(): bool {
		// Check if the file exists
		if ( \file_exists( $this->basedir . \trailingslashit( $this->hash ) . static::FILENAME ) ) {
			if ( \unlink( $this->basedir . \trailingslashit( $this->hash ) . static::FILENAME ) ) {
				return true;
			} else {
				return false;	
			}
		} else {
			return true;
		}
	}
}