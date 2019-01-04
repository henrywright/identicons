<?php
/**
 * Action hooks
 *
 * @package Identicons
 * @subpackage Actions
 */

namespace Identicons;

\add_action( 'init',           __NAMESPACE__ . '\\init'                   );
\add_action( 'profile_update', __NAMESPACE__ . '\\profile_update',  10, 2 );
\add_action( 'delete_user',    __NAMESPACE__ . '\\delete_user'            );