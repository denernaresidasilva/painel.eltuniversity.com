<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_ajax_webinarignition_get_webinar', 'webinarignition_get_webinar' );

function webinarignition_get_webinar() {

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
		wp_send_json_error( [ 'message' => 'Invalid security token' ], 403 );
		exit;
	}
	$webinar_id = sanitize_text_field( filter_input( INPUT_POST, 'id' ) );
	$webinar = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

	if ( isset( $webinar->air_html ) ) {
		$webinar->air_html = stripcslashes( $webinar->air_html );
	}

	if ( isset( $webinar->air_btn_copy ) ) {
		$webinar->air_btn_copy = stripcslashes( $webinar->air_btn_copy );
	}

	if ( isset( $webinar->air_tab_copy ) ) {
		$webinar->air_tab_copy = stripcslashes( $webinar->air_tab_copy );
	}

	if ( isset( $webinar->air_btn_url ) ) {
		$webinar->air_btn_url = stripcslashes( $webinar->air_btn_url );
	}

	wp_send_json_success(array(
		'webinar' => $webinar,
	));
	wp_die();

}


add_action( 'wp_ajax_webinarignition_save_on_air_settings', 'webinarignition_save_on_air_settings' );
function webinarignition_save_on_air_settings() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => 'Insufficient permissions' ], 403 );
		wp_die();
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
		wp_send_json_error( [ 'message' => 'Invalid security token' ], 403 );
		exit;
	}

	if ( ! isset( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) ) {
		wp_send_json_error( array( 'error' => 'Invalid Webinar ID' ) );
		wp_die();

	}

	$id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : null;
	$optionName = 'webinarignition_campaign_' . $id;
	$option = WebinarignitionManager::webinarignition_get_webinar_data( $id );

	$onairStatus = sanitize_text_field( wp_unslash( $_POST['onair_status'] ) );
	if ( ! in_array( $onairStatus, array( 'on', 'off' ), true ) ) {
		wp_send_json_error( array( __( 'on air message could not be toggled', 'webinar-ignition' ) ) );
		wp_die();

	}

	$option->air_toggle = $onairStatus;

	if ( isset( $_POST['air_html'] ) ) {
		$option->air_html = wp_unslash( $_POST['air_html']) ;
	}

	$option->air_btn_copy   = sanitize_text_field( wp_unslash( $_POST['air_btn_copy'] ) );

	$option->air_tab_copy   = sanitize_text_field( wp_unslash( $_POST['air_tab_copy'] ) );

	$option->air_btn_url    = sanitize_text_field( wp_unslash( $_POST['air_btn_url'] ) );

	$option->air_btn_color  = sanitize_text_field( wp_unslash( $_POST['air_btn_color'] ) );

	update_option( $optionName, $option );

	wp_send_json_success();
	wp_die();

}


add_action( 'wp_ajax_webinarignition_toggle_on_air_message', 'webinarignition_toggle_on_air_message' );
function webinarignition_toggle_on_air_message() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => 'Insufficient permissions' ], 403 );
		wp_die();
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
		wp_send_json_error( [ 'message' => 'Invalid security token' ], 403 );
		exit;
	}

	if ( ! isset( $_POST['id'] ) || ! is_numeric( $_POST['id'] ) ) {
		wp_send_json_error( array( 'error' => __( 'Invalid Webinar ID', 'webinar-ignition' ) ) );
		wp_die();
	}

	$webinar_id = sanitize_text_field( wp_unslash( $_POST['id'] ) );
	$optionName = 'webinarignition_campaign_' . $webinar_id;
	$option = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

	if ( ! isset( $_POST['onair_status'] ) ) {
		wp_send_json_error( array( __( 'on air status is required', 'webinar-ignition' ) ) );
		wp_die();
	}

	$onairStatus = sanitize_text_field( wp_unslash( $_POST['onair_status'] ) );
	if ( ! in_array( $onairStatus, array( 'on', 'off' ), true ) ) {
		wp_send_json_error( array( __( 'on air message could not be toggled', 'webinar-ignition' ) ) );
		wp_die();
	}

	$option->air_toggle = $onairStatus;
	update_option( $optionName, $option );

	wp_send_json_success();
	wp_die();
}

add_action( 'wp_ajax_webinarignition_get_leads', 'webinarignition_get_leads' );

