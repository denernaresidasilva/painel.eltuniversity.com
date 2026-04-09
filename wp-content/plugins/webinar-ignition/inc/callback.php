<?php

defined( 'ABSPATH' ) || exit;
// TODO - Separate Backend and Frontend callbacks
// ADD NEW LEAD
add_action( 'wp_ajax_nopriv_webinarignition_add_lead', 'webinarignition_add_lead_callback' );
add_action( 'wp_ajax_webinarignition_add_lead', 'webinarignition_add_lead_callback' );
function webinarignition_add_lead_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $post_input = array();
    $post_input['name'] = sanitize_text_field( filter_input( INPUT_POST, 'name' ) );
    $post_input['firstName'] = sanitize_text_field( filter_input( INPUT_POST, 'firstName' ) );
    $post_input['email'] = ( isset( $_POST['email'] ) ? sanitize_email( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ) : '' );
    $post_input['phone'] = sanitize_text_field( filter_input( INPUT_POST, 'phone' ) );
    $post_input['source'] = sanitize_text_field( filter_input( INPUT_POST, 'source', FILTER_UNSAFE_RAW ) );
    $post_input['gdpr_data'] = sanitize_text_field( filter_input( INPUT_POST, 'gdpr_data', FILTER_UNSAFE_RAW ) );
    $post_input['ip'] = sanitize_text_field( filter_input( INPUT_POST, 'ip', FILTER_UNSAFE_RAW ) );
    $post_input['timezone'] = sanitize_text_field( filter_input( INPUT_POST, 'timezone', FILTER_UNSAFE_RAW ) );
    $post_input['id'] = absint( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    $post_input['id'] = ( empty( $post_input['id'] ) && !empty( filter_input( INPUT_POST, 'campaignID', FILTER_SANITIZE_NUMBER_INT ) ) ? absint( filter_input( INPUT_POST, 'campaignID', FILTER_SANITIZE_NUMBER_INT ) ) : $post_input['id'] );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );
    if ( !empty( $webinar_data->webinar_lang ) ) {
        $applang = $webinar_data->webinar_lang;
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
        $webinar_data->time_format = get_option( 'time_format', 'H:i' );
    }
    $time_format = $webinar_data->time_format;
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    global $wpdb;
    $is_ajax = false;
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        $is_ajax = true;
    }
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    if ( $is_lead_protected ) {
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT hash_ID AS ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $post_input['email'], $post_input['id'] ) );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    } else {
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $post_input['email'], $post_input['id'] ) );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( $lead ) {
        $lead_id = ( isset( $lead->hash_ID ) ? $lead->hash_ID : $lead->ID );
        $is_unsubscribed_to_all = update_option( 'lead_data_restricted_mails' . $post_input['email'], false );
        $webinar_restricted_emails = get_option( 'webinar_data_restricted_mails' . $post_input['id'], array() );
        if ( in_array( $post_input['email'], $webinar_restricted_emails ) ) {
            $key = array_search( $post_input['email'], $webinar_restricted_emails );
            if ( $key !== false ) {
                unset($webinar_restricted_emails[$key]);
            }
            update_option( 'webinar_data_restricted_mails' . $post_input['id'], $webinar_restricted_emails );
        }
        $deleted = $wpdb->delete( $table_db_name, [
            'email'  => $post_input['email'],
            'app_id' => $post_input['id'],
        ], ['%s', '%s'] );
        $lead = NULL;
    }
    if ( $lead ) {
        wp_send_json( $lead->ID );
    }
    $data = array(
        'app_id'              => intval( $post_input['id'] ),
        'name'                => $post_input['name'],
        'email'               => $post_input['email'],
        'phone'               => $post_input['phone'],
        'trk1'                => $post_input['source'],
        'trk3'                => $post_input['ip'],
        'lead_browser_and_os' => $post_input['timezone'],
        'event'               => 'No',
        'replay'              => 'No',
        'created'             => gmdate( 'F j, Y' ),
        'gdpr_data'           => $post_input['gdpr_data'],
    );
    $wpdb->insert( $table_db_name, $data );
    $out = $wpdb->insert_id;
    update_option( 'webinarignition_lead_confirmed_' . $out, false );
    $hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );
    $wpdb->update( $table_db_name, array(
        'hash_ID' => $hash_ID,
    ), array(
        'ID' => $out,
    ) );
    $wiRegForm_data = ( !empty( filter_input(
        INPUT_POST,
        'wiRegForm',
        FILTER_DEFAULT,
        FILTER_REQUIRE_ARRAY
    ) ) ? filter_input(
        INPUT_POST,
        'wiRegForm',
        FILTER_DEFAULT,
        FILTER_REQUIRE_ARRAY
    ) : array() );
    $lead_meta = array();
    foreach ( $wiRegForm_data as $field_name => $field ) {
        $field_label = rtrim( sanitize_text_field( $field['label'] ), '*' );
        if ( $field_name == 'full_name' ) {
            $salutation = ( isset( $wiRegForm_data['optSalutation'] ) ? sanitize_text_field( $wiRegForm_data['optSalutation']['value'] ) : '' );
            $first_name = ( isset( $wiRegForm_data['optFName'] ) ? sanitize_text_field( $wiRegForm_data['optFName']['value'] ) : $wiRegForm_data['optName']['value'] );
            $last_name = ( isset( $wiRegForm_data['optLName'] ) ? sanitize_text_field( $wiRegForm_data['optLName']['value'] ) : '' );
            $field_value = trim( implode( ' ', array_filter( [$salutation, $first_name, $last_name] ) ) );
            // If salutation is
        } else {
            $field_value = sanitize_text_field( $field['value'] );
        }
        $lead_meta[$field_name] = array(
            'label' => $field_label,
            'value' => $field_value,
        );
    }
    if ( !empty( $lead_meta ) ) {
        $lead_meta = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm',
            serialize( $lead_meta ),
            'live'
        );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm_' . $post_input['id'],
            serialize( $lead_meta ),
            'live'
        );
        /**
         * Action Hook: webinarignition_live_lead_added
         *
         * @param int $webinar_id Webinar ID for which the lead was added
         * @param int $lead_id Lead ID which was added
         * @param array $lead_metadata Associated lead metadata
         */
        $webhook_lead_data = array();
        foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
            if ( is_array( $lead_meta_value ) ) {
                $webhook_lead_data[$lead_meta_key] = $lead_meta_value['value'];
            }
        }
        // do_action( 'webinarignition_lead_status_changed', 'attended', $out, absint( $post_input['id'] ) );
    }
    //end if
    //new lead function start
    if ( class_exists( 'Wp2leadsItmWebinarignitionRedirection' ) ) {
        $webinar_to_map = get_option( 'wp2leads_itm_webinarignition_webinar_to_map', array() );
        if ( empty( $webinar_to_map[$post_input['id']] ) ) {
            do_action( 'webinarignition_live_lead_added_email', $out, $post_input['id'] );
        }
    } else {
        // Class doesn't exist, handle gracefully
        do_action( 'webinarignition_live_lead_added_email', $out, $post_input['id'] );
    }
    // registration email has been disabled in notification settings
    if ( 'off' === $webinar_data->email_signup ) {
        WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ) . "\n{$lead_details_string}\n\n" . __( 'Not sending registration email (DISABLED)', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
        if ( $is_lead_protected ) {
            echo esc_html( $hash_ID );
        } else {
            echo esc_attr( $out );
        }
        die;
    }
    $lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";
    if ( isset( $post_input['phone'] ) && 'undefined' !== $post_input['phone'] ) {
        $lead_details_string .= "Phone: {$post_input['phone']}";
    }
    WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ) . "\n\${$lead_details_string}\n\n" . __( 'Firing registration email', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
    //new lead function end
    if ( !empty( $webinar_data->get_registration_notices_state ) && 'show' === $webinar_data->get_registration_notices_state && !empty( $webinar_data->registration_notice_email ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {
        $subj = __( 'New Registration For', 'webinar-ignition' ) . ' ' . $webinar_data->webinar_desc . ' ' . __( 'By', 'webinar-ignition' ) . ' ' . $post_input['name'];
        $attendeeName = $post_input['name'];
        $emailBody = $emailHead;
        if ( !empty( $lead_meta ) ) {
            foreach ( $lead_meta as $lead_field_key => $lead_field_data ) {
                if ( 'optName' === $lead_field_key && '#firstlast#' === $lead_field_data['value'] ) {
                    continue;
                    // Skip first last tag
                }
                $emailBody .= "<br><br>{$lead_field_data['label']}: {$lead_field_data['value']}";
            }
        }
        $emailBody .= '</html>';
        try {
            wp_mail(
                $webinar_data->registration_notice_email,
                $subj,
                $emailBody,
                $headers
            );
        } catch ( Exception $e ) {
            echo esc_html( $e->getMessage() );
        }
    }
    //end if
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
    if ( false !== $is_ajax ) {
        if ( $is_lead_protected ) {
            echo esc_html( $hash_ID );
        } else {
            echo esc_attr( $out );
        }
        die;
    }
    if ( $is_lead_protected ) {
        return $hash_ID;
    } else {
        return $out;
    }
}

add_action(
    'webinarignition_live_lead_added_email',
    'webinarignition_live_lead_added_email_callback',
    10,
    4
);
function webinarignition_live_lead_added_email_callback(  $out, $webinar_id  ) {
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    update_option( 'webinarignition_lead_confirmed_' . $out, true );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $leadInfo = webinarignition_get_lead_info( $out, $webinar_data, false );
    $time_format = $webinar_data->time_format;
    $hash_ID = $leadInfo->hash_ID;
    if ( !empty( $webinar_data->webinar_lang ) ) {
        $applang = $webinar_data->webinar_lang;
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    do_action(
        'webinarignition_lead_added',
        absint( $webinar_id ),
        $out,
        $leadInfo
    );
    do_action(
        'webinarignition_live_lead_added',
        absint( $webinar_id ),
        $out,
        $leadInfo
    );
    do_action( 'webinarignition_lead_created', $out, $table_db_name );
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    if ( !empty( $webinar_data->templates_version ) || !empty( $webinar_data->use_new_email_signup_template ) && 'yes' === $webinar_data->use_new_email_signup_template ) {
        // use new templates
        $webinar_data->emailheading = $webinar_data->email_signup_heading;
        $webinar_data->emailpreview = $webinar_data->email_signup_preview;
        $webinar_data->bodyContent = $webinar_data->email_signup_body;
        $webinar_data->footerContent = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ? $webinar_data->local_email_signup_footer : '' );
        $email = new WI_Emails();
        $emailBody = $email->webinarignition_build_email( $webinar_data );
    } else {
        // This is an old webinar, created before this version
        $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
        $emailBody = $emailHead;
        $emailBody .= $webinar_data->email_signup_body;
        $emailBody .= '</html>';
    }
    $localized_date = webinarignition_get_localized_date( $webinar_data );
    $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
    // Replace
    if ( $is_lead_protected ) {
        $ics_lid = $hash_ID;
    } else {
        $ics_lid = $out;
    }
    $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
    $googleCalendarURL = add_query_arg( array(
        'googlecalendar' => '1',
        'lid'            => $ics_lid,
        'webinar'        => '',
    ), $thankyou_URL );
    ob_start();
    ?>
	<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php 
    echo esc_url_raw( $googleCalendarURL );
    ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
		<i class="icon-google-plus"></i>
		<?php 
    webinarignition_display( $webinar_data->ty_calendar_google, __( 'Add to Google Calendar', 'webinar-ignition' ) );
    ?>
	</a>
	<?php 
    $webinar_email_timezone = ( $leadInfo->lead_browser_and_os ? $leadInfo->lead_browser_and_os : $webinar_data->webinar_timezone );
    $original_time = $webinar_data->webinar_start_time;
    // e.g., '2025-05-07 15:00:00'
    $original_timezone = new DateTimeZone($webinar_data->webinar_timezone);
    // e.g., 'Asia/Riyadh'
    $target_timezone = new DateTimeZone($webinar_email_timezone);
    // e.g., 'Asia/Karachi'
    // Create DateTime object in original timezone
    $date = new DateTime($original_time, $original_timezone);
    // Convert to target timezone
    $date->setTimezone( $target_timezone );
    // Format using provided format
    $converted_time = $date->format( $time_format );
    $wi_calendar_url = ob_get_clean();
    $webinar_duration = ( isset( $webinar_data->webinar_start_duration ) ? sanitize_text_field( $webinar_data->webinar_start_duration ) : 60 );
    $duration_txt = esc_html( sprintf( 
        /* translators: %s: Webinar duration in minutes. */
        __( 'Duration: %s minutes', 'webinar-ignition' ),
        $webinar_duration
     ) );
    $emailBody = str_replace( '{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz(
        $converted_time,
        $time_format,
        $webinar_email_timezone,
        false,
        $timeonly
    ) . ' ' . $wi_calendar_url . '<br>' . $duration_txt, $emailBody );
    $send_mail = true;
    // Format current datetime
    $current_datetime = new DateTime('now', new DateTimeZone($webinar_data->webinar_timezone));
    $webinar_datetime = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time, new DateTimeZone($webinar_data->webinar_timezone) );
    // Calculate difference in seconds
    $diff_in_seconds = $webinar_datetime->getTimestamp() - $current_datetime->getTimestamp();
    // If webinar is within 5 minutes, use current datetime
    if ( $diff_in_seconds <= 300 && $diff_in_seconds > 0 ) {
        $send_mail = false;
    }
    // Add Unsubscribe Text
    $unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
	<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
	<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
	</p>';
    $webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );
    // Check if the setting value is 1
    if ( 1 === absint( $webinarignition_unsubscribe_links ) ) {
        $emailBody .= $unsubscribe_text;
    }
    $emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $emailBody );
    $email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
    $event_data = [
        'id'      => $ics_lid,
        'webdata' => $webinar_data,
    ];
    $ics_file = generate_ics_file( $event_data );
    $unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
    $unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
    $unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $unsubscribe_next_header_url );
    $unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $unsubscribe_all_header_url );
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
        'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
        'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>'
    );
    $attachments = [$ics_file];
    webinarignition_test_smtp_options();
    if ( $send_mail ) {
        try {
            if ( !wp_mail(
                $leadInfo->email,
                $email_signup_sbj,
                $emailBody,
                $headers,
                $attachments
            ) ) {
                WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$leadInfo->email}", WebinarIgnition_Logs::LIVE_EMAIL );
            } else {
                WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinar-ignition' ), $webinar_id, WebinarIgnition_Logs::LIVE_EMAIL );
            }
        } catch ( Exception $e ) {
            WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$leadInfo->email}", WebinarIgnition_Logs::LIVE_EMAIL );
        }
    }
}

