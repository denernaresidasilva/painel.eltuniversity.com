<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
function check_freemius_update_available() {
    // need to write freemius version checking code here
    return false;
    // $product_id       = 7606;                          // your Freemius “product” ID
    // $api_token        = 'xxxxxxxxxxxxxxxxxxxxxxxxx'; // from Freemius dashboard
    // $installed        = WEBINARIGNITION_VERSION;       // your current version
    // $url = "https://api.freemius.com/v1/products/{$product_id}.json";
    // $response = wp_remote_get( $url, [
    //     'headers' => [
    //         'Authorization' => 'Bearer ' . $api_token,
    //         'Accept'        => 'application/json',
    //     ],
    //     'timeout' => 15,
    // ] );
    // if ( is_wp_error( $response ) ) return false;
    // $body = wp_remote_retrieve_body( $response );
    // $data = json_decode( $body );
    // if ( empty( $data->version ) ) return false;
    // return version_compare( $data->version, $installed, '>' );
}

function is_webinarignition_wporg_update_available() {
    if ( !function_exists( 'get_plugin_updates' ) ) {
        require_once ABSPATH . 'wp-admin/includes/update.php';
    }
    // Get update info - wordpress built in update system
    $update_plugins = get_site_transient( 'update_plugins' );
    if ( isset( $update_plugins->response['webinarignition/webinarignition.php'] ) || isset( $update_plugins->response['webinar-ignition/webinarignition.php'] ) ) {
        return true;
    }
    return false;
}

function get_latest_plugin_version_from_wporg(  $plugin_slug  ) {
    $option_key = 'wi_latest_version_in_wp_org';
    $data = get_option( $option_key );
    $current_time = time();
    $one_day = 24 * 60 * 60;
    // $one_day = 1;
    // Check if version is cached and not expired
    if ( is_array( $data ) && isset( $data['version'], $data['timestamp'] ) ) {
        if ( $current_time - $data['timestamp'] < $one_day ) {
            return $data['version'];
            // Return cached version
        }
    }
    // Make API call if cache is expired or not set
    if ( !function_exists( 'plugins_api' ) ) {
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    }
    $api = plugins_api( 'plugin_information', array(
        'slug'   => sanitize_text_field( $plugin_slug ),
        'fields' => array(
            'sections' => false,
        ),
    ) );
    if ( is_wp_error( $api ) || !isset( $api->version ) ) {
        return ( isset( $data['version'] ) ? $data['version'] : false );
        // fallback to old version if available
    }
    // Webinarignition::print_pre($api);
    // wp_die();
    // Save new version and timestamp
    update_option( $option_key, array(
        'version'   => $api->version,
        'timestamp' => $current_time,
    ) );
    return $api->version;
}

function webinarignition_get_pending_notifications_count(  $menu_item = "all"  ) {
    $notif_count = 0;
    $is_pending_activation = webinarignition_fs()->is_pending_activation();
    $is_registered = webinarignition_fs()->is_registered() && webinarignition_fs()->is_tracking_allowed();
    // $latest_version = "0.0.0";
    // Check if activation is pending and user is not registered
    // var_dump($is_pending_activation); // false
    // Webinarignition::print_pre($is_pending_activation);
    // wp_die();
    // $latest_version = get_latest_plugin_version_from_wporg('webinar-ignition');
    // $current_version = defined('WEBINARIGNITION_VERSION') ? WEBINARIGNITION_VERSION : null;
    // update_option('webinarignition_update_available', false);
    // $update_flag = false;
    // if (webinarignition_fs()->is__premium_only()) {
    // 	if (check_freemius_update_available()) {
    // 		if (!$update_flag) {
    // 			update_option('webinarignition_update_available', true);
    // 		}
    // 	}
    // } else {
    // 	if ($latest_version && $current_version && version_compare($current_version, $latest_version, '<')) {
    // 		if (!$update_flag) {
    // 			update_option('webinarignition_update_available', true);
    // 		}
    // 	}
    // }
    // If the update flag is set, increment the count
    // if (get_option('webinarignition_update_available', false)) {
    // 	$notif_count++;
    // }
    if ( is_webinarignition_wporg_update_available() ) {
        $notif_count++;
    }
    if ( $menu_item == "changelog" ) {
        return $notif_count;
    }
    if ( !$is_pending_activation && !$is_registered ) {
        $notif_count++;
    }
    return $notif_count;
}

