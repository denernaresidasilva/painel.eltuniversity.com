<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class WebinarignitionQA {
	private static $table_name = 'webinarignition_questions';

	public static function webinarignition_get_table() {
		global $wpdb;

		$table_db_name = $wpdb->prefix . 'webinarignition_questions';


		if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_db_name ) ) ) !== $table_db_name ) {
			$table_db_name = $wpdb->prefix . 'webinarignition_questions_new';
		}

		return $table_db_name;
	}

	public static function webinarignition_get_chat_messages( $app_id, $email = null, $status = null, $where = '' ) {
		global $wpdb;
		$table = self::webinarignition_get_table();

		// Start with the basic query
		$sql = "";

		// Array to hold the values to prepare
		$query_values = [$app_id];

		// Check if $email is provided and not empty
		if (!empty($email)) {
			$query_values[] = $email;
			if (!empty($where)) {
				$chat_messages = $wpdb->get_results($wpdb->prepare("SELECT ID, name, email, question, status, created, type, parent_id, answer_text, attr3 FROM {$table} WHERE app_id = %d {$where} AND email = %s ORDER BY ID ASC", $query_values), ARRAY_A);// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			}else{
				$chat_messages = $wpdb->get_results($wpdb->prepare("SELECT ID, name, email, question, status, created, type, parent_id, answer_text, attr3 FROM {$table} WHERE app_id = %d AND email = %s ORDER BY ID ASC", $query_values), ARRAY_A);// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			}
		}else{
			if (!empty($where)) {
				$chat_messages = $wpdb->get_results($wpdb->prepare("SELECT ID, name, email, question, status, created, type, parent_id, answer_text, attr3 FROM {$table} WHERE app_id = %d {$where} ORDER BY ID ASC", $query_values), ARRAY_A);// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			}else{
				$chat_messages = $wpdb->get_results($wpdb->prepare("SELECT ID, name, email, question, status, created, type, parent_id, answer_text, attr3 FROM {$table} WHERE app_id = %d ORDER BY ID ASC", $query_values), ARRAY_A);// phpcs:ignore WordPress.DB.DirectDatabaseQuery
			}
		}
		if ( empty( $chat_messages ) ) {
			return array();
		}

		foreach ( $chat_messages as $i => $chat_message ) {
			$type = 'outgoing';

			if ( ! empty( $chat_message['type'] ) && 'answer' === $chat_message['type'] && ! empty( $chat_message['answer_text'] ) ) {
				$type = 'incoming';
				$chat_messages[ $i ]['question'] = $chat_message['answer_text'];

				if ( ! empty( $chat_message['attr3'] ) ) {
					$author = $chat_message['attr3'];
				} else {
					$author = __( 'Webinar Support', 'webinar-ignition' );
				}

				$chat_messages[ $i ]['author'] = $author;
			}

			$chat_messages[ $i ]['question'] = wpautop( $chat_messages[ $i ]['question'] );
			$chat_messages[ $i ]['question'] = strip_tags( $chat_messages[ $i ]['question'], '<h1><h2><h3><h4><h5><h6><p><a><span><ul><ol><li><br><strong><b>' );

			$chat_messages[ $i ]['type'] = $type;
		}//end foreach

		return $chat_messages;
	}

	public static function webinarigntion_get_question( $id ) {
		global $wpdb;
		$table = self::webinarignition_get_table();
		return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$table} WHERE ID = %d", $id ), ARRAY_A );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	public static function webinarignition_get_question_answers( $id ) {
		global $wpdb;
		$table = self::webinarignition_get_table();
		return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM `$table` WHERE parent_id = %d", $id ), ARRAY_A );// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	public static function webinarignition_create_question( $data ) {
		global $wpdb;
		$table = self::webinarignition_get_table();

		if ( ! empty( $data['ID'] ) ) {
			$ID = $data['ID'];
			unset( $data['ID'] );
			$wpdb->update( 
				$table, 
				$data, 
				array( 'ID' => $ID ),
				array_fill( 0, count( $data ), '%s' ),
				array( '%d' )
			);
		} else {
			$wpdb->insert( 
				$table, 
				$data,
				array_fill(0, count($data), '%s')
			);
			$ID = $wpdb->insert_id;
		}

		return $ID;
	}

	public static function webinarignition_delete_answers( $question_id ) {
		$answers = self::webinarignition_get_question_answers( $question_id );

		if ( ! empty( $answers ) ) {
			foreach ( $answers as $answer ) {
				$data = array(
					'ID' => $answer['ID'],
					'status' => 'deleted',
				);

				self::webinarignition_create_question( $data );
			}
		}

		return true;
	}
}
