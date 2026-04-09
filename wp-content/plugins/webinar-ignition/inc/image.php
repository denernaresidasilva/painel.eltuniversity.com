<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Adding media uploader assets.
 */
function webinarignition_admin_img_upload_scripts() {
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
}

function webinarignition_admin_styles() {
	wp_enqueue_style( 'thickbox' );
}

add_action( 'admin_print_scripts', 'webinarignition_admin_img_upload_scripts' );
add_action( 'admin_print_styles', 'webinarignition_admin_styles' );