add_action( 'admin_menu', 'webinarignition_admin_menu' );
// 2. Add the notification bubble using the 'admin_menu' filter
add_filter( 'admin_menu', function () {
    global $menu;
    // Get the number of pending notifications (replace with your logic)
    $notification_count = webinarignition_get_pending_notifications_count();
    // Example: returns 5
    if ( $notification_count > 0 ) {
        // Loop through the admin menu to find our item
        foreach ( $menu as $key => $value ) {
            // Check if this is our menu item (matching the slug)
            if ( $menu[$key][2] === 'webinarignition-dashboard' ) {
                // Append the WordPress-style notification bubble
                $menu[$key][0] .= sprintf( '<span class="update-plugins count-%d"><span class="plugin-count">%d</span></span>', $notification_count, $notification_count );
                break;
            }
        }
    }
} );
add_action( 'admin_menu', function () {
    global $submenu;
    if ( isset( $submenu['webinarignition-dashboard'] ) ) {
        // Define the mapping of submenu items to their custom classes
        $custom_classes = [
            'Webinars'        => 'webinarIgnition_dashboard_webinar',
            'Create Webinar'  => 'webinarIgnition_dashboard_create',
            'WebinarIgnition' => 'webinarIgnition_dashboard_main',
        ];
        foreach ( $submenu['webinarignition-dashboard'] as &$item ) {
            // Check if the item exists in the mapping and apply the custom class
            if ( array_key_exists( $item[0], $custom_classes ) ) {
                $item[0] = '<span class="' . $custom_classes[$item[0]] . '">' . $item[0] . '</span>';
                // Add custom class
            }
        }
    }
} );
function webinarignition_admin_menu() {
    // Create a new stdClass object
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
    $icon_image = 'data:image/svg+xml;base64,' . base64_encode( '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve">  <image id="image0" x="0" y="0"
			href="data:image/png;base64,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" />
		</svg>' );
    add_menu_page(
        'WebinarIgnition',
        'WebinarIgnition',
        'manage_options',
        'webinarignition-dashboard',
        'webinarignition_dashboard',
        $icon_image,
        2
    );
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'Webinars', 'webinar-ignition' ),
        __( 'Webinars', 'webinar-ignition' ),
        'manage_options',
        'webinarignition-dashboard&webinars',
        'webinarignition_dashboard'
    );
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'Create Webinar', 'webinar-ignition' ),
        __( 'Create Webinar', 'webinar-ignition' ),
        'manage_options',
        esc_url( admin_url( 'admin.php?page=webinarignition-dashboard&create' ) )
    );
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'WebinarIgnition Settings', 'webinar-ignition' ),
        __( 'Settings', 'webinar-ignition' ),
        'manage_options',
        'webinarignition_settings',
        'webinarignition_settings_submenu_page'
    );
    $is_pending_activation = webinarignition_fs()->is_pending_activation();
    $is_registered = webinarignition_fs()->is_registered() && webinarignition_fs()->is_tracking_allowed();
    if ( !$is_pending_activation && !$is_registered ) {
        global $submenu;
        $submenu['webinarignition-dashboard'][] = array(
            //phpcs:ignore
            sprintf( '<span class="opt-in-menu-item">%s <span class="update-plugins">1</span></span>', __( 'Opt-in 4 success', 'webinar-ignition' ) ),
            'manage_options',
            'admin.php?page=webinarignition-dashboard#opt-in-box',
        );
    }
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'Grid View', 'webinar-ignition' ),
        __( 'Grid View', 'webinar-ignition' ),
        'manage_options',
        'webinarignition_grid',
        'webinarignition_grid_submenu_page'
    );
    // KB for new integration
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'NEW Built in Meeting', 'webinar-ignition' ),
        __( 'NEW Built in Meeting', 'webinar-ignition' ),
        'manage_options',
        '__integrated-meeting',
        '__return_false'
    );
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'Solution & Support', 'webinar-ignition' ),
        __( 'Support', 'webinar-ignition' ),
        'manage_options',
        'webinarignition_support',
        'webinarignition_support_submenu_page'
    );
    add_submenu_page(
        'webinarignition-dashboard',
        __( 'Webinarignition Changelog', 'webinar-ignition' ),
        __( 'Changelog', 'webinar-ignition' ),
        'manage_options',
        'webinarignition_changelog',
        'webinarignition_changelog_submenu_page'
    );
    global $submenu;
    // Get the number of pending notifications or use a dummy value
    $notification_count = webinarignition_get_pending_notifications_count( 'changelog' );
    if ( $notification_count > 0 && isset( $submenu['webinarignition-dashboard'] ) ) {
        foreach ( $submenu['webinarignition-dashboard'] as &$submenu_item ) {
            // Check if this is the submenu item for "webinarignition_changelog"
            if ( $submenu_item[2] === 'webinarignition_changelog' ) {
                $display_count = substr( (string) $notification_count, 0, 1 );
                // show only first digit
                // Append notification bubble to submenu title
                $submenu_item[0] .= sprintf( ' <span class="update-plugins count-%d"><span class="plugin-count">%s</span></span>', $display_count, $display_count );
                break;
            }
        }
    }
}

