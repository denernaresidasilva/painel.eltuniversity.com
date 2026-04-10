<?php
/**
 * Lead Scoring service.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Lead_Scoring {

    /**
     * Default scoring rules.
     */
    private static function default_rules(): array {
        return array(
            'contact_created'  => 5,
            'form_submitted'   => 10,
            'tag_added'        => 3,
            'list_subscribed'  => 5,
            'email_opened'     => 2,
            'link_clicked'     => 5,
            'webhook_received' => 3,
        );
    }

    /**
     * Process an event and update lead score.
     */
    public static function on_event( string $event_type, int $contact_id, $data = array() ): void {
        $rules  = apply_filters( 'wpla_lead_scoring_rules', self::default_rules() );
        $points = $rules[ $event_type ] ?? 0;

        if ( $points <= 0 ) {
            return;
        }

        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        $wpdb->query( $wpdb->prepare(
            "UPDATE $table SET lead_score = lead_score + %d WHERE id = %d",
            $points, $contact_id
        ) );
    }
}
