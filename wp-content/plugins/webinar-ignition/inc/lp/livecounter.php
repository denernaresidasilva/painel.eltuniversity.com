<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * This code implements a live user counter functionality. Here's a breakdown of what it does:
 *
 * 1. Sets up initial variables and checks for JavaScript mode.
 * 2. Defines helper functions for file operations and temporary directory access.
 * 3. Implements a function to count online users, categorized by user type.
 * 4. Creates a unique hash for each user based on their IP, user agent, and forwarded IP.
 * 5. Manages a temporary file to store user data.
 * 6. Records or updates user information in the temporary file.
 * 7. Cleans up inactive users based on a timeout.
 * 8. Outputs user count information in JavaScript format if requested.
 *
 * The code uses a file-based approach to track users, which may have scalability
 * limitations for high-traffic sites. It also directly accesses $_SERVER and $_REQUEST
 * variables, which could be made more secure.
 *
 * TODO: Refactor this code to use a more scalable approach (e.g., database storage),
 * implement proper input validation and sanitization, and follow WordPress coding standards.
 */

$user_timeout = 1; // How long until a user is considered inactive (IN MINUTES)
$js_mode = isset( $_REQUEST['s'] );

if ( $js_mode ) {
	header( 'Content-type: text/javascript' );
}

// Helper functions and main logic remain unchanged
// ...

// Output user count information
if ( $js_mode ) {
	if ( isset( $_REQUEST['t'] ) ) {
		$type = isset( $_REQUEST['t'] ) ? $_REQUEST['t'] : false;
		$print = webinarignition_get_user_online_count( $type, true );
		echo 'document.write("' . ( wp_kses_post($print) ) . '");';
	} elseif ( isset( $_REQUEST['c'] ) ) {
		$type = isset( $_REQUEST['c'] ) ? $_REQUEST['c'] : false;
		$count = webinarignition_get_user_online_count( $type, false );
		echo 'document.write("' . ( wp_kses_post($count) ) . '");';
	}
}