// KB for new integration
add_action( 'admin_head', 'webinarignition_modify_submenu_link' );
function webinarignition_modify_submenu_link() {
    ?>
		<script>
			document.addEventListener("DOMContentLoaded", function() {
				const menuItem = document.querySelector("a[href='admin.php?page=__integrated-meeting']");
				if (menuItem) {
					menuItem.setAttribute("target", "_blank"); // Open in new tab
					menuItem.setAttribute("href", "https://webinarignition.tawk.help/article/integrated-meeting-and-streaming-solution"); // Set external link
				}
				const menuupgrade = document.querySelector("a[href='admin.php?page=webinarignition-dashboard-pricing']");
				if (menuupgrade) {
					menuupgrade.setAttribute("href", "admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true"); // Set external link
				}
			});
		</script>
<?php 
}

function webinarignition_settings_submenu_page() {
    $tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS ) ) : '' );
    $active_tab = ( isset( $_GET['tab'] ) ? sanitize_text_field( filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_SPECIAL_CHARS ) ) : 'general' );
    if ( 'smtp-settings' === $active_tab ) {
        return webinarignition_display_smtp_settings_tab();
    }
    if ( 'spam-test' === $active_tab ) {
        return webinarignition_display_spam_test_tab();
    }
    if ( 'email-templates' === $active_tab ) {
        return webinarignition_display_email_templates_tab();
    }
    if ( !defined( 'WEBINAR_IGNITION_DISABLE_WEBHOOKS' ) || WEBINAR_IGNITION_DISABLE_WEBHOOKS === false ) {
        if ( 'webhooks' === $active_tab ) {
            return webinarignition_display_webhooks_tab();
        }
    }
    if ( 'general' === $active_tab ) {
        return webinarignition_display_general_settings_tab();
    }
}

/**
 * Table list output.
 */
function webinar_ignition_table_list_output() {
    $wiAdminWebhooksListTable = new WebinarIgnition_Admin_Webhooks_List_Table();
    $wiAdminWebhooksListTable->prepare_items();
    $wiAdminWebhooksListTable->display();
}

function webinar_ignition_table_list_form() {
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/webhooks_form.php';
}

function webinarignition_display_webhooks_tab() {
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/webhooks.php';
}

