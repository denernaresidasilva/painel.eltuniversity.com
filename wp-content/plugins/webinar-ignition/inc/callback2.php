<?php

use Twilio\Rest\Client;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
// Edit Campaign // Save
add_action( 'wp_ajax_webinarignition_test_sms', 'webinarignition_test_sms' );
add_action( 'wp_ajax_webinarignition_process_stripe_charge', 'webinarignition_process_stripe_charge' );
add_action( 'wp_ajax_nopriv_webinarignition_process_stripe_charge', 'webinarignition_process_stripe_charge' );
function webinarignition_process_stripe_charge() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $campaign_id = sanitize_text_field( filter_input( INPUT_POST, 'campaign_id' ) );
    $token = sanitize_text_field( filter_input( INPUT_POST, 'token' ) );
    $stripe_receipt_email = ( isset( $_POST['stripe_receipt_email'] ) ? sanitize_email( filter_input( INPUT_POST, 'stripe_receipt_email' ) ) : '' );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $campaign_id );
    $stripe_secret_key = $webinar_data->stripe_secret_key;
    $stripe_charge = $webinar_data->stripe_charge;
    $stripe_charge_description = $webinar_data->stripe_charge_description;
    $stripe_currency = ( !empty( $webinar_data->stripe_currency ) ? $webinar_data->stripe_currency : 'usd' );
    if ( empty( $webinar_data ) ) {
        die;
    }
    // Set your secret key: remember to change this to your live secret key in production
    // See your keys here https://dashboard.stripe.com/account/apikeys
    \Stripe\Stripe::setApiKey( $stripe_secret_key );
    $token = $token;
    $customers = \Stripe\Customer::all( array(
        'limit' => 1,
        'email' => $stripe_receipt_email,
    ) );
    if ( empty( $customers['data'] ) ) {
        // Create a Customer
        $customer = \Stripe\Customer::create( array(
            'email'  => $stripe_receipt_email,
            'source' => $token,
        ) );
        $customer_data_proess = $customer;
        $customerID = $customer->id;
    } else {
        $customerID = $customers['data'][0]['id'];
        $customer_data_proess = $customer;
    }
    // Create the charge on Stripe's servers - this will charge the user's card
    try {
        $customer_data_id = $customer_data_proess->id;
        $charge = \Stripe\Charge::create( array(
            'amount'      => $stripe_charge,
            'currency'    => $stripe_currency,
            'description' => $stripe_charge_description,
            'customer'    => $customerID,
        ) );
    } catch ( \Stripe\Error\Card $e ) {
        // The card has been declined
        die( wp_json_encode( array(
            'status' => 0,
            'error'  => $e->getMessage(),
            'token'  => $token,
        ) ) );
    } catch ( Exception $e ) {
        wp_send_json( array(
            'status' => 0,
            'error'  => $e->getMessage(),
        ) );
    }
    //end try
    die( wp_json_encode( array(
        'status' => 1,
        'token'  => $token,
        'charge' => $charge,
    ) ) );
}

function webinarignition_test_sms() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $campaign_id = sanitize_text_field( filter_input( INPUT_POST, 'campaign_id' ) );
    $phone_number = sanitize_text_field( filter_input( INPUT_POST, 'phone_number' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $campaign_id );
    if ( empty( $webinar_data ) ) {
        die;
    }
    $AccountSid = $webinar_data->twilio_id;
    $AuthToken = $webinar_data->twilio_token;
    try {
        $client = new Client($AccountSid, $AuthToken);
        $client->messages->create( $phone_number, array(
            'from' => $webinar_data->twilio_number,
            'body' => __( 'You received this message to test WebinarIgnition SMS integration.', 'webinar-ignition' ),
        ) );
        echo wp_json_encode( array(
            'status' => 1,
        ) );
    } catch ( Exception $e ) {
        echo wp_json_encode( array(
            'status' => -1,
            'errors' => $e->getMessage(),
        ) );
    }
    die;
}

