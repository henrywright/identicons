<?php
/**
 * Function definitions
 *
 * @package Identicons
 * @subpackage Functions
 */

namespace Identicons;

/**
 * Init the plugin.
 *
 * @since 1.0.0
 */
function init() {
	// Load translated strings
	\load_plugin_textdomain( PLUGIN_SLUG );
}

/**
 * Filter the avatar default list.
 *
 * @since 1.0.0
 * 
 * @param string $avatar_list The avatar default list.
 * @return string The avatar default list.
 */
function default_avatar_select( $avatar_list ) {
	// Iterate
	foreach ( IDENTICON_TYPES as $key => $value ) {
		// Get the qualified name
		$class = __NAMESPACE__ . '\\' . $value;
		// Get the avatar_default option
		$avatar_default = \get_option( 'avatar_default' );
		// Set the URL
		$url = \sprintf(
			'%s/assets/images/%s',
			\untrailingslashit( \plugin_dir_url( __DIR__ ) ),
			$class::FILENAME
		);
		// Add item
		$avatar_list .= sprintf(
			'<label><input type="radio" name="avatar_default" id="avatar_%s" value="%s" %s> <img alt="%s" src="%s" class="avatar avatar-32 photo avatar-default" height="32" width="32"> %s</label><br>',
			\esc_attr( $key ),
			\esc_attr( $key ),
			\checked( $avatar_default, $key, false ),
			\esc_attr( $value ),
			\esc_url( $url ),
			\esc_html( $value )
		);
	}
	return $avatar_list;
}

/**
 * Filter the avatar URL.
 *
 * @since 1.0.0
 * 
 * @param string $url The avatar URL.
 * @param mixed $id_or_email The user identifier.
 * @param array $args The passed arguments.
 * @return string The avatar URL.
 */
function get_avatar_url( $url, $id_or_email, $args ) {
	// Get the avatar_default option
	$avatar_default = \get_option( 'avatar_default' );
	// Check if avatar_default is a type of identicon
	if ( \array_key_exists( $avatar_default, IDENTICON_TYPES ) ) {
		if ( false === $args['force_default'] ) {
			if ( isset( $id_or_email->comment_author_email ) ) {
				$email = $id_or_email->comment_author_email;
			} else {
				if ( \strpos( $id_or_email, '@md5.gravatar.com' ) ) {
					$parts = \explode( '@', $id_or_email );
					$hash = $parts[0];
				} elseif ( \is_string( $id_or_email ) ) {
					$user = \get_user_by( 'email', \absint( $id_or_email ) );
				} elseif ( \is_numeric( $id_or_email ) ) {
					$user = \get_user_by( 'id', \absint( $id_or_email ) );
				} elseif ( $id_or_email instanceof \WP_Post ) {
					$user = \get_user_by( 'id', (int) $id_or_email->post_author );
				} elseif ( $id_or_email instanceof \WP_User ) {
					$user = $id_or_email;
				}
				if ( isset( $user->user_email ) ) {
					$email = $user->user_email;
				}
			}
			if ( isset( $email ) ) {
				// Create a hash of the user email
				$hash = \md5( \strtolower( \trim( $email ) ) );
			}
			if ( isset( $hash ) ) {
				// Create a new instance
				$identicon = Factory::make( \get_option( 'avatar_default' ), $hash );
				// Get the upload directory
				$wp_upload_dir = \wp_upload_dir();
				if ( ! \file_exists( \trailingslashit( $wp_upload_dir['basedir'] ) . \trailingslashit( PLUGIN_SLUG ) . \trailingslashit( $hash ) . $identicon::FILENAME ) ) {
					// Create an identicon
					$identicon->create();	
				}
				return \esc_url( $identicon->read() );
			} else {
				$class = __NAMESPACE__ . '\\' . IDENTICON_TYPES[$avatar_default];
				return \sprintf(
					'%s/assets/images/%s',
					\untrailingslashit( \plugin_dir_url( __DIR__ ) ),
					$class::FILENAME
				);
			}
		} else {
			return $url;
		}
	} else {
		return $url;	
	}
}

/**
 * Delete if user email is changed.
 *
 * @since 1.0.0
 * 
 * @param int $user_id The user ID.
 * @param WP_User $old_user_data The user data before the update.
 */
function profile_update( $user_id, $old_user_data ) {
	// Get user data
	$user = \get_user_by( 'id', $user_id );
	// Check if the email doesn't match
	if ( $user->user_email !== $old_user_data->user_email ) {
		// Create a hash of the old user email
		$hash = \md5( \strtolower( \trim( $old_user_data->user_email ) ) );
		// Iterate
		foreach ( IDENTICON_TYPES as $key => $value ) {
			// Create a new instance
			$identicon = Factory::make( $key, $hash );
			// Delete the identicon
			$identicon->delete();
		}
		// Get the upload directory
		$wp_upload_dir = \wp_upload_dir();
		// Remove the user directory
		\rmdir( \trailingslashit( $wp_upload_dir['basedir'] ) . \trailingslashit( PLUGIN_SLUG ) . \trailingslashit( $hash ) );
	}
}

/**
 * Delete if user is deleted.
 *
 * @since 1.0.0
 * 
 * @param int $id The user ID.
 */
function delete_user( $id ) {
	// Get user data
	$user = \get_user_by( 'id', $id );
	// Create a hash of the user email
	$hash = \md5( \strtolower( \trim( $user->user_email ) ) );
	// Iterate
	foreach ( IDENTICON_TYPES as $key => $value ) {
		// Create a new instance
		$identicon = Factory::make( $key, $hash );
		// Delete the identicon
		$identicon->delete();
	}
	// Get the upload directory
	$wp_upload_dir = \wp_upload_dir();
	// Remove the user directory
	\rmdir( \trailingslashit( $wp_upload_dir['basedir'] ) . \trailingslashit( PLUGIN_SLUG ) . \trailingslashit( $hash ) );
}