function webinarignition_display_smtp_settings_tab() {
    $protocols = array(
        'http://',
        'https://',
        'http://www.',
        'https://www.',
        'www.'
    );
    $site_domain = str_replace( $protocols, '', site_url() );
    if ( isset( $_POST['submit-webinarignition-settings'] ) && check_admin_referer( 'webinarignition-settings-submenu-save', 'webinarignition-settings-submenu-save-nonce' ) ) {
        $webinarignition_smtp_host = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_host' ) );
        $webinarignition_smtp_port = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_port' ) );
        $webinarignition_smtp_protocol = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_protocol' ) );
        $webinarignition_smtp_user = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_user' ) );
        $webinarignition_smtp_pass = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_pass' ) );
        $webinarignition_smtp_name = sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_smtp_name' ) );
        $webinarignition_smtp_name = ( empty( $webinarignition_smtp_name ) ? get_option( 'webinarignition_smtp_name', get_bloginfo( 'name' ) ) : $webinarignition_smtp_name );
        $webinarignition_smtp_email = sanitize_email( filter_input( INPUT_POST, 'webinarignition_smtp_email' ) );
        $webinarignition_smtp_email = ( empty( $webinarignition_smtp_email ) ? 'webinar@' . $site_domain : $webinarignition_smtp_email );
        $webinarignition_reply_to_email = sanitize_email( filter_input( INPUT_POST, 'webinarignition_reply_to_email' ) );
        $webinarignition_smtp_connect = absint( filter_input( INPUT_POST, 'webinarignition_smtp_connect', FILTER_SANITIZE_NUMBER_INT ) );
        $webinarignition_smtp_settings_global = absint( filter_input( INPUT_POST, 'webinarignition_smtp_settings_global' . FILTER_SANITIZE_NUMBER_INT ) );
        update_option( 'webinarignition_smtp_host', $webinarignition_smtp_host );
        update_option( 'webinarignition_smtp_port', $webinarignition_smtp_port );
        update_option( 'webinarignition_smtp_protocol', $webinarignition_smtp_protocol );
        update_option( 'webinarignition_smtp_user', $webinarignition_smtp_user );
        update_option( 'webinarignition_smtp_pass', $webinarignition_smtp_pass );
        update_option( 'webinarignition_smtp_name', $webinarignition_smtp_name );
        update_option( 'webinarignition_smtp_email', $webinarignition_smtp_email );
        update_option( 'webinarignition_reply_to_email', $webinarignition_reply_to_email );
        update_option( 'webinarignition_smtp_connect', $webinarignition_smtp_connect );
        update_option( 'webinarignition_smtp_settings_global', $webinarignition_smtp_settings_global );
        if ( !empty( $webinarignition_smtp_connect ) ) {
            $smtp_test_results_array = webinarignition_test_smtp_phpmailer(
                $webinarignition_smtp_host,
                $webinarignition_smtp_port,
                $webinarignition_smtp_user,
                $webinarignition_smtp_pass
            );
            if ( 0 === (int) $smtp_test_results_array['status'] || empty( $webinarignition_smtp_connect ) ) {
                update_option( 'webinarignition_smtp_connect', 0 );
            } else {
                update_option( 'webinarignition_smtp_connect', 1 );
            }
        }
    }
    //end if
    $webinarignition_smtp_host = get_option( 'webinarignition_smtp_host' );
    $webinarignition_smtp_port = get_option( 'webinarignition_smtp_port' );
    $webinarignition_smtp_protocol = get_option( 'webinarignition_smtp_protocol' );
    $webinarignition_smtp_user = get_option( 'webinarignition_smtp_user' );
    $webinarignition_smtp_pass = get_option( 'webinarignition_smtp_pass' );
    $webinarignition_smtp_name = get_option( 'webinarignition_smtp_name' );
    $webinarignition_smtp_name = ( empty( get_option( 'webinarignition_smtp_name' ) ) ? get_bloginfo( 'name' ) : $webinarignition_smtp_name );
    $webinarignition_smtp_email = get_option( 'webinarignition_smtp_email' );
    $webinarignition_smtp_email = ( empty( $webinarignition_smtp_email ) ? 'webinar@' . $site_domain : $webinarignition_smtp_email );
    $webinarignition_reply_to_email = get_option( 'webinarignition_reply_to_email', 'webinar@' . $site_domain );
    $webinarignition_smtp_connect = get_option( 'webinarignition_smtp_connect', 0 );
    $webinarignition_smtp_settings_global = get_option( 'webinarignition_smtp_settings_global', 0 );
    $is_from_email_disabled = ( !empty( $webinarignition_smtp_connect ) ? 'disabled' : '' );
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/smtp.php';
}

