<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
if ( !function_exists( 'webinarignition_disable_admin_bar' ) ) {
    add_action( 'template_redirect', 'webinarignition_disable_admin_bar' );
    function webinarignition_disable_admin_bar() {
        // Check if the user is logged in and does not have 'manage_options' (non-admin users)
        if ( is_user_logged_in() && !current_user_can( 'manage_options' ) ) {
            // Fetch the option for hiding the admin bar
            $hide_admin_bar = absint( get_option( 'webinarignition_hide_top_admin_bar', 1 ) );
            // If the option is set to 1, hide the admin bar
            if ( $hide_admin_bar === 1 ) {
                add_filter( 'show_admin_bar', '__return_false' );
            }
        }
    }

}
if ( !function_exists( 'webinarignition_get_shortcode_attributes' ) ) {
    function webinarignition_get_shortcode_attributes(  $shortcode_tag  ) {
        global $post;
        if ( has_shortcode( $post->post_content, $shortcode_tag ) ) {
            $output = array();
            // get shortcode regex pattern WordPress function
            $pattern = get_shortcode_regex( array($shortcode_tag) );
            if ( preg_match_all( '/' . $pattern . '/s', $post->post_content, $matches ) ) {
                $keys = array();
                $output = array();
                foreach ( $matches[0] as $key => $value ) {
                    // $matches[3] return the shortcode attribute as string
                    // replace space with '&' for parse_str() function
                    $get = str_replace( ' ', '&', trim( $matches[3][$key] ) );
                    $get = str_replace( '"', '', $get );
                    parse_str( $get, $sub_output );
                    // get all shortcode attribute keys
                    $keys = array_unique( array_merge( $keys, array_keys( $sub_output ) ) );
                    $output[] = $sub_output;
                }
                if ( $keys && $output ) {
                    // Loop the output array and add the missing shortcode attribute key
                    foreach ( $output as $key => $value ) {
                        // Loop the shortcode attribute key
                        foreach ( $keys as $attr_key ) {
                            $output[$key][$attr_key] = ( isset( $output[$key] ) && isset( $output[$key][$attr_key] ) ? $output[$key][$attr_key] : null );
                        }
                        // sort the array key
                        ksort( $output[$key] );
                    }
                }
            }
            //end if
            return $output;
        } else {
            return false;
        }
        //end if
    }

}
//end if
if ( !function_exists( 'webinarignition_post_has_webinar_id' ) ) {
    /**
     * Check if current $post has webinar_id in meta, or in shortcodes used in it.
     *
     * @return int|void
     */
    function webinarignition_post_has_webinar_id() {
        $webinar_id = 0;
        if ( is_singular( 'page' ) ) {
            global $post;
            $webinar_id = absint( get_post_meta( $post->ID, 'webinarignitionx_meta_box_select', true ) );
            // Check if webinar page
            if ( empty( $webinar_id ) ) {
                // Check if custom page has WI registration shortcodes
                $page_shortcodes = webinarignition_get_shortcode_attributes( 'wi_webinar_block' );
                if ( !empty( $page_shortcodes ) && is_array( $page_shortcodes ) ) {
                    foreach ( $page_shortcodes as $page_shortcode ) {
                        if ( isset( $page_shortcode['id'] ) && !empty( $page_shortcode['id'] ) ) {
                            $webinar_id = absint( $page_shortcode['id'] );
                            if ( !empty( $webinar_id ) ) {
                                break;
                            }
                        }
                    }
                }
            }
        }
        //end if
        return $webinar_id;
    }

}
//end if
if ( !function_exists( 'webinarignition_template_redirect' ) ) {
    function webinarignition_template_redirect() {
        $webinar_id = webinarignition_post_has_webinar_id();
        if ( !empty( $webinar_id ) ) {
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
            if ( !empty( $webinar_data ) ) {
                set_query_var( 'wi_webinar_id', $webinar_id );
                do_action( 'webinarignition_template_redirect', $webinar_data );
            }
        }
    }

    add_action( 'template_redirect', 'webinarignition_template_redirect' );
}
// Check if URL has any WI dashboard parameters, and current user has access
if ( !function_exists( 'webinarignition_is_dashboard_url' ) ) {
    function webinarignition_is_dashboard_url() {
        $has_dashboard_url_params = false;
        $dashboard_get_params = array(
            'console',
            'csv_key',
            'trkorder',
            'register-now'
        );
        foreach ( $dashboard_get_params as $dashboard_get_param ) {
            if ( isset( $_GET[$dashboard_get_param] ) ) {
                $has_dashboard_url_params = true;
                break;
            }
        }
        // If parameters found, then check if user has access
        if ( $has_dashboard_url_params ) {
            $has_dashboard_url_params = current_user_can( 'edit_posts' );
        }
        return $has_dashboard_url_params;
    }

}
//end if
if ( !function_exists( 'webinarignition_wp' ) ) {
    function webinarignition_wp() {
        $webinar_id = webinarignition_post_has_webinar_id();
        if ( !empty( $webinar_id ) ) {
            global $post;
            $is_confirmed_set = WebinarignitionManager::webinarignition_url_is_confirmed_set();
            $lead_id = WebinarignitionManager::webinarignition_url_has_valid_lead_id();
            $is_preview_page = WebinarignitionManager::webinarignition_url_is_preview_page();
            $is_calendar_page = WebinarignitionManager::webinarignition_url_is_calendar_page();
            $is_auto_login_enabled = wp_validate_boolean( absint( get_option( 'webinarignition_registration_auto_login', 1 ) ) );
            $do_auto_login = apply_filters( 'webinarignition_do_auto_login', $is_auto_login_enabled, $webinar_id );
            if ( $do_auto_login && $lead_id && !$is_preview_page ) {
                do_action( 'webinarignition_auto_login', $webinar_id, $lead_id );
            }
        }
    }

    add_action( 'wp', 'webinarignition_wp' );
}
//end if
if ( !function_exists( 'webinarignition_after_user_auto_log_in_cb' ) ) {
    function webinarignition_after_user_auto_log_in_cb(  $user_id, $webinar_id, $lead_id  ) {
        if ( is_single() ) {
            $redirect_params = array(
                'live'       => '',
                'lid'        => $lead_id,
                'watch_type' => 'live',
            );
            $watch_type = sanitize_text_field( filter_input( INPUT_GET, 'watch_type', FILTER_SANITIZE_SPECIAL_CHARS ) );
            if ( !empty( $watch_type ) ) {
                $redirect_params['watch_type'] = $watch_type;
            }
            wp_safe_redirect( add_query_arg( $redirect_params, get_the_permalink() ) );
            exit;
        }
    }

    // add_action('webinarignition_after_user_auto_log_in', 'webinarignition_after_user_auto_log_in_cb', 10, 3);
}
//end if
if ( !function_exists( 'webinarignition_template_redirect_cb' ) ) {
    function webinarignition_template_redirect_cb(  $webinar_data  ) {
        global $post;
        $live = sanitize_text_field( filter_input( INPUT_GET, 'live' ) );
        $googlecalendarA = sanitize_text_field( filter_input( INPUT_GET, 'googlecalendarA' ) );
        $confirmed = sanitize_text_field( filter_input( INPUT_GET, 'confirmed' ) );
        /**
         * @var $webinar_id
         * @var $webinarId
         * @var $is_preview
         * @var $leadId
         * @var $lead
         * @var $leadinfo
         * @var $data
         * @var $isAuto
         * @var $pluginName
         * @var $leadinfo
         * @var $assets
         * @var $webinar_status string ( live | countdown | replay | closed )
         */
        extract( (array) webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        // Check if current user has editor access for this particular post/page
        $has_editor_access = current_user_can( 'edit_published_pages' );
        if ( $has_editor_access && empty( $leadId ) ) {
            return;
            // bail here
        }
        $webinar_post_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_id );
        $timeover = false;
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
        $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'registration' );
        // Registration page
        if ( isset( $_GET['preview'] ) && $_GET['preview'] == 'true' && !current_user_can( 'administrator' ) || !array_key_exists( 'preview', $_GET ) && $leadId != '' && !isset( $lead ) ) {
            $default = ( isset( $webinar_data->default_registration_page ) ? $webinar_data->default_registration_page : 0 );
            if ( !empty( $default ) ) {
                if ( !in_array( $default, $page_id, true ) ) {
                    $page_id = reset( $page_id );
                } else {
                    $page_id = $default;
                }
            } else {
                $page_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_data->id );
            }
            $permalink = get_permalink( $page_id );
            wp_safe_redirect( $permalink );
            exit;
        }
        if ( $leadId && isset( $webinar_status ) ) {
            if ( $timeover ) {
                $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'closed' );
            } elseif ( 'countdown' === $webinar_status && $live ) {
                // Countdown page
                $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'countdown' );
            } elseif ( 'countdown' === $webinar_status ) {
                // Thankyou page
                $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'thank_you' );
            } elseif ( 'closed' === $webinar_status ) {
                // Closed page
                $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'closed' );
            } elseif ( 'replay' === $webinar_status ) {
                // Replay page
                $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'replay' );
            } elseif ( 'live' === $webinar_status ) {
                // Webinar page
                $page_id = WebinarignitionManager::webinarignition_get_webinar_page_id( $webinar_data, 'webinar' );
            }
        }
        $is_dashboard_url = webinarignition_is_dashboard_url();
        if ( is_array( $page_id ) ) {
            // Redirect only if non WI dashboard
            if ( !in_array( $post->ID, $page_id ) && !$is_dashboard_url && !$is_preview ) {
                $default = ( isset( $webinar_data->default_registration_page ) ? $webinar_data->default_registration_page : 0 );
                if ( !empty( $default ) ) {
                    if ( !in_array( $default, $page_id, true ) ) {
                        $page_id = reset( $page_id );
                    } else {
                        $page_id = $default;
                    }
                } else {
                    $page_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_data->id );
                }
                $permalink = get_permalink( $page_id );
                // URL parameters to keep before redirect
                $keep_get_params = array('live', 'googlecalendarA', 'confirmed');
                foreach ( $keep_get_params as $keep_get_param ) {
                    if ( isset( $_GET[$keep_get_param] ) ) {
                        $sanitized_value = sanitize_text_field( wp_unslash( $_GET[$keep_get_param] ) );
                        $permalink = add_query_arg( $keep_get_param, $sanitized_value, $permalink );
                    }
                }
                if ( isset( $webinar_status ) && $leadId && $webinar_status ) {
                    $permalink = add_query_arg( 'lid', $leadId, $permalink );
                }
                if ( (int) $page_id !== $post->ID && !empty( $permalink ) ) {
                    wp_safe_redirect( $permalink );
                    exit;
                }
            }
            //end if
        } else {
            // Redirect only if non WI dashboard
            if ( $post->ID !== $page_id && !$is_dashboard_url && !$is_preview ) {
                $permalink = get_permalink( $page_id );
                // URL parameters to keep before redirect
                $keep_get_params = array('live', 'googlecalendarA', 'confirmed');
                foreach ( $keep_get_params as $keep_get_param ) {
                    if ( isset( $_GET[$keep_get_param] ) ) {
                        $sanitized_value = sanitize_text_field( wp_unslash( $_GET[$keep_get_param] ) );
                        $permalink = add_query_arg( $keep_get_param, $sanitized_value, $permalink );
                    }
                }
                if ( $leadId && $webinar_status ) {
                    $permalink = add_query_arg( 'lid', $leadId, $permalink );
                }
                wp_safe_redirect( $permalink );
                exit;
            }
        }
        //end if
    }

    add_action( 'webinarignition_template_redirect', 'webinarignition_template_redirect_cb' );
}
//end if
if ( !function_exists( 'webinarignition_template_include' ) ) {
    function webinarignition_template_include(  $template  ) {
        if ( is_singular() ) {
            $webinar_id = get_query_var( 'wi_webinar_id' );
            if ( !empty( $webinar_id ) ) {
                $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
                if ( !empty( $webinar_data ) ) {
                    // Switch to webinar language before template include
                    WebinarignitionManager::webinarignition_set_locale( $webinar_data );
                    $template = apply_filters( 'webinarignition_template', $template, $webinar_data );
                    // Restore back to previous language after template include
                    WebinarignitionManager::webinarignition_restore_locale( $webinar_data );
                }
            }
        }
        return $template;
    }

    add_filter( 'template_include', 'webinarignition_template_include' );
}
//end if
if ( !function_exists( 'webinarignition_template' ) ) {
    function webinarignition_template_cb(  $template, $webinar_data  ) {
        global $post;
        $webinar_id = get_query_var( 'wi_webinar_id' );
        // TODO: Not sure where we are using this $client variable but keeping it now for the sake of old code, should be removed later
        $client = $webinar_id;
        // used as global, do not remove
        /**
         * @var $webinar_id
         * @var $webinarId
         * @var $is_preview
         * @var $leadId
         * @var $lead
         * @var $leadinfo
         * @var $data
         * @var $isAuto
         * @var $pluginName
         * @var $leadinfo
         * @var $assets
         * @var $webinar_status string ( live | countdown | replay | closed )
         */
        extract( (array) webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        $webinar_post_id = WebinarignitionManager::webinarignition_get_webinar_post_id( $webinar_id );
        set_query_var( 'webinar_data', $webinar_data );
        set_query_var( 'webinar_id', $webinar_id );
        $timeover = false;
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
        $lead_id = ( isset( $_GET['lid'] ) ? sanitize_text_field( wp_unslash( $_GET['lid'] ) ) : '' );
        if ( isset( $lead_id ) && $lead_id != '' ) {
            $lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
        }
        if ( isset( $_GET['register-now'] ) ) {
            // Build the path to your file in the plugin
            $file_path = plugin_dir_path( __FILE__ ) . 'lp/auto-register.php';
            if ( file_exists( $file_path ) ) {
                set_query_var( 'webinarignition_page', 'auto_register' );
                include $file_path;
                return;
                // stop further WordPress execution
            }
        }
        // TODO: Split default & custom page logics further for better code control and understanding
        $input_get = [
            'confirmed'       => ( isset( $_GET['confirmed'] ) ? sanitize_text_field( $_GET['confirmed'] ) : null ),
            'googlecalendar'  => ( isset( $_GET['googlecalendar'] ) ? sanitize_text_field( $_GET['googlecalendar'] ) : null ),
            'ics'             => ( isset( $_GET['ics'] ) ? sanitize_text_field( $_GET['ics'] ) : null ),
            'googlecalendarA' => ( isset( $_GET['googlecalendarA'] ) ? sanitize_text_field( $_GET['googlecalendarA'] ) : null ),
            'icsA'            => ( isset( $_GET['icsA'] ) ? sanitize_text_field( $_GET['icsA'] ) : null ),
            'thankyou'        => ( isset( $_GET['thankyou'] ) ? sanitize_text_field( $_GET['thankyou'] ) : null ),
            'countdown'       => ( isset( $_GET['countdown'] ) ? sanitize_text_field( $_GET['countdown'] ) : null ),
            'webinar'         => ( isset( $_GET['webinar'] ) ? sanitize_text_field( $_GET['webinar'] ) : null ),
            'replay'          => ( isset( $_GET['replay'] ) ? sanitize_text_field( $_GET['replay'] ) : null ),
            'console'         => ( isset( $_GET['console'] ) ? sanitize_text_field( $_GET['console'] ) : null ),
            'csv_key'         => ( isset( $_GET['csv_key'] ) ? sanitize_text_field( $_GET['csv_key'] ) : null ),
            'register-now'    => ( isset( $_GET['register-now'] ) ? sanitize_text_field( $_GET['register-now'] ) : null ),
            'trkorder'        => ( isset( $_GET['trkorder'] ) ? sanitize_text_field( $_GET['trkorder'] ) : null ),
        ];
        if ( isset( $input_get['thankyou'] ) ) {
            $webinar_status = 'countdown';
        } elseif ( isset( $input_get['countdown'] ) ) {
            $input_get['live'] = 1;
            $webinar_status = 'countdown';
        } elseif ( isset( $input_get['webinar'] ) ) {
            $webinar_status = 'live';
        } elseif ( isset( $input_get['replay'] ) ) {
            $webinar_status = 'replay';
        } else {
            $webinar_status = '';
        }
        if ( isset( $webinar_data->webinar_date ) && 'AUTO' === $webinar_data->webinar_date ) {
            if ( isset( $lead_id ) && $lead_id != '' && !empty( $lead ) ) {
                $datePickedAndLive = $lead->date_picked_and_live;
                $leadTimezone = $lead->lead_timezone;
                // Convert lead's selected time to a DateTime object
                if ( $webinar_data->lp_schedule_type == 'fixed' ) {
                    $leadDateTime = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->auto_date_fixed_submit . ' ' . $webinar_data->auto_time_fixed_submit, new DateTimeZone($leadTimezone) );
                } else {
                    $leadDateTime = new DateTime($datePickedAndLive, new DateTimeZone($leadTimezone));
                }
                // Get the current time in the same timezone as the lead
                $currentDateTime = new DateTime("now", new DateTimeZone($leadTimezone));
                if ( property_exists( $webinar_data, 'webinar_switch' ) && $webinar_data->webinar_switch == 'countdown' ) {
                    $webinar_status = 'countdown';
                } elseif ( $currentDateTime < $leadDateTime ) {
                    $webinar_status = 'countdown';
                }
            }
        }
        if ( property_exists( $webinar_data, 'webinar_switch' ) && $webinar_data->webinar_switch == 'countdown' ) {
            $webinar_status = 'countdown';
        }
        if ( property_exists( $webinar_data, 'webinar_switch' ) && $webinar_data->webinar_switch == 'closed' ) {
            $webinar_status = 'closed';
        }
        // Get the lead's selected date and timezone
        if ( isset( $input_get['trkorder'] ) ) {
            do_action( 'webinarignition_order_track_from_leads' );
        }
        if ( $leadId && isset( $webinar_status ) ) {
            // Include page template based on lead status
            // Added following conditional block to temp fix issue for "live" webinars custom webinar page
            // Some JS scripts not loading properly as webinarignition_page sets to "live" instead of "webinar"
            if ( 'live' === $webinar_status ) {
                // Webinar page
                if ( !empty( $lead->lead_status ) && 'attending' === $lead->lead_status || $timeover ) {
                    set_query_var( 'webinarignition_page', 'closed' );
                } elseif ( isset( $input_get['confirmed'] ) ) {
                    // Live Thank You page
                    set_query_var( 'webinarignition_page', 'thank_you' );
                } else {
                    set_query_var( 'webinarignition_page', 'webinar' );
                }
            } elseif ( $webinar_status == 'countdown' ) {
                //show countdown page if the webinar is not live
                set_query_var( 'show_countdown_page', $webinar_status );
            } else {
                set_query_var( 'webinarignition_page', $webinar_status );
            }
            if ( WebinarignitionManager::webinarignition_url_is_calendar_page() ) {
                if ( isset( $input_get['googlecalendar'] ) ) {
                    // Add To Calendar
                    include 'lp/google.php';
                } elseif ( isset( $input_get['ics'] ) ) {
                    // Add To iCal
                    include 'lp/ics.php';
                } elseif ( isset( $input_get['googlecalendarA'] ) ) {
                    // Add To Calendar
                    include 'lp/googleA.php';
                } elseif ( isset( $input_get['icsA'] ) ) {
                    // Add To iCal
                    include 'lp/icsA.php';
                }
                $template = null;
                // avoid loading default template
            } elseif ( $post->ID === $webinar_post_id ) {
                if ( WebinarignitionManager::webinarignition_url_is_preview_page() ) {
                    if ( isset( $input_get['thankyou'] ) ) {
                        $webinar_status = 'countdown';
                    } elseif ( isset( $input_get['countdown'] ) ) {
                        $input_get['live'] = 1;
                        $webinar_status = 'countdown';
                    } elseif ( isset( $input_get['webinar'] ) ) {
                        $webinar_status = 'live';
                    } elseif ( isset( $input_get['replay'] ) ) {
                        $webinar_status = 'replay';
                    } else {
                        $webinar_status = '';
                    }
                }
                if ( isset( $_GET['unsubnextwebinar'] ) || isset( $_GET['unsuballwebinar'] ) ) {
                    $webinar_status = 'unsubscribe';
                }
                if ( $webinar_status === 'unsubscribe' ) {
                    webinarignition_display_unsubscribed_page( $webinar_data, $webinar_id );
                } elseif ( $timeover ) {
                    // Closed page
                    webinarignition_display_timeover_page( $webinar_data, $webinar_id );
                } elseif ( 'countdown' === $webinar_status && isset( $input_get['live'] ) ) {
                    // Countdown page
                    webinarignition_display_countdown_page( $webinar_data, $webinar_id );
                } elseif ( 'countdown' === $webinar_status ) {
                    // Thankyou page
                    if ( WebinarignitionManager::webinarignition_url_is_preview_page() ) {
                        set_query_var( 'webinarignition_page', 'preview_auto_thankyou' );
                        webinarignition_display_preview_auto_thankyou_page( $webinar_data, $webinar_id );
                    } else {
                        set_query_var( 'webinarignition_page', 'thank_you' );
                        webinarignition_display_thank_you_page( $webinar_data, $webinar_id );
                    }
                } elseif ( 'closed' === $webinar_status ) {
                    // Closed page
                    webinarignition_display_closed_page( $webinar_data, $webinar_id );
                } elseif ( 'replay' === $webinar_status ) {
                    // Replay page
                    if ( WebinarignitionManager::webinarignition_url_is_preview_page() ) {
                        set_query_var( 'webinarignition_page', 'preview-replay' );
                    }
                    webinarignition_display_replay_page( $webinar_data, $webinar_id );
                } elseif ( 'live' === $webinar_status ) {
                    // Webinar page
                    if ( isset( $lead->lead_status ) && 'attending' === $lead->lead_status && !empty( $webinar_data->limit_lead_visit ) && $webinar_data->limit_lead_visit == 'enabled' ) {
                        set_query_var( 'webinarignition_page', 'closed' );
                        webinarignition_display_webinar_attending_page( $webinar_data, $webinar_id );
                    } else {
                        set_query_var( 'webinarignition_page', 'webinar' );
                        webinarignition_do_late_lockout_redirect( $webinar_data );
                        webinarignition_display_webinar_page( $webinar_data, $webinar_id );
                    }
                } else {
                    // Include registration page template if nothing found
                    set_query_var( 'webinarignition_page', 'registration' );
                    webinarignition_display_registration_page( $webinar_data, $webinar_id );
                }
                //end if
                $template = null;
                // avoid loading default template
            }
            //end if
        } else {
            // Include page template based on URL parameters
            if ( $post->ID === $webinar_post_id ) {
                if ( isset( $input_get['console'] ) ) {
                    set_query_var( 'webinarignition_page', 'console' );
                    webinarignition_display_console_page( $webinar_data, $webinar_id );
                } elseif ( isset( $input_get['csv_key'] ) ) {
                    set_query_var( 'webinarignition_page', 'csv_download' );
                    webinarignition_download_csv( $webinar_data, $webinar_id );
                } elseif ( isset( $input_get['register-now'] ) ) {
                    set_query_var( 'webinarignition_page', 'auto_register' );
                    webinarignition_display_auto_register_page( $webinar_data, $webinar_id );
                } else {
                    // Include registration page template if nothing found
                    set_query_var( 'webinarignition_page', 'registration' );
                    webinarignition_display_registration_page( $webinar_data, $webinar_id );
                }
                //end if
                $template = null;
                // avoid loading default template
            }
            //end if
        }
        //end if
        return $template;
    }

    add_filter(
        'webinarignition_template',
        'webinarignition_template_cb',
        10,
        2
    );
}
//end if
if ( !function_exists( 'webinarignition_display_preview_auto_thankyou_page' ) ) {
    function webinarignition_display_preview_auto_thankyou_page(  $webinar_data, $webinar_id  ) {
        if ( isset( $webinar_data->ty_cta_video_url ) && !empty( $webinar_data->ty_cta_video_url ) ) {
            wp_enqueue_style( 'webinarignition_video_css' );
            wp_enqueue_script( 'webinarignition_video_js' );
        }
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        global $wpdb;
        $table_db_name = $wpdb->prefix . 'webinarignition';
        // Sanitize input values
        $webinar_id = intval( $webinar_id );
        // Sanitize as integer
        // Prepare and execute the query
        $data = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $webinar_id ), OBJECT );
        if ( $data ) {
            if ( !empty( $data[0] ) ) {
                $data = $data[0];
                include 'lp/thankyou_cp_preview.php';
            }
        }
    }

}
if ( !function_exists( 'webinarignition_display_console_page' ) ) {
    function webinarignition_display_console_page(  $webinar_data, $webinar_id  ) {
        show_admin_bar( false );
        $is_support = false;
        $is_host = false;
        $is_admin = false;
        if ( WebinarignitionManager::webinarignition_is_support_enabled( $webinar_data ) ) {
            $is_support_stuff_token = ( !empty( $_GET['_support_stuff_token'] ) ? sanitize_text_field( $_GET['_support_stuff_token'] ) : '' );
            $support_stuff_token = ( !empty( $webinar_data->support_stuff_url ) ? $webinar_data->support_stuff_url : '' );
        }
        if ( WebinarignitionManager::webinarignition_is_support_enabled( $webinar_data, 'host' ) ) {
            $is_host_presenters_token = ( !empty( $_GET['_host_presenters_token'] ) ? sanitize_text_field( $_GET['_host_presenters_token'] ) : '' );
            $host_presenters_token = ( !empty( $webinar_data->host_presenters_url ) ? $webinar_data->host_presenters_url : '' );
        }
        $is_token = false;
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $roles = (array) $user->roles;
            if ( !empty( $roles ) ) {
                if ( 1 === count( $roles ) ) {
                    $role = $roles[0];
                    if ( 'webinarignition_support' === $role ) {
                        $is_support = true;
                    } elseif ( 'webinarignition_host' === $role ) {
                        $is_host = true;
                    } elseif ( current_user_can( 'manage_options' ) ) {
                        $is_admin = true;
                    }
                } else {
                    $role = 'subscriber';
                    foreach ( $roles as $role_temp ) {
                        if ( 'webinarignition_support' === $role_temp && 'webinarignition_host' !== $role && 'webinarignition_admin' !== $role && 'administrator' !== $role ) {
                            $role = $role_temp;
                        } elseif ( 'webinarignition_host' === $role_temp && 'webinarignition_admin' !== $role && 'administrator' !== $role ) {
                            $role = $role_temp;
                        }
                    }
                    if ( 'webinarignition_support' === $role ) {
                        $is_support = true;
                    } elseif ( 'webinarignition_host' === $role ) {
                        $is_host = true;
                    } elseif ( current_user_can( 'manage_options' ) ) {
                        $is_admin = true;
                    }
                }
                //end if
            }
            //end if
            $user_email = $user->user_email;
            if ( $is_support ) {
                if ( !WebinarignitionManager::webinarignition_is_support_enabled( $webinar_data ) ) {
                    $is_support = false;
                } else {
                    $support_enabled = false;
                    for ($x = 1; $x <= $webinar_data->support_staff_count; $x++) {
                        $member_email_str = 'member_email_' . $x;
                        if ( !empty( $webinar_data->{$member_email_str} ) && $user_email === $webinar_data->{$member_email_str} ) {
                            $support_enabled = true;
                            break;
                        }
                    }
                    if ( !$support_enabled ) {
                        $is_support = false;
                    }
                }
            }
            if ( $is_host ) {
                if ( !WebinarignitionManager::webinarignition_is_support_enabled( $webinar_data, 'host' ) ) {
                    $is_host = false;
                } else {
                    $host_enabled = false;
                    for ($x = 1; $x <= $webinar_data->host_member_count; $x++) {
                        $host_member_email_str = 'host_member_email_' . $x;
                        if ( !empty( $webinar_data->{$host_member_email_str} ) && $user_email === $webinar_data->{$host_member_email_str} ) {
                            $host_enabled = true;
                            break;
                        }
                    }
                    if ( !$host_enabled ) {
                        $is_host = false;
                    }
                }
            }
        }
        //end if
        if ( $is_support || $is_host || $is_admin ) {
            $current_user = wp_get_current_user();
            include_once WEBINARIGNITION_PATH . 'UI/ui-core.php';
            include_once WEBINARIGNITION_PATH . 'UI/ui-com2.php';
            global $post;
            // Display Leads For This App
            global $wpdb;
            $ID = $webinar_data->id;
            // Get Leads
            if ( 'AUTO' === $webinar_data->webinar_date ) {
                $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
                $ID = intval( $ID );
                // Sanitize as integer
                // Prepare and execute the query
                $leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d ORDER BY `created` ASC", $ID ), ARRAY_A );
                $totalLeads = count( $leads );
            } else {
                $table_db_name = $wpdb->prefix . 'webinarignition_leads';
                // Sanitize input values
                $ID = intval( $ID );
                // Ensure $ID is an integer
                // Prepare and execute the query
                $leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d ORDER BY `created` ASC", $ID ), ARRAY_A );
                $totalLeads = count( $leads );
            }
            $attendingCount = count( array_filter( $leads, function ( $item ) {
                return isset( $item['lead_status'] ) && $item['lead_status'] === 'attending';
            } ) );
            $attendedCount = count( array_filter( $leads, function ( $item ) {
                return isset( $item['lead_status'] ) && $item['lead_status'] !== 'No' && !empty( $item['lead_status'] );
            } ) );
            $purchasedCount = count( array_filter( $leads, function ( $item ) {
                return isset( $item['trk2'] ) && $item['trk2'] !== 'No' && $item['trk2'] == 'Yes';
            } ) );
            // Get Questions
            $table_db_name = $wpdb->prefix . 'webinarignition_questions';
            // Sanitize input values
            $ID = intval( $ID );
            // Ensure $ID is an integer
            // Prepare and execute the queries
            $questionsActive = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `status` = %s", $ID, 'live' ), OBJECT_K );
            $questionsDone = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `status` = %s", $ID, 'done' ), OBJECT );
            $questionsDone = ( is_array( $questionsDone ) ? array_reverse( $questionsDone ) : $questionsDone );
            $totalQuestionsActive = count( $questionsActive );
            $totalQuestionsDone = count( $questionsDone );
            $totalQuestions = $totalQuestionsActive + $totalQuestionsDone;
            // Get Total Orders
            $table_db_name = $wpdb->prefix . 'webinarignition_leads';
            // Sanitize input values
            $ID = intval( $ID );
            // Ensure $ID is an integer
            // Prepare and execute the query
            $orders = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `trk2` = %s", $ID, 'Yes' ), ARRAY_A );
            $totalOrders = count( $orders );
            // Info ::
            $table_db_name = $wpdb->prefix . 'webinarignition';
            // Sanitize input values
            $ID = intval( $ID );
            // Ensure $ID is an integer
            // Prepare and execute the query
            $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $ID ), OBJECT );
            // Path For Stuff
            $pluginName = 'webinarignition';
            $sitePath = WEBINARIGNITION_URL;
            include 'lp/console/index.php';
        } elseif ( !empty( $is_support_stuff_token ) && $is_support_stuff_token === $support_stuff_token ) {
            include 'lp/console/register-support.php';
        } elseif ( !empty( $is_host_presenters_token ) && $is_host_presenters_token === $host_presenters_token ) {
            include 'lp/console/register-support.php';
        } else {
            exit( esc_html__( 'You need to have the correct privileges to access this page.', 'webinar-ignition' ) );
        }
        //end if
    }

}
if ( !function_exists( 'webinarignition_display_closed_page' ) ) {
    function webinarignition_display_closed_page(  $webinar_data, $webinar_id  ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        include 'lp/closed.php';
    }

}
if ( !function_exists( 'webinarignition_display_unsubscribed_page' ) ) {
    function webinarignition_display_unsubscribed_page(  $webinar_data, $webinar_id  ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        include 'lp/unsubscribed.php';
    }

}
if ( !function_exists( 'webinarignition_display_timeover_page' ) ) {
    function webinarignition_display_timeover_page(  $webinar_data, $webinar_id  ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        include 'lp/timeover.php';
    }

}
if ( !function_exists( 'webinarignition_display_webinar_attending_page' ) ) {
    function webinarignition_display_webinar_attending_page(  $webinar_data, $webinar_id  ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        include 'lp/single_lead_notice_page.php';
    }

}
// Auto Register From URL & Submit AR URL
if ( !function_exists( 'webinarignition_display_auto_register_page' ) ) {
    function webinarignition_display_auto_register_page(  $webinar_data, $webinar_id  ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        $name = sanitize_text_field( filter_input( INPUT_GET, 'n' ) );
        $email = ( isset( $_GET['e'] ) ? sanitize_email( filter_input( INPUT_GET, 'e', FILTER_SANITIZE_EMAIL ) ) : '' );
        include 'lp/auto-register.php';
    }

}
if ( !function_exists( 'webinarignition_display_replay_page' ) ) {
    function webinarignition_display_replay_page(  $webinar_data, $webinar_id  ) {
        extract( (array) webinarignition_get_replay_templates_vars( $webinar_data ) );
        //phpcs:ignore
        $webinar_page_template = WebinarignitionManager::webinarignition_get_webinar_page_template( $webinar_data );
        if ( 'modern' === $webinar_page_template ) {
            set_query_var( 'webinarignition_modern_page', 'replay_page' );
            include 'lp/webinar-modern.php';
        } else {
            set_query_var( 'webinarignition_page', 'replay_page' );
            include 'lp/replay.php';
        }
    }

}
if ( !function_exists( 'wi_get_shortcode_background_color_style' ) ) {
    /**
     * Generate inline background color style for a webinar block.
     *
     * @param array $webinarignition_shortcode_params Shortcode parameters array.
     * @param int   $webinar_id Webinar post ID.
     * @return string Inline background color style or empty string.
     */
    function wi_get_shortcode_background_color_style(  $webinarignition_shortcode_params, $webinar_id  ) {
        if ( !empty( $webinarignition_shortcode_params[$webinar_id] ) && isset( $webinarignition_shortcode_params[$webinar_id]['background_color'] ) && stripos( $webinarignition_shortcode_params[$webinar_id]['background_color'], 'example' ) === false ) {
            $bg_color = esc_attr( trim( $webinarignition_shortcode_params[$webinar_id]['background_color'] ) );
            if ( !empty( $bg_color ) ) {
                return "background-color: {$bg_color};";
            }
        }
        // Return empty string if no valid color found.
        return '';
    }

}
if ( !function_exists( 'wi_get_shortcode_contrast_color_style' ) ) {
    /**
     * Generate inline contrast color (text color) style for a webinar block.
     *
     * @param array $webinarignition_shortcode_params Shortcode parameters array.
     * @param int   $webinar_id Webinar post ID.
     * @param bool  $only_color Optional. If true, return only the color code (e.g., #ff0000). Default false.
     * @return string Inline contrast color style or empty string.
     */
    function wi_get_shortcode_contrast_color_style(  $webinarignition_shortcode_params, $webinar_id, $only_color = false  ) {
        if ( !empty( $webinarignition_shortcode_params[$webinar_id] ) && isset( $webinarignition_shortcode_params[$webinar_id]['contrast_color'] ) && stripos( $webinarignition_shortcode_params[$webinar_id]['contrast_color'], 'example' ) === false ) {
            $contrast_color = esc_attr( trim( $webinarignition_shortcode_params[$webinar_id]['contrast_color'] ) );
            if ( !empty( $contrast_color ) ) {
                // Return based on $only_color flag.
                return ( $only_color ? $contrast_color : "color: {$contrast_color} !important;" );
            }
        }
        // Return empty string if no valid color found.
        return '';
    }

}
/**
 * Add inline color style to <a> elements in given HTML if they exist.
 *
 * @param string $html  The HTML content (e.g., $webinar_data->lp_optin_terms_and_conditions).
 * @param string $color The color code to apply (e.g., "#FFF" or "red").
 * @return string Modified HTML with inline color style added to <a> tags (if any).
 */
