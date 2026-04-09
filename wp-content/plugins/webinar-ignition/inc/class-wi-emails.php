<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WI_Emails {


	/**
	 * The single instance of the class
	 *
	 * @var WI_Emails
	 */
	protected static $instance = null;


	/**
	 * Main WI_Emails Instance.
	 *
	 * Ensures only one instance of WI_Emails is loaded or can be loaded.
	 *
	 * @static
	 * @return WI_Emails Main instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function init_hooks() {
		add_action( 'admin_init', array( $this, 'webinarignition_preview_email' ) );
		add_action( 'webinarignition_email_header', array( $this, 'webinarignition_email_header' ) );
		add_action( 'webinarignition_email_footer', array( $this, 'webinarignition_email_footer' ), 20, 2 );
		add_filter( 'webinarignition_email_footer_text', array( $this, 'webinarignition_replace_email_template_placeholders' ), 10, 2 );
	}

	/**
	 * Constructor for the email class hooks in all emails that can be sent.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	public function webinarignition_replace_email_template_placeholders( $text ) {
		$privacy_policy_link    = get_privacy_policy_url();
		$privacy_policy         = '<a href="' . $privacy_policy_link . '" target="_blank">' . __( 'Privacy Policy', 'webinar-ignition' ) . '</a>';
		$imprint_page           = wi_get_page_by_title( 'Imprint' );
		if ( empty( $imprint_page ) ) {
			$imprint_page = wi_get_page_by_title( 'Impressum' );
		}
		$imprint_page_url       = ! empty( $imprint_page ) ? get_permalink( $imprint_page->ID ) : '';
		$imprint_page_title     = ! empty( $imprint_page ) ? $imprint_page->post_title : __( 'Imprint', 'webinar-ignition' );
		$imprint_page_link      = '<a href="' . $imprint_page_url . '" target="_blank">' .  $imprint_page_title . '</a>';

		$domain = wp_parse_url( home_url(), PHP_URL_HOST );

		return str_replace(
			array(
				'{site_title}',
				'{year}',
				'{site_description}',
				'{privacy_policy}',
				'{imprint}',
			),
			array(
				get_bloginfo( 'name' ),
				gmdate( 'Y' ),
				get_bloginfo( 'description' ),
				$privacy_policy,
				$imprint_page_link,
			),
			$text
		);
	}

	public function webinarignition_email_header( $email_data ) {//phpcs:ignore
		include_once WEBINARIGNITION_PATH . 'templates/emails/email-header.php';
	}

	public function webinarignition_email_footer( $email_data ) {//phpcs:ignore
		$show_webinarignition_footer_logo = get_option( 'show_webinarignition_footer_logo' );

		include_once WEBINARIGNITION_PATH . 'templates/emails/email-footer.php';
	}

	/**
	 * Wraps a message in the mail template
	 *
	 * @param obj $email_data The email data object.
	 *
	 * @return string
	 */
	public function webinarignition_wrap_email_content( $email_data ) {
		$webinarignition_enable_header_img_max_width    = get_option( 'webinarignition_enable_header_img_max_width', 'yes' );
		$webinarignition_email_logo_max_width           = get_option( 'webinarignition_email_logo_max_width', 265 );
		$email_data->max_width_css                      = ! empty( $webinarignition_enable_header_img_max_width ) ? 'max-width:' . $webinarignition_email_logo_max_width . 'px;' : '';
		$show_webinarignition_footer_logo               = get_option( 'show_webinarignition_footer_logo' );

		// Buffer.
		ob_start();

		include_once WEBINARIGNITION_PATH . 'templates/emails/email-header.php';

		echo wp_kses_post( wpautop( wptexturize( $email_data->bodyContent ) ) );

		include_once WEBINARIGNITION_PATH . 'templates/emails/email-footer.php';

		// Get contents.
		$body_content = ob_get_clean();

		return $body_content;
	}


	/**
	 * Apply inline styles to dynamic content.
	 *
	 * @param mixed $content The content.
	 */
	public function webinarignition_style_inline( $content ) {
		include_once WEBINARIGNITION_PATH . 'templates/emails/email-styles.php';
		ob_start();
		$css = apply_filters( 'webinarignition_email_styles', ob_get_clean() );

		$emogrifier_class = 'Pelago\\Emogrifier';

		if ( class_exists( 'DOMDocument' ) && class_exists( $emogrifier_class ) ) {
			$emogrifier = new $emogrifier_class( $content, $css );

			$content    = $emogrifier->emogrify();

			$html_prune = \Pelago\Emogrifier\HtmlProcessor\HtmlPruner::fromHtml( $content );
			$html_prune->removeElementsWithDisplayNone();
			$content    = $html_prune->render();
		} else {
			$content = '<style type="text/css">' . $css . '</style>' . $content;
		}

		return $content;
	}

	public function webinarignition_build_email( $email_data ) {

		ob_start();
		$webinarignition_enable_header_img_max_width    = get_option( 'webinarignition_enable_header_img_max_width', 'yes' );
		$webinarignition_email_logo_max_width           = get_option( 'webinarignition_email_logo_max_width', 265 );
		$email_data->max_width_css                      = ! empty( $webinarignition_enable_header_img_max_width ) ? 'max-width:' . $webinarignition_email_logo_max_width . 'px;' : '';
		$show_webinarignition_footer_logo               = get_option( 'show_webinarignition_footer_logo' );

		include WEBINARIGNITION_PATH . 'templates/emails/email-header.php';

		echo wp_kses_post( wpautop( wptexturize( $email_data->bodyContent ) ) );

		include WEBINARIGNITION_PATH . 'templates/emails/email-footer.php';

		// Get contents.
		$content = ob_get_clean();

		ob_start();

		include WEBINARIGNITION_PATH . 'templates/emails/email-styles.php';
		$css = apply_filters( 'webinarignition_email_styles', ob_get_clean() );

		$emogrifier_class = 'Pelago\\Emogrifier';

		if ( class_exists( 'DOMDocument' ) && class_exists( $emogrifier_class ) ) {
			$emogrifier = new $emogrifier_class( $content, $css );
			$content    = $emogrifier->emogrify();
			$html_prune = \Pelago\Emogrifier\HtmlProcessor\HtmlPruner::fromHtml( $content );
			$html_prune->removeElementsWithDisplayNone();
			$content    = $html_prune->render();
		} else {
			$content = '<style type="text/css">' . $css . '</style>' . $content;
		}

		return $content;
	}

	
	public function webinarignition_preview_email() {
		if ( isset( $_GET['preview-webinarignition-template'] ) ) {
			if ( ! ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'preview-mail' ) ) ) {
				die( 'Security check' );
			}

			ob_start();
			include_once WEBINARIGNITION_PATH . 'templates/emails/html-email-template-preview.php';
			$bodyContent = ob_get_clean();

			$email_data                   = new stdClass();
			$email_data->email_subject    = __( 'Email Message Subject', 'webinar-ignition' );
			$email_data->emailheading     = __( 'Sign Up Email Heading Text', 'webinar-ignition' );
			$email_data->emailpreview     = __( 'Sign Up Email Preview Text...', 'webinar-ignition' );
			$email_data->bodyContent      = $bodyContent;

			$bodyContent = $this->webinarignition_style_inline( $this->webinarignition_wrap_email_content( $email_data ) );
			// Add Unsubscribe Text
			$unsubscribe_text = '<p style="text-align: center; font-size: 12px; color: #666; margin-top: 5px;">
			<a href="{SUBLINK}&unsubnextwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe next webinar', 'webinar-ignition' ) . '</a> | 
			<a href="{SUBLINK}&unsuballwebinar=true" style="color: #666; text-decoration: underline;">' . __( 'Unsubscribe all these webinars', 'webinar-ignition' ) . '</a>
			</p>';

			$webinarignition_unsubscribe_links = absint( get_option( 'webinarignition_unsubscribe_links', 1 ) );

			// Check if the setting value is 1
			if (1 === absint($webinarignition_unsubscribe_links)) {
			$bodyContent .= $unsubscribe_text;
			}
			echo wp_kses_post( $bodyContent );
			exit;
		}
	}

	public function webinarignition_set_wp_mail_from( $phpmailer ) {
		$name                = get_option( 'webinarignition_smtp_name' );
		$email               = get_option( 'webinarignition_smtp_email' );
		$reply_to_email      = get_option( 'webinarignition_reply_to_email' );

		if ( ! empty( $email ) && ! empty( $name ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$phpmailer->setFrom( $email, $name );
		} else {
			$phpmailer->setFrom( get_bloginfo( 'admin_email' ), get_bloginfo( 'name' ) );
		}

		if ( ! empty( $reply_to_email ) ) {
			$phpmailer->addReplyTo( $reply_to_email, $name );
		}
	}

	public function webinarignition_phpmailer_smtp_config( $phpmailer ) {
		$host                = get_option( 'webinarignition_smtp_host' );
		$port                = get_option( 'webinarignition_smtp_port' );
		$protocol            = get_option( 'webinarignition_smtp_protocol' );
		$user                = get_option( 'webinarignition_smtp_user' );
		$pass                = get_option( 'webinarignition_smtp_pass' );
		$can_use_smtp        = (int) get_option( 'webinarignition_smtp_connect' );

		if ( ! empty( $can_use_smtp ) && ! empty( $host ) && ! empty( $port ) && ! empty( $protocol ) && ! empty( $user ) && ! empty( $pass ) ) {

			$phpmailer->IsSMTP();
			//phpcs:disable
			$phpmailer->Host = $host;
			$phpmailer->Port = $port;

			$phpmailer->SMTPAuth   = true;
			$phpmailer->Username   = $user;
			$phpmailer->Password   = $pass;
			$phpmailer->SMTPSecure = $protocol;
			//phpcs:enable
		}
	}
}

return new WI_Emails();