function webinarignition_get_leads() {

	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( [ 'message' => 'Insufficient permissions' ], 403 );
		wp_die();
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['security'] ?? '' ) ), 'webinarignition_ajax_nonce' ) ) {
		wp_send_json_error( [ 'message' => 'Invalid security token' ], 403 );
		exit;
	}

	global $wpdb;

	// Only get the required values from INPUT_POST
	$webinar_id = isset( $_POST['id'] ) ? sanitize_text_field( wp_unslash( $_POST['id'] ) ) : null;
	$webinar_type = isset( $_POST['webinar_type'] ) ? sanitize_text_field( wp_unslash( $_POST['webinar_type'] ) ) : null;
	$search_for = isset( $_POST['search_for'] ) ? sanitize_text_field( wp_unslash( $_POST['search_for'] ) ) : null;
	$limit = isset( $_POST['limit'] ) ? sanitize_text_field( wp_unslash( $_POST['limit'] ) ) : null;
	$offset = isset( $_POST['offset'] ) ? sanitize_text_field( wp_unslash( $_POST['offset'] ) ) : null;

	if ( ! isset( $webinar_id ) || ! is_numeric( $webinar_id ) ) {
		wp_send_json_error( array( 'error' => __( 'Invalid Webinar ID', 'webinar-ignition' ) ) );
	}

	if ( ! isset( $webinar_type ) || ! in_array( $webinar_type, array( 'evergreen', 'live' ), true ) ) {
		wp_send_json_error( array( 'error' => __( 'Invalid Webinar Type', 'webinar-ignition' ) ) );
	}

	$table_db_name = 'evergreen' === $webinar_type ? $wpdb->prefix . 'webinarignition_leads_evergreen' : $wpdb->prefix . 'webinarignition_leads';

	if ( ! empty( $search_for ) ) {
		if ( ! empty( $search_for ) ) {
			$leads = $wpdb->get_results( $wpdb->prepare( " SELECT * FROM {$table_db_name} WHERE app_id = %d  AND ( `name` LIKE %s OR `email` LIKE  %s ) LIMIT %d OFFSET %d",
				$webinar_id,
				'%%' . $wpdb->esc_like( $search_for ) . '%%',
				'%%' . $wpdb->esc_like( $search_for ) . '%%',
				$limit,
				$offset
			), OBJECT );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}else{
			$leads = $wpdb->get_results($wpdb->prepare( "SELECT *	FROM {$table_db_name} WHERE app_id = %d LIMIT %d OFFSET %d" ,
				$webinar_id,
				'%%' . $wpdb->esc_like( $search_for ) . '%%',
				'%%' . $wpdb->esc_like( $search_for ) . '%%',
				$limit,
				$offset
			), OBJECT );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}

		$totalQueryLeads = $wpdb->get_var(
			$wpdb->prepare(
				"
                  SELECT COUNT(*)
                  FROM {$table_db_name}
                  WHERE app_id = %d
                  AND `email` LIKE %s
                ",
				$webinar_id,
				'%%' . $wpdb->esc_like( $search_for ) . '%%'
			)
		);

	} else {
		if ( ! empty( $search_for ) ) {
			$leads = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %d  AND ( `name` LIKE %s OR `email` LIKE  %s ) LIMIT %d OFFSET %d",
				$webinar_id,
				$limit,
				$offset
			), OBJECT );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}else{
			$leads = $wpdb->get_results( $wpdb->prepare("SELECT * FROM {$table_db_name} WHERE app_id = %d LIMIT %d OFFSET %d",
				$webinar_id,
				$limit,
				$offset
			), OBJECT );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
		}
	}//end if


	// Sanitize input values
	$app_id = intval($webinar_id); // Assuming $webinar_id is an integer

	// Prepare and execute queries
	$totalLeads = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d",
			$app_id
		)
	);

	$totalQueryLeads = isset($totalQueryLeads) ? $totalQueryLeads : $totalLeads;

	$totalAttendedEvent = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d AND `event` = %s",
			$app_id,
			'Yes'
		)
	);

	$totalAttendedReplay = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d AND `replay` = %s",
			$app_id,
			'Yes'
		)
	);

	$totalOrdered = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d AND `trk2` = %s",
			$app_id,
			'Yes'
		)
	);
	$data = array(
		'leads'                     => $leads,
		'total_leads'               => $totalLeads,
		'total_query_leads'         => $totalQueryLeads,
		'total_attended_event'      => $totalAttendedEvent,
		'total_attended_replay'     => $totalAttendedReplay,
		'total_ordered'             => $totalOrdered,
		'number_of_pages'           => ceil( $totalQueryLeads / $limit ),
	);

	wp_send_json_success( $data );
}


add_action( 'wp_ajax_webinarignition_get_questions', 'webinarignition_get_questions' );
add_action( 'wp_ajax_nopriv_webinarignition_get_questions', 'webinarignition_get_questions');