if ( !function_exists( 'wi_add_color_style_to_a_tag' ) ) {
    function wi_add_color_style_to_a_tag(  $html, $color  ) {
        // Ensure we have valid HTML
        if ( empty( $html ) || empty( $color ) ) {
            return $html;
        }
        // Check if HTML contains <a> tag
        if ( strpos( $html, '<a' ) === false ) {
            return $html;
            // No <a> tag, return as is
        }
        // Add style if <a> tag has no existing style
        $html = preg_replace_callback( '/<a\\b(?![^>]*\\bstyle=)([^>]*)>/i', function ( $matches ) use($color) {
            return '<a style="color: ' . esc_attr( $color ) . ' !important;" ' . $matches[1] . '>';
        }, $html );
        // If <a> already has a style, append color property
        $html = preg_replace( '/<a\\b([^>]*)style=["\']([^"\']*)["\']([^>]*)>/i', '<a\\1style="\\2 color: ' . esc_attr( $color ) . ' !important;"\\3>', $html );
        return $html;
    }

}
if ( !function_exists( 'webinarignition_display_countdown_page' ) ) {
    function webinarignition_display_countdown_page(  $webinar_data, $webinar_id  ) {
        $full_path = get_site_url();
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        // Only get the required input values
        $leadID = sanitize_text_field( filter_input( INPUT_GET, 'lid', FILTER_UNSAFE_RAW ) );
        // Check if its a auto Webinar
        if ( 'AUTO' === $webinar_data->webinar_date && !empty( $leadID ) ) {
            // Get Information
            $leadinfo = webinarignition_get_lead_info( $leadID, $webinar_data );
        }
        include 'lp/countdown.php';
    }

}
if ( !function_exists( 'webinarignition_display_webinar_page' ) ) {
    function webinarignition_display_webinar_page(  $webinar_data, $webinar_id  ) {
        $full_path = get_site_url();
        /**
         * @var $input_get
         * @var $webinar_id
         * @var $webinarId
         * @var $is_preview
         * @var $leadId
         * @var $lead
         * @var $leadinfo
         * @var $data
         * @var $isAuto
         * @var $pluginName
         * @var $leadinfo
         * @var $assets
         * @var $webinar_status string ( live | countdown | replay | closed )
         */
        extract( (array) webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        global $wpdb;
        if ( 'AUTO' === $webinar_data->webinar_date ) {
            $individual_offset = 0;
        }
        $webinar_page_template = WebinarignitionManager::webinarignition_get_webinar_page_template( $webinar_data );
        include "lp/webinar-{$webinar_page_template}.php";
    }

}
//end if
if ( !function_exists( 'webinarignition_display_registration_page' ) ) {
    function webinarignition_display_registration_page(  $webinar_data, $webinarId  ) {
        session_start();
        if ( isset( $_SESSION['latecomer'] ) && !empty( $_SESSION['latecomer'] ) && $_SESSION['latecomer'] == true ) {
            $webinar_data->latecomer = true;
        }
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        $input_get = array(
            'email' => ( isset( $_POST['email'] ) ? sanitize_email( filter_input( INPUT_GET, 'email', FILTER_SANITIZE_EMAIL ) ) : '' ),
            'lid'   => sanitize_text_field( filter_input( INPUT_GET, 'lid' ) ),
        );
        $template_number = '01';
        $registration_templates = array(
            'lp' => '01',
            'ss' => '02',
            'cp' => '03',
        );
        if ( !empty( $webinar_data->fe_template ) && in_array( $webinar_data->fe_template, array_keys( $registration_templates ), true ) ) {
            $template_number = $registration_templates[$webinar_data->fe_template];
        }
        $isSigningUpWithFB = false;
        $fbUserData = array();
        if ( !empty( $webinar_data->fb_id ) && !empty( $webinar_data->fb_secret ) ) {
            include_once 'lp/fbaccess.php';
            /**
             * @var $user_info
             */
            $isSigningUpWithFB = true;
            $fbUserData['name'] = $user_info['name'];
            $fbUserData['email'] = $user_info['email'];
        } else {
            $user_info = array();
        }
        include WEBINARIGNITION_PATH . "inc/lp/registration-tpl-{$template_number}.php";
    }

}
//end if
// add_action('wp_enqueue_scripts', 'webinarignition_deregister_theme_scripts' );
if ( !function_exists( 'webinarignition_deregister_theme_scripts' ) ) {
    function webinarignition_deregister_theme_scripts() {
        global $wp_styles;
        $webinar_data = get_query_var( 'webinar_data' );
        if ( $webinar_data ) {
            if ( 'enabled' === $webinar_data->wp_head_footer ) {
                return;
            }
            foreach ( $wp_styles->registered as $style_obj ) {
                if ( substr( $style_obj->handle, 0, 16 ) !== 'webinarignition_' ) {
                    wp_deregister_style( $style_obj->handle );
                    wp_dequeue_style( $style_obj->handle );
                }
            }
        }
    }

}
if ( !function_exists( 'webinarignition_display_thank_you_page' ) ) {
    function webinarignition_display_thank_you_page(  $webinar_data, $webinarId  ) {
        global $wpdb;
        $instantTest = '';
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        $full_path = get_site_url();
        $leadId = null;
        // Check if 'lid' is in $_GET and sanitize it
        if ( !empty( $_GET['lid'] ) ) {
            $leadId = sanitize_text_field( wp_unslash( $_GET['lid'] ) );
        } elseif ( !empty( $_COOKIE['we-trk-' . $webinarId] ) ) {
            $leadId = sanitize_text_field( wp_unslash( $_COOKIE['we-trk-' . $webinarId] ) );
        }
        // Sanitize 'email' from $_GET
        $email = ( !empty( $_GET['email'] ) ? sanitize_email( wp_unslash( $_GET['email'] ) ) : null );
        $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
        $db_table_name = $wpdb->prefix . 'webinarignition';
        $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$db_table_name} WHERE id = %d", $webinarId ), OBJECT );
        // fix missing lid in live oneclick final url
        if ( empty( $leadId ) && !empty( $email ) ) {
            $getLiveIDByEmail = webinarignition_live_get_lead_by_email( $webinarId, $email, $is_lead_protected );
            $leadId = $getLiveIDByEmail->ID;
        }
        if ( !isset( $leadId ) || empty( $leadId ) ) {
            $leadId = WebinarignitionManager::webinarignition_url_has_valid_lead_id();
        }
        $isAuto = webinarignition_is_auto( $webinar_data );
        if ( !empty( $leadId ) && !empty( $webinarId ) ) {
            $lead = webinarignition_get_lead_info( $leadId, $webinar_data );
            if ( empty( $lead ) ) {
                wp_safe_redirect( $webinar_data->webinar_permalink );
                exit;
            }
            if ( isset( $webinar_data->skip_ty_page ) && 'yes' === $webinar_data->skip_ty_page ) {
                // Do not redirect for instant lead
                if ( !$isAuto || isset( $lead->trk8 ) && $lead->trk8 !== 'yes' ) {
                    wp_safe_redirect( $webinar_data->webinar_permalink . '?live&lid=' . $leadId );
                    exit;
                }
            }
            if ( !empty( $email ) ) {
                wp_safe_redirect( $webinar_data->webinar_permalink . '?confirmed&lid=' . $leadId );
                exit;
            }
        }
        //end if
        if ( $isAuto ) {
            $autoDate_format = webinarignition_display_date( $webinar_data, $lead );
            $autoTime = webinarignition_display_time( $webinar_data, $lead );
            // instant test // todo what is instant test?
            if ( 'yes' === $lead->trk8 ) {
                $instantTest = "style='display:none;'";
            }
            // For Month Icon
            $liveEventMonth = webinarignition_event_month( $webinar_data, $lead );
            $liveEventDateDigit = webinarignition_event_day( $webinar_data, $lead );
        }
        include 'lp/thankyou_cp.php';
    }

}
// --------------------------------------------------------------------------------
// region Enqueue scripts
// --------------------------------------------------------------------------------
add_action( 'wp_enqueue_scripts', 'webinarignition_register_frontend_scripts', 10 );
if ( !function_exists( 'webinarignition_register_frontend_scripts' ) ) {
    function webinarignition_register_frontend_scripts() {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        // Register styles
        wp_register_style(
            'webinarignition_webinar_new',
            $assets . 'css/webinar-new.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_webinar_modern',
            $assets . 'css/webinar-modern.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_webinar_shared',
            $assets . 'css/webinar-shared.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_head_style',
            $assets . 'css/head-style.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_video_css',
            $assets . 'video-js-8.17.4/video-js.min.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_normalize',
            $assets . 'css/normalize.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_bootstrap',
            $assets . 'css/bootstrap.min.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_foundation',
            $assets . 'css/foundation.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_font-awesome',
            $assets . 'css/font-awesome.min.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_main',
            $assets . 'css/main.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_main_template',
            $assets . 'css/main-template.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_cp',
            $assets . 'css/cp.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_ss',
            $assets . 'css/ss.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_cpres_ty',
            $assets . 'css/cpres_ty.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_intlTelInput',
            $assets . 'js-libs/css/intlTelInput.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_css_utils',
            $assets . 'css/utils.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        wp_register_style(
            'webinarignition_cdres',
            $assets . 'css/cdres.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_countdown',
            $assets . 'css/countdown.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_countdown_ty',
            $assets . 'css/countdown-ty.css',
            array('webinarignition_countdown'),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_countdown_ty_inline',
            false,
            array('webinarignition_countdown_ty'),
            WEBINARIGNITION_VERSION,
            true
        );
        wp_register_style(
            'webinarignition_countdown_replay',
            $assets . 'css/countdown-replay.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_auto_register_css',
            $assets . 'css/auto_register_css.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_webinar',
            $assets . 'css/webinar.css',
            array(),
            WEBINARIGNITION_VERSION . '-' . time()
        );
        wp_register_style(
            'webinarignition_head_style_after',
            $assets . 'css/head-style-after.css',
            array(),
            WEBINARIGNITION_VERSION
        );
        // Get sanitized color values (fall back to defaults if needed)
        $brand_color = sanitize_hex_color( get_option( 'webinarignition_brand_color', '#ffffff' ) );
        $contrast_color = sanitize_hex_color( get_option( 'webinarignition_brand_contrast_color', '#3f3f3f' ) );
        // Output CSS variables inline
        $custom_css = ":root {\n\t\t\t\t\t--wi-brand-color: {$brand_color};\n\t\t\t\t\t--wi-brand-contrast-color: {$contrast_color};\n\t\t}";
        wp_add_inline_style( 'webinarignition_webinar_modern', $custom_css );
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
            true
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
        $webinar_id = absint( get_query_var( 'webinar_id' ) );
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
        $lead_id = ( !empty( $_GET['lid'] ) ? sanitize_text_field( $_GET['lid'] ) : '' );
        $video_live_time = '';
        if ( !empty( $lead_id ) ) {
            $lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
        }
        if ( isset( $webinar_data->webinar_date ) && $webinar_data && 'AUTO' === $webinar_data->webinar_date && !empty( $lead->date_picked_and_live ) ) {
            $video_live_time = $lead->date_picked_and_live;
        } elseif ( $webinar_data && !empty( $webinar_data->webinar_date ) && !empty( $webinar_data->webinar_start_time ) ) {
            $video_live_time = $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time;
        }
        wp_localize_script( 'webinarignition_before_footer_js', 'bFwebinarData', array(
            'ajaxurl'         => admin_url( 'admin-ajax.php' ),
            'webinar_id'      => ( !empty( $webinar_id ) ? $webinar_id : get_query_var( 'webinar_id' ) ),
            'ajax_nonce'      => wp_create_nonce( 'webinarignition_ajax_nonce' ),
            'lead_name'       => ( !empty( $lead->name ) ? $lead->name : '' ),
            'lead_email'      => ( !empty( $lead->email ) ? $lead->email : '' ),
            'video_live_time' => $video_live_time,
            'webinar_type'    => ( $webinar_data && isset( $webinar_data->webinar_date ) && 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' ),
            'is_auto_webinar' => $webinar_data && isset( $webinar_data->webinar_date ) && 'AUTO' === $webinar_data->webinar_date,
        ) );
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
            array(
                'webinarignition_cookie_js',
                'webinarignition_intlTelInput_js',
                'webinarignition_frontend_js',
                'webinarignition_webinar_data_after_js'
            ),
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
            'webinarignition_after_footer_js',
            $assets . 'js/after-footer.js',
            array('jquery'),
            WEBINARIGNITION_VERSION,
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
        wp_register_script(
            'webinarignition_auto_video_inline_js',
            $assets . 'js/webinar-auto-video-inline.js',
            array('jquery'),
            WEBINARIGNITION_VERSION,
            true
        );
    }

}
//end if
add_action( 'wp_enqueue_scripts', 'webinarignition_preview_auto_thankyou_page_scripts', 55 );
if ( !function_exists( 'webinarignition_preview_auto_thankyou_page_scripts' ) ) {
    function webinarignition_preview_auto_thankyou_page_scripts() {
        if ( 'preview_auto_thankyou' === get_query_var( 'webinarignition_page' ) ) {
            wp_enqueue_style( 'webinarignition_bootstrap' );
            wp_enqueue_style( 'webinarignition_foundation' );
            wp_enqueue_style( 'webinarignition_intlTelInput' );
            wp_enqueue_style( 'webinarignition_main' );
            wp_enqueue_style( 'webinarignition_cp' );
            wp_enqueue_style( 'webinarignition_cpres_ty' );
            wp_enqueue_style( 'webinarignition_countdown_ty' );
            wp_enqueue_style( 'webinarignition_font-awesome' );
            wp_enqueue_script( 'webinarignition_after_footer_js' );
        }
    }

}
// add_action( 'wp_enqueue_scripts', 'webinarignition_auto_register_page_scripts', 55 );
// if ( ! function_exists( 'webinarignition_auto_register_page_scripts' ) ) :
// 	function webinarignition_auto_register_page_scripts() {
// 		global $post;
// 		if ( 'auto_register' === get_query_var( 'webinarignition_page' ) || webinarignition_is_webinar_common_page() ) :
// 			wp_enqueue_style( 'webinarignition_bootstrap' );
// 			wp_enqueue_style( 'webinarignition_auto_register_css' );
// 			wp_enqueue_script( 'jquery' );
// 			wp_enqueue_script(
// 				'webinarignition-auto-register',
// 				WEBINARIGNITION_URL . 'assets/webinarignition-auto-register.js',
// 				array( 'jquery' ),
// 				WEBINARIGNITION_VERSION,
// 				array( 'in_footer' => true )
// 			);
// 		endif;
// 	}
// endif;
add_action( 'wp_enqueue_scripts', 'webinarignition_thank_you_page_scripts', 55 );
if ( !function_exists( 'webinarignition_thank_you_page_scripts' ) ) {
    function webinarignition_thank_you_page_scripts() {
        if ( 'thank_you' === get_query_var( 'webinarignition_page' ) ) {
            $webinar_data = get_query_var( 'webinar_data' );
            $webinarignition_page = get_query_var( 'webinarignition_page' );
            if ( empty( $webinar_data ) || empty( $webinarignition_page ) ) {
                return;
            }
            extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
            //phpcs:ignore
            // <head> css
            wp_enqueue_style( 'webinarignition_bootstrap' );
            wp_enqueue_style( 'webinarignition_foundation' );
            wp_enqueue_style( 'webinarignition_font-awesome' );
            wp_enqueue_style( 'webinarignition_main' );
            wp_enqueue_style( 'webinarignition_intlTelInput' );
            wp_enqueue_style( 'webinarignition_cp' );
            wp_enqueue_style( 'webinarignition_cpres_ty' );
            wp_enqueue_style( 'webinarignition_countdown_ty' );
            if ( !empty( $webinar_data->custom_ty_css ) ) {
                wp_add_inline_style( 'webinarignition_main', esc_html( $webinar_data->custom_ty_css ) );
            }
            $ty_css = ( ' .topArea{' . ('hide' === $webinar_data->lp_banner_bg_style) ? 'display: none;' : '' );
            $ty_css .= ( ' background-color: ' . empty( $webinar_data->lp_banner_bg_color ) ? '#FFF' : $webinar_data->lp_banner_bg_color );
            $ty_css .= ( empty( $webinar_data->lp_banner_bg_repeater ) ? 'border-top: 3px solid rgba(0,0,0,0.20); border-bottom: 3px solid rgba(0,0,0,0.20);}' : "background-image: url({$webinar_data->lp_banner_bg_repeater});" );
            $ty_css .= '.mainWrapper{  background-color: #f1f1f1; }';
            wp_add_inline_style( 'webinarignition_cp', $ty_css );
            // <head> js
            wp_enqueue_script( 'webinarignition_cookie_js' );
            if ( !empty( $webinar_data->custom_ty_js ) ) {
                wp_add_inline_script( 'moment', $webinar_data->custom_ty_js );
            }
            wp_enqueue_script( 'webinarignition_before_footer_js' );
            wp_enqueue_script( 'moment' );
            wp_enqueue_script( 'webinarignition_intlTelInput_js' );
            wp_enqueue_script( 'webinarignition_frontend_js' );
            wp_enqueue_script( 'webinarignition_countdown_js' );
            wp_enqueue_script( 'webinarignition_after_footer_js' );
            $after_footer_js = array();
            wp_add_inline_script( 'webinarignition_after_footer_js', webinarignition_inline_js_file( $after_footer_js, $webinar_data ), 'before' );
            extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
            //phpcs:ignore
            // Define variables to pass to the JavaScript file
            $ajax_url = esc_url( admin_url( 'admin-ajax.php' ) );
            $tracking_cookie = 'we-trk-ty-' . esc_attr( $webinar_data->id );
            $nonce = esc_attr( wp_create_nonce( 'webinarignition_ajax_nonce' ) );
            $webinar_id = absint( $webinar_data->id );
            $auto_tz = null;
            if ( webinarignition_is_auto( $webinar_data ) ) {
                if ( $webinar_data->lp_schedule_type == 'fixed' ) {
                    $expire = $webinar_data->auto_date_fixed_submit;
                    $webinar_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
                    if ( count( $webinar_date ) >= 3 ) {
                        $expire = "{$webinar_date[2]}-{$webinar_date[0]}-{$webinar_date[1]}";
                    } else {
                        $expire = '';
                        // Handle the case where the date format is unexpected
                    }
                    $time = $webinar_data->auto_time_fixed_submit;
                    $time = gmdate( 'H:i', strtotime( $time ) );
                    $auto_tz = webinarignition_get_tzOffset( $lead->lead_timezone );
                } else {
                    $expire = explode( ' ', $lead->date_picked_and_live )[0];
                    $time = explode( ' ', $lead->date_picked_and_live )[1];
                    $auto_tz = webinarignition_get_tzOffset( $lead->lead_timezone );
                }
            } else {
                $expire = $webinar_data->webinar_date;
                $webinar_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
                if ( count( $webinar_date ) >= 3 ) {
                    $expire = "{$webinar_date[2]}-{$webinar_date[0]}-{$webinar_date[1]}";
                } else {
                    $expire = '';
                    // Handle the case where the date format is unexpected
                }
                $time = gmdate( 'H:i', strtotime( $webinar_data->webinar_start_time ) );
                $timezone_string = $webinar_data->webinar_timezone;
                // The timezone you want to use
                $datetime = new DateTime('now', new DateTimeZone($timezone_string));
                $utc_offset = $datetime->getOffset() / 3600;
                // Get the offset in hours
                $auto_tz = (( $utc_offset >= 0 ? '+' : '' )) . $utc_offset;
            }
            $ex_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
            $ex_time = explode( ':', $time );
            $ex_year = esc_attr( $ex_date[0] ) ?? '0';
            $ex_month = esc_attr( $ex_date[1] - 1 ) ?? '0';
            $ex_day = esc_attr( $ex_date[2] ) ?? '0';
            $ex_hr = esc_attr( $ex_time[0] ) ?? '0';
            $ex_min = esc_attr( str_replace( array(' ', 'AM', 'PM'), '', $ex_time[1] ) ) ?? '0';
            $tz_offset = esc_attr( $auto_tz );
            $webinarignition_page = ( !empty( $webinarignition_page ) ? $webinarignition_page : get_query_var( 'webinarignition_page' ) );
            // Enqueue the JavaScript file and pass the variables
            $secutirytoken = wp_create_nonce( 'webinarignition_ajax_nonce' );
            wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
                'ajaxurl'           => $ajax_url,
                'trackingCookie'    => $tracking_cookie,
                'nonce'             => $nonce,
                'webinarId'         => $webinar_id,
                'exYear'            => $ex_year,
                'exMonth'           => $ex_month,
                'exDay'             => $ex_day,
                'exHr'              => $ex_hr,
                'exMin'             => $ex_min,
                'tzOffset'          => $tz_offset,
                'leadId'            => esc_attr( $leadId ),
                'tycd_years'        => $webinar_data->tycd_years,
                'tycd_months'       => $webinar_data->tycd_months,
                'tycd_weeks'        => $webinar_data->tycd_weeks,
                'tycd_days'         => $webinar_data->tycd_days,
                'tycd_progress'     => $webinar_data->tycd_progress,
                'webinarInProgress' => __( 'Webinar Is In Progress', 'webinar-ignition' ),
                'isAuto'            => webinarignition_is_auto( $webinar_data ),
                'webinarDate'       => $webinar_data->webinar_date,
                'security'          => $secutirytoken,
                'currentPage'       => $webinarignition_page,
                'webinar_id'        => $webinar_data->id,
            ) );
        }
        //end if
    }

}
if ( !function_exists( 'webinarignition_is_webinar_common_page' ) ) {
    function webinarignition_is_webinar_common_page() {
        global $post;
        return is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'wi_webinar_block' );
    }

}
add_action( 'wp_enqueue_scripts', 'webinarignition_webinar_page_scripts', 55 );
if ( !function_exists( 'webinarignition_webinar_page_scripts' ) ) {
    function webinarignition_webinar_page_scripts() {
        if ( 'webinar' === get_query_var( 'webinarignition_page' ) || isset( $_GET['preview-webinar'] ) ) {
            $webinar_data = get_query_var( 'webinar_data' );
            $webinarignition_page = get_query_var( 'webinarignition_page' );
            if ( empty( $webinar_data ) || empty( $webinarignition_page ) ) {
                return;
            }
            extract( (array) webinarignition_get_webinar_templates_vars( $webinar_data ) );
            //phpcs:ignore
            $webinar_page_template = WebinarignitionManager::webinarignition_get_webinar_page_template( $webinar_data );
            if ( 'modern' === $webinar_page_template ) {
                wp_enqueue_style( 'webinarignition_webinar_modern' );
                wp_enqueue_style( 'webinarignition_webinar_shared' );
            } else {
                wp_enqueue_style( 'webinarignition_bootstrap' );
                wp_enqueue_style( 'webinarignition_foundation' );
                wp_enqueue_style( 'webinarignition_font-awesome' );
                wp_enqueue_style( 'webinarignition_main' );
                wp_enqueue_style( 'webinarignition_webinar' );
                wp_enqueue_style( 'webinarignition_webinar_shared' );
                wp_add_inline_style( 'webinarignition_webinar', webinarignition_inline_css_file( WEBINARIGNITION_PATH . 'inc/lp/css/webinar_css.php', $webinar_data ) );
            }
            if ( webinarignition_should_use_videojs( $webinar_data ) ) {
                wp_enqueue_style( 'webinarignition_video_css' );
            }
            if ( !empty( $webinar_data->custom_webinar_css ) ) {
                wp_add_inline_style( 'webinarignition_webinar', esc_html( $webinar_data->custom_webinar_css ) );
            }
            // <head> js
            wp_enqueue_script( 'webinarignition_cookie_js' );
            if ( !empty( $webinar_data->custom_webinar_js ) ) {
                wp_add_inline_script( 'webinarignition_cookie_js', '(function ($) {' . $webinar_data->custom_webinar_js . '})(jQuery);' );
            }
            wp_enqueue_script( 'webinarignition_countdown_js' );
            wp_enqueue_script( 'webinarignition_polling_js' );
            wp_enqueue_script( 'webinarignition_updater_js' );
            if ( webinarignition_should_use_videojs( $webinar_data ) ) {
                wp_enqueue_script( 'webinarignition_video_js' );
            }
            wp_enqueue_script( 'webinarignition_before_footer_js' );
            if ( 'hide' !== trim( $webinar_data->webinar_qa ) ) {
                wp_add_inline_script( 'webinarignition_before_footer_js', webinarignition_inline_js_file( array(WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php', WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php'), $webinar_data ), 'before' );
            }
            if ( 'modern' === $webinar_page_template ) {
                wp_enqueue_script( 'webinarignition_webinar_modern_js' );
                wp_enqueue_script( 'webinarignition_backup_js' );
            }
            wp_enqueue_script( 'webinarignition_webinar_cta_js' );
            wp_enqueue_script( 'webinarignition_after_footer_js' );
            $webinar_data = ( !empty( $webinar_data ) ? $webinar_data : get_query_var( 'webinar_data' ) );
            $webinarignition_page = ( !empty( $webinarignition_page ) ? $webinarignition_page : get_query_var( 'webinarignition_page' ) );
            $webinar_type = ( 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' );
            $tracking_tags_settings = ( isset( $webinar_data->tracking_tags ) ? $webinar_data->tracking_tags : array() );
            $lead_id = ( !empty( $leadinfo->ID ) ? $leadinfo->ID : '' );
            $is_replay_page = 'replay_custom' === $webinarignition_page || 'preview-replay' === $webinarignition_page || 'replay_page' === $webinarignition_page;
            $additional_autoactions_js = array();
            $additional_autoactions = array();
            if ( 'evergreen' === $webinar_type ) {
                if ( 'time' === trim( $webinar_data->auto_action ) && (!empty( $webinar_data->webinar_iframe_source ) || !empty( $webinar_data->auto_video_url ) || !empty( $webinar_data->auto_video_url2 )) ) {
                    $cta_position_default = 'outer';
                    $cta_position_allowed = 'outer';
                    $cta_position_overlay_allowed = 'overlay';
                    if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && property_exists( $webinar_data, 'additional_autoactions' ) && !empty( $webinar_data->additional_autoactions ) ) {
                        $additional_autoactions = maybe_unserialize( $webinar_data->additional_autoactions );
                    }
                    if ( !empty( $webinar_data->auto_action_time ) && (!empty( $webinar_data->auto_action_copy ) || !empty( $webinar_data->auto_action_btn_copy ) && !empty( $webinar_data->auto_action_url )) ) {
                        $webinar_main_auto_action = [
                            'auto_action_time'     => $webinar_data->auto_action_time,
                            'auto_action_time_end' => '',
                            'auto_action_copy'     => '',
                            'auto_action_btn_copy' => '',
                            'auto_action_url'      => '',
                            'replay_order_color'   => '#6BBA40',
                        ];
                        if ( !empty( $webinar_data->auto_action_time_end ) ) {
                            $webinar_main_auto_action['auto_action_time_end'] = $webinar_data->auto_action_time_end;
                        }
                        if ( !empty( $webinar_data->replay_order_color ) ) {
                            $webinar_main_auto_action['replay_order_color'] = $webinar_data->replay_order_color;
                        }
                        if ( !empty( $webinar_data->auto_action_copy ) ) {
                            $webinar_main_auto_action['auto_action_copy'] = $webinar_data->auto_action_copy;
                        }
                        if ( !empty( $webinar_data->auto_action_max_width ) ) {
                            $webinar_main_auto_action['auto_action_max_width'] = $webinar_data->auto_action_max_width;
                        }
                        if ( !empty( $webinar_data->auto_action_transparency ) ) {
                            $webinar_main_auto_action['auto_action_transparency'] = $webinar_data->auto_action_transparency;
                        }
                        if ( !empty( $webinar_data->auto_action_btn_copy ) && !empty( $webinar_data->auto_action_url ) ) {
                            $webinar_main_auto_action['auto_action_btn_copy'] = $webinar_data->auto_action_btn_copy;
                            $webinar_main_auto_action['auto_action_url'] = $webinar_data->auto_action_url;
                        }
                        if ( !empty( $webinar_data->cta_position ) ) {
                            $cta_position_default = $webinar_data->cta_position;
                        }
                        if ( $cta_position_default === $cta_position_allowed ) {
                            $webinar_main_auto_action['cta_position'] = 'outer';
                        } else {
                            $webinar_main_auto_action['cta_position'] = 'overlay';
                        }
                        if ( is_array( $additional_autoactions ) && !empty( $additional_autoactions ) ) {
                            $additional_autoactions = array_merge( [$webinar_main_auto_action], $additional_autoactions );
                        } else {
                            $additional_autoactions[] = $webinar_main_auto_action;
                        }
                    }
                    ksort( $additional_autoactions );
                    foreach ( $additional_autoactions as $index => $additional_autoaction ) {
                        $cta_position = $cta_position_default;
                        if ( !empty( $additional_autoaction['cta_position'] ) ) {
                            $cta_position = $additional_autoaction['cta_position'];
                        }
                        if ( !empty( $additional_autoaction['auto_action_time'] ) ) {
                            $auto_action_time_array = explode( ':', $additional_autoaction['auto_action_time'] );
                            $delay = 10;
                            if ( !empty( $auto_action_time_array[0] ) ) {
                                $delay = $delay + $auto_action_time_array[0] * 60000;
                            }
                            if ( !empty( $auto_action_time_array[1] ) ) {
                                $delay = $delay + absint( $auto_action_time_array[1] ) * 1000;
                            }
                            $start_delay = $delay;
                            if ( $start_delay > 10 ) {
                                $start_delay = $start_delay + 1000;
                            }
                            if ( empty( $additional_autoaction['auto_action_time_end'] ) ) {
                                $delay = 0;
                            } else {
                                $auto_action_time_array = explode( ':', $additional_autoaction['auto_action_time_end'] );
                                $delay = 0;
                                if ( !empty( $auto_action_time_array[0] ) ) {
                                    $delay = $delay + $auto_action_time_array[0] * 60000;
                                }
                                if ( !empty( $auto_action_time_array[1] ) ) {
                                    $delay = $delay + $auto_action_time_array[1] * 1000;
                                }
                            }
                            $end_delay = $delay;
                            if ( $end_delay > 0 ) {
                                $end_delay = $end_delay + 1000;
                            }
                            $cta_index = 'additional-' . $index;
                            $additional_autoactions_js[] = [
                                'index'       => $index,
                                'end_delay'   => $end_delay,
                                'start_delay' => $start_delay,
                                'is_videojs'  => webinarignition_should_use_videojs( $webinar_data ),
                            ];
                        }
                    }
                }
                // else{
                // 	$cta_position_default = 'outer';
                // 	$cta_position_allowed = 'outer';
                // 	$cta_position_overlay_allowed = 'overlay';
                // 	$additional_autoactions = array();
                // }
            }
            $globalOffset = 0;
            if ( 'evergreen' !== $webinar_type ) {
                // live webinar
                $timeStampNow = time();
                $webinarDateTime = $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time;
                $webinar_timezone = ( !empty( $webinar_data->webinar_timezone ) ? $webinar_data->webinar_timezone : 'UTC' );
                $date_picked = DateTime::createFromFormat( 'm-d-Y H:i', $webinarDateTime, new DateTimeZone($webinar_timezone) );
                $too_late_lockout_minutes = ( !empty( $webinar_data->too_late_lockout_minutes ) ? (int) $webinar_data->too_late_lockout_minutes * 60 : 3600 );
                $date_picked_timestamp = ( is_object( $date_picked ) ? $date_picked->getTimestamp() : 0 );
                $offset = $timeStampNow - $date_picked_timestamp;
                if ( 0 > $offset ) {
                    $offset = 0;
                }
                if ( !empty( $offset ) ) {
                    $globalOffset = $offset * 1000;
                }
            }
            $tracking_tags_timeouts = array();
            if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && !empty( $lead_id ) && !empty( $tracking_tags_settings ) && !$is_replay_page ) {
                foreach ( $tracking_tags_settings as $tracking_tag ) {
                    if ( empty( $tracking_tag['time'] ) || empty( $tracking_tag['name'] ) ) {
                        continue;
                    }
                    $time = $tracking_tag['time'];
                    $name = $tracking_tag['name'];
                    $slug = ( empty( $tracking_tag['slug'] ) ? '' : $tracking_tag['slug'] );
                    $pixel = ( empty( $tracking_tag['pixel'] ) ? '' : $tracking_tag['pixel'] );
                    $timedActionArray = explode( ':', $time );
                    $minutes = $timedActionArray[0];
                    if ( !is_numeric( $minutes ) ) {
                        $minutes = 0;
                    } else {
                        $minutes = (int) $minutes;
                    }
                    $seconds = 0;
                    if ( !empty( $timedActionArray[1] ) ) {
                        $seconds = $timedActionArray[1];
                        if ( !is_numeric( $seconds ) ) {
                            $seconds = 0;
                        } else {
                            $seconds = (int) $seconds;
                        }
                    }
                    $timedAction = $minutes * 60 + $seconds;
                    if ( empty( $timedAction ) ) {
                        continue;
                    }
                    $timedAction = $timedAction * 1000;
                    $time_array = array(
                        'timeout' => $timedAction,
                        'time'    => $time,
                        'name'    => $name,
                        'slug'    => $slug,
                    );
                    if ( !empty( $pixel ) ) {
                        $time_array['pixel'] = $pixel;
                    }
                    $tracking_tags_timeouts[] = $time_array;
                }
            }
            wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
                'webinar'               => $webinar_data,
                'autoVideoLength'       => ( isset( $webinar_data->auto_video_length ) ? absint( $webinar_data->auto_video_length ) : 0 ),
                'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                'additionalAutoactions' => $additional_autoactions_js,
                'additionalCTA'         => ( property_exists( $webinar_data, 'additional_autoactions' ) ? unserialize( $webinar_data->additional_autoactions ) : '' ),
                'trackingTags'          => $tracking_tags_timeouts,
                'leadId'                => esc_html( $lead_id ),
                'webinarType'           => esc_html( $webinar_type ),
                'webinarId'             => absint( $webinar_id ),
                'globalOffset'          => $globalOffset,
                'nonce'                 => wp_create_nonce( 'webinarignition_ajax_nonce' ),
            ) );
            $after_footer_js = array(WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/webinar_inline_js.php');
            if ( 'AUTO' === $webinar_data->webinar_date ) {
                wp_enqueue_script( 'webinarignition_auto_video_inline_js' );
                $lead_id = ( !empty( $leadinfo->ID ) ? $leadinfo->ID : '' );
                $is_auto_login_enabled = wp_validate_boolean( absint( get_option( 'webinarignition_registration_auto_login', 1 ) ) );
                $is_user_logged_in = is_user_logged_in();
                $is_preview_page = WebinarignitionManager::webinarignition_url_is_preview_page();
                $nonce = wp_create_nonce( 'webinarignition_mark_lead_status' );
                $webinar_id = $webinar_data->id;
                $auto_redirect_url = WebinarignitionManager::webinarignition_get_auto_redirect_url( $webinar_data );
                $auto_redirect_delay = ( isset( $webinar_data->auto_redirect_delay ) ? absint( $webinar_data->auto_redirect_delay ) : 0 );
                $individual_offset = ( !empty( $individual_offset ) ? $individual_offset : 0 );
                $lid = ( !empty( $_GET['lid'] ) ? sanitize_text_field( $_GET['lid'] ) : '' );
                $auto_video_length = ( !empty( $webinar_data->auto_video_length ) ? (int) $webinar_data->auto_video_length * 60 * 1000 : 0 );
                $should_use_videojs = webinarignition_should_use_videojs( $webinar_data );
                $languages = array(
                    'Video Player'                                                                                                       => esc_html__( 'Video Player', 'webinar-ignition' ),
                    'Play'                                                                                                               => esc_html__( 'Play', 'webinar-ignition' ),
                    'Pause'                                                                                                              => esc_html__( 'Pause', 'webinar-ignition' ),
                    'Replay'                                                                                                             => esc_html__( 'Replay', 'webinar-ignition' ),
                    'Current Time'                                                                                                       => esc_html__( 'Current Time', 'webinar-ignition' ),
                    'Duration'                                                                                                           => esc_html__( 'Duration', 'webinar-ignition' ),
                    'Remaining Time'                                                                                                     => esc_html__( 'Remaining Time', 'webinar-ignition' ),
                    'LIVE'                                                                                                               => esc_html__( 'LIVE', 'webinar-ignition' ),
                    'Seek to live, currently behind live'                                                                                => esc_html__( 'Seek to live, currently behind live', 'webinar-ignition' ),
                    'Seek to live, currently playing live'                                                                               => esc_html__( 'Seek to live, currently playing live', 'webinar-ignition' ),
                    'Loaded'                                                                                                             => esc_html__( 'Loaded', 'webinar-ignition' ),
                    'Progress'                                                                                                           => esc_html__( 'Progress', 'webinar-ignition' ),
                    'Fullscreen'                                                                                                         => esc_html__( 'Fullscreen', 'webinar-ignition' ),
                    'Non-Fullscreen'                                                                                                     => esc_html__( 'Exit Fullscreen', 'webinar-ignition' ),
                    'Mute'                                                                                                               => esc_html__( 'Mute', 'webinar-ignition' ),
                    'Unmute'                                                                                                             => esc_html__( 'Unmute', 'webinar-ignition' ),
                    'Audio Player'                                                                                                       => esc_html__( 'Audio Player', 'webinar-ignition' ),
                    'Caption Settings Dialog'                                                                                            => esc_html__( 'Caption Settings Dialog', 'webinar-ignition' ),
                    'Close'                                                                                                              => esc_html__( 'Close', 'webinar-ignition' ),
                    'Descriptions'                                                                                                       => esc_html__( 'Descriptions', 'webinar-ignition' ),
                    'Text'                                                                                                               => esc_html__( 'Text', 'webinar-ignition' ),
                    'White'                                                                                                              => esc_html__( 'White', 'webinar-ignition' ),
                    'Black'                                                                                                              => esc_html__( 'Black', 'webinar-ignition' ),
                    'Red'                                                                                                                => esc_html__( 'Red', 'webinar-ignition' ),
                    'Green'                                                                                                              => esc_html__( 'Green', 'webinar-ignition' ),
                    'Blue'                                                                                                               => esc_html__( 'Blue', 'webinar-ignition' ),
                    'Yellow'                                                                                                             => esc_html__( 'Yellow', 'webinar-ignition' ),
                    'Magenta'                                                                                                            => esc_html__( 'Magenta', 'webinar-ignition' ),
                    'Cyan'                                                                                                               => esc_html__( 'Cyan', 'webinar-ignition' ),
                    'Background'                                                                                                         => esc_html__( 'Background', 'webinar-ignition' ),
                    'Window'                                                                                                             => esc_html__( 'Window', 'webinar-ignition' ),
                    'Opacity'                                                                                                            => esc_html__( 'Opacity', 'webinar-ignition' ),
                    'Slider'                                                                                                             => esc_html__( 'Slider', 'webinar-ignition' ),
                    'Volume Level'                                                                                                       => esc_html__( 'Volume Level', 'webinar-ignition' ),
                    'Subtitles'                                                                                                          => esc_html__( 'Subtitles', 'webinar-ignition' ),
                    'Captions'                                                                                                           => esc_html__( 'Captions', 'webinar-ignition' ),
                    'Chapters'                                                                                                           => esc_html__( 'Chapters', 'webinar-ignition' ),
                    'Close Modal Dialog'                                                                                                 => esc_html__( 'Close Modal Dialog', 'webinar-ignition' ),
                    'Descriptions off'                                                                                                   => esc_html__( 'Descriptions off', 'webinar-ignition' ),
                    'Captions off'                                                                                                       => esc_html__( 'Captions off', 'webinar-ignition' ),
                    'Audio Track'                                                                                                        => esc_html__( 'Audio Track', 'webinar-ignition' ),
                    'You aborted the media playback'                                                                                     => esc_html__( 'You aborted the media playback', 'webinar-ignition' ),
                    'A network error caused the media download to fail part-way.'                                                        => esc_html__( 'A network error caused the media download to fail part-way.', 'webinar-ignition' ),
                    'The media could not be loaded, either because the server or network failed or because the format is not supported.' => esc_html__( 'The media could not be loaded, either because the server or network failed or because the format is not supported.', 'webinar-ignition' ),
                    'No compatible source was found for this media.'                                                                     => esc_html__( 'No compatible source was found for this media.', 'webinar-ignition' ),
                    'The media is encrypted and we do not have the keys to decrypt it.'                                                  => esc_html__( 'The media is encrypted and we do not have the keys to decrypt it.', 'webinar-ignition' ),
                    'Play Video'                                                                                                         => esc_html__( 'Play Video', 'webinar-ignition' ),
                    'Close'                                                                                                              => esc_html__( 'Close', 'webinar-ignition' ),
                );
                wp_localize_script( 'webinarignition_auto_video_inline_js', 'webinarParams', array(
                    'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                    'is_auto_login_enabled' => $is_auto_login_enabled,
                    'is_preview_page'       => $is_preview_page,
                    'lead_id'               => $lead_id,
                    'webinar_id'            => $webinar_id,
                    'nonce'                 => $nonce,
                    'auto_redirect_url'     => $auto_redirect_url,
                    'auto_redirect_delay'   => $auto_redirect_delay,
                    'individual_offset'     => 0,
                    'lid'                   => $lid,
                    'is_user_logged_in'     => $is_user_logged_in,
                    'auto_video_length'     => $auto_video_length,
                    'should_use_videojs'    => $should_use_videojs,
                    'languages'             => $languages,
                ) );
            }
            //end if
            wp_add_inline_script( 'webinarignition_after_footer_js', webinarignition_inline_js_file( $after_footer_js, $webinar_data ), 'before' );
            wp_enqueue_script( 'webinarignition_webinar_shared_js' );
            wp_localize_script( 'webinarignition_webinar_shared_js', 'wiJsObj', array(
                'ajaxurl'    => admin_url( 'admin-ajax.php' ),
                'someWrong'  => __( 'Something went wrong', 'webinar-ignition' ),
                'ajax_nonce' => wp_create_nonce( 'webinarignition_ajax_nonce' ),
            ) );
        } elseif ( webinarignition_is_webinar_common_page() ) {
            wp_enqueue_script( 'webinarignition_webinar_cta_js' );
        }
        //end if
    }

}
add_action( 'wp_enqueue_scripts', 'webinarignition_closed_page_scripts', 55 );
if ( !function_exists( 'webinarignition_closed_page_scripts' ) ) {
    function webinarignition_closed_page_scripts() {
        if ( 'closed' === get_query_var( 'webinarignition_page' ) || isset( $_GET['unsubnextwebinar'] ) || isset( $_GET['unsuballwebinar'] ) ) {
            $webinar_data = get_query_var( 'webinar_data' );
            $webinarignition_page = get_query_var( 'webinarignition_page' );
            if ( $webinar_data && $webinarignition_page ) {
                extract( (array) webinarignition_get_global_templates_vars( $webinar_data ) );
                //phpcs:ignore
            }
            // <head> css
            wp_enqueue_style( 'webinarignition_normalize' );
            wp_enqueue_style( 'webinarignition_foundation' );
            wp_enqueue_style( 'webinarignition_main' );
            wp_enqueue_style( 'webinarignition_font-awesome' );
            wp_enqueue_style( 'webinarignition_countdown' );
            wp_enqueue_style( 'webinarignition_webinar' );
            wp_enqueue_style( 'webinarignition_cdres' );
            wp_enqueue_style( 'webinarignition_countdown_replay' );
            // <head> js
            wp_enqueue_script( 'webinarignition_cookie_js' );
            wp_enqueue_script( 'webinarignition_js_countdown' );
            $webinar_data = get_query_var( 'webinar_data' );
            if ( $webinar_data ) {
                if ( isset( $webinar_data->webinar_ld_share ) && 'off' !== $webinar_data->webinar_ld_share ) {
                    wp_enqueue_script( 'webinarignition_linkedin_js' );
                }
            }
            wp_enqueue_script( 'webinarignition_after_footer_js' );
        }
    }

}
add_action( 'wp_enqueue_scripts', 'webinarignition_replay_page_scripts', 55 );
if ( !function_exists( 'webinarignition_replay_page_scripts' ) ) {
    function webinarignition_replay_page_scripts() {
        $webinar_page = get_query_var( 'webinarignition_page' );
        if ( 'replay' === $webinar_page || 'replay_page' === $webinar_page || 'preview-replay' === $webinar_page ) {
            $webinar_data = get_query_var( 'webinar_data' );
            $webinarignition_page = get_query_var( 'webinarignition_page' );
            if ( empty( $webinar_data ) || empty( $webinarignition_page ) ) {
                return;
            }
            extract( (array) webinarignition_get_global_templates_vars( $webinar_data ) );
            //phpcs:ignore
            $webinar_page_template = WebinarignitionManager::webinarignition_get_webinar_page_template( $webinar_data );
            if ( 'modern' === $webinar_page_template ) {
                wp_enqueue_style( 'webinarignition_webinar_modern' );
                wp_enqueue_style( 'webinarignition_webinar_shared' );
            } else {
                wp_enqueue_style( 'webinarignition_bootstrap' );
                wp_enqueue_style( 'webinarignition_foundation' );
                wp_enqueue_style( 'webinarignition_font-awesome' );
                wp_enqueue_style( 'webinarignition_main' );
                wp_enqueue_style( 'webinarignition_webinar' );
                wp_enqueue_style( 'webinarignition_webinar_shared' );
                wp_add_inline_style( 'webinarignition_webinar', webinarignition_inline_css_file( WEBINARIGNITION_PATH . 'inc/lp/css/webinar_css.php', $webinar_data ) );
            }
            if ( webinarignition_should_use_videojs( $webinar_data ) ) {
                wp_enqueue_style( 'webinarignition_video_css' );
            }
            if ( isset( $webinar_data->custom_replay_css ) && !empty( $webinar_data->custom_replay_css ) ) {
                wp_add_inline_style( 'webinarignition_webinar', esc_html( $webinar_data->custom_replay_css ) );
            }
            /** ====================================
            			 *  HEAD JS
            				==================================== */
            wp_enqueue_script( 'webinarignition_js_countdown' );
            wp_enqueue_script( 'webinarignition_webinar_data_after_js' );
            wp_enqueue_script( 'webinarignition_cookie_js' );
            wp_enqueue_script( 'webinarignition_webinar_data_after_js' );
            $is_auto_login_enabled = absint( get_option( 'webinarignition_registration_auto_login', 1 ) ) === 1;
            // Check if user is logged in
            $is_user_logged_in = is_user_logged_in();
            // Get query var
            $webinarignition_page = get_query_var( 'webinarignition_page' );
            wp_localize_script( 'webinarignition_webinar_data_after_js', 'webinar_data', array(
                'ajax_url'                        => admin_url( 'admin-ajax.php' ),
                'security'                        => wp_create_nonce( 'webinarignition_ajax_nonce' ),
                'webinar_id'                      => ( isset( $webinar_id ) ? $webinar_id : '' ),
                'input_get'                       => ( isset( $input_get ) ? $input_get : '' ),
                'webinar_date'                    => ( isset( $webinar_data->webinar_date ) ? $webinar_data->webinar_date : '' ),
                'lead_timezone'                   => ( isset( $leadinfo->lead_timezone ) ? $leadinfo->lead_timezone : '' ),
                'webinar_timezone'                => ( isset( $webinar_data->webinar_timezone ) ? $webinar_data->webinar_timezone : '' ),
                'auto_replay'                     => ( isset( $webinar_data->auto_replay ) ? $webinar_data->auto_replay : '' ),
                'date_picked_and_live'            => ( isset( $leadinfo->date_picked_and_live ) ? $leadinfo->date_picked_and_live : '' ),
                'replay_cd_date'                  => ( isset( $webinar_data->replay_cd_date ) ? $webinar_data->replay_cd_date : '' ),
                'replay_optional'                 => ( isset( $webinar_data->replay_optional ) ? $webinar_data->replay_optional : '' ),
                'cd_months'                       => ( isset( $webinar_data->cd_months ) ? $webinar_data->cd_months : '' ),
                'cd_weeks'                        => ( isset( $webinar_data->cd_weeks ) ? $webinar_data->cd_weeks : '' ),
                'cd_days'                         => ( isset( $webinar_data->cd_days ) ? $webinar_data->cd_days : '' ),
                'cd_hours'                        => ( isset( $webinar_data->cd_hours ) ? $webinar_data->cd_hours : '' ),
                'cd_minutes'                      => ( isset( $webinar_data->cd_minutes ) ? $webinar_data->cd_minutes : '' ),
                'cd_seconds'                      => ( isset( $webinar_data->cd_seconds ) ? $webinar_data->cd_seconds : '' ),
                'webinar_source_toggle'           => ( isset( $webinar_data->webinar_source_toggle ) ? $webinar_data->webinar_source_toggle : '' ),
                'webinar_iframe_source'           => ( isset( $webinar_data->webinar_iframe_source ) ? $webinar_data->webinar_iframe_source : '' ),
                'auto_action'                     => ( isset( $webinar_data->auto_action ) ? $webinar_data->auto_action : '' ),
                'auto_action_time'                => ( isset( $webinar_data->auto_action_time ) ? $webinar_data->auto_action_time : '' ),
                'auto_action_time_end'            => ( isset( $webinar_data->auto_action_time_end ) ? $webinar_data->auto_action_time_end : '' ),
                'additional_autoactions'          => ( property_exists( $webinar_data, 'additional_autoactions' ) && isset( $webinar_data->additional_autoactions ) ? maybe_unserialize( $webinar_data->additional_autoactions ) : '' ),
                'replay_order_time'               => ( isset( $webinar_data->replay_order_time ) ? $webinar_data->replay_order_time : '' ),
                'is_auto_login_enabled'           => $is_auto_login_enabled,
                'is_user_logged_in'               => $is_user_logged_in,
                'webinarignition_page'            => $webinarignition_page,
                'wp_timezone_string'              => wp_timezone_string(),
                'current_time'                    => current_time( 'mysql' ),
                'webinar_timezone_offset'         => get_option( 'gmt_offset' ),
                'webinar_is_multiple_cta_enabled' => WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ),
            ) );
            if ( isset( $webinar_data->custom_replay_js ) && !empty( $webinar_data->custom_replay_js ) ) {
                wp_add_inline_script( 'webinarignition_cookie_js', '(function ($) {' . $webinar_data->custom_replay_js . '})(jQuery);' );
            }
            if ( isset( $webinar_data->webinar_ld_share ) && 'off' !== $webinar_data->webinar_ld_share ) {
                wp_enqueue_script( 'webinarignition_linkedin_js' );
            }
            if ( webinarignition_should_use_videojs( $webinar_data ) ) {
                wp_enqueue_script( 'webinarignition_video_js' );
            }
            /** ====================================
            			 *  FOOTER JS
            				==================================== */
            wp_enqueue_script( 'webinarignition_before_footer_js' );
            if ( 'hide' !== trim( $webinar_data->webinar_qa ) ) {
                wp_add_inline_script( 'webinarignition_before_footer_js', webinarignition_inline_js_file( array(WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php', WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php'), $webinar_data ), 'before' );
            }
            if ( 'modern' === $webinar_page_template ) {
                wp_enqueue_script( 'webinarignition_webinar_modern_js' );
                wp_enqueue_script( 'webinarignition_backup_js' );
            }
            wp_enqueue_script( 'webinarignition_webinar_cta_js' );
            wp_enqueue_script( 'webinarignition_after_footer_js' );
            $after_footer_js = array();
            if ( 'AUTO' === $webinar_data->webinar_date ) {
                wp_enqueue_script( 'webinarignition_auto_video_inline_js' );
                $lead_id = ( !empty( $leadinfo->ID ) ? $leadinfo->ID : '' );
                $is_auto_login_enabled = wp_validate_boolean( absint( get_option( 'webinarignition_registration_auto_login', 1 ) ) );
                $is_user_logged_in = is_user_logged_in();
                $is_preview_page = WebinarignitionManager::webinarignition_url_is_preview_page();
                $nonce = wp_create_nonce( 'webinarignition_mark_lead_status' );
                $webinar_id = $webinar_data->id;
                $auto_redirect_url = WebinarignitionManager::webinarignition_get_auto_redirect_url( $webinar_data );
                $auto_redirect_delay = ( isset( $webinar_data->auto_redirect_delay ) ? absint( $webinar_data->auto_redirect_delay ) : 0 );
                $individual_offset = ( !empty( $individual_offset ) ? $individual_offset : 0 );
                $lid = ( !empty( $_GET['lid'] ) ? sanitize_text_field( $_GET['lid'] ) : '' );
                $auto_video_length = ( !empty( $webinar_data->auto_video_length ) ? (int) $webinar_data->auto_video_length * 60 * 1000 : 0 );
                $should_use_videojs = webinarignition_should_use_videojs( $webinar_data );
                $languages = array(
                    'Video Player'                                                                                                       => esc_html__( 'Video Player', 'webinar-ignition' ),
                    'Play'                                                                                                               => esc_html__( 'Play', 'webinar-ignition' ),
                    'Pause'                                                                                                              => esc_html__( 'Pause', 'webinar-ignition' ),
                    'Replay'                                                                                                             => esc_html__( 'Replay', 'webinar-ignition' ),
                    'Current Time'                                                                                                       => esc_html__( 'Current Time', 'webinar-ignition' ),
                    'Duration'                                                                                                           => esc_html__( 'Duration', 'webinar-ignition' ),
                    'Remaining Time'                                                                                                     => esc_html__( 'Remaining Time', 'webinar-ignition' ),
                    'LIVE'                                                                                                               => esc_html__( 'LIVE', 'webinar-ignition' ),
                    'Seek to live, currently behind live'                                                                                => esc_html__( 'Seek to live, currently behind live', 'webinar-ignition' ),
                    'Seek to live, currently playing live'                                                                               => esc_html__( 'Seek to live, currently playing live', 'webinar-ignition' ),
                    'Loaded'                                                                                                             => esc_html__( 'Loaded', 'webinar-ignition' ),
                    'Progress'                                                                                                           => esc_html__( 'Progress', 'webinar-ignition' ),
                    'Fullscreen'                                                                                                         => esc_html__( 'Fullscreen', 'webinar-ignition' ),
                    'Non-Fullscreen'                                                                                                     => esc_html__( 'Exit Fullscreen', 'webinar-ignition' ),
                    'Mute'                                                                                                               => esc_html__( 'Mute', 'webinar-ignition' ),
                    'Unmute'                                                                                                             => esc_html__( 'Unmute', 'webinar-ignition' ),
                    'Audio Player'                                                                                                       => esc_html__( 'Audio Player', 'webinar-ignition' ),
                    'Caption Settings Dialog'                                                                                            => esc_html__( 'Caption Settings Dialog', 'webinar-ignition' ),
                    'Close'                                                                                                              => esc_html__( 'Close', 'webinar-ignition' ),
                    'Descriptions'                                                                                                       => esc_html__( 'Descriptions', 'webinar-ignition' ),
                    'Text'                                                                                                               => esc_html__( 'Text', 'webinar-ignition' ),
                    'White'                                                                                                              => esc_html__( 'White', 'webinar-ignition' ),
                    'Black'                                                                                                              => esc_html__( 'Black', 'webinar-ignition' ),
                    'Red'                                                                                                                => esc_html__( 'Red', 'webinar-ignition' ),
                    'Green'                                                                                                              => esc_html__( 'Green', 'webinar-ignition' ),
                    'Blue'                                                                                                               => esc_html__( 'Blue', 'webinar-ignition' ),
                    'Yellow'                                                                                                             => esc_html__( 'Yellow', 'webinar-ignition' ),
                    'Magenta'                                                                                                            => esc_html__( 'Magenta', 'webinar-ignition' ),
                    'Cyan'                                                                                                               => esc_html__( 'Cyan', 'webinar-ignition' ),
                    'Background'                                                                                                         => esc_html__( 'Background', 'webinar-ignition' ),
                    'Window'                                                                                                             => esc_html__( 'Window', 'webinar-ignition' ),
                    'Opacity'                                                                                                            => esc_html__( 'Opacity', 'webinar-ignition' ),
                    'Slider'                                                                                                             => esc_html__( 'Slider', 'webinar-ignition' ),
                    'Volume Level'                                                                                                       => esc_html__( 'Volume Level', 'webinar-ignition' ),
                    'Subtitles'                                                                                                          => esc_html__( 'Subtitles', 'webinar-ignition' ),
                    'Captions'                                                                                                           => esc_html__( 'Captions', 'webinar-ignition' ),
                    'Chapters'                                                                                                           => esc_html__( 'Chapters', 'webinar-ignition' ),
                    'Close Modal Dialog'                                                                                                 => esc_html__( 'Close Modal Dialog', 'webinar-ignition' ),
                    'Descriptions off'                                                                                                   => esc_html__( 'Descriptions off', 'webinar-ignition' ),
                    'Captions off'                                                                                                       => esc_html__( 'Captions off', 'webinar-ignition' ),
                    'Audio Track'                                                                                                        => esc_html__( 'Audio Track', 'webinar-ignition' ),
                    'You aborted the media playback'                                                                                     => esc_html__( 'You aborted the media playback', 'webinar-ignition' ),
                    'A network error caused the media download to fail part-way.'                                                        => esc_html__( 'A network error caused the media download to fail part-way.', 'webinar-ignition' ),
                    'The media could not be loaded, either because the server or network failed or because the format is not supported.' => esc_html__( 'The media could not be loaded, either because the server or network failed or because the format is not supported.', 'webinar-ignition' ),
                    'No compatible source was found for this media.'                                                                     => esc_html__( 'No compatible source was found for this media.', 'webinar-ignition' ),
                    'The media is encrypted and we do not have the keys to decrypt it.'                                                  => esc_html__( 'The media is encrypted and we do not have the keys to decrypt it.', 'webinar-ignition' ),
                    'Play Video'                                                                                                         => esc_html__( 'Play Video', 'webinar-ignition' ),
                    'Close'                                                                                                              => esc_html__( 'Close', 'webinar-ignition' ),
                );
                wp_localize_script( 'webinarignition_auto_video_inline_js', 'webinarParams', array(
                    'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                    'is_auto_login_enabled' => $is_auto_login_enabled,
                    'is_preview_page'       => $is_preview_page,
                    'lead_id'               => $lead_id,
                    'webinar_id'            => $webinar_id,
                    'nonce'                 => $nonce,
                    'auto_redirect_url'     => $auto_redirect_url,
                    'auto_redirect_delay'   => $auto_redirect_delay,
                    'individual_offset'     => 0,
                    'lid'                   => $lid,
                    'is_user_logged_in'     => $is_user_logged_in,
                    'auto_video_length'     => $auto_video_length,
                    'should_use_videojs'    => $should_use_videojs,
                    'languages'             => $languages,
                ) );
            }
            $webinar_data = ( !empty( $webinar_data ) ? $webinar_data : get_query_var( 'webinar_data' ) );
            $webinarignition_page = ( !empty( $webinarignition_page ) ? $webinarignition_page : get_query_var( 'webinarignition_page' ) );
            $webinar_type = ( 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live' );
            $tracking_tags_settings = ( isset( $webinar_data->tracking_tags ) ? $webinar_data->tracking_tags : array() );
            $lead_id = ( !empty( $leadinfo->ID ) ? $leadinfo->ID : '' );
            $is_replay_page = 'replay_custom' === $webinarignition_page || 'preview-replay' === $webinarignition_page || 'replay_page' === $webinarignition_page;
            $additional_autoactions_js = array();
            if ( 'evergreen' === $webinar_type ) {
                if ( 'time' === $webinar_data->auto_action && (!empty( $webinar_data->webinar_iframe_source ) || !empty( $webinar_data->auto_video_url ) || !empty( $webinar_data->auto_video_url2 )) ) {
                    $additional_autoactions = array();
                    if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && property_exists( $webinar_data, 'additional_autoactions' ) && !empty( $webinar_data->additional_autoactions ) ) {
                        $additional_autoactions = maybe_unserialize( $webinar_data->additional_autoactions );
                    }
                    // Additional logic to build $additional_autoactions_js
                    if ( 'evergreen' === $webinar_type ) {
                        if ( 'time' === trim( $webinar_data->auto_action ) && (!empty( $webinar_data->webinar_iframe_source ) || !empty( $webinar_data->auto_video_url ) || !empty( $webinar_data->auto_video_url2 )) ) {
                            $cta_position_default = 'outer';
                            $cta_position_allowed = 'outer';
                            $cta_position_overlay_allowed = 'overlay';
                            $additional_autoactions = array();
                            if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && property_exists( $webinar_data, 'additional_autoactions' ) && !empty( $webinar_data->additional_autoactions ) ) {
                                $additional_autoactions = maybe_unserialize( $webinar_data->additional_autoactions );
                            }
                            if ( !empty( $webinar_data->auto_action_time ) && (!empty( $webinar_data->auto_action_copy ) || !empty( $webinar_data->auto_action_btn_copy ) && !empty( $webinar_data->auto_action_url )) ) {
                                $webinar_main_auto_action = [
                                    'auto_action_time'     => $webinar_data->auto_action_time,
                                    'auto_action_time_end' => '',
                                    'auto_action_copy'     => '',
                                    'auto_action_btn_copy' => '',
                                    'auto_action_url'      => '',
                                    'replay_order_color'   => '#6BBA40',
                                ];
                                if ( !empty( $webinar_data->auto_action_time_end ) ) {
                                    $webinar_main_auto_action['auto_action_time_end'] = $webinar_data->auto_action_time_end;
                                }
                                if ( !empty( $webinar_data->replay_order_color ) ) {
                                    $webinar_main_auto_action['replay_order_color'] = $webinar_data->replay_order_color;
                                }
                                if ( !empty( $webinar_data->auto_action_copy ) ) {
                                    $webinar_main_auto_action['auto_action_copy'] = $webinar_data->auto_action_copy;
                                }
                                if ( !empty( $webinar_data->auto_action_btn_copy ) && !empty( $webinar_data->auto_action_url ) ) {
                                    $webinar_main_auto_action['auto_action_btn_copy'] = $webinar_data->auto_action_btn_copy;
                                    $webinar_main_auto_action['auto_action_url'] = $webinar_data->auto_action_url;
                                }
                                if ( !empty( $webinar_data->cta_position ) ) {
                                    $cta_position_default = $webinar_data->cta_position;
                                }
                                if ( $cta_position_default === $cta_position_allowed ) {
                                    $webinar_main_auto_action['cta_position'] = 'outer';
                                } else {
                                    $webinar_main_auto_action['cta_position'] = 'overlay';
                                }
                                if ( is_array( $additional_autoactions ) && !empty( $additional_autoactions ) ) {
                                    $additional_autoactions = array_merge( [$webinar_main_auto_action], $additional_autoactions );
                                } else {
                                    $additional_autoactions[] = $webinar_main_auto_action;
                                }
                            }
                            ksort( $additional_autoactions );
                            foreach ( $additional_autoactions as $index => $additional_autoaction ) {
                                $cta_position = $cta_position_default;
                                if ( !empty( $additional_autoaction['cta_position'] ) ) {
                                    $cta_position = $additional_autoaction['cta_position'];
                                }
                                if ( !empty( $additional_autoaction['auto_action_time'] ) ) {
                                    $auto_action_time_array = explode( ':', $additional_autoaction['auto_action_time'] );
                                    $delay = 10;
                                    if ( !empty( $auto_action_time_array[0] ) ) {
                                        $delay = $delay + $auto_action_time_array[0] * 60000;
                                    }
                                    if ( !empty( $auto_action_time_array[1] ) ) {
                                        $delay = $delay + absint( $auto_action_time_array[1] ) * 1000;
                                    }
                                    $start_delay = $delay;
                                    if ( $start_delay > 10 ) {
                                        $start_delay = $start_delay + 1000;
                                    }
                                    if ( empty( $additional_autoaction['auto_action_time_end'] ) ) {
                                        $delay = 0;
                                    } else {
                                        $auto_action_time_array = explode( ':', $additional_autoaction['auto_action_time_end'] );
                                        $delay = 0;
                                        if ( !empty( $auto_action_time_array[0] ) ) {
                                            $delay = $delay + $auto_action_time_array[0] * 60000;
                                        }
                                        if ( !empty( $auto_action_time_array[1] ) ) {
                                            $delay = $delay + $auto_action_time_array[1] * 1000;
                                        }
                                    }
                                    $end_delay = $delay;
                                    if ( $end_delay > 0 ) {
                                        $end_delay = $end_delay + 1000;
                                    }
                                    $cta_index = 'additional-' . $index;
                                    $additional_autoactions_js[] = [
                                        'index'       => $index,
                                        'end_delay'   => $end_delay,
                                        'start_delay' => $start_delay,
                                        'is_videojs'  => webinarignition_should_use_videojs( $webinar_data ),
                                    ];
                                }
                            }
                        }
                    }
                    $globalOffset = 0;
                    if ( 'evergreen' !== $webinar_type ) {
                        // live webinar
                        $timeStampNow = time();
                        $webinarDateTime = $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time;
                        $webinar_timezone = ( !empty( $webinar_data->webinar_timezone ) ? $webinar_data->webinar_timezone : 'UTC' );
                        $date_picked = DateTime::createFromFormat( 'm-d-Y H:i', $webinarDateTime, new DateTimeZone($webinar_timezone) );
                        $too_late_lockout_minutes = ( !empty( $webinar_data->too_late_lockout_minutes ) ? (int) $webinar_data->too_late_lockout_minutes * 60 : 3600 );
                        $date_picked_timestamp = ( is_object( $date_picked ) ? $date_picked->getTimestamp() : 0 );
                        $offset = $timeStampNow - $date_picked_timestamp;
                        if ( 0 > $offset ) {
                            $offset = 0;
                        }
                        if ( !empty( $offset ) ) {
                            $globalOffset = $offset * 1000;
                        }
                    }
                }
            }
            $tracking_tags_timeouts = array();
            if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && !empty( $lead_id ) && !empty( $tracking_tags_settings ) && !$is_replay_page ) {
                foreach ( $tracking_tags_settings as $tracking_tag ) {
                    if ( empty( $tracking_tag['time'] ) || empty( $tracking_tag['name'] ) ) {
                        continue;
                    }
                    $time = $tracking_tag['time'];
                    $name = $tracking_tag['name'];
                    $slug = ( empty( $tracking_tag['slug'] ) ? '' : $tracking_tag['slug'] );
                    $pixel = ( empty( $tracking_tag['pixel'] ) ? '' : $tracking_tag['pixel'] );
                    $timedActionArray = explode( ':', $time );
                    $minutes = $timedActionArray[0];
                    if ( !is_numeric( $minutes ) ) {
                        $minutes = 0;
                    } else {
                        $minutes = (int) $minutes;
                    }
                    $seconds = 0;
                    if ( !empty( $timedActionArray[1] ) ) {
                        $seconds = $timedActionArray[1];
                        if ( !is_numeric( $seconds ) ) {
                            $seconds = 0;
                        } else {
                            $seconds = (int) $seconds;
                        }
                    }
                    $timedAction = $minutes * 60 + $seconds;
                    if ( empty( $timedAction ) ) {
                        continue;
                    }
                    $timedAction = $timedAction * 1000;
                    $time_array = array(
                        'timeout' => $timedAction,
                        'time'    => $time,
                        'name'    => $name,
                        'slug'    => $slug,
                    );
                    if ( !empty( $pixel ) ) {
                        $time_array['pixel'] = $pixel;
                    }
                    $tracking_tags_timeouts[] = $time_array;
                }
                //end foreach
            }
            //end if
            wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
                'webinar'               => $webinar_data,
                'autoVideoLength'       => ( isset( $webinar_data->auto_video_length ) ? absint( $webinar_data->auto_video_length ) : 0 ),
                'ajaxurl'               => admin_url( 'admin-ajax.php' ),
                'additionalAutoactions' => $additional_autoactions_js,
                'additionalCTA'         => ( property_exists( $webinar_data, 'additional_autoactions' ) ? unserialize( $webinar_data->additional_autoactions ) : '' ),
                'trackingTags'          => $tracking_tags_timeouts,
                'leadId'                => esc_html( $lead_id ),
                'webinarType'           => esc_html( $webinar_type ),
                'webinarId'             => absint( $webinar_id ),
                'globalOffset'          => ( isset( $globalOffset ) ? $globalOffset : null ),
                'nonce'                 => wp_create_nonce( 'webinarignition_ajax_nonce' ),
            ) );
            wp_add_inline_script( 'webinarignition_after_footer_js', webinarignition_inline_js_file( $after_footer_js, $webinar_data ), 'before' );
            wp_enqueue_script( 'webinarignition_webinar_shared_js' );
            wp_localize_script( 'webinarignition_webinar_shared_js', 'wiJsObj', array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' ),
                'someWrong' => __( 'Something went wrong', 'webinar-ignition' ),
            ) );
        }
        wp_enqueue_style( 'webinarignition_webinar_modern' );
        wp_enqueue_script( 'webinarignition_webinar_modern_js' );
        wp_enqueue_script( 'webinarignition_backup_js' );
    }

}
add_action( 'wp_enqueue_scripts', 'webinarignition_countdown_page_scripts', 55 );
if ( !function_exists( 'webinarignition_countdown_page_scripts' ) ) {
    function webinarignition_countdown_page_scripts() {
        if ( 'countdown' === get_query_var( 'webinarignition_page' ) ) {
            $webinar_data = get_query_var( 'webinar_data' );
            $webinarignition_page = get_query_var( 'webinarignition_page' );
            if ( $webinar_data && $webinarignition_page ) {
                extract( (array) webinarignition_get_countdown_templates_vars( $webinar_data ) );
                //phpcs:ignore
            }
            // <head> css
            wp_enqueue_style( 'webinarignition_head_style' );
            wp_enqueue_style( 'webinarignition_normalize' );
            wp_enqueue_style( 'webinarignition_foundation' );
            wp_enqueue_style( 'webinarignition_main' );
            wp_enqueue_style( 'webinarignition_font-awesome' );
            wp_enqueue_style( 'webinarignition_countdown' );
            wp_enqueue_style( 'webinarignition_webinar' );
            wp_enqueue_style( 'webinarignition_cdres' );
            wp_add_inline_style( 'webinarignition_cdres', webinarignition_inline_css_file( WEBINARIGNITION_PATH . 'inc/lp/css/webinar_css.php', $webinar_data ) );
            if ( isset( $webinar_data->custom_webinar_css ) && !empty( $webinar_data->custom_webinar_css ) ) {
                wp_add_inline_style( 'webinarignition_cdres', $webinar_data->custom_webinar_css );
            }
            // <head> js
            wp_enqueue_script( 'webinarignition_cookie_js' );
            wp_enqueue_script( 'webinarignition_js_countdown' );
            if ( 'AUTO' === $webinar_data->webinar_date ) {
                $livedate = explode( ' ', $leadinfo->date_picked_and_live );
                $expire = $livedate[0];
                $ex_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
                $ex_year = $ex_date[0];
                $ex_month = (int) $ex_date[1];
                $ex_day = $ex_date[2];
            } else {
                $expire = $webinar_data->webinar_date;
                $ex_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
                $ex_year = $ex_date[2];
                $ex_month = (int) $ex_date[0];
                $ex_day = $ex_date[1];
            }
            $time = ( 'AUTO' === $webinar_data->webinar_date ? $livedate[1] : $webinar_data->webinar_start_time );
            $time = gmdate( 'H:i', strtotime( $time ) );
            $ex_time = explode( ':', $time );
            $ex_hr = $ex_time[0];
            $ex_min = $ex_time[1];
            $ex_sec = '00';
            $timezone_to_create = ( empty( $leadinfo->lead_timezone ) ? 'Asia/Beirut' : $leadinfo->lead_timezone );
            $tz = new DateTimeZone(( 'AUTO' === $webinar_data->webinar_date ? $timezone_to_create : $webinar_data->webinar_timezone ));
            $utc_offset = $tz->getOffset( new DateTime() ) / 3600;
            $webinar_url = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'webinar' );
            $webinar_url = add_query_arg( 'live', '', $webinar_url );
            $lead_id = sanitize_text_field( $input_get['lid'] );
            $webinar_url = add_query_arg( 'lid', $lead_id, $webinar_url );
            if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
                $webinar_url = add_query_arg( md5( $webinar_data->paid_code ), '', $webinar_url );
            }
            $webinar_url = add_query_arg( 'watch_type', 'live', $webinar_url );
            $labels = array(
                __( 'Years', 'webinar-ignition' ),
                __( 'Months', 'webinar-ignition' ),
                __( 'Weeks', 'webinar-ignition' ),
                __( 'Days', 'webinar-ignition' ),
                __( 'Hours', 'webinar-ignition' ),
                __( 'Minutes', 'webinar-ignition' ),
                __( 'Seconds', 'webinar-ignition' )
            );
            $labels1 = array(
                __( 'Year', 'webinar-ignition' ),
                __( 'Month', 'webinar-ignition' ),
                __( 'Week', 'webinar-ignition' ),
                __( 'Day', 'webinar-ignition' ),
                __( 'Hour', 'webinar-ignition' ),
                __( 'Minute', 'webinar-ignition' ),
                __( 'Second', 'webinar-ignition' )
            );
            wp_localize_script( 'webinarignition_js_countdown', 'webinarData', array(
                'webinar_date'     => $webinar_data->webinar_date,
                'utc_offset'       => $utc_offset,
                'ex_year'          => $ex_year,
                'ex_month'         => $ex_month,
                'ex_day'           => $ex_day,
                'ex_hr'            => $ex_hr,
                'ex_min'           => $ex_min,
                'ex_sec'           => $ex_sec,
                'is_preview'       => $is_preview,
                'webinar_url'      => $webinar_url,
                'admin_ajax_url'   => admin_url( 'admin-ajax.php' ),
                'ajax_nonce'       => wp_create_nonce( 'webinarignition_ajax_nonce' ),
                'webinar_id'       => $webinar_data->id,
                'paid_status'      => $webinar_data->paid_status,
                'paid_webinar_url' => ( isset( $webinar_data->paid_webinar_url ) ? esc_url( $webinar_data->paid_webinar_url ) : '' ),
                'labels'           => $labels,
                'labels1'          => $labels1,
            ) );
            wp_enqueue_script( 'webinarignition_after_footer_js' );
            $webinar_timezone = webinarignition_get_webinar_timezone( $webinar_data, null );
            if ( webinarignition_is_auto( $webinar_data ) && $lead != false ) {
                $expire = explode( ' ', $lead->date_picked_and_live )[0];
                $time = explode( ' ', $lead->date_picked_and_live )[1];
                $auto_tz = webinarignition_get_tzOffset( $lead->lead_timezone );
            } else {
                $expire = $webinar_data->webinar_date;
                $webinar_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
                $webinar_date_key_2 = ( isset( $webinar_date[2] ) ? $webinar_date[2] : null );
                $webinar_date_key_1 = ( isset( $webinar_date[1] ) ? $webinar_date[1] : null );
                $expire = "{$webinar_date_key_2}-{$webinar_date[0]}-{$webinar_date_key_1}";
                $time = gmdate( 'H:i', strtotime( ( isset( $webinar_data->webinar_start_time ) ? $webinar_data->webinar_start_time : null ) ) );
            }
            $ex_date = ( strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire ) );
            $ex_time = explode( ':', $time );
            $ex_year = esc_attr( $ex_date[0] ) ?? '0';
            $ex_date_key_1 = ( isset( $ex_date[1] ) ? $ex_date[1] : null );
            $ex_month = esc_attr( $ex_date_key_1 - 1 ) ?? '0';
            $ex_date_key_2 = ( isset( $ex_date[2] ) ? $ex_date[2] : '0' );
            $ex_day = esc_attr( $ex_date_key_2 ) ?? '0';
            $ex_hr = esc_attr( $ex_time[0] ) ?? '0';
            $ex_min = esc_attr( str_replace( array(' ', 'AM', 'PM'), '', $ex_time[1] ) ) ?? '0';
            $tz_offset = esc_attr( ( isset( $auto_tz ) ? $auto_tz : null ) );
            wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
                'webinar_date'     => $webinar_data->webinar_date,
                'tzOffset'         => $tz_offset,
                'exYear'           => $ex_year,
                'exMonth'          => $ex_month,
                'exDay'            => $ex_day,
                'exHr'             => $ex_hr,
                'exMin'            => $ex_min,
                'exSec'            => $ex_sec,
                'isPreview'        => $is_preview,
                'webinar_url'      => $webinar_url,
                'admin_ajax_url'   => admin_url( 'admin-ajax.php' ),
                'ajax_nonce'       => wp_create_nonce( 'webinarignition_ajax_nonce' ),
                'webinar_id'       => $webinar_data->id,
                'paid_status'      => $webinar_data->paid_status,
                'paid_webinar_url' => ( isset( $webinar_data->paid_webinar_url ) ? esc_url( $webinar_data->paid_webinar_url ) : '' ),
                'labels'           => $labels,
                'labels1'          => $labels1,
            ) );
            wp_add_inline_script( 'webinarignition_after_footer_js', webinarignition_inline_js_file( '', '' ) );
        }
    }

}
add_action( 'wp_enqueue_scripts', 'webinarignition_console_page_scripts', PHP_INT_MAX );
if ( !function_exists( 'webinarignition_console_page_scripts' ) ) {
    function webinarignition_console_page_scripts() {
        if ( 'console' === get_query_var( 'webinarignition_page' ) ) {
            $assets = WEBINARIGNITION_URL . 'inc/lp/';
            wp_enqueue_style(
                'webinarignition_foundation',
                $assets . 'css/foundation.css',
                array(),
                WEBINARIGNITION_VERSION
            );
            wp_enqueue_style(
                'webinarignition_stream',
                $assets . 'css/stream.css',
                array(),
                WEBINARIGNITION_VERSION
            );
            wp_enqueue_style(
                'webinarignition_font-awesome',
                $assets . 'css/font-awesome.min.css',
                array(),
                WEBINARIGNITION_VERSION
            );
            wp_enqueue_style(
                'webinarignition_colorpicker_css',
                WEBINARIGNITION_URL . 'css/colorpicker.css',
                array(),
                WEBINARIGNITION_VERSION
            );
            wp_enqueue_style(
                'bootstrap',
                $assets . 'css/bootstrap.min.css',
                array('webinarignition_foundation', 'webinarignition_stream', 'webinarignition_colorpicker_css'),
                '3.4.1'
            );
            // wp_enqueue_style( 'summernote', $assets . 'css/summernote.min.css', array( 'bootstrap' ), '0.8.18' );
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script(
                'bootstrap',
                $assets . 'js/bootstrap.min.js',
                array('jquery'),
                WEBINARIGNITION_VERSION,
                true
            );
            // wp_enqueue_script( 'summernote', $assets . 'js/summernote.min.js', array( 'jquery', 'bootstrap' ), '0.8.18', true );
            wp_enqueue_script(
                'jquery.dataTables',
                $assets . 'js/jquery.dataTables.min.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'bootbox',
                $assets . 'js/bootbox.min.js',
                array('jquery', 'bootstrap'),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'webinarignition_search',
                $assets . 'js/search.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'webinarignition_cookie',
                $assets . 'js/cookie.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'webinarignition_webinardata_after_js',
                $assets . 'js/webinar-data-after.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'webinarignition_polling',
                $assets . 'js/polling.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'webinarignition_colorpicker',
                WEBINARIGNITION_URL . 'inc/js/colorpicker.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
            wp_enqueue_script(
                'webinarignition_colorconversion',
                WEBINARIGNITION_URL . 'inc/js/colorconversion.js',
                array(),
                WEBINARIGNITION_VERSION,
                true
            );
        }
    }

}
if ( !function_exists( 'webinarignition_download_csv' ) ) {
    function webinarignition_download_csv(  $webinar_data, $webinar_id  ) {
        if ( empty( $_GET['csv_key'] ) ) {
            exit( 'Access denied' );
        }
        $csv_key = $_GET['csv_key'];
        if ( $csv_key !== $webinar_data->csv_key ) {
            exit( 'Access denied' );
        }
        global $wpdb;
        $table_db_name = $wpdb->prefix . 'webinarignition_questions';
        // Secure the query for selecting questions with status 'live' or 'done'
        $results = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM {$table_db_name} WHERE app_id = %s AND status IN (%s, %s)",
            $webinar_id,
            'live',
            'done'
        ), OBJECT );
        // Secure the query for selecting answers
        $answers = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %s AND status = %s", $webinar_id, 'answer' ), OBJECT );
        $answers_by_qid = array();
        if ( !empty( $answers ) ) {
            foreach ( $answers as $answer ) {
                if ( !empty( $answer->parent_id ) ) {
                    $answers_by_qid[$answer->parent_id][] = $answer;
                }
            }
        }
        // CSV Header:
        header( 'Content-type: application/text' );
        header( 'Content-Disposition: attachment; filename=export_questions.csv' );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );
        echo esc_html__( 'Full Name, E-mail, Created, Status, Question, Answer', 'webinar-ignition' );
        echo "\n";
        foreach ( $results as $result ) {
            if ( 'deleted' === $result->status ) {
                continue;
            }
            echo esc_html( $result->name );
            echo ',';
            echo esc_html( $result->email );
            echo ',';
            echo esc_html( str_replace( ',', ' -', $result->created ) );
            echo ',';
            echo esc_html( $result->status );
            echo ',';
            echo '"' . esc_html( $result->question ) . '"';
            echo ',';
            if ( !empty( $answers_by_qid[$result->ID] ) ) {
                $answer_q = $answers_by_qid[$result->ID][0];
                if ( !empty( $answer_q->answer_text ) ) {
                    echo '"' . esc_html( $answer_q->answer_text ) . '"';
                } elseif ( !empty( $answer_q->answer ) ) {
                    echo '"' . esc_html( $answer_q->answer ) . '"';
                } else {
                    echo '';
                }
                unset($answers_by_qid[$result->ID][0]);
            } elseif ( !empty( $result->answer_text ) ) {
                echo '"' . esc_html( $result->answer_text ) . '"';
            } elseif ( !empty( $result->answer ) ) {
                echo '"' . esc_html( $result->answer ) . '"';
            } else {
                echo '';
            }
            echo "\n";
            if ( !empty( $answers_by_qid[$result->ID] ) ) {
                foreach ( $answers_by_qid[$result->ID] as $answer ) {
                    echo '';
                    echo ',';
                    echo '';
                    echo ',';
                    echo '';
                    echo ',';
                    echo '';
                    echo ',';
                    echo '';
                    echo ',';
                    if ( !empty( $answer->answer_text ) ) {
                        echo '"' . esc_html( $answer->answer_text ) . '"';
                    } elseif ( !empty( $answer->answer ) ) {
                        echo '"' . esc_html( $answer->answer ) . '"';
                    } else {
                        echo '';
                    }
                    echo "\n";
                }
                //end foreach
            }
            //end if
        }
        //end foreach
    }

}
// endregion
if ( !function_exists( 'webinarignition_do_late_lockout_redirect' ) ) {
    function webinarignition_do_late_lockout_redirect(  $webinar_data  ) {
        // TODO - Move conditional settings to WebinarignitionManager::webinarignition_get_webinar_data method
        $is_too_late_lockout_enabled = WebinarignitionPowerups::webinarignition_is_too_late_lockout_enabled( $webinar_data );
        if ( $is_too_late_lockout_enabled && (isset( $webinar_data->too_late_lockout ) && $webinar_data->too_late_lockout == 'show') && !empty( $webinar_data->too_late_lockout_minutes ) ) {
            $timeStampNow = time();
            $webinarDateTime = $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time;
            if ( $webinar_data->webinar_date == 'AUTO' ) {
                if ( !empty( $webinar_data->auto_timezone_custom && 'fixed' === $webinar_data->auto_timezone_type ) ) {
                    $date_picked = new DateTime($leadinfo->date_picked_and_live, new DateTimeZone($webinar_data->auto_timezone_custom));
                } else {
                    $date_picked = new DateTime($leadinfo->date_picked_and_live);
                }
            } else {
                $date_picked = DateTime::createFromFormat( 'm-d-Y H:i', $webinarDateTime, new DateTimeZone($webinar_data->webinar_timezone) );
            }
            $too_late_lockout_minutes = $webinar_data->too_late_lockout_minutes * 60;
            $date_picked_timestamp = $date_picked->getTimestamp();
            $cutoffTime = $date_picked_timestamp + $too_late_lockout_minutes;
            if ( $timeStampNow > $cutoffTime ) {
                if ( 'registration_page' == $webinar_data->latecomer_redirection_type ) {
                    session_start();
                    $_SESSION['latecomer'] = true;
                    wp_safe_redirect( $webinar_data->webinar_permalink );
                    exit;
                } elseif ( !empty( $webinar_data->too_late_redirect_url ) ) {
                    wp_safe_redirect( $webinar_data->too_late_redirect_url );
                    exit;
                } else {
                    wp_safe_redirect( $webinar_data->webinar_permalink );
                    exit;
                }
            }
        }
        //end if
    }

}
//end if
/**
 * Overriding Advanced Iframe plugin single content page
 *
 * This function will override the single content page if post type is "ai_content_page"
 * to avoid printing any unnecessary page template contents (i.e. header, footer, sidebars etc.)
 * for WebinarIgnition CTAs
 */
