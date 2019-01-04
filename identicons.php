<?php
/**
 * Plugin Name: Identicons
 * Plugin URI: https://github.com/henrywright/identicons
 * Description: Fun identicons for your WordPress site.
 * Version: 1.0.0
 * Author: Henry Wright
 * Author URI: https://about.me/henrywright
 * Text Domain: identicons
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Identicons
 *
 * @package Identicons
 */

namespace Identicons;

const PLUGIN_SLUG = 'identicons';
const IDENTICON_TYPES = [
	'pixicon' => 'Pixicon'
];
require_once \trailingslashit( \dirname( __FILE__ ) ) . 'php/classes/class-factory.php';
require_once \trailingslashit( \dirname( __FILE__ ) ) . 'php/classes/class-identicon.php';
require_once \trailingslashit( \dirname( __FILE__ ) ) . 'php/classes/class-pixicon.php';
require_once \trailingslashit( \dirname( __FILE__ ) ) . 'php/actions.php';
require_once \trailingslashit( \dirname( __FILE__ ) ) . 'php/filters.php';
require_once \trailingslashit( \dirname( __FILE__ ) ) . 'php/functions.php';
\register_activation_hook( __FILE__, function() {
	// Set option
	\update_option( 'avatar_default', \key( IDENTICON_TYPES ) );
} );
\register_deactivation_hook( __FILE__, function() {
	// Set option
	\update_option( 'avatar_default', 'mystery' );
} );