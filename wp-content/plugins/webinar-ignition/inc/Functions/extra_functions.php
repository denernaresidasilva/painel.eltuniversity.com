<?php


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// fnc fix permalink uri variable deliminator
// --------------------------------------------------------
function webinarignition_fixPerma( $postID = false, $url = false ) {
	$pml = $url;
	if ( ! $pml ) {
		$pml = $postID ? get_permalink( $postID ) : get_permalink();
	}
	$pml = ( ( strpos( $pml, '?' ) !== false ) ? "$pml&" : "$pml?" );

	return $pml;
}

// --------------------------------------------------------


function webinarignition_display( $var, $placeholder = null ) { 
	 // Check if $var is not empty
	 if ( ! empty( $var ) ) {
        // Use stripslashes only if necessary and escape the content
		// Define all HTML tags and their attributes, including <script>.
		$all_html_tags = array(
			'a' => array(),
			'abbr' => array(),
			'address' => array(),
			'area' => array(),
			'article' => array(),
			'aside' => array(),
			'audio' => array(),
			'b' => array(),
			'base' => array(),
			'bdi' => array(),
			'bdo' => array(),
			'blockquote' => array(),
			'body' => array(),
			'br' => array(),
			'button' => array(),
			'canvas' => array(),
			'caption' => array(),
			'cite' => array(),
			'code' => array(),
			'col' => array(),
			'colgroup' => array(),
			'data' => array(),
			'datalist' => array(),
			'dd' => array(),
			'del' => array(),
			'details' => array(),
			'dfn' => array(),
			'dialog' => array(),
			'div' => array(),
			'dl' => array(),
			'dt' => array(),
			'em' => array(),
			'embed' => array(),
			'fieldset' => array(),
			'figcaption' => array(),
			'figure' => array(),
			'footer' => array(),
			'form' => array(),
			'h1' => array(),
			'h2' => array(),
			'h3' => array(),
			'h4' => array(),
			'h5' => array(),
			'h6' => array(),
			'head' => array(),
			'header' => array(),
			'hgroup' => array(),
			'hr' => array(),
			'html' => array(),
			'i' => array(),
			'iframe' => array(
				'allowfullscreen' => true,
				'allow' => true,
			),
			'img' => array(),
			'input' => array(),
			'ins' => array(),
			'kbd' => array(),
			'keygen' => array(),
			'label' => array(),
			'legend' => array(),
			'li' => array(),
			'link' => array(),
			'main' => array(),
			'map' => array(),
			'mark' => array(),
			'menu' => array(),
			'menuitem' => array(),
			'meta' => array(),
			'meter' => array(),
			'nav' => array(),
			'noscript' => array(),
			'object' => array(),
			'ol' => array(),
			'optgroup' => array(),
			'option' => array(),
			'output' => array(),
			'p' => array(),
			'param' => array(),
			'picture' => array(),
			'pre' => array(),
			'progress' => array(),
			'q' => array(),
			'rp' => array(),
			'rt' => array(),
			'ruby' => array(),
			's' => array(),
			'samp' => array(),
			'script' => array( // Explicitly allow <script> and its attributes.
				'type' => true,
				'src' => true,
				'async' => true,
				'defer' => true,
			),
			'section' => array(),
			'select' => array(),
			'small' => array(),
			'source' => array(),
			'span' => array(),
			'strong' => array(),
			'style' => array(),
			'sub' => array(),
			'summary' => array(),
			'sup' => array(),
			'table' => array(),
			'tbody' => array(),
			'td' => array(),
			'textarea' => array(),
			'tfoot' => array(),
			'th' => array(),
			'thead' => array(),
			'time' => array(),
			'title' => array(),
			'tr' => array(),
			'track' => array(),
			'u' => array(),
			'ul' => array(),
			'var' => array(),
			'video' => array(),
			'wbr' => array(),
		);

		// Dynamically add attributes to all tags.
		foreach ($all_html_tags as $tag => $attributes) {
			$all_html_tags[$tag] = array_merge(
				$attributes,
				array_fill_keys(
					array(
						'class', 'id', 'style', 'src', 'href', 'alt', 'title', 'type', 
						'value', 'name', 'target', 'action', 'method', 'checked', 
						'selected', 'placeholder', 'width', 'height', 'border', 
						'align', 'valign', 'lang', 'xml:lang', 'aria-label', 'role', 
						'data-*', 'aria-hidden', 'aria-labelledby', 'aria-describedby', 
						'rel', 'media', 'accept', 'accept-charset', 'charset', 'async', 
						'defer', 'property', 'http-equiv', 'content', 'viewBox', 'd', 
						'x', 'y', 'viewbox', 'preserveAspectRatio', 'xmlns', 'version', 
						'baseProfile'
					), 
					true
				)
			);
		}

		// Safely output the content.
		echo wp_kses(
			$var, 
			array_merge(
				wp_kses_allowed_html('post'), // Allow default WordPress post tags and attributes.
				$all_html_tags
			)
		);

    } else {
        // Output the placeholder image safely
        echo wp_kses(
            $placeholder,
            array(
                'img' => array(
                    'class' => array(),
                    'style' => array(),
                    'src'   => array(),
                    'alt'   => array(),
                ),
                'p' => array(
                    'class' => array(),
                    'style' => array(),
                ),
                'span' => array(
                    'class' => array(),
                    'style' => array(),
                ),
                'div' => array(
                    'class' => array(),
                    'style' => array(),
                ),
            )
        );
    }
}