// ADD NEW EVERGREEN (auto) LEAD
add_action( 'wp_ajax_nopriv_webinarignition_get_lead_auto', 'webinarignition_get_lead_auto_callback' );
add_action( 'wp_ajax_webinarignition_get_lead_auto', 'webinarignition_get_lead_auto_callback' );
function webinarignition_get_lead_auto_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Only get the required input values
    $lead_id = absint( filter_input( INPUT_GET, 'lid', FILTER_SANITIZE_NUMBER_INT ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    $table_db_name = esc_sql( $table_db_name );
    // Prepare and execute the query
    $lead = $wpdb->get_row( $wpdb->prepare( "SELECT `app_id`, `name`, `email`, `phone`, `date_picked_and_live`, `lead_timezone` \n\t\tFROM {$table_db_name} \n\t\tWHERE ID = %d", $lead_id ), OBJECT );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    if ( empty( $lead ) ) {
        // Sanitize input values
        $hash_id = sanitize_text_field( filter_input( INPUT_GET, 'lid', FILTER_UNSAFE_RAW ) );
        // Prepare and execute the query
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT `app_id`, `name`, `email`, `phone`, `date_picked_and_live`, `lead_timezone` \n\t\t\tFROM {$table_db_name} \n\t\t\tWHERE hash_ID = %s", $hash_id ), OBJECT );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( is_object( $lead ) ) {
        if ( !isset( $lead->lname ) && strrpos( $lead->name, ' ' ) ) {
            $lead->lname = explode( ' ', $lead->name, 2 );
            $lead->name = $lead->lname[0];
            $lead->lname = $lead->lname[1];
        }
        $webinar = WebinarignitionManager::webinarignition_get_webinar_data( $lead->app_id );
        $arCustomDateFormat = ( isset( $webinar->ar_custom_date_format ) ? $webinar->ar_custom_date_format : 'not-set' );
        $webinarignition_webinar_timestamp = strtotime( $lead->date_picked_and_live );
        $arWebinarDate = webinarignition_format_date_for_ar_service( $arCustomDateFormat, $webinarignition_webinar_timestamp );
        $lead->webinar_date = $arWebinarDate;
        $lead->webinar_time = gmdate( 'g:i A', strtotime( $lead->date_picked_and_live ) );
        $lead->lead_timezone = $lead->lead_timezone . ' (UTC' . webinarignition_get_timezone_offset_by_name( $lead->lead_timezone ) . ')';
        echo wp_json_encode( $lead );
        exit;
    }
    $object = new stdClass();
    $object->message = 'lead not found';
    echo wp_json_encode( $object );
    exit;
}

// ADD NEW EVERGREEN (auto) LEAD
add_action( 'wp_ajax_nopriv_webinarignition_add_lead_auto', 'webinarignition_add_lead_auto_callback' );
add_action( 'wp_ajax_webinarignition_add_lead_auto', 'webinarignition_add_lead_auto_callback' );
function webinarignition_add_lead_auto_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $post_input = array();
    $post_input['name'] = ( isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null );
    $post_input['email'] = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null );
    $post_input['phone'] = ( isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : null );
    $post_input['id'] = ( isset( $_POST['id'] ) ? sanitize_text_field( $_POST['id'] ) : null );
    $post_input['timezone'] = ( isset( $_POST['timezone'] ) ? sanitize_text_field( $_POST['timezone'] ) : null );
    $post_input['date'] = ( isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : null );
    $post_input['time'] = ( isset( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : null );
    $post_input['gdpr_data'] = ( isset( $_POST['gdpr_data'] ) ? sanitize_text_field( $_POST['gdpr_data'] ) : null );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );
    // Delete existing lead
    if ( !empty( $webinar_data ) ) {
        $delete_lead_id = webinarignition_existing_lead_id( $post_input['id'], $post_input['email'] );
        if ( !empty( $delete_lead_id ) ) {
            webinarignition_delete_lead_by_id( $delete_lead_id );
        }
    }
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
        $webinar_data->time_format = get_option( 'time_format', 'H:i' );
    }
    $time_format = $webinar_data->time_format;
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y' );
    if ( !empty( $post_input['timezone'] ) ) {
        if ( preg_match( '/UTC([+-]\\d+(\\.\\d+)?)/', $post_input['timezone'], $matches ) ) {
            // Convert offset to valid "+HH:MM" format
            $offset = floatval( $matches[1] );
            $hours = floor( $offset );
            $minutes = ($offset - $hours) * 60;
            $timezone_offset_new = sprintf( '%+03d:%02d', $hours, abs( $minutes ) );
        } else {
            // Assume the input is already a valid timezone identifier
            $timezone_offset_new = $post_input['timezone'];
        }
        $lead_timezone = new DateTimeZone($timezone_offset_new);
    } else {
        $lead_timezone = get_option( 'timezone_string' );
    }
    // Get info
    $webinarLength = $webinar_data->auto_video_length;
    $setCheckInstant = '';
    $instant = 'no';
    if ( 'instant_access' === $post_input['date'] ) {
        $current_time = new DateTime('now', $lead_timezone);
        $todaysDate = $current_time->format( 'Y-m-d' );
        $todaysTime = $current_time->format( 'H:i' );
        // They choose to watch replay
        $time = gmdate( 'H:i', strtotime( $todaysTime . '+0 hours' ) );
        $post_input['date'] = $todaysDate;
        $post_input['time'] = $time;
        $instant = 'yes';
        $setCheckInstant = 'yes';
    }
    $is_ty_page_skipped = false;
    if ( 'yes' === $instant ) {
        $is_ty_page_skipped = isset( $webinar_data->skip_instant_acces_confirm_page ) && 'yes' === $webinar_data->skip_instant_acces_confirm_page;
    }
    // Get & Set Dates For Emails...
    $dpl = $post_input['date'] . ' ' . $post_input['time'];
    $fmt = 'Y-m-d H:i';
    $date_object = DateTime::createFromFormat( 'd-Y-m h:i A', $dpl );
    if ( $date_object ) {
        $date_picked_and_live = $date_object->format( $fmt );
    } else {
        $date_picked_and_live = gmdate( $fmt, strtotime( $dpl ) );
    }
    $date_1_day_before = gmdate( $fmt, strtotime( $dpl . ' -1 days' ) );
    $date_1_hour_before = gmdate( $fmt, strtotime( $dpl . ' -1 hours' ) );
    $date_after_live = gmdate( $fmt, strtotime( $dpl . ' +' . $webinarLength . ' minutes' ) );
    $date_1_day_after = gmdate( $fmt, strtotime( $dpl . ' +1 days' ) );
    if ( 'time_zone_instant' === $post_input['date'] ) {
        $current_time = new DateTime('now', $lead_timezone);
        $todaysDate = $current_time->format( 'Y-m-d' );
        $todaysTime = $current_time->format( 'H:i' );
        $webinar_timezone_selected = $lead_timezone;
    }
    $wiRegForm_data = ( !empty( $_POST['wiRegForm'] ) ? $_POST['wiRegForm'] : array() );
    $lead_meta = array();
    foreach ( $wiRegForm_data as $field_name => $field ) {
        $field_label = rtrim( sanitize_text_field( $field['label'] ), '*' );
        if ( $field_name == 'full_name' ) {
            $salutation = ( isset( $wiRegForm_data['optSalutation'] ) ? sanitize_text_field( $wiRegForm_data['optSalutation']['value'] ) : '' );
            $first_name = ( isset( $wiRegForm_data['optFName'] ) ? sanitize_text_field( $wiRegForm_data['optFName']['value'] ) : $wiRegForm_data['optName']['value'] );
            $last_name = ( isset( $wiRegForm_data['optLName'] ) ? sanitize_text_field( $wiRegForm_data['optLName']['value'] ) : '' );
            $field_value = trim( implode( ' ', array_filter( [$salutation, $first_name, $last_name] ) ) );
            // If salutation is
        } else {
            $field_value = sanitize_text_field( $field['value'] );
        }
        $lead_meta[$field_name] = array(
            'label' => $field_label,
            'value' => $field_value,
        );
    }
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    $d = array(
        'app_id'                     => $post_input['id'],
        'name'                       => $post_input['name'],
        'email'                      => $post_input['email'],
        'phone'                      => ( !empty( $post_input['phone'] ) ? $post_input['phone'] : '' ),
        'lead_timezone'              => ( !empty( $post_input['timezone'] ) ? $post_input['timezone'] : '' ),
        'trk1'                       => 'Optin',
        'trk3'                       => ( !empty( $post_input['ip'] ) ? $post_input['ip'] : '' ),
        'trk8'                       => $instant,
        'event'                      => ( 'yes' === $instant && $is_ty_page_skipped ? 'Yes' : 'No' ),
        'replay'                     => ( 'yes' === $instant && $is_ty_page_skipped ? 'Yes' : 'No' ),
        'created'                    => gmdate( 'F j, Y' ),
        'date_picked_and_live'       => $date_picked_and_live,
        'date_1_day_before'          => $date_1_day_before,
        'date_1_hour_before'         => $date_1_hour_before,
        'date_after_live'            => $date_after_live,
        'date_1_day_after'           => $date_1_day_after,
        'date_picked_and_live_check' => $setCheckInstant,
        'date_1_day_before_check'    => $setCheckInstant,
        'date_1_hour_before_check'   => $setCheckInstant,
        'date_after_live_check'      => $setCheckInstant,
        'gdpr_data'                  => ( !empty( $post_input['gdpr_data'] ) ? $post_input['gdpr_data'] : '' ),
    );
    $wpdb->insert( $table_db_name, $d );
    $is_unsubscribed_to_all = update_option( 'lead_data_restricted_mails' . $post_input['email'], false );
    $webinar_restricted_emails = get_option( 'webinar_data_restricted_mails' . $post_input['id'], array() );
    if ( in_array( $post_input['email'], $webinar_restricted_emails ) ) {
        $key = array_search( $post_input['email'], $webinar_restricted_emails );
        if ( $key !== false ) {
            unset($webinar_restricted_emails[$key]);
        }
        update_option( 'webinar_data_restricted_mails' . $post_input['id'], $webinar_restricted_emails );
    }
    $out = $wpdb->insert_id;
    $hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );
    update_option( 'webinarignition_lead_confirmed_' . $out, false );
    $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET hash_ID = %s WHERE ID = %d", $hash_ID, $out ) );
    if ( !empty( $lead_meta ) ) {
        $lead_meta = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm',
            serialize( $lead_meta ),
            'evergreen'
        );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm_' . $post_input['id'],
            serialize( $lead_meta ),
            'evergreen'
        );
        /**
         * Action Hook: webinarignition_lead_added
         *
         * @param int $webinar_id Webinar ID for which the lead was added
         * @param int $lead_id Lead ID which was added
         * @param array $lead_metadata Associated lead metadata
         */
        $webhook_lead_data = array();
        foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
            if ( is_array( $lead_meta_value ) ) {
                $webhook_lead_data[$lead_meta_key] = $lead_meta_value['value'];
            }
        }
        if ( 'yes' === $instant ) {
            // Trigger status change hooks
            // do_action( 'webinarignition_lead_status_changed', 'attended', $out, absint( $post_input['id'] ) );
        }
    }
    //end if
    $cookieID = $out;
    $lead_id = $out;
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    if ( $is_lead_protected ) {
        $lead_id = $hash_ID;
    }
    echo esc_attr( $lead_id );
    $lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";
    if ( !empty( $post_input['phone'] ) ) {
        $lead_details_string .= "Phone: {$post_input['phone']}";
    }
    WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
    WebinarIgnition_Logs::add( $lead_details_string, $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
    if ( class_exists( 'Wp2leadsItmWebinarignitionRedirection' ) ) {
        $webinar_to_map = get_option( 'wp2leads_itm_webinarignition_webinar_to_map', array() );
        if ( empty( $webinar_to_map[$post_input['id']] ) ) {
            do_action( 'webinarignition_evergreen_lead_added_email', absint( $out ), $post_input['id'] );
        }
    } else {
        // Class doesn't exist, handle gracefully
        do_action( 'webinarignition_evergreen_lead_added_email', absint( $out ), $post_input['id'] );
    }
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
    $send_signup_user_notification = isset( $webinar_data->email_signup ) && 'off' !== $webinar_data->email_signup;
    $send_signup_admin_notification = isset( $webinar_data->get_registration_notices_state ) && 'show' === $webinar_data->get_registration_notices_state;
    // Send new user sign-up notification email to admin
    if ( $send_signup_admin_notification && (isset( $webinar_data->registration_notice_email ) && !empty( $webinar_data->registration_notice_email ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL )) ) {
        WebinarIgnition_Logs::add( __( 'Sending new user sign-up notification email to admin', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
        $subj = __( 'New Registration For', 'webinar-ignition' ) . ' ' . $webinar_data->webinar_desc . ' ' . __( 'By', 'webinar-ignition' ) . ' ' . $post_input['name'];
        $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
        $emailBody = $emailHead;
        if ( !empty( $lead_meta ) ) {
            foreach ( $lead_meta as $lead_field_key => $lead_field_data ) {
                if ( 'optName' === $lead_field_key && '#firstlast#' === $lead_field_data['value'] ) {
                    continue;
                    // Skip firstlast tag
                }
                $emailBody .= "<br><br>{$lead_field_data['label']}: {$lead_field_data['value']}";
            }
        }
        $emailBody .= '</html>';
        try {
            wp_mail(
                $webinar_data->registration_notice_email,
                $subj,
                $emailBody,
                $headers
            );
        } catch ( Exception $e ) {
            echo esc_attr( $e->getMessage() );
        }
    } else {
        WebinarIgnition_Logs::add( __( 'Not sending new user sign-up notification email to admin', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::AUTO_EMAIL );
    }
    //end if
    die;
}

add_action(
    'webinarignition_wp2leads_lead_confirmed',
    'webinarignition_wp2leads_lead_confirmed_callback',
    10,
    2
);
function webinarignition_wp2leads_lead_confirmed_callback(  $out, $webinar_id  ) {
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    if ( WebinarignitionManager::webinarignition_is_auto_webinar( $webinar_data ) ) {
        update_option( 'webinarignition_lead_confirmed_' . $out, true );
        do_action( 'webinarignition_evergreen_lead_added_email', $out, $webinar_id );
    } else {
        update_option( 'webinarignition_lead_confirmed_' . $out, true );
        do_action( 'webinarignition_live_lead_added_email', $out, $webinar_id );
    }
}

add_action(
    'webinarignition_evergreen_lead_added_email',
    'webinarignition_evergreen_lead_added_email_callback',
    10,
    2
);
function webinarignition_evergreen_lead_added_email_callback(  $out, $webinar_id  ) {
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    update_option( 'webinarignition_lead_confirmed_' . $out, true );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $leadInfo = webinarignition_get_lead_info( $out, $webinar_data, false );
    $instant = $leadInfo->trk8;
    $time_format = $webinar_data->time_format;
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y' );
    $hash_ID = $leadInfo->hash_ID;
    do_action(
        'webinarignition_lead_added',
        absint( $webinar_id ),
        $out,
        $leadInfo
    );
    do_action(
        'webinarignition_live_lead_added',
        absint( $webinar_id ),
        $out,
        $leadInfo
    );
    do_action( 'webinarignition_lead_created', $out, $table_db_name );
    if ( !empty( $webinar_data->webinar_lang ) ) {
        $applang = $webinar_data->webinar_lang;
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    $send_signup_user_notification = isset( $webinar_data->email_signup ) && 'off' !== $webinar_data->email_signup;
    /*
    |-------------------------------------------------------------------------------------------
    |  EMAIL SENDING`
    |-------------------------------------------------------------------------------------------
    */
    // Send sign-up email to user
    if ( !$send_signup_user_notification ) {
        WebinarIgnition_Logs::add( __( 'Not sending user sign-up email', 'webinar-ignition' ), $webinar_id, WebinarIgnition_Logs::AUTO_EMAIL );
    } else {
        WebinarIgnition_Logs::add( __( 'Sending user sign-up email', 'webinar-ignition' ), $webinar_id, WebinarIgnition_Logs::AUTO_EMAIL );
        if ( !empty( $webinar_data->templates_version ) || isset( $webinar_data->use_new_email_signup_template ) && 'yes' === $webinar_data->use_new_email_signup_template ) {
            // Use new templates
            $webinar_data->emailheading = $webinar_data->email_signup_heading;
            $webinar_data->emailpreview = $webinar_data->email_signup_preview;
            $webinar_data->bodyContent = $webinar_data->email_signup_body;
            $webinar_data->footerContent = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ? $webinar_data->local_email_signup_footer : '' );
            $email = new WI_Emails();
            $emailBody = $email->webinarignition_build_email( $webinar_data );
        } else {
            $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
            $emailBody = $emailHead;
            $emailBody .= $webinar_data->email_signup_body;
        }
        $email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
        if ( !isset( $webinar_data->webinar_permalink ) ) {
            $webinar_data->webinar_permalink = WebinarignitionManager::webinarignition_get_permalink( $webinar_id, 'webinar' );
        }
        if ( $is_lead_protected ) {
            $ics_lid = $hash_ID;
        } else {
            $ics_lid = $out;
        }
        $translated_date = '';
        $storedDateTime = $leadInfo->date_picked_and_live;
        // e.g. "2025-11-06 14:30"
        $dateTime = DateTime::createFromFormat( 'Y-m-d H:i', $storedDateTime );
        if ( $dateTime ) {
            $date = $dateTime->format( 'Y-m-d' );
            // Extracted date part
            $time = $dateTime->format( 'H:i' );
            // Extracted time part
        }
        if ( isset( $date ) && !empty( $date ) ) {
            $translated_date = webinarignition_get_translated_date( sanitize_text_field( $date ), 'Y-m-d', $date_format );
        }
        $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
        $googleCalendarURL = add_query_arg( array(
            'googlecalendarA' => '1',
            'lid'             => $ics_lid,
            'webinar'         => '',
        ), $thankyou_URL );
        ob_start();
        ?>
		<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php 
        echo esc_url_raw( $googleCalendarURL );
        ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
			<i class="icon-google-plus"></i>
			<?php 
        webinarignition_display( $webinar_data->ty_calendar_google, __( 'Add to Google Calendar', 'webinar-ignition' ) );
        ?>
		</a>
		<?php 
        $wi_calendar_url = ob_get_clean();
        $send_mail = true;
        // Replace
        if ( 'yes' === $instant ) {
            if ( empty( $webinar_data->auto_translate_instant ) ) {
                $emailBody = str_replace( '{DATE}', 'Watch Replay', $emailBody );
            } else {
                $emailBody = str_replace( '{DATE}', $webinar_data->auto_translate_instant . ' ' . $wi_calendar_url, $emailBody );
            }
        } else {
            $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
            $emailBody = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz(
                $time,
                $time_format,
                $leadInfo->lead_timezone,
                false,
                $timeonly
            ) . ' ' . $wi_calendar_url, $emailBody );
            // Format current datetime
            $current_datetime = new DateTime('now', new DateTimeZone($leadInfo->lead_timezone));
            $webinar_datetime = DateTime::createFromFormat( 'd-Y-m H:i', $date . ' ' . $time, new DateTimeZone($leadInfo->lead_timezone) );
            if ( !$webinar_datetime ) {
                $webinar_datetime = DateTime::createFromFormat( 'Y-d-m H:i', $date . ' ' . $time, new DateTimeZone($leadInfo->lead_timezone) );
            }
            // Calculate difference in seconds
            $diff_in_seconds = $webinar_datetime->getTimestamp() - $current_datetime->getTimestamp();
            // If webinar is within 5 minutes, use current datetime
            if ( $diff_in_seconds <= 300 && $diff_in_seconds > 0 ) {
                $send_mail = false;
            }
        }
        $unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
        $unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
        $unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $unsubscribe_next_header_url );
        $unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $unsubscribe_all_header_url );
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
            'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
            'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>'
        );
        webinarignition_test_smtp_options();
        $watch_type = 'live';
        $additional_email_query_params = 'event=OI3shBXlqsw';
        $additional_email_query_params .= "&watch_type={$watch_type}";
        $event_data = [
            'id'       => $ics_lid,
            'webdata'  => $webinar_data,
            'leadinfo' => (array) $leadInfo,
        ];
        $ics_file = generate_icsA_file( $event_data );
        // Add Unsubscribe Text
        $unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
								<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
								<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
							</p>';
        $webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );
        // Check if the setting value is 1
        if ( 1 === absint( $webinarignition_unsubscribe_links ) ) {
            $emailBody .= $unsubscribe_text;
        }
        $emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders(
            $webinar_data,
            $out,
            $emailBody,
            $additional_email_query_params
        );
        $attachments = [$ics_file];
        if ( $send_mail ) {
            try {
                if ( !wp_mail(
                    $leadInfo->email,
                    $email_signup_sbj,
                    $emailBody,
                    $headers,
                    $attachments
                ) ) {
                    WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$leadInfo->lemail}", $webinar_id, WebinarIgnition_Logs::AUTO_EMAIL );
                } else {
                    WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinar-ignition' ), $webinar_id, WebinarIgnition_Logs::AUTO_EMAIL );
                }
            } catch ( Exception $e ) {
                WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$leadInfo->lemail}", $webinar_id, WebinarIgnition_Logs::AUTO_EMAIL );
            }
        }
    }
    //end if
}

add_action( 'wp_ajax_nopriv_webinarignition_get_tz_time_date', 'webinarignition_webinarignition_get_tz_time_date' );
add_action( 'wp_ajax_webinarignition_get_tz_time_date', 'webinarignition_webinarignition_get_tz_time_date' );
function webinarignition_webinarignition_get_tz_time_date() {
    global $wp_locale;
    $webinar_id = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $userTimezone = sanitize_text_field( filter_input( INPUT_POST, 'userTimezone' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    $webinarDateObject = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time, new DateTimeZone($webinar_data->webinar_timezone) );
    $webinarDateObject->setTimezone( new DateTimeZone($userTimezone) );
    $webinarTime = $webinarDateObject->format( $webinar_data->time_format );
    $webinarDateObject = $webinarDateObject->format( 'm-d-Y' );
    $webinarDateObject = DateTime::createFromFormat( 'm-d-Y', $webinarDateObject, new DateTimeZone('UTC') );
    $webinarTimestamp = $webinarDateObject->getTimestamp();
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : (( $webinar_data->webinar_date == 'AUTO' ? 'l, F j, Y' : get_option( 'date_format' ) )) );
    $date = date_i18n( $date_format, $webinarTimestamp );
    $translated_date = webinarignition_get_translated_date( $date, $date_format, $date_format );
    if ( !empty( $webinar_data->webinar_start_duration ) ) {
        /* translators: %s: Webinar duration in minutes. */
        $webinar_duration = esc_html( sprintf( __( 'Duration: %s minutes', 'webinar-ignition' ), $webinar_data->webinar_start_duration ) );
    } else {
        $webinar_duration = '';
    }
    wp_send_json( array(
        'date'     => $date,
        'time'     => $webinarTime,
        'duration' => $webinar_duration,
    ) );
}

add_action( 'wp_ajax_nopriv_webinarignition_get_wig_date', 'webinarignition_webinarignition_get_wig_date' );
add_action( 'wp_ajax_webinarignition_get_wig_date', 'webinarignition_webinarignition_get_wig_date' );
function webinarignition_webinarignition_get_wig_date() {
    global $wp_locale;
    $webinar_id = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $userTimezone = sanitize_text_field( filter_input( INPUT_POST, 'userTimezone' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    $webinarDateObject = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time, new DateTimeZone($webinar_data->webinar_timezone) );
    if ( $webinarDateObject === false ) {
        $webinarDateObject = DateTime::createFromFormat( 'm-d-Y ' . $webinar_data->time_format, $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time, new DateTimeZone($webinar_data->webinar_timezone) );
    }
    if ( $webinarDateObject === false ) {
        wp_send_json_error();
        die;
    }
    $webinarDateObject->setTimezone( new DateTimeZone($userTimezone) );
    $webinarTime = $webinarDateObject->format( $webinar_data->time_format );
    $webinarDateObject = $webinarDateObject->format( 'm-d-Y' );
    $webinarDateObject = DateTime::createFromFormat( 'm-d-Y', $webinarDateObject, new DateTimeZone('UTC') );
    $webinarTimestamp = $webinarDateObject->getTimestamp();
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : (( $webinar_data->webinar_date == 'AUTO' ? 'l, F j, Y' : get_option( 'date_format' ) )) );
    $date = date_i18n( $date_format, $webinarTimestamp );
    $translated_date = webinarignition_get_translated_date( $date, $date_format, $date_format );
    if ( !empty( $webinar_data->webinar_start_duration ) ) {
        /* translators: %s: Webinar duration in minutes. */
        $webinar_duration = esc_html( sprintf( __( 'Duration: %s minutes', 'webinar-ignition' ), $webinar_data->webinar_start_duration ) );
    } else {
        $webinar_duration = '';
    }
    wp_send_json( array(
        'date'     => $date,
        'time'     => $webinarTime,
        'duration' => $webinar_duration,
    ) );
}

add_action( 'wp_ajax_nopriv_webinarignition_get_tz_time_date_fixed', 'webinarignition_webinarignition_get_tz_time_date_fixed' );
add_action( 'wp_ajax_webinarignition_get_tz_time_date_fixed', 'webinarignition_webinarignition_get_tz_time_date_fixed' );
function webinarignition_webinarignition_get_tz_time_date_fixed() {
    global $wp_locale;
    $webinar_id = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $userTimezone = sanitize_text_field( filter_input( INPUT_POST, 'userTimezone' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    $webinarDateObject = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->auto_date_fixed_submit . ' ' . $webinar_data->auto_time_fixed_submit, new DateTimeZone($webinar_data->auto_timezone_fixed) );
    $webinarDateObject->setTimezone( new DateTimeZone($userTimezone) );
    $webinarTime = $webinarDateObject->format( $webinar_data->time_format );
    $webinarDateObject = $webinarDateObject->format( 'm-d-Y' );
    $webinarDateObject = DateTime::createFromFormat( 'm-d-Y', $webinarDateObject, new DateTimeZone('UTC') );
    $webinarTimestamp = $webinarDateObject->getTimestamp();
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : (( $webinar_data->webinar_date == 'AUTO' ? 'l, F j, Y' : get_option( 'date_format' ) )) );
    $date = date_i18n( $date_format, $webinarTimestamp );
    $translated_date = webinarignition_get_translated_date( $date, $date_format, $date_format );
    wp_send_json( array(
        'date' => $date,
        'time' => $webinarTime,
    ) );
}

