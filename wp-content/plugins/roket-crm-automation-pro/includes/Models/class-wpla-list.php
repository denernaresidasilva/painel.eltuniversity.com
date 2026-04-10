<?php
/**
 * List model — CRUD for lists table.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_List_Model {

    public static function create( array $data ): int {
        global $wpdb;
        $table = WPLA_Database::table( 'lists' );

        $form_fields = isset( $data['form_fields'] ) && is_array( $data['form_fields'] )
            ? wp_json_encode( $data['form_fields'] )
            : ( isset( $data['form_fields'] ) ? $data['form_fields'] : null );

        $wpdb->insert( $table, array(
            'name'        => sanitize_text_field( $data['name'] ?? '' ),
            'description' => sanitize_textarea_field( $data['description'] ?? '' ),
            'status'      => 'active',
            'list_type'   => sanitize_text_field( $data['list_type'] ?? 'general' ),
            'webinar_id'  => absint( $data['webinar_id'] ?? 0 ),
            'form_fields' => $form_fields,
        ), array( '%s', '%s', '%s', '%s', '%d', '%s' ) );

        return (int) $wpdb->insert_id;
    }

    public static function get( int $id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            'SELECT * FROM ' . WPLA_Database::table( 'lists' ) . ' WHERE id = %d',
            $id
        ) ) ?: null;
    }

    public static function update( int $id, array $data ): bool {
        global $wpdb;
        $update = array();
        $format = array();

        if ( isset( $data['name'] ) ) {
            $update['name'] = sanitize_text_field( $data['name'] );
            $format[]       = '%s';
        }
        if ( isset( $data['description'] ) ) {
            $update['description'] = sanitize_textarea_field( $data['description'] );
            $format[]              = '%s';
        }
        if ( isset( $data['status'] ) ) {
            $update['status'] = in_array( $data['status'], array( 'active', 'archived' ), true ) ? $data['status'] : 'active';
            $format[]         = '%s';
        }
        if ( isset( $data['form_fields'] ) ) {
            $update['form_fields'] = is_array( $data['form_fields'] ) ? wp_json_encode( $data['form_fields'] ) : $data['form_fields'];
            $format[]              = '%s';
        }

        if ( empty( $update ) ) {
            return false;
        }

        return false !== $wpdb->update( WPLA_Database::table( 'lists' ), $update, array( 'id' => $id ), $format, array( '%d' ) );
    }

    public static function delete( int $id ): bool {
        global $wpdb;
        $wpdb->delete( WPLA_Database::table( 'contact_lists' ), array( 'list_id' => $id ), array( '%d' ) );
        return (bool) $wpdb->delete( WPLA_Database::table( 'lists' ), array( 'id' => $id ), array( '%d' ) );
    }

    public static function all( string $status = 'active' ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'lists' );

        if ( $status ) {
            return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE status = %s ORDER BY name ASC", $status ) );
        }
        return $wpdb->get_results( "SELECT * FROM $table ORDER BY name ASC" );
    }

    /**
     * Count subscribers in a list.
     */
    public static function subscriber_count( int $list_id ): int {
        global $wpdb;
        return (int) $wpdb->get_var( $wpdb->prepare(
            'SELECT COUNT(*) FROM ' . WPLA_Database::table( 'contact_lists' ) . ' WHERE list_id = %d AND status = %s',
            $list_id, 'subscribed'
        ) );
    }

    public static function count(): int {
        global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . WPLA_Database::table( 'lists' ) );
    }
}