function webinarignition_btn_color($btn_color = '#74BB00') {
    // Sanitize the button color and remove the leading '#'
    $btn_color = isset($btn_color) ? sanitize_hex_color($btn_color) : '#74BB00';
    $hexCode   = ltrim($btn_color, '#');

    // Ensure the hex code only contains valid characters
    $hexCode = preg_replace('/[^a-fA-F0-9]/', '', $hexCode);

    // Convert 3-character hex to 6-character hex
    if (strlen($hexCode) === 3) {
        $hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
    }

    // Validate the length of the hex code
    if (strlen($hexCode) !== 6) {
        $hexCode = '74BB00'; // Default fallback color
    }

    // Convert hex to RGB values
    $hoverCode = array_map('hexdec', str_split($hexCode, 2));

    // Adjust color brightness for hover effect
    $adjustPercent = -0.05;
    foreach ($hoverCode as &$color) {
        $adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
        $adjustAmount    = ceil($adjustableLimit * $adjustPercent);
        $color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
    }

    // Generate hover color
    $hover_color = '#' . implode('', $hoverCode);

    // Convert hex to RGB for text color calculation
    $r = hexdec(substr($btn_color, 1, 2));
    $g = hexdec(substr($btn_color, 3, 2));
    $b = hexdec(substr($btn_color, 5, 2));

    // Calculate YIQ value to determine text color (black or white)
    $yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
    $text_color = ( $yiq >= 198 ) ? 'black' : 'white';

    // Return color array
    $color_array = array(
        'hover_color' => $hover_color,
        'text_color'  => $text_color
    );

    return $color_array;
}

if(!function_exists('webinarignition_get')){
	function webinarignition_get( $var, $placeholder ) {
		// check if var is set
		if ( empty( $var ) ) {
			return $placeholder;
		} else {
			return wp_kses_post( stripslashes( $var ) );
		}
	}
}


function webinarignition_generate_key( $length = 32 ) {
	return wp_generate_password( $length, false );
}


function webinarignition_live_notification_times( $start_date, $start_time, $webinar_timezone, $webinar_duration ) {
    // Convert the start date and time into a DateTime object
    $dateTimeString = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' ' . $start_time;
    $dateTime = new DateTime($dateTimeString, new DateTimeZone('UTC'));

    // Extract the corrected date and time
    $newDate = $dateTime->format('Y-m-d');
    $newTime = $dateTime->format('H:i');

    // Create DateTime objects for each notification time
    $notificationTimes = array();

    $notificationTimes['live']['date']       = $dateTime->format('m-d-Y');
    $notificationTimes['live']['time']       = $newTime;

    // Day before
    $dayBefore = clone $dateTime;
    $dayBefore->modify('-1 day');
    $notificationTimes['daybefore']['date']  = $dayBefore->format('m-d-Y');
    $notificationTimes['daybefore']['time']  = $newTime;

    // Hour before
    $hourBefore = clone $dateTime;
    $hourBefore->modify('-1 hour');
    $notificationTimes['hourbefore']['date'] = $hourBefore->format('m-d-Y');
    $notificationTimes['hourbefore']['time'] = $hourBefore->format('H:i');

    // Hour after
    $hourAfter = clone $dateTime;
	$webinar_duration = absint( $webinar_duration) + 60 ; // Add 1 hour to the duration;
    $hourAfter->modify($webinar_duration . ' minutes');
    $notificationTimes['hourafter']['date']  = $hourAfter->format('m-d-Y');
    $notificationTimes['hourafter']['time']  = $hourAfter->format('H:i');

    // Day after
    $dayAfter = clone $dateTime;
    $dayAfter->modify('+1 day');
    $notificationTimes['dayafter']['date']   = $dayAfter->format('m-d-Y');
    $notificationTimes['dayafter']['time']   = $newTime;

    return $notificationTimes;
}

function webinarignition_prettifyNotificationTitle( $num ) {
	switch ( $num ) {
		case 1:
			return __( 'Day Before Notification', 'webinar-ignition' );
		case 2:
			return __( 'Hour Before Notification', 'webinar-ignition' );
		case 3:
			return __( 'Live Notification', 'webinar-ignition' );
		case 4:
			return __( 'Hour After Notification', 'webinar-ignition' );
		case 5:
			return __( 'Day After Notification', 'webinar-ignition' );
		default:
			return '';
	}
}


// export leads
add_action( 'admin_post_webinarignition_export_leads', 'webinarignition_export_leads' );
add_action( 'wp_ajax_webinarignition_export_leads', 'webinarignition_export_leads' );