// ADD NEW LEAD
add_action( 'wp_ajax_nopriv_webinarignition_add_lead_auto_reg', 'webinarignition_add_lead_auto_reg_callback' );
add_action( 'wp_ajax_webinarignition_add_lead_auto_reg', 'webinarignition_add_lead_auto_reg_callback' );
function webinarignition_add_lead_auto_reg_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $post_input = array();
    $post_input['name'] = sanitize_text_field( filter_input( INPUT_POST, 'name' ) );
    $post_input['lname'] = sanitize_text_field( filter_input( INPUT_POST, 'lname' ) );
    $post_input['salutation'] = sanitize_text_field( filter_input( INPUT_POST, 'salutation' ) );
    $post_input['reason'] = sanitize_text_field( filter_input( INPUT_POST, 'reason' ) );
    $post_input['firstName'] = ( filter_input( INPUT_POST, 'firstName' ) != '' ? sanitize_text_field( filter_input( INPUT_POST, 'firstName' ) ) : sanitize_text_field( filter_input( INPUT_POST, 'name' ) ) );
    $post_input['webinar_type'] = sanitize_text_field( filter_input( INPUT_POST, 'weibnar_type' ) );
    $post_input['email'] = ( isset( $_POST['email'] ) ? sanitize_email( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ) : '' );
    $post_input['phone'] = sanitize_text_field( filter_input( INPUT_POST, 'phone' ) );
    $post_input['id'] = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $post_input['source'] = ( isset( $_POST['source'] ) ? sanitize_text_field( filter_input( INPUT_POST, 'source' ) ) : '' );
    $post_input['usertimezone'] = ( isset( $_POST['userTimezone'] ) ? sanitize_text_field( filter_input( INPUT_POST, 'userTimezone' ) ) : '' );
    $post_input['gdpr_data'] = sanitize_text_field( filter_input( INPUT_POST, 'gdpr_data', FILTER_UNSAFE_RAW ) );
    $post_input['ip'] = sanitize_text_field( filter_input( INPUT_POST, 'ip' ) );
    if ( empty( $post_input['email'] ) || empty( $post_input['id'] ) ) {
        WebinarignitionAjax::error_response( array(
            'message' => __( 'Error', 'webinar-ignition' ) . ': ' . __( 'Cheating, huh!!!.1', 'webinar-ignition' ),
        ) );
    }
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );
    if ( !isset( $webinar_data->webinar_status ) || 'draft' === $webinar_data->webinar_status ) {
        return '';
    }
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
        $webinar_data->time_format = get_option( 'time_format', 'H:i' );
    }
    $time_format = $webinar_data->time_format;
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y' );
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    global $wpdb;
    $table_db_name = $wpdb->prefix . (( WebinarignitionManager::webinarignition_is_auto_webinar( $webinar_data ) ? 'webinarignition_leads_evergreen' : 'webinarignition_leads' ));
    // Check if lead with such email exists in database
    $email = sanitize_email( $post_input['email'] );
    $app_id = intval( $post_input['id'] );
    if ( $is_lead_protected ) {
        // Prepare and execute the query for protected leads
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT hash_ID AS ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $email, $app_id ) );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    } else {
        // Prepare and execute the query for non-protected leads
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $email, $app_id ) );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( $lead ) {
        $is_unsubscribed_to_all = update_option( 'lead_data_restricted_mails' . $email, false );
        $webinar_restricted_emails = get_option( 'webinar_data_restricted_mails' . $app_id, array() );
        if ( in_array( $email, $webinar_restricted_emails ) ) {
            $key = array_search( $email, $webinar_restricted_emails );
            if ( $key !== false ) {
                unset($webinar_restricted_emails[$key]);
            }
            update_option( 'webinar_data_restricted_mails' . $app_id, $webinar_restricted_emails );
        }
        $wpdb->delete( $table_db_name, [
            'email'  => $email,
            'app_id' => $app_id,
        ], ['%s', '%d'] );
        $lead = NULL;
    }
    // If the lead exists, return success response
    if ( $lead ) {
        $response = array(
            'success' => 1,
            'lid'     => $lead->ID,
        );
        echo wp_json_encode( $response );
        wp_die();
    }
    // Sanitize input values
    $name = sanitize_text_field( $post_input['name'] );
    $source = ( !empty( $post_input['source'] ) ? sanitize_text_field( $post_input['source'] ) : 'Optin' );
    $ip = sanitize_text_field( $post_input['ip'] );
    // Convert date to MySQL datetime format
    $created = gmdate( 'Y-m-d H:i:s' );
    $data = array(
        'app_id'              => $app_id,
        'name'                => $name,
        'email'               => $email,
        'trk1'                => $source,
        'trk3'                => $ip,
        'lead_browser_and_os' => $post_input['usertimezone'],
        'event'               => 'No',
        'replay'              => 'No',
        'created'             => $created,
    );
    $format = array(
        '%d',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%s'
    );
    // Correct usage of wpdb->insert
    $db_lead_created = $wpdb->insert( $table_db_name, $data, $format );
    // Get the ID of the inserted row
    $out = $wpdb->insert_id;
    $hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );
    $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET hash_ID = %s WHERE ID = %d", $hash_ID, $out ) );
    if ( $is_lead_protected ) {
        $ics_lid = $hash_ID;
    } else {
        $ics_lid = $out;
    }
    $lead_meta = array();
    $applang = $webinar_data->webinar_lang;
    foreach ( $post_input as $field_name => $field ) {
        $field_value = '';
        $field_label = '';
        if ( $field_name == 'name' ) {
            $field_name = 'optName';
            $field_value = $field;
            $field_label = WebinarignitionManager::webinarignition_ar_field_translated_name( 'optName', $applang );
        } elseif ( $field_name == 'lname' ) {
            $field_name = 'optLName';
            $field_value = $field;
            $field_label = WebinarignitionManager::webinarignition_ar_field_translated_name( 'optLName', $applang );
        } elseif ( $field_name == 'salutation' ) {
            $field_name = 'optSalutation';
            $field_value = $field;
            $field_label = $field_name;
        } elseif ( $field_name == 'reason' ) {
            $field_name = 'optReason';
            $field_value = $field;
            $field_label = 'Reason';
        } elseif ( $field_name == 'email' ) {
            $field_name = 'optEmail';
            $field_value = $field;
            $field_label = WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang );
        } elseif ( $field_name == 'usertimezone' ) {
            $field_name = 'webinar_timezone';
            $field_value = $field;
            $field_label = $field_name;
        }
        if ( $field_value == '' ) {
            continue;
        }
        $lead_meta[$field_name] = array(
            'label' => $field_label,
            'value' => $field_value,
        );
    }
    $salutation = ( isset( $post_input['salutation'] ) ? sanitize_text_field( $post_input['salutation'] ) : '' );
    $first_name = ( isset( $post_input['name'] ) ? sanitize_text_field( $post_input['name'] ) : '' );
    $last_name = ( isset( $post_input['lname'] ) ? sanitize_text_field( $post_input['lname'] ) : '' );
    $field_value = trim( implode( ' ', array_filter( [$salutation, $first_name, $last_name] ) ) );
    $field_label = 'full_name';
    $lead_meta['full_name'] = array(
        'label' => $field_label,
        'value' => $field_value,
    );
    $webinar_type = $post_input['webinar_type'];
    /**
     * Action Hook: webinarignition_lead_added
     *
     * @param int $webinar_id Webinar ID for which the lead was added
     * @param int $lead_id Lead ID which was added
     * @param array $lead_metadata Associated lead metadata
     */
    $webhook_lead_data = array();
    foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
        if ( is_array( $lead_meta_value ) ) {
            $webhook_lead_data[$lead_meta_key] = $lead_meta_value['value'];
        }
    }
    if ( !empty( $lead_meta ) ) {
        $lead_meta = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm',
            serialize( $lead_meta ),
            $webinar_type
        );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm_' . $post_input['id'],
            serialize( $lead_meta ),
            $webinar_type
        );
    }
    $lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";
    WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ) . "\n{$lead_details_string}\n\n" . __( 'Firing registration email', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
    if ( class_exists( 'Wp2leadsItmWebinarignitionRedirection' ) ) {
        $webinar_to_map = get_option( 'wp2leads_itm_webinarignition_webinar_to_map', array() );
        if ( empty( $webinar_to_map[$post_input['id']] ) ) {
            do_action( 'webinarignition_live_lead_added_email', $out, $post_input['id'] );
        }
    } else {
        // Class doesn't exist, handle gracefully
        do_action( 'webinarignition_live_lead_added_email', $out, $post_input['id'] );
    }
    if ( 'show' === $webinar_data->get_registration_notices_state && !empty( $webinar_data->registration_notice_email ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {
        $subj = 'New Registration For Webinar ' . $webinar_data->webinar_desc;
        $attendeeName = $post_input['name'];
        $emailBody = $attendeeName . ' (' . $post_input['email'] . ') ' . __( 'has just registered for your webinar', 'webinar-ignition' ) . ' ' . $webinar_data->webinar_desc;
        try {
            wp_mail(
                $webinar_data->registration_notice_email,
                $subj,
                $emailBody,
                $headers
            );
        } catch ( Exception $e ) {
            echo esc_attr( $e->getMessage() );
        }
    }
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
    if ( $is_lead_protected ) {
        $response = array(
            'success' => 1,
            'lid'     => $hash_ID,
        );
    } else {
        $response = array(
            'success' => 1,
            'lid'     => $out,
        );
    }
    echo wp_json_encode( $response );
    wp_die();
}

/**
 * TODO: This function might not be in used, need to check further before removing it.
 *
 * @param int    $ID The lead id.
 * @param string $name The lead name.
 * @param string $email The lead email.
 * @param string $IP The lead ip address.
 */
function webinarignition_add_lead_fb(
    $ID,
    $name,
    $email,
    $IP
) {
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $ID = sanitize_text_field( $ID );
    $name = sanitize_text_field( $name );
    $email = sanitize_email( $email );
    $wpdb->prepare(
        "INSERT INTO {$table_db_name}\n\t\t(app_id, name, email, trk1, trk3, created)\n\t\tVALUES (%s, %s, %s, %s, %s, %s)",
        $ID,
        $name,
        $email,
        'FB',
        $IP,
        gmdate( 'F j, Y' )
    );
    $wpdb->query( $wpdb->prepare );
    $get_lead_id = $wpdb->insert_id;
    $hash_ID = sha1( $ID . $email . $get_lead_id );
    $wpdb->prepare( "UPDATE {$table_db_name} SET hash_ID = %s WHERE ID = %d", $hash_ID, $get_lead_id );
    $wpdb->query( $wpdb->prepare );
    echo esc_attr( $get_lead_id );
    $lead_details_string = "Name: {$name}\nEmail: {$email}\n";
    WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ) . "\n{$lead_details_string}\n\n" . __( 'Firing registration email', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );
    if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
        $webinar_data->time_format = get_option( 'time_format', 'H:i' );
    }
    $time_format = $webinar_data->time_format;
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : (( 'AUTO' === $webinar_data->webinar_date ? 'l, F j, Y' : get_option( 'date_format' ) )) );
    $emailBody = $webinar_data->email_signup_body;
    // NB: date format for Live webinars always saved in DB as m-d-Y
    $translated_date = webinarignition_get_translated_date( $webinar_data->webinar_date, 'm-d-Y', $date_format );
    $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
    // Replace
    $emailBody = str_replace( '{DATE}', $translated_date . ' @ ' . webinarignition_get_time_tz(
        $webinar_data->webinar_start_time,
        $time_format,
        $webinar_data->webinar_timezone,
        false,
        $timeonly
    ), $emailBody );
    $emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $get_lead_id, $emailBody );
    $email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
    webinarignition_test_smtp_options();
    try {
        if ( !wp_mail(
            $email,
            $email_signup_sbj,
            $emailBody,
            $headers
        ) ) {
            WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$email}", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
            exit;
        } else {
            WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinar-ignition' ), $ID, WebinarIgnition_Logs::LIVE_EMAIL );
        }
    } catch ( Exception $e ) {
        WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$email}", $ID, WebinarIgnition_Logs::LIVE_EMAIL );
        exit;
    }
    if ( 'show' === $webinar_data->get_registration_notices_state && !empty( $webinar_data->registration_notice_email ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {
        $subj = __( 'New Registration For Webinar', 'webinar-ignition' ) . ' ' . $webinar_data->webinar_desc;
        $emailBody = $name . ' ' . __( 'has just registered for your webinar', 'webinar-ignition' ) . ' ' . $webinar_data->webinar_desc;
        try {
            wp_mail(
                $webinar_data->registration_notice_email,
                $subj,
                $emailBody,
                $headers
            );
        } catch ( Exception $e ) {
            echo esc_attr( $e->getMessage() );
        }
    }
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
}

function webinarignition_get_fb_id(  $ID, $email  ) {
    // Get ID for the FB Lead
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $findstat = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %s AND email = %s", $ID, $email ), OBJECT );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    return $findstat->ID;
}

// Track View - LANDING PAGE
add_action( 'wp_ajax_nopriv_webinarignition_track_lp_view', 'webinarignition_track_lp_view_callback' );
add_action( 'wp_ajax_webinarignition_track_lp_view', 'webinarignition_track_lp_view_callback' );
function webinarignition_track_lp_view_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    // Sanitize input value
    $ID = intval( $ID );
    // Prepare and execute the query
    $findstat = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE id = %d", $ID ), OBJECT );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    // Prepare the update query
    $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_views = total_views + 1 WHERE id = %d", $ID ) );
}

// ADD NEW QUESTION
add_action( 'wp_ajax_nopriv_webinarignition_submit_question', 'webinarignition_submit_question_callback' );
add_action( 'wp_ajax_webinarignition_submit_question', 'webinarignition_submit_question_callback' );
function webinarignition_submit_question_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $post_input = array(
        'name'              => sanitize_text_field( filter_input( INPUT_POST, 'name' ) ),
        'email'             => ( isset( $_POST['email'] ) ? sanitize_email( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ) : '' ),
        'id'                => absint( filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT ) ),
        'question'          => sanitize_text_field( filter_input( INPUT_POST, 'question' ) ),
        'lead'              => sanitize_text_field( filter_input( INPUT_POST, 'lead' ) ),
        'webinar_type'      => sanitize_text_field( filter_input( INPUT_POST, 'webinar_type' ) ),
        'webinarTime'       => sanitize_text_field( filter_input( INPUT_POST, 'webinarTime' ) ),
        'is_first_question' => wp_validate_boolean( filter_input( INPUT_POST, 'is_first_question' ) ),
    );
    $timezone_string = get_option( 'timezone_string' );
    $created = gmdate( 'Y-m-d h:i:sa' );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_questions';
    $post_input['name'] = ( isset( $post_input['name'] ) ? sanitize_text_field( $post_input['name'] ) : null );
    $post_input['email'] = ( isset( $post_input['email'] ) ? sanitize_email( $post_input['email'] ) : null );
    $post_input['id'] = ( isset( $post_input['id'] ) ? sanitize_text_field( $post_input['id'] ) : null );
    $post_input['question'] = ( isset( $post_input['question'] ) ? sanitize_text_field( $post_input['question'] ) : null );
    $post_input['lead'] = ( isset( $post_input['lead'] ) ? sanitize_text_field( $post_input['lead'] ) : null );
    $post_input['webinar_type'] = ( isset( $post_input['webinar_type'] ) ? sanitize_text_field( $post_input['webinar_type'] ) : null );
    $post_input['webinarTime'] = ( isset( $post_input['webinarTime'] ) ? sanitize_text_field( $post_input['webinarTime'] ) : null );
    $post_input['is_first_question'] = wp_validate_boolean( $post_input['is_first_question'] );
    $data = array(
        'app_id'      => $post_input['id'],
        'name'        => $post_input['name'],
        'email'       => $post_input['email'],
        'question'    => $post_input['question'],
        'type'        => 'question',
        'status'      => 'live',
        'created'     => current_time( 'mysql' ),
        'webinarTime' => $post_input['webinarTime'],
    );
    $id = WebinarignitionQA::webinarignition_create_question( $data );
    $data['webinar_type'] = $post_input['webinar_type'];
    $data['is_first_question'] = $post_input['is_first_question'];
    do_action( 'webinarignition_question_asked', $data );
    wp_send_json( $id );
}

add_action( 'webinarignition_question_asked', 'webinarignition_send_after_question_live_support_request' );
function webinarignition_send_after_question_live_support_request(  $supportData  ) {
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $supportData['app_id'] );
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    if ( 'AUTO' === $webinar_data->webinar_date || !WebinarignitionPowerups::webinarignition_is_multiple_support_enabled( $webinar_data ) ) {
        return;
    }
    $send_question_notification = false;
    if ( isset( $webinar_data->enable_first_question_notification ) && 'yes' === $webinar_data->enable_first_question_notification && 'no' === $webinar_data->first_question_notification_sent ) {
        $send_question_notification = true;
    }
    if ( $send_question_notification && isset( $webinar_data->support_staff_count ) && !empty( $webinar_data->support_staff_count ) ) {
        for ($x = 1; $x <= $webinar_data->support_staff_count; $x++) {
            $member_email = 'member_email_' . $x;
            if ( property_exists( $webinar_data, $member_email ) ) {
                $qstn_notification_email_body = $webinar_data->qstn_notification_email_body;
                $emailSubj = $webinar_data->qstn_notification_email_sbj;
                $member = get_user_by( 'email', $webinar_data->{'member_email_' . $x} );
                if ( is_object( $member ) ) {
                    $email_data = new stdClass();
                    $_wi_support_token = get_user_meta( $member->ID, '_wi_support_token', true );
                    $support_link = $webinar_data->webinar_permalink . '?console&_wi_support_token=' . $_wi_support_token . '#/questions';
                    $replacement = array(
                        $member->first_name,
                        $supportData['name'],
                        $webinar_data->webinar_desc,
                        $support_link
                    );
                    $replace = array(
                        '{support}',
                        '{attendee}',
                        '{webinarTitle}',
                        '{link}'
                    );
                    $email_data->bodyContent = str_replace( $replace, $replacement, $qstn_notification_email_body );
                    $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ? $webinar_data->qstn_answer_email_footer : '' );
                    $email_data->email_subject = __( 'Questions From Your Webinar', 'webinar-ignition' );
                    $email_data->emailheading = __( 'Questions From Your Webinar', 'webinar-ignition' );
                    $email_data->emailpreview = __( 'Questions From Your Webinar', 'webinar-ignition' );
                    $email = new WI_Emails();
                    $emailBody = $email->webinarignition_build_email( $email_data );
                    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
                    try {
                        if ( !wp_mail(
                            $member->user_email,
                            $emailSubj,
                            $emailBody,
                            $headers
                        ) ) {
                            WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinar-ignition' ) . " {$member->email}", WebinarIgnition_Logs::LIVE_EMAIL );
                        } elseif ( property_exists( $webinar_data, 'first_question_notification_sent' ) && 'no' === $webinar_data->first_question_notification_sent ) {
                            $webinar_data->first_question_notification_sent = 'yes';
                            update_option( 'webinarignition_campaign_' . $supportData['app_id'], $webinar_data );
                            WebinarIgnition_Logs::add( __( 'Support request has been sent.', 'webinar-ignition' ), $supportData['app_id'], WebinarIgnition_Logs::LIVE_EMAIL );
                        }
                    } catch ( Exception $e ) {
                        WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinar-ignition' ) . " {$member->user_email}", WebinarIgnition_Logs::LIVE_EMAIL );
                    }
                }
                //end if
            }
            //end if
        }
        //end for
    }
    //end if
    if ( $send_question_notification && isset( $webinar_data->send_host_questions_notifications ) && 'yes' === $webinar_data->send_host_questions_notifications && isset( $webinar_data->host_questions_notifications_email ) ) {
        if ( filter_var( $webinar_data->host_questions_notifications_email, FILTER_VALIDATE_EMAIL ) ) {
            $qstn_notification_email_body = $webinar_data->qstn_notification_email_body;
            $emailSubj = $webinar_data->qstn_notification_email_sbj;
            $support_link = $webinar_data->webinar_permalink . '/?console#/questions';
            $replacement = array(
                $webinar_data->webinar_host,
                $supportData['name'],
                $webinar_data->webinar_desc,
                $support_link
            );
            $replace = array(
                '{support}',
                '{attendee}',
                '{webinarTitle}',
                '{link}'
            );
            $email_data = new stdClass();
            $email_data->bodyContent = str_replace( $replace, $replacement, $qstn_notification_email_body );
            $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ? $webinar_data->qstn_answer_email_footer : '' );
            $email_data->email_subject = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $email_data->emailheading = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $email_data->emailpreview = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $wi_emails = new WI_Emails();
            $emailBody = $email->webinarignition_build_email( $email_data );
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
            try {
                if ( !wp_mail(
                    $webinar_data->host_questions_notifications_email,
                    $emailSubj,
                    $emailBody,
                    $headers
                ) ) {
                    WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent', 'webinar-ignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
                }
            } catch ( Exception $e ) {
                WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent.', 'webinar-ignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
            }
        }
        //end if
    }
    //end if
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
}

add_action( 'webinarignition_question_asked', 'webinarignition_send_after_question_auto_support_request' );
function webinarignition_send_after_question_auto_support_request(  $supportData  ) {
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $supportData['app_id'] );
    $applang = $webinar_data->webinar_lang;
    if ( $applang ) {
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    if ( !WebinarignitionPowerups::webinarignition_is_multiple_support_enabled( $webinar_data ) || 'AUTO' === $webinar_data->webinar_date && !$supportData['is_first_question'] ) {
        return;
    }
    $send_question_notification = false;
    if ( isset( $webinar_data->enable_first_question_notification ) && 'yes' === $webinar_data->enable_first_question_notification ) {
        $send_question_notification = true;
    }
    if ( $send_question_notification && isset( $webinar_data->support_staff_count ) && !empty( $webinar_data->support_staff_count ) ) {
        for ($x = 1; $x <= $webinar_data->support_staff_count; $x++) {
            $member_email = 'member_email_' . $x;
            if ( property_exists( $webinar_data, $member_email ) ) {
                $qstn_notification_email_body = $webinar_data->qstn_notification_email_body;
                $emailSubj = $webinar_data->qstn_notification_email_sbj;
                $member = get_user_by( 'email', $webinar_data->{'member_email_' . $x} );
                if ( is_object( $member ) ) {
                    $_wi_support_token = get_user_meta( $member->ID, '_wi_support_token', true );
                    $support_link = $webinar_data->webinar_permalink . '?console&_wi_support_token=' . $_wi_support_token . '#/questions';
                    $replacement = array(
                        $member->first_name,
                        $supportData['name'],
                        $webinar_data->webinar_desc,
                        $support_link
                    );
                    $replace = array(
                        '{support}',
                        '{attendee}',
                        '{webinarTitle}',
                        '{link}'
                    );
                    $email_data = new stdClass();
                    $email_data->bodyContent = str_replace( $replace, $replacement, $qstn_notification_email_body );
                    $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ? $webinar_data->qstn_answer_email_footer : '' );
                    $email_data->email_subject = $webinar_data->qstn_notification_email_sbj;
                    $email = new WI_Emails();
                    $emailBody = $email->webinarignition_build_email( $email_data );
                    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
                    try {
                        if ( !wp_mail(
                            $member->user_email,
                            $emailSubj,
                            $emailBody,
                            $headers
                        ) ) {
                            WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinar-ignition' ) . " {$member->email}", WebinarIgnition_Logs::LIVE_EMAIL );
                        } elseif ( property_exists( $webinar_data, 'first_question_notification_sent' ) && 'no' === $webinar_data->first_question_notification_sent ) {
                            $webinar_data->first_question_notification_sent = 'yes';
                            update_option( 'webinarignition_campaign_' . $supportData['app_id'], $webinar_data );
                            WebinarIgnition_Logs::add( __( 'Support request has been sent.', 'webinar-ignition' ), $supportData['app_id'], WebinarIgnition_Logs::LIVE_EMAIL );
                        }
                    } catch ( Exception $e ) {
                        WebinarIgnition_Logs::add( __( 'Support request email could not be sent to', 'webinar-ignition' ) . " {$member->user_email}", WebinarIgnition_Logs::LIVE_EMAIL );
                    }
                }
                //end if
            }
            //end if
        }
        //end for
    }
    //end if
    if ( $send_question_notification && isset( $webinar_data->send_host_questions_notifications ) && 'yes' === $webinar_data->send_host_questions_notifications && isset( $webinar_data->host_questions_notifications_email ) ) {
        if ( filter_var( $webinar_data->host_questions_notifications_email, FILTER_VALIDATE_EMAIL ) ) {
            $qstn_notification_email_body = $webinar_data->qstn_notification_email_body;
            $emailSubj = $webinar_data->qstn_notification_email_sbj;
            $support_link = $webinar_data->webinar_permalink . '/?console#/questions';
            $replacement = array(
                $webinar_data->webinar_host,
                $supportData['name'],
                $webinar_data->webinar_desc,
                $support_link
            );
            $replace = array(
                '{support}',
                '{attendee}',
                '{webinarTitle}',
                '{link}'
            );
            $email_data = new stdClass();
            $email_data->bodyContent = str_replace( $replace, $replacement, $qstn_notification_email_body );
            $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ? $webinar_data->qstn_answer_email_footer : '' );
            $email_data->email_subject = $webinar_data->qstn_notification_email_sbj;
            $email = new WI_Emails();
            $emailBody = $email->webinarignition_build_email( $email_data );
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
            try {
                if ( !wp_mail(
                    $webinar_data->host_questions_notifications_email,
                    $emailSubj,
                    $emailBody,
                    $headers
                ) ) {
                    WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent', 'webinar-ignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
                }
            } catch ( Exception $e ) {
                WebinarIgnition_Logs::add( __( 'Support request email to webinar host could not be sent.', 'webinar-ignition' ), WebinarIgnition_Logs::LIVE_EMAIL );
            }
        }
        //end if
    }
    //end if
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
}

add_action( 'wp_ajax_webinarignition_delete_question', 'webinarignition_delete_question_callback' );
function webinarignition_delete_question_callback() {
    // Check user capabilities
    if ( !current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( [
            'message' => 'Insufficient permissions',
        ], 403 );
        wp_die();
    }
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    global $wpdb;
    $data = array(
        'ID'     => $ID,
        'status' => 'deleted',
    );
    $result = WebinarignitionQA::webinarignition_create_question( $data );
    if ( $result ) {
        WebinarignitionQA::webinarignition_delete_answers( $ID );
        $message = __( 'Question successfully deleted', 'webinar-ignition' );
        wp_send_json_success( array(
            'success' => true,
            'message' => $message,
        ) );
    }
    wp_die();
}

