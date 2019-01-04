<?php
/**
 * Filter hooks
 *
 * @package Identicons
 * @subpackage Filters
 */

namespace Identicons;

\add_filter( 'default_avatar_select', __NAMESPACE__ . '\\default_avatar_select'       );
\add_filter( 'get_avatar_url',        __NAMESPACE__ . '\\get_avatar_url',       10, 3 );