function webinarignition_export_leads() {
		// Check user capabilities
	if ( ! current_user_can( 'edit_posts' ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( [ 'message' => 'Insufficient permissions' ], 403 );
			wp_die();
		} else {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'webinar-ignition' ) );
		}
	}
	
	// Verify nonce - handle both admin_post and ajax
	if ( wp_doing_ajax() ) {
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid security token' ], 403 );
			wp_die();
		}
	} else {
		if ( ! isset( $_POST['webinarignition_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['webinarignition_export_nonce'] ) ), 'webinarignition_export_leads' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'webinar-ignition' ) );
		}
	}
	
	require_once WEBINARIGNITION_PATH . 'inc/ex/ex.php';
}



// export leads
add_action( 'admin_post_webinarignition_export_leads_example', 'webinarignition_export_leads_example' );
add_action( 'wp_ajax_webinarignition_export_leads_example', 'webinarignition_export_leads_example' );
function webinarignition_export_leads_example() {
		// Check user capabilities
	if ( ! current_user_can( 'edit_posts' ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( [ 'message' => 'Insufficient permissions' ], 403 );
			wp_die();
		} else {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'webinar-ignition' ) );
		}
	}
	
	// Verify nonce - handle both admin_post and ajax
	if ( wp_doing_ajax() ) {
		if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
			wp_send_json_error( [ 'message' => 'Invalid security token' ], 403 );
			wp_die();
		}
	} else {
		if ( ! isset( $_POST['webinarignition_export_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['webinarignition_export_nonce'] ) ), 'webinarignition_export_leads_example' ) ) {
			wp_die( esc_html__( 'Security check failed.', 'webinar-ignition' ) );
		}
	}
	
	require_once WEBINARIGNITION_PATH . 'inc/ex/ex-example.php';
}

// extra stuff
function webinarignition_admin_scripts() {
	wp_enqueue_script( 'jquery-ui-sortable' );
}


add_action( 'admin_enqueue_scripts', 'webinarignition_admin_scripts' );


function webinarignition_check_admin() {
	$query_string = isset($_SERVER['QUERY_STRING']) ? sanitize_text_field(wp_unslash($_SERVER['QUERY_STRING'])) : '';
	if ( $query_string && strstr( $query_string, 'preview-' ) && ! is_user_logged_in() ) {
		wp_safe_redirect( home_url() );
		exit;
	}
}

add_action( 'init', 'webinarignition_check_admin' );

/**
 * @param String $client (is the webinar id in the webinarignition_leads table
 *
 * @return String
 */
function webinarignition_getLid( $client ) {
	$lid = filter_input(INPUT_GET, 'lid', FILTER_UNSAFE_RAW);
	$lid = sanitize_text_field($lid);

	if ( ! empty( $_COOKIE[ 'we-trk-' . $client ] ) ) {
		return sanitize_text_field(wp_unslash($_COOKIE[ 'we-trk-' . $client ]));
	} elseif ( ! empty( $lid ) ) {
		return $lid;
	} else {
		return '';
	}
}


function webinarignition_determinePaymentProvider( $webinar ) {
	if ( webinarignition_usingStripePaymentOption( $webinar ) ) {
		return 'stripe';
	} elseif ( webinarignition_usingPaypalPaymentOption( $webinar ) ) {
		return 'paypal';
	} elseif (isset($webinar->paid_button_type) && ! empty( sanitize_text_field( $webinar->paid_button_type ) ) ) {
		return sanitize_text_field( $webinar->paid_button_type );
	}

	return 'unknown';
}

function webinarignition_usingStripePaymentOption( $results ) {
	if ( isset( $results->paid_button_type ) && $results->paid_button_type === 'stripe' ) {
		return true;
	} elseif ( isset($results->paid_button_type) && $results->paid_button_type === 'default' && $results->stripe_secret_key && $results->stripe_publishable_key ) {
		return true;
	}

	return false;
}

function webinarignition_usingPaypalPaymentOption( $results ) {
	return isset( $results->paid_button_type ) && ( $results->paid_button_type === 'paypal' || $results->paid_button_type === 'default' );
}


function webinarignition_should_use_videojs( $results ) {
	// don't use Videojs if it is a live webinar
	if ( ! isset( $results->webinar_date ) || $results->webinar_date !== 'AUTO' ) {
		return false;
	}

	// don't use Videojs if iframe (3rd party e.g Youtube) is used
	if ( empty( $results->webinar_source_toggle ) || $results->webinar_source_toggle === 'iframe' ) {
		return false;
	}

	return true;
}

/**
 * @param $path
 * @param $webinar_data
 */
function webinarignition_inline_css_file( $path, $webinar_data ) {
	extract( webinarignition_get_global_templates_vars( $webinar_data ) );
	$to_include = array();

	if ( ! is_array( $path ) ) {
		$to_include[] = $path;
	} else {
		$to_include = $path;
	}

	ob_start();
	foreach ( $to_include as $path ) {
		if ( file_exists( $path ) ) {
			include $path;
		}
	}
	$inline_css = ob_get_clean();

	$inline_css = str_replace( '<style type="text/css">', '', $inline_css );
	$inline_css = str_replace( '<style>', '', $inline_css );
	$inline_css = str_replace( '</style>', '', $inline_css );
	$inline_css = trim( $inline_css );

	return $inline_css;
}


/**
 * @param $path string | array
 *
 * @return string
 */
function webinarignition_inline_js_file( $path, $webinar_data ) {
	extract( webinarignition_get_global_templates_vars( $webinar_data ) );

	$to_include = array();

	if ( ! is_array( $path ) ) {
		$to_include[] = $path;
	} else {
		$to_include = $path;
	}

	ob_start();
	foreach ( $to_include as $path ) {
		if ( file_exists( $path ) ) {
			include $path;
		}
	}

	$inline_js = ob_get_clean();
	$inline_js = str_replace( '<script type="text/javascript">', '', $inline_js );
	$inline_js = str_replace( '<script>', '', $inline_js );
	$inline_js = str_replace( '</script>', '', $inline_js );
	$inline_js = trim( $inline_js );

	return $inline_js;
}

function webinarignition_is_auto( $webinar ) {
	return isset( $webinar->webinar_date ) && ( $webinar->webinar_date === 'AUTO' );
}

function webinarignition_is_email_verification_enabled( $webinarData ) {
	if ( ! empty( $webinarData->email_verification ) ) {
		if ( $webinarData->email_verification === 'no' ) {
			return false;
		}
		if ( $webinarData->email_verification === 'yes' ) {
			return true;
		}
	}

	$webinarignition_email_verification = get_option( 'webinarignition_email_verification', 0 );

	return ( 1 == $webinarignition_email_verification );
}


function webinarignition_parse_registration_page_data( $webinarId, $rawWebinarData ) {
	$w = $rawWebinarData;

	if ( ! empty( $w->paid_status ) ) :
		$paidCode = $w->paid_status === 'paid' ? '&' . urlencode( $w->paid_code ) : '';
	else :
		$paidCode = '';
	endif;

	$webinarUrl = webinarignition_fixPerma();

	if ( ! empty( $w->webinar_url ) ) {
		$webinarUrl = webinarignition_fixPerma( false, $w->webinar_url );
	} elseif ( ! empty( $w->webinar_id ) ) {
		$webinarUrl = webinarignition_fixPerma( $w->webinar_id );
	}

	$thankYouPageUrl = webinarignition_fixPerma();

	if ( ! empty( $w->ty_url ) ) {
		$thankYouPageUrl = webinarignition_fixPerma( false, $w->ty_url );
	} elseif ( ! empty( $w->ty_id ) ) {
		$thankYouPageUrl = webinarignition_fixPerma( $w->ty_id );
	}

	$thankYouPageUrl = $thankYouPageUrl . 'confirmed' . $paidCode; // lead id (lid) will be appended after registration form has been submitted.

	$useCustomThankYouUrl = false;
	if ( isset( $w->custom_ty_url_state ) && $w->custom_ty_url_state === 'show' && ! empty( $w->custom_ty_url ) ) {
		$useCustomThankYouUrl = true;
		$thankYouPageUrl      = $w->custom_ty_url;
	}

	if ( webinarignition_is_auto( $w ) ) {
		$scheduleType = $w->lp_schedule_type;
		if ( ! empty( $scheduleType ) && ! in_array( $w->lp_schedule_type, array( 'fixed', 'delayed' ) ) ) {
			$scheduleType          = 'custom';
			$customScheduleMaxTime = webinarignition_get_cs_max_time( $w );
		}
	}

	$custom_thankyou_page_url = '';
	$custom_webinar_page_url = '';

	$is_webinar_protected = ! empty( $rawWebinarData->protected_webinar_id ) && 'protected' === $rawWebinarData->protected_webinar_id;
	$webinarIdUrl = $is_webinar_protected ? (isset($rawWebinarData->hash_id) ? $rawWebinarData->hash_id : '') : $webinarId;

	if ( ! empty( $rawWebinarData->custom_thankyou_page ) ) {
		$custom_thankyou_page = get_post( $rawWebinarData->custom_thankyou_page );

		if ( ! empty( $custom_thankyou_page ) ) {
			$custom_thankyou_page_url = get_permalink( $custom_thankyou_page );
		}
	}

	if ( ! empty( $rawWebinarData->custom_webinar_page ) ) {
		$custom_webinar_page = get_post( $rawWebinarData->custom_webinar_page );

		if ( ! empty( $custom_webinar_page ) ) {
			$custom_webinar_page_url = get_permalink( $custom_webinar_page );
		}
	}

	$current_locale = determine_locale();

	$parsed = array(
		'webinarId'                 => $webinarId,
		'webinarType'               => webinarignition_is_auto( $rawWebinarData ) ? 'evergreen' : 'live',
		'ajaxUrl'                   => admin_url( 'admin-ajax.php' ),
		'webinarUrl'                => $webinarUrl,
		'custom_thankyou_page_url'  => $custom_thankyou_page_url,
		'custom_webinar_page_url'   => $custom_webinar_page_url,
		// This is the registration page url, to get the event/webinar page url, just append &live to it later (and the lid of course).
		'thankYouPageUrl'           => $thankYouPageUrl,
		'skipThankYouPage'          => isset( $rawWebinarData->skip_ty_page ) && $rawWebinarData->skip_ty_page === 'yes',
		'useCustomThankYouUrl'      => $useCustomThankYouUrl,
		'arUrl'                     => ! empty( $w->ar_url ) ? $w->ar_url : 'none',
		'paidCode'                  => ! empty( $rawWebinarData->paid_code ) ? $rawWebinarData->paid_code : '',
		'isPaidWebinar'             => isset( $w->paid_status ) && $w->paid_status === 'paid',
		'isSigningUpWithFB'         => false,
		'fbUserData'                => array(),
		'paymentProvider'           => webinarignition_determinePaymentProvider( $rawWebinarData ),
		'scheduleType'              => ! empty( $scheduleType ) ? $scheduleType : '',
		'leadDeviceInfo'            => array(),
		'userIp'                    => isset( $_SERVER['REMOTE_ADDR'] ) ? filter_var( sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ), FILTER_VALIDATE_IP ) : null,
		'emailVerificationEnabled'  => ( ! is_user_logged_in() ) ? webinarignition_is_email_verification_enabled( $w ) : false,
		'translations' => array(
			'ar_modal_head'         => __( 'Autoresponder (AR) data submitted!', 'webinar-ignition' ),
			'ar_modal_body'         => __( 'If everything went well, the data should be in your autoresponder list. Check your autoresponder list to confirm.', 'webinar-ignition' ),
			'done'                  => __( 'Done', 'webinar-ignition' ),
			'verify_email_btn'      => __( 'Confirm code', 'webinar-ignition' ),
			'verify_email_text'     => __( 'Please enter the code. It was sent to your email.', 'webinar-ignition' ),
			'wrong_code_text'       => __( 'The entered code is incorrect.', 'webinar-ignition' ),
			'bot_submission'        => esc_html__( 'This could be a bot registration, please open the link in the mail in a different browser.', 'webinar-ignition' ),
			'current_locale'        => $current_locale,
		),

		// This is for values only used in live webinars.
		'live'                 => array(
			'webinar_switch'   => ! empty( $rawWebinarData->webinar_switch ) ? $rawWebinarData->webinar_switch : '',
		),

		// This is for values only used in evergreen webinars.
		'evergreen'            => array(
			'schedules' => array(
				'custom'  => array(
					'maxTime' => ! empty( $customScheduleMaxTime ) ? $customScheduleMaxTime : '',
				),
				'fixed'   => array(),
				'delayed' => array(),
			),
			'skip_instant_acces_confirm_page' => ( isset( $rawWebinarData->skip_instant_acces_confirm_page ) && $rawWebinarData->skip_instant_acces_confirm_page === 'yes' ),
		),
	);

	return $parsed;
}