add_action( 'wp_ajax_check_webinar_time_change', 'handle_webinar_time_change_check' );
add_action( 'wp_ajax_nopriv_check_webinar_time_change', 'handle_webinar_time_change_check' );
function handle_webinar_time_change_check() {
    // Verify nonce for security if needed
    if ( !wp_verify_nonce( $_POST['wp_nonce'], 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    try {
        if ( !isset( $_POST['webinar_id'] ) || !isset( $_POST['new_date'] ) || !isset( $_POST['new_time'] ) || !isset( $_POST['new_timezone'] ) ) {
            wp_send_json_error( 'Missing required parameters' );
        }
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $_POST['webinar_id'] );
        $date_format = 'm-d-Y';
        $time_format = $webinar_data->time_format;
        // e.g., 'H:i'
        // Format for parsing submitted new date/time
        $new_format = "{$date_format} g:i A";
        // Create DateTime object for new date/time in submitted timezone
        $new_datetime = DateTime::createFromFormat( $new_format, $_POST['new_date'] . ' ' . $_POST['new_time'], new DateTimeZone($_POST['new_timezone']) );
        if ( !$new_datetime ) {
            wp_send_json_error( 'Invalid new date or time format' );
        }
        // Convert new datetime to UTC
        $new_datetime->setTimezone( new DateTimeZone('UTC') );
        // Get current time in UTC
        $current_utc = new DateTime('now', new DateTimeZone('UTC'));
        // Compare new datetime to current UTC time
        $is_moved_forward = $new_datetime > $current_utc;
        wp_send_json( ( $is_moved_forward ? 'true' : 'false' ) );
    } catch ( Exception $e ) {
        wp_send_json_error( $e->getMessage() );
    }
}

add_action( 'wp_ajax_check_webinar_time_new', 'handle_webinar_time_future_check' );
function handle_webinar_time_future_check() {
    // Verify nonce for security
    if ( !isset( $_POST['wp_nonce'] ) || !wp_verify_nonce( $_POST['wp_nonce'], 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( 'Invalid nonce' );
    }
    try {
        $new_date = ( isset( $_POST['new_date'] ) ? sanitize_text_field( $_POST['new_date'] ) : '' );
        $new_time = ( isset( $_POST['new_time'] ) ? sanitize_text_field( $_POST['new_time'] ) : '' );
        $timezone = ( !empty( $_POST['new_timezone'] ) ? sanitize_text_field( $_POST['new_timezone'] ) : 'UTC' );
        $new_format = 'm-d-Y h:i A';
        // e.g., 04-15-2025 06:45 PM
        $datetime_str = $new_date . ' ' . $new_time;
        $new_datetime = DateTime::createFromFormat( $new_format, $datetime_str, new DateTimeZone($timezone) );
        if ( !$new_datetime ) {
            wp_send_json_error( 'Invalid date or time format' );
        }
        // Convert new datetime to UTC
        $new_datetime->setTimezone( new DateTimeZone('UTC') );
        // Get current UTC time
        $current_utc = new DateTime('now', new DateTimeZone('UTC'));
        // Check if new datetime is in the future
        $is_future = $new_datetime > $current_utc;
        wp_send_json( ( $is_future ? 'true' : 'false' ) );
    } catch ( Exception $e ) {
        wp_send_json_error( $e->getMessage() );
    }
}

add_action( 'wp_ajax_date_change_on_no', 'date_change_on_no_callback' );
add_action( 'wp_ajax_nopriv_date_change_on_no', 'date_change_on_no_callback' );
function date_change_on_no_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    if ( !isset( $_POST['wi_webinar_id_resend_mail'] ) ) {
        wp_send_json_error( [
            'message' => 'Invalid request.',
        ] );
    }
    global $wpdb;
    $wi_webinar_id_resend_mail = $_POST['wi_webinar_id_resend_mail'];
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $ID = intval( $wi_webinar_id_resend_mail );
    // Sanitize as integer
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    $wpdb->query( $wpdb->prepare( "DELETE FROM `{$table_db_name}` WHERE `app_id` = %d", $ID ) );
    wp_send_json_success( [
        'message' => 'Timezone updated successfully!',
    ] );
}

add_action( 'wp_ajax_notification_current_user', 'notification_current_user_callback' );
// add_action('wp_ajax_nopriv_notification_current_user', 'notification_current_user_callback');
function notification_current_user_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    if ( !isset( $_POST['notify_current_user'] ) ) {
        wp_send_json_error( [
            'message' => 'Invalid request.',
        ] );
        wp_die();
    }
    global $wpdb;
    $wi_webinar_date_resent_mail = sanitize_text_field( wp_unslash( $_POST['wi_webinar_date_resent_mail'] ?? '' ) );
    $webinar_start_time_submit = sanitize_text_field( wp_unslash( $_POST['webinar_start_time_submit'] ?? '' ) );
    $wi_webinar_timezone_resend = sanitize_text_field( wp_unslash( $_POST['wi_webinar_timezone_resend'] ?? '' ) );
    $wi_webinar_id_resend_mail = sanitize_text_field( wp_unslash( $_POST['wi_webinar_id_resend_mail'] ?? '' ) );
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $ID = intval( $wi_webinar_id_resend_mail );
    // Sanitize as integer
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    // Prepare and execute the query
    $leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d ORDER BY `created` ASC", $ID ), ARRAY_A );
    foreach ( $leads as $lead ) {
        $wpdb->update(
            $table_db_name,
            array(
                'trk2'        => null,
                'lead_status' => null,
            ),
            array(
                'ID' => $lead['ID'],
            ),
            array('%s', '%s'),
            // data formats
            array('%d')
        );
        $is_unsubscribed_to_all = get_option( 'lead_data_restricted_mails' . $lead['email'], false );
        $webinar_restricted_emails = get_option( 'webinar_data_restricted_mails' . $ID, array() );
        if ( $is_unsubscribed_to_all || in_array( $lead['email'], $webinar_restricted_emails ) ) {
            continue;
        }
        if ( !empty( $webinar_data->templates_version ) || !empty( $webinar_data->use_new_email_signup_template ) && 'yes' === $webinar_data->use_new_email_signup_template ) {
            // use new templates
            $webinar_data->emailheading = $webinar_data->email_signup_heading;
            $webinar_data->emailpreview = $webinar_data->email_signup_preview;
            $webinar_data->bodyContent = $webinar_data->email_signup_body;
            $webinar_data->footerContent = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ? $webinar_data->local_email_signup_footer : '' );
            $email = new WI_Emails();
            $emailBody = $email->webinarignition_build_email( $webinar_data );
        } else {
            // This is an old webinar, created before this version
            $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
            $emailBody = $emailHead;
            $emailBody .= $webinar_data->email_signup_body;
            $emailBody .= '</html>';
        }
        $emailBody = str_replace( '{LEAD_NAME}', ( !empty( $lead['name'] ) ? sanitize_text_field( $lead['name'] ) : $lead['firstName'] ), $emailBody );
        $emailBody = str_replace( '{FIRSTNAME}', ( !empty( $lead['firstName'] ) ? sanitize_text_field( $lead['firstName'] ) : $lead['name'] ), $emailBody );
        $localized_date = webinarignition_get_localized_date( $webinar_data );
        $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
        // Replace
        if ( $is_lead_protected ) {
            $ics_lid = $lead['hash_ID'];
        } else {
            $ics_lid = $lead['ID'];
        }
        $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
        $googleCalendarURL = add_query_arg( array(
            'googlecalendar' => '1',
            'lid'            => $ics_lid,
            'webinar'        => '',
        ), $thankyou_URL );
        ob_start();
        ?>
		<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php 
        echo esc_url_raw( $googleCalendarURL );
        ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
			<i class="icon-google-plus"></i>
			<?php 
        webinarignition_display( $webinar_data->ty_calendar_google, __( 'Add to Google Calendar', 'webinar-ignition' ) );
        ?>
		</a>
		<?php 
        $applang = $webinar_data->webinar_lang;
        if ( $applang ) {
            switch_to_locale( $applang );
            unload_textdomain( 'webinar-ignition' );
            load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
        }
        $webinarDateObject = DateTime::createFromFormat( 'm-d-Y h:i A', $wi_webinar_date_resent_mail . ' ' . $webinar_start_time_submit, new DateTimeZone($wi_webinar_timezone_resend) );
        $webinarDateObjectNew = DateTime::createFromFormat( 'm-d-Y h:i A', $wi_webinar_date_resent_mail . ' ' . $webinar_start_time_submit, new DateTimeZone($wi_webinar_timezone_resend) );
        if ( isset( $lead['lead_browser_and_os'] ) ) {
            $webinarDateObject->setTimezone( new DateTimeZone($lead['lead_browser_and_os']) );
            $webinarDateObjectNew->setTimezone( new DateTimeZone($lead['lead_browser_and_os']) );
        }
        $webinarTime = $webinarDateObject->format( $webinar_data->time_format );
        $webinarDateObject = $webinarDateObject->format( 'm-d-Y' );
        $webinarDateObject = DateTime::createFromFormat( 'm-d-Y', $webinarDateObject, new DateTimeZone('UTC') );
        $webinarTimestamp = $webinarDateObject->getTimestamp();
        $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : (( $webinar_data->webinar_date == 'AUTO' ? 'l, F j, Y' : get_option( 'date_format' ) )) );
        $date = date_i18n( $date_format, $webinarTimestamp );
        $wi_calendar_url = ob_get_clean();
        $emailBody = str_replace( '{DATE}', $date . ' @ ' . webinarignition_get_time_tz(
            $webinarTime,
            '',
            $lead['lead_browser_and_os'],
            false,
            $timeonly
        ) . ' ' . $wi_calendar_url, $emailBody );
        $send_mail = true;
        // Format current datetime
        if ( isset( $lead['lead_browser_and_os'] ) && !empty( $lead['lead_browser_and_os'] ) ) {
            $current_datetime = new DateTime('now', new DateTimeZone($lead['lead_browser_and_os']));
        } else {
            $current_datetime = new DateTime('now', new DateTimeZone($webinar_data->webinar_timezone));
        }
        // Calculate difference in seconds
        $diff_in_seconds = $webinarDateObjectNew->getTimestamp() - $current_datetime->getTimestamp();
        // If webinar is within 5 minutes, use current datetime
        if ( $diff_in_seconds <= 300 && $diff_in_seconds > 0 ) {
            $send_mail = false;
        }
        // Add Unsubscribe Text
        $unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
			<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
			<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
		</p>';
        $webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );
        // Check if the setting value is 1
        if ( 1 === absint( $webinarignition_unsubscribe_links ) ) {
            $emailBody .= $unsubscribe_text;
        }
        $emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead['ID'], $emailBody );
        $email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
        $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
        $event_data = [
            'id'      => $ics_lid,
            'webdata' => $webinar_data,
            'webdate' => $wi_webinar_date_resent_mail,
            'webtime' => $webinar_start_time_submit,
        ];
        $ics_file = generate_renotification_ics_file( $event_data );
        $unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
        $unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
        $unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead['ID'], $unsubscribe_next_header_url );
        $unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $lead['ID'], $unsubscribe_all_header_url );
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
            'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
            'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>'
        );
        $attachments = [$ics_file];
        webinarignition_test_smtp_options();
        if ( $send_mail ) {
            try {
                if ( !wp_mail(
                    $lead['email'],
                    $email_signup_sbj,
                    $emailBody,
                    $headers,
                    $attachments
                ) ) {
                    WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$lead['email']}", WebinarIgnition_Logs::LIVE_EMAIL );
                } else {
                    WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinar-ignition' ), $lead['ID'], WebinarIgnition_Logs::LIVE_EMAIL );
                }
            } catch ( Exception $e ) {
                WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$lead['email']}", WebinarIgnition_Logs::LIVE_EMAIL );
            }
        }
    }
    $notify_current = sanitize_text_field( $_POST['notify_current_user'] );
    wp_send_json_success( [
        'message' => 'Notification preference updated successfully!',
    ] );
    wp_die();
}

add_action( 'wp_ajax_webinarignition_all_lead_delete', 'webinarignition_all_lead_delete_callback' );
function webinarignition_all_lead_delete_callback() {
    global $wpdb;
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $_POST['id'] );
    // Get Leads
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    $table_meta_db_name = $wpdb->prefix . 'webinarignition_leadmeta';
    $ID = intval( $_POST['id'] );
    // Ensure $ID is an integer
    if ( 'AUTO' === $webinar_data->webinar_date ) {
        $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
        $ID = intval( $ID );
        // Sanitize as integer
        // Prepare and execute the query
        $leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d", $ID ), ARRAY_A );
        $totalLeads = count( $leads );
    } else {
        $table_db_name = $wpdb->prefix . 'webinarignition_leads';
        // Sanitize input values
        // Prepare and execute the query
        $leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d", $ID ), ARRAY_A );
        $totalLeads = count( $leads );
    }
    foreach ( $leads as $lead ) {
        if ( $wpdb->delete( $table_db_name, array(
            'ID' => $lead['ID'],
        ) ) ) {
            $message = 'lead ' . $ID . ' deleted';
            $wpdb->query( $wpdb->prepare( "DELETE FROM {$table_meta_db_name} WHERE lead_id = %d", $lead['ID'] ) );
        }
    }
    wp_send_json_success( array(
        'success' => true,
        'message' => $message,
    ) );
}

add_action( 'wp_ajax_save_csv_mapping', function () {
    check_ajax_referer( 'webinarignition_ajax_nonce', 'nonce' );
    parse_str( $_POST['data'], $form_data );
    $webinar_id = ( isset( $form_data['webinarignition_webinar_id'] ) ? intval( $form_data['webinarignition_webinar_id'] ) : 0 );
    $field_map = array();
    foreach ( $form_data['field_map'] as $default => $custom ) {
        $field_map[$default] = ( !empty( $custom ) ? sanitize_text_field( $custom ) : $default );
    }
    update_post_meta( $webinar_id, 'webinar_import_mapped_fields', $field_map );
    wp_send_json_success( array(
        'message' => 'Field mapping saved successfully.',
        'mapping' => $field_map,
    ) );
} );
add_action( 'wp_ajax_webinarignition_delete_lead', 'webinarignition_delete_lead_callback' );
function webinarignition_delete_lead_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $webinar_id = sanitize_text_field( filter_input( INPUT_POST, 'webinar_id' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $table_meta_db_name = $wpdb->prefix . 'webinarignition_leadmeta';
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $table_db_name = ( webinarignition_is_auto( $webinar_data ) ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads' );
    if ( $wpdb->delete( $table_db_name, array(
        'ID' => $ID,
    ) ) ) {
        $message = 'lead ' . $ID . ' deleted';
        $wpdb->query( $wpdb->prepare( "DELETE FROM {$table_meta_db_name} WHERE lead_id = %d", $ID ) );
        wp_send_json_success( array(
            'success' => true,
            'message' => $message,
        ) );
    }
}

add_action( 'wp_ajax_webinarignition_delete_lead_auto', 'webinarignition_delete_lead_auto_callback' );
function webinarignition_delete_lead_auto_callback() {
    // Check user capabilities
    if ( !current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( [
            'message' => 'Insufficient permissions',
        ], 403 );
        wp_die();
    }
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    $table_meta_db_name = $wpdb->prefix . 'webinarignition_lead_evergreenmeta';
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$table_db_name} WHERE id = %d", $ID ) );
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$table_meta_db_name} WHERE lead_id = %d", $ID ) );
    wp_send_json_success();
    wp_die();
}

add_action( 'wp_ajax_webinarignition_reset_stats', 'webinarignition_reset_stats_callback' );
function webinarignition_reset_stats_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    $wpdb->query( $wpdb->prepare(
        "UPDATE {$table_db_name} SET\n\t\ttotal_lp = %s,\n\t\ttotal_ty = %s,\n\t\ttotal_live = %s,\n\t\ttotal_replay = %s\n\t\tWHERE id = %d",
        '0%%0',
        '0%%0',
        '0%%0',
        '0%%0',
        $ID
    ) );
    wp_send_json_success();
    wp_die();
}

// COUNTDOWN - EXPIRE -- UPDATE TO LIVE
add_action( 'wp_ajax_nopriv_webinarignition_update_to_live', 'webinarignition_update_to_live_callback' );
add_action( 'wp_ajax_webinarignition_update_to_live', 'webinarignition_update_to_live_callback' );
function webinarignition_update_to_live_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    // update status
    $results->webinar_switch = 'live';
    // save
    update_option( 'webinarignition_campaign_' . $ID, $results );
}

add_action( 'wp_ajax_nopriv_webinarignition_get_master_switch_status', 'webinarignition_get_master_switch_status_callback' );
add_action( 'wp_ajax_webinarignition_get_master_switch_status', 'webinarignition_get_master_switch_status_callback' );
function webinarignition_get_master_switch_status_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    wp_send_json( array(
        'webinar_switch_status' => $webinar_data->webinar_switch,
    ) );
}

// TRACK VIEW
add_action( 'wp_ajax_nopriv_webinarignition_track_view', 'webinarignition_track_view_callback' );
add_action( 'wp_ajax_webinarignition_track_view', 'webinarignition_track_view_callback' );
function webinarignition_track_view_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Campaign ID
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $PAGE = sanitize_text_field( filter_input( INPUT_POST, 'page' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    $findstat = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE id = %d", $ID ), OBJECT );
    if ( 'lp' === $PAGE ) {
        // LANDING PAGE
        $getData = $findstat->total_lp;
        $getData = explode( '%%', $getData );
        $getUnique = (int) $getData[0] + 1;
        $getTotal = $getData[1];
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_lp = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    } elseif ( 'ty' === $PAGE ) {
        // THANK YOU PAGE
        $getData = $findstat->total_ty;
        $getData = explode( '%%', $getData );
        $getUnique = (int) $getData[0] + 1;
        $getTotal = $getData[1];
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_ty = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    } elseif ( 'live' === $PAGE ) {
        // LIVE
        $getData = $findstat->total_live;
        $getData = explode( '%%', $getData );
        $getUnique = (int) $getData[0] + 1;
        $getTotal = $getData[1];
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_live = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    } elseif ( 'replay' === $PAGE ) {
        // REPLAY
        $getData = $findstat->total_replay;
        $getData = explode( '%%', $getData );
        $getUnique = (int) $getData[0] + 1;
        $getTotal = $getData[1];
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_replay = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    }
    //end if
}

// TRACK VIEW
add_action( 'wp_ajax_nopriv_webinarignition_track_view_total', 'webinarignition_track_view_total_callback' );
add_action( 'wp_ajax_webinarignition_track_view_total', 'webinarignition_track_view_total_callback' );
function webinarignition_track_view_total_callback() {
    check_ajax_referer( 'webinarignition_ajax_nonce', 'security', false );
    // Campaign ID
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
    $PAGE = sanitize_text_field( filter_input( INPUT_POST, 'page' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    $findstat = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE id = %d", $ID ), OBJECT );
    if ( 'lp' === $PAGE ) {
        // LANDING PAGE
        $getData = $findstat->total_lp;
        $getData = explode( '%%', $getData );
        $getUnique = $getData[0];
        $current_visitors = $getData[1];
        $getTotal = (int) $current_visitors + 1;
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_lp = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    } elseif ( 'ty' === $PAGE ) {
        // THANK YOU PAGE
        $getData = $findstat->total_ty;
        $getData = explode( '%%', $getData );
        $getUnique = $getData[0];
        $current_visitors = $getData[1];
        $getTotal = (int) $current_visitors + 1;
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_ty = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    } elseif ( 'live' === $PAGE ) {
        // LIVE
        $getData = $findstat->total_live;
        $getData = explode( '%%', $getData );
        $getUnique = $getData[0];
        $current_visitors = $getData[1];
        $getTotal = (int) $current_visitors + 1;
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_live = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    } elseif ( 'replay' === $PAGE ) {
        // REPLAY
        $getData = $findstat->total_replay;
        $getData = explode( '%%', $getData );
        $getUnique = $getData[0];
        $current_visitors = $getData[1];
        $getTotal = (int) $current_visitors + 1;
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET total_replay = %s WHERE id = %d", $getUnique . '%%' . $getTotal, $ID ) );
    }
    //end if
}

// TRACK LIVE ATTEND
add_action( 'wp_ajax_nopriv_webinarignition_update_view_status', 'webinarignition_update_view_status_callback' );
add_action( 'wp_ajax_webinarignition_update_view_status', 'webinarignition_update_view_status_callback' );
function webinarignition_update_view_status_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $lead_id = sanitize_text_field( filter_input( INPUT_POST, 'lead_id', FILTER_UNSAFE_RAW ) );
    $webinar_id = absint( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $webinar_started = webinarignition_should_use_videojs( $webinar_data ) && isset( $_COOKIE["videoResumeTime-{$lead_id}"] ) || !webinarignition_should_use_videojs( $webinar_data );
    $updated = false;
    if ( !empty( $lead_id ) && !empty( $webinar_data ) && $webinar_started ) {
        $updated = webinarignition_update_webinar_lead_status( $webinar_data->webinar_date, $lead_id );
    }
    wp_send_json_success( array(
        'message' => __( 'Data updated successfully', 'webinar-ignition' ),
    ) );
}

// GET QA -- NAME AND EMAIL
add_action( 'wp_ajax_nopriv_webinarignition_get_qa_name_email', 'webinarignition_get_qa_name_email_callback' );
add_action( 'wp_ajax_webinarignition_get_qa_name_email', 'webinarignition_get_qa_name_email_callback' );
function webinarignition_get_qa_name_email_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get Variables
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $cookieStatus = sanitize_text_field( filter_input( INPUT_POST, 'cookie', FILTER_UNSAFE_RAW ) );
    $IP = sanitize_text_field( filter_input( INPUT_POST, 'ip', FILTER_UNSAFE_RAW ) );
    if ( empty( $cookieStatus ) ) {
        // No Cookie Found -- Try IP
        // Prepare the query
        $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE trk3 = %s", $IP ), OBJECT );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        if ( empty( $data ) ) {
            // TODO: Improve the codes.
            // No IP Found - Do Nothing...
        } else {
            // IP Found - GET NAME / EMAIL
            echo esc_attr( $data->name . '//' . $data->email . '//' . $data->ID );
        }
    } else {
        // Cookie Was Found - Get Info
        // Assuming $cookieStatus is an ID and should be an integer
        $id = intval( $cookieStatus );
        // Prepare the query
        $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE id = %d", $id ), OBJECT );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        if ( is_object( $data ) ) {
            echo esc_attr( $data->name . '//' . $data->email . '//' . $data->ID );
        }
        //end if
    }
    die;
}

// GET QA -- NAME AND EMAIL AUTO
add_action( 'wp_ajax_nopriv_webinarignition_get_qa_name_email2', 'webinarignition_get_qa_name_email2_callback' );
add_action( 'wp_ajax_webinarignition_get_qa_name_email2', 'webinarignition_get_qa_name_email2_callback' );
function webinarignition_get_qa_name_email2_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get Variables
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    $cookieStatus = sanitize_text_field( filter_input( INPUT_POST, 'cookie', FILTER_UNSAFE_RAW ) );
    if ( !empty( $cookieStatus ) ) {
        $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE id = %d", intval( $cookieStatus ) ), OBJECT );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( is_object( $data ) ) {
        echo esc_attr( $data->name . '//' . $data->email . '//' . $data->ID );
    }
    die;
}

// add_action('wp_ajax_nopriv_webinarignition_update_master_switch', 'webinarignition_update_master_switch_callback');
add_action( 'wp_ajax_webinarignition_update_master_switch', 'webinarignition_update_master_switch_callback' );
function webinarignition_update_master_switch_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    $status = sanitize_text_field( filter_input( INPUT_POST, 'status', FILTER_UNSAFE_RAW ) );
    // Return Option Object:
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    $results->webinar_switch = $status;
    update_option( 'webinarignition_campaign_' . $ID, $results );
    wp_send_json_success();
    wp_die();
}

