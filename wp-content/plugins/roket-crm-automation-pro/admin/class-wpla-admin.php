<?php
/**
 * Admin controller — menus, pages, AJAX handlers.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_menus' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

        // AJAX handlers.
        $ajax_actions = array(
            'wpla_save_contact',
            'wpla_delete_contact',
            'wpla_save_list',
            'wpla_delete_list',
            'wpla_save_tag',
            'wpla_delete_tag',
            'wpla_save_automation',
            'wpla_delete_automation',
            'wpla_save_settings',
            'wpla_get_dashboard_stats',
            'wpla_contact_add_tag',
            'wpla_contact_remove_tag',
            'wpla_contact_subscribe_list',
            'wpla_send_test_email',
            'wpla_send_test_whatsapp',
            // Webinar handlers.
            'wpla_save_webinar',
            'wpla_delete_webinar',
            'wpla_get_webinar',
            'wpla_list_webinars',
            'wpla_get_webinar_lists',
            // Evolution API handlers.
            'wpla_evolution_create_instance',
            'wpla_evolution_get_qr',
        );

        foreach ( $ajax_actions as $action ) {
            add_action( "wp_ajax_{$action}", array( $this, $action ) );
        }
    }

    /**
     * Register admin menus.
     */
    public function register_menus(): void {
        add_menu_page(
            __( 'Roket CRM', 'roket-crm' ),
            __( 'Roket CRM', 'roket-crm' ),
            'manage_options',
            'wpla-dashboard',
            array( $this, 'render_app' ),
            'data:image/svg+xml;base64,' . base64_encode( '<svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#a5b4fc"/></svg>' ),
            3
        );

        $subpages = array(
            'wpla-dashboard'   => __( 'Painel', 'roket-crm' ),
            'wpla-contacts'    => __( 'Contatos', 'roket-crm' ),
            'wpla-lists'       => __( 'Listas', 'roket-crm' ),
            'wpla-tags'        => __( 'Tags', 'roket-crm' ),
            'wpla-automations' => __( 'Automações', 'roket-crm' ),
            'wpla-webinars'    => __( 'Webinars', 'roket-crm' ),
            'wpla-forms'       => __( 'Formulários', 'roket-crm' ),
            'wpla-whatsapp'    => __( 'WhatsApp', 'roket-crm' ),
            'wpla-email'       => __( 'Email', 'roket-crm' ),
            'wpla-webhooks'    => __( 'Webhooks', 'roket-crm' ),
            'wpla-settings'    => __( 'Configurações', 'roket-crm' ),
        );

        foreach ( $subpages as $slug => $title ) {
            add_submenu_page(
                'wpla-dashboard',
                $title,
                $title,
                'manage_options',
                $slug,
                array( $this, 'render_app' )
            );
        }
    }

    /**
     * Enqueue admin assets.
     */
    public function enqueue_assets( string $hook ): void {
        if ( strpos( $hook, 'wpla-' ) === false ) {
            return;
        }

        wp_enqueue_style( 'wpla-admin', WPLA_PLUGIN_URL . 'assets/css/admin.css', array(), WPLA_VERSION );
        wp_enqueue_script( 'wpla-admin', WPLA_PLUGIN_URL . 'assets/js/admin.js', array(), WPLA_VERSION, true );

        wp_localize_script( 'wpla-admin', 'wpla', array(
            'ajax_url'  => admin_url( 'admin-ajax.php' ),
            'rest_url'  => rest_url( 'wpla/v1/' ),
            'nonce'     => wp_create_nonce( 'wpla_admin_nonce' ),
            'rest_nonce'=> wp_create_nonce( 'wp_rest' ),
            'api_key'   => get_option( 'wpla_api_key', '' ),
            'site_url'  => home_url(),
        ) );
    }

    /**
     * Render the SPA shell — all pages use the same wrapper.
     */
    public function render_app(): void {
        include WPLA_PLUGIN_DIR . 'admin/views/app-shell.php';
    }

    /* ───────────────────────────────────
     * AJAX Handlers
     * ─────────────────────────────────── */

    private function verify_nonce(): void {
        if ( ! check_ajax_referer( 'wpla_admin_nonce', 'nonce', false ) || ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( array( 'message' => 'Unauthorized' ), 403 );
        }
    }

    /* ── Contacts ── */

    public function wpla_save_contact(): void {
        $this->verify_nonce();

        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

        $data = array(
            'email'      => isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '',
            'first_name' => isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '',
            'last_name'  => isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '',
            'phone'      => isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '',
            'company'    => isset( $_POST['company'] ) ? sanitize_text_field( wp_unslash( $_POST['company'] ) ) : '',
            'status'     => isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'active',
        );

        if ( $id ) {
            $success = WPLA_Contact::update( $id, $data );
            wp_send_json_success( array( 'id' => $id, 'updated' => $success ) );
        } else {
            $result = WPLA_Contact::create( $data );
            if ( is_wp_error( $result ) ) {
                wp_send_json_error( array( 'message' => $result->get_error_message() ) );
            }
            wp_send_json_success( array( 'id' => $result ) );
        }
    }

    public function wpla_delete_contact(): void {
        $this->verify_nonce();
        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        wp_send_json_success( array( 'deleted' => WPLA_Contact::delete( $id ) ) );
    }

    public function wpla_contact_add_tag(): void {
        $this->verify_nonce();
        $contact_id = isset( $_POST['contact_id'] ) ? absint( $_POST['contact_id'] ) : 0;
        $tag_id     = isset( $_POST['tag_id'] ) ? absint( $_POST['tag_id'] ) : 0;
        wp_send_json_success( array( 'added' => WPLA_Contact::add_tag( $contact_id, $tag_id ) ) );
    }

    public function wpla_contact_remove_tag(): void {
        $this->verify_nonce();
        $contact_id = isset( $_POST['contact_id'] ) ? absint( $_POST['contact_id'] ) : 0;
        $tag_id     = isset( $_POST['tag_id'] ) ? absint( $_POST['tag_id'] ) : 0;
        wp_send_json_success( array( 'removed' => WPLA_Contact::remove_tag( $contact_id, $tag_id ) ) );
    }

    public function wpla_contact_subscribe_list(): void {
        $this->verify_nonce();
        $contact_id = isset( $_POST['contact_id'] ) ? absint( $_POST['contact_id'] ) : 0;
        $list_id    = isset( $_POST['list_id'] ) ? absint( $_POST['list_id'] ) : 0;
        wp_send_json_success( array( 'subscribed' => WPLA_Contact::subscribe_list( $contact_id, $list_id ) ) );
    }

    /* ── Lists ── */

    public function wpla_save_list(): void {
        $this->verify_nonce();

        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

        $data = array(
            'name'        => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
            'description' => isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '',
        );

        if ( $id ) {
            WPLA_List_Model::update( $id, $data );
            wp_send_json_success( array( 'id' => $id ) );
        } else {
            $new_id = WPLA_List_Model::create( $data );
            wp_send_json_success( array( 'id' => $new_id ) );
        }
    }

    public function wpla_delete_list(): void {
        $this->verify_nonce();
        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        wp_send_json_success( array( 'deleted' => WPLA_List_Model::delete( $id ) ) );
    }

    /* ── Tags ── */

    public function wpla_save_tag(): void {
        $this->verify_nonce();

        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

        $data = array(
            'name'  => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
            'color' => isset( $_POST['color'] ) ? sanitize_text_field( wp_unslash( $_POST['color'] ) ) : '#6366f1',
        );

        if ( $id ) {
            WPLA_Tag::update( $id, $data );
            wp_send_json_success( array( 'id' => $id ) );
        } else {
            $new_id = WPLA_Tag::create( $data );
            wp_send_json_success( array( 'id' => $new_id ) );
        }
    }

    public function wpla_delete_tag(): void {
        $this->verify_nonce();
        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        wp_send_json_success( array( 'deleted' => WPLA_Tag::delete( $id ) ) );
    }

    /* ── Automations ── */

    public function wpla_save_automation(): void {
        $this->verify_nonce();

        $data = array(
            'id'             => isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0,
            'name'           => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
            'description'    => isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '',
            'trigger_type'   => isset( $_POST['trigger_type'] ) ? sanitize_text_field( wp_unslash( $_POST['trigger_type'] ) ) : 'contact_created',
            'status'         => isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'draft',
        );

        if ( isset( $_POST['trigger_config'] ) ) {
            $data['trigger_config'] = json_decode( sanitize_text_field( wp_unslash( $_POST['trigger_config'] ) ), true );
        }

        $automation_id = WPLA_Automation_Engine::save_automation( $data );

        // Save steps if provided.
        if ( isset( $_POST['steps'] ) ) {
            $raw_steps = wp_unslash( $_POST['steps'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
            $steps     = is_string( $raw_steps ) ? json_decode( $raw_steps, true ) : $raw_steps;
            if ( is_array( $steps ) ) {
                // Sanitize each step.
                $clean_steps = array();
                foreach ( $steps as $step ) {
                    $clean_steps[] = array(
                        'parent_id'    => absint( $step['parent_id'] ?? 0 ),
                        'step_type'    => sanitize_text_field( $step['step_type'] ?? 'action' ),
                        'action_type'  => sanitize_text_field( $step['action_type'] ?? '' ),
                        'config'       => $step['config'] ?? array(),
                        'step_order'   => absint( $step['step_order'] ?? 0 ),
                        'branch_label' => sanitize_text_field( $step['branch_label'] ?? '' ),
                    );
                }
                WPLA_Automation_Engine::save_steps( $automation_id, $clean_steps );
            }
        }

        wp_send_json_success( array( 'id' => $automation_id ) );
    }

    public function wpla_delete_automation(): void {
        $this->verify_nonce();
        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        wp_send_json_success( array( 'deleted' => WPLA_Automation_Engine::delete_automation( $id ) ) );
    }

    /* ── Settings ── */

    public function wpla_save_settings(): void {
        $this->verify_nonce();

        $fields = array(
            'wpla_whatsapp_provider',
            'wpla_evolution_api_url',
            'wpla_evolution_api_key',
            'wpla_evolution_instance',
            'wpla_meta_whatsapp_token',
            'wpla_meta_phone_number_id',
            'wpla_email_from_name',
            'wpla_email_from_address',
            // SMTP fields.
            'wpla_smtp_host',
            'wpla_smtp_port',
            'wpla_smtp_encryption',
            'wpla_smtp_auth',
            'wpla_smtp_user',
        );

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_option( $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }

        // SMTP password stored separately (sanitize but don't strip).
        if ( isset( $_POST['wpla_smtp_pass'] ) ) {
            update_option( 'wpla_smtp_pass', sanitize_text_field( wp_unslash( $_POST['wpla_smtp_pass'] ) ) );
        }

        // Regenerate API key if requested.
        if ( ! empty( $_POST['regenerate_api_key'] ) ) {
            update_option( 'wpla_api_key', wp_generate_password( 40, false ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Configurações salvas com sucesso.', 'roket-crm' ),
            'api_key' => get_option( 'wpla_api_key' ),
        ) );
    }

    /* ── Dashboard ── */

    public function wpla_get_dashboard_stats(): void {
        $this->verify_nonce();

        $seven_days = gmdate( 'Y-m-d H:i:s', strtotime( '-7 days' ) );

        wp_send_json_success( array(
            'contacts_total'     => WPLA_Contact::count(),
            'contacts_active'    => WPLA_Contact::count( 'active' ),
            'lists_total'        => WPLA_List_Model::count(),
            'tags_total'         => WPLA_Tag::count(),
            'automations_active' => WPLA_Automation_Engine::count_active(),
            'events_total'       => WPLA_Event_Manager::count_total(),
            'queue_stats'        => WPLA_Message_Queue::stats(),
            'new_contacts_7d'    => WPLA_Event_Manager::count_by_type( 'contact_created', $seven_days ),
            'recent_events'      => WPLA_Event_Manager::get_recent( 15 ),
        ) );
    }

    /* ── Test sends ── */

    public function wpla_send_test_email(): void {
        $this->verify_nonce();

        $to      = isset( $_POST['to'] ) ? sanitize_email( wp_unslash( $_POST['to'] ) ) : '';
        $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : 'Email de Teste do Roket CRM';
        $body    = isset( $_POST['body'] ) ? wp_kses_post( wp_unslash( $_POST['body'] ) ) : '<p>This is a test email.</p>';

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        $result  = wp_mail( $to, $subject, $body, $headers );

        wp_send_json_success( array( 'sent' => $result ) );
    }

    public function wpla_send_test_whatsapp(): void {
        $this->verify_nonce();

        $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
        $message = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : 'Teste do Roket CRM';

        // Create a mock message object.
        $msg = (object) array(
            'recipient' => $phone,
            'body'      => $message,
        );

        $result = WPLA_WhatsApp::send( $msg );

        wp_send_json_success( array( 'sent' => $result ) );
    }

    /* ── Webinars ── */

    public function wpla_list_webinars(): void {
        $this->verify_nonce();

        $webinars = WPLA_Webinar::get_all();

        wp_send_json_success( array(
            'items' => $webinars,
            'total' => count( $webinars ),
        ) );
    }

    public function wpla_get_webinar(): void {
        $this->verify_nonce();

        $id      = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        $webinar = WPLA_Webinar::get( $id );

        if ( ! $webinar ) {
            wp_send_json_error( array( 'message' => __( 'Webinar não encontrado.', 'roket-crm' ) ) );
            return;
        }

        wp_send_json_success( array( 'webinar' => $webinar ) );
    }

    public function wpla_save_webinar(): void {
        $this->verify_nonce();

        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;

        $data = array(
            'name'               => isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '',
            'description'        => isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '',
            'status'             => isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : 'draft',
            'video_type'         => isset( $_POST['video_type'] ) ? sanitize_text_field( wp_unslash( $_POST['video_type'] ) ) : 'youtube',
            'video_url'          => isset( $_POST['video_url'] ) ? esc_url_raw( wp_unslash( $_POST['video_url'] ) ) : '',
            'offer_title'        => isset( $_POST['offer_title'] ) ? sanitize_text_field( wp_unslash( $_POST['offer_title'] ) ) : '',
            'offer_url'          => isset( $_POST['offer_url'] ) ? esc_url_raw( wp_unslash( $_POST['offer_url'] ) ) : '',
            'offer_button_text'  => isset( $_POST['offer_button_text'] ) ? sanitize_text_field( wp_unslash( $_POST['offer_button_text'] ) ) : '',
            'offer_time_live'    => isset( $_POST['offer_time_live'] ) ? absint( $_POST['offer_time_live'] ) : 0,
            'offer_time_replay'  => isset( $_POST['offer_time_replay'] ) ? absint( $_POST['offer_time_replay'] ) : 0,
        );

        if ( $id ) {
            WPLA_Webinar::update( $id, $data );
            $webinar = WPLA_Webinar::get( $id );
            wp_send_json_success( array(
                'id'           => $id,
                'automation_id' => $webinar ? (int) $webinar->automation_id : 0,
            ) );
        } else {
            $new_id = WPLA_Webinar::create( $data );
            if ( ! $new_id ) {
                wp_send_json_error( array( 'message' => __( 'Erro ao criar webinar.', 'roket-crm' ) ) );
                return;
            }
            $webinar = WPLA_Webinar::get( $new_id );
            wp_send_json_success( array(
                'id'           => $new_id,
                'automation_id' => $webinar ? (int) $webinar->automation_id : 0,
            ) );
        }
    }

    public function wpla_delete_webinar(): void {
        $this->verify_nonce();
        $id = isset( $_POST['id'] ) ? absint( $_POST['id'] ) : 0;
        wp_send_json_success( array( 'deleted' => WPLA_Webinar::delete( $id ) ) );
    }

    public function wpla_get_webinar_lists(): void {
        $this->verify_nonce();

        $webinar_id = isset( $_POST['webinar_id'] ) ? absint( $_POST['webinar_id'] ) : 0;

        global $wpdb;
        $table = WPLA_Database::table( 'lists' );
        $cl    = WPLA_Database::table( 'contact_lists' );

        $lists = $wpdb->get_results( $wpdb->prepare(
            "SELECT l.*, (SELECT COUNT(*) FROM $cl WHERE list_id = l.id AND status = 'subscribed') AS subscriber_count
             FROM $table l
             WHERE l.webinar_id = %d
             ORDER BY l.id ASC",
            $webinar_id
        ) );

        wp_send_json_success( array( 'lists' => $lists ) );
    }

    /* ── Evolution API ── */

    public function wpla_evolution_create_instance(): void {
        $this->verify_nonce();

        $api_url  = isset( $_POST['api_url'] ) ? esc_url_raw( wp_unslash( $_POST['api_url'] ) ) : '';
        $api_key  = isset( $_POST['api_key'] ) ? sanitize_text_field( wp_unslash( $_POST['api_key'] ) ) : '';
        $instance = isset( $_POST['instance'] ) ? sanitize_text_field( wp_unslash( $_POST['instance'] ) ) : '';

        if ( empty( $api_url ) || empty( $api_key ) || empty( $instance ) ) {
            wp_send_json_error( array( 'message' => __( 'Preencha URL, chave API e nome da instância.', 'roket-crm' ) ) );
            return;
        }

        // Call Evolution API to create instance.
        $response = wp_remote_post(
            trailingslashit( $api_url ) . 'instance/create',
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'apikey'       => $api_key,
                ),
                'body'    => wp_json_encode( array(
                    'instanceName' => $instance,
                    'qrcode'       => true,
                ) ),
                'timeout' => 30,
            )
        );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( array( 'message' => $response->get_error_message() ) );
            return;
        }

        $code = wp_remote_retrieve_response_code( $response );
        $body = json_decode( wp_remote_retrieve_body( $response ), true );

        // Extract QR code from response.
        $qr_code = '';
        if ( isset( $body['qrcode']['base64'] ) ) {
            $qr_code = $body['qrcode']['base64'];
            if ( strpos( $qr_code, 'data:image' ) === false ) {
                $qr_code = 'data:image/png;base64,' . $qr_code;
            }
            update_option( 'wpla_evolution_qr_code', $qr_code );
        }

        $status = $body['instance']['state'] ?? ( $code >= 200 && $code < 300 ? 'connecting' : 'error' );

        if ( $code >= 200 && $code < 300 || $code === 409 ) {
            // 409 = instance already exists — still success.
            wp_send_json_success( array(
                'status'   => $status,
                'qr_code'  => $qr_code,
                'response' => $body,
            ) );
        } else {
            $msg = isset( $body['message'] ) ? $body['message'] : __( 'Erro ao criar instância Evolution.', 'roket-crm' );
            wp_send_json_error( array( 'message' => $msg ) );
        }
    }

    public function wpla_evolution_get_qr(): void {
        $this->verify_nonce();

        $api_url  = get_option( 'wpla_evolution_api_url', '' );
        $api_key  = get_option( 'wpla_evolution_api_key', '' );
        $instance = get_option( 'wpla_evolution_instance', '' );

        if ( empty( $api_url ) || empty( $api_key ) || empty( $instance ) ) {
            wp_send_json_error( array( 'message' => __( 'Configurações da Evolution API incompletas.', 'roket-crm' ) ) );
            return;
        }

        // First check connection state.
        $state_resp = wp_remote_get(
            trailingslashit( $api_url ) . "instance/connectionState/{$instance}",
            array(
                'headers' => array( 'apikey' => $api_key ),
                'timeout' => 15,
            )
        );

        if ( ! is_wp_error( $state_resp ) ) {
            $state_body = json_decode( wp_remote_retrieve_body( $state_resp ), true );
            $state      = $state_body['instance']['state'] ?? '';

            if ( 'open' === $state ) {
                // Already connected — no QR needed.
                delete_option( 'wpla_evolution_qr_code' );
                wp_send_json_success( array( 'status' => 'open', 'qr_code' => '' ) );
                return;
            }
        }

        // Fetch QR code.
        $response = wp_remote_get(
            trailingslashit( $api_url ) . "instance/connect/{$instance}",
            array(
                'headers' => array( 'apikey' => $api_key ),
                'timeout' => 30,
            )
        );

        if ( is_wp_error( $response ) ) {
            wp_send_json_error( array( 'message' => $response->get_error_message() ) );
            return;
        }

        $body    = json_decode( wp_remote_retrieve_body( $response ), true );
        $qr_code = '';

        if ( isset( $body['base64'] ) ) {
            $qr_code = $body['base64'];
            if ( strpos( $qr_code, 'data:image' ) === false ) {
                $qr_code = 'data:image/png;base64,' . $qr_code;
            }
            update_option( 'wpla_evolution_qr_code', $qr_code );
        }

        if ( $qr_code ) {
            wp_send_json_success( array( 'status' => 'connecting', 'qr_code' => $qr_code ) );
        } else {
            wp_send_json_error( array( 'message' => __( 'Não foi possível obter o QR Code. Verifique se a instância existe.', 'roket-crm' ) ) );
        }
    }
}
