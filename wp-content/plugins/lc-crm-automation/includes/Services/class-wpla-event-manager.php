<?php
/**
 * Event Manager — central hub for all CRM events.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Event_Manager {

    /** @var self|null */
    private static $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action( 'wpla_event', array( $this, 'handle' ), 10, 3 );
    }

    /**
     * Central event handler — logs the event and triggers automations.
     *
     * @param string $event_type  One of the defined event types.
     * @param int    $contact_id  Contact ID.
     * @param mixed  $data        Additional event data.
     */
    public function handle( string $event_type, int $contact_id, $data = array() ): void {
        // 1. Log the event.
        $this->log_event( $event_type, $contact_id, $data );

        // 2. Update lead score.
        WPLA_Lead_Scoring::on_event( $event_type, $contact_id, $data );

        // 3. Trigger matching automations.
        WPLA_Automation_Engine::trigger( $event_type, $contact_id, $data );
    }

    /**
     * Store event in the events table.
     */
    private function log_event( string $event_type, int $contact_id, $data ): void {
        global $wpdb;

        $wpdb->insert(
            WPLA_Database::table( 'events' ),
            array(
                'event_type' => sanitize_text_field( $event_type ),
                'contact_id' => $contact_id,
                'data'       => is_array( $data ) || is_object( $data ) ? wp_json_encode( $data ) : $data,
            ),
            array( '%s', '%d', '%s' )
        );
    }

    /**
     * Get recent events.
     */
    public static function get_recent( int $limit = 50 ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'events' );

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT e.*, c.email, c.first_name, c.last_name
             FROM $table e
             LEFT JOIN " . WPLA_Database::table( 'contacts' ) . " c ON c.id = e.contact_id
             ORDER BY e.created_at DESC
             LIMIT %d",
            $limit
        ) );
    }

    /**
     * Count events by type in a date range.
     */
    public static function count_by_type( string $event_type, string $since = '' ): int {
        global $wpdb;
        $table = WPLA_Database::table( 'events' );

        if ( $since ) {
            return (int) $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE event_type = %s AND created_at >= %s",
                $event_type, $since
            ) );
        }

        return (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE event_type = %s",
            $event_type
        ) );
    }

    /**
     * Count total events.
     */
    public static function count_total(): int {
        global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . WPLA_Database::table( 'events' ) );
    }
}