function webinarignition_display_spam_test_tab() {
    if ( isset( $_POST['webinarignition_spam_test_email'] ) ) {
        check_admin_referer( 'webinarignition-spam-test-save', 'webinarignition-spam-test-save-nonce' );
        $spam_test_email_address = sanitize_email( filter_input( INPUT_POST, 'webinarignition_spam_test_email', FILTER_SANITIZE_EMAIL ) );
        $email_data = new stdClass();
        $email_data->email_subject = __( 'WebinarIgnition Spammyness Test', 'webinar-ignition' );
        $email_data->emailheading = __( 'This Is The Message Heading', 'webinar-ignition' );
        $email_data->emailpreview = __( 'This is the preview text', 'webinar-ignition' );
        ob_start();
        include_once WEBINARIGNITION_PATH . 'templates/emails/html-email-template-preview.php';
        $email_data->bodyContent = ob_get_clean();
        $email = new WI_Emails();
        $email_data->bodyContent = $email->webinarignition_build_email( $email_data );
        $email_data->bodyContent = str_replace( '{YEAR}', gmdate( 'Y' ), $email_data->bodyContent );
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
        $emailSent = wp_mail(
            $spam_test_email_address,
            $email_data->email_subject,
            $email_data->bodyContent,
            $headers
        );
    }
    //end if
    $locale = substr( get_locale(), 0, 2 );
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/spam-test.php';
}

function webinarignition_display_email_templates_tab() {
    if ( isset( $_POST['submit-webinarignition-email-templ-settings'] ) && check_admin_referer( 'webinarignition-template-settings-save', 'webinarignition-template-settings-save-nonce' ) ) {
        update_option( 'webinarignition_show_email_header_img', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_show_email_header_img' ) ) );
        update_option( 'webinarignition_email_logo_url', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_logo_url' ) ) );
        update_option( 'header_img_algnmnt', sanitize_text_field( filter_input( INPUT_POST, 'header_img_algnmnt' ) ) );
        update_option( 'webinarignition_enable_header_img_max_width', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_enable_header_img_max_width' ) ) );
        update_option( 'webinarignition_email_logo_max_width', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_logo_max_width' ) ) );
        update_option( 'webinarignition_email_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_background_color' ) ) );
        update_option( 'webinarignition_email_body_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_body_background_color' ) ) );
        update_option( 'webinarignition_email_text_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_text_color' ) ) );
        update_option( 'webinarignition_body_text_line_height', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_body_text_line_height' ) ) );
        update_option( 'webinarignition_email_templates_from_name', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_templates_from_name' ) ) );
        update_option( 'webinarignition_email_templates_from_email', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_templates_from_email' ) ) );
        update_option( 'webinarignition_headings_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_headings_color' ) ) );
        update_option( 'webinarignition_headings_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_headings_color' ) ) );
        update_option( 'webinarignition_email_font_size', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_email_font_size' ) ) );
        update_option( 'webinarignition_heading_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_heading_background_color' ) ) );
        update_option( 'webinarignition_heading_text_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_heading_text_color' ) ) );
        update_option( 'webinarignition_email_signature', filter_input( INPUT_POST, 'webinarignition_email_signature' ) );
        $post_webinarignition_unsubscribe_links = absint( filter_input( INPUT_POST, 'webinarignition_unsubscribe_links' ) );
        update_option( 'webinarignition_unsubscribe_links', $post_webinarignition_unsubscribe_links );
    }
    //end if
    $default_webinarignition_email_logo_url = WEBINARIGNITION_URL . 'images/wi-email-design-logo.png';
    $webinarignition_show_email_header_img = get_option( 'webinarignition_show_email_header_img' );
    $webinarignition_email_logo_url = get_option( 'webinarignition_email_logo_url' );
    $header_img_algnmnt = get_option( 'header_img_algnmnt' );
    $webinarignition_enable_header_img_max_width = get_option( 'webinarignition_enable_header_img_max_width', 'yes' );
    $webinarignition_email_logo_max_width = get_option( 'webinarignition_email_logo_max_width', 265 );
    $webinarignition_emails_signatur = get_option( 'webinarignition_emails_signatur', '' );
    $webinarignition_email_background_color = get_option( 'webinarignition_email_background_color', '#ffffff' );
    $webinarignition_email_body_background_color = get_option( 'webinarignition_email_body_background_color', '#ededed' );
    $webinarignition_email_text_color = get_option( 'webinarignition_email_text_color', '#3f3f3f' );
    $webinarignition_email_font_size = get_option( 'webinarignition_email_font_size' );
    $webinarignition_body_text_line_height = get_option( 'webinarignition_body_text_line_height', 'normal' );
    $webinarignition_email_templates_from_name = get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) );
    $webinarignition_email_templates_from_email = get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) );
    $webinarignition_headings_color = get_option( 'webinarignition_headings_color', '#ffffff' );
    $webinarignition_heading_background_color = get_option( 'webinarignition_heading_background_color', '#000' );
    $webinarignition_heading_text_color = get_option( 'webinarignition_heading_text_color', '#fff' );
    $webinarignition_email_signature = get_option( 'webinarignition_email_signature', '' );
    $webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );
    $statusCheck = null;
    $wp_editor_settings = array(
        'wpautop'       => true,
        'textarea_name' => 'webinarignition_email_signature',
        'tinymce'       => array(
            'height' => '250',
        ),
    );
    wp_enqueue_script( 'wp-color-picker' );
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/email-templates.php';
}

