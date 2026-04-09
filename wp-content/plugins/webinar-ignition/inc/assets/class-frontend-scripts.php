<?php

/**
 * Responsible plugin frontend assets.
 *
 * @package    Webinar_Ignition
 * @subpackage Webinar_Ignition/inc
 * @since 2.9.1
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
if ( !class_exists( 'Webinar_Ignition_Frontend_Scripts' ) ) {
    /**
     * Plugin common assets manager.
     *
     * @since 3.08.1
     */
    class Webinar_Ignition_Frontend_Scripts extends Webinar_Ignition_Common_Scripts {
        private static $instance;

        public static function init() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            add_action( 'webinarignition_order_track_from_leads', array(self::$instance, 'webinarignition_load_order_track_from_leads_script') );
            add_action( 'wp_enqueue_scripts', array(self::$instance, 'scripts') );
            add_filter(
                'script_loader_tag',
                array(self::$instance, 'add_data_jetpack_boost_tag'),
                10,
                2
            );
            // add_filter('tiny_mce_before_init', array( self::$instance, 'tiny_mce_before_init_frontend' ) );
            return self::$instance;
        }

        function add_data_jetpack_boost_tag( $src, $handle ) {
            if ( $handle !== 'webinarignition-auto-register' ) {
                return $src;
            }
            return str_replace( ' src', ' data-jetpack-boost="ignore" src', $src );
        }

        public function scripts() {
            global $post;
            $webinar_page = get_query_var( 'webinarignition_page' );
            if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'wi_grid' ) ) {
                $this->webinarignition_register_grid_scripts();
            }
            if ( empty( $webinar_page ) && !webinarignition_is_webinar_common_page() ) {
                return;
            }
            do_action( 'webinarignition_before_frontend_scripts' );
            $this->webinarignition_register_frontend_scripts();
            $localizeable_data = array(
                'translations' => array(
                    'verify_email'                         => esc_html__( 'Please enter a valid email address', 'webinar-ignition' ),
                    'bot_submission'                       => esc_html__( 'This could be a bot registration, please open the link in the mail in a different browser.', 'webinar-ignition' ),
                    'answer_sent_successfully'             => esc_html__( 'Your answer was successfully sent', 'webinar-ignition' ),
                    'something_went_wrong'                 => esc_html__( 'Something went wrong', 'webinar-ignition' ),
                    'text_saving'                          => esc_html__( 'Save', 'webinar-ignition' ),
                    'save_broadcast'                       => esc_html__( 'Saved Broadcast Message Settings', 'webinar-ignition' ),
                    'save_on_air'                          => esc_html__( 'Save', 'webinar-ignition' ),
                    'adv_iframe_activate'                  => esc_html__( 'Please install and activate the Advance Iframe plugin', 'webinar-ignition' ),
                    'adv_iframe_activate_link'             => esc_html__( 'Click Here', 'webinar-ignition' ),
                    'tabName_required'                     => esc_html__( 'Tab Name is required', 'webinar-ignition' ),
                    'adv_iframe_on'                        => __( 'Please set the option Display CTA in iFrame to "on"', 'webinar-ignition' ),
                    'adv_ifram_plus_not_working_shortcode' => __( 'The Advanced iFrames shortcode and iFrames only work with the Ultimate Unlimited Plus licence. Please upgrade ', 'webinar-ignition' ),
                    'adv_ifram_plus_another_shortcode'     => __( "If Mix iframe non iframe Shortcode, it won't work properly and it's not recommended. If you have an [advanced_iframe...] shortcode and you want to add another shortcode like [hurrytimer...] countdown, add it on the other overlay side via a second CTA. (Soon will have unlimited CTAs).", 'webinar-ignition' ),
                    'empty_table'                          => esc_html__( 'No data available in table', 'webinar-ignition' ),
                    'info'                                 => esc_html__( 'Showing _START_ to _END_ of _TOTAL_ entries', 'webinar-ignition' ),
                    'info_empty'                           => esc_html__( 'Showing 0 to 0 of 0 entries', 'webinar-ignition' ),
                    'length_menu'                          => esc_html__( 'Show _MENU_ entries', 'webinar-ignition' ),
                    'loading_records'                      => esc_html__( 'Loading...', 'webinar-ignition' ),
                    'text_processing'                      => esc_html__( 'Processing...', 'webinar-ignition' ),
                    'search'                               => esc_html__( 'Search:', 'webinar-ignition' ),
                    'zero_records'                         => esc_html__( 'No matching records found', 'webinar-ignition' ),
                    'paginate_first'                       => esc_html__( 'First', 'webinar-ignition' ),
                    'paginate_last'                        => esc_html__( 'Last', 'webinar-ignition' ),
                    'paginate_next'                        => esc_html__( 'Next', 'webinar-ignition' ),
                    'paginate_previous'                    => esc_html__( 'Previous', 'webinar-ignition' ),
                    'search_your_leads'                    => esc_html__( 'Search Through Your Leads Here...', 'webinar-ignition' ),
                    'del_lead_confirmation'                => esc_html__( 'Are You Sure You Want To Delete This Lead?', 'webinar-ignition' ),
                    'paste_iframe_code'                    => esc_html__( 'Paste This iFrame Code On Your Download Page:', 'webinar-ignition' ),
                ),
                'assets'       => array(
                    'watermark' => esc_url( WEBINARIGNITION_URL . 'images/watermark-webinar.png' ),
                ),
                'ip'           => esc_html( sanitize_text_field( $_SERVER['REMOTE_ADDR'] ) ),
                'current_time' => current_time( 'mysql' ),
            );
            $show_advance_iframe_message = true;
            $localizeable_data['show_advance_iframe_message'] = $show_advance_iframe_message;
            $webinar_id = absint( get_query_var( 'webinar_id' ) );
            $webinar = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
            $applang = $webinar->webinar_lang;
            foreach ( $webinar->ar_fields_order as $_field ) {
                switch ( $_field ) {
                    case 'ar_name':
                        $field_name = 'optName';
                        $label = $webinar_data->lp_optin_name ?? WebinarignitionManager::webinarignition_ar_field_translated_name( 'optName', $applang );
                        break;
                    case 'ar_lname':
                        $field_name = 'optLName';
                        $label = WebinarignitionManager::webinarignition_ar_field_translated_name( 'optLName', $applang );
                        break;
                    case 'ar_email':
                        $field_name = 'optEmail';
                        $label = $webinar_data->lp_optin_email ?? WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang );
                        break;
                    case 'ar_phone':
                        $field_name = 'optPhone';
                        $label = WebinarignitionManager::webinarignition_ar_field_translated_name( 'optPhone', $applang );
                        break;
                    case 'ar_salutation':
                        $field_name = 'optSalutation';
                        $label = 'Salutation';
                        break;
                    case 'ar_reason':
                        $field_name = 'optReason';
                        $label = 'Reason';
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
                        $field_name = 'optCustom_' . $index;
                        $label_property = 'lp_optin_custom_' . $index;
                        $label = $webinar_data->{$label_property} ?? 'Custom Field ' . $index;
                        break;
                    case 'ar_privacy_policy':
                        $field_name = 'optGDPR_PP';
                        $label = ( !empty( $webinar_data->lp_optin_privacy_policy ) ? $webinar_data->lp_optin_privacy_policy : __( 'Have read and understood our Privacy Policy', 'webinar-ignition' ) );
                        break;
                    case 'ar_terms_and_conditions':
                        $field_name = 'optGDPR_TC';
                        $label = ( !empty( $webinar_data->lp_optin_terms_and_conditions ) ? $webinar_data->lp_optin_terms_and_conditions : __( 'Accept our Terms & Conditions', 'webinar-ignition' ) );
                        break;
                    case 'ar_mailing_list':
                        $field_name = 'optGDPR_ML';
                        $label = ( !empty( $webinar_data->lp_optin_mailing_list ) ? $webinar_data->lp_optin_mailing_list : __( 'Want to be added to our mailing list', 'webinar-ignition' ) );
                        break;
                    default:
                        break;
                }
                $field_labels[$field_name] = trim( wp_strip_all_tags( $label ) );
            }
            if ( !isset( $field_labels['webinar_timezone'] ) ) {
                $field_labels['webinar_timezone'] = 'webinar_timezone';
            }
            if ( !isset( $field_labels['full_name'] ) ) {
                $field_labels['full_name'] = 'full_name';
            }
            unset($field_labels['full_name']);
            $reordered_fields = [];
            // Append any remaining fields
            $field_labels = array_merge( $reordered_fields, $field_labels );
            $show_label = array(
                'optEmail',
                'optName',
                'optLName',
                'optPhone'
            );
            $export_labels = array();
            foreach ( $field_labels as $field_name => $label ) {
                if ( in_array( $field_name, $show_label ) ) {
                    $export_labels[] = $label;
                } else {
                    $export_labels[] = $field_name;
                }
            }
            $localizeable_data['webinar_map_labels'] = $export_labels;
            if ( $webinar ) {
                $webinar_record = WebinarignitionManager::webinarignition_get_webinar_record_by_id( $webinar_id );
                $localizeable_data['webinar_record'] = $webinar_record;
                $localizeable_data['webinar'] = $webinar;
                $localizeable_data['webinar_type'] = ( webinarignition_is_auto( $webinar ) ? 'evergreen' : 'live' );
                $localizeable_data['lid'] = webinarignition_getLid( $webinar_id );
                // Leads to order id.
                $order_id = WebinarignitionManager::webinarignition_is_paid_webinar( $webinar ) && WebinarignitionManager::webinarignition_get_paid_webinar_type( $webinar ) === 'woocommerce' && WebinarignitionManager::webinarignition_url_has_valid_wc_order_id();
                $is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
                $localizeable_data['order_id'] = $order_id;
                // Webinar user role.
                if ( is_user_logged_in() ) {
                    $webinar_user_role_manager = new Webinar_User_Role_Manager($webinar);
                    $localizeable_data['webinar_user_roles'] = array(
                        'is_support' => $webinar_user_role_manager->webinarignition_is_support(),
                        'is_host'    => $webinar_user_role_manager->webinarignition_is_host(),
                        'is_admin'   => $webinar_user_role_manager->webinarignition_is_admin(),
                    );
                    $webinar_page = get_query_var( 'webinarignition_page' );
                    if ( !empty( $webinar_page ) ) {
                        $localizeable_data['webinar_page'] = $webinar_page;
                    }
                }
            }
            wp_enqueue_style(
                'wi-notice-assets',
                WEBINARIGNITION_URL . 'css/notices-style.css',
                false,
                WEBINARIGNITION_VERSION,
                'all'
            );
            if ( isset( $webinar->webinar_ld_share ) && 'on' === $webinar->webinar_ld_share ) {
                wp_enqueue_script(
                    'linkedin-platform',
                    '//platform.linkedin.com/in.js',
                    array(),
                    WEBINARIGNITION_VERSION,
                    false
                );
            }
            // if ( 'disabled' !== $webinar->live_stats ) {
            // 	wp_enqueue_script(
            // 		'webinarignition-live-counter',
            // 		WEBINARIGNITION_URL . 'inc/lp/livecounter.php',
            // 		array( 'jquery' ),
            // 		WEBINARIGNITION_VERSION
            // 	);
            // }
            if ( $webinar && property_exists( $webinar, 'webinar_date' ) && 'AUTO' !== $webinar->webinar_date && isset( $webinar->paid_status ) && 'paid' === $webinar->paid_status ) {
                $localizeable_data['paid_code'] = array(
                    'code' => $webinar->paid_code,
                );
            }
            $lead_id = ( !empty( $_GET['lid'] ) ? sanitize_text_field( $_GET['lid'] ) : '' );
            if ( !empty( $lead_id ) ) {
                $lead_info = webinarignition_get_lead_info( $lead_id, $webinar, false );
                $localizeable_data['lead_id'] = $lead_id;
                $localizeable_data['lead_info'] = $lead_info;
            }
            $is_auto_login_enabled = get_option( 'webinarignition_registration_auto_login', 1 ) == 1;
            $is_user_registered = $webinar && Webinar_Ignition_Helper::webinarignition_is_user_registered_on_this_webinar( $webinar->id, $lead_id ) || is_user_logged_in();
            $is_webinar_page = get_query_var( 'webinarignition_page' ) === 'webinar';
            $is_alt_webinar_page = isset( $webinarignition_page ) && 'webinar' === $webinarignition_page;
            $localizeable_data['is_auto_login_enabled'] = $is_auto_login_enabled;
            $localizeable_data['is_user_registered'] = $is_user_registered;
            $localizeable_data['is_webinar_page'] = $is_webinar_page;
            $localizeable_data['is_alt_webinar_page'] = $is_alt_webinar_page;
            $frontend_dependencies = ( wp_script_is( 'linkedin-platform', 'enqueued' ) ? array('jquery', 'linkedin-platform') : array('jquery', 'wp-editor') );
            global $pagenow;
            $screen = $pagenow;
            wp_enqueue_editor();
            if ( isset( $_GET['console'] ) ) {
                wp_enqueue_script(
                    'webinarignition-admin-dashboard',
                    WEBINARIGNITION_URL . 'assets/webinarignition-admin-dashboard.js',
                    array('jquery', 'webinarignition_tz_js'),
                    WEBINARIGNITION_VERSION,
                    array(
                        'in_footer' => true,
                    )
                );
            }
            wp_register_script(
                'webinarignition-frontend',
                WEBINARIGNITION_URL . 'assets/webinarignition-frontend.js',
                $frontend_dependencies,
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_media();
            wp_register_script(
                'webinarignition-live-cta-manager',
                WEBINARIGNITION_URL . 'assets/webinarignition-live-cta-manager.js',
                ['jquery', 'wp-editor', 'wp-tinymce'],
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition-webinar-inline',
                WEBINARIGNITION_URL . 'assets/webinarignition-webinar-inline.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_script(
                'webinarignition-webinar-cta-frontend',
                WEBINARIGNITION_URL . 'assets/webinarignition-cta-live-frontend.js',
                array('jquery', 'socket-io'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_localize_script( 'webinarignition-frontend', 'WEBINARIGNITION', apply_filters( 'webinarignition_frontend_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            $localizeable_data['can_use_multi_cta'] = 0;
            // Add custom dropdown items for live CTA manager
            $custom_dropdown_items = array();
            // Check if we're in console mode and have webinar data
            if ( isset( $_GET['console'] ) ) {
                error_log( 'in console - live CTA manager' );
                $webinar_id = absint( get_query_var( 'webinar_id' ) );
                $webinar = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
                if ( $webinar && isset( $webinar->ar_fields_order ) ) {
                    $custom_dropdown_items = array(
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
                                $custom_dropdown_items[] = array(
                                    'text'  => 'First Name',
                                    'value' => '{FIRSTNAME}',
                                );
                                break;
                            case 'ar_lname':
                                $custom_dropdown_items[] = array(
                                    'text'  => 'Last Name',
                                    'value' => '{LASTNAME}',
                                );
                                break;
                            case 'ar_email':
                                $custom_dropdown_items[] = array(
                                    'text'  => 'Email Address',
                                    'value' => '{EMAIL}',
                                );
                                break;
                            case 'ar_phone':
                                $custom_dropdown_items[] = array(
                                    'text'  => 'Phone No',
                                    'value' => '{PHONENUM}',
                                );
                                break;
                            case 'ar_salutation':
                                $custom_dropdown_items[] = array(
                                    'text'  => 'Title/Salutation',
                                    'value' => '{SALUTATION}',
                                );
                                break;
                            case 'ar_reason':
                                $custom_dropdown_items[] = array(
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
                                $custom_dropdown_items[] = array(
                                    'text'  => 'Custom Field' . $index,
                                    'value' => '{CUSTOM' . $index . '}',
                                );
                                break;
                            default:
                                break;
                        }
                    }
                }
            }
            // Fallback to default items if no webinar-specific data
            if ( empty( $custom_dropdown_items ) ) {
                $custom_dropdown_items = array(
                    array(
                        'text'  => 'Email Address',
                        'value' => '{EMAIL}',
                    ),
                    array(
                        'text'  => 'Participation Reason',
                        'value' => '{REASON}',
                    ),
                    array(
                        'text'  => 'Title/Salutation',
                        'value' => '{SALUTATION}',
                    ),
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
                    ),
                    array(
                        'text'  => 'First Name',
                        'value' => '{FIRSTNAME}',
                    ),
                    array(
                        'text'  => 'Last Name',
                        'value' => '{LASTNAME}',
                    ),
                    array(
                        'text'  => 'Phone No',
                        'value' => '{PHONENUM}',
                    )
                );
                // Add custom fields
                for ($i = 1; $i <= 18; $i++) {
                    $custom_dropdown_items[] = array(
                        'text'  => 'Custom Field' . $i,
                        'value' => '{CUSTOM' . $i . '}',
                    );
                }
            }
            $localizeable_data['custom_dropdown_items'] = $custom_dropdown_items;
            wp_localize_script( 'webinarignition-live-cta-manager', 'WEBINARIGNITION', apply_filters( 'webinarignition_frontend_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            wp_localize_script( 'webinarignition_registration_js', 'WEBINARIGNITION', apply_filters( 'webinarignition_frontend_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            wp_localize_script( 'webinarignition-webinar-inline', 'WEBINARIGNITION', apply_filters( 'webinarignition_frontend_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            wp_localize_script( 'webinarignition-webinar-cta-frontend', 'WEBINARIGNITION', apply_filters( 'webinarignition_frontend_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            global $post;
            if ( 'auto_register' === get_query_var( 'webinarignition_page' ) || webinarignition_is_webinar_common_page() ) {
                wp_enqueue_script(
                    'webinarignition-auto-register',
                    WEBINARIGNITION_URL . 'assets/webinarignition-auto-register.js',
                    array('jquery'),
                    WEBINARIGNITION_VERSION,
                    true
                );
                wp_localize_script( 'webinarignition-auto-register', 'WEBINARIGNITION', apply_filters( 'webinarignition_auto_reg_localizeable_scripts', array_merge( $this->webinarignition_get_default_localizeable(), $localizeable_data ) ) );
            }
            wp_enqueue_script( 'webinarignition-frontend' );
            wp_enqueue_script( 'webinarignition-live-cta-manager' );
            if ( (isset( $_GET['webinar'] ) && isset( $_GET['lid'] ) || isset( $_GET['live'] ) && isset( $_GET['lid'] )) && $is_webinar_page ) {
                wp_enqueue_script( 'webinarignition-webinar-inline' );
                wp_enqueue_script(
                    'socket-io',
                    WEBINARIGNITION_URL . 'assets/socket.io.min.js',
                    array(),
                    null,
                    true
                );
                wp_enqueue_script( 'webinarignition-webinar-cta-frontend' );
            }
            $registration_scripts = false;
            if ( $registration_scripts ) {
                wp_enqueue_script( 'registration-scripts' );
            }
            $this->webinarignition_load_webinar_page_scripts();
            do_action( 'webinarignition_after_frontend_scripts' );
        }

        public function webinarignition_load_registration_page_scripts( $webinar_data, $template_number, $dynamically_generated_css ) {
            // <head> css
            wp_enqueue_style( 'webinarignition_bootstrap' );
            wp_enqueue_style( 'webinarignition_foundation' );
            wp_enqueue_style( 'webinarignition_intlTelInput' );
            wp_enqueue_style( 'webinarignition_main' );
            wp_enqueue_style( 'webinarignition_font-awesome' );
            wp_enqueue_style( 'webinarignition_css_utils' );
            wp_enqueue_style( 'webinarignition_ss' );
            if ( '02' === $template_number || '03' === $template_number ) {
                wp_enqueue_style( 'webinarignition_ss' );
            }
            if ( (empty( $webinar_data->lp_cta_type ) || 'video' === $webinar_data->lp_cta_type) && !empty( $webinar_data->lp_cta_video_url ) ) {
                wp_enqueue_style( 'webinarignition_video_css' );
            }
            // <head> js
            wp_enqueue_script( 'moment' );
            wp_enqueue_script( 'webinarignition_js_utils' );
            wp_enqueue_script( 'webinarignition_cookie_js' );
            // footer scripts
            if ( (empty( $webinar_data->lp_cta_type ) || $webinar_data->lp_cta_type == 'video') && !empty( $webinar_data->lp_cta_video_url ) ) {
                wp_enqueue_script( 'webinarignition_video_js' );
            }
            wp_enqueue_script( 'webinarignition_intlTelInput_js' );
            wp_enqueue_script( 'webinarignition_frontend_js' );
            wp_enqueue_script( 'webinarignition_tz_js' );
            wp_enqueue_script( 'webinarignition_luxon_js' );
            if ( !empty( $dynamically_generated_css ) ) {
                wp_add_inline_style( 'webinarignition_main', esc_html( $dynamically_generated_css ) );
            }
            if ( !empty( $webinar_data->custom_lp_css ) ) {
                wp_add_inline_style( 'webinarignition_main', esc_html( $webinar_data->custom_lp_css ) );
            }
            if ( !empty( $webinar_data->custom_lp_js ) ) {
                wp_add_inline_script( 'wi_js_utils', $webinar_data->custom_lp_js );
            }
            if ( !empty( $webinar_data->stripe_publishable_key ) ) {
                wp_enqueue_script( 'webinarignition_stripe_js' );
                $setPublishableKey = 'Stripe.setPublishableKey("' . $webinar_data->stripe_publishable_key . '")';
                wp_add_inline_script( 'webinarignition_stripe_js', $setPublishableKey );
            }
            if ( !empty( $webinar_data->paid_status ) && $webinar_data->paid_status == 'paid' ) {
                $paid_js_code = "var paid_code = {code: {$webinar_data->paid_code}}";
                wp_add_inline_script( 'wi_js_utils', $paid_js_code );
            }
            $wi_parsed = webinarignition_parse_registration_page_data( get_query_var( 'webinar_id' ), $webinar_data );
            $isSigningUpWithFB = false;
            $fbUserData = array();
            if ( !empty( $webinar_data->fb_id ) && !empty( $webinar_data->fb_secret ) && isset( $_GET['code'] ) ) {
                include 'lp/fbaccess.php';
                /**
                 * @var $user_info
                 */
                $isSigningUpWithFB = true;
                $fbUserData['name'] = $user_info['name'];
                $fbUserData['email'] = $user_info['email'];
            }
            $wi_parsed['isSigningUpWithFB'] = $isSigningUpWithFB;
            $wi_parsed['fbUserData'] = $fbUserData;
            $window_webinarignition = 'window.webinarignition = ' . wp_json_encode( $wi_parsed, JSON_HEX_APOS ) . ';';
            wp_enqueue_script( 'webinarignition_registration_js' );
            wp_add_inline_script( 'webinarignition_registration_js', $window_webinarignition, 'before' );
        }

        public function webinarignition_load_webinar_closed_page_header_scripts( $webinar_data ) {
            wp_register_script(
                'webinarignition-webinar-closed',
                WEBINARIGNITION_URL . 'assets/webinarignition-webinar-closed.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_register_style(
                'webinarignition-webinar-closed',
                WEBINARIGNITION_URL . 'assets/webinarignition-webinar-closed.css',
                '',
                WEBINARIGNITION_VERSION,
                'all'
            );
            wp_enqueue_script( 'webinarignition-webinar-closed' );
        }

        public function webinarignition_load_thank_you_page_inline_scripts() {
            wp_enqueue_script(
                'webinarignition-thank-you-page',
                WEBINARIGNITION_URL . 'assets/webinarignition-thank-you-page.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
        }

        public function webinarignition_load_order_track_from_leads_script() {
            $webinar_id = absint( get_query_var( 'webinar_id' ) );
            $webinar_id = ( !empty( $_GET['trkorder'] ) ? trim( $_GET['trkorder'] ) : $webinar_id );
            if ( !$webinar_id ) {
                return;
            }
            wp_enqueue_script(
                'webinarignition-order-track',
                WEBINARIGNITION_URL . 'assets/webinarignition-order-track.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
        }

        public function webinarignition_load_thankyou_cp_preview_scripts( $webinar ) {
            if ( !empty( $webinar->custom_ty_js ) ) {
                wp_add_inline_script( 'webinarignition-frontend', $webinar->custom_ty_js );
            }
            if ( !empty( $webinar->custom_ty_css ) ) {
                wp_add_inline_style( 'webinarignition-frontend', $webinar->custom_ty_css );
            }
        }

        public function webinarignition_load_webinar_page_scripts() {
            $webinar_page = get_query_var( 'webinarignition_page' );
            add_action( 'webinarignition_webinar_closed_before_head', array($this, 'webinarignition_load_webinar_closed_page_header_scripts') );
            add_action( 'webinarignition_thankyou_cp_page_header', array($this, 'webinarignition_load_thankyou_cp_preview_scripts') );
            switch ( $webinar_page ) {
                case 'registration':
                    add_action(
                        'webinarignition/webinar_page_header',
                        array($this, 'webinarignition_load_registration_page_scripts'),
                        10,
                        3
                    );
                    break;
                case 'auto_register':
                    break;
                case 'preview_auto_thankyou':
                    $this->webinarignition_load_thank_you_page_inline_scripts();
                    break;
                default:
            }
        }

    }

}
//end if