// ARRAY TO OBJECT FUNCTION::
function webinarignition_array_to_object( $array ) {
	$obj = new stdClass();
	foreach ( $array as $k => $v ) {
		if ( is_array( $v ) ) {
			$obj->{$k} = $v; // RECURSION
		} else {
			$obj->{$k} = $v;
		}
	}

	return $obj;
}

// Timezone UTC To Abrv.
function webinarignition_utc_to_abrc( $utc ) {
	switch ( $utc ) {
		case '-12':
			return 'Y';
		case '-11':
			return 'SST';
		case '-10':
			return 'CKT';
		case '-930':
			return 'MART';
		case '-9':
			return 'AKST';
		case '-8':
			return 'PST';
		case '-7':
			return 'MST';
		case '-6':
			return 'CST';
		case '-5':
			return 'EST';
		case '-430':
			return 'VST';
		case '-330':
			return 'NST';
		case '-3':
			return 'SRT';
		case '-2':
			return 'O';
		case '-1':
			return 'EGT';
		case '0':
			return 'GMT';
		case '+1':
			return 'CET';
		case '+2':
			return 'CAT';
		case '+3':
			return 'EAT';
		case '+330':
			return 'IST';
		case '+4':
			return 'AST';
		case '+430':
			return 'AFT';
		case '+5':
			return 'PKT';
		case '+530':
			return 'IST';
		case '+545':
			return 'NPT';
		case '+6':
			return 'BTT';
		case '+630':
			return 'MMT';
		case '+7':
			return 'ICT';
		case '+8':
			return 'HKT';
		case '+845':
			return 'ACWST';
		case '+9':
			return 'JST';
		case '+930':
			return 'ACST';
		case '+10':
			return 'PGT';
		case '+1030':
			return 'LHST';
		case '+11':
			return 'VUT';
		case '+1130':
			return 'NFT';
		case '+12':
			return 'MHT';
		case '+1245':
			return 'CHAST';
		case '+13':
			return 'WST';
		case '+14':
			return 'LINT';
			break;
	}//end switch
}


