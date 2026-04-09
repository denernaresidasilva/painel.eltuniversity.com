<?php

/**
 * Responsible plugin admin assets.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( 'Webinar_Ignition_Admin_Scripts' ) ) {
    /**
     * Plugin common assets manager.
     *
     * @since 3.08.1
     */
    class Webinar_Ignition_Admin_Scripts extends Webinar_Ignition_Common_Scripts {
        private static $instance;

        public static function init() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            add_action( 'admin_enqueue_scripts', array(self::$instance, 'scripts') );
            add_filter( 'tiny_mce_before_init', array(self::$instance, 'tiny_mce_before_init') );
            return self::$instance;
        }

        /**
         * Pass dynamic dropdown items into TinyMCE init.
         */
        // 👇 your base items
        public function tiny_mce_before_init( $init ) {
            $pluginName = 'webinarignition';
            if ( function_exists( 'get_current_screen' ) ) {
                $screen = get_current_screen();
                if ( 'toplevel_page_webinarignition-dashboard' === $screen->id && !empty( $_GET['id'] ) ) {
                    $webinar_id = absint( sanitize_text_field( $_GET['id'] ) );
                    $webinar_record = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $webinar_id );
                    $webinar = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
                    $fields_array = array(
                        array(
                            'text'  => 'Webinar Link',
                            'value' => '{LINK}',
                        ),
                        array(
                            'text'  => 'Date',
                            'value' => '{DATE}',
                        ),
                        array(
                            'text'  => 'Title',
                            'value' => '{TITLE}',
                        ),
                        array(
                            'text'  => 'Host',
                            'value' => '{HOST}',
                        ),
                        array(
                            'text'  => 'Full Name',
                            'value' => '{FULLNAME}',
                        )
                    );
                    foreach ( $webinar->ar_fields_order as $_field ) {
                        switch ( $_field ) {
                            case 'ar_name':
                                $fields_array[] = array(
                                    'text'  => 'First Name',
                                    'value' => '{FIRSTNAME}',
                                );
                                break;
                            case 'ar_lname':
                                $fields_array[] = array(
                                    'text'  => 'Last Name',
                                    'value' => '{LASTNAME}',
                                );
                                break;
                            case 'ar_email':
                                $fields_array[] = array(
                                    'text'  => 'Email Adress',
                                    'value' => '{EMAIL}',
                                );
                                break;
                            case 'ar_phone':
                                $fields_array[] = array(
                                    'text'  => 'Phone No',
                                    'value' => '{PHONENUM}',
                                );
                                break;
                            case 'ar_salutation':
                                $fields_array[] = array(
                                    'text'  => 'Title/Salutation',
                                    'value' => '{SALUTATION}',
                                );
                                break;
                            case 'ar_reason':
                                $fields_array[] = array(
                                    'text'  => 'Participation Reason',
                                    'value' => '{REASON}',
                                );
                                break;
                            case 'ar_custom_1':
                            case 'ar_custom_2':
                            case 'ar_custom_3':
                            case 'ar_custom_4':
                            case 'ar_custom_5':
                            case 'ar_custom_6':
                            case 'ar_custom_7':
                            case 'ar_custom_15':
                            case 'ar_custom_16':
                            case 'ar_custom_17':
                            case 'ar_custom_18':
                                $index = str_replace( 'ar_custom_', '', $_field );
                                $fields_array[] = array(
                                    'text'  => 'Custom Field' . $index,
                                    'value' => '{Custom Field' . $index . '}',
                                );
                                break;
                            default:
                                break;
                        }
                    }
                }
                $items = $fields_array;
                // 🔹 Example: change items conditionally
                // if ( current_user_can('manage_options') ) {
                //     $items[] = array('text' => 'Admin Only Placeholder', 'value' => '{ADMIN}');
                // }
                // Pass items directly (WP outputs as JS array/object)
                $init['custom_dropdown_items'] = wp_json_encode( $items );
            }
            return $init;
        }

        public function scripts() {
            $pluginName = 'webinarignition';
            $screen = get_current_screen();
            // TODO: add limit screen asset loading.
            wp_enqueue_script( 'jquery-ui-tooltip' );
            wp_enqueue_script(
                'picker-js',
                WEBINARIGNITION_URL . 'inc/js/picker.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'picker-date-js',
                WEBINARIGNITION_URL . 'inc/js/picker.date.js',
                array('jquery', 'picker-js'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'picker-time-js',
                WEBINARIGNITION_URL . 'inc/js/picker.time.js',
                array('jquery', 'picker-js'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'jstz-js',
                WEBINARIGNITION_URL . 'inc/js/tz.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition-admin',
                WEBINARIGNITION_URL . 'assets/webinarignition-admin.js',
                array(
                    'jquery',
                    'jquery-ui-sortable',
                    'picker-date-js',
                    'picker-time-js',
                    'jstz-js'
                ),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_enqueue_script(
                'webinarignition-admin-two',
                WEBINARIGNITION_URL . 'inc/js/webinarignition-admin.js',
                array(
                    'jquery',
                    'jquery-ui-sortable',
                    'picker-date-js',
                    'picker-time-js',
                    'jstz-js'
                ),
                WEBINARIGNITION_VERSION,
                false
            );
            wp_enqueue_script(
                'webinarignition-admin-custom-js',
                WEBINARIGNITION_URL . 'inc/js/webinarignition-admin-custom.js',
                array('webinarignition-admin-two'),
                WEBINARIGNITION_VERSION . time(),
                false
            );
            $localizeable_data = array(
                'settings' => array(
                    'general_settings'  => array(
                        'affiliate_button_text'  => esc_attr__( 'Your affiliate link should be to freemius!', 'webinar-ignition' ),
                        'powered_by_text_alert'  => esc_attr__( 'Your branding copy should contain "Webinar Powered By WebinarIgnition"!', 'webinar-ignition' ),
                        'powered_by_button_text' => esc_attr__( 'Powered By WebinarIgnition', 'webinar-ignition' ),
                    ),
                    'email_settings'    => array(
                        'media_upload_title' => esc_attr__( 'Insert image', 'webinar-ignition' ),
                        'media_update_title' => esc_attr__( 'Use this image', 'webinar-ignition' ),
                    ),
                    'webhooks_settings' => array(
                        'confirm_delete' => esc_attr__( 'Are you sure you want to delete?', 'webinar-ignition' ),
                    ),
                ),
            );
            $localizeable_data['images'] = array(
                'ajax_loader' => WEBINARIGNITION_URL . 'images/ajax-loader.gif',
            );
            $localizeable_data['url'] = array(
                'dashboard'      => site_url() . "/wp-admin/?page={$pluginName}-dashboard&id=",
                'admin_page'     => site_url() . "/wp-admin/admin.php?page={$pluginName}-dashboard",
                'page_dashboard' => site_url() . "/wp-admin/admin.php?page={$pluginName}-dashboard&id=",
            );
            if ( 'toplevel_page_webinarignition-dashboard' === $screen->id && !empty( $_GET['id'] ) ) {
                $webinar_id = absint( sanitize_text_field( $_GET['id'] ) );
                $webinar_record = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $webinar_id );
                $webinar = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
                if ( !empty( $webinar->webinar_lang ) ) {
                    $applang = $webinar->webinar_lang;
                    switch_to_locale( $applang );
                    unload_textdomain( 'webinar-ignition' );
                    load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
                }
                $webinar_params = '?register-now&n=FIRSTNAME';
                foreach ( $webinar->ar_fields_order as $_field ) {
                    switch ( $_field ) {
                        case 'ar_lname':
                            $webinar_params .= '&ln=LASTNAME';
                            break;
                        case 'ar_email':
                            $webinar_params .= '&e=EMAIL';
                            break;
                        case 'ar_salutation':
                            $webinar_params .= '&sal=TITLE';
                            break;
                        case 'ar_reason':
                            $webinar_params .= '&r=REASON';
                            break;
                        default:
                            break;
                    }
                    // $field_labels[ $field_name ] = trim( wp_strip_all_tags( $label ) );
                }
                $evergreen_param = $webinar_params;
                $evergreen_param .= '&readonly=true';
                $webinar_params .= '&readonly=true&login=true';
                $privacy_policy_link = get_privacy_policy_url();
                wp_localize_script( 'webinarignition-admin', 'webinarignitionTranslations', array(
                    'wpMediaImgTitle'      => __( 'Insert image', 'webinar-ignition' ),
                    'wpMediaImgButtonText' => __( 'Use this image', 'webinar-ignition' ),
                    'wpMediaVidTitle'      => __( 'Insert video', 'webinar-ignition' ),
                    'wpMediaVidButtonText' => __( 'Use this video', 'webinar-ignition' ),
                    'someWrong'            => __( 'Something went wrong', 'webinar-ignition' ),
                    'monthsArray'          => WiDateHelpers::webinarignition_get_locale_months(),
                    'weekdaysFull'         => WiDateHelpers::webinarignition_get_locale_days(),
                    'weekdaysShort'        => WiDateHelpers::webinarignition_get_locale_weekday_abbrev(),
                    'today'                => __( 'Today', 'webinar-ignition' ),
                    'clear'                => __( 'clear', 'webinar-ignition' ),
                    'close'                => __( 'close', 'webinar-ignition' ),
                    'mailingList'          => __( 'Addition to mailing list requested', 'webinar-ignition' ),
                    'privacyPolicy'        => sprintf( 
                        /* translators: %s: Privacy Policy link */
                        __( '<a href="%s" target="_blank">Privacy Policy</a> read', 'webinar-ignition' ),
                        esc_url_raw( $privacy_policy_link )
                     ),
                    'termsAndConditions'   => __( '<a href="https://example.com" target="_blank">Terms and Conditions</a> accepted', 'webinar-ignition' ),
                    'ajax_nonce'           => wp_create_nonce( 'webinarignition_ajax_nonce' ),
                ) );
                wp_localize_script( 'webinarignition-admin', 'webinarignitiononeclickparama', $webinar_params );
                wp_localize_script( 'webinarignition-admin', 'webinarignitionoegparama', $evergreen_param );
                $lead_timezone = ( isset( $webinar->lead_timezone ) ? $webinar->lead_timezone : '' );
                $webinar_timezone = webinarignition_get_webinar_timezone( $webinar, null );
                $autoTZ_org = trim( $lead_timezone );
                if ( $autoTZ_org === '' ) {
                    $autoTZ_org = $webinar_timezone;
                    if ( $autoTZ_org === '' ) {
                        $autoTZ_org = wp_timezone_string();
                    }
                }
                $dtz = new DateTimeZone($autoTZ_org);
                $time_in_sofia = new DateTime('now', $dtz);
                $offset = $dtz->getOffset( $time_in_sofia ) / 3600;
                if ( !empty( $offset ) ) {
                    $localizeable_data['autoTZ'] = $offset;
                }
                if ( !empty( $webinar_record ) ) {
                    $localizeable_data['webinar_record'] = $webinar_record;
                }
                if ( !empty( $webinar ) ) {
                    $localizeable_data['webinar'] = $webinar;
                }
                if ( !empty( $webinar ) ) {
                    $settings_language = ( isset( $webinar->settings_language ) ? $webinar->settings_language : '' );
                    if ( !empty( $settings_language ) ) {
                        switch_to_locale( $settings_language );
                        unload_textdomain( 'webinar-ignition' );
                        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $settings_language . '.mo' );
                    }
                    $localizeable_data['webinar_date_js_format'] = webinarignition_convert_wp_to_js_date_format( $webinar->id );
                    $localizeable_data['translations']['monthsArray'] = WiDateHelpers::webinarignition_get_locale_months();
                    $localizeable_data['translations']['weekdaysFull'] = WiDateHelpers::webinarignition_get_locale_days();
                    $localizeable_data['translations']['weekdaysShort'] = WiDateHelpers::webinarignition_get_locale_weekday_abbrev();
                }
            }
            $localizeable_data['translations'] = array(
                'member_email'                    => esc_html__( 'Member Email', 'webinar-ignition' ),
                'member_email_help'               => esc_html__( 'This is the email address of the additional host member', 'webinar-ignition' ),
                'host_email'                      => esc_html__( 'host_member_email@example.com', 'webinar-ignition' ),
                'host_first_name'                 => esc_html__( 'Host Member First Name', 'webinar-ignition' ),
                'host_last_name'                  => esc_html__( 'Host Member Last Name', 'webinar-ignition' ),
                'member_first_name'               => esc_html__( 'John', 'webinar-ignition' ),
                'member_last_name'                => esc_html__( 'Doe', 'webinar-ignition' ),
                'delete_member'                   => esc_html__( 'Delete Member', 'webinar-ignition' ),
                'send_notifications'              => esc_html__( 'Send User Notification', 'webinar-ignition' ),
                'delete_additional_host'          => esc_html__( 'Delete Additional Host', 'webinar-ignition' ),
                'support_staff_email_label'       => esc_html__( 'Support Staff Email', 'webinar-ignition' ),
                'support_staff_last_name'         => esc_html__( 'Support Staff Last Name', 'webinar-ignition' ),
                'monthsArray'                     => WiDateHelpers::webinarignition_get_locale_months(),
                'weekdaysFull'                    => WiDateHelpers::webinarignition_get_locale_days(),
                'weekdaysShort'                   => WiDateHelpers::webinarignition_get_locale_weekday_abbrev(),
                'support_staff_email_placeholder' => esc_html__( 'This is the email address of the support staff', 'webinar-ignition' ),
                'member_email_placeholder'        => esc_html__( 'supportmember@example.com', 'webinar-ignition' ),
                'support_staff_first_name'        => esc_html__( 'Support Staff First Name', 'webinar-ignition' ),
                'provide_phone_number'            => esc_html__( 'Provide a phone number to send the SMS to.', 'webinar-ignition' ),
                'sms_sent'                        => esc_html__( 'SMS has been sent.', 'webinar-ignition' ),
                'saving'                          => esc_html__( 'Save', 'webinar-ignition' ),
                'ar_integration_test'             => esc_html__( 'AR Integration Test', 'webinar-ignition' ),
                'in_order_to_test_ar'             => esc_html__( 'In order to test your AR integration setup, these steps may help:', 'webinar-ignition' ),
                'click_the_button'                => __( 'Click the <strong>test button</strong> below.', 'webinar-ignition' ),
                'in_the_new_window'               => __( 'In the new window, fill in the registration form with dummy info for testing, then click <strong>register</strong>.', 'webinar-ignition' ),
                'if_all_went_well'                => esc_html__( 'If all went well, the data should be in your autoresponder list. Check your autoresponder list to confirm.', 'webinar-ignition' ),
                'test'                            => esc_html__( 'Test', 'webinar-ignition' ),
                'integration_tutorial'            => esc_html__( 'Integration Tutorials', 'webinar-ignition' ),
                'done'                            => esc_html__( 'Done', 'webinar-ignition' ),
                'delete_campaign_confirm'         => esc_html__( 'Are You Sure You Want To Delete This Campaign?', 'webinar-ignition' ),
                'delete_lead_confirm'             => esc_html__( 'Are You Sure You Want To Delete This Lead?', 'webinar-ignition' ),
                'reset_campaign_stats_confirm'    => esc_html__( 'Are You Sure You Want To Reset ALL The View Stats For This Campaign?', 'webinar-ignition' ),
                'changes_not_saved_warning'       => esc_html__( 'Your changes are not saved!', 'webinar-ignition' ),
                'save_and_update'                 => esc_html__( 'Save & Update', 'webinar-ignition' ),
                'save_past_message'               => esc_html__( 'Kindly select a future date instead of previous to save the webinar. Hint: If like to do an instant participant test, register for webinar from incognito window and set webinar status to live.', 'webinar-ignition' ),
                'save_past_message_new'           => esc_html__( 'Kindly select a future date instead of previous to save the webinar.', 'webinar-ignition' ),
                'search_leads_placeholder'        => esc_html__( 'Transfer Leads ...', 'webinar-ignition' ),
                'future_date_message_line_1'      => esc_html__( 'You have changed the webinar date & time,', 'webinar-ignition' ),
                'future_date_message_line_2'      => esc_html__( 'so the plugin will do the following', 'webinar-ignition' ),
                'future_date_message_line_3'      => esc_html__( 'Set the master status to "countdown"', 'webinar-ignition' ),
                'future_date_message_line_4'      => esc_html__( 'Set notifications for new registrations.', 'webinar-ignition' ),
                'future_date_message_line_5'      => esc_html__( 'Do you want to notify current attendees?', 'webinar-ignition' ),
                'future_date_message_line_6'      => esc_html__( '3 options: Just click >Save & Update< settings, that will update the webinar start and not change participants list. | No. (Will delete participants list! You can export before) | Yes. Start re-notification immediately with "Confirmation e-mail"', 'webinar-ignition' ),
                'future_date_NO_text'             => esc_html__( 'No', 'webinar-ignition' ),
                'future_date_Yes_text'            => esc_html__( 'Yes', 'webinar-ignition' ),
            );
            wp_localize_script( 'webinarignition-admin', 'WEBINARIGNITION', apply_filters( 'webinarignition_admin_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            if ( 'webinarignition_page_webinarignition_settings' === $screen->id ) {
                wp_enqueue_script(
                    'webinarignition-settings',
                    WEBINARIGNITION_URL . 'assets/webinarignition-settings.js',
                    array('jquery'),
                    WEBINARIGNITION_VERSION,
                    array(true)
                );
            }
            wp_enqueue_editor();
            if ( 'toplevel_page_webinarignition-dashboard' === $screen->id ) {
                wp_enqueue_script(
                    'webinarignition-webinar',
                    WEBINARIGNITION_URL . 'assets/webinarignition-webinar.js',
                    array('jquery'),
                    WEBINARIGNITION_VERSION,
                    array(
                        'in_footer' => true,
                    )
                );
                wp_enqueue_script(
                    'webinarignition-admin-dashboard',
                    WEBINARIGNITION_URL . 'assets/webinarignition-admin-dashboard.js',
                    array(
                        'jquery',
                        'picker-date-js',
                        'picker-time-js',
                        'jstz-js'
                    ),
                    WEBINARIGNITION_VERSION,
                    array(
                        'in_footer' => true,
                    )
                );
                wp_localize_script( 'webinarignition-webinar', 'WEBINARIGNITION', apply_filters( 'webinarignition_admin_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
                // Localize the script with new data. Should remove this code if not used anywhere.
                // wp_localize_script(
                // 	'webinarignition-admin-dashboard',
                // 	'webinarignition_ajax',
                // 	array(
                // 		'ajax_url' => admin_url( 'admin-ajax.php' ),
                // 		'nonce'    => wp_create_nonce( 'webinarignition_ajax_nonce' ),
                // 	)
                // );
            }
            wp_enqueue_script( 'webinarignition-admin' );
        }

    }

}
//end if