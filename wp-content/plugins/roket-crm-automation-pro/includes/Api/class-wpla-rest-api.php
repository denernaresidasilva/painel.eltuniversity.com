<?php
/**
 * REST API — Webhook endpoint and internal API.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Rest_Api {

    /**
     * Register all REST routes.
     */
    public static function register_routes(): void {
        // Public webhook endpoint.
        register_rest_route( 'wpla/v1', '/webhook', array(
            'methods'             => 'GET,HEAD,POST',
            'callback'            => array( __CLASS__, 'handle_webhook' ),
            'permission_callback' => array( __CLASS__, 'validate_api_key' ),
        ) );

        // Internal CRUD endpoints (admin only).
        register_rest_route( 'wpla/v1', '/contacts', array(
            array(
                'methods'             => 'GET',
                'callback'            => array( __CLASS__, 'get_contacts' ),
                'permission_callback' => array( __CLASS__, 'admin_permission' ),
            ),
            array(
                'methods'             => 'POST',
                'callback'            => array( __CLASS__, 'create_contact' ),
                'permission_callback' => array( __CLASS__, 'admin_permission' ),
            ),
        ) );

        register_rest_route( 'wpla/v1', '/contacts/(?P<id>\d+)', array(
            array(
                'methods'             => 'GET',
                'callback'            => array( __CLASS__, 'get_contact' ),
                'permission_callback' => array( __CLASS__, 'admin_permission' ),
            ),
            array(
                'methods'             => 'PUT,PATCH',
                'callback'            => array( __CLASS__, 'update_contact' ),
                'permission_callback' => array( __CLASS__, 'admin_permission' ),
            ),
            array(
                'methods'             => 'DELETE',
                'callback'            => array( __CLASS__, 'delete_contact' ),
                'permission_callback' => array( __CLASS__, 'admin_permission' ),
            ),
        ) );

        register_rest_route( 'wpla/v1', '/lists', array(
            'methods'             => 'GET',
            'callback'            => array( __CLASS__, 'get_lists' ),
            'permission_callback' => array( __CLASS__, 'admin_permission' ),
        ) );

        register_rest_route( 'wpla/v1', '/tags', array(
            'methods'             => 'GET',
            'callback'            => array( __CLASS__, 'get_tags' ),
            'permission_callback' => array( __CLASS__, 'admin_permission' ),
        ) );

        register_rest_route( 'wpla/v1', '/automations', array(
            'methods'             => 'GET',
            'callback'            => array( __CLASS__, 'get_automations' ),
            'permission_callback' => array( __CLASS__, 'admin_permission' ),
        ) );

        register_rest_route( 'wpla/v1', '/automations/(?P<id>\d+)/steps', array(
            'methods'             => 'GET',
            'callback'            => array( __CLASS__, 'get_automation_steps' ),
            'permission_callback' => array( __CLASS__, 'admin_permission' ),
        ) );

        register_rest_route( 'wpla/v1', '/stats', array(
            'methods'             => 'GET',
            'callback'            => array( __CLASS__, 'get_stats' ),
            'permission_callback' => array( __CLASS__, 'admin_permission' ),
        ) );
    }

    /**
     * Validate API key for webhooks. Rate-limited.
     */
    public static function validate_api_key( WP_REST_Request $request ): bool {
        $method = strtoupper( $request->get_method() );
        if ( in_array( $method, array( 'GET', 'HEAD' ), true ) ) {
            return true;
        }

        $api_key  = get_option( 'wpla_api_key', '' );
        $provided = $request->get_header( 'X-API-Key' );

        if ( empty( $provided ) ) {
            $provided = $request->get_param( 'api_key' );
        }

        if ( empty( $api_key ) || ! hash_equals( $api_key, (string) $provided ) ) {
            return false;
        }

        // Rate limiting: 60 requests per minute per IP.
        $ip    = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' );
        $trans = 'wpla_wh_rate_' . md5( $ip );
        $count = (int) get_transient( $trans );
        if ( $count >= 60 ) {
            return false;
        }
        set_transient( $trans, $count + 1, 60 );

        return true;
    }

    /**
     * Admin permission check.
     */
    public static function admin_permission(): bool {
        return current_user_can( 'manage_options' );
    }

    /**
     * Handle incoming webhook.
     */
    public static function handle_webhook( WP_REST_Request $request ): WP_REST_Response {
        $method = strtoupper( $request->get_method() );
        if ( in_array( $method, array( 'GET', 'HEAD' ), true ) ) {
            return new WP_REST_Response( array(
                'success' => true,
                'message' => 'Webhook endpoint ready.',
                'method'  => $method,
            ), 200 );
        }

        $params = $request->get_json_params();

        if ( empty( $params ) ) {
            $params = $request->get_body_params();
        }

        $email = sanitize_email( $params['email'] ?? '' );
        if ( ! is_email( $email ) ) {
            return new WP_REST_Response( array( 'error' => 'Valid email is required.' ), 400 );
        }

        $list_id_from_query = absint( $request->get_param( 'list_id' ) );

        $data = array(
            'email'      => $email,
            'first_name' => sanitize_text_field( $params['first_name'] ?? '' ),
            'last_name'  => sanitize_text_field( $params['last_name'] ?? '' ),
            'phone'      => sanitize_text_field( $params['phone'] ?? '' ),
            'company'    => sanitize_text_field( $params['company'] ?? '' ),
            'source'     => 'webhook',
        );

        $result = WPLA_Contact::create( $data );

        if ( is_wp_error( $result ) && 'duplicate_email' === $result->get_error_code() ) {
            $err_data   = $result->get_error_data();
            $contact_id = $err_data['contact_id'] ?? 0;
            if ( $contact_id ) {
                WPLA_Contact::update( (int) $contact_id, $data );
            }
        } elseif ( is_wp_error( $result ) ) {
            return new WP_REST_Response( array( 'error' => $result->get_error_message() ), 400 );
        } else {
            $contact_id = $result;
        }

        // Tags.
        if ( ! empty( $params['tags'] ) ) {
            $tags = is_array( $params['tags'] ) ? $params['tags'] : explode( ',', $params['tags'] );
            foreach ( $tags as $tag_name ) {
                $tag_name = sanitize_text_field( trim( $tag_name ) );
                if ( empty( $tag_name ) ) {
                    continue;
                }
                $tag = WPLA_Tag::get_by_name( $tag_name );
                if ( ! $tag ) {
                    $tag_id = WPLA_Tag::create( array( 'name' => $tag_name ) );
                } else {
                    $tag_id = $tag->id;
                }
                WPLA_Contact::add_tag( (int) $contact_id, (int) $tag_id );
            }
        }

        // Lists.
        if ( ! empty( $params['lists'] ) ) {
            $lists = is_array( $params['lists'] ) ? $params['lists'] : explode( ',', $params['lists'] );
            foreach ( $lists as $list_id ) {
                $list_id = absint( trim( $list_id ) );
                if ( $list_id ) {
                    WPLA_Contact::subscribe_list( (int) $contact_id, $list_id );
                }
            }
        }

        if ( $list_id_from_query > 0 ) {
            WPLA_Contact::subscribe_list( (int) $contact_id, $list_id_from_query );
        }

        do_action( 'wpla_event', 'webhook_received', (int) $contact_id, $params );

        return new WP_REST_Response( array(
            'success'    => true,
            'contact_id' => (int) $contact_id,
        ), 200 );
    }

    /* ───── Internal admin endpoints ───── */

    public static function get_contacts( WP_REST_Request $request ): WP_REST_Response {
        $result = WPLA_Contact::query( array(
            'search'   => $request->get_param( 'search' ) ?? '',
            'status'   => $request->get_param( 'status' ) ?? '',
            'tag_id'   => $request->get_param( 'tag_id' ) ?? 0,
            'list_id'  => $request->get_param( 'list_id' ) ?? 0,
            'per_page' => $request->get_param( 'per_page' ) ?? 20,
            'page'     => $request->get_param( 'page' ) ?? 1,
            'orderby'  => $request->get_param( 'orderby' ) ?? 'created_at',
            'order'    => $request->get_param( 'order' ) ?? 'DESC',
        ) );

        return new WP_REST_Response( $result );
    }

    public static function get_contact( WP_REST_Request $request ): WP_REST_Response {
        $contact = WPLA_Contact::get( (int) $request['id'] );
        if ( ! $contact ) {
            return new WP_REST_Response( array( 'error' => 'Not found' ), 404 );
        }
        $contact->tags  = WPLA_Contact::get_tags( (int) $contact->id );
        $contact->lists = WPLA_Contact::get_lists( (int) $contact->id );
        return new WP_REST_Response( $contact );
    }

    public static function create_contact( WP_REST_Request $request ): WP_REST_Response {
        $result = WPLA_Contact::create( $request->get_json_params() );
        if ( is_wp_error( $result ) ) {
            return new WP_REST_Response( array( 'error' => $result->get_error_message() ), 400 );
        }
        return new WP_REST_Response( array( 'id' => $result ), 201 );
    }

    public static function update_contact( WP_REST_Request $request ): WP_REST_Response {
        $success = WPLA_Contact::update( (int) $request['id'], $request->get_json_params() );
        return new WP_REST_Response( array( 'success' => $success ) );
    }

    public static function delete_contact( WP_REST_Request $request ): WP_REST_Response {
        $success = WPLA_Contact::delete( (int) $request['id'] );
        return new WP_REST_Response( array( 'success' => $success ) );
    }

    public static function get_lists(): WP_REST_Response {
        return new WP_REST_Response( WPLA_List_Model::all( '' ) );
    }

    public static function get_tags(): WP_REST_Response {
        return new WP_REST_Response( WPLA_Tag::all() );
    }

    public static function get_automations(): WP_REST_Response {
        return new WP_REST_Response( WPLA_Automation_Engine::all_automations() );
    }

    public static function get_automation_steps( WP_REST_Request $request ): WP_REST_Response {
        return new WP_REST_Response( WPLA_Automation_Engine::get_steps( (int) $request['id'] ) );
    }

    public static function get_stats(): WP_REST_Response {
        $seven_days_ago = gmdate( 'Y-m-d H:i:s', strtotime( '-7 days' ) );

        return new WP_REST_Response( array(
            'contacts_total'     => WPLA_Contact::count(),
            'contacts_active'    => WPLA_Contact::count( 'active' ),
            'lists_total'        => WPLA_List_Model::count(),
            'tags_total'         => WPLA_Tag::count(),
            'automations_active' => WPLA_Automation_Engine::count_active(),
            'events_total'       => WPLA_Event_Manager::count_total(),
            'queue_stats'        => WPLA_Message_Queue::stats(),
            'recent_contacts'    => WPLA_Event_Manager::count_by_type( 'contact_created', $seven_days_ago ),
        ) );
    }
}