// SAVE AIR MESSAGE
add_action( 'wp_ajax_nopriv_webinarignition_save_air', 'webinarignition_save_air_callback' );
add_action( 'wp_ajax_webinarignition_save_air', 'webinarignition_save_air_callback' );
function webinarignition_save_air_callback() {
    // Verify nonce for security
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get the webinar ID
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    // Decode the JSON string containing all CTAs
    $live_ctas = json_decode( stripslashes( $_POST['ctas'] ?? '' ), true );
    // Get the existing webinar data
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    if ( empty( $live_ctas ) || !is_array( $live_ctas ) ) {
        $results->live_ctas = [];
        update_option( 'webinarignition_campaign_' . $ID, $results );
        wp_send_json_error( [
            'message' => 'No CTAs found',
            'Ctas'    => $results->live_ctas,
        ] );
        exit;
    }
    $results->live_ctas = [];
    // Initialize the live_ctas array
    // Loop through each CTA and update the webinar data
    foreach ( $live_ctas as $cta ) {
        $ctaAirToggle = sanitize_text_field( $cta['air_toggle'] ?? 'off' );
        $isTabNameAvailable = sanitize_text_field( $cta['isTabNameAvailable'] ?? '0' );
        $isAdvaceIframeActive = sanitize_text_field( $cta['isAdvaceIframeActive'] ?? '0' );
        // Sanitize HTML content
        $airAmeliaToggle = sanitize_text_field( $cta['air_amelia_toggle'] ?? 'off' );
        $ctaId = sanitize_text_field( $cta['ctaId'] );
        $contenta = wp_kses_post( $cta['response'] );
        // Sanitize HTML content
        $ctaPosition = sanitize_text_field( $cta['cta_position'] );
        $tabName = sanitize_text_field( $cta['tab_text'] );
        $buttonText = sanitize_text_field( $cta['button_text'] );
        $buttonUrl = esc_url_raw( $cta['button_url'] );
        $buttonColor = sanitize_hex_color( $cta['button_color'] );
        $broadcastMessageWidth = sanitize_text_field( $cta['box_width'] ?? '60%' );
        $broadcastMessageBgTransparency = intval( $cta['bg_transparency'] ?? 0 );
        $broadcastMessageAlignment = sanitize_text_field( $cta['box_alignment'] ?? 'center' );
        // Store the CTA data in an array
        $results->live_ctas[$ctaId] = [
            'ctaId'                => $ctaId,
            'air_toggle'           => $ctaAirToggle,
            'isAdvaceIframeActive' => $isAdvaceIframeActive,
            'air_amelia_toggle'    => $airAmeliaToggle,
            'response'             => $contenta,
            'cta_position'         => $ctaPosition,
            'isTabNameAvailable'   => $isTabNameAvailable,
            'tab_text'             => $tabName,
            'button_text'          => $buttonText,
            'button_url'           => $buttonUrl,
            'button_color'         => $buttonColor,
            'box_width'            => $broadcastMessageWidth,
            'bg_transparency'      => $broadcastMessageBgTransparency,
            'box_alignment'        => $broadcastMessageAlignment,
        ];
    }
    // Save the updated webinar data
    $updated = update_option( 'webinarignition_campaign_' . $ID, $results );
    if ( $updated ) {
        $notification_service_url = 'https://nodejs-small-wildflower-8030.fly.dev/cta-updated';
        error_log( 'Notification service URL: ' . $notification_service_url );
        $data = array(
            'id'        => 'webinarID-' . $ID,
            'site'      => site_url(),
            'action'    => 'updated',
            'title'     => $results->webinarURLName2,
            'timestamp' => current_time( 'c' ),
        );
        $response = wp_remote_post( $notification_service_url, array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body'    => json_encode( $data ),
            'timeout' => 15,
        ) );
        error_log( 'Notification service response: ' . print_r( $response, true ) );
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( [
                'message' => 'Notification service error: ' . $response->get_error_message(),
            ] );
            error_log( 'Notification service error: ' . $response->get_error_message() );
        } else {
            wp_send_json_success( [
                'message' => 'CTAs saved successfully',
                'ctas'    => $results->live_ctas,
            ] );
        }
    } else {
        wp_send_json_error( [
            'message' => 'Failed to save CTAs',
        ] );
    }
    wp_die();
}

add_action( 'wp_ajax_nopriv_webinarignition_track_order', 'webinarignition_track_order_callback' );
add_action( 'wp_ajax_webinarignition_track_order', 'webinarignition_track_order_callback' );
function webinarignition_track_order_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    global $wpdb;
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    $lead = sanitize_text_field( filter_input( INPUT_POST, 'lead', FILTER_UNSAFE_RAW ) );
    if ( empty( $ID ) || empty( $lead ) ) {
        wp_send_json( 'invalid webinar or lead id' );
    }
    $webinarData = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    if ( empty( $webinarData ) ) {
        wp_send_json( 'webinar not found: ' . $ID );
    }
    $table_db_name = ( webinarignition_is_auto( $webinarData ) ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads' );
    $is_lead_protected = !empty( $webinarData->protected_lead_id ) && 'protected' === $webinarData->protected_lead_id;
    if ( $is_lead_protected ) {
        $updated = $wpdb->update(
            $table_db_name,
            array(
                'trk2' => 'Yes',
            ),
            array(
                'hash_ID' => $lead,
            ),
            // no prepare here
            array('%s'),
            array('%s')
        );
    } else {
        $updated = $wpdb->update(
            $table_db_name,
            array(
                'trk2' => 'Yes',
            ),
            array(
                'id' => $lead,
            ),
            // no prepare here either
            array('%s'),
            array('%d')
        );
    }
    do_action( 'webinarignition_lead_purchased', $lead, $ID );
    wp_send_json( 'tracked lead' );
}

// Store New / Add Phone Number webinarignition_store_phone
add_action( 'wp_ajax_nopriv_webinarignition_store_phone', 'webinarignition_store_phone_callback' );
add_action( 'wp_ajax_webinarignition_store_phone', 'webinarignition_store_phone_callback' );
function webinarignition_store_phone_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get Variables
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    $PHONE = sanitize_text_field( filter_input( INPUT_POST, 'phone', FILTER_UNSAFE_RAW ) );
    $ID = intval( $ID );
    // Sanitize the ID to ensure it's an integer
    // Prepare and execute the query
    $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $ID ), OBJECT );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    if ( empty( $lead ) ) {
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `hash_ID` = %d", $ID ), OBJECT );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( !empty( $lead ) ) {
        $ID = $lead->ID;
    }
    // Set Phone Number
    $wpdb->update( $table_db_name, array(
        'phone' => $PHONE,
    ), array(
        'id' => $ID,
    ) );
}

// Store New / Add Phone Number webinarignition_store_phone
add_action( 'wp_ajax_nopriv_webinarignition_store_phone_auto', 'webinarignition_store_phone_auto_callback' );
add_action( 'wp_ajax_webinarignition_store_phone_auto', 'webinarignition_store_phone_auto_callback' );
function webinarignition_store_phone_auto_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get Variables
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
    $ID = sanitize_text_field( filter_input( INPUT_POST, 'id', FILTER_UNSAFE_RAW ) );
    $PHONE = sanitize_text_field( filter_input( INPUT_POST, 'phone', FILTER_UNSAFE_RAW ) );
    $ID = intval( $ID );
    // Sanitize the ID to ensure it's an integer
    // Prepare and execute the query
    $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $ID ), OBJECT );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    if ( empty( $lead ) ) {
        // Prepare and execute the query
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `id` = %d", $ID ), OBJECT );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( !empty( $lead ) ) {
        $ID = $lead->ID;
    }
    // Set Phone Number
    $wpdb->query( $wpdb->prepare( "UPDATE `{$table_db_name}` SET `phone` = %s WHERE `id` = %d", $PHONE, $ID ) );
}

// Get Timezone & Local Time For Users
add_action( 'wp_ajax_nopriv_webinarignition_get_local_tz', 'webinarignition_get_local_tz_callback' );
add_action( 'wp_ajax_webinarignition_get_local_tz', 'webinarignition_get_local_tz_callback' );
function webinarignition_get_local_tz_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get Olson Time ::
    $timezone = sanitize_text_field( filter_input( INPUT_POST, 'tz', FILTER_UNSAFE_RAW ) );
    $dtz = new DateTimeZone($timezone);
    $time_in_sofia = new DateTime('now', $dtz);
    $offset = $dtz->getOffset( $time_in_sofia ) / 3600;
    echo "<i class='icon-globe' style='margin-right: 10px;' ></i> <b>UTC</b> :: " . (( $offset < 0 ? esc_attr( $offset ) : '+' . esc_attr( $offset ) )) . "<i class='icon-time' style='margin-left: 10px; margin-right:10px;' ></i><b>" . esc_html__( 'Local Time', 'webinar-ignition' ) . '</b> :: ' . esc_attr( gmdate( 'g:i A' ) );
    die;
}

// Get Timezone & Local Time For Users
add_action( 'wp_ajax_nopriv_webinarignition_get_local_tz_set', 'webinarignition_get_local_tz_set_callback' );
add_action( 'wp_ajax_webinarignition_get_local_tz_set', 'webinarignition_get_local_tz_set_callback' );
function webinarignition_get_local_tz_set_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    // Get Olson Time ::
    $timezone = sanitize_text_field( filter_input( INPUT_POST, 'tz', FILTER_UNSAFE_RAW ) );
    $dtz = new DateTimeZone($timezone);
    $time_in_sofia = new DateTime('now', $dtz);
    $offset = $dtz->getOffset( $time_in_sofia ) / 3600;
    $set = ( $offset < 0 ? $offset : '+' . $offset );
    // ReFormat UTC - GMT and half'rs
    if ( '+0' === $set ) {
        $set = '0';
    } elseif ( '-9.5' === $set ) {
        $set = '-930';
    } elseif ( '-4.5' === $set ) {
        $set = '-430';
    } elseif ( '+5.5' === $set ) {
        $set = '+530';
    } elseif ( '+5.75' === $set ) {
        $set = '+545';
    } elseif ( '+6.5' === $set ) {
        $set = '+630';
    } elseif ( '+9.5' === $set ) {
        $set = '+930';
    }
    echo esc_html( $set );
    die;
}

function webinarignition_live_lead_import(  $import_data, $arfield_data = array()  ) {
    $post_input['id'] = $import_data['app_id'];
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $post_input['id'] );
    $applang = $webinar_data->webinar_lang;
    $post_input['name'] = ( isset( $import_data['full_name'] ) ? $import_data['full_name'] : '' );
    $post_input['email'] = ( isset( $import_data[WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang )] ) ? $import_data[WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang )] : '' );
    $post_input['phone'] = ( isset( $import_data[WebinarignitionManager::webinarignition_ar_field_translated_name( 'optPhone', $applang )] ) ? $import_data[WebinarignitionManager::webinarignition_ar_field_translated_name( 'optPhone', $applang )] : '' );
    $post_input['gdpr_data'] = NULL;
    $post_input['ip'] = NULL;
    $post_input['timezone'] = ( !empty( $import_data['webinar_timezone'] ) && $import_data['webinar_timezone'] != '' ? $import_data['webinar_timezone'] : (( isset( $webinar_data->webinar_timezone ) ? $webinar_data->webinar_timezone : '' )) );
    $post_input['gdpr_data'] = NULL;
    $post_input['ip'] = NULL;
    $post_input['created_date'] = gmdate( 'F j, Y' );
    $post_input['attended'] = '';
    $arfields_array = array();
    $change_name_fields = array(
        WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang ) => array(
            'field_key_name'  => 'optEmail',
            'field_key_label' => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang ),
        ),
        WebinarignitionManager::webinarignition_ar_field_translated_name( 'optPhone', $applang ) => array(
            'field_key_name'  => 'optPhone',
            'field_key_label' => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optPhone', $applang ),
        ),
        WebinarignitionManager::webinarignition_ar_field_translated_name( 'optName', $applang )  => array(
            'field_key_name'  => 'optName',
            'field_key_label' => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optName', $applang ),
        ),
        WebinarignitionManager::webinarignition_ar_field_translated_name( 'optLName', $applang ) => array(
            'field_key_name'  => 'optLName',
            'field_key_label' => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optLName', $applang ),
        ),
    );
    foreach ( $arfield_data as $field_name => $field ) {
        if ( isset( $change_name_fields[$field_name] ) ) {
            $field_label = $change_name_fields[$field_name]['field_key_label'];
            $field_value = sanitize_text_field( $field );
            $field_name = $change_name_fields[$field_name]['field_key_name'];
        } else {
            $field_label = sanitize_text_field( $field_name );
            $field_value = sanitize_text_field( $field );
        }
        $arfields_array[$field_name] = array(
            'label' => $field_label,
            'value' => $field_value,
        );
        $salutation = ( isset( $arfields_array['optSalutation'] ) ? sanitize_text_field( $arfields_array['optSalutation']['value'] ) : '' );
        $first_name = ( isset( $arfields_array['optFName'] ) ? sanitize_text_field( $arfields_array['optFName']['value'] ) : $arfields_array['optName']['value'] );
        $last_name = ( isset( $arfields_array['optLName'] ) ? sanitize_text_field( $arfields_array['optLName']['value'] ) : '' );
        $field_value = trim( implode( ' ', array_filter( [$salutation, $first_name, $last_name] ) ) );
        $arfields_array['full_name'] = array(
            'label' => 'full_name',
            'value' => $field_value,
        );
        // If salutation is
    }
    $first_name = ( isset( $arfields_array['optFName'] ) ? sanitize_text_field( $arfields_array['optFName']['value'] ) : $arfields_array['optName']['value'] );
    $last_name = ( isset( $arfields_array['optLName'] ) ? sanitize_text_field( $arfields_array['optLName']['value'] ) : '' );
    $lead_name = trim( implode( ' ', array_filter( [$first_name, $last_name] ) ) );
    if ( !empty( $webinar_data->webinar_lang ) ) {
        $applang = $webinar_data->webinar_lang;
        switch_to_locale( $applang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    }
    if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
        $webinar_data->time_format = get_option( 'time_format', 'H:i' );
    }
    $time_format = $webinar_data->time_format;
    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
    global $wpdb;
    $is_ajax = false;
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        $is_ajax = true;
    }
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    if ( $is_lead_protected ) {
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT hash_ID AS ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $post_input['email'], $post_input['id'] ) );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    } else {
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $post_input['email'], $post_input['id'] ) );
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    }
    if ( $lead ) {
        return;
        $lead_id = ( isset( $lead->hash_ID ) ? $lead->hash_ID : $lead->ID );
        $is_unsubscribed_to_all = update_option( 'lead_data_restricted_mails' . $post_input['email'], false );
        $webinar_restricted_emails = get_option( 'webinar_data_restricted_mails' . $post_input['id'], array() );
        if ( in_array( $post_input['email'], $webinar_restricted_emails ) ) {
            $key = array_search( $post_input['email'], $webinar_restricted_emails );
            if ( $key !== false ) {
                unset($webinar_restricted_emails[$key]);
            }
            update_option( 'webinar_data_restricted_mails' . $post_input['id'], $webinar_restricted_emails );
        }
        $deleted = $wpdb->delete( $table_db_name, [
            'email'  => $post_input['email'],
            'app_id' => $post_input['id'],
        ], ['%s', '%s'] );
        $lead = NULL;
    }
    if ( $lead ) {
        wp_send_json( $lead->ID );
    }
    $data = array(
        'app_id'              => intval( $post_input['id'] ),
        'name'                => $lead_name,
        'email'               => $post_input['email'],
        'phone'               => $post_input['phone'],
        'trk3'                => $post_input['ip'],
        'lead_browser_and_os' => $post_input['timezone'],
        'event'               => 'No',
        'replay'              => 'No',
        'created'             => $post_input['created_date'],
        'lead_status'         => $post_input['attended'],
        'gdpr_data'           => $post_input['gdpr_data'],
    );
    $wpdb->insert( $table_db_name, $data );
    $out = $wpdb->insert_id;
    $hash_ID = sha1( $post_input['id'] . $post_input['email'] . $out );
    $wpdb->update( $table_db_name, array(
        'hash_ID' => $hash_ID,
    ), array(
        'ID' => $out,
    ) );
    $lead_meta = $arfields_array;
    if ( !empty( $lead_meta ) ) {
        $lead_meta = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm',
            serialize( $lead_meta ),
            'live'
        );
        WebinarignitionLeadsManager::webinarignition_update_lead_meta(
            $out,
            'wiRegForm_' . $post_input['id'],
            serialize( $lead_meta ),
            'live'
        );
        /**
         * Action Hook: webinarignition_live_lead_added
         *
         * @param int $webinar_id Webinar ID for which the lead was added
         * @param int $lead_id Lead ID which was added
         * @param array $lead_metadata Associated lead metadata
         */
        $webhook_lead_data = array();
        foreach ( $lead_meta as $lead_meta_key => $lead_meta_value ) {
            if ( is_array( $lead_meta_value ) ) {
                $webhook_lead_data[$lead_meta_key] = $lead_meta_value['value'];
            }
        }
        do_action(
            'webinarignition_lead_added',
            absint( $post_input['id'] ),
            $out,
            $webhook_lead_data
        );
        do_action(
            'webinarignition_live_lead_added',
            absint( $post_input['id'] ),
            $out,
            $webhook_lead_data
        );
        // do_action( 'webinarignition_lead_status_changed', 'attended', $out, absint( $post_input['id'] ) );
    }
    //end if
    do_action(
        'webinarignition_lead_imported',
        $out,
        $data,
        $lead_meta
    );
    do_action( 'webinarignition_lead_created', $out, $table_db_name );
    $lead_details_string = "Name: {$post_input['name']}\nEmail: {$post_input['email']}\n";
    if ( isset( $post_input['phone'] ) && 'undefined' !== $post_input['phone'] ) {
        $lead_details_string .= "Phone: {$post_input['phone']}";
    }
    // registration email has been disabled in notification settings
    if ( 'off' === $webinar_data->email_signup ) {
        WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ) . "\n{$lead_details_string}\n\n" . __( 'Not sending registration email (DISABLED)', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
        if ( $is_lead_protected ) {
            echo esc_html( $hash_ID );
        } else {
            echo esc_attr( $out );
        }
        die;
    }
    WebinarIgnition_Logs::add( __( 'New Lead Added', 'webinar-ignition' ) . "\n\${$lead_details_string}\n\n" . __( 'Firing registration email', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
    if ( !empty( $webinar_data->templates_version ) || !empty( $webinar_data->use_new_email_signup_template ) && 'yes' === $webinar_data->use_new_email_signup_template ) {
        // use new templates
        $webinar_data->emailheading = $webinar_data->email_signup_heading;
        $webinar_data->emailpreview = $webinar_data->email_signup_preview;
        $webinar_data->bodyContent = $webinar_data->email_signup_body;
        $webinar_data->footerContent = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ? $webinar_data->local_email_signup_footer : '' );
        $email = new WI_Emails();
        $emailBody = $email->webinarignition_build_email( $webinar_data );
    } else {
        // This is an old webinar, created before this version
        $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
        $emailBody = $emailHead;
        $emailBody .= $webinar_data->email_signup_body;
        $emailBody .= '</html>';
    }
    $emailBody = str_replace( '{LEAD_NAME}', ( !empty( $post_input['firstName'] ) ? sanitize_text_field( $post_input['firstName'] ) : $post_input['name'] ), $emailBody );
    $emailBody = str_replace( '{FIRSTNAME}', ( !empty( $post_input['firstName'] ) ? sanitize_text_field( $post_input['firstName'] ) : $post_input['name'] ), $emailBody );
    $localized_date = webinarignition_get_localized_date( $webinar_data, $data );
    $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
    // Replace
    if ( $is_lead_protected ) {
        $ics_lid = $hash_ID;
    } else {
        $ics_lid = $out;
    }
    $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
    $googleCalendarURL = add_query_arg( array(
        'googlecalendar' => '1',
        'lid'            => $ics_lid,
        'webinar'        => '',
    ), $thankyou_URL );
    ob_start();
    ?>
	<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php 
    echo esc_url_raw( $googleCalendarURL );
    ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
		<i class="icon-google-plus"></i>
		<?php 
    webinarignition_display( $webinar_data->ty_calendar_google, __( 'Add to Google Calendar', 'webinar-ignition' ) );
    ?>
	</a>
	<?php 
    $webinar_email_timezone = ( $post_input['timezone'] ? $post_input['timezone'] : $webinar_data->webinar_timezone );
    $original_time = $webinar_data->webinar_start_time;
    // e.g., '2025-05-07 15:00:00'
    $original_timezone = new DateTimeZone($webinar_data->webinar_timezone);
    // e.g., 'Asia/Riyadh'
    $target_timezone = new DateTimeZone($webinar_email_timezone);
    // e.g., 'Asia/Karachi'
    // Create DateTime object in original timezone
    $date = new DateTime($original_time, $original_timezone);
    // Convert to target timezone
    $date->setTimezone( $target_timezone );
    // Format using provided format
    $converted_time = $date->format( $time_format );
    $wi_calendar_url = ob_get_clean();
    $webinar_duration = ( isset( $webinar_data->webinar_start_duration ) ? sanitize_text_field( $webinar_data->webinar_start_duration ) : 60 );
    /* translators: %s: Webinar duration in minutes. */
    $duration_txt = esc_html( sprintf( __( 'Duration: %s minutes', 'webinar-ignition' ), $webinar_duration ) );
    $emailBody = str_replace( '{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz(
        $converted_time,
        $time_format,
        $webinar_email_timezone,
        false,
        $timeonly
    ) . ' ' . $wi_calendar_url . '<br>' . $duration_txt, $emailBody );
    $send_mail = true;
    // Format current datetime
    $current_datetime = new DateTime('now', new DateTimeZone($webinar_data->webinar_timezone));
    $webinar_datetime = DateTime::createFromFormat( 'm-d-Y h:i A', $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time, new DateTimeZone($webinar_data->webinar_timezone) );
    // Calculate difference in seconds
    $diff_in_seconds = $webinar_datetime->getTimestamp() - $current_datetime->getTimestamp();
    // If webinar is within 5 minutes, use current datetime
    if ( $diff_in_seconds <= 300 && $diff_in_seconds > 0 ) {
        $send_mail = false;
    }
    // Add Unsubscribe Text
    $unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
	<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
	<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
	</p>';
    $webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );
    // Check if the setting value is 1
    if ( 1 === absint( $webinarignition_unsubscribe_links ) ) {
        $emailBody .= $unsubscribe_text;
    }
    $emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $emailBody );
    $email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
    $event_data = [
        'id'      => $ics_lid,
        'webdata' => $webinar_data,
    ];
    $ics_file = generate_ics_file( $event_data );
    $unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
    $unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
    $unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $unsubscribe_next_header_url );
    $unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $out, $unsubscribe_all_header_url );
    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
        'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
        'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>'
    );
    $attachments = [$ics_file];
    webinarignition_test_smtp_options();
    if ( $send_mail ) {
        try {
            if ( !wp_mail(
                $post_input['email'],
                $email_signup_sbj,
                $emailBody,
                $headers,
                $attachments
            ) ) {
                WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$post_input['email']}", WebinarIgnition_Logs::LIVE_EMAIL );
            } else {
                WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinar-ignition' ), $post_input['id'], WebinarIgnition_Logs::LIVE_EMAIL );
            }
        } catch ( Exception $e ) {
            WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$post_input['email']}", WebinarIgnition_Logs::LIVE_EMAIL );
        }
    }
    // if ( ! empty( $webinar_data->get_registration_notices_state ) && ( 'show' === $webinar_data->get_registration_notices_state ) && ( ! empty( $webinar_data->registration_notice_email ) ) && filter_var( $webinar_data->registration_notice_email, FILTER_VALIDATE_EMAIL ) ) {
    // 	$subj         = __( 'New Registration For', 'webinar-ignition' ) . ' ' . $webinar_data->webinar_desc . ' ' . __( 'By', 'webinar-ignition' ) . ' ' . $post_input['name'];
    // 	$attendeeName = $post_input['name'];
    // 	$emailBody = $emailHead;
    // 	if ( ! empty( $lead_meta ) ) {
    // 		foreach ( $lead_meta as $lead_field_key => $lead_field_data ) {
    // 			if ( 'optName' === $lead_field_key && '#firstlast#' === $lead_field_data['value'] ) {
    // 				continue; // Skip first last tag
    // 			}
    // 			$emailBody .= "<br><br>{$lead_field_data['label']}: {$lead_field_data['value']}";
    // 		}
    // 	}
    // 	$emailBody .= '</html>';
    // 	try {
    // 		wp_mail( $webinar_data->registration_notice_email, $subj, $emailBody, $headers );
    // 	} catch ( Exception $e ) {
    // 		echo esc_html( $e->getMessage() );
    // 	}
    // } //end if
    if ( !empty( $webinar_data->webinar_lang ) ) {
        restore_previous_locale();
    }
}