add_filter( 'cron_schedules', 'webinarignition_cron_add_five_minutes' );
function webinarignition_cron_add_five_minutes( $schedules ) {

	$schedules['five_minutes'] = array(
		'interval' => 300,
		'display'  => 'Once Every Five Minutes',
	);

	return $schedules;
}

add_action( 'webinarignition_cron_hook', 'webinarignition_cron_exec' );

function webinarignition_cron_exec() {
	include WEBINARIGNITION_PATH . 'inc/schedule_notifications.php';
}
add_action( 'webinarignition_daily_hook', 'run_on_date_change' );

function run_on_date_change() {
    global $wpdb;

    // Get the table name
    $table_db_name = $wpdb->prefix . 'webinarignition';

    // Get all entries from the table
    $webinars = $wpdb->get_results( "SELECT ID FROM {$table_db_name}", ARRAY_A );

    // Loop through and update each option to an empty value
    if ( ! empty( $webinars ) ) {
        foreach ( $webinars as $webinar ) {
            $webinar_id = $webinar['ID'];
            update_option( 'webinar_data_restricted_mails' . $webinar_id, array() );
        }
    }
}


if ( ! wp_next_scheduled( 'webinarignition_cron_hook' ) ) {
	wp_schedule_event( time(), 'five_minutes', 'webinarignition_cron_hook' );
}



function webinarignition_get_text_color_from_bg_color( $hex_color ) {
	$hexCode = ltrim( $hex_color, '#' );

	if ( strlen( $hexCode ) == 3 ) {
		$hexCode_array = str_split( $hexCode );
		$hexCode = $hexCode_array[0] . $hexCode_array[0] . $hexCode_array[1] . $hexCode_array[1] . $hexCode_array[2] . $hexCode_array[2];
	}
	$hex_color = '#' . $hexCode;

	$r = hexdec( substr( $hex_color, 1, 2 ) );
	$g = hexdec( substr( $hex_color, 3, 2 ) );
	$b = hexdec( substr( $hex_color, 5, 2 ) );
	$yiq = ( ( $r * 299 ) + ( $g * 587 ) + ( $b * 114 ) ) / 1000;
	$text_color = ( $yiq >= 198 ) ? 'black' : 'white';

	return $text_color;
}

function webinarignition_get_hover_color_from_bg_color( $hex_color ) {
	$hexCode = ltrim( $hex_color, '#' );

	if ( strlen( $hexCode ) == 3 ) {
		$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
	}

	$hoverCode = array_map( 'hexdec', str_split( $hexCode, 2 ) );

	$adjustPercent = -0.08;
	foreach ( $hoverCode as & $color ) {
		$adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
		$adjustAmount = ceil( $adjustableLimit * $adjustPercent );

		$color = str_pad( dechex( $color + $adjustAmount ), 2, '0', STR_PAD_LEFT );
	}

	$hover_color = '#' . implode( $hoverCode );

	return $hover_color;
}

add_action( 'webinarignition_delete_logs_db_cron_hook', 'webinarignition_delete_logs_db_cron_exec' );

function webinarignition_delete_logs_db_cron_exec() {

	if ( get_option( 'webinarignition_auto_clean_log_db' ) ) {
		WebinarIgnition_Logs::webinarignition_deleteOldLogs();
	}
}


if ( ! wp_next_scheduled( 'webinarignition_delete_logs_db_cron_hook' ) ) {
	wp_schedule_event( time(), 'daily', 'webinarignition_delete_logs_db_cron_hook' );
}


register_deactivation_hook( __FILE__, 'webinarignition_deactivate_delete_logs_cron' );

function webinarignition_deactivate_delete_logs_cron() {
	$timestamp = wp_next_scheduled( 'webinarignition_delete_logs_db_cron_hook' );
	wp_unschedule_event( $timestamp, 'webinarignition_delete_logs_db_cron_hook' );
}