function webinarignition_get_questions() {

	check_ajax_referer( 'webinarignition_ajax_nonce', 'security', false );

	global $wpdb;

	// Only get the required values from INPUT_POST
	$webinar_id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : null;
	$search_for = isset($_POST['search_for']) ? sanitize_text_field($_POST['search_for']) : null;
	$limit = isset($_POST['limit']) ? intval($_POST['limit']) : null;
	$offset = isset($_POST['offset']) ? intval($_POST['offset']) : null;

	if ( ! isset( $webinar_id ) || ! is_numeric( $webinar_id ) ) {
		wp_send_json_error( array( 'error' => 'Invalid Webinar ID' ) );
	}

	$webinar_data = WebinarignitionManager::webinarignition_get_webinar_data( $webinar_id );

	$table_db_name = $wpdb->prefix . 'webinarignition_questions';

	// check if table exists

	if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_db_name ) ) ) !== $table_db_name ) {
		$table_db_name = $wpdb->prefix . 'webinarignition_questions_new';
	}

	// Prepare and execute the query
	$totalQuestions = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d",
			intval($webinar_id)
		)
	);
	
	if ( ! empty( $search_for ) ) {
		
		if ( ! empty( $search_for ) ) {
			$questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %d  AND `email` LIKE %s LIMIT %d OFFSET %d",
				$webinar_id,
				'%%' . $wpdb->esc_like( $search_for ) . '%%',
				$limit,
				$offset
			), OBJECT_K );

		}else{
			$questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %d LIMIT %d OFFSET %d",
				$webinar_id,
				'%%' . $wpdb->esc_like( $search_for ) . '%%',
				$limit,
				$offset
			), OBJECT_K );
		}
		$totalQueryQuestions = $wpdb->get_var(
			$wpdb->prepare(
				"
                  SELECT COUNT(*)
                  FROM {$table_db_name}
                  WHERE app_id = %d
                  AND `email` LIKE %s
                ",
				$webinar_id,
				'%%' . $wpdb->esc_like( $search_for ) . '%%'
			)
		);
	} else {
		if ( ! empty( $search_for ) ) {
			$questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %d  AND `email` LIKE %s ",
				$webinar_id,
				$limit,
				$offset
			), OBJECT_K );
		}else{
			$questions = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table_db_name} WHERE app_id = %d LIMIT %d OFFSET %d",
				$webinar_id,
				$limit,
				$offset
			), OBJECT_K );
		}
	}//end if

	$questions              = is_array( $questions ) ? array_reverse( $questions ) : $questions;
	$active_questions       = '';
	$current_user_id        = get_current_user_id();

	foreach ( $questions as $questionsActive ) {

		if ( 'live' === $questionsActive->status ) {

			$deleteQuestionHtml = empty( $_POST['is_support'] ) ? "<div class='questionBlockIcons qbi-remove' qaID='" . $questionsActive->ID . "'><i class='icon-remove icon-large' data-toggle='tooltip' data-placement='top' title='Delete question'></i></div>" : '';
			$answerAttendeeHtml = ( empty( $questionsActive->attr4 ) || $questionsActive->attr2 == $current_user_id ) ? "<div class='questionBlockIcons qbi-reply'><a class='answerAttendee' data-toggle='tooltip' data-placement='top' title='" . __( 'Respond to attendee question', 'webinar-ignition' ) . "'data-questionid=" . $questionsActive->ID . ' data-attendee-email=' . $questionsActive->email . ' data-attendee-name=' . $questionsActive->name . "><i class='icon-comments icon-large'></i></a></div>" : '';
			$message            = ( 'hold' === $questionsActive->attr4 && ! empty( $questionsActive->attr5 ) && $questionsActive->attr2 === $current_user_id ) ? __( "You're answering this question...", 'webinar-ignition' ) : $questionsActive->attr5 . ' ' . __( 'is answering this question...', 'webinar-ignition' );
			$questionOnHoldHtml = ( 'hold' === $questionsActive->attr4 && ! empty( $questionsActive->attr5 ) ) ? '<span class="questionOnHold green bold"> ' . $message . '</span>' : '';

			$active_questions .= "<!-- QUESTION BLOCK -->
                                        <div class='questionBlockWrapper questionBlockWrapperActive' qa_lead='" . $questionsActive->ID . "' id='QA-BLOCK-" . $questionsActive->ID . "' >
                                                <div class='questionBlockQuestion'>
                                                <span class='questionTimestamp'> " . $questionsActive->created . " </span>
                                                    <p style='padding: 10px; background-color: #eee; width: 100%;border-radius: 7px;'>
                                                        <span class='questionBlockText' >" . $questionsActive->question . "</span>
                                                        <span class='questionBlockAuthor' >
                                                            " . $questionsActive->name . " - 
                                                            <span data-toggle='tooltip' data-placement='top' title='Search leads table' class='radius secondary label qa-lead-search'>" . $questionsActive->email . '
                                                            </span>
                                                        </span>
                                                    </p>
                                                            ' . $questionOnHoldHtml . "   
                                                </div>
                                                <div class='questionActions'>
                                                            " . $deleteQuestionHtml . '
                                                             ' . $answerAttendeeHtml . "   
                                                        <br clear='left' />
                                                </div>
                                                <br clear='all' />
                                        </div>
                    <!-- END OF QUESTION BLOCK -->";

		}//end if
	}//end foreach

	$answered_questions       = '';

	$questionsDone = array();

	foreach ( $questions as $question ) {
		if ( 'done' === $question->status ) {
			$questionsDone[] = $question;
		}
	}

	foreach ( $questionsDone as $question ) {
		$is_support = ! empty( $_POST['is_support'] );
		$questionDone = $question;

		ob_start();
		include WEBINARIGNITION_PATH . 'inc/lp/console/partials/answeredQuestion.php';
		$answered_questions .= ob_get_clean();
	}

	// Prepare and execute queries
	$totalQuestions = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d",
			intval($webinar_id)
		)
	);

	$totalQueryQuestions = isset($totalQueryQuestions) ? $totalQueryQuestions : $totalQuestions;

	$totalActiveQuestions = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d AND `status` = %s",
			intval($webinar_id),
			'live'
		)
	);

	$totalDoneQuestions = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM `{$table_db_name}` WHERE `app_id` = %d AND `status` = %s",
			intval($webinar_id),
			'done'
		)
	);
	$data = array(
		'active_questions'              => $active_questions,
		'answered_questions'            => $answered_questions,
		'total_questions'               => $totalQuestions,
		'total_query_questions'         => $totalQueryQuestions,
		'total_active_questions'        => $totalActiveQuestions,
		'total_done_questions'          => $totalDoneQuestions,
		'number_of_pages'               => ceil( $totalQueryQuestions / $limit ),
	);

	wp_send_json_success( $data );
}