add_action( 'wp_ajax_reh_wi_handle_csv_preview', 'reh_wi_handle_csv_preview_callback' );
function reh_wi_handle_csv_preview_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( $_POST['security'] ?? '' ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
    }
    if ( !isset( $_FILES['csv_file'] ) ) {
        wp_send_json_error( [
            'message' => 'No CSV file provided.',
        ] );
    }
    require_once ABSPATH . 'wp-admin/includes/file.php';
    $uploadedfile = $_FILES['csv_file'];
    $upload_overrides = [
        'test_form' => false,
    ];
    $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
    if ( $movefile && !isset( $movefile['error'] ) ) {
        WP_Filesystem();
        global $wp_filesystem;
        $target_path = $movefile['file'];
        $csv_data = $wp_filesystem->get_contents( $target_path );
        $lines = explode( "\n", trim( $csv_data ) );
        $first_line = $lines[0] ?? '';
        $preview_row = ( isset( $lines[1] ) ? str_getcsv( $lines[1] ) : [] );
        $headers = str_getcsv( $first_line );
        $headers = array_map( 'trim', $headers );
        wp_send_json_success( [
            'headers'     => $headers,
            'file'        => $target_path,
            'preview_row' => $preview_row,
        ] );
    }
    wp_send_json_error( [
        'message' => 'Failed to upload and read CSV.',
    ] );
}

add_action( 'wp_ajax_reh_wi_handle_csv_upload_mapped', 'webinarignition_reh_wi_handle_csv_upload_mapped_callback' );
function webinarignition_reh_wi_handle_csv_upload_mapped_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( $_POST['security'] ?? '' ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( 'Invalid security token' );
    }
    global $wpdb;
    $app_id = (int) sanitize_text_field( $_POST['id'] );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );
    $applang = $webinar_data->webinar_lang;
    $time_format = $webinar_data->time_format;
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y' );
    $target_path = sanitize_text_field( $_POST['file'] ?? '' );
    $mapping = $_POST['mapping'] ?? [];
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    WP_Filesystem();
    global $wp_filesystem;
    if ( $wp_filesystem->exists( $target_path ) ) {
        $csv_data = $wp_filesystem->get_contents( $target_path );
        $lines = explode( "\n", trim( $csv_data ) );
        if ( count( $lines ) < 2 ) {
            wp_send_json_error( 'CSV contains no data.' );
        }
        // Read header and rows
        $headers = str_getcsv( $lines[0] );
        $data_lines = array_slice( $lines, 1 );
        $mapped_indexes = [];
        foreach ( $headers as $index => $header ) {
            $header = trim( $header );
            if ( $header === '' ) {
                continue;
                // Skip empty header
            }
            // Find the field_key this header is mapped to (reverse match)
            $matched_field_key = array_search( $header, $mapping, true );
            if ( $matched_field_key && $matched_field_key !== '' ) {
                $headers[$index] = $matched_field_key;
                $mapped_indexes[] = $index;
                // Save only indexes that are mapped
            } else {
                unset($headers[$index]);
                // Remove unmapped header
            }
        }
        // Reindex headers to ensure array_combine works correctly
        $headers = array_values( $headers );
        $mapped_indexes = array_values( $mapped_indexes );
        unset($header);
        // break reference
        $csv_array = array();
        // new code
        $fields_to_extract = [
            'Registration date',
            'Attended',
            WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang ),
            WebinarignitionManager::webinarignition_ar_field_translated_name( 'optPhone', $applang ),
            'webinar_timezone'
        ];
        foreach ( $lines as $index => $line ) {
            $row = str_getcsv( $line );
            // First row: set headers
            if ( $index === 0 ) {
                continue;
            }
            // Skip rows that are empty or all values are empty after trimming
            if ( empty( array_filter( $row, function ( $value ) {
                return trim( $value ) !== '';
            } ) ) ) {
                continue;
            }
            // Only keep the values that correspond to mapped headers
            $filtered_values = [];
            foreach ( $mapped_indexes as $i => $original_index ) {
                $filtered_values[] = ( isset( $row[$original_index] ) ? $row[$original_index] : '' );
            }
            // Combine with filtered headers
            $full_row = array_combine( $headers, $filtered_values );
            // Remove core fields from full_row to get all remaining values
            $all_row = $full_row;
            unset($all_row['Registration date'], $all_row['Attended']);
            // Create filtered_row with selected fields
            $filtered_row = [];
            foreach ( $fields_to_extract as $field ) {
                $filtered_row[$field] = ( isset( $full_row[$field] ) ? $full_row[$field] : '' );
            }
            $filtered_row['app_id'] = $app_id;
            // Add app_id to filtered_row
            webinarignition_live_lead_import( $filtered_row, $all_row );
        }
        if ( file_exists( $target_path ) ) {
            wp_delete_file( $target_path );
        }
        wp_send_json_success( [
            'data' => $csv_array,
        ] );
    } else {
        wp_send_json_error( 'Failed to read the CSV file.' );
    }
}

// Add CSV Lead
add_action( 'wp_ajax_nopriv_webinarignition_import_csv_leads', 'webinarignition_import_csv_leads_callback' );
add_action( 'wp_ajax_webinarignition_import_csv_leads', 'webinarignition_import_csv_leads_callback' );
function webinarignition_import_csv_leads_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $post_input = array(
        'id'  => absint( filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT ) ),
        'csv' => wp_kses_post( filter_input( INPUT_POST, 'csv' ) ),
    );
    global $wpdb;
    $app_id = (int) sanitize_text_field( $post_input['id'] );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $app_id );
    $time_format = $webinar_data->time_format;
    $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : 'l, F j, Y' );
    // Normalize line endings
    $csv_string = str_replace( array("\r\n", "\r"), "\n", $post_input['csv'] );
    // Split into lines
    $lines = explode( "\n", $csv_string );
    // Parse each line as CSV
    $leads = array();
    foreach ( $lines as $line ) {
        if ( trim( $line ) === '' ) {
            continue;
        }
        // skip empty lines
        $leads[] = str_getcsv( $line );
    }
    $table_db_name = $wpdb->prefix . 'webinarignition_leads';
    foreach ( $leads as $key => $lead ) {
        $name = ( isset( $lead[0] ) ? trim( $lead[0] ) : '' );
        $email = ( isset( $lead[1] ) ? trim( $lead[1] ) : '' );
        ( isset( $lead[2] ) ? $phone = trim( $lead[2] ) : ($phone = '') );
        if ( 'email' === strtolower( $email ) ) {
            continue;
        }
        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT ID FROM {$table_db_name} WHERE email = %s AND app_id = %d", $email, $app_id ) );
        if ( $lead ) {
            $is_unsubscribed_to_all = update_option( 'lead_data_restricted_mails' . $email, false );
            $webinar_restricted_emails = get_option( 'webinar_data_restricted_mails' . $app_id, array() );
            if ( in_array( $email, $webinar_restricted_emails ) ) {
                $key = array_search( $email, $webinar_restricted_emails );
                if ( $key !== false ) {
                    unset($webinar_restricted_emails[$key]);
                }
                update_option( 'webinar_data_restricted_mails' . $app_id, $webinar_restricted_emails );
            }
            $deleted = $wpdb->delete( $table_db_name, [
                'email'  => $email,
                'app_id' => $app_id,
            ], ['%s', '%s'] );
        }
        $wpdb->query( $wpdb->prepare(
            "INSERT INTO {$table_db_name}\n\t\t\t(app_id, name, email, phone, trk1, trk3, event, replay, created)\n\t\t\tVALUES (%d, %s, %s, %s, %s, %s, %s, %s, %s)",
            intval( $app_id ),
            sanitize_text_field( $name ),
            sanitize_email( $email ),
            sanitize_text_field( $phone ),
            'import',
            '-',
            'No',
            'No',
            gmdate( 'F j, Y' )
        ) );
        $new_lead_id = $wpdb->insert_id;
        $hash_ID = sha1( $app_id . $email . $new_lead_id );
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET hash_ID = %s WHERE ID = %d", $hash_ID, $new_lead_id ) );
        do_action(
            'webinarignition_lead_added',
            absint( $app_id ),
            $new_lead_id,
            array()
        );
        do_action(
            'webinarignition_live_lead_added',
            absint( $app_id ),
            $new_lead_id,
            array()
        );
        do_action( 'webinarignition_auto_register_import', absint( $app_id ), $new_lead_id );
        // do_action( 'webinarignition_lead_status_changed', 'attended', $new_lead_id, absint( $app_id ) );
        do_action( 'webinarignition_lead_created', $new_lead_id, $table_db_name );
        if ( !empty( $webinar_data->templates_version ) || !empty( $webinar_data->use_new_email_signup_template ) && 'yes' === $webinar_data->use_new_email_signup_template ) {
            // use new templates
            $webinar_data->emailheading = $webinar_data->email_signup_heading;
            $webinar_data->emailpreview = $webinar_data->email_signup_preview;
            $webinar_data->bodyContent = $webinar_data->email_signup_body;
            $webinar_data->footerContent = ( property_exists( $webinar_data, 'show_or_hide_local_email_signup_footer' ) && 'show' === $webinar_data->show_or_hide_local_email_signup_footer ? $webinar_data->local_email_signup_footer : '' );
            $email_obj = new WI_Emails();
            $emailBody = $email_obj->webinarignition_build_email( $webinar_data );
        } else {
            // This is an old webinar, created before this version
            $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
            $emailBody = $emailHead;
            $emailBody .= $webinar_data->email_signup_body;
            $emailBody .= '</html>';
        }
        $emailBody = str_replace( '{LEAD_NAME}', ( !empty( $name ) ? sanitize_text_field( $name ) : $name ), $emailBody );
        $emailBody = str_replace( '{FIRSTNAME}', ( !empty( $name ) ? sanitize_text_field( $name ) : $name ), $emailBody );
        $localized_date = webinarignition_get_localized_date( $webinar_data );
        $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
        $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
        // Replace
        if ( $is_lead_protected ) {
            $ics_lid = $hash_ID;
        } else {
            $ics_lid = $new_lead_id;
        }
        $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
        $googleCalendarURL = add_query_arg( array(
            'googlecalendar' => '1',
            'lid'            => $ics_lid,
            'webinar'        => '',
        ), $thankyou_URL );
        ob_start();
        ?>
		<a style="color: rgba(102, 102, 102, 1); text-decoration: underline" href="<?php 
        echo esc_url_raw( $googleCalendarURL );
        ?>" class="small button wiButton wiButton-info wiButton-block" target="_blank">
			<i class="icon-google-plus"></i>
			<?php 
        webinarignition_display( $webinar_data->ty_calendar_google, __( 'Add to Google Calendar', 'webinar-ignition' ) );
        ?>
		</a>
		<?php 
        $wi_calendar_url = ob_get_clean();
        $emailBody = str_replace( '{DATE}', $localized_date . ' @ ' . webinarignition_get_time_tz(
            $webinar_data->webinar_start_time,
            $time_format,
            $webinar_data->webinar_timezone,
            false,
            $timeonly
        ) . ' ' . $wi_calendar_url, $emailBody );
        // Add Unsubscribe Text
        $unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
		<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
		<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
		</p>';
        $webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );
        // Check if the setting value is 1
        if ( 1 === absint( $webinarignition_unsubscribe_links ) ) {
            $emailBody .= $unsubscribe_text;
        }
        $emailBody = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $new_lead_id, $emailBody );
        $email_signup_sbj = str_replace( '{TITLE}', $webinar_data->webinar_desc, $webinar_data->email_signup_sbj );
        $event_data = [
            'id'      => $ics_lid,
            'webdata' => $webinar_data,
        ];
        $ics_file = generate_ics_file( $event_data );
        $unsubscribe_next_header_url = '{SUBLINK}&unsubnextwebinar=true';
        $unsubscribe_all_header_url = '{SUBLINK}&unsuballwebinar=true';
        $unsubscribe_next_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $new_lead_id, $unsubscribe_next_header_url );
        $unsubscribe_all_header_url = WebinarignitionManager::webinarignition_replace_email_body_placeholders( $webinar_data, $new_lead_id, $unsubscribe_all_header_url );
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>',
            'List-Unsubscribe-Post: List-Unsubscribe=One-Click',
            'List-Unsubscribe: <' . $unsubscribe_next_header_url . '>, <' . $unsubscribe_all_header_url . '>'
        );
        $attachments = [$ics_file];
        webinarignition_test_smtp_options();
        try {
            if ( !wp_mail(
                $email,
                $email_signup_sbj,
                $emailBody,
                $headers,
                $attachments
            ) ) {
                WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL );
            } else {
                WebinarIgnition_Logs::add( __( 'Registration email has been sent.', 'webinar-ignition' ), $app_id, WebinarIgnition_Logs::LIVE_EMAIL );
            }
        } catch ( Exception $e ) {
            WebinarIgnition_Logs::add( __( 'Registration email could not be sent to', 'webinar-ignition' ) . " {$email}", WebinarIgnition_Logs::LIVE_EMAIL );
        }
    }
    //end foreach
    die;
}

add_action( 'wp_ajax_nopriv_wi_show_logs_get', 'webinarignition_ajax_show_logs' );
add_action( 'wp_ajax_wi_show_logs_get', 'webinarignition_ajax_show_logs' );
function webinarignition_ajax_show_logs() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $campaign_id = sanitize_text_field( filter_input( INPUT_POST, 'campaign_id' ) );
    $page = sanitize_text_field( filter_input( INPUT_POST, 'page' ) );
    $webinar = WebinarignitionManager::webinarignition_get_webinar_data( $campaign_id );
    $log_types = array(WebinarIgnition_Logs::LIVE_EMAIL, WebinarIgnition_Logs::LIVE_SMS);
    if ( 'AUTO' === $webinar->webinar_date ) {
        $log_types = array(WebinarIgnition_Logs::AUTO_EMAIL, WebinarIgnition_Logs::AUTO_SMS);
        $webinar->webinar_timezone = false;
    }
    webinarignition_show_logs(
        $webinar->id,
        $log_types,
        $page,
        $webinar->timezone
    );
    die;
}

add_action( 'wp_ajax_nopriv_wi_delete_logs', 'webinarignition_ajax_delete_logs' );
add_action( 'wp_ajax_wi_delete_logs', 'webinarignition_ajax_delete_logs' );
function webinarignition_ajax_delete_logs() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $campaign_id = sanitize_text_field( filter_input( INPUT_POST, 'campaign_id' ) );
    $logs = WebinarIgnition_Logs::webinarignition_deleteCampaignLogs( $campaign_id );
    return $logs;
}

function webinarignition_show_logs(
    $id,
    $log_types,
    $page,
    $timezone = false
) {
    $logs = WebinarIgnition_Logs::webinarignition_getLogs(
        $id,
        $log_types,
        $page,
        $timezone
    );
    ?>
	<table>
		<tr>
			<th>Date</th>
			<th>Message</th>
		</tr>
		<?php 
    foreach ( $logs as $log ) {
        ?>
			<tr>
				<td><?php 
        echo esc_html( $log->date );
        ?></td>
				<td><?php 
        echo nl2br( esc_attr( $log->message ) );
        ?></td>
			</tr>
		<?php 
    }
    ?>
	</table>
	<?php 
    WebinarIgnition_Logs::webinarignition_pagination( $id );
    ?>
	<?php 
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        die;
    }
}

add_action( 'wp_ajax_nopriv_webinarignition_broadcast_msg_poll_callback', 'webinarignition_broadcast_msg_poll_callback' );
add_action( 'wp_ajax_webinarignition_broadcast_msg_poll_callback', 'webinarignition_broadcast_msg_poll_callback' );
function webinarignition_broadcast_msg_poll_callback() {
    // error_log('Broadcast msg poll callback');
    // ! TODO: Use nonce verification if possible.
    if ( !isset( $_GET['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid nonce',
        ) );
        wp_die();
        // terminate the script if nonce is invalid
    }
    $ID = sanitize_text_field( $_GET['id'] );
    $IP = sanitize_text_field( $_GET['ip'] );
    $LEAD_ID = ( isset( $_GET['lead_id'] ) ? sanitize_text_field( $_GET['lead_id'] ) : '' );
    // Count User As Online -- User Tracking...
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_users_online';
    // Sanitize input values
    $ID = intval( $ID );
    $IP = sanitize_text_field( $IP );
    // Assuming $IP is a text field. Use appropriate sanitization if it's an IP address.
    // Prepare and execute the query
    $query = $wpdb->prepare(
        "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `ip` = %s AND `lead_id` = %d",
        $ID,
        $IP,
        $LEAD_ID
    );
    $lookUpIP = $wpdb->get_row( $wpdb->prepare(
        "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `ip` = %s AND `lead_id` = %d",
        $ID,
        $IP,
        $LEAD_ID
    ), OBJECT );
    if ( empty( $lookUpIP ) ) {
        // Not Found -- Add Users
        $wpdb->query( $wpdb->prepare(
            "INSERT INTO {$table_db_name} (app_id, ip, lead_id, dt) VALUES (%d, %s, %d, %s)",
            $ID,
            $IP,
            $LEAD_ID,
            gmdate( 'Y-m-d H:i:s' )
        ) );
    } else {
        // Found -- Update Time
        $wpdb->query( $wpdb->prepare( "UPDATE {$table_db_name} SET dt = %s WHERE id = %d", gmdate( 'Y-m-d H:i:s' ), $lookUpIP->ID ) );
    }
    // Purge All Who Havent been updated in 5 minutes...
    // $currentTime = date("Y-m-d H:i:s");
    // $currentTime = strtotime($currentTime);
    // $minus5Minutes = date("Y-m-d H:i:s", strtotime('-5 minutes', $currentTime));
    // $wpdb->query("DELETE FROM $table_db_name WHERE dt < '$minus5Minutes' ");
    // Return Option Object:
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    $is_lead_protected = !empty( $results->protected_lead_id ) && 'protected' === $results->protected_lead_id;
    // error_log('Webinar switch save button callback: ' . print_r($results->webinar_switch, true));
    // error_log('Webinar switch save button callback: ' . print_r($_GET['isPreview'], true));
    if ( property_exists( $results, 'webinar_switch' ) && !empty( $results->webinar_switch ) && 'live' != $results->webinar_switch && $_GET['isPreview'] != 'true' ) {
        // Air Message Not On
        // error_log('Webinar switch save button callback: ' . print_r($results->webinar_switch, true));
        wp_send_json( array(
            'webinar_closed' => 'YES',
            'air_toggle'     => 'OFF',
            'hash'           => '',
        ) );
    }
    $active_cta_found = false;
    // Flag to check if any active CTA is found
    // Ensure live_ctas exists and is an array
    if ( property_exists( $results, 'live_ctas' ) && is_array( $results->live_ctas ) ) {
        // Loop through all CTAs to check if any air_toggle is "on"
        foreach ( $results->live_ctas as $cta ) {
            if ( !empty( $cta['air_toggle'] ) && $cta['air_toggle'] === 'on' ) {
                $active_cta_found = true;
                // At least one CTA is active
                break;
                // Exit the loop early since we found an active CTA
            }
        }
        $cta_count = count( $results->live_ctas );
        $hashes = [];
        // If at least one CTA is active, process and send the response
        if ( $active_cta_found ) {
            foreach ( $results->live_ctas as $index => $cta ) {
                if ( !empty( $cta['air_toggle'] ) && $cta['air_toggle'] === 'on' ) {
                    $active_cta_found = true;
                    $showHTML = $cta['response'];
                    // Remove <iframe> tags for security purpose
                    $showHTML = preg_replace( '/<iframe[^>]*>.*?<\\/iframe>/is', '', $showHTML );
                    // Remove [advanced_iframe] shortcode for security purpose
                    $showHTML = preg_replace( '/\\[advanced_iframe[^\\]]*\\]/i', '', $showHTML );
                    $showHTML = str_replace( '<!DOCTYPE html><html><head></head><body>', '', $showHTML );
                    $showHTML = str_replace( '</body></html>', '', $showHTML );
                    $showHTML = stripcslashes( wpautop( $showHTML ) );
                    $bg_color = ( empty( $cta['button_color'] ) ? '#6BBA40' : $cta['button_color'] );
                    if ( empty( $cta['air_amelia_toggle'] ) || 'off' === $cta['air_amelia_toggle'] ) {
                        $air_amelia_toggle = 'off';
                    } else {
                        $air_amelia_toggle = 'on';
                    }
                    $table_db_name_leads = $wpdb->prefix . 'webinarignition_leads';
                    if ( $is_lead_protected ) {
                        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name_leads} WHERE `hash_ID` = %s AND `app_id` = %s", $LEAD_ID, $ID ) );
                        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
                    } else {
                        $lead = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table_db_name_leads} WHERE `ID` = %s AND `app_id` = %s", $LEAD_ID, $ID ) );
                        // phpcs:ignore WordPress.DB.DirectDatabaseQuery
                    }
                    if ( $lead ) {
                        $lead_meta = WebinarignitionLeadsManager::webinarignition_get_lead_meta( $lead->ID, 'wiRegForm', ( 'AUTO' === $results->webinar_date ? 'evergreen' : 'live' ) );
                        if ( !empty( $lead_meta['meta_value'] ) ) {
                            $lead_meta_data = maybe_unserialize( $lead_meta['meta_value'] );
                            $lead_meta_data = WebinarignitionLeadsManager::webinarignition_fix_opt_name( $lead_meta_data );
                        } else {
                            $lead_meta_data = array();
                        }
                        $showHTML = str_replace( '{EMAIL}', $lead->email, $showHTML );
                        if ( in_array( 'full_name', array_keys( $lead_meta_data ) ) ) {
                            $showHTML = str_replace( '{FULLNAME}', $lead_meta_data['full_name']['value'], $showHTML );
                        } else {
                            $showHTML = str_replace( '{FULLNAME}', $lead->name, $showHTML );
                        }
                        if ( in_array( 'optReason', array_keys( $lead_meta_data ) ) ) {
                            $showHTML = str_replace( '{REASON}', $lead_meta_data['optReason']['value'], $showHTML );
                        } else {
                            $showHTML = str_replace( '{REASON}', '', $showHTML );
                        }
                        if ( in_array( 'optSalutation', array_keys( $lead_meta_data ) ) ) {
                            $showHTML = str_replace( '{SALUTATION}', $lead_meta_data['optSalutation']['value'], $showHTML );
                        } else {
                            $showHTML = str_replace( '{SALUTATION}', '', $showHTML );
                        }
                        if ( in_array( 'optPhone', array_keys( $lead_meta_data ) ) ) {
                            $phone_clean = preg_replace( '/\\s+/', '', $lead_meta_data['optPhone']['value'] );
                            $showHTML = str_replace( '{PHONENUM}', $phone_clean, $showHTML );
                        } else {
                            $showHTML = str_replace( '{PHONENUM}', '', $showHTML );
                        }
                        if ( in_array( 'optName', array_keys( $lead_meta_data ) ) ) {
                            $showHTML = str_replace( '{FIRSTNAME}', $lead_meta_data['optName']['value'], $showHTML );
                        } else {
                            $showHTML = str_replace( '{FIRSTNAME}', $lead->name, $showHTML );
                        }
                        if ( in_array( 'optLName', array_keys( $lead_meta_data ) ) ) {
                            $showHTML = str_replace( '{LASTNAME}', $lead_meta_data['optLName']['value'], $showHTML );
                        } else {
                            $showHTML = str_replace( '{LASTNAME}', '', $showHTML );
                        }
                        // Handle optCustom_1 to optCustom_18
                        for ($i = 1; $i <= 18; $i++) {
                            $meta_key = 'optCustom_' . $i;
                            $placeholder = '{CUSTOM' . $i . '}';
                            if ( isset( $lead_meta_data[$meta_key]['value'] ) ) {
                                $showHTML = str_replace( $placeholder, $lead_meta_data[$meta_key]['value'], $showHTML );
                            } else {
                                $showHTML = str_replace( $placeholder, '', $showHTML );
                            }
                        }
                    } else {
                        $current_user = wp_get_current_user();
                        if ( user_can( $current_user, 'administrator' ) ) {
                            $first_name = $current_user->first_name;
                            $last_name = $current_user->last_name;
                            $email = $current_user->user_email;
                            // Output or use the values as needed
                            $showHTML = str_replace( '{FIRSTNAME}', $first_name, $showHTML );
                            $showHTML = str_replace( '{EMAIL}', $email, $showHTML );
                            $showHTML = str_replace( '{LASTNAME}', $last_name, $showHTML );
                            $showHTML = str_replace( '{PHONENUM}', '', $showHTML );
                            $showHTML = str_replace( '{SALUTATION}', '', $showHTML );
                            $showHTML = str_replace( '{REASON}', '', $showHTML );
                            $showHTML = str_replace( '{FULLNAME}', '', $showHTML );
                            for ($i = 1; $i <= 18; $i++) {
                                $meta_key = 'optCustom_' . $i;
                                $placeholder = '{CUSTOM' . $i . '}';
                                $showHTML = str_replace( $placeholder, '', $showHTML );
                            }
                        }
                        $showHTML = str_replace( '{FIRSTNAME}', '', $showHTML );
                        $showHTML = str_replace( '{LASTNAME}', '', $showHTML );
                    }
                    // Iframe should not work if amelia shortcodes option is disabled
                    $air_broadcast_message_width = ( isset( $cta['box_width'] ) ? $cta['box_width'] : '60%' );
                    $air_broadcast_message_bg_transparency = ( isset( $cta['bg_transparency'] ) ? $cta['bg_transparency'] : '0' );
                    $live_webinar_ctas_alignment_radios = ( isset( $cta['box_alignment'] ) ? $cta['box_alignment'] : 'center' );
                    $live_webinar_ctas_tab_name = ( isset( $cta['tab_text'] ) && !empty( $cta['tab_text'] ) ? $cta['tab_text'] : '' );
                    $live_webinar_ctas_position_radios = ( isset( $cta['cta_position'] ) ? $cta['cta_position'] : 'overlay' );
                    $live_air_btn_url = ( isset( $cta['button_url'] ) ? $cta['button_url'] : '' );
                    $live_air_btn_copy = ( isset( $cta['button_text'] ) ? $cta['button_text'] : '' );
                    $air_toggle = ( isset( $cta['air_toggle'] ) ? $cta['air_toggle'] : 'off' );
                    $iframe_ver = ( isset( $cta['iframe_ver'] ) ? (string) $cta['iframe_ver'] : '' );
                    // Hash excludes air_toggle to prevent content refresh when only toggle state changes
                    $current_hash = wp_hash( $showHTML . $air_amelia_toggle . $air_broadcast_message_width . $live_webinar_ctas_alignment_radios . $bg_color . $live_air_btn_url . $live_air_btn_copy . $live_webinar_ctas_tab_name . $live_webinar_ctas_position_radios . $air_broadcast_message_bg_transparency . $iframe_ver );
                    // Store each hash
                    $hashes[] = $current_hash;
                    if ( 'off' !== $air_amelia_toggle && class_exists( 'advancediFrame' ) && false === strpos( $showHTML, '[advanced_ifram' ) ) {
                        $advance_iframe_sc = $showHTML . webinarignition_get_cta_aiframe_sc( $ID, $current_hash, '' );
                        $showHTML = apply_filters( 'ai_handle_temp_pages', $advance_iframe_sc );
                    }
                    // Update the specific CTA entry while preserving all other fields
                    $results->live_ctas[$index] = array_merge( 
                        (array) $results->live_ctas[$index],
                        // Keep existing data
                        [
                            'showHTML'   => do_shortcode( $showHTML ),
                            'hash'       => $current_hash,
                            'iframe_ver' => $iframe_ver,
                        ]
                     );
                    unset($results->live_ctas[$index]['response']);
                    // Explicitly remove
                }
            }
            $final_hash = wp_hash( implode( '', $hashes ) );
            wp_send_json( array(
                'live_ctas'      => $results->live_ctas,
                'webinar_closed' => 'NO',
                'hash'           => $final_hash,
                'message'        => 'Active CTAs found',
            ) );
        } else {
            wp_send_json( array(
                'air_toggle'     => 'OFF',
                'webinar_closed' => 'NO',
                'message'        => 'No active CTAs found',
                'live_ctas'      => $results->live_ctas,
            ) );
        }
    }
    // If live_ctas does not exist or is not an array, send the OFF response
    wp_send_json( array(
        'air_toggle'     => 'OFF',
        'webinar_closed' => 'NO',
        'hash'           => '',
        'message'        => 'live ctas does not exist or is not an array',
    ) );
    die;
}

