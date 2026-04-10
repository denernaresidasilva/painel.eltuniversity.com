<?php
/**
 * Email integration — templates, sending, tracking.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class WPLA_Email {

    /**
     * Send an email from the queue.
     *
     * @param object $message Queue message row.
     * @return bool
     */
    public static function send( object $message ): bool {
        $contact = WPLA_Contact::get( (int) $message->contact_id );
        $to      = $message->recipient;
        $subject = $message->subject;
        $body    = self::render_template( $message->body, $contact );

        // Add tracking pixel.
        $tracking_url = add_query_arg( array(
            'wpla_track' => 'open',
            'tid'        => $message->tracking_id,
        ), home_url( '/' ) );

        $body .= '<img src="' . esc_url( $tracking_url ) . '" width="1" height="1" alt="" style="display:none" />';

        // Replace links with tracking links.
        $body = self::add_click_tracking( $body, $message->tracking_id );

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $from_name  = get_option( 'wpla_email_from_name', get_bloginfo( 'name' ) );
        $from_email = get_option( 'wpla_email_from_address', get_bloginfo( 'admin_email' ) );
        $headers[]  = "From: {$from_name} <{$from_email}>";

        return wp_mail( $to, $subject, $body, $headers );
    }

    /**
     * Render template with contact merge fields.
     */
    public static function render_template( string $body, ?object $contact ): string {
        if ( ! $contact ) {
            return $body;
        }

        $replacements = array(
            '{{first_name}}' => $contact->first_name ?? '',
            '{{last_name}}'  => $contact->last_name ?? '',
            '{{email}}'      => $contact->email ?? '',
            '{{phone}}'      => $contact->phone ?? '',
            '{{company}}'    => $contact->company ?? '',
        );

        $body = str_replace( array_keys( $replacements ), array_values( $replacements ), $body );

        // Wrap in template.
        ob_start();
        $template_file = WPLA_PLUGIN_DIR . 'templates/emails/default.php';
        if ( file_exists( $template_file ) ) {
            include $template_file;
        } else {
            echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        return ob_get_clean();
    }

    /**
     * Add click tracking to links in the email body.
     */
    private static function add_click_tracking( string $body, string $tracking_id ): string {
        return preg_replace_callback(
            '/<a\s([^>]*?)href=["\']([^"\']+)["\']/',
            function ( $matches ) use ( $tracking_id ) {
                $url = $matches[2];
                // Don't track unsubscribe / tracking links.
                if ( strpos( $url, 'wpla_track' ) !== false ) {
                    return $matches[0];
                }
                $tracked = add_query_arg( array(
                    'wpla_track' => 'click',
                    'tid'        => $tracking_id,
                    'url'        => rawurlencode( $url ),
                ), home_url( '/' ) );
                return '<a ' . $matches[1] . 'href="' . esc_url( $tracked ) . '"';
            },
            $body
        );
    }

    /**
     * Handle tracking requests (open / click). Hooked to template_redirect.
     */
    public static function handle_tracking(): void {
        if ( ! isset( $_GET['wpla_track'], $_GET['tid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
            return;
        }

        $type        = sanitize_text_field( wp_unslash( $_GET['wpla_track'] ) ); // phpcs:ignore WordPress.Security.NonceVerification
        $tracking_id = sanitize_text_field( wp_unslash( $_GET['tid'] ) ); // phpcs:ignore WordPress.Security.NonceVerification

        global $wpdb;
        $table = WPLA_Database::table( 'message_queue' );

        $message = $wpdb->get_row( $wpdb->prepare(
            "SELECT * FROM $table WHERE tracking_id = %s",
            $tracking_id
        ) );

        if ( ! $message ) {
            return;
        }

        if ( 'open' === $type ) {
            do_action( 'wpla_event', 'email_opened', (int) $message->contact_id, array( 'tracking_id' => $tracking_id ) );

            // Return a 1x1 transparent GIF.
            header( 'Content-Type: image/gif' );
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo base64_decode( 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' );
            exit;
        }

        if ( 'click' === $type && isset( $_GET['url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
            $url = esc_url_raw( rawurldecode( wp_unslash( $_GET['url'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
            do_action( 'wpla_event', 'link_clicked', (int) $message->contact_id, array(
                'tracking_id' => $tracking_id,
                'url'         => $url,
            ) );

            wp_redirect( $url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect
            exit;
        }
    }
}

// Hook tracking handler.
add_action( 'template_redirect', array( 'WPLA_Email', 'handle_tracking' ), 1 );
