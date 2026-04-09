<?php

/**
 * Responsible plugin common assets.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( 'Webinar_Ignition_Common_Scripts' ) ) {
    /**
     * Plugin common assets manager.
     *
     * @since 3.08.1
     */
    class Webinar_Ignition_Common_Scripts {
        public function webinarignition_get_default_localizeable() {
            $localizeable_data = array(
                'ajax_url'                  => esc_url( admin_url( 'admin-ajax.php' ) ),
                'nonce'                     => esc_attr( wp_create_nonce( 'webinarignition_ajax_nonce' ) ),
                'site_url'                  => esc_url( get_site_url() ),
                'home_url'                  => esc_url( home_url() ),
                'plugin_name'               => 'webinarignition',
                'current_webinar_page'      => get_query_var( 'webinarignition_page' ),
                'current_user'              => wp_get_current_user(),
                'current_user_can'          => array(
                    'edit_posts' => current_user_can( 'edit_posts' ),
                ),
                'branding'                  => wp_validate_boolean( get_option( 'webinarignition_show_footer_branding', false ) ),
                'enable_third_party_server' => absint( get_option( 'webinarignition_enable_third_party_server', 0 ) ),
            );
            if ( is_user_logged_in() ) {
                $site_url = get_site_url();
                $lkey = new stdClass();
                $lkey->switch = 'free';
                $lkey->slug = 'free';
                $lkey->licensor = '';
                $lkey->is_free = 1;
                $lkey->is_dev = '';
                $lkey->is_registered = '';
                $lkey->title = 'Free';
                $lkey->member_area = '';
                $lkey->is_pending_activation = 1;
                $lkey->upgrade_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
                $lkey->trial_url = $site_url . '/wp-admin/admin.php?billing_cycle=annual&trial=true&page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
                $lkey->reconnect_url = $site_url . '/wp-admin/admin.php?nonce=fc5eb326b0&fs_action=webinar-ignition_reconnect&page=webinarignition-dashboard';
                $lkey->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
                $lkey->name = '';
                $lkey->is_trial = null;
            }
            return apply_filters( 'webinarignition_common_localizeable_scripts', $localizeable_data );
        }

        function webinarignition_register_grid_scripts() {
            $assets = WEBINARIGNITION_URL . 'inc/lp/';
            wp_register_script(
                'webinarignition_grid_js',
                $assets . 'js/webinar-grid.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_enqueue_script( 'webinarignition_grid_js' );
        }

        function webinarignition_register_frontend_scripts() {
            $assets = WEBINARIGNITION_URL . 'inc/lp/';
            // Register styles
            wp_register_style(
                'webinarignition_webinar_new',
                $assets . 'css/webinar-new.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_webinar_modern',
                $assets . 'css/webinar-modern.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_webinar_shared',
                $assets . 'css/webinar-shared.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_head_style',
                $assets . 'css/head-style.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_video_css',
                $assets . 'video-js-8.17.4/video-js.min.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_normalize',
                $assets . 'css/normalize.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_bootstrap',
                $assets . 'css/bootstrap.min.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_foundation',
                $assets . 'css/foundation.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_font-awesome',
                $assets . 'css/font-awesome.min.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_main',
                $assets . 'css/main.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_main_template',
                $assets . 'css/main-template.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_cp',
                $assets . 'css/cp.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_ss',
                $assets . 'css/ss.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_cpres_ty',
                $assets . 'css/cpres_ty.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_intlTelInput',
                $assets . 'js-libs/css/intlTelInput.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_css_utils',
                $assets . 'css/utils.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_cdres',
                $assets . 'css/cdres.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_countdown',
                $assets . 'css/countdown.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_countdown_ty',
                $assets . 'css/countdown-ty.css',
                array('webinarignition_countdown'),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_countdown_ty_inline',
                false,
                array('webinarignition_countdown_ty'),
                WEBINARIGNITION_VERSION
            );
            wp_register_style(
                'webinarignition_countdown_replay',
                $assets . 'css/countdown-replay.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_auto_register_css',
                $assets . 'css/auto_register_css.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_webinar',
                $assets . 'css/webinar.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition_head_style_after',
                $assets . 'css/head-style-after.css',
                array(),
                WEBINARIGNITION_VERSION,
                'all'
            );
            // Register head scripts
            wp_register_script(
                'webinarignition_linkedin_js',
                '//platform.linkedin.com/in.js',
                array(),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_register_script(
                'webinarignition_video_js',
                $assets . 'video-js-8.17.4/video-js.min.js',
                array(),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_enqueue_script( 'moment' );
            wp_register_script(
                'webinarignition_js_utils',
                $assets . 'js/utils.js',
                array('moment'),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_register_script(
                'webinarignition_cookie_js',
                $assets . 'js/cookie.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_register_script(
                'webinarignition_webinar_data_after_js',
                $assets . 'js/webinar-data-after.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_register_script(
                'webinarignition_js_countdown',
                $assets . 'js/countdown.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_register_script(
                'webinarignition_polling_js',
                $assets . 'js/polling.js',
                array('jquery', 'webinarignition_cookie_js'),
                WEBINARIGNITION_VERSION,
                false
            );
            // Register footer scripts
            wp_register_script(
                'webinarignition_after_head_js',
                $assets . 'js/after-head.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            // Register footer scripts
            wp_register_script(
                'webinarignition_before_footer_js',
                $assets . 'js/before-footer.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_stripe_js',
                'https://js.stripe.com/v2/',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_intlTelInput_js',
                $assets . 'js-libs/js/intlTelInput.js',
                array('jquery', 'webinarignition_cookie_js'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_updater_js',
                $assets . 'js/updater.js',
                array('jquery', 'webinarignition_cookie_js', 'webinarignition_polling_js'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_frontend_js',
                $assets . 'js/frontend.js',
                array('jquery', 'webinarignition_cookie_js', 'webinarignition_intlTelInput_js'),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_countdown_js',
                $assets . 'js/countdown.js',
                array('webinarignition_cookie_js', 'webinarignition_intlTelInput_js', 'webinarignition_frontend_js'),
                WEBINARIGNITION_VERSION,
                false
            );
            // WP does not load in-line scripts/styles in shorcodes by default
            // Workaround - Register a script with false path and then enqueue inline script to it
            wp_register_script(
                'webinarignition_countdown_ty_inline',
                false,
                array('webinarignition_countdown_js'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_tz_js',
                $assets . 'js/tz.js',
                array(
                    'jquery',
                    'webinarignition_cookie_js',
                    'webinarignition_intlTelInput_js',
                    'webinarignition_frontend_js'
                ),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_luxon_js',
                $assets . 'js/luxon.min.js',
                array(
                    'jquery',
                    'webinarignition_cookie_js',
                    'webinarignition_intlTelInput_js',
                    'webinarignition_frontend_js'
                ),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition_registration_js',
                $assets . 'js/registration-page.js',
                array(
                    'jquery',
                    'webinarignition_cookie_js',
                    'webinarignition_intlTelInput_js',
                    'webinarignition_frontend_js',
                    'webinarignition_tz_js',
                    'webinarignition_luxon_js'
                ),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_webinar_new_js',
                $assets . 'js/webinar-new.js',
                array('jquery'),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_webinar_modern_js',
                $assets . 'js/webinar-modern.js',
                array('jquery'),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_backup_js',
                $assets . 'js/backup.js',
                array('jquery', 'webinarignition_video_js'),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_webinar_cta_js',
                $assets . 'js/webinar-cta.js',
                array('jquery'),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_webinar_shared_js',
                $assets . 'js/webinar-shared.js',
                array('jquery'),
                WEBINARIGNITION_VERSION . '-' . time(),
                true
            );
            wp_register_script(
                'webinarignition_after_footer_js',
                $assets . 'js/after-footer.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
        }

        public function webinarignition_register_styles() {
            $assets = WEBINARIGNITION_URL . 'inc/lp/';
            wp_register_style(
                'webinarignition-bootstrap',
                $assets . 'css/bootstrap.min.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition-foundation',
                $assets . 'css/foundation.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition-intlTelInput',
                $assets . 'js-libs/css/intlTelInput.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition-main',
                $assets . 'css/main.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition-font-awesome',
                $assets . 'css/font-awesome.min.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition-css-utils',
                $assets . 'css/utils.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_style(
                'webinarignition-ss',
                $assets . 'css/ss.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_register_script(
                'webinarignition-momentjs',
                $assets . 'js/moment.min.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                array(
                    'in_footer' => true,
                )
            );
            wp_register_script(
                'webinarignition-utils',
                $assets . 'js/utils.js',
                array('webinarignition-momentjs'),
                WEBINARIGNITION_VERSION,
                array(
                    'in_footer' => true,
                )
            );
            wp_register_style(
                'webinarignition-video-player',
                $assets . 'video-js-8.17.4/video-js.min.css',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                array(
                    'in_footer' => true,
                )
            );
        }

    }

}
//end if