if ( !function_exists( 'wi_aiframe_single_template_cb' ) ) {
    add_filter( 'single_template', 'wi_aiframe_single_template_cb' );
    function wi_aiframe_single_template_cb(  $single  ) {
        global $post;
        if ( !empty( $post ) && isset( $post->post_type ) && 'ai_content_page' === $post->post_type ) {
            $single_template_file_path = WEBINARIGNITION_PATH . 'inc/lp/partials/single-ai-content-page-template.php';
            if ( file_exists( $single_template_file_path ) ) {
                return apply_filters( 'wi_aiframe_single_template_path', $single_template_file_path );
            }
        }
        return $single;
    }

}
if ( !function_exists( 'webinarignition_footer' ) ) {
    function webinarignition_footer(  $webinar_data  ) {
        if ( empty( $webinar_data->footer_code ) ) {
            return;
        }
        global $allowedposttags;
        $tags_footer_code = webinarignition_extract_tags( $webinar_data->footer_code );
        $merged_tags = webinarignition_merge_allowed_tags( $allowedposttags, $tags_footer_code );
        echo wp_kses( $webinar_data->footer_code, $merged_tags );
    }

}
if ( !function_exists( 'webinarignition_extract_tags' ) ) {
    function webinarignition_extract_tags(  $html  ) {
        $dom = new DOMDocument();
        // Suppress warnings due to malformed HTML
        libxml_use_internal_errors( true );
        $dom->loadHTML( $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        libxml_clear_errors();
        $result = [];
        foreach ( $dom->getElementsByTagName( '*' ) as $element ) {
            $tagName = $element->nodeName;
            $attributes = [];
            foreach ( $element->attributes as $attr ) {
                $attributes[$attr->name] = true;
                // Set attribute name as key with 'true' as value
            }
            // If the tag doesn't exist in the result, initialize it
            if ( !isset( $result[$tagName] ) ) {
                $result[$tagName] = $attributes;
            } else {
                // Merge attributes if the tag already exists
                $result[$tagName] = array_merge( $result[$tagName], $attributes );
            }
        }
        return $result;
    }

}
if ( !function_exists( 'webinarignition_merge_allowed_tags' ) ) {
    function webinarignition_merge_allowed_tags(  $wp_tags, $custom_tags  ) {
        $mergedResult = $wp_tags;
        foreach ( $custom_tags as $tag => $attributes ) {
            if ( !isset( $mergedResult[$tag] ) ) {
                $mergedResult[$tag] = $attributes;
            } else {
                $mergedResult[$tag] = array_merge( $mergedResult[$tag], $attributes );
            }
        }
        ksort( $mergedResult );
        return $mergedResult;
    }

}