add_action( 'wp_ajax_webinarignition_get_users_online', 'webinarignition_get_users_online' );

function webinarignition_get_users_online() {

	// TODO: Use nonce verification if possible.
	// check_ajax_referer( 'webinarignition_ajax_nonce', 'security' );


	$post_input                 = $_POST;
	$post_input['webinar_id']   = isset( $post_input['webinar_id'] ) ? sanitize_text_field( $post_input['webinar_id'] ) : null;
	$webinar_id = sanitize_text_field( $post_input['webinar_id'] );
	$webinar_type = isset( $post_input['webinar_type'] ) ? sanitize_text_field( $post_input['webinar_type'] ): '' ;

	global $wpdb;
	$table_db_name = $wpdb->prefix . 'webinarignition_users_online';
	// Purge All Who Havent been updated in 1 minute...
	$currentTime = gmdate( 'Y-m-d H:i:s' );
	$currentTime = strtotime( $currentTime );
	$minus5Minutes = gmdate( 'Y-m-d H:i:s', strtotime( '-10 seconds', $currentTime ) );
	// Sanitize input values
	$minus5Minutes = esc_sql($minus5Minutes); // Assuming $minus5Minutes is a properly formatted datetime string
	$webinar_id = intval($webinar_id); // Assuming $webinar_id is an integer

	// Prepare and execute queries
	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM `{$table_db_name}` WHERE dt < %s",
			$minus5Minutes
		)
	);

	$wpdb->query(
		$wpdb->prepare(
			"DELETE FROM `{$table_db_name}` WHERE lead_id IS NULL OR lead_id = 0 OR lead_id = '0'"
		)
	);

	// Count All
	$attendees = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT lead_id FROM `{$table_db_name}` WHERE app_id = %d",
			$webinar_id
		),
		ARRAY_A
	);

	$count = count($attendees);
	$leads = array();

	if ('live' === $webinar_type) {
		$table_db_name = $wpdb->prefix . 'webinarignition_leads';
	} else {
		$table_db_name = $wpdb->prefix . 'webinarignition_leads_evergreen';
	}

	// Escape the table name again for dynamic table names
	$table_db_name = esc_sql($table_db_name);

	$leads = $wpdb->get_results(
		$wpdb->prepare(
			"SELECT ID, email FROM `{$table_db_name}` WHERE app_id = %d",
			$webinar_id
		),
		ARRAY_A
	);

	wp_send_json(wp_json_encode(array(
		'count' => $count,
		'visitors' => $attendees,
		'webinar_id' => $webinar_id,
		'webinar_type' => $webinar_type,
		'leads' => $leads,
	)));
}
