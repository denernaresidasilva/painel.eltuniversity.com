<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
class WebinarignitionPowerups {
    public static function webinarignition_is_powerups_enabled( $webinar_data ) {
        return true;
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
        $type = ( $webinar_data->webinar_date === 'AUTO' ? 'auto' : 'live' );
        if ( $statusCheck && 'free' === $statusCheck->switch ) {
            if ( !empty( $statusCheck->is_registered ) && 'auto' === $type ) {
                return true;
            }
        }
        return false;
    }

    public static function webinarignition_is_shortcodes_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_multiple_cta_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_multiple_auto_time_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_too_late_lockout_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_modern_template_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_multiple_support_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_two_way_qa_enabled( $webinar_data ) {
        $type = ( $webinar_data->webinar_date === 'AUTO' ? 'auto' : 'live' );
        if ( 'auto' === $type ) {
            return false;
        }
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

    public static function webinarignition_is_secure_access_enabled( $webinar_data ) {
        return self::webinarignition_is_powerups_enabled( $webinar_data );
    }

}
