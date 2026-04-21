<?php
/**
 * Email Template model — CRUD for email_templates table.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PLS_Email_Template {

    /**
     * Create a new template.
     *
     * @param array $data name, subject, body, status.
     * @return int New template ID.
     */
    public static function create( array $data ): int {
        global $wpdb;

        $wpdb->insert(
            PLS_Database::table( 'email_templates' ),
            array(
                'name'    => sanitize_text_field( $data['name'] ?? '' ),
                'subject' => sanitize_text_field( $data['subject'] ?? '' ),
                'body'    => wp_kses_post( $data['body'] ?? '' ),
                'status'  => in_array( $data['status'] ?? '', array( 'active', 'draft' ), true ) ? $data['status'] : 'draft',
            ),
            array( '%s', '%s', '%s', '%s' )
        );

        return (int) $wpdb->insert_id;
    }

    /**
     * Retrieve a single template by ID.
     */
    public static function get( int $id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            'SELECT * FROM ' . PLS_Database::table( 'email_templates' ) . ' WHERE id = %d', $id
        ) ) ?: null;
    }

    /**
     * Update an existing template.
     */
    public static function update( int $id, array $data ): bool {
        global $wpdb;

        $update = array();
        $format = array();

        if ( isset( $data['name'] ) ) {
            $update['name'] = sanitize_text_field( $data['name'] );
            $format[]       = '%s';
        }
        if ( isset( $data['subject'] ) ) {
            $update['subject'] = sanitize_text_field( $data['subject'] );
            $format[]          = '%s';
        }
        if ( isset( $data['body'] ) ) {
            $update['body'] = wp_kses_post( $data['body'] );
            $format[]       = '%s';
        }
        if ( isset( $data['status'] ) ) {
            $update['status'] = in_array( $data['status'], array( 'active', 'draft' ), true ) ? $data['status'] : 'draft';
            $format[]         = '%s';
        }

        if ( empty( $update ) ) {
            return false;
        }

        return false !== $wpdb->update(
            PLS_Database::table( 'email_templates' ),
            $update,
            array( 'id' => $id ),
            $format,
            array( '%d' )
        );
    }

    /**
     * Delete a template.
     */
    public static function delete( int $id ): bool {
        global $wpdb;
        return (bool) $wpdb->delete( PLS_Database::table( 'email_templates' ), array( 'id' => $id ), array( '%d' ) );
    }

    /**
     * Get all templates.
     *
     * @param string $status  Filter by status ('active', 'draft', or '' for all).
     */
    public static function all( string $status = '' ): array {
        global $wpdb;
        $table = PLS_Database::table( 'email_templates' );

        if ( $status ) {
            return $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM $table WHERE status = %s ORDER BY name ASC",
                $status
            ) );
        }

        return $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC" );
    }

    /**
     * Count templates.
     */
    public static function count( string $status = '' ): int {
        global $wpdb;
        $table = PLS_Database::table( 'email_templates' );

        if ( $status ) {
            return (int) $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table WHERE status = %s", $status
            ) );
        }

        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
    }

    /**
     * Render the template body by replacing merge-field variables.
     *
     * Supported variables:
     *   {{first_name}}, {{last_name}}, {{name}}, {{email}}, {{phone}},
     *   {{company}}, {{unsubscribe_url}}, and any custom field key.
     *
     * @param string      $body     Raw template body (HTML).
     * @param object|null $contact  Contact row from DB (may include custom_fields JSON).
     * @return string Rendered HTML.
     */
    public static function render( string $body, ?object $contact ): string {
        if ( ! $contact ) {
            return $body;
        }

        $full_name = trim( ( $contact->first_name ?? '' ) . ' ' . ( $contact->last_name ?? '' ) );

        $unsubscribe_url = add_query_arg( array(
            'pls_unsub' => '1',
            'email'      => rawurlencode( $contact->email ?? '' ),
            'token'      => self::unsubscribe_token( $contact->email ?? '' ),
        ), home_url( '/' ) );

        $replacements = array(
            '{{first_name}}'     => $contact->first_name ?? '',
            '{{last_name}}'      => $contact->last_name ?? '',
            '{{name}}'           => $full_name,
            '{{email}}'          => $contact->email ?? '',
            '{{phone}}'          => $contact->phone ?? '',
            '{{company}}'        => $contact->company ?? '',
            '{{unsubscribe_url}}' => esc_url( $unsubscribe_url ),
        );

        // Merge custom fields.
        if ( ! empty( $contact->custom_fields ) ) {
            $custom = json_decode( $contact->custom_fields, true );
            if ( is_array( $custom ) ) {
                foreach ( $custom as $key => $val ) {
                    $replacements[ '{{' . sanitize_key( $key ) . '}}' ] = esc_html( (string) $val );
                }
            }
        }

        return str_replace( array_keys( $replacements ), array_values( $replacements ), $body );
    }

    /**
     * Generate a signed unsubscribe token for an email address.
     */
    public static function unsubscribe_token( string $email ): string {
        return substr( hash_hmac( 'sha256', strtolower( trim( $email ) ), wp_salt( 'auth' ) ), 0, 32 );
    }

    /**
     * Validate an unsubscribe token.
     */
    public static function verify_unsubscribe_token( string $email, string $token ): bool {
        return hash_equals( self::unsubscribe_token( $email ), $token );
    }
}
