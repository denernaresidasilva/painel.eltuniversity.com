<?php
/**
 * Email integration — templates, sending, tracking, unsubscribe.
 *
 * @package LC_CRM_Automation
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
        // Rate limiting: max 3 emails per contact in 1 hour.
        if ( ! self::allow_send( (int) $message->contact_id ) ) {
            return false;
        }

        $contact = WPLA_Contact::get( (int) $message->contact_id );
        $to      = $message->recipient;
        $subject = $message->subject;

        // If a template_id is stored in the queue body (JSON), use it.
        $body = $message->body;
        if ( (int) $message->template_id > 0 ) {
            $tpl = WPLA_Email_Template::get( (int) $message->template_id );
            if ( $tpl ) {
                if ( empty( $subject ) ) {
                    $subject = $tpl->subject;
                }
                $body = $tpl->body;
            }
        }

        // Render merge fields.
        $body    = WPLA_Email_Template::render( $body, $contact );
        $subject = $contact ? WPLA_Email_Template::render( $subject, $contact ) : $subject;

        // Add unsubscribe link if not already present.
        if ( $contact && strpos( $body, 'wpla_unsub' ) === false ) {
            $unsub_url  = add_query_arg( array(
                'wpla_unsub' => '1',
                'email'      => rawurlencode( $contact->email ?? '' ),
                'token'      => WPLA_Email_Template::unsubscribe_token( $contact->email ?? '' ),
            ), home_url( '/' ) );
            $body .= '<p style="font-size:11px;color:#9ca3af;text-align:center;margin-top:30px;">'
                   . '<a href="' . esc_url( $unsub_url ) . '" style="color:#9ca3af;">'
                   . esc_html__( 'Cancelar inscrição', 'lc-crm' )
                   . '</a></p>';
        }

        // Add tracking pixel.
        $tracking_url = add_query_arg( array(
            'wpla_track' => 'open',
            'tid'        => $message->tracking_id,
        ), home_url( '/' ) );
        $body .= '<img src="' . esc_url( $tracking_url ) . '" width="1" height="1" alt="" style="display:none" />';

        // Replace links with tracking links.
        $body = self::add_click_tracking( $body, $message->tracking_id );

        // Wrap in the default email template.
        ob_start();
        $template_file = WPLA_PLUGIN_DIR . 'templates/emails/default.php';
        if ( file_exists( $template_file ) ) {
            include $template_file;
        } else {
            echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
        $html = ob_get_clean();

        $headers = array( 'Content-Type: text/html; charset=UTF-8' );

        $from_name  = get_option( 'wpla_email_from_name', get_bloginfo( 'name' ) );
        $from_email = get_option( 'wpla_email_from_address', get_bloginfo( 'admin_email' ) );
        $headers[]  = "From: {$from_name} <{$from_email}>";

        $result = wp_mail( $to, $subject, $html, $headers );

        if ( $result ) {
            // Update contact email fields.
            self::update_contact_email_meta( (int) $message->contact_id, 'sent' );
        }

        return $result;
    }

    /**
     * Rate limit: allow at most 3 emails per contact per hour.
     */
    private static function allow_send( int $contact_id ): bool {
        $max_per_hour = (int) get_option( 'wpla_email_rate_limit', 3 );
        if ( $max_per_hour <= 0 ) {
            return true; // No limit configured.
        }
        $trans_key = 'wpla_email_rate_' . $contact_id;
        $count     = (int) get_transient( $trans_key );
        if ( $count >= $max_per_hour ) {
            return false;
        }
        set_transient( $trans_key, $count + 1, HOUR_IN_SECONDS );
        return true;
    }

    /**
     * Update contact email engagement fields.
     *
     * @param int    $contact_id Contact ID.
     * @param string $event      'sent', 'opened', or 'clicked'.
     */
    public static function update_contact_email_meta( int $contact_id, string $event ): void {
        global $wpdb;
        $table = WPLA_Database::table( 'contacts' );
        $now   = current_time( 'mysql' );

        switch ( $event ) {
            case 'sent':
                $wpdb->query( $wpdb->prepare(
                    "UPDATE $table SET last_email_sent = %s WHERE id = %d",
                    $now, $contact_id
                ) );
                break;
            case 'opened':
                $wpdb->query( $wpdb->prepare(
                    "UPDATE $table SET last_email_opened_at = %s WHERE id = %d",
                    $now, $contact_id
                ) );
                break;
            case 'clicked':
                $wpdb->query( $wpdb->prepare(
                    "UPDATE $table SET last_email_clicked_at = %s WHERE id = %d",
                    $now, $contact_id
                ) );
                break;
        }
    }

    /**
     * Render template with contact merge fields.
     *
     * @deprecated Use WPLA_Email_Template::render() instead.
     */
    public static function render_template( string $body, ?object $contact ): string {
        return WPLA_Email_Template::render( $body, $contact );
    }

    /**
     * Add click tracking to links in the email body.
     */
    private static function add_click_tracking( string $body, string $tracking_id ): string {
        return preg_replace_callback(
            '/<a\s([^>]*?)href=["\']([^"\']+)["\']/',
            function ( $matches ) use ( $tracking_id ) {
                $url = $matches[2];
                // Don't track unsubscribe / existing tracking links.
                if ( strpos( $url, 'wpla_track' ) !== false || strpos( $url, 'wpla_unsub' ) !== false ) {
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
     * Configure PHPMailer with SMTP settings when configured.
     *
     * @param \PHPMailer\PHPMailer\PHPMailer $phpmailer PHPMailer instance.
     */
    public static function configure_smtp( $phpmailer ): void {
        $host = get_option( 'wpla_smtp_host', '' );

        if ( empty( $host ) ) {
            return; // No SMTP configured — use WordPress default.
        }

        $port       = (int) get_option( 'wpla_smtp_port', 587 );
        $encryption = get_option( 'wpla_smtp_encryption', 'tls' );
        $auth       = (bool) get_option( 'wpla_smtp_auth', '1' );
        $user       = get_option( 'wpla_smtp_user', '' );
        $pass       = get_option( 'wpla_smtp_pass', '' );
        $from_name  = get_option( 'wpla_email_from_name', get_bloginfo( 'name' ) );
        $from_email = get_option( 'wpla_email_from_address', get_bloginfo( 'admin_email' ) );

        $phpmailer->isSMTP();
        $phpmailer->Host       = $host;
        $phpmailer->Port       = $port;
        $phpmailer->SMTPAuth   = $auth;
        $phpmailer->Username   = $user;
        $phpmailer->Password   = $pass;
        $phpmailer->FromName   = $from_name;
        $phpmailer->From       = $from_email;

        if ( 'ssl' === $encryption ) {
            $phpmailer->SMTPSecure = 'ssl';
        } elseif ( 'tls' === $encryption ) {
            $phpmailer->SMTPSecure = 'tls';
        } else {
            $phpmailer->SMTPSecure = '';
            $phpmailer->SMTPAutoTLS = false;
        }
    }

    /**
     * Handle tracking requests (open / click) and unsubscribe. Hooked to template_redirect.
     */
    public static function handle_tracking(): void {
        // Handle unsubscribe request.
        if ( isset( $_GET['wpla_unsub'] ) && isset( $_GET['email'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
            $email = sanitize_email( rawurldecode( wp_unslash( $_GET['email'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification
            $token = sanitize_text_field( wp_unslash( $_GET['token'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification

            if ( is_email( $email ) && WPLA_Email_Template::verify_unsubscribe_token( $email, $token ) ) {
                // Rate limit unsubscribe processing: max 5 requests per IP per minute.
                $ip         = sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' );
                $rate_key   = 'wpla_unsub_rate_' . md5( $ip );
                $rate_count = (int) get_transient( $rate_key );
                if ( $rate_count < 5 ) {
                    set_transient( $rate_key, $rate_count + 1, MINUTE_IN_SECONDS );

                    $contact = WPLA_Contact::get_by_email( $email );
                    if ( $contact ) {
                        // Mark contact as unsubscribed.
                        global $wpdb;
                        $wpdb->update(
                            WPLA_Database::table( 'contacts' ),
                            array(
                                'email_status' => 'unsubscribed',
                                'status'       => 'unsubscribed',
                            ),
                            array( 'id' => (int) $contact->id ),
                            array( '%s', '%s' ),
                            array( '%d' )
                        );
                        do_action( 'wpla_event', 'email_unsubscribed', (int) $contact->id, array( 'email' => $email ) );
                    }
                }
            }

            // Show a simple confirmation page.
            wp_die(
                esc_html__( 'Você foi descadastrado com sucesso.', 'lc-crm' ),
                esc_html__( 'Descadastrado', 'lc-crm' ),
                array( 'response' => 200 )
            );
        }

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
            // Update status to 'opened' for sent or still-processing messages (timing race).
            if ( in_array( $message->status, array( 'sent', 'processing' ), true ) ) {
                $wpdb->update( $table, array( 'status' => 'opened' ), array( 'id' => $message->id ), array( '%s' ), array( '%d' ) );
            }
            do_action( 'wpla_event', 'email_opened', (int) $message->contact_id, array( 'tracking_id' => $tracking_id ) );
            self::update_contact_email_meta( (int) $message->contact_id, 'opened' );

            // Return a 1x1 transparent GIF.
            header( 'Content-Type: image/gif' );
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo base64_decode( 'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7' );
            exit;
        }

        if ( 'click' === $type && isset( $_GET['url'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
            $url = esc_url_raw( rawurldecode( wp_unslash( $_GET['url'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification

            // Validate that the URL uses an allowed scheme to prevent javascript: or data: redirects.
            $parsed = wp_parse_url( $url );
            if ( ! $parsed || ! isset( $parsed['scheme'] ) || ! in_array( $parsed['scheme'], array( 'http', 'https' ), true ) ) {
                $url = home_url();
            }

            do_action( 'wpla_event', 'link_clicked', (int) $message->contact_id, array(
                'tracking_id' => $tracking_id,
                'url'         => $url,
            ) );
            self::update_contact_email_meta( (int) $message->contact_id, 'clicked' );

            wp_redirect( $url ); // phpcs:ignore WordPress.Security.SafeRedirect.wp_redirect_wp_redirect -- External URL redirect for email click tracking.
            exit;
        }
    }

    /**
     * Get email engagement statistics.
     *
     * @param string $since  Optional MySQL datetime string for the earliest date.
     * @return array
     */
    public static function get_stats( string $since = '' ): array {
        global $wpdb;
        $table = WPLA_Database::table( 'message_queue' );

        $where = $since ? $wpdb->prepare( "AND created_at >= %s", $since ) : '';

        $rows = $wpdb->get_results(
            "SELECT status, COUNT(*) as cnt FROM $table WHERE channel = 'email' $where GROUP BY status" // phpcs:ignore WordPress.DB.PreparedSQL
        );

        $stats = array(
            'sent'       => 0,
            'opened'     => 0,
            'clicked'    => 0,
            'failed'     => 0,
            'pending'    => 0,
            'unsubscribed' => 0,
        );

        foreach ( $rows as $row ) {
            if ( isset( $stats[ $row->status ] ) ) {
                $stats[ $row->status ] = (int) $row->cnt;
            }
        }

        // Unsubscribed contacts.
        $contacts_table       = WPLA_Database::table( 'contacts' );
        $stats['unsubscribed'] = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM $contacts_table WHERE email_status = 'unsubscribed'" // phpcs:ignore WordPress.DB.PreparedSQL
        );

        return $stats;
    }
}

// Hook tracking/unsubscribe handler.
add_action( 'template_redirect', array( 'WPLA_Email', 'handle_tracking' ), 1 );

// Configure SMTP via PHPMailer when settings are present.
add_action( 'phpmailer_init', array( 'WPLA_Email', 'configure_smtp' ) );