add_action( 'wp_ajax_nopriv_webinarignition_update_cta_state', 'webinarignition_update_cta_state' );
add_action( 'wp_ajax_webinarignition_update_cta_state', 'webinarignition_update_cta_state' );
function webinarignition_update_cta_state() {
    // Verify nonce first
    if ( !isset( $_GET['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid nonce',
        ) );
        wp_die();
    }
    // Sanitize input data
    $ID = sanitize_text_field( $_GET['id'] );
    $cta_id = sanitize_text_field( $_GET['cta_id'] );
    $new_state = sanitize_text_field( $_GET['new_state'] );
    // Get current webinar data
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    // Check if live_ctas exists and is an array
    if ( property_exists( $results, 'live_ctas' ) && is_array( $results->live_ctas ) ) {
        // Update the specific CTA's air_toggle value
        if ( isset( $results->live_ctas[$cta_id] ) ) {
            $results->live_ctas[$cta_id]['air_toggle'] = $new_state;
            // Update the option in database
            $updated = update_option( 'webinarignition_campaign_' . $ID, $results );
            if ( $updated ) {
                $notification_service_url = 'https://nodejs-small-wildflower-8030.fly.dev/cta-updated';
                error_log( 'Notification service URL: ' . $notification_service_url );
                $data = array(
                    'id'        => 'webinarID-' . $ID,
                    'site'      => site_url(),
                    'action'    => 'updated',
                    'title'     => $results->webinarURLName2,
                    'timestamp' => current_time( 'c' ),
                );
                $response = wp_remote_post( $notification_service_url, array(
                    'method'  => 'POST',
                    'headers' => array(
                        'Content-Type' => 'application/json',
                    ),
                    'body'    => json_encode( $data ),
                    'timeout' => 15,
                ) );
                error_log( 'Notification service response: ' . print_r( $response, true ) );
                if ( is_wp_error( $response ) ) {
                    wp_send_json_error( [
                        'message' => 'Notification service error: ' . $response->get_error_message(),
                    ] );
                    error_log( 'Notification service error: ' . $response->get_error_message() );
                } else {
                    wp_send_json_success( [
                        'message' => 'CTAs saved successfully',
                        'ctas'    => $results->live_ctas,
                    ] );
                }
                wp_send_json_success( array(
                    'message'   => 'CTA state updated successfully',
                    'live_ctas' => $results->live_ctas,
                    'cta_id'    => $cta_id,
                    'new_state' => $new_state,
                ) );
            } else {
                wp_send_json_error( array(
                    'message' => 'Failed to update database option',
                ) );
            }
        } else {
            wp_send_json_error( array(
                'message' => 'CTA ID not found in live_ctas',
            ) );
        }
    } else {
        wp_send_json_error( array(
            'message' => 'No live_ctas property found or it\'s not an array',
        ) );
    }
    die;
}

// New endpoint to fetch latest air_toggle values for console
add_action( 'wp_ajax_webinarignition_get_latest_air_toggle', 'webinarignition_get_latest_air_toggle' );
function webinarignition_get_latest_air_toggle() {
    // Verify nonce first
    if ( !isset( $_GET['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['nonce'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid nonce',
        ) );
        wp_die();
    }
    // Sanitize input data
    $ID = sanitize_text_field( $_GET['id'] );
    // Get current webinar data
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    // Check if live_ctas exists and is an array
    if ( property_exists( $results, 'live_ctas' ) && is_array( $results->live_ctas ) ) {
        // Extract only the air_toggle values for each CTA
        $air_toggle_values = array();
        foreach ( $results->live_ctas as $cta_id => $cta_data ) {
            if ( isset( $cta_data['air_toggle'] ) ) {
                $air_toggle_values[$cta_id] = $cta_data['air_toggle'];
            }
        }
        wp_send_json_success( array(
            'message'           => 'Latest air_toggle values fetched successfully',
            'air_toggle_values' => $air_toggle_values,
        ) );
    } else {
        wp_send_json_success( array(
            'message'           => 'No live_ctas found',
            'air_toggle_values' => array(),
        ) );
    }
    die;
}

add_action( 'wp_ajax_nopriv_webinarignition_delete_smtp_updated_status', 'webinarignition_delete_smtp_updated_status' );
add_action( 'wp_ajax_webinarignition_delete_smtp_updated_status', 'webinarignition_delete_smtp_updated_status' );
function webinarignition_delete_smtp_updated_status() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $option_deleted = delete_option( 'webinarignition_upgraded_smtp' );
    wp_send_json( array(
        'result' => $option_deleted,
    ) );
}

add_action( 'admin_notices', 'webinarignition_smtp_credentials_failed_notice' );
function webinarignition_smtp_credentials_failed_notice() {
    $webinarignition_smtp_credentials_failed = get_option( 'webinarignition_smtp_credentials_failed' );
    if ( 1 === $webinarignition_smtp_credentials_failed ) {
        ?>
		<div id="webinarignition-smtp-failed-notice" class="notice notice-warning is-dismissible">
			<p><?php 
        esc_html_e( 'Your WebinarIgnition SMTP settings failed in the last attempt to use them. Webinarignition will not try using them from now on.', 'webinar-ignition' );
        ?></p>
		</div>
		<?php 
    }
}

add_action( 'admin_notices', 'webinarignition_check_defer_plugins' );
function webinarignition_check_defer_plugins() {
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    }
    // If dismissed, don't show again
    if ( get_option( 'webinarignition_defer_notice_dismissed' ) ) {
        return;
    }
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    $defer_plugins = array(
        'autoptimize/autoptimize.php'         => 'Autoptimize',
        'litespeed-cache/litespeed-cache.php' => 'LiteSpeed Cache',
        'wp-rocket/wp-rocket.php'             => 'WP Rocket',
        'asset-cleanup/wp-asset-cleanup.php'  => 'Asset CleanUp',
    );
    $active = array();
    foreach ( $defer_plugins as $plugin_file => $plugin_name ) {
        if ( is_plugin_active( $plugin_file ) ) {
            $active[] = $plugin_name;
        }
    }
    if ( !empty( $active ) ) {
        ?>
        <div class="notice notice-warning is-dismissible webinarignition-defer-notice">
            <p>
                <?php 
        echo esc_html( sprintf( 
            /* translators: %s: list of detected optimization plugins */
            __( 'We detected that the following optimization plugin(s) are active: %s.', 'webinar-ignition' ),
            implode( ', ', $active )
         ) );
        ?>
            </p>
            <p>
                <?php 
        echo esc_html__( 'Please make sure to exclude our script "webinarignition-auto-register" from being deferred or optimized, otherwise it may break functionality.', 'webinar-ignition' );
        ?>
            </p>
        </div>
        <?php 
    }
}

add_action( 'admin_footer', 'webinarignition_admin_notice_inline_js' );
function webinarignition_admin_notice_inline_js() {
    // Only show for admins
    if ( !current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <script type="text/javascript">
        (function($){
            var ajaxurl = "<?php 
    echo esc_url( admin_url( 'admin-ajax.php' ) );
    ?>";

            $(document).on('click', '.webinarignition-defer-notice .notice-dismiss', function() {
                $.post(ajaxurl, { 
					action: 'webinarignition_dismiss_defer_notice',
                    security: '<?php 
    echo esc_js( wp_create_nonce( 'webinarignition_ajax_nonce' ) );
    ?>'

				});
            });
        })(jQuery);
    </script>
    <?php 
}

add_action( 'wp_ajax_webinarignition_dismiss_defer_notice', 'webinarignition_dismiss_defer_notice' );
/**
 * AJAX handler to save dismissal.
 */
function webinarignition_dismiss_defer_notice() {
    // Check user capabilities
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [
            'message' => 'Insufficient permissions',
        ], 403 );
        wp_die();
    }
    // Verify nonce
    if ( !isset( $_POST['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        wp_die();
    }
    update_option( 'webinarignition_defer_notice_dismissed', 1 );
    wp_send_json_success();
    wp_die();
}

// add_action( 'wp_ajax_nopriv_webinarignition_delete_smtp_failed_notice', 'webinarignition_delete_smtp_failed_notice' );
add_action( 'wp_ajax_webinarignition_delete_smtp_failed_notice', 'webinarignition_delete_smtp_failed_notice' );
function webinarignition_delete_smtp_failed_notice() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [
            'message' => 'Insufficient permissions',
        ], 403 );
        wp_die();
    }
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $option_deleted = delete_option( 'webinarignition_smtp_credentials_failed' );
    wp_send_json( array(
        'result' => $option_deleted,
    ) );
    wp_die();
}

// add_action( 'wp_ajax_nopriv_webinarignition_get_support_users', 'webinarignition_get_support_users' );
add_action( 'wp_ajax_webinarignition_get_support_users', 'webinarignition_get_support_users' );
function webinarignition_get_support_users() {
    if ( !current_user_can( 'manage_options' ) ) {
        wp_send_json_error( [
            'message' => 'Insufficient permissions',
        ], 403 );
        wp_die();
    }
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $users = get_users();
    wp_send_json_success( $users );
    wp_die();
}

add_action( 'wp_ajax_nopriv_webinarignition_check_if_q_and_a_enabled', 'webinarignition_check_if_q_and_a_enabled' );
add_action( 'wp_ajax_webinarignition_check_if_q_and_a_enabled', 'webinarignition_check_if_q_and_a_enabled' );
function webinarignition_check_if_q_and_a_enabled() {
    if ( !isset( $_POST['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid nonce',
        ) );
        wp_die();
        // terminate the script if nonce is invalid
    }
    $webinar_id = ( isset( $_POST['webinar_id'] ) ? absint( wp_unslash( $_POST['webinar_id'] ) ) : 0 );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    if ( isset( $webinar_data->enable_qa ) && 'yes' !== $webinar_data->enable_qa ) {
        return wp_send_json_success( array(
            'enable_qa' => 'no',
        ) );
    }
    wp_send_json_success( array(
        'enable_qa' => 'yes',
    ) );
}

add_action( 'wp_ajax_nopriv_webinarignition_set_q_a_status', 'webinarignition_set_q_a_status' );
add_action( 'wp_ajax_webinarignition_set_q_a_status', 'webinarignition_set_q_a_status' );
function webinarignition_set_q_a_status() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $webinar_id = absint( filter_input( INPUT_POST, 'webinarId', FILTER_SANITIZE_NUMBER_INT ) );
    $status = sanitize_text_field( filter_input( INPUT_POST, 'status' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    if ( 'hide' === $status ) {
        $webinar_data->enable_qa = 'no';
        update_option( 'webinarignition_campaign_' . $webinar_id, $webinar_data );
        wp_send_json_success( array(
            'webinar_qa' => '1849',
            'status'     => $webinar_data->enable_qa,
        ) );
    } else {
        $webinar_data->enable_qa = 'yes';
        update_option( 'webinarignition_campaign_' . $webinar_id, $webinar_data );
        wp_send_json_success( array(
            'webinar_qa' => '1853',
            'status'     => $webinar_data->enable_qa,
        ) );
    }
}

add_action( 'wp_ajax_nopriv_webinarignition_answer_attendee_question', 'webinarignition_answer_attendee_question' );
add_action( 'wp_ajax_webinarignition_answer_attendee_question', 'webinarignition_answer_attendee_question' );
function webinarignition_answer_attendee_question() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $webinarId = absint( filter_input( INPUT_POST, 'webinarId', FILTER_SANITIZE_NUMBER_INT ) );
    $attendeeEmail = ( isset( $_POST['attendeeEmail'] ) ? sanitize_email( filter_input( INPUT_POST, 'attendeeEmail', FILTER_SANITIZE_EMAIL ) ) : '' );
    $emailAnswer = sanitize_textarea_field( filter_input( INPUT_POST, 'answer' ) );
    $attendeeQuestion = sanitize_textarea_field( filter_input( INPUT_POST, 'attendeeQuestion' ) );
    $subject = sanitize_text_field( filter_input( INPUT_POST, 'subject' ) );
    $answerText = filter_input( INPUT_POST, 'answerText' );
    $questionId = sanitize_text_field( filter_input( INPUT_POST, 'questionId' ) );
    $supportId = sanitize_text_field( filter_input( INPUT_POST, 'supportId' ) );
    $supportName = sanitize_text_field( filter_input( INPUT_POST, 'supportName' ) );
    $emailQAEnabled = sanitize_text_field( filter_input( INPUT_POST, 'emailQAEnabled' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_questions';
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinarId );
    $result = $wpdb->update( $table_db_name, array(
        'status'      => 'done',
        'attr2'       => $supportId,
        'attr3'       => $supportName,
        'attr4'       => '',
        'attr5'       => '',
        'answer'      => $emailAnswer,
        'answer_text' => $answerText,
    ), array(
        'id' => $questionId,
    ) );
    $parent = WebinarignitionQA::webinarigntion_get_question( $questionId );
    if ( !empty( $parent ) ) {
        unset($parent['ID']);
        $parent['type'] = 'answer';
        $parent['status'] = 'answer';
        $parent['created'] = current_time( 'mysql' );
        $parent['parent_id'] = $questionId;
        $answer_id = WebinarignitionQA::webinarignition_create_question( $parent );
    }
    if ( empty( $emailQAEnabled ) || 'off' !== $emailQAEnabled ) {
        $email_data = new stdClass();
        $email_data->bodyContent = $emailAnswer;
        $email_data->email_subject = $subject;
        $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ? $webinar_data->qstn_answer_email_footer : '' );
        if ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ) {
            $email_data->footerContent = str_replace( '{YEAR}', gmdate( 'Y' ), $email_data->footerContent );
        }
        $email_data->emailheading = $subject;
        $email_data->emailpreview = $subject;
        $email = new WI_Emails();
        $emailBody = $email->webinarignition_build_email( $email_data );
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
        if ( !wp_mail(
            $attendeeEmail,
            $subject,
            $emailBody,
            $headers
        ) ) {
            WebinarIgnition_Logs::add( __( 'Support answer email could not be sent to', 'webinar-ignition' ) . " {$attendeeEmail}", WebinarIgnition_Logs::LIVE_EMAIL );
        }
    }
    //end if
    wp_send_json_success();
}

add_action( 'wp_ajax_nopriv_webinarignition_hold_or_release_console_question', 'webinarignition_hold_or_release_console_question' );
add_action( 'wp_ajax_webinarignition_hold_or_release_console_question', 'webinarignition_hold_or_release_console_question' );
function webinarignition_hold_or_release_console_question() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $questionId = sanitize_text_field( filter_input( INPUT_POST, 'questionId' ) );
    $supportName = sanitize_text_field( filter_input( INPUT_POST, 'supportName' ) );
    $webinarId = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );
    $supportId = sanitize_text_field( filter_input( INPUT_POST, 'supportId' ) );
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_questions';
    // Release other questions first
    // Sanitize the input value
    $supportId = intval( $supportId );
    // Assuming $supportId is an integer
    // Prepare and execute the query
    $questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `attr2` = %d", $supportId ), ARRAY_A );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    foreach ( $questions as $question ) {
        if ( 'hod' === $question['attr4'] ) {
            $wpdb->update( $table_db_name, array(
                'attr2' => '',
                'attr3' => '',
                'attr4' => '',
                'attr5' => '',
            ), array(
                'ID' => $question['ID'],
            ) );
        }
    }
    if ( wp_validate_boolean( filter_input( INPUT_POST, 'hold' ) ) ) {
        $wpdb->update( $table_db_name, array(
            'attr2' => $supportId,
            'attr3' => $supportName,
            'attr4' => 'hold',
            'attr5' => $supportName,
        ), array(
            'id' => $questionId,
        ) );
    } else {
        $wpdb->update( $table_db_name, array(
            'attr2' => '',
            'attr3' => '',
            'attr4' => '',
            'attr5' => '',
        ), array(
            'id' => $questionId,
        ) );
    }
    //end if
    wp_send_json_success();
}

add_action( 'wp_ajax_nopriv_webinarignition_release_unanswered_questions', 'webinarignition_release_unanswered_questions' );
add_action( 'wp_ajax_webinarignition_release_unanswered_questions', 'webinarignition_release_unanswered_questions' );
function webinarignition_release_unanswered_questions() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $webinarId = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );
    $supportId = sanitize_text_field( filter_input( INPUT_POST, 'supportId' ) );
    // Sanitize the input values
    $webinarId = intval( $webinarId );
    // Assuming $webinarId is an integer
    $supportId = intval( $supportId );
    // Assuming $supportId is an integer
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition_questions';
    // Prepare and execute the query
    $questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `attr2` = %d", $webinarId, $supportId ), ARRAY_A );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    foreach ( $questions as $question ) {
        if ( 'hold' === $question->attr4 ) {
            $wpdb->update( $table_db_name, array(
                'attr2' => '',
                'attr3' => '',
                'attr4' => '',
                'attr5' => '',
            ), array(
                'ID' => $question->ID,
            ) );
        }
    }
    wp_send_json_success();
}

add_action( 'wp_ajax_nopriv_webinarignition_get_answer_template', 'webinarignition_get_answer_template' );
add_action( 'wp_ajax_webinarignition_get_answer_template', 'webinarignition_get_answer_template' );
function webinarignition_get_answer_template() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $webinarId = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinarId );
    $emailBody = $webinar_data->qstn_answer_email_body;
    $return = array(
        'template' => $emailBody,
    );
    wp_send_json_success( $return );
}