function webinarignition_display_general_settings_tab() {
    if ( isset( $_POST['submit-webinarignition-general-settings'] ) && check_admin_referer( 'webinarignition-general-settings-save', 'webinarignition-general-settings-save-nonce' ) ) {
        update_option( 'webinarignition_show_footer_branding', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_show_footer_branding' ) ) );
        update_option( 'webinarignition_branding_copy', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_branding_copy' ) ) );
        update_option( 'webinarignition_affiliate_link', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_affiliate_link' ) ) );
        update_option( 'show_webinarignition_footer_logo', sanitize_text_field( filter_input( INPUT_POST, 'show_webinarignition_footer_logo' ) ) );
        update_option( 'webinarignition_branding_background_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_branding_background_color' ) ) );
        update_option( 'webinarignition_auto_clean_log_db', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_auto_clean_log_db' ) ) );
        $email_verification = ( isset( $_POST['webinarignition_email_verification'] ) ? sanitize_text_field( $_POST['webinarignition_email_verification'] ) : '' );
        $email_verification = ( $email_verification ? absint( $email_verification ) : 0 );
        update_option( 'webinarignition_email_verification', absint( $email_verification ) );
        $use_grid_custom_color = ( isset( $_POST['webinarignition_use_grid_custom_color'] ) ? sanitize_text_field( $_POST['webinarignition_use_grid_custom_color'] ) : '' );
        $use_grid_custom_color = ( $use_grid_custom_color ? absint( $use_grid_custom_color ) : 0 );
        update_option( 'webinarignition_use_grid_custom_color', absint( $use_grid_custom_color ) );
        update_option( 'webinarignition_email_verification_template', sanitize_textarea_field( filter_input( INPUT_POST, 'webinarignition_email_verification_template' ) ) );
        $post_webinarignition_registration_auto_login = sanitize_text_field( $_POST['webinarignition_registration_auto_login'] );
        $webinarignition_enable_honeypot_field = ( isset( $_POST['webinarignition_enable_honeypot_field'] ) ? sanitize_text_field( $_POST['webinarignition_enable_honeypot_field'] ) : 0 );
        $webinarignition_registration_shortcode = sanitize_text_field( $_POST['webinarignition_registration_shortcode'] );
        $post_webinarignition_registration_auto_login = ( $post_webinarignition_registration_auto_login ? absint( $post_webinarignition_registration_auto_login ) : 0 );
        update_option( 'webinarignition_registration_auto_login', $post_webinarignition_registration_auto_login );
        $webinarignition_enable_honeypot_field = ( $webinarignition_enable_honeypot_field ? absint( $webinarignition_enable_honeypot_field ) : 0 );
        update_option( 'webinarignition_enable_honeypot_field', $webinarignition_enable_honeypot_field );
        update_option( 'webinarignition_registration_shortcode', $webinarignition_registration_shortcode );
        $post_webinarignition_auto_login_password_email = absint( filter_input( INPUT_POST, 'webinarignition_auto_login_password_email' ) );
        if ( 0 === $post_webinarignition_registration_auto_login ) {
            $post_webinarignition_auto_login_password_email = 0;
        }
        update_option( 'webinarignition_auto_login_password_email', $post_webinarignition_auto_login_password_email );
        $post_webinarignition_hide_top_admin_bar = absint( filter_input( INPUT_POST, 'webinarignition_hide_top_admin_bar' ) );
        update_option( 'webinarignition_hide_top_admin_bar', $post_webinarignition_hide_top_admin_bar );
        $webinarignition_enable_third_party_server = ( isset( $_POST['webinarignition_enable_third_party_server'] ) ? absint( $_POST['webinarignition_enable_third_party_server'] ) : 0 );
        update_option( 'webinarignition_enable_third_party_server', $webinarignition_enable_third_party_server );
        update_option( 'webinarignition_footer_text', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_footer_text' ) ) );
        update_option( 'webinarignition_footer_text_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_footer_text_color' ) ) );
        update_option( 'webinarignition_brand_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_brand_color' ) ) );
        update_option( 'webinarignition_brand_contrast_color', sanitize_text_field( filter_input( INPUT_POST, 'webinarignition_brand_contrast_color' ) ) );
    }
    //end if
    $webinarignition_show_footer_branding = get_option( 'webinarignition_show_footer_branding' );
    $show_webinarignition_footer_logo = get_option( 'show_webinarignition_footer_logo' );
    $webinarignition_branding_copy = get_option( 'webinarignition_branding_copy' );
    $webinarignition_affiliate_link = get_option( 'webinarignition_affiliate_link' );
    $webinarignition_branding_background_color = get_option( 'webinarignition_branding_background_color', '#000' );
    $webinarignition_auto_clean_log_db = get_option( 'webinarignition_auto_clean_log_db', 'no' );
    $statusCheck = null;
    if ( class_exists( 'NextendSocialLogin' ) ) {
        $webinarignition_registration_shortcode = get_option( 'webinarignition_registration_shortcode', '[nextend_social_login]' );
    } else {
        $webinarignition_registration_shortcode = get_option( 'webinarignition_registration_shortcode', '' );
    }
    $webinarignition_registration_auto_login = absint( get_option( 'webinarignition_registration_auto_login', 1 ) );
    $webinarignition_enable_honeypot_field = absint( get_option( 'webinarignition_enable_honeypot_field', 1 ) );
    $webinarignition_email_verification = get_option( 'webinarignition_email_verification', 0 );
    $webinarignition_use_grid_custom_color = get_option( 'webinarignition_use_grid_custom_color', 0 );
    $webinarignition_email_verification_template = WebinarignitionManager::webinarignition_get_webinarignition_email_verification_template();
    $webinarignition_auto_login_password_email = absint( get_option( 'webinarignition_auto_login_password_email', 0 ) );
    $webinarignition_hide_top_admin_bar = absint( get_option( 'webinarignition_hide_top_admin_bar', 1 ) );
    $webinarignition_footer_text = get_option( 'webinarignition_footer_text', '' );
    $webinarignition_footer_text_color = get_option( 'webinarignition_footer_text_color', '#3f3f3f' );
    $webinarignition_brand_color = get_option( 'webinarignition_brand_color', '#3f3f3f' );
    $webinarignition_brand_contrast_color = get_option( 'webinarignition_brand_contrast_color', '#ffffff' );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    $webinars = $wpdb->get_results( "SELECT * FROM `{$table_db_name}`", ARRAY_A );
    if ( is_array( $webinars ) && !empty( $webinars ) ) {
        $all_webinars = array_reverse( $webinars );
        $latest_webinar_id = $all_webinars[0]['ID'];
        $latest_webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $latest_webinar_id );
        if ( $latest_webinar_data && !isset( $latest_webinar_data->webinar_permalink ) ) {
            $latest_webinar_data->webinar_permalink = WebinarignitionManager::webinarignition_get_permalink( $latest_webinar_data, 'webinar' );
        }
        $latest_webinar_permalink = $latest_webinar_data->webinar_permalink;
        $latest_webinar_permalink = add_query_arg( 'preview', 'true', $latest_webinar_permalink );
    }
    wp_enqueue_script( 'wp-color-picker' );
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/general.php';
}

function webinarignition_support_submenu_page() {
    $lang = get_locale();
    if ( strlen( $lang ) > 0 ) {
        $lang = explode( '_', $lang )[0];
    }
    $support_link = ( 'en' === $lang ? 'https://webinarignition.tawk.help/' : 'https://webinarignition.tawk.help/' . $lang );
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/support.php';
}

function webinarignition_changelog_submenu_page() {
    $changelog_link = get_admin_url() . 'plugin-install.php?tab=plugin-information&plugin=webinar-ignition&section=changelog';
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/changelog.php';
}

function webinarignition_grid_submenu_page() {
    include_once plugin_dir_path( __DIR__ ) . 'admin/views/tabs/grid.php';
}
