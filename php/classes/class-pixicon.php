<?php
/**
 * Pixicon class
 *
 * @package Identicons
 * @subpackage Classes
 */

namespace Identicons;

/**
 * The Pixicon class definition.
 *
 * @since 1.0.0
 */
class Pixicon extends Identicon {

	/**
	 * The name of the file.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public const FILENAME = 'pixicon.png';

	/**
	 * The image resource.
	 *
	 * @since 1.0.0
	 * @var resource
	 */
	private $image;

	/**
	 * Create a pixicon.
	 *
	 * @since 1.0.0
	 * 
	 * @return bool
	 */
	public function create(): bool {
		// Create a new image
		$this->image = \imagecreatetruecolor( 5, 5 );
		// Iterate
		foreach ( \range( 0, 4 ) as $x ) {
			foreach ( \range( 0, 4 ) as $y ) {
				// Set data
				$data[$x][$y] = \hexdec( \substr( $this->hash, $x * 5 + $y + 6, 1 ) ) % 2 === 0;
				if ( 3 === $x ) {
					$z = $x - 2;
				} elseif ( 4 === $x ) {
					$z = $x - 4;
				} else {
					$z = $x - 0;
				}
				// Check data
				if ( $data[$z][$y] ) {
					// Set a pixel in image
					\imagesetpixel( $this->image, $x, $y, \imagecolorallocate( $this->image, \hexdec( \substr( $this->hash, 0, 2 ) ), \hexdec( \substr( $this->hash, 2, 2 ) ), \hexdec( \substr( $this->hash, 4, 2 ) ) ) );
				} else {
					// Set a pixel in image
					\imagesetpixel( $this->image, $x, $y, \imagecolorallocate( $this->image, \hexdec( 'ee' ), \hexdec( 'ee' ), \hexdec( 'ee' ) ) );
				}
			}	
		}
		// Scale
		$this->image = \imagescale( $this->image, 320, 320, \IMG_NEAREST_NEIGHBOUR );
		// Create a directory
		\wp_mkdir_p( $this->basedir . \trailingslashit( $this->hash ) );
		// Save
		if ( \imagepng( $this->image, $this->basedir . \trailingslashit( $this->hash ) . self::FILENAME ) ) {
			return true;
		} else {
			return false;
		}
	}
}