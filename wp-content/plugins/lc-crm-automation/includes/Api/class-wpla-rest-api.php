<?php
/**
 * REST API — Webhook endpoint and internal API.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Rest_Api {

    /**
     * Register all REST routes.
     */
    public static function register_routes(): void {
        // Public webhook endpoint (inbound — named clearly for settings display).
        register_rest_route( 'wpla/v1', '/webhook/inbound', array(
            'methods'             => 'GET,HEAD,POST',
            'callback'            => array( __CLASS__, 'handle_webhook' ),
            'permission_callback' => array( __CLASS__, 'validate_api_key' ),
        ) );

        // Legacy webhook path (keep for compatibility).
        register_rest_route( 'wpla/v1', '/webhook', array(
            'methods'             => 'GET,HEAD,POST',
            'callback'            => array( __CLASS__, 'handle_webhook' ),
            'permission_callback' => array( __CLASS__, 'validate_api_key' ),
        ) );

        // Webinar player events (called from the front-end player).
        register_rest_route( 'wpla/v1', '/webinar/event', array(
            'methods'             => 'POST',
            'callback'            => array( __CLASS__, 'handle_webinar_event' ),
            'permission_callback' => '__return_true',
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

    /* ───── Webinar Player Events ───── */

    /**
     * Handle events fired by the webinar player (front-end JS calls this).
     *
     * Expected POST body (JSON):
     *   {
     *     "webinar_id": 5,
     *     "event":      "offer_shown" | "offer_clicked" | "replay_requested",
     *     "contact_id": 12,           // optional — if known
     *     "email":      "x@y.com"     // optional — used to resolve contact if contact_id absent
     *   }
     */
    public static function handle_webinar_event( WP_REST_Request $request ): WP_REST_Response {
        $params = $request->get_json_params();

        if ( empty( $params ) ) {
            $params = $request->get_body_params();
        }

        $webinar_id = absint( $params['webinar_id'] ?? 0 );
        $event      = sanitize_text_field( $params['event'] ?? '' );
        $contact_id = absint( $params['contact_id'] ?? 0 );
        $email      = sanitize_email( $params['email'] ?? '' );

        if ( ! $webinar_id || ! $event ) {
            return new WP_REST_Response( array( 'error' => 'webinar_id and event are required.' ), 400 );
        }

        // Resolve contact from email if contact_id not provided.
        if ( ! $contact_id && is_email( $email ) ) {
            $contact = WPLA_Contact::get_by_email( $email );
            if ( $contact ) {
                $contact_id = (int) $contact->id;
            }
        }

        if ( ! $contact_id ) {
            return new WP_REST_Response( array( 'error' => 'Could not resolve contact.' ), 404 );
        }

        // Map event string to routing destination.
        $destination_map = array(
            'offer_shown'       => 'assistiu_oferta',
            'offer_not_shown'   => 'nao_viu_oferta',
            'offer_clicked'     => 'converteu',
            'replay_requested'  => 'replay',
        );

        $destination = $destination_map[ $event ] ?? '';

        if ( $destination ) {
            WPLA_Webinar::route_contact( $webinar_id, $contact_id, $destination );
        } else {
            // Unknown event — just fire action for custom handling.
            do_action( 'wpla_event', "webinar_{$event}", $contact_id, array(
                'webinar_id' => $webinar_id,
                'event'      => $event,
            ) );
        }

        return new WP_REST_Response( array(
            'success'     => true,
            'contact_id'  => $contact_id,
            'webinar_id'  => $webinar_id,
            'event'       => $event,
            'destination' => $destination,
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