if ( ! defined( 'WEBINAR_IGNITION_DISABLE_LOGIN_ON_REGISTER' ) || WEBINAR_IGNITION_DISABLE_LOGIN_ON_REGISTER !== true ) {

	/**
	 * Redefine "webinarignition_new_user_notification" to send login details to user
	 *
	 * @param $user_id
	 * @param string $plaintext_pass
	 */
	if ( ! function_exists( 'webinarignition_new_user_notification' ) ) {
		function webinarignition_new_user_notification( $user_id, $plaintext_pass = '' ) {
			$user = new WP_User( $user_id );

			$user_login = stripslashes( $user->user_login );
			$user_email = stripslashes( $user->user_email );

			$message = sprintf(
				/* translators: %s: blog name */
				__( 'New user registration on your blog %s:', 'webinar-ignition' ),
				get_option( 'blogname' )
			) . "\r\n\r\n";
			/* translators: %s: username */
			$message .= sprintf( __( 'Username: %s', 'webinar-ignition' ), $user_login ) . "\r\n\r\n";
			/* translators: %s: user email address */
			$message .= sprintf( __( 'E-mail: %s', 'webinar-ignition' ), $user_email ) . "\r\n";

			/* translators: %s: blog name */
			@wp_mail( get_option( 'admin_email' ), sprintf( __( '[%s] New User Registration', 'webinar-ignition' ), get_option( 'blogname' ) ), $message );

			if ( empty( $plaintext_pass ) ) {
				return;
			}

			/* translators: %s: user's display name */
			$message = sprintf( esc_html__( 'Hi %s,', 'webinar-ignition' ), $user->display_name ) . "\r\n\r\n";
			/* translators: %s: blog name */
			$message .= sprintf( esc_html__( 'Welcome to %s! Here\'s how to log in:', 'webinar-ignition' ), get_option( 'blogname' ) ) . "\r\n\r\n";
			$message .= wp_login_url() . "\r\n";
			/* translators: %s: user's login name */
			$message .= sprintf( esc_html__( 'Username: %s', 'webinar-ignition' ), $user_login ) . "\r\n";
			/* translators: %s: user's password */
			$message .= sprintf( esc_html__( 'Password: %s', 'webinar-ignition' ), $plaintext_pass ) . "\r\n\r\n";
			/* translators: %s: admin email address */
			$message .= sprintf( esc_html__( 'If you have any problems, please contact us at %s.', 'webinar-ignition' ), get_option( 'admin_email' ) ) . "\r\n\r\n";
			$message .= esc_html__( 'Thank You,', 'webinar-ignition' ) . "\r\n";
			/* translators: %s: blog name */
			$message .= sprintf( esc_html__( 'The %s Team', 'webinar-ignition' ), esc_html( get_option( 'blogname' ) ) );

			/* translators: %s: blog name */
			wp_mail( $user_email, sprintf( esc_html__( '[%s] Your username and password', 'webinar-ignition' ), get_option( 'blogname' ) ), $message );
		}
	}//end if

	/**
	 * Register/Login user when new lead has been created
	 *
	 * @param $webinar_data
	 * @param $lead_id
	 * @param $lead_meta
	 */
	function webinarignition_auto_login_cb( $webinar_id, $lead_id ) {
		if ( wp_doing_cron() || wp_doing_ajax() || current_user_can( 'manage_options' ) ) {
			return; // bail here, skip cron, ajax, admin users
		}
		if ( empty( $webinar_id ) || empty( $lead_id ) ) {
			return; // bail here, missing required IDs
		}

		$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

		if ( empty( $webinar_data ) ) {
			return; // bail here, invalid webinar
		}

		if ( isset($_GET['register-now']) && $webinar_data->webinar_date != 'AUTO' && ( ! isset( $_GET['login'] ) || $_GET['login'] != 'true' ) ) {
			return; // bail here, login is not true in live
		}

		$lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );

		if ( empty( $lead ) ) {
			return; // bail here, invalid lead
		}

		$lead_data = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead->ID, 'wiRegForm', $webinar_data->webinar_date == 'AUTO' ? 'evergreen' : 'live' );

		if ( empty( $lead_data ) ) {
			return; // bail here, invalid lead_data
		}

		$names = array();
		$user_display_name = '';

		$wiRegForm_data = (array) maybe_unserialize( $lead_data['meta_value'] );
		$lead_meta = array();

		foreach ( $wiRegForm_data as $field_name => $field ) {

			$field_value = is_array($field) && isset($field['value']) ? sanitize_text_field($field['value']) : $field;
			$lead_meta[ $field_name ] = $field_value;
		}
		if ( isset( $lead_meta['optName'] ) ) {
			$names[]           = isset( $lead_meta['optName'] ) ? trim( sanitize_text_field( $lead_meta['optName'] ) ) : '';
			$user_display_name = isset( $lead_meta['optName'] ) ? trim( sanitize_text_field( $lead_meta['optName'] ) ) : '';
		
		}
		if ( isset( $lead_meta['optLName'] ) ) {
			$names[]           = isset( $lead_meta['optLName'] ) ? trim( sanitize_text_field( $lead_meta['optLName'] ) ) : '';
		}
		
		
		

		$user_email = isset($lead_meta['optEmail']) ? sanitize_text_field($lead_meta['optEmail']) : (isset($lead_meta['email']) ? sanitize_text_field($lead_meta['email']) : '');
		$user_email = !isset($user_email) || $user_email =='' ? $lead->email : $user_email ;

		// Do email verification
		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition_verification';
		$code = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT * FROM {$table_db_name} WHERE email = %s",
				$user_email
			),
			ARRAY_A
		);
		
		// if ( ! isset( $_GET['code'] ) || ( isset($code['code']) && $_GET['code'] != $code['code'])  ) {
		// 	return; // bail here, invalid verification code
		// }

		$user              = get_user_by( 'email', $user_email );

		if ( ! $user && false == email_exists( $user_email ) ) {
			$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

			$user_data = array(
				'user_login'    => $user_email,
				'user_email'    => $user_email,
				'user_pass'     => $random_password,
				'user_nicename' => $user_display_name,
				'display_name'  => $user_display_name,
			);
			$last_name = '';
			if ( ! empty( $names ) ) {
				$user_data['first_name'] = isset( $names[0] ) ? $names[0] : '';
				$user_data['user_nicename'] = $user_data['user_nicename'] == '' ? $names[0] : $user_data['user_nicename'];
				$user_data['display_name'] = $user_data['display_name'] == '' ? $names[0] : $user_data['display_name'];
				$user_data['last_name'] = isset( $names[1] ) ? $names[1] : '';
				$last_name = isset( $names[1] ) ? $names[1] : '';
				$user_display_name =  $user_display_name == '' ? $names[0] : $user_display_name;
				$user_data['nickname'] = isset( $user_display_name ) ? $user_display_name : '';


			}

			$user_id = wp_insert_user( $user_data );

			if ( ! is_wp_error( $user_id ) ) {
				$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' => $user_display_name ) );

				update_user_meta( $user_id, 'billing_first_name', $user_display_name );
				update_user_meta( $user_id, 'billing_last_name', $last_name );
				$webinarignition_auto_login_password_email = absint( get_option( 'webinarignition_auto_login_password_email', 0 ) );

				if ( $webinarignition_auto_login_password_email !== 0 ) {
					webinarignition_new_user_notification( $user_id, $random_password );
				}
			}
		} else {
			$user_id = $user->ID;

			$user_data = array(
				'ID' => $user_id,
				'user_nicename' => $user_display_name,
				'display_name'  => $user_display_name,
			);
			$last_name = '';

			if ( ! empty( $names ) ) {
				$user_data['first_name'] = isset( $names[0] ) ? $names[0] : $user->get( 'first_name' );
				$user_data['last_name']  = isset( $names[1] ) ? $names[1] : '';
				$last_name = isset( $names[1] ) ? $names[1] : '';
			}

			wp_update_user( $user_data );
			update_user_meta( $user_id, 'billing_first_name', $user_display_name );
			update_user_meta( $user_id, 'billing_last_name', $last_name );

		}//end if

		if ( $user_id !== get_current_user_id() ) {
			$user = get_user_by( 'id', $user_id );
			$roles = ! empty( $user->roles ) ? (array) $user->roles : [];
			if ( isset($roles[0]) && $roles[0] == 'subscriber' ) {
				wp_set_current_user( $user_id ); // set the current wp user
				wp_set_auth_cookie( $user_id ); // start the cookie for the current registered user

				do_action( 'webinarignition_after_user_auto_log_in', $user_id, $webinar_id, $lead_id ); // Do something after user auto log-in
			}
		}
	}

	add_action( 'webinarignition_auto_login', 'webinarignition_auto_login_cb', 10, 2 );
}//end if