if ( !function_exists( 'webinarignition_build_time' ) ) {
    function webinarignition_build_time(  $date, $time  ) {
        // ReArrange Date To Fit Format
        if ( strpos( $date, '-' ) ) {
            $exDate = explode( '-', $date );
        } else {
            $exDate = explode( '/', $date );
        }
        $exYear = ( isset( $exDate[2] ) ? $exDate[2] : 0 );
        $exMonth = ( isset( $exDate[0] ) ? $exDate[0] : 0 );
        $exDay = ( isset( $exDate[1] ) ? $exDate[1] : 0 );
        $newDate = $exYear . '-' . $exMonth . '-' . $exDay . ' ' . $time;
        return $newDate;
    }

}
// Create Campaign
add_action( 'wp_ajax_webinarignition_create', 'webinarignition_create_callback' );
function webinarignition_create_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    if ( !current_user_can( 'edit_posts' ) ) {
        wp_die( esc_html__( 'You do not have permissions to access this page.', 'webinar-ignition' ) );
    }
    // WP DB Include
    global $wpdb;
    global $wp_locale;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    $clone = ( isset( $_POST['cloneapp'] ) ? sanitize_text_field( wp_unslash( $_POST['cloneapp'] ) ) : null );
    $importcode = ( isset( $_POST['importcode'] ) ? sanitize_text_field( wp_unslash( $_POST['importcode'] ) ) : null );
    // Save DB Info - Name & Created Date
    $wpdb->insert( $table_db_name, array(
        'appname'      => sanitize_text_field( wp_unslash( $_POST['appname'] ) ),
        'camtype'      => $clone,
        'total_lp'     => '0%%0',
        'total_ty'     => '0%%0',
        'total_live'   => '0%%0',
        'total_replay' => '0%%0',
        'created'      => gmdate( 'F j, Y' ),
    ) );
    // Return The ID Of Campaign Created
    $campaignID = $wpdb->insert_id;
    do_action( 'webinarignition_campaign_created', $campaignID );
    // CREATE A CORRESPONDING POST ::
    $my_post = array(
        'post_title'   => sanitize_text_field( wp_unslash( $_POST['appname'] ) ),
        'post_type'    => 'page',
        'post_content' => sanitize_text_field( wp_unslash( $_POST['appname'] ) ),
        'post_status'  => 'publish',
    );
    // Insert the post into the database
    $getPostID = wp_insert_post( $my_post );
    // Add postID to db:
    $campaignID = intval( $campaignID );
    // Assuming $campaignID is an integer
    // Prepare the data and where clauses
    $data = array(
        'postID' => $getPostID,
    );
    $where = array(
        'id' => $campaignID,
    );
    // Execute the update query
    $wpdb->update( $table_db_name, $data, $where );
    // Set Meta Info so it links this page with the bonus page::
    update_post_meta( $getPostID, 'webinarignitionx_meta_box_select', esc_attr( $campaignID ) );
    $site_url = get_site_url();
    $statusCheck = new stdClass();
    $statusCheck->switch = 'free';
    $statusCheck->slug = 'free';
    $statusCheck->licensor = '';
    $statusCheck->is_free = 1;
    $statusCheck->is_dev = '';
    $statusCheck->is_registered = '';
    $statusCheck->title = 'Free';
    $statusCheck->account_url = $site_url . '/wp-admin/admin.php?page=webinarignition-dashboard-account';
    $statusCheck->name = '';
    $current_locale_temp = determine_locale();
    $show_all_live_languages = true;
    $show_all_eg_languages = true;
    $applang = ( isset( $_POST['applang'] ) ? sanitize_text_field( wp_unslash( $_POST['applang'] ) ) : null );
    if ( empty( $applang ) || (!$show_all_live_languages && 'new' === $clone || !$show_all_eg_languages && 'auto' === $clone) ) {
        $applang = 'en_US';
    }
    $settings_language = ( isset( $_POST['settings_language'] ) ? sanitize_text_field( wp_unslash( $_POST['settings_language'] ) ) : null );
    if ( empty( $settings_language ) ) {
        $settings_language = 'no';
    }
    if ( 'no' === $settings_language && (!$show_all_live_languages && 'new' === $clone || !$show_all_eg_languages && 'auto' === $clone) ) {
        $settings_language = 'yes';
    }
    $_POST['settings_language'] = $settings_language;
    switch_to_locale( $applang );
    unload_textdomain( 'webinar-ignition' );
    load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $applang . '.mo' );
    echo esc_attr( $campaignID );
    // MODEL :: CORE DATA
    add_option( 'webinarignition_campaign_' . $campaignID, '' );
    $maintitle = sanitize_text_field( wp_unslash( $_POST['appname'] ) );
    if ( 'auto' === $clone ) {
        $_POST['webinar_date'] = 'AUTO';
    }
    $live_date = sanitize_text_field( wp_unslash( $_POST['webinar_date'] ) );
    if ( 'new' === $clone ) {
        $webinarDateObject = DateTime::createFromFormat( 'm-d-Y', $live_date );
        if ( is_object( $webinarDateObject ) ) {
            $webinarTimestamp = $webinarDateObject->getTimestamp();
            $localized_date = date_i18n( $_POST['date_format_custom'], $webinarTimestamp );
            $localized_month = $wp_locale->get_month( $webinarDateObject->format( 'm' ) );
            $localized_week_day = $wp_locale->get_weekday( $webinarDateObject->format( 'w' ) );
        }
        // NB: Date is stored in DB in format m-d-Y but displayed according to user's chosen option, get_option("date_format").
        if ( !empty( $live_date ) ) {
            $live_date_array = explode( '-', $live_date );
            // ['m', 'd', 'Y']
        }
        $setTime = sanitize_text_field( wp_unslash( $_POST['webinar_start_time'] ) );
        if ( $setTime ) {
            $getTime = gmdate( 'h:i:s A', strtotime( $setTime ) );
            $getTime = explode( ' ', $getTime );
            $getHour = explode( ':', $getTime[0] );
            $getHour2 = $getHour[0];
            // Check for 0 in front of time..
            if ( 0 === (int) $getHour2[0] ) {
                $getHour2 = str_replace( '0', '', $getHour2 );
            }
        }
    }
    //end if
    $timezone = '-5';
    if ( !empty( $_POST['webinar_timezone'] ) ) {
        $timezone = sanitize_text_field( wp_unslash( $_POST['webinar_timezone'] ) );
    }
    $host = __( 'Your Name', 'webinar-ignition' );
    if ( !empty( $_POST['webinar_host'] ) ) {
        $host = sanitize_text_field( wp_unslash( $_POST['webinar_host'] ) );
    }
    $desc = __( 'How We Crush It With Webinars', 'webinar-ignition' );
    if ( !empty( $_POST['webinar_desc'] ) ) {
        $desc = sanitize_text_field( wp_unslash( $_POST['webinar_desc'] ) );
    }
    if ( isset( $_POST['cloneapp'] ) && wp_validate_boolean( wp_unslash( $_POST['cloneapp'] ) ) ) {
        $desc = ( isset( $_POST['appname'] ) ? sanitize_text_field( wp_unslash( $_POST['appname'] ) ) : null );
    }
    $emailSetup = '';
    $emailSetup .= '<p>';
    $emailSetup .= ( !empty( $statusCheck->account_url ) ? __( 'Hi', 'webinar-ignition' ) : 'Hi' );
    $emailSetup .= ' {FULLNAME}.</p><p>%%INTRO%%</p><p>';
    $emailSetup .= ( !empty( $statusCheck->account_url ) ? __( 'Date: Join us live on', 'webinar-ignition' ) : 'Date: Join us live on' );
    $emailSetup .= ' {DATE}</p><p>';
    $emailSetup .= ( !empty( $statusCheck->account_url ) ? __( 'Webinar Topic', 'webinar-ignition' ) : 'Webinar Topic' );
    $emailSetup .= ': {TITLE}</p><p>';
    $emailSetup .= ( !empty( $statusCheck->account_url ) ? __( 'Hosts', 'webinar-ignition' ) : 'Hosts' );
    $emailSetup .= ': {HOST}</p><p><strong>';
    $emailSetup .= ( !empty( $statusCheck->account_url ) ? __( 'How To Join The Webinar', 'webinar-ignition' ) : 'How To Join The Webinar' );
    $emailSetup .= '</strong></p><p>';
    $emailSetup .= ( !empty( $statusCheck->account_url ) ? __( 'Click the following link to join.', 'webinar-ignition' ) : 'Click the following link to join.' );
    $emailSetup .= '</p><p></p><p style="text-align:center;">{LINK}</p><p></p>';
    $emailSetup_replay = '';
    $emailSetup_replay .= '<p>';
    $emailSetup_replay .= ( !empty( $statusCheck->account_url ) ? __( 'Hi', 'webinar-ignition' ) : 'Hi' );
    $emailSetup_replay .= ' {FULLNAME}.</p><p>%%INTRO%%</p><p>';
    $emailSetup_replay .= ( !empty( $statusCheck->account_url ) ? __( 'Webinar Topic', 'webinar-ignition' ) : 'Webinar Topic' );
    $emailSetup_replay .= ': {TITLE}</p><p>';
    $emailSetup_replay .= ( !empty( $statusCheck->account_url ) ? __( 'Hosts', 'webinar-ignition' ) : 'Hosts' );
    $emailSetup_replay .= ': {HOST}</p><p><strong>';
    $emailSetup_replay .= ( !empty( $statusCheck->account_url ) ? __( 'How To Join The Webinar', 'webinar-ignition' ) : 'How To Join The Webinar' );
    $emailSetup_replay .= '</strong></p><p>';
    $emailSetup_replay .= ( !empty( $statusCheck->account_url ) ? __( 'Click the following link to join.', 'webinar-ignition' ) : 'Click the following link to join.' );
    $emailSetup_replay .= '</p><p></p><p style="text-align:center;">{LINK}</p><p></p>';
    $systemRequirements = '<p style="font-size: 14px;">';
    $systemRequirements .= ( !empty( $statusCheck->account_url ) ? __( 'You will be connected to video via your browser using your computer, tablet, or mobile phone\'s microphone and speakers. A headset is recommended.', 'webinar-ignition' ) : 'You will be connected to video via your browser using your computer, tablet, or mobile phone\'s microphone and speakers. A headset is recommended.' );
    $systemRequirements .= '</p><p style="font-size: 14px;"><strong>';
    $systemRequirements .= ( !empty( $statusCheck->account_url ) ? __( 'Webinar Requirements', 'webinar-ignition' ) : 'Webinar Requirements' );
    $systemRequirements .= '</strong></p><p style="font-size: 14px;">';
    $systemRequirements .= ( !empty( $statusCheck->account_url ) ? __( 'A recent browser version of Mozilla Firefox, Google Chrome, Apple Safari, Microsoft Edge or Opera.', 'webinar-ignition' ) : 'A recent browser version of Mozilla Firefox, Google Chrome, Apple Safari, Microsoft Edge or Opera.' );
    $systemRequirements .= '</p><p style="font-size: 14px;">';
    $systemRequirements .= ( !empty( $statusCheck->account_url ) ? __( 'You can join the webinar on mobile, tablet or desktop.', 'webinar-ignition' ) : 'You can join the webinar on mobile, tablet or desktop.' );
    $systemRequirements .= '</p>';
    $lp_main_headline = ( !empty( $statusCheck->account_url ) ? __( 'Introducing This Exclusive Webinar From', 'webinar-ignition' ) : 'Introducing This Exclusive Webinar From' );
    $cd_headline = ( !empty( $statusCheck->account_url ) ? __( 'You Are Viewing A Webinar That Is Not Yet Live - <b>We Go Live Soon!</b>', 'webinar-ignition' ) : 'You Are Viewing A Webinar That Is Not Yet Live - <b>We Go Live Soon!</b>' );
    $webinar_starts_soon = ( !empty( $statusCheck->account_url ) ? __( 'Webinar Starts Very Soon', 'webinar-ignition' ) : 'Webinar Starts Very Soon' );
    $email_signup_sbj = ( !empty( $statusCheck->account_url ) ? __( '[Reminder] Your Webinar ::', 'webinar-ignition' ) : '[Reminder] Your Webinar ::' );
    $email_signup_intro = ( !empty( $statusCheck->account_url ) ? __( 'Here is the webinar information you\'ve just signed up for...', 'webinar-ignition' ) : 'Here is the webinar information you\'ve just signed up for...' );
    $email_notiff_sbj_1 = ( !empty( $statusCheck->account_url ) ? __( 'WEBINAR REMINDER :: Goes Live Tomorrow ::', 'webinar-ignition' ) : 'WEBINAR REMINDER :: Goes Live Tomorrow ::' );
    $email_notiff_body_1 = ( !empty( $statusCheck->account_url ) ? __( 'This is a reminder that the webinar you signed up for is tomorrow...', 'webinar-ignition' ) : 'This is a reminder that the webinar you signed up for is tomorrow...' );
    $email_notiff_sbj_2 = ( !empty( $statusCheck->account_url ) ? __( 'WEBINAR REMINDER :: Goes Live In 1 Hour ::', 'webinar-ignition' ) : 'WEBINAR REMINDER :: Goes Live In 1 Hour ::' );
    $email_signup_heading = ( !empty( $statusCheck->account_url ) ? __( 'Information On The Webinar', 'webinar-ignition' ) : 'Information On The Webinar' );
    $email_signup_preview = ( !empty( $statusCheck->account_url ) ? __( "Here's info on the webinar you've signed up for...", 'webinar-ignition' ) : "Here's info on the webinar you've signed up for..." );
    $email_notiff_1_heading = ( !empty( $statusCheck->account_url ) ? __( "Information On Tomorrow's Webinar", 'webinar-ignition' ) : "Information On Tomorrow's Webinar" );
    $email_notiff_1_preview = ( !empty( $statusCheck->account_url ) ? __( "Here's info on tomorrow's webinar...", 'webinar-ignition' ) : "Here's info on tomorrow's webinar..." );
    $email_notiff_2_heading = ( !empty( $statusCheck->account_url ) ? __( 'Information On Your Webinar', 'webinar-ignition' ) : 'Information On Your Webinar' );
    $email_notiff_2_preview = ( !empty( $statusCheck->account_url ) ? __( "Here's info on today's webinar...", 'webinar-ignition' ) : "Here's info on today's webinar..." );
    $email_notiff_3_heading = ( !empty( $statusCheck->account_url ) ? __( 'Information On Your Webinar', 'webinar-ignition' ) : 'Information On Your Webinar' );
    $email_notiff_3_preview = ( !empty( $statusCheck->account_url ) ? __( 'The webinar is live...', 'webinar-ignition' ) : 'The webinar is live...' );
    $email_notiff_4_heading = ( !empty( $statusCheck->account_url ) ? __( 'Replay is live!', 'webinar-ignition' ) : 'Replay is live!' );
    $email_notiff_4_preview = ( !empty( $statusCheck->account_url ) ? __( 'The webinar replay is live...', 'webinar-ignition' ) : 'The webinar replay is live...' );
    $email_notiff_5_heading = ( !empty( $statusCheck->account_url ) ? __( 'Webinar replay is going down soon!', 'webinar-ignition' ) : 'Webinar replay is going down soon!' );
    $email_notiff_5_preview = ( !empty( $statusCheck->account_url ) ? __( 'The webinar replay is going down soon...', 'webinar-ignition' ) : 'The webinar replay is going down soon...' );
    $email_notiff_body_2 = ( !empty( $statusCheck->account_url ) ? __( 'The webinar is live in 1 hour!', 'webinar-ignition' ) : 'The webinar is live in 1 hour!' );
    $email_notiff_sbj_3 = ( !empty( $statusCheck->account_url ) ? __( 'We Are Live', 'webinar-ignition' ) : 'We Are Live' );
    $email_notiff_body_3 = ( !empty( $statusCheck->account_url ) ? __( 'We are live, on air and ready to go!', 'webinar-ignition' ) : 'We are live, on air and ready to go!' );
    $email_notiff_sbj_4 = ( !empty( $statusCheck->account_url ) ? __( 'Replay is live!', 'webinar-ignition' ) : 'Replay is live!' );
    $email_notiff_body_4 = ( !empty( $statusCheck->account_url ) ? __( 'We just posted the replay video for the webinar tonight...', 'webinar-ignition' ) : 'We just posted the replay video for the webinar tonight...' );
    $email_notiff_sbj_5 = ( !empty( $statusCheck->account_url ) ? __( 'WEBINAR REPLAY COMING DOWN SOON ::', 'webinar-ignition' ) : 'WEBINAR REPLAY COMING DOWN SOON ::' );
    $email_notiff_body_5 = ( !empty( $statusCheck->account_url ) ? __( 'Did you get a chance to check out the webinar replay? It\'s coming down very soon!', 'webinar-ignition' ) : 'Did you get a chance to check out the webinar replay? It\'s coming down very soon!' );
    $twilio_msg = ( !empty( $statusCheck->account_url ) ? __( 'The webinar is starting soon! Jump On Live:', 'webinar-ignition' ) : 'The webinar is starting soon! Jump On Live:' );
    $email_signup_body = $emailSetup . $systemRequirements;
    // Save Campaign Setup
    if ( 'new' === $clone ) {
        $webinar_duration = ( isset( $_POST['webinar_start_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['webinar_start_duration'] ) ) : 60 );
        $notification_times = webinarignition_live_notification_times(
            $live_date_array,
            $setTime,
            $timezone,
            $webinar_duration
        );
        // Data For New Webinar
        $dataArray = array(
            'id'                                         => (string) $campaignID,
            'webinar_lang'                               => $applang,
            'webinar_desc'                               => $desc,
            'webinar_host'                               => $host,
            'webinar_date'                               => $live_date,
            'webinar_start_time'                         => $notification_times['live']['time'],
            'webinar_start_duration'                     => ( isset( $_POST['webinar_start_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['webinar_start_duration'] ) ) : 60 ),
            'webinar_end_time'                           => $notification_times['live']['time'],
            'webinar_timezone'                           => $timezone,
            'lp_metashare_title'                         => $maintitle,
            'lp_metashare_desc'                          => $desc,
            'lp_main_headline'                           => '<h4 class="subheader">' . $lp_main_headline . ' ' . $host . '</h4><h2 class="wiWebinarTitle" id="150">' . $desc . '</h2>',
            'lp_webinar_subheadline'                     => '',
            'cd_headline'                                => '<h4 class="subheader">' . $cd_headline . '</h4><h2 margin-bottom: 30px;">' . $webinar_starts_soon . '</h2>',
            'email_signup_sbj'                           => $email_signup_sbj . " {$desc}",
            'email_signup_body'                          => str_replace( '%%INTRO%%', $email_signup_intro, $email_signup_body ),
            'email_notiff_date_1'                        => $notification_times['daybefore']['date'],
            'email_notiff_time_1'                        => $notification_times['daybefore']['time'],
            'email_notiff_status_1'                      => 'queued',
            'email_notiff_sbj_1'                         => $email_notiff_sbj_1 . " {$desc}",
            'email_notiff_body_1'                        => str_replace( '%%INTRO%%', $email_notiff_body_1, $emailSetup ),
            'email_notiff_date_2'                        => $notification_times['hourbefore']['date'],
            'email_notiff_time_2'                        => $notification_times['hourbefore']['time'],
            'email_notiff_status_2'                      => 'queued',
            'email_notiff_sbj_2'                         => $email_notiff_sbj_2 . " {$desc}",
            'email_signup_heading'                       => $email_signup_heading,
            'email_signup_preview'                       => $email_signup_preview,
            'email_notiff_1_heading'                     => $email_notiff_1_heading,
            'email_notiff_1_preview'                     => $email_notiff_1_preview,
            'email_notiff_2_heading'                     => $email_notiff_2_heading,
            'email_notiff_2_preview'                     => $email_notiff_2_preview,
            'email_notiff_3_heading'                     => $email_notiff_3_heading,
            'email_notiff_3_preview'                     => $email_notiff_3_preview,
            'email_notiff_4_heading'                     => $email_notiff_4_heading,
            'email_notiff_4_preview'                     => $email_notiff_4_preview,
            'email_notiff_5_heading'                     => $email_notiff_5_heading,
            'email_notiff_5_preview'                     => $email_notiff_5_preview,
            'email_notiff_body_2'                        => str_replace( '%%INTRO%%', $email_notiff_body_2, $emailSetup ) . $systemRequirements,
            'email_notiff_date_3'                        => $notification_times['live']['date'],
            'email_notiff_time_3'                        => $notification_times['live']['time'],
            'email_notiff_status_3'                      => 'queued',
            'email_notiff_sbj_3'                         => $email_notiff_sbj_3,
            'email_notiff_body_3'                        => str_replace( '%%INTRO%%', $email_notiff_body_3, $emailSetup ) . $systemRequirements,
            'email_notiff_date_4'                        => $notification_times['hourafter']['date'],
            'email_notiff_time_4'                        => $notification_times['hourafter']['time'],
            'email_notiff_status_4'                      => 'queued',
            'email_notiff_sbj_4'                         => $email_notiff_sbj_4,
            'email_notiff_body_4'                        => str_replace( '%%INTRO%%', $email_notiff_body_4, $emailSetup_replay ),
            'email_notiff_date_5'                        => $notification_times['dayafter']['date'],
            'email_notiff_time_5'                        => $notification_times['dayafter']['time'],
            'email_notiff_status_5'                      => 'queued',
            'email_notiff_sbj_5'                         => $email_notiff_sbj_5 . " {$desc}",
            'email_notiff_body_5'                        => str_replace( '%%INTRO%%', $email_notiff_body_5, $emailSetup_replay ),
            'email_twilio_date'                          => $notification_times['live']['date'],
            'email_twilio_time'                          => $notification_times['hourbefore']['time'],
            'email_twilio_status'                        => 'queued',
            'email_twilio'                               => 'off',
            'twilio_msg'                                 => $twilio_msg . ' {LINK}',
            'lp_banner_bg_style'                         => 'hide',
            'webinar_banner_bg_style'                    => 'hide',
            'ar_fields_order'                            => array('ar_name', 'ar_email'),
            'ar_required_fields'                         => array('ar_name', 'ar_email'),
            'ar_name'                                    => '',
            'ar_email'                                   => '',
            'lp_optin_name'                              => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optName', $applang, true ),
            'lp_optin_email'                             => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang, true ),
            'ar_hidden'                                  => '',
            'fb_id'                                      => '',
            'fb_secret'                                  => '',
            'ty_share_image'                             => '',
            'ar_url'                                     => '',
            'ar_method'                                  => '',
            'lp_background_color'                        => '',
            'lp_background_image'                        => '',
            'lp_cta_bg_color'                            => 'transparent',
            'lp_cta_type'                                => '',
            'lp_cta_video_url'                           => '',
            'lp_cta_video_code'                          => '',
            'lp_sales_headline'                          => '',
            'lp_sales_headline_color'                    => '',
            'lp_sales_copy'                              => '',
            'lp_optin_headline'                          => '',
            'lp_webinar_host_block'                      => '',
            'lp_host_image'                              => '',
            'lp_host_info'                               => '',
            'paid_status'                                => '',
            'ar_code'                                    => '',
            'lp_fb_button'                               => '',
            'ar_custom_date_format'                      => '',
            'lp_optin_button'                            => '',
            'lp_optin_btn_color'                         => '',
            'lp_optin_spam'                              => '',
            'lp_optin_closed'                            => '',
            'custom_ty_url_state'                        => '',
            'ty_ticket_headline'                         => '',
            'ty_ticket_subheadline'                      => '',
            'ty_cta_bg_color'                            => 'transparent',
            'ty_cta_type'                                => '',
            'ty_cta_html'                                => '',
            'ty_webinar_headline'                        => '',
            'ty_webinar_subheadline'                     => '',
            'ty_webinar_url'                             => '',
            'ty_share_toggle'                            => '',
            'ty_step2_headline'                          => '',
            'ty_fb_share'                                => '',
            'ty_tw_share'                                => '',
            'ty_share_intro'                             => '',
            'ty_share_reveal'                            => '',
            'webinar_switch'                             => 'countdown',
            'total_cd'                                   => '',
            'cd_button_show'                             => '',
            'cd_button_copy'                             => '',
            'cd_button_color'                            => '',
            'cd_button'                                  => '',
            'cd_button_url'                              => '',
            'cd_headline2'                               => '',
            'cd_months'                                  => '',
            'cd_weeks'                                   => '',
            'cd_days'                                    => '',
            'cd_hours'                                   => '',
            'cd_minutes'                                 => '',
            'cd_seconds'                                 => '',
            'webinar_info_block'                         => '',
            'webinar_info_block_title'                   => '',
            'webinar_info_block_host'                    => '',
            'webinar_info_block_eventtitle'              => '',
            'webinar_info_block_desc'                    => '',
            'privacy_status'                             => '',
            'webinar_live_video'                         => '',
            'webinar_live_bgcolor'                       => '',
            'webinar_banner_bg_color'                    => '',
            'webinar_banner_bg_repeater'                 => '',
            'webinar_banner_image'                       => '',
            'webinar_background_color'                   => '',
            'webinar_background_image'                   => '',
            'webinar_qa_title'                           => '',
            'webinar_qa'                                 => 'hide',
            'webinar_qa_name_placeholder'                => '',
            'webinar_qa_email_placeholder'               => '',
            'webinar_qa_desc_placeholder'                => '',
            'webinar_qa_button'                          => '',
            'webinar_qa_button_color'                    => '',
            'webinar_qa_thankyou'                        => '',
            'webinar_qa_custom'                          => '',
            'webinar_speaker'                            => '',
            'webinar_speaker_color'                      => '',
            'social_share_links'                         => '',
            'webinar_invite'                             => '',
            'webinar_invite_color'                       => '',
            'webinar_fb_share'                           => '',
            'webinar_tw_share'                           => '',
            'webinar_ld_share'                           => '',
            'webinar_callin'                             => '',
            'webinar_callin_copy'                        => '',
            'webinar_callin_color'                       => '',
            'webinar_callin_number'                      => '',
            'webinar_callin_color2'                      => '',
            'webinar_live'                               => '',
            'webinar_live_color'                         => '',
            'webinar_giveaway_toggle'                    => 'hide',
            'webinar_giveaway_title'                     => '',
            'webinar_giveaway'                           => '',
            'lp_banner_bg_color'                         => '',
            'lp_banner_bg_repeater'                      => '',
            'lp_banner_image'                            => '',
            'lp_cta_image'                               => '',
            'paid_headline'                              => '',
            'paid_button_type'                           => '',
            'paid_button_custom'                         => '',
            'payment_form'                               => '',
            'paypal_paid_btn_copy'                       => '',
            'paid_btn_color'                             => '',
            'stripe_secret_key'                          => '',
            'stripe_publishable_key'                     => '',
            'stripe_charge'                              => '',
            'stripe_charge_description'                  => '',
            'stripe_paid_btn_copy'                       => '',
            'paid_pay_url'                               => '',
            'lp_fb_copy'                                 => '',
            'lp_fb_or'                                   => '',
            'lp_optin_btn_image'                         => '',
            'lp_optin_btn'                               => '',
            'custom_ty_url'                              => '',
            'ty_cta_video_url'                           => '',
            'ty_cta_video_code'                          => '',
            'ty_cta_image'                               => '',
            'ty_werbinar_custom_url'                     => '',
            'ty_ticket_webinar_option'                   => '',
            'ty_ticket_webinar'                          => '',
            'ty_webinar_option_custom_title'             => '',
            'ty_ticket_host_option'                      => '',
            'ty_ticket_host'                             => '',
            'ty_webinar_option_custom_host'              => '',
            'ty_ticket_date_option'                      => '',
            'ty_ticket_date'                             => '',
            'ty_webinar_option_custom_date'              => '',
            'ty_ticket_time_option'                      => '',
            'ty_ticket_time'                             => '',
            'ty_webinar_option_custom_time'              => '',
            'tycd_countdown'                             => '',
            'tycd_progress'                              => '',
            'tycd_years'                                 => '',
            'tycd_months'                                => '',
            'tycd_weeks'                                 => '',
            'tycd_days'                                  => '',
            'ty_add_to_calendar_option'                  => '',
            'ty_calendar_headline'                       => '',
            'ty_calendar_google'                         => '',
            'ty_calendar_ical'                           => '',
            'skip_ty_page'                               => '',
            'txt_area'                                   => 'off',
            'txt_headline'                               => '',
            'txt_placeholder'                            => '',
            'txt_btn'                                    => '',
            'txt_reveal'                                 => '',
            'replay_video'                               => '',
            'replay_optional'                            => '',
            'replay_cd_date'                             => '',
            'replay_cd_time'                             => '',
            'replay_cd_headline'                         => '',
            'replay_timed_style'                         => '',
            'replay_order_copy'                          => '',
            'replay_order_url'                           => '',
            'replay_order_html'                          => '',
            'replay_order_time'                          => '',
            'replay_closed'                              => '',
            'footer_copy'                                => '',
            'footer_branding'                            => 'hide',
            'custom_lp_js'                               => '',
            'custom_lp_css'                              => '',
            'meta_site_title_ty'                         => '',
            'meta_desc_ty'                               => '',
            'custom_ty_js'                               => '',
            'custom_ty_css'                              => '',
            'meta_site_title_webinar'                    => '',
            'meta_desc_webinar'                          => '',
            'custom_webinar_js'                          => '',
            'custom_webinar_css'                         => '',
            'meta_site_title_replay'                     => '',
            'meta_desc_replay'                           => '',
            'custom_replay_js'                           => '',
            'custom_replay_css'                          => '',
            'footer_code'                                => '',
            'footer_code_ty'                             => '',
            'live_stats'                                 => '',
            'wp_head_footer'                             => '',
            'email_signup'                               => '',
            'email_notiff_1'                             => '',
            'email_notiff_2'                             => '',
            'email_notiff_3'                             => '',
            'email_notiff_4'                             => 'off',
            'email_notiff_5'                             => 'off',
            'twilio_id'                                  => '',
            'twilio_token'                               => '',
            'twilio_number'                              => '',
            'webinar_live_overlay'                       => '1',
            'replay_order_color'                         => '',
            'air_toggle'                                 => '',
            'live_ctas'                                  => [
                "1" => [
                    "air_toggle"           => "on",
                    "isAdvaceIframeActive" => "",
                    "air_amelia_toggle"    => "on",
                    "response"             => '[advanced_iframe src="https://checkout.freemius.com/plugin/7606/plan/23433/?coupon=&billing_cycle=monthly&hide_coupon=true&trial=paid&affiliate_user_id=0&currency=auto&locale=auto&user_email={EMAIL}&user_firstname={FIRSTNAME}" width=”100%” height="100dvh" transparency="true" use_shortcode_attributes_only=”true” scrolling="auto"]',
                    "cta_position"         => "outer",
                    "isTabNameAvailable"   => "",
                    "tab_text"             => "Inside Webinar offer",
                    "button_text"          => "",
                    "button_url"           => "",
                    "button_color"         => "#6BBA40",
                    "box_width"            => "60%",
                    "bg_transparency"      => "0",
                    "box_alignment"        => "center",
                ],
            ],
            'protected_webinar_id'                       => 'protected',
            'email_verification'                         => 'global',
            'protected_lead_id'                          => 'protected',
            'protected_webinar_redirection'              => '',
            'limit_lead_visit'                           => 'disabled',
            'limit_lead_timer'                           => '30',
            'webinar_status'                             => 'draft',
            'cta_position'                               => 'outer',
            'cta_alignment'                              => 'Center',
            'console_q_notifications'                    => 'no',
            'qstn_notification_email_sbj'                => __( 'You have new support questions for webinar ', 'webinar-ignition' ) . $desc,
            'enable_first_question_notification'         => 'no',
            'enable_after_webinar_question_notification' => 'no',
            'first_question_notification_sent'           => 'no',
            'after_webinar_question_notification_sent'   => 'no',
            'qstn_notification_email_body'               => __( 'Hi', 'webinar-ignition' ) . ' {support}, {attendee} ' . __( 'has asked a question in the', 'webinar-ignition' ) . ' {webinarTitle} ' . __( 'webinar and needs an answer. Click', 'webinar-ignition' ) . ' {link} ' . __( 'to answer this question now.', 'webinar-ignition' ),
            'templates_version'                          => 2,
            'date_format'                                => ( isset( $_POST['date_format'] ) ? sanitize_text_field( wp_unslash( $_POST['date_format'] ) ) : '' ),
            'date_format_custom'                         => ( isset( $_POST['date_format_custom'] ) ? sanitize_text_field( wp_unslash( $_POST['date_format_custom'] ) ) : '' ),
            'date_format_custom_new'                     => ( isset( $_POST['date_format_custom_new'] ) ? sanitize_text_field( wp_unslash( $_POST['date_format_custom_new'] ) ) : '' ),
            'time_format'                                => ( isset( $_POST['time_format'] ) ? sanitize_text_field( wp_unslash( $_POST['time_format'] ) ) : '' ),
            'settings_language'                          => ( isset( $_POST['settings_language'] ) ? sanitize_text_field( wp_unslash( $_POST['settings_language'] ) ) : '' ),
            'display_tz'                                 => 'no',
        );
        $obj = webinarignition_array_to_object( $dataArray );
        // no clone - new
        update_option( 'webinarignition_campaign_' . $campaignID, $obj );
    } elseif ( 'import' === $clone ) {
        // importing campaign -- update Name & Permalink
        $importcode = trim( $importcode );
        $webinar = json_decode( base64_decode( $importcode ) );
        //phpcs:ignore
        $webinar->webinarURLName2 = sanitize_text_field( wp_unslash( $_POST['appname'] ) );
        //phpcs:ignore
        $webinar->webinar_permalink = get_permalink( $getPostID );
        $webinar->id = (string) $campaignID;
        update_option( 'webinarignition_campaign_' . $campaignID, $webinar );
    } elseif ( 'auto' === $clone ) {
        // Data For New Webinar
        $webinar_starts_soon = ( !empty( $statusCheck->account_url ) ? __( 'Webinar Starts Very Soon', 'webinar-ignition' ) : 'Webinar Starts Very Soon' );
        $email_signup_sbj = ( !empty( $statusCheck->account_url ) ? __( '[Reminder] Your Webinar Information', 'webinar-ignition' ) : '[Reminder] Your Webinar Information' );
        $dataArray = array(
            'id'                                         => (string) $campaignID,
            'webinar_lang'                               => $applang,
            'settings_language'                          => ( isset( $_POST['settings_language'] ) ? sanitize_text_field( $_POST['settings_language'] ) : '' ),
            'webinar_desc'                               => $desc,
            'webinar_host'                               => $host,
            'webinar_date'                               => 'AUTO',
            'lp_metashare_title'                         => $maintitle,
            'lp_metashare_desc'                          => $desc,
            'lp_main_headline'                           => '<h4 class="subheader">' . $lp_main_headline . ' ' . $host . '</h4><h2 id="229">' . $desc . '</h2>',
            'cd_headline'                                => '<h4 class="subheader">' . $cd_headline . '</h4><h2 margin-bottom: 30px;">' . $webinar_starts_soon . '</h2>',
            'email_signup_sbj'                           => $email_signup_sbj,
            'email_signup_body'                          => str_replace( '%%INTRO%%', $email_signup_intro, $email_signup_body ),
            'email_notiff_sbj_1'                         => $email_notiff_sbj_1 . " {$desc}",
            'email_notiff_body_1'                        => str_replace( '%%INTRO%%', $email_notiff_body_1, $emailSetup ),
            'email_notiff_sbj_2'                         => $email_notiff_sbj_2 . " {$desc}",
            'email_signup_heading'                       => $email_signup_heading,
            'email_signup_preview'                       => $email_signup_preview,
            'email_notiff_1_heading'                     => $email_notiff_1_heading,
            'email_notiff_1_preview'                     => $email_notiff_1_preview,
            'email_notiff_2_heading'                     => $email_notiff_2_heading,
            'email_notiff_2_preview'                     => $email_notiff_2_preview,
            'email_notiff_3_heading'                     => $email_notiff_3_heading,
            'email_notiff_3_preview'                     => $email_notiff_3_preview,
            'email_notiff_4_heading'                     => $email_notiff_4_heading,
            'email_notiff_4_preview'                     => $email_notiff_4_preview,
            'email_notiff_5_heading'                     => $email_notiff_5_heading,
            'email_notiff_5_preview'                     => $email_notiff_5_preview,
            'email_notiff_body_2'                        => str_replace( '%%INTRO%%', $email_notiff_body_2, $emailSetup ) . $systemRequirements,
            'email_notiff_sbj_3'                         => $email_notiff_sbj_3,
            'email_notiff_body_3'                        => str_replace( '%%INTRO%%', $email_notiff_body_3, $emailSetup ) . $systemRequirements,
            'email_notiff_sbj_4'                         => $email_notiff_sbj_4,
            'email_notiff_body_4'                        => str_replace( '%%INTRO%%', $email_notiff_body_4, $emailSetup ),
            'email_notiff_sbj_5'                         => $email_notiff_sbj_5 . " {$desc}",
            'email_notiff_body_5'                        => str_replace( '%%INTRO%%', $email_notiff_body_5, $emailSetup ),
            'twilio_msg'                                 => $twilio_msg . ' {LINK}',
            'email_twilio'                               => 'off',
            'lp_banner_bg_style'                         => 'hide',
            'webinar_banner_bg_style'                    => 'hide',
            'auto_saturday'                              => 'yes',
            'auto_sunday'                                => 'yes',
            'auto_thursday'                              => 'yes',
            'auto_monday'                                => 'yes',
            'auto_friday'                                => 'yes',
            'auto_tuesday'                               => 'yes',
            'auto_wednesday'                             => 'yes',
            'auto_time_1'                                => '16:00',
            'auto_time_2'                                => '18:00',
            'auto_time_3'                                => '20:00',
            'auto_video_length'                          => '60',
            'auto_translate_local'                       => 'Local Time',
            'ar_fields_order'                            => array('ar_name', 'ar_email'),
            'ar_required_fields'                         => array('ar_name', 'ar_email'),
            'ar_name'                                    => '',
            'ar_email'                                   => '',
            'lp_optin_name'                              => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optName', $applang, true ),
            'lp_optin_email'                             => WebinarignitionManager::webinarignition_ar_field_translated_name( 'optEmail', $applang, true ),
            'lp_schedule_type'                           => 'customized',
            'auto_today'                                 => 'yes',
            'auto_day_offset'                            => 0,
            'auto_day_limit'                             => 7,
            'auto_blacklisted_dates'                     => '',
            'auto_timezone_type'                         => 'user_specific',
            'lp_background_color'                        => '',
            'lp_background_image'                        => '',
            'ty_share_image'                             => '',
            'lp_cta_bg_color'                            => 'transparent',
            'lp_cta_type'                                => '',
            'lp_cta_video_url'                           => '',
            'lp_cta_video_code'                          => '',
            'lp_sales_headline'                          => '',
            'lp_sales_headline_color'                    => '',
            'lp_sales_copy'                              => '',
            'lp_optin_headline'                          => '',
            'lp_webinar_host_block'                      => '',
            'lp_host_image'                              => '',
            'lp_host_info'                               => '',
            'paid_status'                                => '',
            'ar_code'                                    => '',
            'ar_custom_date_format'                      => '',
            'lp_optin_button'                            => '',
            'lp_optin_btn_color'                         => '',
            'lp_optin_spam'                              => '',
            'lp_optin_closed'                            => '',
            'custom_ty_url_state'                        => '',
            'ty_ticket_headline'                         => '',
            'ty_ticket_subheadline'                      => '',
            'ty_cta_bg_color'                            => 'transparent',
            'ty_cta_type'                                => '',
            'ty_cta_html'                                => '',
            'ty_webinar_headline'                        => '',
            'ty_webinar_subheadline'                     => '',
            'ty_webinar_url'                             => '',
            'ty_share_toggle'                            => '',
            'ty_step2_headline'                          => '',
            'ty_fb_share'                                => '',
            'ty_tw_share'                                => '',
            'ty_share_intro'                             => '',
            'ty_share_reveal'                            => '',
            'webinar_switch'                             => 'countdown',
            'total_cd'                                   => '',
            'cd_button_show'                             => '',
            'cd_button_copy'                             => '',
            'cd_button_color'                            => '',
            'cd_button'                                  => '',
            'cd_button_url'                              => '',
            'cd_headline2'                               => '',
            'cd_months'                                  => '',
            'cd_weeks'                                   => '',
            'cd_days'                                    => '',
            'cd_hours'                                   => '',
            'cd_minutes'                                 => '',
            'cd_seconds'                                 => '',
            'webinar_info_block'                         => '',
            'webinar_info_block_title'                   => '',
            'webinar_info_block_host'                    => '',
            'webinar_info_block_eventtitle'              => '',
            'webinar_info_block_desc'                    => '',
            'privacy_status'                             => '',
            'webinar_live_video'                         => '',
            'webinar_live_overlay'                       => '1',
            'webinar_live_bgcolor'                       => '',
            'webinar_banner_bg_color'                    => '',
            'webinar_banner_bg_repeater'                 => '',
            'webinar_banner_image'                       => '',
            'webinar_background_color'                   => '',
            'webinar_background_image'                   => '',
            'webinar_qa_title'                           => '',
            'webinar_qa'                                 => 'hide',
            'webinar_qa_name_placeholder'                => '',
            'webinar_qa_email_placeholder'               => '',
            'webinar_qa_desc_placeholder'                => '',
            'webinar_qa_button'                          => '',
            'webinar_qa_button_color'                    => '',
            'webinar_qa_thankyou'                        => '',
            'webinar_qa_custom'                          => '',
            'webinar_speaker'                            => '',
            'webinar_speaker_color'                      => '',
            'social_share_links'                         => '',
            'webinar_invite'                             => '',
            'webinar_invite_color'                       => '',
            'webinar_fb_share'                           => '',
            'webinar_tw_share'                           => '',
            'webinar_ld_share'                           => '',
            'webinar_callin'                             => '',
            'webinar_callin_copy'                        => '',
            'webinar_callin_color'                       => '',
            'webinar_callin_number'                      => '',
            'webinar_callin_color2'                      => '',
            'webinar_live'                               => '',
            'webinar_live_color'                         => '',
            'webinar_giveaway_toggle'                    => 'hide',
            'webinar_giveaway_title'                     => '',
            'webinar_giveaway'                           => '',
            'lp_banner_bg_color'                         => '',
            'lp_banner_bg_repeater'                      => '',
            'lp_banner_image'                            => '',
            'lp_cta_image'                               => '',
            'paid_headline'                              => '',
            'paid_button_type'                           => '',
            'paid_button_custom'                         => '',
            'payment_form'                               => '',
            'paid_btn_copy'                              => '',
            'paid_btn_color'                             => '',
            'stripe_secret_key'                          => '',
            'stripe_publishable_key'                     => '',
            'stripe_charge'                              => '',
            'stripe_charge_description'                  => '',
            'paid_pay_url'                               => '',
            'lp_fb_copy'                                 => '',
            'lp_fb_or'                                   => '',
            'lp_optin_btn_image'                         => '',
            'lp_optin_btn'                               => '',
            'custom_ty_url'                              => '',
            'ty_cta_video_url'                           => '',
            'ty_cta_video_code'                          => '',
            'ty_cta_image'                               => '',
            'ty_werbinar_custom_url'                     => '',
            'ty_ticket_webinar_option'                   => '',
            'ty_ticket_webinar'                          => '',
            'ty_webinar_option_custom_title'             => '',
            'ty_ticket_host_option'                      => '',
            'ty_ticket_host'                             => '',
            'ty_webinar_option_custom_host'              => '',
            'ty_ticket_date_option'                      => '',
            'ty_ticket_date'                             => '',
            'ty_webinar_option_custom_date'              => '',
            'ty_ticket_time_option'                      => '',
            'ty_ticket_time'                             => '',
            'ty_webinar_option_custom_time'              => '',
            'tycd_countdown'                             => '',
            'tycd_progress'                              => '',
            'tycd_years'                                 => '',
            'tycd_months'                                => '',
            'tycd_weeks'                                 => '',
            'tycd_days'                                  => '',
            'ty_add_to_calendar_option'                  => '',
            'ty_calendar_headline'                       => '',
            'ty_calendar_google'                         => '',
            'ty_calendar_ical'                           => '',
            'skip_ty_page'                               => '',
            'txt_area'                                   => 'off',
            'skip_instant_acces_confirm_page'            => 'yes',
            'txt_headline'                               => '',
            'txt_placeholder'                            => '',
            'txt_btn'                                    => '',
            'txt_reveal'                                 => '',
            'replay_video'                               => '',
            'replay_optional'                            => '',
            'replay_cd_date'                             => '',
            'replay_cd_time'                             => '',
            'replay_cd_headline'                         => '',
            'replay_timed_style'                         => '',
            'replay_order_copy'                          => '',
            'replay_order_url'                           => '',
            'replay_order_html'                          => '',
            'replay_order_time'                          => '',
            'replay_closed'                              => '',
            'footer_copy'                                => '',
            'footer_branding'                            => '',
            'custom_lp_js'                               => '',
            'custom_lp_css'                              => '',
            'meta_site_title_ty'                         => '',
            'meta_desc_ty'                               => '',
            'custom_ty_js'                               => '',
            'custom_ty_css'                              => '',
            'meta_site_title_webinar'                    => '',
            'meta_desc_webinar'                          => '',
            'custom_webinar_js'                          => '',
            'custom_webinar_css'                         => '',
            'meta_site_title_replay'                     => '',
            'meta_desc_replay'                           => '',
            'custom_replay_js'                           => '',
            'custom_replay_css'                          => '',
            'footer_code'                                => '',
            'footer_code_ty'                             => '',
            'live_stats'                                 => '',
            'wp_head_footer'                             => '',
            'email_signup'                               => '',
            'email_notiff_1'                             => '',
            'email_notiff_2'                             => '',
            'email_notiff_3'                             => '',
            'email_notiff_4'                             => '',
            'email_notiff_5'                             => '',
            'twilio_id'                                  => '',
            'twilio_token'                               => '',
            'twilio_number'                              => '',
            'webinar_source_toggle'                      => '',
            'auto_video_url'                             => '',
            'auto_video_load'                            => '',
            'webinar_show_videojs_controls'              => '',
            'webinar_iframe_source'                      => '',
            'auto_action'                                => '',
            'auto_action_time'                           => '',
            'auto_action_max_width'                      => '',
            'auto_action_transparency'                   => '',
            'auto_action_copy'                           => '',
            'auto_action_btn_copy'                       => '',
            'auto_action_url'                            => '',
            'replay_order_color'                         => '',
            'auto_redirect'                              => '',
            'auto_redirect_url'                          => '',
            'auto_redirect_delay'                        => 0,
            'auto_timezone_custom'                       => '',
            'auto_time_fixed'                            => '',
            'auto_timezone_fixed'                        => '',
            'delayed_day_offset'                         => '',
            'auto_time_delayed'                          => '',
            'delayed_timezone_type'                      => '',
            'auto_timezone_user_specific_name'           => '',
            'auto_timezone_delayed'                      => '',
            'delayed_blacklisted_dates'                  => '',
            'auto_translate_instant'                     => '',
            'auto_translate_headline1'                   => '',
            'auto_translate_subheadline1'                => '',
            'auto_translate_headline2'                   => '',
            'auto_translate_subheadline2'                => '',
            'lp_webinar_subheadline'                     => '',
            'fb_id'                                      => '',
            'fb_secret'                                  => '',
            'auto_video_url2'                            => '',
            'auto_date_fixed'                            => '',
            'auto_replay'                                => '',
            'protected_webinar_id'                       => 'protected',
            'email_verification'                         => 'global',
            'protected_webinar_redirection'              => '',
            'limit_lead_visit'                           => 'disabled',
            'limit_lead_timer'                           => '30',
            'webinar_status'                             => 'draft',
            'cta_position'                               => 'outer',
            'cta_alignment'                              => 'Center',
            'console_q_notifications'                    => 'no',
            'qstn_notification_email_sbj'                => __( 'You have new support questions for webinar ', 'webinar-ignition' ) . $desc,
            'enable_first_question_notification'         => 'no',
            'enable_after_webinar_question_notification' => 'no',
            'first_question_notification_sent'           => 'no',
            'after_webinar_question_notification_sent'   => 'no',
            'qstn_notification_email_body'               => __( 'Hi', 'webinar-ignition' ) . ' {support}, {attendee} ' . __( 'has asked a question in the', 'webinar-ignition' ) . ' {webinarTitle} ' . __( 'webinar and needs an answer. Click', 'webinar-ignition' ) . ' {link} ' . __( 'to answer this question now.', 'webinar-ignition' ),
            'templates_version'                          => 2,
            'date_format'                                => ( isset( $_POST['date_format'] ) ? sanitize_text_field( wp_unslash( $_POST['date_format'] ) ) : '' ),
            'date_format_custom'                         => ( isset( $_POST['date_format_custom'] ) ? sanitize_text_field( wp_unslash( $_POST['date_format_custom'] ) ) : '' ),
            'date_format_custom_new'                     => ( isset( $_POST['date_format_custom_new'] ) ? sanitize_text_field( wp_unslash( $_POST['date_format_custom_new'] ) ) : '' ),
            'time_format'                                => ( isset( $_POST['time_format'] ) ? sanitize_text_field( wp_unslash( $_POST['time_format'] ) ) : '' ),
            'auto_weekdays_1'                            => array(
                'mon',
                'tue',
                'wed',
                'thu',
                'fri',
                'sat',
                'sun'
            ),
            'auto_weekdays_2'                            => array(
                'mon',
                'tue',
                'wed',
                'thu',
                'fri',
                'sat',
                'sun'
            ),
            'auto_weekdays_3'                            => array(
                'mon',
                'tue',
                'wed',
                'thu',
                'fri',
                'sat',
                'sun'
            ),
            'display_tz'                                 => 'no',
        );
        $obj = webinarignition_array_to_object( $dataArray );
        $obj->wi_show_day = ( isset( $_POST['wi_show_day'] ) && !empty( $_POST['wi_show_day'] ) ? 1 : 0 );
        $obj->day_string = ( isset( $_POST['day_string'] ) && !empty( $_POST['day_string'] ) ? sanitize_text_field( wp_unslash( $_POST['day_string'] ) ) : 'D' );
        // save
        update_option( 'webinarignition_campaign_' . $campaignID, $obj );
    } else {
        // get option from parent campaign
        $cloneParent = WebinarignitionManager::webinarignition_get_webinar_data( $clone );
        $cloneParent->id = (string) $campaignID;
        $cloneParent->webinarURLName2 = sanitize_text_field( wp_unslash( $_POST['appname'] ) );
        $cloneParent->webinar_desc = sanitize_text_field( wp_unslash( $_POST['appname'] ) );
        $cloneParent->lp_metashare_title = sanitize_text_field( wp_unslash( $_POST['appname'] ) );
        $cloneParent->lp_metashare_desc = sanitize_text_field( wp_unslash( $_POST['appname'] ) );
        $cloneParent->default_registration_page = '';
        $cloneParent->lp_main_headline = "<h4 class='subheader'>" . $lp_main_headline . ' ' . $cloneParent->webinar_host . "</h4><h2 style='margin-top: -10px;'>" . $desc . '</h2>';
        update_option( 'webinarignition_campaign_' . $campaignID, $cloneParent );
    }
    //end if
    // Sanitize the campaign ID
    $campaignID = intval( $campaignID );
    // Assuming $campaignID is an integer
    $table_options = esc_sql( $wpdb->options );
    $webinar = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_options}` WHERE option_name LIKE %s", "webinarignition_campaign_{$campaignID}" . '%' ), ARRAY_A );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    $webinar_settings_string = $webinar['option_id'] . $webinar['option_value'];
    $webinar_hashed_id = sha1( $webinar_settings_string );
    $map = get_option( 'webinarignition_map_campaign_hash_to_id', array() );
    $map_rev = get_option( 'webinarignition_map_campaign_id_to_hash', array() );
    $map[$webinar_hashed_id] = $campaignID;
    $map_rev[$campaignID] = $webinar_hashed_id;
    update_option( 'webinarignition_map_campaign_hash_to_id', $map );
    update_option( 'webinarignition_map_campaign_id_to_hash', $map_rev );
    switch_to_locale( $current_locale_temp );
    // *****************************************************************************
    die;
}

// Edit Campaign
add_action( 'wp_ajax_webinarignition_edit', 'webinarignition_edit_callback' );
function webinarignition_edit_callback() {
    if ( !current_user_can( 'edit_posts' ) ) {
        return;
    }
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    //We need to process whole input here as we need the data
    $post_input = filter_input_array( INPUT_POST );
    $webinar_iid = sanitize_text_field( $post_input['id'] );
    $webinar_oold_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_iid );
    $hundered_saved_template = ( !empty( $webinar_oold_data->hunderedms_template ) ? esc_html( $webinar_oold_data->hunderedms_template ) : '' );
    $room_id = ( isset( $webinar_oold_data->hunderedms_template_room_id ) && !empty( $webinar_oold_data->hunderedms_template_room_id ) ? esc_html( $webinar_oold_data->hunderedms_template_room_id ) : '' );
    $hundered_ms_template_saved_subdomain = ( isset( $webinar_oold_data->hundered_ms_template_subdomain ) && !empty( $webinar_oold_data->hundered_ms_template_subdomain ) ? esc_html( $webinar_oold_data->hundered_ms_template_subdomain ) : '' );
    $admin_meeting_code = ( isset( $webinar_oold_data->admin_meeting_code ) && !empty( $webinar_oold_data->admin_meeting_code ) ? esc_html( $webinar_oold_data->admin_meeting_code ) : '' );
    $attendee_meeting_code = ( isset( $webinar_oold_data->attendee_meeting_code ) && !empty( $webinar_oold_data->attendee_meeting_code ) ? esc_html( $webinar_oold_data->attendee_meeting_code ) : '' );
    $post_input['webinar_switch'] = $webinar_oold_data->webinar_switch;
    $bearer_token = $post_input['hundered_ms_token'] ?? '';
    $hundered_ms_template_subdomain = $post_input['hundered_ms_template_subdomain'] ?? '';
    $hunderedms_template = $post_input['hunderedms_template'] ?? '';
    update_option( 'hundered_ms_management_token', $bearer_token );
    if ( ($room_id == '' || $hunderedms_template != $hundered_saved_template || $admin_meeting_code == '' || $attendee_meeting_code == '') && $bearer_token != '' && $hunderedms_template != '' && $hundered_ms_template_subdomain != '' ) {
        $room_body = '{
				"name": "webinarignition-room-' . $webinar_iid . '",
				"description": "This is a webinarignition room for webinar id ' . $webinar_iid . '",
				"template_id": "' . $hunderedms_template . '"
			}';
        $response_room = wp_remote_post( 'https://api.100ms.live/v2/rooms', array(
            'method'  => 'POST',
            'headers' => array(
                'Authorization' => 'Bearer ' . $bearer_token,
                'Content-Type'  => 'application/json',
            ),
            'body'    => $room_body,
            'timeout' => 60,
        ) );
        if ( is_wp_error( $response_room ) ) {
            error_log( 'Request failed: ' . $response_room->get_error_message() );
        } else {
            $status_code = wp_remote_retrieve_response_code( $response_room );
            $response_body = wp_remote_retrieve_body( $response_room );
            $response_body = json_decode( $response_body, true );
            // Decode the JSON response
            $room_id = $response_body['id'] ?? '';
            $hundered_ms_room_codes = WebinarignitionManager::generate_100ms_room_codes( $bearer_token, $room_id );
            foreach ( $hundered_ms_room_codes['data'] as $key => $value ) {
                $hundered_ms_room_codes[$key] = esc_html( $value );
                if ( $value['role'] == 'host' || $value['role'] == 'broadcaster' ) {
                    $admin_meeting_code = $value['code'];
                } elseif ( $value['role'] == 'participant' || $value['role'] == 'viewer' || $value['role'] == 'guest' || $value['role'] == 'viewer-realtime' ) {
                    $attendee_meeting_code = $value['code'];
                }
            }
        }
    }
    // Remove extra spaces from $post_input array values
    $post_input = array_map( function ( $value ) {
        if ( is_string( $value ) ) {
            return trim( $value );
        } elseif ( is_array( $value ) ) {
            // Recursively trim sub-arrays
            return array_map( function ( $item ) {
                return ( is_array( $item ) ? array_map( 'trim', $item ) : trim( $item ) );
            }, $value );
        }
        return $value;
    }, $post_input );
    // NB: Date is stored in DB in format m-d-Y but displayed according to user's chosen option
    $live_date = sanitize_text_field( $post_input['webinar_date'] );
    if ( !empty( $live_date ) && 'AUTO' !== $live_date ) {
        $live_date = $post_input['webinar_date_submit'];
        $post_input['webinar_date'] = $post_input['webinar_date_submit'];
        $live_date_array = explode( '-', $live_date );
        // ['m', 'd', 'Y']
        $post_input['email_notiff_date_1'] = $post_input['email_notiff_date_1_submit'];
        $post_input['email_notiff_date_2'] = $post_input['email_notiff_date_2_submit'];
        $post_input['email_notiff_date_3'] = $post_input['email_notiff_date_3_submit'];
        $post_input['email_notiff_date_4'] = $post_input['email_notiff_date_4_submit'];
        $post_input['email_notiff_date_5'] = $post_input['email_notiff_date_5_submit'];
        $post_input['email_notiff_time_1'] = $post_input['email_notiff_time_1_submit'];
        $post_input['email_notiff_time_2'] = $post_input['email_notiff_time_2_submit'];
        $post_input['email_notiff_time_3'] = $post_input['email_notiff_time_3_submit'];
        $post_input['email_notiff_time_4'] = $post_input['email_notiff_time_4_submit'];
        $post_input['email_notiff_time_5'] = $post_input['email_notiff_time_5_submit'];
        $post_input['webinar_start_time'] = $post_input['webinar_start_time_submit'];
        $post_input['replay_cd_date'] = $post_input['replay_cd_date_submit'];
    }
    if ( isset( $post_input['auto_date_fixed_submit'] ) ) {
        $auto_date_fixed_date_object = DateTime::createFromFormat( 'm-d-Y', $post_input['auto_date_fixed_submit'] );
    } else {
        $auto_date_fixed_date_object = NULL;
    }
    if ( $auto_date_fixed_date_object ) {
        $post_input['auto_date_fixed'] = $auto_date_fixed_date_object->format( 'Y-m-d' );
    } else {
        // Handle the error, e.g., log it or set a default value
        $post_input['auto_date_fixed'] = null;
        // Or set to a default date value as needed
    }
    // Get ID & Post Data Array
    $id = sanitize_text_field( $post_input['id'] );
    $data = $post_input;
    // get old webinar data for comparison
    $old_webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $id );
    $additional_autoactions = array();
    $auto_action_time_array = array();
    $auto_action_time_end_array = array();
    foreach ( $post_input as $key => $value ) {
        if ( is_string( $key ) && false !== strpos( $key, 'additional-autoaction__' ) ) {
            $additional_autoaction = explode( '__', $key );
            if ( 3 === count( $additional_autoaction ) && !empty( $additional_autoaction[2] ) ) {
                $index = $additional_autoaction[2];
                $field = $additional_autoaction[1];
                if ( 'auto_action_time_min' === $field ) {
                    //phpcs:ignore
                } elseif ( 'auto_action_time_sec' === $field ) {
                    //phpcs:ignore
                } elseif ( 'auto_action_time' === $field ) {
                    $additional_autoactions[$index][$field] = stripslashes( $value );
                } elseif ( 'auto_action_time_end' === $field ) {
                    $additional_autoactions[$index][$field] = stripslashes( $value );
                } else {
                    $additional_autoactions[$index][$field] = stripslashes( $value );
                }
                unset($post_input[$key]);
                unset($data[$key]);
            } else {
                unset($post_input[$key]);
                unset($data[$key]);
            }
            //end if
        }
        //end if
        if ( 'auto_action_time_min' === $key ) {
            $auto_action_time_array['min'] = (int) $value;
            unset($post_input[$key]);
            unset($data[$key]);
        }
        if ( 'auto_action_time_sec' === $key ) {
            $auto_action_time_array['sec'] = (int) $value;
            unset($post_input[$key]);
            unset($data[$key]);
        }
        if ( 'auto_action_time_end_min' === $key ) {
            $auto_action_time_end_array['min'] = (int) $value;
            unset($post_input[$key]);
            unset($data[$key]);
        }
        if ( 'auto_action_time_end_sec' === $key ) {
            $auto_action_time_end_array['sec'] = (int) $value;
            unset($post_input[$key]);
            unset($data[$key]);
        }
    }
    $post_input['video_integration'] = 'embeded_video';
    $data['video_integration'] = 'embeded_video';
    if ( !empty( $auto_action_time_array ) ) {
        $auto_action_time = '';
        if ( !empty( $auto_action_time_array['min'] ) ) {
            $auto_action_time .= $auto_action_time_array['min'];
        } else {
            $auto_action_time .= '0';
        }
        if ( !empty( $auto_action_time_array['sec'] ) ) {
            $sec = (int) $auto_action_time_array['sec'];
            if ( $sec < 10 ) {
                $sec = '0' . $sec;
            } elseif ( $sec > 60 ) {
                $sec = '60';
            }
            $auto_action_time .= ':' . $sec;
        } else {
            $auto_action_time .= ':00';
        }
    } else {
        $auto_action_time = ( !empty( $data['auto_action_time'] ) ? $data['auto_action_time'] : '' );
    }
    //end if
    if ( !empty( $auto_action_time_end_array ) ) {
        $auto_action_time_end = '';
        if ( !empty( $auto_action_time_end_array['min'] ) ) {
            $auto_action_time_end .= $auto_action_time_end_array['min'];
        } else {
            $auto_action_time_end .= '0';
        }
        if ( !empty( $auto_action_time_end_array['sec'] ) ) {
            $sec = (int) $auto_action_time_end_array['sec'];
            if ( $sec < 10 ) {
                $sec = '0' . $sec;
            } elseif ( $sec > 60 ) {
                $sec = '60';
            }
            $auto_action_time_end .= ':' . $sec;
        } else {
            $auto_action_time_end .= ':00';
        }
    } else {
        $auto_action_time_end = ( !empty( $data['auto_action_time_end'] ) ? $data['auto_action_time_end'] : '' );
    }
    //end if
    if ( empty( $additional_autoactions ) && isset( $post_input['additional_autoactions_serialise'] ) ) {
        $data['additional_autoactions'] = $post_input['additional_autoactions_serialise'];
    } else {
        /**
         * First normalize data and then serialize to proper data serialization without any loss
         */
        $normalized_data = wi_normalize_data( $additional_autoactions );
        $data['additional_autoactions'] = maybe_serialize( $normalized_data );
    }
    $data['auto_action_time'] = $auto_action_time;
    $data['auto_action_time_end'] = $auto_action_time_end;
    // fix issue where default webinar length and iframe video length settings override each other.
    if ( isset( $post_input['webinar_source_toggle'] ) && $post_input['webinar_source_toggle'] === 'default' ) {
        if ( !empty( $post_input['auto_video_length_default'] ) && is_numeric( $post_input['auto_video_length_default'] ) ) {
            $data['auto_video_length'] = $post_input['auto_video_length_default'];
        }
    }
    // change Youtube urls (iframe only) from http to https
    $youtubeUrlsToCheck = array(
        'webinar_live_video',
        // live webinar video
        'replay_video',
        // live replay
        'webinar_iframe_source',
    );
    foreach ( $youtubeUrlsToCheck as $formFieldName ) {
        if ( !empty( $data[$formFieldName] ) ) {
            $wi_iframe = $data[$formFieldName];
            if ( strpos( $wi_iframe, 'youtube' ) || strpos( $wi_iframe, 'youtu.be' ) ) {
                $wi_iframe = str_replace( 'http://', 'https://', $wi_iframe );
                $data[$formFieldName] = $wi_iframe;
            }
        }
    }
    if ( isset( $post_input['webinar_source_toggle'] ) && 'iframe' === $post_input['webinar_source_toggle'] ) {
        $data['webinar_live_overlay'] = $post_input['webinar_live_overlay1'];
        unset($data['webinar_live_overlay1']);
    }
    if ( isset( $_POST['notify_current_user'] ) && $_POST['notify_current_user'] === 'yes' ) {
        $data['notify_current_user'] = 'yes';
    } else {
        $data['notify_current_user'] = 'no';
    }
    if ( isset( $_POST['wi_live_previous_date'] ) && !empty( $_POST['wi_live_previous_date'] ) ) {
        // Save the actual previous_date value in the $data array
        $data['wi_live_previous_date'] = $_POST['wi_live_previous_date'];
    } else {
        // Handle the case where the previous_date is not set
        $data['wi_live_previous_date'] = '';
    }
    if ( isset( $_POST['wi_compare_timestamp'] ) && !empty( $_POST['wi_compare_timestamp'] ) ) {
        // Save the actual wi_compare_timestamp value in the $data array
        $data['wi_compare_timestamp'] = $_POST['wi_compare_timestamp'];
    } else {
        // Handle the case where the wi_compare_timestamp is not set
        $data['wi_compare_timestamp'] = '';
    }
    foreach ( $data as $key => $value ) {
        // Check if the value is not null and is not an array before calling stripslashes()
        if ( !is_array( $value ) && $value !== null ) {
            $data[$key] = stripslashes( $value );
        } else {
            $data[$key] = $value;
        }
    }
    // Convert Array To Object
    $object = webinarignition_array_to_object( $data );
    if ( strpos( $object->webinar_date, '-' ) ) {
        $fullDate = explode( '-', $object->webinar_date );
    } else {
        $fullDate = explode( '/', $object->webinar_date );
    }
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $id );
    if ( !empty( $webinar_data->webinar_start_time ) && strtotime( webinarignition_build_time( $webinar_data->webinar_date, $webinar_data->webinar_start_time ) ) !== strtotime( webinarignition_build_time( $object->webinar_date, $object->webinar_start_time ) ) ) {
        // Webinar Date has changed
        // Update email notification dates & times
        $notification_times = webinarignition_live_notification_times(
            $fullDate,
            $object->webinar_start_time,
            $object->webinar_timezone,
            $object->webinar_start_duration
        );
        $object->email_notiff_date_1 = $notification_times['daybefore']['date'];
        $object->email_notiff_time_1 = $notification_times['daybefore']['time'];
        $object->email_notiff_status_1 = 'queued';
        $object->email_notiff_date_2 = $notification_times['hourbefore']['date'];
        $object->email_notiff_time_2 = $notification_times['hourbefore']['time'];
        $object->email_notiff_status_2 = 'queued';
        $object->email_notiff_date_3 = $notification_times['live']['date'];
        $object->email_notiff_time_3 = $notification_times['live']['time'];
        $object->email_notiff_status_3 = 'queued';
        $object->email_notiff_date_4 = $notification_times['hourafter']['date'];
        $object->email_notiff_time_4 = $notification_times['hourafter']['time'];
        $object->email_notiff_status_4 = 'queued';
        $object->email_notiff_date_5 = $notification_times['dayafter']['date'];
        $object->email_notiff_time_5 = $notification_times['dayafter']['time'];
        $object->email_notiff_status_5 = 'queued';
        $object->email_twilio_date = $notification_times['live']['date'];
        $object->email_twilio_time = $notification_times['hourbefore']['time'];
    }
    //end if
    // just in case date or time formats have backslashes, as in 'j \d\e F \d\e Y'
    $object->date_format = $post_input['date_format'];
    $object->date_format_custom = $post_input['date_format_custom'];
    $object->date_format_custom_new = ( isset( $post_input['date_format_custom_new'] ) ? $post_input['date_format_custom_new'] : '' );
    $object->time_format = $post_input['time_format'];
    $live_video_data = ( isset( $object->webinar_live_video ) ? $object->webinar_live_video : '' );
    if ( $live_video_data == '' ) {
        $object->email_notiff_4 = 'off';
        $object->email_notiff_5 = 'off';
    }
    if ( 'AUTO' === $live_date ) {
        $object->wi_show_day = ( isset( $post_input['wi_show_day'] ) && !empty( $post_input['wi_show_day'] ) ? 1 : 0 );
        $object->day_string = ( isset( $post_input['day_string'] ) && !empty( $post_input['day_string'] ) ? sanitize_text_field( $post_input['day_string'] ) : 'D' );
    }
    // Keep air CTA settings intact on webinar save
    // $air_cta_fields = array( 'air_toggle', 'air_btn_copy', 'air_btn_url', 'air_btn_color', 'air_html', 'air_tab_copy', 'live_webinar_ctas_position_radios', 'live_webinar_ctas_alignment_radios', 'air_amelia_toggle', 'air_broadcast_message_width', 'air_broadcast_message_bg_transparency' );
    // foreach ( $air_cta_fields as $air_cta_field ) {
    // 	if ( ! isset( $webinar_data->{$air_cta_field} ) || empty( $webinar_data->{$air_cta_field} ) ) {
    // 		continue;
    // 	}
    // 	if ( 'air_html' === $air_cta_field ) {
    // 		$object->{$air_cta_field} = sanitize_post( $webinar_data->{$air_cta_field} );
    // 	} else {
    // 		$object->{$air_cta_field} = sanitize_text_field( $webinar_data->{$air_cta_field} );
    // 	}
    // }
    if ( isset( $webinar_data->live_ctas ) && is_array( $webinar_data->live_ctas ) ) {
        $object->live_ctas = [];
        foreach ( $webinar_data->live_ctas as $cta_index => $cta_data ) {
            $sanitized_cta = [];
            foreach ( $cta_data as $key => $value ) {
                // Choose appropriate sanitization
                if ( $key === 'air_html' ) {
                    $sanitized_cta[$key] = sanitize_post( $value );
                } else {
                    $sanitized_cta[$key] = sanitize_text_field( $value );
                }
            }
            $object->live_ctas[$cta_index] = $sanitized_cta;
        }
    }
    // Keep webinar settings language intact after saving AR fields
    $object->hundered_ms_template_subdomain = $hundered_ms_template_subdomain;
    $object->hunderedms_template = $hunderedms_template;
    $object->admin_meeting_code = $admin_meeting_code;
    $object->attendee_meeting_code = $attendee_meeting_code;
    $object->hunderedms_template_room_id = $room_id;
    $object->settings_language = $old_webinar_data->settings_language;
    // Update Option Field:
    update_option( 'webinarignition_campaign_' . $id, $object );
    // Resave & Redo URL
    $webinarName = $object->webinarURLName2;
    // Get Current Name From DB
    global $wpdb;
    $table_db_name = $wpdb->prefix . 'webinarignition';
    // Sanitize the input value
    $id = intval( $id );
    // Assuming $id is an integer
    $webinars = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE id = %d", $id ), OBJECT );
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery
    if ( count( $webinars ) ) {
        $webinar = $webinars[0];
        if ( $webinar->appname !== $webinarName ) {
            $wpdb->update( $table_db_name, array(
                'appname' => $webinarName,
            ), array(
                'id' => $id,
            ) );
            // ReName permalink URL
            $my_post = array();
            $my_post['ID'] = $webinar->postID;
            $my_post['post_name'] = $webinarName;
            wp_update_post( $my_post );
        }
    }
    do_action( 'webinar_saved', $webinar_data, $old_webinar_data );
}

add_action(
    'webinar_saved',
    'webinarignition_save_support_staff',
    10,
    2
);
function webinarignition_save_support_staff(  $webinar_data, $old_webinar_data  ) {
    if ( isset( $webinar_data->enable_support ) && 'yes' === $webinar_data->enable_support && isset( $webinar_data->support_staff_count ) && !empty( $webinar_data->support_staff_count ) ) {
        for ($x = 1; $x <= $webinar_data->support_staff_count; $x++) {
            $member_email_str = 'member_email_' . $x;
            $member_first_name_str = 'member_first_name_' . $x;
            $member_last_name_str = 'member_last_name_' . $x;
            if ( property_exists( $webinar_data, $member_email_str ) && property_exists( $webinar_data, $member_first_name_str ) && property_exists( $webinar_data, $member_last_name_str ) ) {
                $member_email = $webinar_data->{'member_email_' . $x};
                $member = get_user_by( 'email', $member_email );
                $member_first_name = $webinar_data->{'member_first_name_' . $x};
                $member_last_name = $webinar_data->{'member_last_name_' . $x};
                if ( empty( $member ) ) {
                    $password = wp_generate_password( absint( 15 ), true, false );
                    $display_name = $member_first_name . ' ' . $member_last_name;
                    $user_id = wp_insert_user( array(
                        'user_login'   => $member_email,
                        'user_email'   => sanitize_email( $member_email ),
                        'user_pass'    => $password,
                        'display_name' => $display_name,
                        'first_name'   => $member_first_name,
                        'last_name'    => $member_last_name,
                        'role'         => 'subscriber',
                    ) );
                    $str = $user_id . time() . uniqid( '', true );
                    $_wi_support_token = md5( $str );
                    update_user_meta( $user_id, '_wi_support_token', $_wi_support_token );
                }
                //end if
            }
            //end if
        }
        //end for
    }
    //end if
}

add_action( 'init', 'webinarignition_add_additional_host_role' );
function webinarignition_add_additional_host_role() {
    add_role( 'webinarignition_host', 'WebinarIgnition Host', array(
        'manage_options'       => true,
        'edit_posts'           => true,
        'edit_others_posts'    => true,
        'edit_published_posts' => true,
        'publish_posts'        => true,
        'edit_pages'           => true,
        'edit_published_pages' => true,
        'publish_pages'        => true,
        'edit_others_pages'    => true,
    ) );
    $subscriber_role = get_role( 'subscriber' );
    if ( $subscriber_role ) {
        add_role( 'webinarignition_support', 'WebinarIgnition Support', $subscriber_role->capabilities );
    }
}

add_filter(
    'login_redirect',
    'webinarignition_redirect_webinarignition_host',
    10,
    3
);
function webinarignition_redirect_webinarignition_host(  $redirect_to, $request, $user  ) {
    if ( isset( $user->roles ) && is_array( $user->roles ) && in_array( 'webinarignition_host', $user->roles, true ) ) {
        $redirect_to = get_admin_url() . '?page=webinarignition-dashboard';
    }
    return $redirect_to;
}

add_action(
    'webinar_saved',
    'webinarignition_save_additional_hosts',
    20,
    2
);
function webinarignition_save_additional_hosts(  $webinar_data  ) {
    if ( isset( $webinar_data->enable_multiple_hosts ) && 'yes' === $webinar_data->enable_multiple_hosts && isset( $webinar_data->host_member_count ) && !empty( $webinar_data->host_member_count ) ) {
        for ($x = 1; $x <= $webinar_data->host_member_count; $x++) {
            $host_member_email_str = 'host_member_email_' . $x;
            $host_member_first_name_str = 'host_member_first_name_' . $x;
            $host_member_last_name_str = 'host_member_last_name_' . $x;
            if ( property_exists( $webinar_data, $host_member_email_str ) && property_exists( $webinar_data, $host_member_first_name_str ) && property_exists( $webinar_data, $host_member_last_name_str ) ) {
                $host_member_email = $webinar_data->{'host_member_email_' . $x};
                if ( filter_var( $host_member_email, FILTER_VALIDATE_EMAIL ) ) {
                    $member = get_user_by( 'email', $host_member_email );
                    $host_member_first_name = $webinar_data->{'host_member_first_name_' . $x};
                    $host_member_last_name = $webinar_data->{'host_member_last_name_' . $x};
                    if ( empty( $member ) ) {
                        $password = wp_generate_password( absint( 15 ), true, false );
                        $display_name = $host_member_first_name . ' ' . $host_member_last_name;
                        $user_id = wp_insert_user( array(
                            'user_login'   => $host_member_email,
                            'user_email'   => sanitize_email( $host_member_email ),
                            'user_pass'    => $password,
                            'display_name' => $display_name,
                            'first_name'   => $host_member_first_name,
                            'last_name'    => $host_member_last_name,
                            'role'         => 'webinarignition_host',
                        ) );
                        if ( isset( $webinar_data->send_user_notification ) && !empty( $webinar_data->send_user_notification ) ) {
                            webinarignition_new_user_notification( $user_id, null, 'both' );
                        }
                    }
                }
                //end if
            }
            //end if
        }
        //end for
    }
    //end if
}

add_action(
    'webinar_saved',
    'webinarignition_send_after_live_webinar_questions',
    10,
    2
);
function webinarignition_send_after_live_webinar_questions(  $webinar_data, $webinar_old_data  ) {
    if ( 'AUTO' === $webinar_data->webinar_date ) {
        return;
    }
    if ( !empty( $webinar_data->console_q_notifications ) && 'yes' === $webinar_data->console_q_notifications && !empty( $webinar_data->enable_after_webinar_question_notification ) && 'yes' === $webinar_data->enable_after_webinar_question_notification && 'closed' === $webinar_data->webinar_switch && $webinar_data->webinar_switch !== $webinar_old_data->webinar_switch ) {
        if ( filter_var( $webinar_data->host_questions_notifications_email, FILTER_VALIDATE_EMAIL ) ) {
            global $wpdb;
            $table_db_name = $wpdb->prefix . 'webinarignition_questions';
            $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s WHERE app_id = %d", $table_db_name, $webinar_data->id ), OBJECT );
            if ( empty( $results ) ) {
                $table_db_name = $wpdb->prefix . 'webinarignition_questions_new';
                // for older installations that stored questions in this table
                $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM %s WHERE app_id = %d", $table_db_name, $webinar_data->id ), OBJECT );
            }
            $upload_dir = wp_upload_dir();
            $wi_dirname = $upload_dir['basedir'] . '/webinarignition';
            if ( !file_exists( $wi_dirname ) ) {
                wp_mkdir_p( $wi_dirname );
            }
            $filename = $wi_dirname . '/webinar_' . $webinar_data->id . '_questions.csv';
            // Initialize the WordPress filesystem, no direct file operations allowed
            if ( !function_exists( 'WP_Filesystem' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            global $wp_filesystem;
            WP_Filesystem();
            $file = $filename;
            $handle = $wp_filesystem->fopen( $file, 'w' );
            foreach ( $results as $result ) {
                $question = array();
                $question[] = $result->name;
                $question[] = $result->email;
                $question[] = str_replace( ',', ' -', $result->created );
                $question[] = $result->status;
                $question[] = $result->question;
                // Use $wp_filesystem methods
                $wp_filesystem->fputcsv( $handle, $question );
            }
            $wp_filesystem->fclose( $handle );
            $email_data = new stdClass();
            $csv_link = $upload_dir['baseurl'] . '/webinarignition/webinar_' . $webinar_data->id . '_questions.csv';
            $host_email = ( !empty( $webinar_data->host_questions_notifications_email ) ? $webinar_data->host_questions_notifications_email : get_option( 'webinarignition_smtp_email' ) );
            $email_data->email_subject = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
            $email_data->bodyContent = '<p>' . __( 'Here is a link to the questions your webinar attendees asked in your recent webinar:', 'webinar-ignition' );
            $email_data->bodyContent .= '<a href="' . $csv_link . '">' . __( 'Webinar Questions', 'webinar-ignition' ) . '</a></p>';
            $email_data->bodyContent .= '<p>' . __( 'The file is also attached to this email for your convenience.', 'webinar-ignition' ) . '</p>';
            $attachments = array($wi_dirname . '/webinar_' . $webinar_data->id . '_questions.csv');
            $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && 'show' === $webinar_data->show_or_hide_local_qstn_answer_email_footer ? $webinar_data->qstn_answer_email_footer : '' );
            $email_data->emailheading = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $email_data->emailpreview = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $email = new WI_Emails();
            $emailBody = $email->webinarignition_build_email( $email_data );
            wp_mail(
                $host_email,
                $email_data->email_subject,
                $emailBody,
                $headers,
                $attachments
            );
        }
        //end if
    }
    //end if
}

add_action( 'wp_ajax_nopriv_webinarignition_after_auto_webinar', 'webinarignition_after_auto_webinar_callback' );
add_action( 'wp_ajax_webinarignition_after_auto_webinar', 'webinarignition_after_auto_webinar_callback' );
function webinarignition_after_auto_webinar_callback() {
    if ( !wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
        wp_send_json_error( [
            'message' => 'Invalid security token',
        ], 403 );
        exit;
    }
    $webinar_id = sanitize_text_field( filter_input( INPUT_POST, 'webinar_id' ) );
    $email = ( isset( $_POST['email'] ) ? sanitize_email( filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL ) ) : '' );
    $attendee_name = sanitize_text_field( filter_input( INPUT_POST, 'name' ) );
    $lead_id = sanitize_text_field( filter_input( INPUT_POST, 'lead' ) );
    $webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );
    $send_question_notification = false;
    $host_notification_email = filter_var( $webinar_data->host_questions_notifications_email, FILTER_VALIDATE_EMAIL );
    if ( isset( $webinar_data->console_q_notifications ) && 'yes' === $webinar_data->console_q_notifications && isset( $webinar_data->enable_after_webinar_question_notification ) && 'yes' === $webinar_data->enable_after_webinar_question_notification ) {
        global $wpdb;
        $table_db_name = $wpdb->prefix . 'webinarignition_questions';
        // Sanitize the input values
        $webinar_id = intval( $webinar_id );
        // Assuming $webinar_id is an integer
        $email = sanitize_email( $email );
        // Assuming $email is a sanitized email address
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE `app_id` = %d AND `email` = %s", $webinar_id, $email ), OBJECT );
        if ( !empty( $results ) ) {
            $upload_dir = wp_upload_dir();
            $wi_dirname = $upload_dir['basedir'] . '/webinarignition';
            if ( !file_exists( $wi_dirname ) ) {
                wp_mkdir_p( $wi_dirname );
            }
            $filename = $wi_dirname . '/webinar_' . $webinar_data->id . '_questions_' . $lead_id . '.csv';
            if ( !function_exists( 'WP_Filesystem' ) ) {
                require_once ABSPATH . 'wp-admin/includes/file.php';
            }
            global $wp_filesystem;
            WP_Filesystem();
            $handle = $wp_filesystem->fopen( $filename, 'w' );
            foreach ( $results as $result ) {
                $question = array();
                $question[] = $result->name;
                $question[] = $result->email;
                $question[] = str_replace( ',', ' -', $result->created );
                $question[] = $result->status;
                $question[] = $result->question;
                $wp_filesystem->fputcsv( $handle, $question );
            }
            $wp_filesystem->fclose( $handle );
            $email_data = new stdClass();
            $csv_link = $upload_dir['baseurl'] . '/webinarignition/webinar_' . $webinar_data->id . '_questions_' . $lead_id . '.csv';
            $email_data->bodyContent = '';
            $email_data->email_subject = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . get_option( 'webinarignition_email_templates_from_name', get_option( 'blogname' ) ) . ' <' . get_option( 'webinarignition_email_templates_from_email', get_option( 'admin_email' ) ) . '>');
            $email_data->bodyContent = '<p>' . $attendee_name . __( ' has just finished watching your webinar ', 'webinar-ignition' ) . $webinar_data->webinar_desc . '.</p><p>' . __( 'Here is a link to the questions they asked in your recent webinar: ', 'webinar-ignition' );
            $email_data->bodyContent .= '<a href="' . $csv_link . '">' . __( 'Webinar Questions', 'webinar-ignition' ) . '</a></p>';
            $email_data->bodyContent .= '<p>' . __( 'The file is also attached to this email for your convenience.', 'webinar-ignition' ) . '</p>';
            $email_data->footerContent = ( !empty( $webinar_data->show_or_hide_local_qstn_answer_email_footer ) && $webinar_data->show_or_hide_local_qstn_answer_email_footer == 'show' ? $webinar_data->qstn_answer_email_footer : '' );
            $email_data->emailheading = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $email_data->emailpreview = __( 'Questions From Your Webinar', 'webinar-ignition' );
            $attachments = array($wi_dirname . '/webinar_' . $webinar_data->id . '_questions_' . $lead_id . '.csv');
            $email = new WI_Emails();
            $emailBody = $email->webinarignition_build_email( $email_data );
            wp_mail(
                $webinar_data->host_questions_notifications_email,
                $email_data->email_subject,
                $emailBody,
                $headers,
                $attachments
            );
        }
        //end if
    }
    //end if
}

// Delete Campaign
add_action( 'wp_ajax_webinarignition_delete_campaign', 'webinarignition_delete_campaign_callback' );
function webinarignition_delete_campaign_callback() {
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
    global $wpdb;
    $ID = absint( filter_input( INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT ) );
    $getVersion = 'webinarignition';
    $table_db_name = $wpdb->prefix . $getVersion;
    $wpdb->query( $wpdb->prepare( "DELETE FROM {$table_db_name} WHERE id = %d", $ID ) );
    // Also Delete Corresponding Page Post
    $ID = intval( $ID );
    // Assuming $ID is an integer
    // Prepare and execute the query
    $results = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `{$table_db_name}` WHERE id = %d", $ID ), OBJECT );
    if ( isset( $results->postID ) ) {
        wp_delete_post( $results->postID, true );
    }
    delete_option( 'webinarignition_campaign_' . $ID );
    delete_option( 'wi_webinar_post_id_' . $ID );
    $hash_to_id = get_option( 'webinarignition_map_campaign_hash_to_id', array() );
    $id_to_hash = get_option( 'webinarignition_map_campaign_id_to_hash', array() );
    if ( !empty( $id_to_hash[$ID] ) ) {
        $hash = $id_to_hash[$ID];
        unset($id_to_hash[$ID]);
        if ( !empty( $hash_to_id[$hash] ) ) {
            unset($hash_to_id[$hash]);
        }
    }
    update_option( 'webinarignition_map_campaign_hash_to_id', $hash_to_id );
    update_option( 'webinarignition_map_campaign_id_to_hash', $id_to_hash );
    // Remove old webinar warning, if there are no old webinars exists in DB
    $date_before = '2022-03-25';
    $has_old_webinars = WebinarIgnition::instance()->webinarignition_has_webinars_before_date( $date_before );
    if ( !$has_old_webinars ) {
        set_transient( 'wi_has_old_webinars', 0 );
    }
    wp_send_json_success();
    wp_die();
}
