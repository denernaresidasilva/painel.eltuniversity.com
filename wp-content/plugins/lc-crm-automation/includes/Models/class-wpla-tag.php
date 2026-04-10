<?php
/**
 * Tag model — CRUD for tags table.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Tag {

    public static function create( array $data ): int {
        global $wpdb;

        $wpdb->insert(
            WPLA_Database::table( 'tags' ),
            array(
                'name'  => sanitize_text_field( $data['name'] ?? '' ),
                'color' => sanitize_hex_color( $data['color'] ?? '#6366f1' ) ?: '#6366f1',
            ),
            array( '%s', '%s' )
        );

        return (int) $wpdb->insert_id;
    }

    public static function get( int $id ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            'SELECT * FROM ' . WPLA_Database::table( 'tags' ) . ' WHERE id = %d', $id
        ) ) ?: null;
    }

    public static function get_by_name( string $name ): ?object {
        global $wpdb;
        return $wpdb->get_row( $wpdb->prepare(
            'SELECT * FROM ' . WPLA_Database::table( 'tags' ) . ' WHERE name = %s',
            sanitize_text_field( $name )
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
        if ( isset( $data['color'] ) ) {
            $update['color'] = sanitize_hex_color( $data['color'] ) ?: '#6366f1';
            $format[]        = '%s';
        }

        if ( empty( $update ) ) {
            return false;
        }

        return false !== $wpdb->update( WPLA_Database::table( 'tags' ), $update, array( 'id' => $id ), $format, array( '%d' ) );
    }

    public static function delete( int $id ): bool {
        global $wpdb;
        $wpdb->delete( WPLA_Database::table( 'contact_tags' ), array( 'tag_id' => $id ), array( '%d' ) );
        return (bool) $wpdb->delete( WPLA_Database::table( 'tags' ), array( 'id' => $id ), array( '%d' ) );
    }

    public static function all(): array {
        global $wpdb;
        return $wpdb->get_results( 'SELECT * FROM ' . WPLA_Database::table( 'tags' ) . ' ORDER BY name ASC' );
    }

    /**
     * Count contacts that have this tag.
     */
    public static function contact_count( int $tag_id ): int {
        global $wpdb;
        return (int) $wpdb->get_var( $wpdb->prepare(
            'SELECT COUNT(*) FROM ' . WPLA_Database::table( 'contact_tags' ) . ' WHERE tag_id = %d',
            $tag_id
        ) );
    }

    public static function count(): int {
        global $wpdb;
        return (int) $wpdb->get_var( 'SELECT COUNT(*) FROM ' . WPLA_Database::table( 'tags' ) );
    }
}