add_action( 'webinarignition_auto_register_import', 'webinarignition_auto_register_cb', 10, 2 );

function webinarignition_auto_register_cb( $webinar_id, $lead_id ) {
	if ( empty( $webinar_id ) || empty( $lead_id ) ) {
		return; // bail here, missing required IDs
	}
	
	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

	if ( empty( $webinar_data ) ) {
		return; // bail here, invalid webinar
	}

	$lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );

	if ( empty( $lead ) ) {
		return; // bail here, invalid lead
	}

	$lead_data = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead->ID, 'wiRegForm', $webinar_data->webinar_date == 'AUTO' ? 'evergreen' : 'live' );

	if ( empty( $lead_data ) ) {
		$lead_data = array(); // bail here, invalid lead_data
		$lead_data['meta_value'] =''; // bail here, invalid lead_data
	}

	$names = array();
	$user_display_name = '';

	$wiRegForm_data = (array) maybe_unserialize( $lead_data['meta_value'] );
	$lead_meta = array();

	foreach ( $wiRegForm_data as $field_name => $field ) {

		$field_value = is_array($field) && isset($field['value']) ? sanitize_text_field($field['value']) : $field;
		$lead_meta[ $field_name ] = $field_value;
	}

	if ( isset( $lead_meta['optName'] ) ) {

		if ( trim( $lead_meta['optName'] ) == '#firstlast#' ) {
			$names[]           = isset( $lead_meta['optFName'] ) ? trim( sanitize_text_field( $lead_meta['optFName'] ) ) : '';
			$names[]           = isset( $lead_meta['optLName'] ) ? trim( sanitize_text_field( $lead_meta['optLName'] ) ) : '';
			$user_display_name = implode( ' ', $names );
		} else {
			$user_display_name = trim( sanitize_text_field( $lead_meta['optName'] ) );
			$names = explode( ' ', $user_display_name, 2 );
		}
	}else{
		$user_display_name = $lead->name;

		$names[0] = $lead->name;
	}
	

	$user_email = isset($lead_meta['optEmail']) ? sanitize_text_field($lead_meta['optEmail']) : (isset($lead_meta['email']) ? sanitize_text_field($lead_meta['email']) : '');
	$user_email = !isset($user_email) || $user_email =='' ? $lead->email : $user_email ;

	// Do email verification
	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_verification';
	$code = $wpdb->get_row(
		$wpdb->prepare(
			"SELECT * FROM {$table_db_name} WHERE email = %s",
			$user_email
		),
		ARRAY_A
	);
	
	// if ( ! isset( $_GET['code'] ) || ( isset($code['code']) && $_GET['code'] != $code['code'])  ) {
	// 	return; // bail here, invalid verification code
	// }

	$user              = get_user_by( 'email', $user_email );

	if ( ! $user && false == email_exists( $user_email ) ) {
		$random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );

		$user_data = array(
			'user_login'    => $user_email,
			'user_email'    => $user_email,
			'user_pass'     => $random_password,
			'user_nicename' => $user_display_name,
			'display_name'  => $user_display_name,
		);
		$last_name = '';
		if ( ! empty( $names ) ) {
			$user_data['first_name'] = isset( $names[0] ) ? $names[0] : '';
			$user_data['user_nicename'] = $user_data['user_nicename'] == '' ? $names[0] : $user_data['user_nicename'];
			$user_data['display_name'] = $user_data['display_name'] == '' ? $names[0] : $user_data['display_name'];
			$user_data['last_name'] = isset( $names[1] ) ? $names[1] : '';
			$last_name = isset( $names[1] ) ? $names[1] : '';
			$user_display_name =  $user_display_name == '' ? $names[0] : $user_display_name;
			$user_data['nickname'] = isset( $user_display_name ) ? $user_display_name : '';


		}

		$user_id = wp_insert_user( $user_data );

		if ( ! is_wp_error( $user_id ) ) {
			$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' => $user_display_name ) );

			update_user_meta( $user_id, 'billing_first_name', $user_display_name );
			update_user_meta( $user_id, 'billing_last_name', $last_name );
			$webinarignition_auto_login_password_email = absint( get_option( 'webinarignition_auto_login_password_email', 0 ) );

			if ( $webinarignition_auto_login_password_email !== 0 ) {
				webinarignition_new_user_notification( $user_id, $random_password );
			}
		}
	} else {
		$user_id = $user->ID;

		$user_data = array(
			'ID' => $user_id,
			'user_nicename' => $user_display_name,
			'display_name'  => $user_display_name,
		);
		$last_name = '';

		if ( ! empty( $names ) ) {
			$user_data['first_name'] = isset( $names[0] ) ? $names[0] : $user->get( 'first_name' );
			$user_data['last_name']  = isset( $names[1] ) ? $names[1] : '';
			$last_name = isset( $names[1] ) ? $names[1] : '';
		}

		wp_update_user( $user_data );
		update_user_meta( $user_id, 'billing_first_name', $user_display_name );
		update_user_meta( $user_id, 'billing_last_name', $last_name );

	}//end if
}


