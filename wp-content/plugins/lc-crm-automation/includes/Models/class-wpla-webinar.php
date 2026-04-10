<?php
/**
 * Webinar model — CRUD + auto-list creation + INBOX routing.
 *
 * Tipos de lista criadas por webinar:
 *   - inbox            : entrada (sempre mantida vazia após roteamento)
 *   - assistiu_oferta  : contatos que viram a oferta ao vivo
 *   - nao_viu_oferta   : contatos que não viram a oferta ao vivo
 *   - replay           : contatos encaminhados para replay
 *   - converteu        : contatos que clicaram na oferta
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Webinar {

    /** Sub-tipos de lista gerados automaticamente por webinar. */
    const LIST_TYPES = array(
        'inbox'           => 'Inbox',
        'assistiu_oferta' => 'Assistiu Oferta',
        'nao_viu_oferta'  => 'Não Viu Oferta',
        'replay'          => 'Replay',
        'converteu'       => 'Converteu',
    );

    /* ───────────────────────────────────
     * CRUD
     * ─────────────────────────────────── */

    /**
     * Create a new webinar and auto-generate its lists + a dedicated automation.
     *
     * @param array $data Webinar data.
     * @return int|false New webinar ID or false on failure.
     */
    public static function create( array $data ) {
        global $wpdb;
        $table = WPLA_Database::table( 'webinars' );

        $row = self::sanitize( $data );

        $wpdb->insert( $table, $row, array( '%s','%s','%s','%s','%s','%s','%s','%d','%d','%d','%s' ) );
        $webinar_id = (int) $wpdb->insert_id;

        if ( ! $webinar_id ) {
            return false;
        }

        // Create associated lists.
        self::create_lists( $webinar_id, $row['name'] );

        // Create dedicated automation for this webinar.
        $automation_id = WPLA_Automation_Engine::save_automation( array(
            'name'         => sprintf( __( 'Automação — %s', 'lc-crm' ), $row['name'] ),
            'description'  => sprintf( __( 'Automação dedicada do webinar "%s". Gerada automaticamente.', 'lc-crm' ), $row['name'] ),
            'trigger_type' => 'webinar_inbox',
            'trigger_config' => array( 'webinar_id' => $webinar_id ),
            'status'       => 'draft',
        ) );

        if ( $automation_id ) {
            $wpdb->update( $table, array( 'automation_id' => $automation_id ), array( 'id' => $webinar_id ), array( '%d' ), array( '%d' ) );
        }

        do_action( 'wpla_webinar_created', $webinar_id );

        return $webinar_id;
    }

    /**
     * Update a webinar.
     */
    public static function update( int $id, array $data ): bool {
        global $wpdb;

        $row = self::sanitize( $data );
        // Remove automation_id from update (managed separately).
        unset( $row['automation_id'] );

        $result = $wpdb->update(
            WPLA_Database::table( 'webinars' ),
            $row,
            array( 'id' => $id ),
            null,
            array( '%d' )
        );

        return $result !== false;
    }

    /**
     * Get a single webinar by ID.
     */
    public static function get( int $id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            'SELECT * FROM ' . WPLA_Database::table( 'webinars' ) . ' WHERE id = %d',
            $id
        ) ) ?: null;
    }

    /**
     * Get all webinars.
     */
    public static function get_all( string $status = '' ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'webinars' );

        if ( $status ) {
            return $wpdb->get_results( $wpdb->prepare(
                "SELECT * FROM $table WHERE status = %s ORDER BY created_at DESC",
                $status
            ) );
        }

        return $wpdb->get_results( "SELECT * FROM $table ORDER BY created_at DESC" );
    }

    /**
     * Delete a webinar (and its associated lists/automation).
     */
    public static function delete( int $id ): bool {
        global $wpdb;

        $webinar = self::get( $id );
        if ( ! $webinar ) {
            return false;
        }

        // Delete dedicated automation.
        if ( $webinar->automation_id ) {
            WPLA_Automation_Engine::delete_automation( (int) $webinar->automation_id );
        }

        // Remove webinar lists.
        $lists_table = WPLA_Database::table( 'lists' );
        $lists       = $wpdb->get_results( $wpdb->prepare(
            "SELECT id FROM $lists_table WHERE webinar_id = %d",
            $id
        ) );
        foreach ( $lists as $list ) {
            WPLA_List_Model::delete( (int) $list->id );
        }

        return (bool) $wpdb->delete( WPLA_Database::table( 'webinars' ), array( 'id' => $id ), array( '%d' ) );
    }

    /**
     * Count webinars.
     */
    public static function count( string $status = '' ): int {
        global $wpdb;
        $table = WPLA_Database::table( 'webinars' );

        if ( $status ) {
            return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
        }

        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
    }

    /* ───────────────────────────────────
     * List helpers
     * ─────────────────────────────────── */

    /**
     * Create the standard lists for a webinar.
     */
    private static function create_lists( int $webinar_id, string $webinar_name ): void {
        foreach ( self::LIST_TYPES as $type => $label ) {
            WPLA_List_Model::create( array(
                'name'       => "{$webinar_name} — {$label}",
                'description' => sprintf( __( 'Lista %s do webinar "%s". Criada automaticamente.', 'lc-crm' ), $label, $webinar_name ),
                'list_type'  => $type,
                'webinar_id' => $webinar_id,
            ) );
        }
    }

    /**
     * Get all lists belonging to a webinar, keyed by list_type.
     */
    public static function get_lists( int $webinar_id ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'lists' );

        $rows   = $wpdb->get_results( $wpdb->prepare(
            "SELECT * FROM $table WHERE webinar_id = %d ORDER BY id ASC",
            $webinar_id
        ) );
        $result = array();
        foreach ( $rows as $row ) {
            $result[ $row->list_type ] = $row;
        }

        return $result;
    }

    /**
     * Get the INBOX list ID for a webinar.
     */
    public static function get_inbox_list_id( int $webinar_id ): int {
        global $wpdb;
        $table = WPLA_Database::table( 'lists' );

        return (int) $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM $table WHERE webinar_id = %d AND list_type = 'inbox' LIMIT 1",
            $webinar_id
        ) );
    }

    /* ───────────────────────────────────
     * Contact routing (INBOX always-empty)
     * ─────────────────────────────────── */

    /**
     * Add a contact to the webinar INBOX and fire the webinar trigger.
     */
    public static function enter_inbox( int $webinar_id, int $contact_id ): void {
        $inbox_id = self::get_inbox_list_id( $webinar_id );
        if ( ! $inbox_id ) {
            return;
        }

        WPLA_Contact::subscribe_list( $contact_id, $inbox_id );

        do_action( 'wpla_event', 'webinar_inbox', $contact_id, array( 'webinar_id' => $webinar_id ) );
    }

    /**
     * Route a contact from INBOX to a specific derived list.
     * Removes from INBOX automatically (keeping it empty).
     *
     * @param int    $webinar_id  Webinar ID.
     * @param int    $contact_id  Contact ID.
     * @param string $destination One of the LIST_TYPES keys (e.g. 'assistiu_oferta').
     */
    public static function route_contact( int $webinar_id, int $contact_id, string $destination ): void {
        global $wpdb;
        $table = WPLA_Database::table( 'lists' );

        // Get the destination list.
        $dest_list = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE webinar_id = %d AND list_type = %s LIMIT 1",
            $webinar_id, $destination
        ) );

        if ( ! $dest_list ) {
            return;
        }

        // Subscribe to destination.
        WPLA_Contact::subscribe_list( $contact_id, (int) $dest_list->id );

        // Remove from INBOX (keep it empty).
        $inbox_id = self::get_inbox_list_id( $webinar_id );
        if ( $inbox_id ) {
            WPLA_Contact::unsubscribe_list( $contact_id, $inbox_id );
        }

        // Fire routed event.
        do_action( 'wpla_event', "webinar_{$destination}", $contact_id, array(
            'webinar_id'  => $webinar_id,
            'list_id'     => (int) $dest_list->id,
            'destination' => $destination,
        ) );
    }

    /* ───────────────────────────────────
     * Private helpers
     * ─────────────────────────────────── */

    private static function sanitize( array $data ): array {
        return array(
            'name'                => sanitize_text_field( $data['name'] ?? '' ),
            'description'         => sanitize_textarea_field( $data['description'] ?? '' ),
            'video_type'          => in_array( $data['video_type'] ?? '', array( 'youtube', 'vimeo', 'html5' ), true )
                                        ? $data['video_type']
                                        : 'youtube',
            'video_url'           => esc_url_raw( $data['video_url'] ?? '' ),
            'offer_title'         => sanitize_text_field( $data['offer_title'] ?? '' ),
            'offer_url'           => esc_url_raw( $data['offer_url'] ?? '' ),
            'offer_button_text'   => sanitize_text_field( $data['offer_button_text'] ?? '' ),
            'offer_time_live'     => absint( $data['offer_time_live'] ?? 0 ),
            'offer_time_replay'   => absint( $data['offer_time_replay'] ?? 0 ),
            'automation_id'       => absint( $data['automation_id'] ?? 0 ),
            'status'              => in_array( $data['status'] ?? '', array( 'active', 'paused', 'draft' ), true )
                                        ? $data['status']
                                        : 'draft',
        );
    }
}
