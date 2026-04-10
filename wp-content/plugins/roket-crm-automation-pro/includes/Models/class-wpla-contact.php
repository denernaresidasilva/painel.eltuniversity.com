<?php
/**
 * Contact model — CRUD for contacts table.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Contact {

    /**
     * Create a contact. Returns the new ID or WP_Error.
     *
     * @param array $data Associative array of contact fields.
     * @return int|WP_Error
     */
    public static function create( array $data ) {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        $defaults = array(
            'email'        => '',
            'first_name'   => '',
            'last_name'    => '',
            'phone'        => '',
            'company'      => '',
            'status'       => 'active',
            'lead_score'   => 0,
            'source'       => '',
            'utm_source'   => '',
            'utm_medium'   => '',
            'utm_campaign' => '',
            'utm_content'  => '',
            'utm_term'     => '',
            'ip_address'   => '',
            'custom_fields'=> null,
        );

        $data  = wp_parse_args( $data, $defaults );
        $email = sanitize_email( $data['email'] );

        if ( empty( $email ) || ! is_email( $email ) ) {
            return new WP_Error( 'invalid_email', __( 'A valid email address is required.', 'roket-crm' ) );
        }

        // Check duplicate
        $existing = $wpdb->get_var( $wpdb->prepare(
            "SELECT id FROM $table WHERE email = %s",
            $email
        ) );

        if ( $existing ) {
            return new WP_Error( 'duplicate_email', __( 'A contact with this email already exists.', 'roket-crm' ), array( 'contact_id' => (int) $existing ) );
        }

        $custom_fields = is_array( $data['custom_fields'] ) ? wp_json_encode( $data['custom_fields'] ) : $data['custom_fields'];

        $inserted = $wpdb->insert(
            $table,
            array(
                'email'         => $email,
                'first_name'    => sanitize_text_field( $data['first_name'] ),
                'last_name'     => sanitize_text_field( $data['last_name'] ),
                'phone'         => sanitize_text_field( $data['phone'] ),
                'company'       => sanitize_text_field( $data['company'] ),
                'status'        => in_array( $data['status'], array( 'active', 'unsubscribed', 'bounced' ), true ) ? $data['status'] : 'active',
                'lead_score'    => absint( $data['lead_score'] ),
                'source'        => sanitize_text_field( $data['source'] ),
                'utm_source'    => sanitize_text_field( $data['utm_source'] ),
                'utm_medium'    => sanitize_text_field( $data['utm_medium'] ),
                'utm_campaign'  => sanitize_text_field( $data['utm_campaign'] ),
                'utm_content'   => sanitize_text_field( $data['utm_content'] ),
                'utm_term'      => sanitize_text_field( $data['utm_term'] ),
                'ip_address'    => sanitize_text_field( $data['ip_address'] ),
                'custom_fields' => $custom_fields,
            ),
            array( '%s','%s','%s','%s','%s','%s','%d','%s','%s','%s','%s','%s','%s','%s','%s' )
        );

        if ( false === $inserted ) {
            return new WP_Error( 'db_error', __( 'Could not create contact.', 'roket-crm' ) );
        }

        $contact_id = (int) $wpdb->insert_id;

        /**
         * Fire contact_created event.
         */
        do_action( 'wpla_event', 'contact_created', $contact_id, $data );

        return $contact_id;
    }

    /**
     * Get a single contact by ID.
     */
    public static function get( int $id ): ?object {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id ) );
        return $row ?: null;
    }

    /**
     * Get a contact by email.
     */
    public static function get_by_email( string $email ): ?object {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        $row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE email = %s", sanitize_email( $email ) ) );
        return $row ?: null;
    }

    /**
     * Update a contact.
     *
     * @param int   $id   Contact ID.
     * @param array $data Fields to update.
     * @return bool|WP_Error
     */
    public static function update( int $id, array $data ) {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        $allowed = array( 'email','first_name','last_name','phone','company','status','lead_score','source','utm_source','utm_medium','utm_campaign','utm_content','utm_term','ip_address','custom_fields' );

        $update = array();
        $format = array();

        foreach ( $data as $key => $value ) {
            if ( ! in_array( $key, $allowed, true ) ) {
                continue;
            }
            if ( 'email' === $key ) {
                $value = sanitize_email( $value );
                if ( ! is_email( $value ) ) {
                    continue;
                }
            } elseif ( 'lead_score' === $key ) {
                $value = absint( $value );
            } elseif ( 'custom_fields' === $key && is_array( $value ) ) {
                $value = wp_json_encode( $value );
            } else {
                $value = sanitize_text_field( $value );
            }
            $update[ $key ] = $value;
            $format[]       = 'lead_score' === $key ? '%d' : '%s';
        }

        if ( empty( $update ) ) {
            return false;
        }

        $result = $wpdb->update( $table, $update, array( 'id' => $id ), $format, array( '%d' ) );

        if ( false !== $result ) {
            do_action( 'wpla_event', 'contact_updated', $id, $data );
        }

        return false !== $result;
    }

    /**
     * Delete a contact and related pivot rows.
     */
    public static function delete( int $id ): bool {
        global $wpdb;

        $wpdb->delete( WPLA_Database::table( 'contact_tags' ), array( 'contact_id' => $id ), array( '%d' ) );
        $wpdb->delete( WPLA_Database::table( 'contact_lists' ), array( 'contact_id' => $id ), array( '%d' ) );

        return (bool) $wpdb->delete( WPLA_Database::table( 'contacts' ), array( 'id' => $id ), array( '%d' ) );
    }

    /**
     * List contacts with filters, search & pagination.
     *
     * @param array $args Filters.
     * @return array { items: object[], total: int }
     */
    public static function query( array $args = array() ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        $defaults = array(
            'search'   => '',
            'status'   => '',
            'tag_id'   => 0,
            'list_id'  => 0,
            'orderby'  => 'created_at',
            'order'    => 'DESC',
            'per_page' => 20,
            'page'     => 1,
        );

        $args   = wp_parse_args( $args, $defaults );
        $where  = array( '1=1' );
        $values = array();

        if ( ! empty( $args['search'] ) ) {
            $like     = '%' . $wpdb->esc_like( $args['search'] ) . '%';
            $where[]  = '(c.email LIKE %s OR c.first_name LIKE %s OR c.last_name LIKE %s OR c.phone LIKE %s)';
            $values[] = $like;
            $values[] = $like;
            $values[] = $like;
            $values[] = $like;
        }

        if ( ! empty( $args['status'] ) ) {
            $where[]  = 'c.status = %s';
            $values[] = sanitize_text_field( $args['status'] );
        }

        $join = '';
        if ( ! empty( $args['tag_id'] ) ) {
            $join    .= ' INNER JOIN ' . WPLA_Database::table( 'contact_tags' ) . ' ct ON ct.contact_id = c.id';
            $where[]  = 'ct.tag_id = %d';
            $values[] = absint( $args['tag_id'] );
        }

        if ( ! empty( $args['list_id'] ) ) {
            $join    .= ' INNER JOIN ' . WPLA_Database::table( 'contact_lists' ) . ' cl ON cl.contact_id = c.id';
            $where[]  = 'cl.list_id = %d';
            $values[] = absint( $args['list_id'] );
        }

        $where_sql = implode( ' AND ', $where );
        $allowed_order = array( 'created_at', 'email', 'first_name', 'lead_score' );
        $orderby       = in_array( $args['orderby'], $allowed_order, true ) ? $args['orderby'] : 'created_at';
        $order         = 'ASC' === strtoupper( $args['order'] ) ? 'ASC' : 'DESC';
        $per_page      = absint( $args['per_page'] );
        $offset        = ( absint( $args['page'] ) - 1 ) * $per_page;

        // Total count
        $count_sql = "SELECT COUNT(DISTINCT c.id) FROM $table c $join WHERE $where_sql";
        if ( ! empty( $values ) ) {
            $count_sql = $wpdb->prepare( $count_sql, $values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        }
        $total = (int) $wpdb->get_var( $count_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

        // Items
        $items_sql = "SELECT DISTINCT c.* FROM $table c $join WHERE $where_sql ORDER BY c.$orderby $order LIMIT %d OFFSET %d";
        $values[]  = $per_page;
        $values[]  = $offset;
        $items_sql = $wpdb->prepare( $items_sql, $values ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $items     = $wpdb->get_results( $items_sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

        return array(
            'items' => $items,
            'total' => $total,
        );
    }

    /**
     * Add a tag to a contact.
     */
    public static function add_tag( int $contact_id, int $tag_id ): bool {
        global $wpdb;
        $table = WPLA_Database::table( 'contact_tags' );

        $exists = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE contact_id = %d AND tag_id = %d",
            $contact_id, $tag_id
        ) );

        if ( $exists ) {
            return true;
        }

        $result = $wpdb->insert( $table, array(
            'contact_id' => $contact_id,
            'tag_id'     => $tag_id,
        ), array( '%d', '%d' ) );

        if ( $result ) {
            do_action( 'wpla_event', 'tag_added', $contact_id, array( 'tag_id' => $tag_id ) );
        }

        return (bool) $result;
    }

    /**
     * Remove a tag from a contact.
     */
    public static function remove_tag( int $contact_id, int $tag_id ): bool {
        global $wpdb;
        $result = $wpdb->delete(
            WPLA_Database::table( 'contact_tags' ),
            array( 'contact_id' => $contact_id, 'tag_id' => $tag_id ),
            array( '%d', '%d' )
        );

        if ( $result ) {
            do_action( 'wpla_event', 'tag_removed', $contact_id, array( 'tag_id' => $tag_id ) );
        }

        return (bool) $result;
    }

    /**
     * Get all tags for a contact.
     */
    public static function get_tags( int $contact_id ): array {
        global $wpdb;
        $ct = WPLA_Database::table( 'contact_tags' );
        $t  = WPLA_Database::table( 'tags' );

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT t.* FROM $t t INNER JOIN $ct ct ON ct.tag_id = t.id WHERE ct.contact_id = %d ORDER BY t.name ASC",
            $contact_id
        ) );
    }

    /**
     * Subscribe a contact to a list.
     */
    public static function subscribe_list( int $contact_id, int $list_id ): bool {
        global $wpdb;
        $table = WPLA_Database::table( 'contact_lists' );

        $exists = $wpdb->get_var( $wpdb->prepare(
            "SELECT COUNT(*) FROM $table WHERE contact_id = %d AND list_id = %d",
            $contact_id, $list_id
        ) );

        if ( $exists ) {
            $wpdb->update( $table, array( 'status' => 'subscribed' ), array( 'contact_id' => $contact_id, 'list_id' => $list_id ) );
            return true;
        }

        $result = $wpdb->insert( $table, array(
            'contact_id' => $contact_id,
            'list_id'    => $list_id,
            'status'     => 'subscribed',
        ), array( '%d', '%d', '%s' ) );

        if ( $result ) {
            do_action( 'wpla_event', 'list_subscribed', $contact_id, array( 'list_id' => $list_id ) );
        }

        return (bool) $result;
    }

    /**
     * Get all lists for a contact.
     */
    public static function get_lists( int $contact_id ): array {
        global $wpdb;
        $cl = WPLA_Database::table( 'contact_lists' );
        $l  = WPLA_Database::table( 'lists' );

        return $wpdb->get_results( $wpdb->prepare(
            "SELECT l.*, cl.status AS subscription_status FROM $l l INNER JOIN $cl cl ON cl.list_id = l.id WHERE cl.contact_id = %d ORDER BY l.name ASC",
            $contact_id
        ) );
    }

    /**
     * Unsubscribe a contact from a list (marks as 'unsubscribed').
     */
    public static function unsubscribe_list( int $contact_id, int $list_id ): bool {
        global $wpdb;
        $table = WPLA_Database::table( 'contact_lists' );

        $result = $wpdb->update(
            $table,
            array( 'status' => 'unsubscribed' ),
            array( 'contact_id' => $contact_id, 'list_id' => $list_id ),
            array( '%s' ),
            array( '%d', '%d' )
        );

        return $result !== false;
    }

    /**
     * Get total contacts count.
     */
    public static function count( string $status = '' ): int {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );

        if ( $status ) {
            return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table WHERE status = %s", $status ) );
        }

        return (int) $wpdb->get_var( "SELECT COUNT(*) FROM $table" );
    }
}
