<?php
/**
 * WhatsApp integration — supports Evolution API and Meta Cloud API.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_WhatsApp {

    /**
     * Send a WhatsApp message using the configured provider.
     *
     * @param object $message Queue message row.
     * @return bool
     */
    public static function send( object $message ): bool {
        $provider = get_option( 'wpla_whatsapp_provider', 'evolution' );

        switch ( $provider ) {
            case 'meta':
                return self::send_via_meta( $message );
            case 'evolution':
            default:
                return self::send_via_evolution( $message );
        }
    }

    /**
     * Helper function for plugins/themes.
     */
    public static function send_message( int $contact_id, string $message_text ): int {
        $contact = WPLA_Contact::get( $contact_id );
        if ( ! $contact || empty( $contact->phone ) ) {
            return 0;
        }

        return WPLA_Message_Queue::enqueue( $contact_id, 'whatsapp', $contact->phone, '', $message_text );
    }

    /**
     * Send via Evolution API.
     */
    private static function send_via_evolution( object $message ): bool {
        $api_url  = get_option( 'wpla_evolution_api_url', '' );
        $api_key  = get_option( 'wpla_evolution_api_key', '' );
        $instance = get_option( 'wpla_evolution_instance', '' );

        if ( empty( $api_url ) || empty( $api_key ) || empty( $instance ) ) {
            return false;
        }

        $phone = preg_replace( '/[^0-9]/', '', $message->recipient );

        $response = wp_remote_post(
            trailingslashit( $api_url ) . "message/sendText/{$instance}",
            array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'apikey'       => $api_key,
                ),
                'body'    => wp_json_encode( array(
                    'number' => $phone,
                    'text'   => $message->body,
                ) ),
                'timeout' => 30,
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $code = wp_remote_retrieve_response_code( $response );
        return $code >= 200 && $code < 300;
    }

    /**
     * Send via Meta WhatsApp Cloud API.
     */
    private static function send_via_meta( object $message ): bool {
        $token    = get_option( 'wpla_meta_whatsapp_token', '' );
        $phone_id = get_option( 'wpla_meta_phone_number_id', '' );

        if ( empty( $token ) || empty( $phone_id ) ) {
            return false;
        }

        $phone = preg_replace( '/[^0-9]/', '', $message->recipient );

        $response = wp_remote_post(
            "https://graph.facebook.com/v18.0/{$phone_id}/messages",
            array(
                'headers' => array(
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $token,
                ),
                'body'    => wp_json_encode( array(
                    'messaging_product' => 'whatsapp',
                    'to'                => $phone,
                    'type'              => 'text',
                    'text'              => array( 'body' => $message->body ),
                ) ),
                'timeout' => 30,
            )
        );

        if ( is_wp_error( $response ) ) {
            return false;
        }

        $code = wp_remote_retrieve_response_code( $response );
        return $code >= 200 && $code < 300;
    }
}

/**
 * Global helper function.
 */
function wpla_send_whatsapp( int $contact_id, string $message ): int {
    return WPLA_WhatsApp::send_message( $contact_id, $message );
}