add_action( 'wp_ajax_webinarignition_send_test_email', 'webinarignition_send_test_email_callback' );
function webinarignition_send_test_email_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $required_fields = array(
        'subject',
        'showLocalFooter',
        'emailheadingval',
        'emailpreviewval',
        'bodyContent',
        'footerContent',
        'webinarid',
        'templates_version',
        'use_new_template'
    );
    $post_input = array();
    foreach ( $required_fields as $field ) {
        $post_input[$field] = filter_input( INPUT_POST, $field );
    }
    $email_data = new stdClass();
    $email_data->email_subject = ( isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '' );
    //sanitize text data
    $email_data->showLocalFooter = ( isset( $_POST['showLocalFooter'] ) ? sanitize_text_field( $_POST['showLocalFooter'] ) : '' );
    //sanitize text data
    $email_data->emailheading = ( isset( $_POST['emailheadingval'] ) ? sanitize_text_field( $_POST['emailheadingval'] ) : '' );
    //sanitize text data
    $email_data->emailpreview = ( isset( $_POST['emailpreviewval'] ) ? sanitize_text_field( $_POST['emailpreviewval'] ) : '' );
    //sanitize text data
    $email_data->bodyContent = ( isset( $_POST['bodyContent'] ) ? wp_kses_post( $_POST['bodyContent'] ) : '' );
    //sanitize text data
    $email_data->footerContent = ( isset( $_POST['footerContent'] ) ? sanitize_text_field( $_POST['footerContent'] ) : '' );
    //sanitize text data
    $email_data->webinarid = ( isset( $_POST['webinarid'] ) ? absint( $_POST['webinarid'] ) : 0 );
    // validate numbers
    $email_data->templates_version = ( isset( $_POST['templates_version'] ) ? sanitize_text_field( $_POST['templates_version'] ) : '' );
    $email_data->use_new_template = ( isset( $_POST['use_new_template'] ) ? sanitize_text_field( $_POST['use_new_template'] ) : '' );
    if ( 'yes' === $email_data->use_new_template || !empty( $email_data->templates_version ) ) {
        $email = new WI_Emails();
        $emailBody = $email->webinarignition_build_email( $email_data );
    } else {
        $emailHead = WebinarignitionEmailManager::webinarignition_get_email_head();
        $emailBody = $emailHead;
        $emailBody .= $email_data->bodyContent;
    }
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
    $response = array();
    $email = ( isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '' );
    $subject = ( isset( $_POST['subject'] ) ? sanitize_text_field( $_POST['subject'] ) : '' );
    $email_test_ruuning = wp_mail(
        $email,
        $subject,
        $emailBody,
        $headers
    );
    if ( !$email_test_ruuning ) {
        $response['status'] = 0;
        $response['message'] = __( 'Sorry; email could not be sent.', 'webinar-ignition' );
    } else {
        $response['status'] = 1;
        $response['message'] = __( 'Email was successfully sent.', 'webinar-ignition' );
    }
    echo wp_json_encode( $response );
    die;
}

add_action( 'wp_ajax_nopriv_webinarignition_update_admin_webinar_status', 'webinarignition_update_admin_webinar_status' );
add_action( 'wp_ajax_webinarignition_update_admin_webinar_status', 'webinarignition_update_admin_webinar_status' );
function webinarignition_update_admin_webinar_status() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $webinarId = sanitize_text_field( filter_input( INPUT_POST, 'webinarId' ) );
    $webinar_switch = sanitize_text_field( filter_input( INPUT_POST, 'webinar_switch' ) );
    // error_log('Webinar switch: ' . print_r($webinar_switch, true));
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinarId );
    $webinar_data->webinar_switch = $webinar_switch;
    $updated = update_option( 'webinarignition_campaign_' . $webinarId, $webinar_data );
    if ( $updated ) {
        $notification_service_url = 'https://nodejs-small-wildflower-8030.fly.dev/cta-updated';
        error_log( 'Notification service URL: ' . $notification_service_url );
        $data = array(
            'id'        => 'webinarID-' . $webinarId,
            'site'      => site_url(),
            'action'    => 'webinar_status_updated',
            'title'     => $webinar_data->webinarURLName2,
            'timestamp' => current_time( 'c' ),
        );
        $response = wp_remote_post( $notification_service_url, array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body'    => json_encode( $data ),
            'timeout' => 15,
        ) );
        // error_log('Notification service response: ' . print_r($response, true));
        if ( is_wp_error( $response ) ) {
            wp_send_json_error( [
                'message' => 'Notification service error: ' . $response->get_error_message(),
            ] );
            // error_log('Notification service error: ' . $response->get_error_message());
        } else {
            wp_send_json_success( [
                'message'        => 'Webinar status updated successfully',
                'webinar_switch' => $webinar_switch,
            ] );
        }
    } else {
        wp_send_json_error( array(
            'message' => 'Failed to update database option',
        ) );
    }
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_get_localized_time', 'webinarignition_ajax_get_localized_time' );
add_action( 'wp_ajax_webinarignition_ajax_get_localized_time', 'webinarignition_ajax_get_localized_time' );
function webinarignition_ajax_get_localized_time() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $time = sanitize_text_field( filter_input( INPUT_POST, 'time' ) );
    echo esc_attr( webinarignition_get_localized_time( $time ) );
    die;
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_get_date_format', 'webinarignition_ajax_get_date_format' );
add_action( 'wp_ajax_webinarignition_ajax_get_date_format', 'webinarignition_ajax_get_date_format' );
function webinarignition_ajax_get_date_format() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $locale = sanitize_text_field( filter_input( INPUT_POST, 'locale' ) );
    $format = sanitize_text_field( filter_input( INPUT_POST, 'format' ) );
    switch_to_locale( $locale );
    echo esc_attr( date_i18n( $format ) );
    restore_previous_locale();
    wp_die();
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_get_date_in_chosen_language', 'webinarignition_ajax_get_date_in_chosen_language' );
add_action( 'wp_ajax_webinarignition_ajax_get_date_in_chosen_language', 'webinarignition_ajax_get_date_in_chosen_language' );
/**
 * Retrieves the date in localized format, based on the format and language provided.
 */
function webinarignition_ajax_get_date_in_chosen_language() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $selected_lng = sanitize_text_field( filter_input( INPUT_POST, 'locale' ) );
    $selected_lng_web = 'webinar-ignition-' . $selected_lng;
    require_once ABSPATH . 'wp-admin/includes/translation-install.php';
    $available_languages = webinarignition_get_available_languages();
    $wp_available_languages = get_available_languages();
    if ( get_locale() !== $selected_lng && in_array( $selected_lng_web, $available_languages, true ) && !in_array( $selected_lng, $wp_available_languages, true ) ) {
        $downloaded = wp_download_language_pack( $selected_lng );
        if ( $downloaded ) {
            wp_send_json_success( 'downloaded' );
        }
    } else {
        $response = array();
        $switched_locale = switch_to_locale( $selected_lng );
        $date_format = 'F j, Y';
        if ( $selected_lng === 'de_DE' ) {
            $date_format = 'j. F Y';
            // Dutch format
        }
        $response['date_in_chosen_locale'] = date_i18n( $date_format );
        $response['date_in_chosen_day_D_locale'] = date_i18n( 'D' );
        $response['date_in_chosen_day_l_locale'] = date_i18n( 'l' );
        $response['monthsFull'] = WiDateHelpers::webinarignition_get_locale_months();
        $response['weekdaysFull'] = WiDateHelpers::webinarignition_get_locale_days();
        $response['weekdaysShort'] = WiDateHelpers::webinarignition_get_locale_weekday_abbrev();
        $response['js_date_format'] = webinarignition_convert_php_to_js_date_format( $date_format );
        $response['php_date_format'] = $date_format;
        // Define time formats for each locale
        $time_formats = [
            'en_US' => 'g:i a',
            'af'    => 'g:i a',
            'de_DE' => 'H:i',
            'es_ES' => 'H:i',
            'es_MX' => 'g:i a',
            'fr_FR' => 'H:i',
            'hi_IN' => 'g:i a',
            'hr'    => 'H:i',
            'hu_HU' => 'H:i',
            'it_IT' => 'H:i',
            'ja'    => 'g:i a',
            'nb_NO' => 'H:i',
            'nl_NL' => 'H:i',
            'pl_PL' => 'H:i',
            'pt_BR' => 'H:i',
            'ru_RU' => 'H:i',
            'tr_TR' => 'H:i',
            'uk'    => 'H:i',
            'ur'    => 'g:i a',
            'zh_CN' => 'H:i',
        ];
        // Fallback to default time format if locale is not mapped
        $time_format = ( isset( $time_formats[$selected_lng] ) ? $time_formats[$selected_lng] : 'H:i' );
        $response['php_time_format'] = $time_format;
        $response['time_in_chosen_locale'] = date_i18n( $time_format );
        $response['js_time_format'] = webinarignition_convert_wp_to_js_time_format( $time_format );
        $response['preview_text'] = __( 'Preview:', 'webinar-ignition' );
        $response['custom_text'] = __( 'Custom:', 'webinar-ignition' );
        restore_previous_locale();
        wp_send_json_success( $response );
    }
    //end if
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_convert_php_to_js_date_format', 'webinarignition_ajax_convert_php_to_js_date_format' );
add_action( 'wp_ajax_webinarignition_ajax_convert_php_to_js_date_format', 'webinarignition_ajax_convert_php_to_js_date_format' );
function webinarignition_ajax_convert_php_to_js_date_format() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $date_format = sanitize_text_field( filter_input( INPUT_POST, 'date_format' ) );
    $response = array();
    $response['date_format'] = webinarignition_convert_php_to_js_date_format( $date_format );
    wp_send_json_success( $response );
}

add_action( 'wp_ajax_nopriv_webinarignition_ajax_convert_wp_to_js_time_format', 'webinarignition_ajax_convert_wp_to_js_time_format' );
add_action( 'wp_ajax_webinarignition_ajax_convert_wp_to_js_time_format', 'webinarignition_ajax_convert_wp_to_js_time_format' );
function webinarignition_ajax_convert_wp_to_js_time_format() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $time_format = sanitize_text_field( filter_input( INPUT_POST, 'time_format' ) );
    $response = array();
    $response['time_format'] = webinarignition_convert_wp_to_js_time_format( $time_format );
    wp_send_json_success( $response );
}

// TODO: Need to check how not to duplicate this function, after reviewing the whole plugin structure
if ( !function_exists( 'webinarignition_get_available_languages' ) ) {
    function webinarignition_get_available_languages() {
        $webinarignition_languages = get_available_languages( WEBINARIGNITION_PATH . '/languages/' );
        $loco_translate_languages = get_available_languages( WP_CONTENT_DIR . '/languages/loco/plugins/' );
        $system_languages = get_available_languages( WP_CONTENT_DIR . '/languages/plugins/' );
        $all_languages = array_merge( $loco_translate_languages, $system_languages, $webinarignition_languages );
        $available_languages = array();
        $all_languages_count = count( $all_languages );
        $available_languages_count = count( $available_languages );
        for ($i = 0; $i < $all_languages_count; $i++) {
            if ( strpos( $all_languages[$i], 'webinar-ignition' ) !== false || strpos( $all_languages[$i], 'webinar-ignition' ) !== false ) {
                $available_languages[] = $all_languages[$i];
            }
        }
        for ($i = 0; $i < $available_languages_count; $i++) {
            if ( strpos( $available_languages[$i], 'webinar-ignition-' ) !== false ) {
                $available_languages[$i] = substr( $available_languages[$i], 16 );
            }
            if ( strpos( $available_languages[$i], 'webinar-ignition-' ) !== false ) {
                $available_languages[$i] = substr( $available_languages[$i], 17 );
            }
        }
        return array_unique( $available_languages );
    }

}
//end if
function webinarignition_get_lead_table(  $webinar_type  ) {
    global $wpdb;
    $table = "{$wpdb->prefix}webinarignition_leads";
    $webinar_type = trim( strtolower( $webinar_type ) );
    if ( 'auto' === $webinar_type ) {
        $table = "{$table}_evergreen";
    }
    return $table;
}

function webinarignition_update_webinar_lead_status(  $webinar_type, $lead_id  ) {
    global $wpdb;
    $table_name = webinarignition_get_lead_table( $webinar_type );
    $id_column = 'ID';
    if ( !is_numeric( $lead_id ) ) {
        $id_column = 'hash_ID';
    }
    $id_column = esc_sql( $id_column );
    // Escape column name if necessary
    // Prepare and execute the query
    $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_name}` L WHERE L.`{$id_column}` = %s", $lead_id ), OBJECT );
    if ( !empty( $data ) ) {
        $attended = trim( strtolower( $data->event ) );
        $watched_replay = trim( strtolower( $data->replay ) );
        $status_column_value = 'Yes';
        if ( 'yes' !== $attended ) {
            $status_column = 'event';
        } elseif ( 'yes' !== $watched_replay ) {
            $status_column = 'replay';
        } else {
            $status_column = false;
        }
        if ( !wp_validate_boolean( $status_column ) ) {
            $lead_status = 'attended';
            // Give more logical names to lead status
            if ( 'replay' === $status_column ) {
                $lead_status = 'watched_replay';
            }
            if ( $status_column ) {
                $updated = $wpdb->update( $table_name, array(
                    $status_column => $status_column_value,
                ), array(
                    $id_column => $lead_id,
                ) );
            }
            do_action( 'webinarignition_lead_updated', $data->ID );
            do_action(
                'webinarignition_lead_status_changed',
                $lead_status,
                $lead_id,
                $data->app_id
            );
            return !empty( $updated );
        }
    }
    //end if
    return false;
}

/**
 * Check if current logged in user has existing un-attempted lead for the given webinar ID
 *
 * Returns 0 if no lead found, numeric lead ID otherwise
 *
 * @param int    $webinar_id The webinar id.
 * @param string $user_email The webinar associated email.
 * @param string $webinar_type The webinar type.
 *
 * @return int
 */
function webinarignition_existing_lead_id(  $webinar_id, $user_email, $webinar_type = 'auto'  ) {
    $webinar_id = absint( $webinar_id );
    if ( empty( $webinar_id ) || empty( $webinar_type ) || empty( $user_email ) ) {
        return 0;
    }
    global $wpdb;
    $table_lead = ( 'auto' === $webinar_type ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads' );
    // Escape the table name
    $table_lead = esc_sql( $table_lead );
    // Prepare and execute the query
    $lead_id = $wpdb->get_var( $wpdb->prepare( "SELECT L.ID FROM `{$table_lead}` L WHERE L.app_id = %d AND L.email = %s", $webinar_id, $user_email ) );
    $lead_id = absint( $lead_id );
    return $lead_id;
}

/**
 * Delete lead by ID and webinar type
 *
 * @param int    $lead_id The lead id.
 * @param string $webinar_type The webinar type.
 */
function webinarignition_delete_lead_by_id(  $lead_id, $webinar_type = 'auto'  ) {
    global $wpdb;
    if ( 'auto' === $webinar_type ) {
        $table_lead = $wpdb->prefix . 'webinarignition_leads_evergreen';
        $table_lead_meta = $wpdb->prefix . 'webinarignition_lead_evergreenmeta';
    } else {
        $table_lead = $wpdb->prefix . 'webinarignition_leads';
        $table_lead_meta = $wpdb->prefix . 'webinarignition_leadmeta';
    }
    $lead_id = absint( $lead_id );
    $lead_deleted = $wpdb->delete( $table_lead, array(
        'ID' => $lead_id,
    ), array('%d') );
    if ( $lead_deleted ) {
        $wpdb->delete( $table_lead_meta, array(
            'lead_id' => $lead_id,
        ), array('%d') );
    }
}

/**
 * @param obj    $webinar_data The webinar data.
 * @param obj    $lead The lead data..
 * @param string $status The lead status.
 */
function webinarignition_mark_lead_status(  $webinar_data, $lead, $status  ) {
    if ( !empty( $webinar_data ) && !empty( $lead ) ) {
        $is_auto = webinarignition_is_auto( $webinar_data );
        global $wpdb;
        $leads_table = "{$wpdb->prefix}webinarignition_leads";
        if ( $is_auto ) {
            $leads_table .= '_evergreen';
        }
        $wpdb->update( $leads_table, array(
            'lead_status' => $status,
        ), array(
            'ID'     => $lead->ID,
            'app_id' => $webinar_data->id,
        ) );
        if ( 'attending' === $status ) {
            $status = 'attended';
        }
        do_action(
            'webinarignition_lead_status_changed',
            $status,
            $lead->ID,
            $webinar_data->id
        );
        return true;
    }
    //end if
    return false;
}

function webinarignition_mark_lead_watched() {
    check_admin_referer( 'webinarignition_mark_lead_status', 'nonce' );
    if ( !wp_doing_ajax() ) {
        return;
    }
    $response_type = 'error';
    if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {
        if ( isset( $_POST['is_preview_page'] ) && wp_validate_boolean( $_POST['is_preview_page'] ) ) {
            $response_type = 'success';
            // Return success always for preview page
        } else {
            $webinar_id = absint( $_POST['webinar_id'] );
            $lead_id = absint( $_POST['lead_id'] );
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
            $lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
            if ( 'watched' !== $lead->lead_status ) {
                if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'watched' ) ) {
                    $response_type = 'success';
                }
            }
        }
    }
    call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_watched', 'webinarignition_mark_lead_watched' );
add_action( 'wp_ajax_webinarignition_lead_mark_watched', 'webinarignition_mark_lead_watched' );
function webinarignition_mark_lead_attended() {
    check_admin_referer( 'webinarignition_mark_lead_status', 'nonce' );
    if ( !wp_doing_ajax() ) {
        return;
    }
    $response_type = 'error';
    if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {
        if ( isset( $_POST['is_preview_page'] ) && wp_validate_boolean( $_POST['is_preview_page'] ) ) {
            $response_type = 'success';
            // Return success always for preview page
        } else {
            $webinar_id = absint( $_POST['webinar_id'] );
            $lead_id = absint( $_POST['lead_id'] );
            $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
            $lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
            if ( 'attended' !== $lead->lead_status ) {
                if ( empty( $lead->lead_status ) ) {
                    if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'attended' ) ) {
                        $response_type = 'success';
                    }
                }
            }
        }
    }
    call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_attended', 'webinarignition_mark_lead_attended' );
add_action( 'wp_ajax_webinarignition_lead_mark_attended', 'webinarignition_mark_lead_attended' );
function webinarignition_mark_lead_attending() {
    if ( !isset( $_POST['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid nonce',
        ) );
        wp_die();
        // Terminate the script if nonce is invalid
    }
    if ( !wp_doing_ajax() ) {
        return;
    }
    $response_type = 'error';
    if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {
        $webinar_id = absint( $_POST['webinar_id'] );
        $lead_id = $_POST['lead_id'];
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
        $lead = webinarignition_get_lead_info( $lead_id, $webinar_data );
        if ( !isset( $lead->lead_status ) || 'watched' !== $lead->lead_status ) {
            if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'attending' ) ) {
                $response_type = 'success';
            }
        }
    }
    call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_attending', 'webinarignition_mark_lead_attending' );
add_action( 'wp_ajax_webinarignition_lead_mark_attending', 'webinarignition_mark_lead_attending' );
function webinarignition_mark_lead_complete() {
    if ( !isset( $_POST['nonce'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid nonce',
        ) );
        wp_die();
        // Terminate the script if nonce is invalid
    }
    if ( !wp_doing_ajax() ) {
        return;
    }
    $response_type = 'error';
    if ( isset( $_POST['webinar_id'] ) && isset( $_POST['lead_id'] ) ) {
        $webinar_id = absint( $_POST['webinar_id'] );
        $lead_id = $_POST['lead_id'];
        $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
        $lead = webinarignition_get_lead_info( $lead_id, $webinar_data, false );
        if ( isset( $lead ) && 'watched' !== $lead->lead_status ) {
            if ( webinarignition_mark_lead_status( $webinar_data, $lead, 'complete' ) ) {
                $response_type = 'success';
            }
        }
    }
    call_user_func( "wp_send_json_{$response_type}" );
}

add_action( 'wp_ajax_nopriv_webinarignition_lead_mark_complete', 'webinarignition_mark_lead_complete' );
add_action( 'wp_ajax_webinarignition_lead_mark_complete', 'webinarignition_mark_lead_complete' );
// Bump CTA iframe version so all clients reload iframe via polling
add_action( 'wp_ajax_nopriv_webinarignition_bump_cta_version', 'webinarignition_bump_cta_version' );
add_action( 'wp_ajax_webinarignition_bump_cta_version', 'webinarignition_bump_cta_version' );
function webinarignition_bump_cta_version() {
    if ( !isset( $_POST['security'] ) || !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( array(
            'message' => 'Invalid security token',
        ), 403 );
        wp_die();
    }
    $ID = ( isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : '' );
    $cta_id = ( isset( $_POST['cta_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cta_id'] ) ) : '' );
    if ( empty( $ID ) || '' === $cta_id ) {
        wp_send_json_error( array(
            'message' => 'Missing parameters',
        ), 400 );
        wp_die();
    }
    $results = WebinarignitionManager::webinarignition_get_webinar_data( $ID );
    if ( !property_exists( $results, 'live_ctas' ) || !is_array( $results->live_ctas ) || !isset( $results->live_ctas[$cta_id] ) ) {
        wp_send_json_error( array(
            'message' => 'CTA not found',
        ), 404 );
        wp_die();
    }
    $current_ver = ( isset( $results->live_ctas[$cta_id]['iframe_ver'] ) ? intval( $results->live_ctas[$cta_id]['iframe_ver'] ) : 0 );
    $results->live_ctas[$cta_id]['iframe_ver'] = (string) ($current_ver + 1);
    $updated = update_option( 'webinarignition_campaign_' . $ID, $results );
    if ( $updated || true ) {
        // Send notification to Node.js server
        $notification_service_url = 'https://nodejs-small-wildflower-8030.fly.dev/cta-updated';
        error_log( 'Notification service URL: ' . $notification_service_url );
        $data = array(
            'id'         => 'webinarID-' . $ID,
            'site'       => site_url(),
            'action'     => 'iframe_version_bumped',
            'title'      => ( isset( $results->webinarURLName2 ) ? $results->webinarURLName2 : 'Webinar CTA' ),
            'cta_id'     => $cta_id,
            'iframe_ver' => $results->live_ctas[$cta_id]['iframe_ver'],
            'timestamp'  => current_time( 'c' ),
        );
        $response = wp_remote_post( $notification_service_url, array(
            'method'  => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
            'body'    => json_encode( $data ),
            'timeout' => 15,
        ) );
        error_log( 'Notification service response: ' . print_r( $response, true ) );
        if ( is_wp_error( $response ) ) {
            error_log( 'Notification service error: ' . $response->get_error_message() );
        }
        wp_send_json_success( array(
            'iframe_ver'   => $results->live_ctas[$cta_id]['iframe_ver'],
            'cta_position' => $results->live_ctas[$cta_id]['cta_position'],
        ) );
    } else {
        wp_send_json_error( array(
            'message' => 'Failed to update CTA version',
        ) );
    }
}