add_filter( 'display_post_states', 'webinarignition_display_post_states', 10, 2 );

function webinarignition_display_post_states( $post_states, $post ) {
	$is_webinar_page = webinarignition_admin_is_webinar_page( $post->ID );

	if ( $is_webinar_page ) {
		if ( $is_webinar_page['appname'] ) {
			$post_states['wi_webinar_page'] = __( 'Webinar', 'webinar-ignition' ) . " ({$is_webinar_page['appname']} - ID: {$is_webinar_page['ID']})";
		} else {
			$post_states['wi_webinar_page'] = __( 'Webinar Page', 'webinar-ignition' );
		}
	}
	return $post_states;
}


add_filter( 'page_row_actions', 'webinarignition_page_row_actions', 10, 2 );

function webinarignition_page_row_actions( $actions, $post ) {
	$is_webinar_page = webinarignition_admin_is_webinar_page( $post->ID );

	if ( $is_webinar_page && ! empty( $actions['trash'] ) ) {
		// unset($actions['trash']);
		$actions['trash'] = '<span>' . __( 'Trash (delete webinar first)', 'webinar-ignition' ) . '</span>';
	}

	return $actions;
}

function webinarignition_admin_is_webinar_page( $id ) {
	global $webinarignition_webinars_list;

	if ( empty( $webinarignition_webinars_list ) ) {
		global $wpdb;
		$table_db_name = $wpdb->prefix . 'webinarignition';
		
		// Directly include the table name without using prepare for it
		$webinarignition_webinars_list = $wpdb->get_results( 
			"SELECT postID, ID, appname FROM `{$table_db_name}`", 
			ARRAY_A 
		);
	}

	if ( empty( $webinarignition_webinars_list ) ) {
		return false;
	}

	foreach ( $webinarignition_webinars_list as $webinar ) {
		if ( ! empty( $webinar['postID'] ) && (int) $webinar['postID'] === (int) $id ) {
			return $webinar;
		}
	}

	return false;
}

add_action( 'wp_trash_post', 'webinarignition_protect_webinar_page' );
add_action( 'before_delete_post', 'webinarignition_protect_webinar_page' );

function webinarignition_protect_webinar_page( $postid ) {
	$is_webinar_page = webinarignition_admin_is_webinar_page( $postid );

	if ( $is_webinar_page ) {
		wp_die( esc_html__( 'The page you are trying to delete is used for one of your webinars, you need to delete the webinar first.', 'webinar-ignition' ) );
	}	
}

add_action( 'pre_post_update', 'webinarignition_protect_update_webinar_page', 1, 2 );

function webinarignition_protect_update_webinar_page( $post_id, $data ) {
	$is_webinar_page = webinarignition_admin_is_webinar_page( $post_id );

	if ( $is_webinar_page && $data['post_status'] !== 'publish' ) {
		wp_die( esc_html__( 'You cannot change the status of this page, as it is used for one of your webinars. You need to delete the webinar first.', 'webinar-ignition' ) );
	}	
}

add_action( 'admin_enqueue_scripts', 'webinarignition_hide_trash_button_in_editor' );

function webinarignition_hide_trash_button_in_editor() {
	$screen = get_current_screen();

	if (
		$screen->post_type !== 'page' ||
		( $screen->action !== 'edit' && $screen->action !== '' ) ||
		! isset( $_GET['post'] )
	) {
		return;
	}

	$is_webinar_page = webinarignition_admin_is_webinar_page( $_GET['post'] );
	if ( $is_webinar_page ) {
		$elements = '.editor-post-trash, #delete-action, .editor-post-switch-to-draft, .edit-post-post-template';
		$post = get_post( $_GET['post'] );
		$post_status = $post->post_status;
		$additional_styles = '';

		if ( 'publish' === $post_status ) {
			$elements .= ', .misc-pub-post-status .edit-post-status';
			$elements .= ', .misc-pub-visibility .edit-visibility';
			$elements .= ', .misc-pub-curtime .edit-timestamp';
			$additional_styles .= '.edit-post-post-visibility__toggle, .edit-post-post-schedule__toggle {
                pointer-events: none;
                font-weight: 600;
                color: #333!important;
            }';
		}

		wp_add_inline_style('wp-admin', "
            {$elements} { 
                display: none; 
            }
            {$additional_styles}
        ");
	}//end if
}

if ( isset( $_GET['page'] ) && $_GET['page'] == 'webinarignition-dashboard' && isset( $_GET['i_confirmed'] ) && ( $_GET['i_confirmed'] == 'true' || $_GET['i_confirmed'] == true ) ) {
	update_option( 'wi_optin_confirmed', true );
}

if ( isset( $_GET['page'] ) && $_GET['page'] == 'webinarignition-dashboard' && isset( $_GET['wi_support_confirmed'] ) && ( $_GET['wi_support_confirmed'] == 'true' || $_GET['wi_support_confirmed'] == true ) ) {
	update_option( 'wi_support_confirmed', true );
}
function wi_get_fse_color_by_slug( $slug ) {
    $theme_json = wp_get_global_settings( [ 'color', 'palette' ] );
    if ( isset( $theme_json['theme'] ) && is_array( $theme_json['theme'] ) ) {
        foreach ( $theme_json['theme'] as $color_item ) {
            if ( isset( $color_item['slug'] ) && $color_item['slug'] === $slug ) {
                return $color_item['color'];
            }
        }
    }
    return null; // Return null if slug not found
}