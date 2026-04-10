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
            'wpla-dashboard'   => __( 'Dashboard', 'roket-crm' ),
            'wpla-contacts'    => __( 'Contacts', 'roket-crm' ),
            'wpla-lists'       => __( 'Lists', 'roket-crm' ),
            'wpla-tags'        => __( 'Tags', 'roket-crm' ),
            'wpla-automations' => __( 'Automations', 'roket-crm' ),
            'wpla-forms'       => __( 'Forms', 'roket-crm' ),
            'wpla-whatsapp'    => __( 'WhatsApp', 'roket-crm' ),
            'wpla-email'       => __( 'Email', 'roket-crm' ),
            'wpla-webhooks'    => __( 'Webhooks', 'roket-crm' ),
            'wpla-settings'    => __( 'Settings', 'roket-crm' ),
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
        );

        foreach ( $fields as $field ) {
            if ( isset( $_POST[ $field ] ) ) {
                update_option( $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
            }
        }

        // Regenerate API key if requested.
        if ( ! empty( $_POST['regenerate_api_key'] ) ) {
            update_option( 'wpla_api_key', wp_generate_password( 40, false ) );
        }

        wp_send_json_success( array(
            'message' => __( 'Settings saved successfully.', 'roket-crm' ),
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
        $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : 'Test Email from Roket CRM';
        $body    = isset( $_POST['body'] ) ? wp_kses_post( wp_unslash( $_POST['body'] ) ) : '<p>This is a test email.</p>';

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );
        $result  = wp_mail( $to, $subject, $body, $headers );

        wp_send_json_success( array( 'sent' => $result ) );
    }

    public function wpla_send_test_whatsapp(): void {
        $this->verify_nonce();

        $phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
        $message = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : 'Test from Roket CRM';

        // Create a mock message object.
        $msg = (object) array(
            'recipient' => $phone,
            'body'      => $message,
        );

        $result = WPLA_WhatsApp::send( $msg );

        wp_send_json_success( array( 'sent' => $result ) );
    }
}
