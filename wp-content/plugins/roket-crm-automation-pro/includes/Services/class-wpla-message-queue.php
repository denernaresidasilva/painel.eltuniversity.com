<?php
/**
 * Message Queue — enqueue & process email / WhatsApp messages.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Message_Queue {

    /**
     * Add a message to the queue.
     */
    public static function enqueue( int $contact_id, string $channel, string $recipient, string $subject, string $body, ?string $scheduled_at = null ): int {
        global $wpdb;

        $tracking_id = wp_generate_password( 32, false );

        $wpdb->insert(
            WPLA_Database::table( 'message_queue' ),
            array(
                'contact_id'   => $contact_id,
                'channel'      => in_array( $channel, array( 'email', 'whatsapp' ), true ) ? $channel : 'email',
                'recipient'    => sanitize_text_field( $recipient ),
                'subject'      => sanitize_text_field( $subject ),
                'body'         => wp_kses_post( $body ),
                'status'       => 'pending',
                'scheduled_at' => $scheduled_at ?: current_time( 'mysql' ),
                'tracking_id'  => $tracking_id,
            ),
            array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
        );

        return (int) $wpdb->insert_id;
    }

    /**
     * Process pending messages — called by WP Cron.
     */
    public static function process(): void {
        global $wpdb;
        $table = WPLA_Database::table( 'message_queue' );

        $messages = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table WHERE status = 'pending' AND scheduled_at <= %s ORDER BY scheduled_at ASC LIMIT 20",
            current_time( 'mysql' )
        ) );

        foreach ( $messages as $msg ) {
            // Mark as processing.
            $wpdb->update( $table, array( 'status' => 'processing', 'attempts' => $msg->attempts + 1 ), array( 'id' => $msg->id ) );

            $success = false;

            if ( 'email' === $msg->channel ) {
                $success = WPLA_Email::send( $msg );
            } elseif ( 'whatsapp' === $msg->channel ) {
                $success = WPLA_WhatsApp::send( $msg );
            }

            if ( $success ) {
                $wpdb->update( $table, array(
                    'status'  => 'sent',
                    'sent_at' => current_time( 'mysql' ),
                ), array( 'id' => $msg->id ) );
            } else {
                $new_status = $msg->attempts >= 3 ? 'failed' : 'pending';
                $wpdb->update( $table, array(
                    'status'        => $new_status,
                    'error_message' => 'Send failed at attempt ' . ( $msg->attempts + 1 ),
                ), array( 'id' => $msg->id ) );
            }
        }
    }

    /**
     * Get queue stats.
     */
    public static function stats(): array {
        global $wpdb;
        $table = WPLA_Database::table( 'message_queue' );

        $results = $wpdb->get_results( "SELECT status, COUNT(*) as cnt FROM $table GROUP BY status" );

        $stats = array( 'pending' => 0, 'processing' => 0, 'sent' => 0, 'failed' => 0 );
        foreach ( $results as $row ) {
            $stats[ $row->status ] = (int) $row->cnt;
        }
        return $stats;
    }

    /**
     * Get recent messages.
     */
    public static function get_recent( string $channel = '', int $limit = 50 ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'message_queue' );

        if ( $channel ) {
            return $wpdb->get_results( $wpdb->prepare(
                "SELECT mq.*, c.email, c.first_name, c.last_name
                 FROM $table mq
                 LEFT JOIN " . WPLA_Database::table( 'contacts' ) . " c ON c.id = mq.contact_id
                 WHERE mq.channel = %s
                 ORDER BY mq.created_at DESC LIMIT %d",
                $channel, $limit
            ) );
        }

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT mq.*, c.email, c.first_name, c.last_name
             FROM $table mq
             LEFT JOIN " . WPLA_Database::table( 'contacts' ) . " c ON c.id = mq.contact_id
             ORDER BY mq.created_at DESC LIMIT %d",
            $limit
        ) );
    }
}
