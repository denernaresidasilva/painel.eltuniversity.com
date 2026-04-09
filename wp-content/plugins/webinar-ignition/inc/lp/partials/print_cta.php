<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$cta_index = ( isset( $index ) ? absint( $index ) : 0 );
$cta_iframe = '';
$cta_iframe_sc = '';
$cta_content = '';
if ( 0 === $cta_index ) {
    // Main CTA
    if ( isset( $webinar_data->cta_iframe ) ) {
        $cta_iframe = $webinar_data->cta_iframe;
    }
    if ( isset( $webinar_data->cta_iframe_sc ) ) {
        $cta_iframe_sc = $webinar_data->cta_iframe_sc;
    }
    if ( isset( $webinar_data->auto_action_copy ) ) {
        $cta_content = $webinar_data->auto_action_copy;
    }
} else {
    // Additional CTA
    if ( isset( $additional_autoaction['cta_iframe'] ) ) {
        $cta_iframe = $additional_autoaction['cta_iframe'];
    }
    if ( isset( $additional_autoaction['cta_iframe_sc'] ) ) {
        $cta_iframe_sc = $additional_autoaction['cta_iframe_sc'];
    }
    if ( isset( $additional_autoaction['auto_action_copy'] ) ) {
        $cta_content = $additional_autoaction['auto_action_copy'];
    }
}
//end if
$cta_iframe = strtolower( $cta_iframe );
$cta_iframe_sc = wp_kses_post( $cta_iframe_sc );
$cta_content = stripcslashes( wpautop( $cta_content ) );
$site_url = get_site_url();
$statusCheck = new stdClass();
$statusCheck->switch = 'free';
$statusCheck->slug = 'free';
$statusCheck->licensor = '';
$statusCheck->is_free = 1;
$statusCheck->is_dev = '';
$statusCheck->is_registered = '';
$statusCheck->title = 'Free';
$statusCheck->member_area = '';
$statusCheck->is_pending_activation = 1;
$statusCheck->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
$statusCheck->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
$statusCheck->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
$statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
$statusCheck->name = '';
if ( $lead ) {
    $lead_meta = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead->ID, 'wiRegForm', ( 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' ) );
    if ( !empty( $lead_meta['meta_value'] ) ) {
        $lead_meta_data = maybe_unserialize( $lead_meta['meta_value'] );
        $lead_meta_data = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta_data );
    } else {
        $lead_meta_data = array();
    }
    $cta_content = str_replace( '{EMAIL}', $lead->email, $cta_content );
    if ( in_array( 'full_name', array_keys( $lead_meta_data ) ) ) {
        $cta_content = str_replace( '{FULLNAME}', $lead_meta_data['full_name']['value'], $cta_content );
    } else {
        $cta_content = str_replace( '{FULLNAME}', $lead->name, $cta_content );
    }
    if ( in_array( 'optReason', array_keys( $lead_meta_data ) ) ) {
        $cta_content = str_replace( '{REASON}', $lead_meta_data['optReason']['value'], $cta_content );
    } else {
        $cta_content = str_replace( '{REASON}', '', $cta_content );
    }
    if ( in_array( 'optSalutation', array_keys( $lead_meta_data ) ) ) {
        $cta_content = str_replace( '{SALUTATION}', $lead_meta_data['optSalutation']['value'], $cta_content );
    } else {
        $cta_content = str_replace( '{SALUTATION}', '', $cta_content );
    }
    if ( in_array( 'optPhone', array_keys( $lead_meta_data ) ) ) {
        $phone_clean = preg_replace( '/\\s+/', '', $lead_meta_data['optPhone']['value'] );
        $cta_content = str_replace( '{PHONENUM}', $phone_clean, $cta_content );
    } else {
        $cta_content = str_replace( '{PHONENUM}', '', $cta_content );
    }
    if ( in_array( 'optName', array_keys( $lead_meta_data ) ) ) {
        $cta_content = str_replace( '{FIRSTNAME}', $lead_meta_data['optName']['value'], $cta_content );
    } else {
        $cta_content = str_replace( '{FIRSTNAME}', $lead->name, $cta_content );
    }
    if ( in_array( 'optLName', array_keys( $lead_meta_data ) ) ) {
        $cta_content = str_replace( '{LASTNAME}', $lead_meta_data['optLName']['value'], $cta_content );
    } else {
        $cta_content = str_replace( '{LASTNAME}', '', $cta_content );
    }
    // Handle optCustom_1 to optCustom_18
    for ($i = 1; $i <= 18; $i++) {
        $meta_key = 'optCustom_' . $i;
        $placeholder = '{CUSTOM' . $i . '}';
        if ( isset( $lead_meta_data[$meta_key]['value'] ) ) {
            $cta_content = str_replace( $placeholder, $lead_meta_data[$meta_key]['value'], $cta_content );
        } else {
            $cta_content = str_replace( $placeholder, '', $cta_content );
        }
    }
}
$webinar_id = absint( $webinar_data->id );
$cta_content = apply_filters( 'webinarignition_additional_cta_content', $cta_content );
if ( class_exists( 'advancediFrame' ) && 'yes' === $cta_iframe && false === strpos( $cta_content, '[advanced_ifram' ) ) {
    $cta_content .= webinarignition_get_cta_aiframe_sc( $webinar_id, $cta_index, $cta_iframe_sc );
    $cta_content = apply_filters( 'ai_handle_temp_pages', $cta_content );
}
// Remove <iframe> tags for security purpose
$cta_content = preg_replace( '/<iframe[^>]*>.*?<\\/iframe>/is', '', $cta_content );
// Remove [advanced_iframe] shortcode for security purpose
$cta_content = preg_replace( '/\\[advanced_iframe[^\\]]*\\]/i', '', $cta_content );
echo do_shortcode( $cta_content );