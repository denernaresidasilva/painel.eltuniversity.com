<?php

/**
 * Plugin Name: WebinarIgnition – Live & Automated Webinars for WooCommerce
 * Description: WebinarIgnition is a premium webinar solution that allows you to create, run and manage webinars. Build and fully customize, professional webinar registration, confirmation, live webinar and replay pages with ease.
 * Version: 4.06.07
 * Requires at least: 5.0
 * Tested up to: 6.9
 * Requires PHP: 7.2.5
 * Author: Saleswonder Team
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: webinar-ignition
 * Domain Path: /languages
 * Plugin URI: https://webinarignition.com
 * Prefix: wi
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
if ( !defined( 'WEBINARIGNITION_URL' ) ) {
    define( 'WEBINARIGNITION_URL', plugins_url( '/', __FILE__ ) );
}
if ( function_exists( 'webinarignition_fs' ) ) {
    webinarignition_fs()->set_basename( false, __FILE__ );
} else {
    if ( !defined( 'WEBINARIGNITION_PREVIOUS_VERSION' ) ) {
        define( 'WEBINARIGNITION_PREVIOUS_VERSION', '2.15.2' );
    }
    if ( !defined( 'WEBINARIGNITION_VERSION' ) ) {
        define( 'WEBINARIGNITION_VERSION', '4.06.07' );
    }
    if ( !defined( 'WEBINARIGNITION_BRANCH' ) ) {
        define( 'WEBINARIGNITION_BRANCH', '4.06.07 https://bitbucket.org/WP-Leads-Plugins/webinarignition/commits/branch/release/main' );
    }
    if ( !defined( 'WEBINARIGNITION_URL' ) ) {
        define( 'WEBINARIGNITION_URL', plugins_url( '/', __FILE__ ) );
    }
    if ( !defined( 'WEBINARIGNITION_PATH' ) ) {
        define( 'WEBINARIGNITION_PATH', plugin_dir_path( __FILE__ ) );
    }
    if ( !defined( 'WEBINARIGNITION_PLUGIN_FILE' ) ) {
        define( 'WEBINARIGNITION_PLUGIN_FILE', __FILE__ );
    }
    if ( !defined( 'WEBINARIGNITION_DB_VERSION' ) ) {
        define( 'WEBINARIGNITION_DB_VERSION', 12 );
    }
    if ( !defined( 'WEBINARIGNITION_PLUGIN_NAME' ) ) {
        define( 'WEBINARIGNITION_PLUGIN_NAME', "webinarignition" );
    }
    if ( !defined( 'WEBINARIGNITION_PLUGIN_BASENAME' ) ) {
        define( 'WEBINARIGNITION_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    }
    if ( !defined( 'WEBINARIGNITION_UPGRADE_LINK' ) ) {
        define( 'WEBINARIGNITION_UPGRADE_LINK', admin_url( 'admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true' ) );
    }
    // Plugin dev mode.
    define( 'WEBINARIGNITION_DEV_MODE', false );
    function schedule_daily_task() {
        if ( !wp_next_scheduled( 'webinarignition_daily_hook' ) ) {
            wp_schedule_event( strtotime( '00:00:00' ), 'daily', 'webinarignition_daily_hook' );
        }
        if ( !wp_next_scheduled( 'webinarignition_cron_hook' ) ) {
            $timestamp = time() + 120;
            // schedule 1 minute from now
            wp_schedule_event( $timestamp, 'five_minutes', 'webinarignition_cron_hook' );
        }
    }

    add_action( 'init', 'schedule_daily_task' );
    function add_custom_editor_dropdown(  $buttons  ) {
        array_push( $buttons, 'custom_dropdown' );
        return $buttons;
    }

    add_filter( 'mce_buttons', 'add_custom_editor_dropdown' );
    add_filter(
        'auto_update_plugin',
        function ( $update, $item ) {
            // Replace with your plugin's slug
            $allowed_slugs = array('webinar-ignition', 'webinarignition-premium');
            if ( isset( $item->slug ) && in_array( $item->slug, $allowed_slugs, true ) ) {
                return $update;
                // Not our plugin, leave as is
            }
            // Get current installed version
            $current_version = get_plugin_data( WP_PLUGIN_DIR . '/' . $item->slug )['Version'];
            $new_version = $item->new_version;
            // Compare major/minor version
            $current_parts = explode( '.', $current_version );
            $new_parts = explode( '.', $new_version );
            // Allow only minor updates (same major number)
            if ( $current_parts[0] === $new_parts[0] ) {
                return true;
                // Auto update allowed
            }
            // Otherwise (major update), disable auto update
            return false;
        },
        10,
        2
    );
    function register_custom_editor_plugin(  $plugin_array  ) {
        $plugin_array['custom_dropdown'] = WEBINARIGNITION_URL . 'assets/webinarignition-tiny-mce.js';
        return $plugin_array;
    }

    add_filter( 'mce_external_plugins', 'register_custom_editor_plugin' );
    if ( !function_exists( 'webinarignition_fs' ) ) {
        /**
         * Create a helper function for easy SDK access.
         * DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
         *
         * @since 3.08.1
         */
        function webinarignition_fs() {
            global $webinarignition_fs;
            if ( !isset( $webinarignition_fs ) ) {
                if ( !defined( 'WP_FS__PRODUCT_7606_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_7606_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once __DIR__ . '/vendor/freemius/start.php';
                $webinarignition_fs = fs_dynamic_init( array(
                    'id'               => '7606',
                    'slug'             => 'webinar-ignition',
                    'type'             => 'plugin',
                    'public_key'       => 'pk_78db77544c037d3e892f673cf65d4',
                    'is_premium'       => false,
                    'has_addons'       => false,
                    'has_paid_plans'   => true,
                    'is_org_compliant' => true,
                    'trial'            => array(
                        'days'               => 30,
                        'is_require_payment' => true,
                    ),
                    'has_affiliation'  => 'selected',
                    'menu'             => array(
                        'slug'       => 'webinarignition-dashboard',
                        'support'    => false,
                        'contact'    => false,
                        'first-path' => 'admin.php?page=webinarignition-dashboard',
                    ),
                    'is_live'          => true,
                ) );
            }
            //end if
            return $webinarignition_fs;
        }

        webinarignition_fs();
        do_action( 'webinarignition_fs_loaded' );
    }
    //end if
    //end if
    // ✅ Shared message body
    function wi_build_message_body() {
        return '<span style="display:block; margin-bottom:6px;">' . esc_html__( "Here's what's waiting for you:", 'webinar-ignition' ) . '</span>' . '<span style="display:block; margin-left:4px; line-height:2;">' . '✅ ' . esc_html__( 'Everything runs free — YouTube Live, automated webinars, unlimited attendees', 'webinar-ignition' ) . '<br>' . '✅ ' . esc_html__( 'Free weekly live sessions — questions answered, setup done together', 'webinar-ignition' ) . '<br>' . '✅ ' . esc_html__( 'Short tips on how to actually use it — before your next session', 'webinar-ignition' ) . '<br>' . '✅ ' . esc_html__( 'Security & feature updates — so nothing breaks quietly', 'webinar-ignition' ) . '<br>' . '✅ ' . esc_html__( 'Occasional offers — only when relevant', 'webinar-ignition' ) . '<br>' . '</span>' . '<span style="display:block; margin-top:10px; font-size:0.9em; color:#666;">' . esc_html__( 'To keep WebinarIgnition running well on your site, clicking also shares basic WP info. Unsubscribe anytime.', 'webinar-ignition' ) . '</span>';
    }

    // ✅ Fresh install message
    function wi_build_connect_message() {
        return '<span style="font-size:1.0em; line-height:1.8; color:#2c3338; display:block;">' . '<strong style="font-size:1.25em; display:block; margin-bottom:10px;">' . esc_html__( 'WebinarIgnition is active.', 'webinar-ignition' ) . '<br>' . esc_html__( "Here's what most people don't use yet.", 'webinar-ignition' ) . '</strong>' . wi_build_message_body() . '</span>';
    }

    // ✅ Update message
    function wi_build_connect_message_on_update() {
        return '<span style="font-size:1.0em; line-height:1.8; color:#2c3338; display:block;">' . '<strong style="font-size:1.25em; display:block; margin-bottom:10px;">' . esc_html__( 'WebinarIgnition was just updated.', 'webinar-ignition' ) . '<br>' . esc_html__( 'One thing is worth knowing.', 'webinar-ignition' ) . '</strong>' . wi_build_message_body() . '</span>';
    }

    // ✅ Customize Freemius opt-in
    function webinarignition_customize_freemius_optin() {
        webinarignition_fs()->add_filter(
            'connect_message',
            function (
                $message,
                $user_first_name,
                $plugin_title,
                $user_login,
                $site_link,
                $freemius_link
            ) {
                return wi_build_connect_message();
            },
            10,
            6
        );
        webinarignition_fs()->add_filter(
            'connect_message_on_update',
            function (
                $message,
                $user_first_name,
                $plugin_title,
                $user_login,
                $site_link,
                $freemius_link
            ) {
                return wi_build_connect_message_on_update();
            },
            10,
            6
        );
        webinarignition_fs()->override_i18n( array(
            'opt-in-connect' => esc_html__( '👉 Yes, I\'m in →', 'webinar-ignition' ),
            'skip'           => esc_html__( 'No thanks', 'webinar-ignition' ),
        ) );
    }

    add_action( 'init', 'webinarignition_customize_freemius_optin', 20 );
    // ✅ Button-Reihenfolge + Styling
    add_action( 'admin_head', function () {
        if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'webinarignition-dashboard' ) !== false ) {
            echo '<style>
            #fs_connect .fs-content h2 {
                display: none !important;
            }
            #fs_connect .fs-actions {
                display: flex !important;
                flex-direction: row !important;
                align-items: center !important;
                justify-content: flex-end !important;
                flex-wrap: nowrap !important;
                gap: 16px !important;
            }
            #fs_connect .fs-actions > a,
            #fs_connect #fs_skip_activation {
                order: 1 !important;
                flex: 0 0 auto !important;
                color: #999 !important;
                font-size: 0.9em !important;
                background: none !important;
                border: none !important;
                box-shadow: none !important;
                float: none !important;
                white-space: nowrap !important;
                display: inline-block !important;
            }
            #fs_connect .fs-actions form {
                order: 2 !important;
                flex: 0 0 auto !important;
                width: auto !important;
                display: inline-flex !important;
            }
            #fs_connect .fs-actions form .button-primary,
            #fs_connect .fs-actions .button-primary {
                white-space: nowrap !important;
                height: auto !important;
                line-height: 1.5 !important;
                padding: 8px 18px !important;
                font-size: 1.1em !important;
                font-weight: 700 !important;
                display: inline-block !important;
                width: auto !important;
                transition: all 0.15s ease !important;
            }
            #fs_connect .fs-actions form .button-primary:hover,
            #fs_connect .fs-actions .button-primary:hover {
                background-color: #1a4f9c !important;
                border-color: #1a4f9c !important;
                transform: translateY(-1px) !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2) !important;
            }
        </style>';
        }
    } );
    // Include the main WebinarIgnition class.
    if ( !class_exists( 'WebinarIgnition' ) ) {
        include_once __DIR__ . '/inc/class-webinarignition.php';
    }
    if ( !class_exists( 'WI_Install' ) ) {
        include_once __DIR__ . '/inc/class.WebinarignitionManager.php';
        include_once __DIR__ . '/inc/class-wi-install.php';
        register_activation_hook( WEBINARIGNITION_PLUGIN_FILE, array('WI_Install', 'install') );
    }
    register_activation_hook( __FILE__, 'webinarignition_activate' );
    register_deactivation_hook( __FILE__, 'webinarignition_deactivate' );
    if ( !function_exists( 'webinarignition_activate' ) ) {
        function webinarignition_activate() {
            webinarignition_deactivate_previous();
            // Deactivate any previously active instance of the plugin, before activating new one
            include_once WEBINARIGNITION_PATH . 'inc/wi-activation.php';
            webinarignition_installer();
            // TODO: Separate the code for setting up smtp after plugin installation. line:130-146
            if ( isset( $_REQUEST['action'] ) && 'activate-selected' === $_REQUEST['action'] && (isset( $_POST['checked'] ) && count( $_POST['checked'] ) > 1) ) {
                // phpcs:ignore
                return;
            }
            if ( empty( intval( get_option( 'wi_first_install' ) ) ) ) {
                add_option( 'wi_redirect_after_installation', wp_get_current_user()->ID );
                update_option( 'webinarignition_smtp_name', get_bloginfo( 'name' ) );
                $protocols = array(
                    'http://',
                    'https://',
                    'http://www.',
                    'https://www.',
                    'www.'
                );
                $site_domain = str_replace( $protocols, '', site_url() );
                update_option( 'webinarignition_smtp_email', 'webinar@' . $site_domain );
            }
            update_option( 'webinarignition_activated', 1 );
            update_option( 'webinarignition_branding_copy', 'Webinar powered by WebinarIgnition' );
            do_action( 'webinarignition_activated' );
        }

    }
    //end if
    if ( !function_exists( 'webinarignition_deactivate' ) ) {
        function webinarignition_deactivate() {
            $timestamp = wp_next_scheduled( 'webinarignition_cron_hook' );
            wp_unschedule_event( $timestamp, 'webinarignition_cron_hook' );
        }

    }
    if ( !function_exists( 'webinarignition_deactivate_previous' ) ) {
        /**
         * Deactivate any previously active instance of the plugin
         */
        function webinarignition_deactivate_previous() {
            if ( current_user_can( 'activate_plugins' ) ) {
                // Include necessary WordPress plugin functions
                if ( !function_exists( 'is_plugin_active_for_network' ) || !function_exists( 'deactivate_plugins' ) ) {
                    include_once ABSPATH . '/wp-admin/includes/plugin.php';
                }
                // Determine the current plugin's directory to avoid self-deactivation
                $current_plugin_dir = dirname( plugin_basename( __FILE__ ) );
                // Target directory names excluding the current plugin's directory
                $target_dirs = array('webinarignition', 'webinar-ignition', 'webinarignition-premium');
                $target_dirs = array_diff( $target_dirs, array($current_plugin_dir) );
                // Exit if no target directories remain
                if ( empty( $target_dirs ) ) {
                    return;
                }
                // Collect all active plugins (both site and network)
                $active_plugins = (array) get_option( 'active_plugins', array() );
                if ( is_multisite() ) {
                    $network_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
                    $active_plugins = array_merge( $active_plugins, array_keys( $network_plugins ) );
                }
                // Deactivate target plugins
                foreach ( $active_plugins as $plugin ) {
                    $plugin_dir = dirname( $plugin );
                    if ( in_array( $plugin_dir, $target_dirs ) ) {
                        deactivate_plugins( $plugin, true );
                    }
                }
            }
        }

    }
    if ( !function_exists( 'WebinarIgnition' ) ) {
        /**
         * Returns the main instance of WebinarIgnition.
         *
         * @return WebinarIgnition|null
         */
        function webinar_ignition() {
            return WebinarIgnition::instance();
        }

        add_action( 'plugins_loaded', 'webinar_ignition' );
    }
    function webinarignition_my_plugin_load_textdomain() {
        load_plugin_textdomain( 'webinar-ignition', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    add_action( 'init', 'webinarignition_my_plugin_load_textdomain' );
    /**
     * Force PHP translations to load from this plugin's languages directory first.
     */
    function wi_force_plugin_mofile(  $mofile, $domain  ) {
        if ( 'webinar-ignition' !== $domain ) {
            return $mofile;
        }
        $locale = ( function_exists( 'determine_locale' ) ? determine_locale() : get_locale() );
        $locale = apply_filters( 'plugin_locale', $locale, $domain );
        // 1. Path where Loco Translate saves global plugin translations
        $loco_global_mofile = WP_CONTENT_DIR . '/languages/loco/plugins/' . $domain . '-' . $locale . '.mo';
        // 2. Path where Loco Translate may save local translations in your plugin folder
        $loco_local_mofile = plugin_dir_path( __FILE__ ) . 'languages/loco/' . $domain . '-' . $locale . '.mo';
        // 3. Your plugin's default translation file
        $plugin_mofile = plugin_dir_path( __FILE__ ) . 'languages/' . $domain . '-' . $locale . '.mo';
        // Prefer Loco Translate's file if it exists
        if ( file_exists( $loco_global_mofile ) ) {
            return $loco_global_mofile;
        }
        if ( file_exists( $loco_local_mofile ) ) {
            return $loco_local_mofile;
        }
        // Fallback to plugin's own language file
        if ( file_exists( $plugin_mofile ) ) {
            return $plugin_mofile;
        }
        return $mofile;
    }

    add_filter(
        'load_textdomain_mofile',
        'wi_force_plugin_mofile',
        10,
        2
    );
}
//end if
if ( !function_exists( 'hide_freemius_notification_bubbles' ) ) {
    add_action( 'admin_head', 'hide_freemius_notification_bubbles' );
    function hide_freemius_notification_bubbles() {
        echo '<style>
			.update-plugins[class^="fs-"],
			.update-plugins[class*=" fs-"] {
				display: none !important;
			}
				@media screen and (min-width: 801px) {
					#adminmenu .toplevel_page_webinarignition-dashboard  div.wp-menu-name {
						min-width: 170px;
		
					}
				}
		</style>';
    }

}
// 		#adminmenu, #adminmenu .wp-submenu, #adminmenuback, #adminmenuwrap{
// 	/* this style to make admin menu notification in the same line of webinarignition */
// 	width: 170px;
// }
// remove the optin header
add_action( 'admin_head', function () {
    if ( isset( $_GET['page'] ) && strpos( $_GET['page'], 'webinarignition-dashboard' ) !== false ) {
        echo '<style>
                #fs_connect .fs-content h2 {
                    display: none !important;
                }
            </style>';
    }
} );