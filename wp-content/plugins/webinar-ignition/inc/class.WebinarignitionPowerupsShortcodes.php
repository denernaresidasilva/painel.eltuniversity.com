<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WebinarignitionPowerupsShortcodes {
	private static function webinarignition_get_renamed_shortcodes() {
		return array(
			'old_shortcode_block' => 'new_shortcode_block',
			'ty_message_area' => 'ty_video_area',
		);
	}

	public static function webinarignition_get_available_shortcodes() {
		return array(
			'global_webinar_title' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'global',
				'cb' => 'webinarignition_get_webinar_title',
				'description' => 'global-lead-name',
			),
			'global_webinar_giveaway' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'global',
				'cb' => 'webinarignition_get_webinar_giveaway_compact',
			),
			'global_host_name' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'global',
				'cb' => 'webinarignition_get_host_name',
			),
			'global_lead_name' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'global',
				'cb' => 'webinarignition_get_lead_name',
			),
			'global_lead_email' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'global',
				'cb' => 'webinarignition_get_lead_email',
			),
			'reg_banner' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_banner_short',
				'description' => 'reg-banner',
			),
			'reg_main_headline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_main_headline',
				'description' => 'reg-main-headline',
			),
			'reg_video_area' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_video_area',
				'description' => 'reg-video-area',
			),
			'reg_host_info' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_host_info',
				'description' => 'reg-host-info',
			),
			'reg_sales_headline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_sales_headline',
				'description' => 'reg-sales-headline',
			),
			'reg_sales_copy' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_sales_copy',
				'description' => 'reg-sales-copy',
			),
			'reg_optin_headline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_optin_headline',
			),
			'reg_optin_dates' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_event_dates',
			),
			'reg_optin_dates_compact' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_event_dates_compact',
			),
			'reg_date_time_inline' => array(
				'type' => array( 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_date_time_inline',
			),
			'reg_date_inline' => array(
				'type' => array( 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_date_inline',
			),
			'reg_time_inline' => array(
				'type' => array( 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_time_inline',
			),
			'reg_timezone_inline' => array(
				'type' => array( 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_timezone_inline',
			),
			'reg_optin_form' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_optin_form',
				'description' => 'reg-optin-form',
			),
			'reg_optin_form_compact' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_optin_form_compact',
			),
			'reg_optin_section' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'registration',
				'cb' => 'webinarignition_get_lp_optin_section',
			),

			// Thankyou page
			'ty_headline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_headline',
				'description' => 'ty-headline',
			),
			'ty_video_area' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_message_area',
				'description' => 'ty-message-area',
			),
			'ty_webinar_url' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_webinar_url',
				'description' => 'ty-webinar-url',
			),
			'ty_webinar_url_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_webinar_url_inline',
			),
			'ty_calendar_reminder' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_calendar_reminder',
				'description' => 'ty-calendar-reminder',
			),
			'ty_calendar_reminder_google_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_calendar_reminder_google',
			),
			'ty_calendar_reminder_outlook_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_calendar_reminder_outlook',
			),
			'ty_sms_reminder' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_sms_reminder',
				'description' => 'ty-sms-reminder',
			),
			'ty_sms_reminder_compact' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_sms_reminder_compact',
			),
			'ty_share_gift' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_share_gift',
				'description' => 'ty-share-gift',
			),
			'ty_share_gift_compact' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_share_gift_compact',
			),
			'ty_ticket_date' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_ticket_date',
				'description' => 'ty-ticket-date',
			),
			'ty_date_time_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_date_time_inline',
			),
			'ty_date_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_date_inline',
			),
			'ty_time_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_time_inline',
			),
			'ty_timezone_inline' => array(
				'type' => array( 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_timezone_inline',
			),
			'ty_ticket_webinar' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_ticket_webinar',
				'description' => 'ty-ticket-webinar',
			),
			'ty_ticket_webinar_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_ticket_webinar_inline',
			),
			'ty_ticket_host' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_ticket_host',
				'description' => 'ty-ticket-host',
			),
			'ty_ticket_host_inline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_ticket_host_inline',
			),
			'ty_countdown' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'thankyou',
				'cb' => 'webinarignition_get_ty_countdown',
				'description' => 'ty-countdown',
			),
			// 'ty_countdown_compact' => array(
			// 'type' => array('evergreen', 'live'),
			// 'page' => 'thankyou',
			// 'cb' => 'webinarignition_get_ty_countdown_compact',
			// ),

				// Replay page
				'replay_main_headline' => array(
					'type' => array( 'evergreen', 'live' ),
					'page' => 'replay',
					'cb' => 'webinarignition_get_replay_main_headline',
					'description' => 'replay-main-headline',
				),
			'replay_video' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'replay',
				'cb' => 'webinarignition_get_replay_video',
				'description' => 'replay-video',
			),
			'replay_cta' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'replay',
				'cb' => 'webinarignition_get_replay_video_under_cta',
				'description' => 'replay-cta',
			),
			'replay_info' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'replay',
				'cb' => 'webinarignition_get_replay_info',
				'description' => 'replay-info',
			),
			'replay_giveaway' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'replay',
				'cb' => 'webinarignition_get_replay_giveaway',
				'description' => 'replay-giveaway',
			),

			// Countdown page
			'countdown_headline' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'countdown',
				'cb' => 'webinarignition_get_countdown_headline',
				'description' => 'countdown-headline',
			),
			'countdown_counter' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'countdown',
				'cb' => 'webinarignition_get_countdown_counter',
				'description' => 'countdown-counter',
			),
			'countdown_signup' => array(
				'type' => array( 'live' ),
				'page' => 'countdown',
				'cb' => 'webinarignition_get_countdown_signup',
				'description' => 'countdown-signup',
			),

			// Webinar page
			'webinar_video_cta' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_video_cta_comb',
				'description' => 'webinar-video-cta-comb',
			),
			'webinar_sidebar' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_sidebar',
				'description' => 'sidebar',
			),
			// webinar video and sidebar together
			'webinar_video_cta_sidebar' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_video_cta_sidebar_combine',
				'description' => 'webinar-video-cta-sidebar',
			),
			'webinar_video' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_video_cta',
				'description' => 'webinar-video-cta',
			),
			'webinar_cta' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_video_under_cta',
				'description' => 'webinar-cta',
			),
			'webinar_info' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_info',
				'description' => 'webinar-info',
			),
			'webinar_giveaway' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_giveaway',
				'description' => 'webinar-giveaway',
			),
			'webinar_giveaway_compact' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_giveaway_compact',
			),
			'webinar_qa' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_qa',
				'description' => 'webinar-qa',
			),
			'webinar_qa_compact' => array(
				'type' => array( 'evergreen', 'live' ),
				'page' => 'webinar',
				'cb' => 'webinarignition_get_webinar_qa_compact',
				'description' => 'webinar-qa-compact',
			),
		);
	}

	public static function webinarignition_get_available_templates() {
		return array(
			'custom_registration_page' => array(
				'title' => __( 'Select Registration page template', 'webinar-ignition' ),
				'params' => '',
			),
			'custom_thankyou_page' => array(
				'title' => __( 'Select Thank You page template', 'webinar-ignition' ),
				'params' => 'thankyou&lid=[lead_id]',
			),
			'custom_webinar_page' => array(
				'title' => __( 'Select Webinar page template', 'webinar-ignition' ),
				'params' => 'webinar&lid=[lead_id]',
			),
			'custom_countdown_page' => array(
				'title' => __( 'Select Webinar Countdown page template', 'webinar-ignition' ),
				'params' => 'countdown&lid=[lead_id]',
			),
			'custom_replay_page' => array(
				'title' => __( 'Select Webinar Replay page template', 'webinar-ignition' ),
				'params' => 'replay&lid=[lead_id]',
			),
		);
	}

	public static function webinarignition_is_enabled( $webinar_data ) {
		return WebinarignitionPowerups::webinarignition_is_shortcodes_enabled( $webinar_data );
	}

	public static function init() {
		add_shortcode( 'wi_webinar_block', array( 'WebinarignitionPowerupsShortcodes', 'webinarignition_shortcode' ) );
		add_shortcode( 'webinarignition_footer', array( 'WebinarignitionPowerupsShortcodes', 'webinar_ignition_footer_sc' ) );
	}

	public static function webinarignition_show_shortcode_description( $block, $webinar_data, $display = true, $two_col = false ) {
		if ( ! self::webinarignition_is_enabled( $webinar_data ) ) {
			$html = '';
		} else {
			$renamed_shortcodes = self::webinarignition_get_renamed_shortcodes();

			if ( ! empty( $renamed_shortcodes[ $block ] ) ) {
				$block = $renamed_shortcodes[ $block ];
			}

			$template_array = self::webinarignition_get_available_shortcodes();

			$webinar_type = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';
			$html = '';

			if (
				empty( $template_array[ $block ] ) ||
				! in_array( $webinar_type, $template_array[ $block ]['type'], true ) ||
				empty( $template_array[ $block ]['description'] )
			) {
				$html = '';
			} elseif ( $two_col ) {
					ob_start();
					webinarignition_display_two_col_info(
						__( 'Available shortcode', 'webinar-ignition' ),
						'',
						self::webinarignition_get_shortcode_description( $template_array[ $block ]['description'], $webinar_data )
					);
					$html = ob_get_clean();
			} else {
				$html = wpautop( self::webinarignition_get_shortcode_description( $template_array[ $block ]['description'], $webinar_data ) );
			}
		}//end if

		if ( ! $display ) {
			return $html;
		}
		echo wp_kses_post( $html );
	}

	public static function webinarignition_get_shortcode_description( $path, $webinar_data ) {//phpcs:ignore

		if ( ! file_exists( WEBINARIGNITION_PATH . "UI/shortcodes_descriptions/{$path}.php" ) ) {
			return '';
		}

		global $webinarignition_shortcodes_is_list;
		$is_list = $webinarignition_shortcodes_is_list;

		ob_start();
		include WEBINARIGNITION_PATH . "UI/shortcodes_descriptions/{$path}.php";
		return ob_get_clean();
	}

	public static function webinar_ignition_footer_sc() {
		$webinarignition_footer_text = get_option( 'webinarignition_footer_text', '' );
		$footer_copy            = str_replace( '{site_title}', get_bloginfo( 'name' ), $webinarignition_footer_text );
		$footer_copy            = str_replace( '{year}', gmdate( 'Y' ), $footer_copy );
		$footer_copy            = str_replace( '{site_description}', get_bloginfo( 'description' ), $footer_copy );
		$privacy_policy_link    = get_privacy_policy_url();
		$privacy_policy         = '<a href="' . $privacy_policy_link . '" target="_blank">' . __( 'Privacy Policy', 'webinar-ignition' ) . '</a>';
		$footer_copy            = str_replace( '{privacy_policy}', $privacy_policy, $footer_copy );
		$imprint_page           = wi_get_page_by_title( 'Imprint' );
		$imprint_page           = empty( $imprint_page ) ? wi_get_page_by_title( 'Impressum' ) : $imprint_page;
		$imprint_page_url       = ! empty( $imprint_page ) ? get_permalink( $imprint_page->ID ) : '';
		$imprint_page_link      = '<a href="' . $imprint_page_url . '" target="_blank">' . $imprint_page->post_title . '</a>';
		$footer_copy            = is_object( $imprint_page ) ? str_replace( '{imprint}', $imprint_page_link, $footer_copy ) : str_replace( '{imprint}', ' ', $footer_copy );
		return $footer_copy;
	}

	public static function webinarignition_sidebar_cta_static() {
		$Sidebar_settings = get_option( 'sidebar_cta', '' );
		return $Sidebar_settings;
	}

	public static function webinarignition_shortcode( $atts = array() ) {
		global $webinarignition_shortcodes_options;
		global $webinarignition_shortcode_page;
		global $webinarignition_shortcode_scripts;
		global $webinarignition_shortcode_params;

		if ( empty( $webinarignition_shortcodes_options ) ) {
			$webinarignition_shortcodes_options = array();
		}

		if ( empty( $webinarignition_shortcode_params ) ) {
			$webinarignition_shortcode_params = array();
		}

		$params = array(
			'id'               => '',
			'allowed_ids'      => '',
			'block'            => '',
			'readonly'         => 'false',
			'custom_video_url' => '',
			'border'           => 'false',
			'background_color' => 'default',
        	'contrast_color' => 'default',
		);

		$params = shortcode_atts( $params, $atts );

		/**
		 * @var int    $id
		 * @var string $block
		 */
		extract( $params ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

		$webinarignition_shortcode_params[ $id ] = $params;

		$renamed_shortcodes = self::webinarignition_get_renamed_shortcodes();

		if ( ! empty( $renamed_shortcodes[ $block ] ) ) {
			$block = $renamed_shortcodes[ $block ];
		}

		$available_shortcodes = self::webinarignition_get_available_shortcodes();

		if ( empty( $block ) || empty( $available_shortcodes[ $block ] ) ) {
			return '';
		}

		$shortcode_settings = $available_shortcodes[ $block ];
		$id                 = get_query_var( 'webinar_id' );

		if ( empty( $id ) ) {
			$id = isset($atts['id']) ? $atts['id'] : '';
		}

		if ( ! empty( $id ) ) {
			if ( ! empty( $allowed_ids ) ) {
				$allowed            = false;
				$allowed_ids_array  = explode( ',', $allowed_ids );

				foreach ( $allowed_ids_array as $allowed_id ) {
					if ( (int) $allowed_id === (int) $id ) {
						$allowed = true;
					}
				}

				if ( ! $allowed ) {
					return '';
				}
			}
		}

		if ( empty( $id ) ) {
			return '';
		}

		if ( empty( $webinarignition_shortcodes_options[ $id ] ) ) {
			$results = WebinarignitionManager::webinarignition_get_webinar_data( $id );

			if ( empty( $results ) ) {
				return '';
			}

			$webinar_type = 'AUTO' === $results->webinar_date ? 'evergreen' : 'live';

			if ( ! in_array( $webinar_type, $shortcode_settings['type'], true ) ) {
				return '';
			}

			$webinarignition_shortcodes_options[ $id ] = $results;
		} else {
			$results      = $webinarignition_shortcodes_options[ $id ];
			$webinar_type = 'AUTO' === $results->webinar_date ? 'evergreen' : 'live';

			if ( ! in_array( $webinar_type, $shortcode_settings['type'], true ) ) {
				return '';
			}
		}

		if ( ! self::webinarignition_is_enabled( $results ) && 'evergreen' === $webinar_type ) {
			return '';
		}

		if ( empty( $webinarignition_shortcode_page ) ) {
			$webinarignition_shortcode_page = $shortcode_settings['page'];
		}

		if ( empty( $webinarignition_shortcode_page ) ) {
			$webinarignition_shortcode_page = $shortcode_settings['page'];
		}

		if ( empty( $webinarignition_shortcode_scripts ) && false === strpos( $block, 'global' ) ) {
			$webinarignition_shortcode_scripts = $shortcode_settings['page'];

			self::webinarignition_enqueue_scripts( $id, $block );
		}

		return self::html( $id, $block );
	}

	public static function html( $webinarId, $block ) {
		global $webinarignition_shortcodes_options;

		if ( empty( $webinarignition_shortcodes_options[ $webinarId ] ) ) {
			return '';
		}

		$webinar_data = $webinarignition_shortcodes_options[ $webinarId ];
		$webinar_type = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';

		$template_cb_array = self::webinarignition_get_available_shortcodes();

		if ( empty( $template_cb_array[ $block ] ) || ! in_array( $webinar_type, $template_cb_array[ $block ]['type'], true ) ) {
			return '';
		}

		self::webinarignition_set_page_params( $webinarId, $template_cb_array[ $block ]['page'] );

		$template_cb = $template_cb_array[ $block ]['cb'];

		// Switch to webinar language before getting shortcode HTML
		WebinarignitionManager::webinarignition_set_locale( $webinar_data );

		$html = $template_cb( $webinar_data );

		// Restore back to previous language after getting shortcode HTML
		WebinarignitionManager::webinarignition_restore_locale( $webinar_data );

		return $html;
	}

	public static function webinarignition_enqueue_scripts( $webinarId, $block ) {
		$template_cb_array = self::webinarignition_get_available_shortcodes();

		if ( empty( $template_cb_array[ $block ] ) ) {
			return '';
		}

		global $webinarignition_shortcodes_options;

		if ( empty( $webinarignition_shortcodes_options[ $webinarId ] ) ) {
			return '';
		}

		$page         = $template_cb_array[ $block ]['page'];
		$enqueue_cb   = 'enqueue_' . $page . '_page_script';
		$webinar_data = $webinarignition_shortcodes_options[ $webinarId ];
		$webinar_load_registration_scripts = 'load_webinarignition-scripts-on-registration';
		if ( method_exists( 'WebinarignitionPowerupsShortcodes', 'webinarignition_'.$enqueue_cb ) ) {
			self::{'webinarignition_'.$enqueue_cb}( $webinar_data );
		}
	}

	public static function webinarignition_enqueue_registration_page_script( $webinar_data ) {
		$webinarId = $webinar_data->id;
		// <head> css
		wp_enqueue_style( 'webinarignition_intlTelInput' );
		wp_enqueue_style( 'webinarignition_font-awesome' );

		/**
		 * Change the or operator to and operator to avoid error
		 * Now first the property custom_templates_styles will be checked if isset then it will try to access
		 * its value and prevent the error from occuring
		 */
		if ( isset( $webinar_data->custom_templates_styles ) && 'off' !== $webinar_data->custom_templates_styles ) {
			// Enqueue based style directly, so that in-line style can be inserted
			wp_enqueue_style( 'webinarignition_main_template', WEBINARIGNITION_URL . 'inc/lp/css/main-template.css', array(), WEBINARIGNITION_VERSION . '-' . time() );
			wp_add_inline_style( 'webinarignition_main_template', wp_strip_all_tags( webinarignition_inline_css_file( WEBINARIGNITION_PATH . 'inc/lp/css/lp_css.php', $webinar_data ) ) ); // Used in shortcodes
		}

		// Load wi admin CSS on frontend to display modal properly, when registration shortcode has been used on a custom page
		// ! TODO: Use nonce verification if need or possible.
		if ( isset( $_GET['artest'] ) && 1 === (int) $_GET['artest'] ) {
			wp_enqueue_style( 'webinarignition-admin', WEBINARIGNITION_URL . 'css/webinarignition-admin.css', array(), '2.2.9' );
		}

		// <head> js
		wp_enqueue_script( 'moment' );
		wp_enqueue_script( 'webinarignition_js_utils' );

		// footer scripts
		wp_enqueue_script( 'webinarignition_cookie_js' );
		wp_enqueue_script( 'webinarignition_webinar_data_after_js', WEBINARIGNITION_URL . 'inc/lp/js/webinar-data-after.js', array('jquery', 'webinarignition_countdown_js'), WEBINARIGNITION_VERSION, true );
		wp_enqueue_script( 'webinarignition_intlTelInput_js' );
		wp_enqueue_script( 'webinarignition_frontend_js' );
		wp_enqueue_script( 'webinarignition_tz_js' );
		wp_enqueue_script( 'webinarignition_luxon_js' );

		if ( ! empty( $webinar_data->custom_lp_js ) ) {
			wp_add_inline_script( 'wi_js_utils', $webinar_data->custom_lp_js );
		}

		if ( ! empty( $webinar_data->stripe_publishable_key ) ) {
			wp_enqueue_script( 'webinarignition_stripe_js', 'https://js.stripe.com/v2/', array(), WEBINARIGNITION_VERSION, array() );
			$setPublishableKey = 'Stripe.setPublishableKey("' . $webinar_data->stripe_publishable_key . '")';
			wp_add_inline_script( 'webinarignition_stripe_js', $setPublishableKey );
		}

		if ( ! empty( $webinar_data->paid_status ) && ( 'paid' === $webinar_data->paid_status ) ) {
			$paid_js_code = "var paid_code = {code: $webinar_data->paid_code}";
			wp_add_inline_script( 'wi_js_utils', $paid_js_code );
		}

		global $wpdb;
		$webinar_post_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %d ORDER BY meta_id ASC;",
			'webinarignitionx_meta_box_select',
			$webinarId
		) );

		if ( ! empty( $webinar_post_id ) ) {
			$webinar_data->webinar_id = $webinar_post_id;
			$webinar_data->ty_id = $webinar_post_id;
		}

		$wi_parsed = webinarignition_parse_registration_page_data( $webinarId, $webinar_data );

		$isSigningUpWithFB = false;
		$fbUserData = array();
		$code = filter_input(INPUT_GET, 'code', FILTER_UNSAFE_RAW);

		if ( ! empty( $webinar_data->fb_id ) && ! empty( $webinar_data->fb_secret ) && ! empty( $code ) ) {
			include_once 'lp/fbaccess.php';
			/**
			 * @var $user_info;
			 */
			$isSigningUpWithFB                      = true;
			$fbUserData['name']                     = $user_info['name'];
			$fbUserData['email']                    = $user_info['email'];
		}

		$wi_parsed['isSigningUpWithFB'] = $isSigningUpWithFB;
		$wi_parsed['fbUserData']        = $fbUserData;
		$window_webinarignition         = 'window.webinarignition = ' . wp_json_encode( $wi_parsed, JSON_HEX_APOS ) . ';';
		$window_security                = "window.wiRegJS.ajax_nonce = '" . wp_create_nonce( 'webinarignition_ajax_nonce' ) . "'";

		wp_enqueue_script( 'webinarignition_registration_js', WEBINARIGNITION_URL . 'inc/lp/js/registration-page.js', array(
			'jquery',
			'webinarignition_cookie_js',
			'webinarignition_webinar_data_after_js',
			'webinarignition_intlTelInput_js',
			'webinarignition_frontend_js',
			'webinarignition_tz_js',
			'webinarignition_luxon_js',
		), WEBINARIGNITION_VERSION . '-' . time(), true );

		wp_localize_script('webinarignition_registration_js', 'wiRegJS', array(
			'ajax_nonce' => wp_create_nonce( 'webinarignition_ajax_nonce' ),
		));

		wp_add_inline_script( 'webinarignition_registration_js', $window_webinarignition, 'before' );

		wp_enqueue_script( 'webinarignition_after_footer_js', WEBINARIGNITION_URL . 'inc/lp/js/after-footer.js', array('jquery', 'webinarignition_countdown_js'), WEBINARIGNITION_VERSION, true );
		$ajax_url        = esc_url( admin_url( 'admin-ajax.php' ) );
		$tracking_cookie = 'we-trk-ty-' . esc_attr( $webinar_data->id );
		$nonce           = esc_attr( wp_create_nonce( 'webinarignition_ajax_nonce' ) );
		$webinar_id      = absint( $webinar_data->id );
		$lead_timezone   = isset($webinar_data->lead_timezone) ? $webinar_data->lead_timezone : '';
		$webinar_timezone= webinarignition_get_webinar_timezone($webinar_data, null);
		if ( isset($lead) && webinarignition_is_auto( $webinar_data ) && $lead != false ) {
			$expire = explode( ' ', $lead->date_picked_and_live )[0];
			$time   = explode( ' ', $lead->date_picked_and_live )[1];
			$auto_tz = webinarignition_get_tzOffset( $lead->lead_timezone );
		} else {
				$expire = $webinar_data->webinar_date;
				$webinar_date = strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire );
				$webinar_date_key_2 = isset($webinar_date[2]) ? $webinar_date[2] : null;
				$webinar_date_key_1 = isset($webinar_date[1]) ? $webinar_date[1] : null;
				$expire = "{$webinar_date_key_2}-{$webinar_date[0]}-{$webinar_date_key_1}";
				$time = gmdate('H:i', strtotime(!empty($webinar_data->webinar_start_time) ? $webinar_data->webinar_start_time : '00:00'));
		}
		
		$ex_date = strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire );
		$ex_time = explode( ':', $time );
		
		$ex_year = esc_attr( $ex_date[0] ) ?? '0';
		$ex_date_key_1 = isset($ex_date[1]) ? $ex_date[1] : null;
		$ex_month = esc_attr( $ex_date_key_1 - 1 ) ?? '0';
		$ex_date_key_2 = isset($ex_date[2]) ? $ex_date[2]: '0';
		$ex_day = esc_attr( $ex_date_key_2 ) ?? '0';
		$ex_hr = esc_attr( $ex_time[0] ) ?? '0';
		$ex_min = esc_attr( str_replace( array( ' ', 'AM', 'PM' ), '', $ex_time[1] ) ) ?? '0';
		$tz_offset = esc_attr( isset($auto_tz) ? $auto_tz : null );
		// Enqueue the JavaScript file and pass the variables
		wp_localize_script(
			'webinarignition_after_footer_js',
			'webinarData',
			array(
				'ajaxurl'           => $ajax_url,
				'trackingCookie'    => $tracking_cookie,
				'nonce'             => $nonce,
				'webinarId'         => $webinar_id,
				'exYear' => $ex_year,
				'exMonth' => $ex_month,
				'exDay' => $ex_day,
				'exHr' => $ex_hr,
				'exMin' => $ex_min,
				'tzOffset' => $tz_offset,
				'tycd_years'        => isset($webinar_data->tycd_years) ? $webinar_data->tycd_years : '',
				'tycd_months'       => isset($webinar_data->tycd_months) ? $webinar_data->tycd_months : '',
				'tycd_weeks'        => isset($webinar_data->tycd_weeks) ? $webinar_data->tycd_weeks : '',
				'tycd_days'         => isset($webinar_data->tycd_days) ? $webinar_data->tycd_days : '',
				'tycd_progress'     => isset($webinar_data->tycd_progress) ? $webinar_data->tycd_progress : '',
				'webinarInProgress' => __( 'Webinar Is In Progress', 'webinar-ignition' ),
				'isAuto'            => webinarignition_is_auto( $webinar_data ),
				'webinarDate'       => isset($webinar_data->webinar_date) ? $webinar_data->webinar_date : '',
			)
		);
	}

	public static function webinarignition_enqueue_thankyou_page_script( $webinar_data ) {
		$webinarId = $webinar_data->id;
		extract( webinarignition_get_ty_templates_vars( $webinar_data ) );//phpcs:ignore

		// <head> css
		wp_enqueue_style( 'webinarignition_intlTelInput' );
		wp_enqueue_style( 'webinarignition_font-awesome' );
		wp_enqueue_style( 'webinarignition_countdown_ty' );

		if ( empty( $webinar_data->custom_templates_styles ) || 'off' !== $webinar_data->custom_templates_styles ) {
			wp_enqueue_style( 'webinarignition_main_template' );
		}

		$ty_css = '.topArea {';

		if ( 'hide' === $webinar_data->lp_banner_bg_style ) {
			$ty_css .= ' display: none;';
		}

		if ( empty( $webinar_data->lp_banner_bg_color ) ) {
			$ty_css .= ' background-color: #FFF;';
		} else {
			$ty_css .= " background-color: {$webinar_data->lp_banner_bg_color};";
		}

		if ( empty( $webinar_data->lp_banner_bg_repeater ) ) {
			$ty_css .= ' border-top: 3px solid rgba(0,0,0,0.20); border-bottom: 3px solid rgba(0,0,0,0.20);';
		} else {
			$ty_css .= ' background-image: url(' . $webinar_data->lp_banner_bg_repeater . ');';
		}

		$ty_css .= '}';
		$ty_css .= ' .mainWrapper{ background-color: #f1f1f1; }';

		wp_enqueue_style( 'webinarignition_countdown_ty_inline' );
		wp_add_inline_style( 'webinarignition_countdown_ty_inline', $ty_css );

		if ( ! empty( $webinar_data->custom_ty_css ) ) {
			wp_add_inline_style( 'webinarignition_head_style', esc_html( $webinar_data->custom_ty_css ) );
		}

		// <head> js
		wp_enqueue_script( 'moment' );
		wp_enqueue_script( 'webinarignition_cookie_js' );
		wp_enqueue_script( 'webinarignition_webinar_data_after_js' );
		wp_enqueue_script( 'webinarignition_intlTelInput_js' );
		wp_enqueue_script( 'webinarignition_frontend_js' );
		wp_enqueue_script( 'webinarignition_countdown_js' );

		wp_enqueue_script( 'webinarignition_countdown_ty_inline' );

		
		if ( 'AUTO' === $webinar_data->webinar_date && $leadinfo != false) {
			$livedate = explode( ' ', $leadinfo->date_picked_and_live );
			$expire = $livedate[0];
			$ex_date = strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire );
			$ex_year = $ex_date[0];
			$ex_month = (int) $ex_date[1];
			$ex_day = $ex_date[2];
	} else {
			$expire = $webinar_data->webinar_date;
			$ex_date = strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire );
			$ex_year = isset($ex_date[2]) ? $ex_date[2] : null;
			$ex_month = (int) $ex_date[0];
			$ex_day = isset($ex_date[1]) ? $ex_date[1] : null;
		}

		$liverdate_first_key =  isset($livedate[1]) ? $livedate[1] : null;
		$time = 'AUTO' === $webinar_data->webinar_date ? $liverdate_first_key  : $webinar_data->webinar_start_time;
		$time = !empty($time) ? gmdate('H:i', strtotime($time)) : '00:00';
		$ex_time = explode( ':', $time );
		$ex_hr = $ex_time[0];
		$ex_min = $ex_time[1];
		$ex_sec = '00';
		$timezone_to_create = empty( $leadinfo->lead_timezone ) ? 'Asia/Beirut' : $leadinfo->lead_timezone;
		$tz = new DateTimeZone( 'AUTO' === $webinar_data->webinar_date ? $timezone_to_create : $webinar_data->webinar_timezone );
		$utc_offset = $tz->getOffset( new DateTime() ) / 3600;

		$webinar_url = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'webinar' );
		$webinar_url = add_query_arg( 'live', '', $webinar_url );
		
		$lead_id = isset($input_get['lid']) ? sanitize_text_field( $input_get['lid'] ) : 0;
		
		$webinar_url = add_query_arg( 'lid', $lead_id, $webinar_url );
		if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
			$webinar_url = add_query_arg( md5( $webinar_data->paid_code ), '', $webinar_url );
		}
		$webinar_url = add_query_arg( 'watch_type', 'live', $webinar_url );
		$labels = array(
			__( 'Years', 'webinar-ignition' ),
			 __( 'Months', 'webinar-ignition' ),
			 __( 'Weeks', 'webinar-ignition' ),
			 __( 'Days', 'webinar-ignition' ),
			 __( 'Hours', 'webinar-ignition' ),
			 __( 'Minutes', 'webinar-ignition' ),
			 __( 'Seconds', 'webinar-ignition' ),
		);
	
	$labels1 = array(
			__( 'Year', 'webinar-ignition' ),
			 __( 'Month', 'webinar-ignition' ),
			 __( 'Week', 'webinar-ignition' ),
			 __( 'Day', 'webinar-ignition' ),
			 __( 'Hour', 'webinar-ignition' ),
			 __( 'Minute', 'webinar-ignition' ),
			 __( 'Second', 'webinar-ignition' ),
	);
	
	wp_localize_script(
			'webinarignition_js_countdown',
			'webinarData',
			array(
					'webinar_date'      => $webinar_data->webinar_date,
					'utc_offset'        => $utc_offset,
					'ex_year'           => $ex_year,
					'ex_month'          => $ex_month,
					'ex_day'            => $ex_day,
					'ex_hr'             => $ex_hr,
					'ex_min'            => $ex_min,
					'ex_sec'            => $ex_sec,
					'is_preview'        => $is_preview,
					'webinar_url'       => $webinar_url,
					'admin_ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'        => wp_create_nonce( 'webinarignition_ajax_nonce' ),
					'webinar_id'        => $webinar_data->id,
					'paid_status'       => $webinar_data->paid_status,
					'paid_webinar_url'  => isset($webinar_data->paid_webinar_url) ? $webinar_data->paid_webinar_url : null,
					'labels'            => $labels,
					'labels1'           => $labels1,
			)
	);

		wp_enqueue_script( 'webinarignition_before_footer_js' );

		if ( ! empty( $webinar_data->custom_ty_js ) ) {
			wp_add_inline_script( 'moment', $webinar_data->custom_ty_js );
		}

		wp_enqueue_script( 'webinarignition_after_footer_js' );

		$after_footer_js = array(
		);
		

		wp_add_inline_script( 'webinarignition_after_footer_js',
			webinarignition_inline_js_file( $after_footer_js, $webinar_data ),
			'before'
		);
		extract( webinarignition_get_ty_templates_vars( $webinar_data ) );

			// Define variables to pass to the JavaScript file
			$ajax_url = esc_url( admin_url( 'admin-ajax.php' ) );
			$tracking_cookie = 'we-trk-ty-' . esc_attr( $webinar_data->id );
			$nonce = esc_attr( wp_create_nonce( 'webinarignition_ajax_nonce' ) );
			$webinar_id = absint( $webinar_data->id );

			if ( webinarignition_is_auto( $webinar_data ) && $lead != false ) {
					$expire = explode( ' ', $lead->date_picked_and_live )[0];
					$time   = explode( ' ', $lead->date_picked_and_live )[1];
					$auto_tz = webinarignition_get_tzOffset( $lead->lead_timezone );
			} else {
					$expire = $webinar_data->webinar_date;
					$webinar_date = strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire );
					$webinar_date_key_2 = isset($webinar_date[2]) ? $webinar_date[2] : null;
					$webinar_date_key_1 = isset($webinar_date[1]) ? $webinar_date[1] : null;
					$expire = "{$webinar_date_key_2}-{$webinar_date[0]}-{$webinar_date_key_1}";
					$time = '00:00';

					if ( ! empty( $webinar_data->webinar_start_time ) ) {
						$timestamp = strtotime( $webinar_data->webinar_start_time );
						if ( $timestamp !== false ) {
							$time = gmdate( 'H:i', $timestamp );
						}
					}
								}
			
			$ex_date = strpos( $expire, '-' ) ? explode( '-', $expire ) : explode( '/', $expire );
			$ex_time = explode( ':', $time );
			
			$ex_year = esc_attr( $ex_date[0] ) ?? '0';
			$ex_date_key_1 = isset($ex_date[1]) ? $ex_date[1] : null;
			$ex_month = esc_attr( $ex_date_key_1 - 1 ) ?? '0';
			$ex_date_key_2 = isset($ex_date[2]) ? $ex_date[2]: '0';
			$ex_day = esc_attr( $ex_date_key_2 ) ?? '0';
			$ex_hr = esc_attr( $ex_time[0] ) ?? '0';
			$ex_min = esc_attr( str_replace( array( ' ', 'AM', 'PM' ), '', $ex_time[1] ) ) ?? '0';
			$tz_offset = esc_attr( isset($auto_tz) ? $auto_tz : null );

			// Enqueue the JavaScript file and pass the variables
			wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
					'ajaxurl' => $ajax_url,
					'trackingCookie' => $tracking_cookie,
					'nonce' => $nonce,
					'webinarId' => $webinar_id,
					'exYear' => $ex_year,
					'exMonth' => $ex_month,
					'exDay' => $ex_day,
					'exHr' => $ex_hr,
					'exMin' => $ex_min,
					'tzOffset' => $tz_offset,
					'leadId' => esc_attr( $leadId ),
					'tycd_years' => $webinar_data->tycd_years,
					'tycd_months' => $webinar_data->tycd_months,
					'tycd_weeks' => $webinar_data->tycd_weeks,
					'tycd_days' => $webinar_data->tycd_days,
					'tycd_progress' => $webinar_data->tycd_progress,
					'webinarInProgress' => __( 'Webinar Is In Progress', 'webinar-ignition' ),
					'isAuto' => webinarignition_is_auto( $webinar_data ),
					'webinarDate' => $webinar_data->webinar_date
			) );
	}

	public static function webinarignition_enqueue_replay_page_script( $webinar_data ) {
		extract( webinarignition_get_replay_templates_vars( $webinar_data ) ); //phpcs:ignore

		wp_enqueue_style( 'webinarignition_font-awesome' );

		if ( empty( $webinar_data->custom_templates_styles ) || 'off' !== $webinar_data->custom_templates_styles ) {
			wp_enqueue_style( 'webinarignition_main_template' );
		}

		if ( webinarignition_should_use_videojs( $webinar_data ) ) {
			wp_enqueue_style( 'webinarignition_video_css' );
		}

		wp_enqueue_style( 'webinarignition_head_style_after' );

		if ( isset($webinar_data->custom_replay_css) && ! empty( $webinar_data->custom_replay_css ) ) {
			wp_add_inline_style( 'webinarignition_head_style_after', esc_html( $webinar_data->custom_replay_css ) );
		}

		/** ====================================
		 *  HEAD JS
		 *  ==================================== */
		wp_enqueue_script( 'webinarignition_js_countdown' );
		wp_enqueue_script( 'webinarignition_cookie_js' );
		wp_enqueue_script( 'webinarignition_webinar_data_after_js' );
		$is_auto_login_enabled = (absint(get_option('webinarignition_registration_auto_login', 1)) === 1);
		// Check if user is logged in
		$is_user_logged_in = is_user_logged_in();

		// Get query var
		$webinarignition_page = get_query_var('webinarignition_page');
		wp_localize_script(
				'webinarignition_webinar_data_after_js',
				'webinar_data',
				array(
						'ajax_url'               => admin_url('admin-ajax.php'),
						'security'               => wp_create_nonce('webinarignition_ajax_nonce'),
						'webinar_id'             => isset($webinar_id) ? $webinar_id : '',
						'input_get'              => isset($input_get) ? $input_get : '',
						'webinar_date'           => isset($webinar_data->webinar_date) ? $webinar_data->webinar_date : '',
						'lead_timezone'          => isset($leadinfo->lead_timezone) ? $leadinfo->lead_timezone : '',
						'webinar_timezone'       => isset($webinar_data->webinar_timezone) ? $webinar_data->webinar_timezone : '',
						'auto_replay'            => isset($webinar_data->auto_replay) ? $webinar_data->auto_replay : '',
						'date_picked_and_live'   => isset($leadinfo->date_picked_and_live) ? $leadinfo->date_picked_and_live : '',
						'replay_cd_date'         => isset($webinar_data->replay_cd_date) ? $webinar_data->replay_cd_date : '',
						'replay_optional'        => isset($webinar_data->replay_optional) ? $webinar_data->replay_optional : '',
						'cd_months'              => isset($webinar_data->cd_months) ? $webinar_data->cd_months : '',
						'cd_weeks'               => isset($webinar_data->cd_weeks) ? $webinar_data->cd_weeks : '',
						'cd_days'                => isset($webinar_data->cd_days) ? $webinar_data->cd_days : '',
						'cd_hours'               => isset($webinar_data->cd_hours) ? $webinar_data->cd_hours : '',
						'cd_minutes'             => isset($webinar_data->cd_minutes) ? $webinar_data->cd_minutes : '',
						'cd_seconds'             => isset($webinar_data->cd_seconds) ? $webinar_data->cd_seconds : '',
						'webinar_source_toggle'  => isset($webinar_data->webinar_source_toggle) ? $webinar_data->webinar_source_toggle : '',
						'webinar_iframe_source'  => isset($webinar_data->webinar_iframe_source) ? $webinar_data->webinar_iframe_source : '',
						'auto_action'            => isset($webinar_data->auto_action) ? $webinar_data->auto_action : '',
						'auto_action_time'       => isset($webinar_data->auto_action_time) ? $webinar_data->auto_action_time : '',
						'auto_action_time_end'   => isset($webinar_data->auto_action_time_end) ? $webinar_data->auto_action_time_end : '',
						'auto_action_max_width'	 => isset($webinar_data->auto_action_max_width) ? $webinar_data->auto_action_max_width : '',
						'auto_action_transparency'=> isset($webinar_data->auto_action_transparency) ? $webinar_data->auto_action_transparency : '0',
						'additional_autoactions' => isset($webinar_data->additional_autoactions) ? maybe_unserialize($webinar_data->additional_autoactions) : '',
						'replay_order_time'      => isset($webinar_data->replay_order_time) ? $webinar_data->replay_order_time : '',
						'is_auto_login_enabled'  => $is_auto_login_enabled,
						'is_user_logged_in'      => $is_user_logged_in,
						'webinarignition_page'   => $webinarignition_page,
						'wp_timezone_string'     => wp_timezone_string(),
						'current_time'           => current_time('mysql'),
						'webinar_timezone_offset'=> get_option('gmt_offset'),
						'webinar_is_multiple_cta_enabled'=>WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled($webinar_data)
				)
		);

		if ( isset($webinar_data->custom_replay_js) && ! empty( $webinar_data->custom_replay_js ) ) {
			wp_add_inline_script( 'webinarignition_cookie_js', '(function ($) {' . $webinar_data->custom_replay_js . '})(jQuery);' );
		}

		if ( webinarignition_should_use_videojs( $webinar_data ) ) {
			wp_enqueue_script( 'webinarignition_video_js' );
		}

		/** ====================================
		 *  FOOTER JS
			==================================== */
		wp_enqueue_script( 'webinarignition_before_footer_js' );

		if ( 'hide' !== trim($webinar_data->webinar_qa) ) {
			wp_add_inline_script( 'webinarignition_before_footer_js',
				webinarignition_inline_js_file( array(
					WEBINARIGNITION_PATH . 'inc/lp/partials/fb_share_js.php',
					WEBINARIGNITION_PATH . 'inc/lp/partials/tw_share_js.php',
				), $webinar_data),
				'before'
			);
		}

		wp_enqueue_script( 'webinarignition_after_footer_js' );

		$after_footer_js = array( );

		if ( 'AUTO' === $webinar_data->webinar_date ) {
			wp_enqueue_script( 'webinarignition_auto_video_inline_js' );
			$lead_id = !empty($leadinfo->ID) ? $leadinfo->ID : '';
			$is_auto_login_enabled = absint(get_option('webinarignition_registration_auto_login', 1)) == 1;
			$is_user_logged_in = is_user_logged_in();
			$is_preview_page = WebinarignitionManager::webinarignition_url_is_preview_page();
			$nonce = wp_create_nonce('webinarignition_mark_lead_status');
			$webinar_id = $webinar_data->id;
			$auto_redirect_url = WebinarignitionManager::webinarignition_get_auto_redirect_url($webinar_data);
			$auto_redirect_delay = isset($webinar_data->auto_redirect_delay) ? absint($webinar_data->auto_redirect_delay) : 0;
			$individual_offset = !empty($individual_offset) ? $individual_offset : 0;
			$lid = !empty($_GET['lid']) ? $_GET['lid'] : '';
			$auto_video_length = !empty($webinar_data->auto_video_length) ? (int) $webinar_data->auto_video_length * 60 * 1000 : 0;
			$should_use_videojs = webinarignition_should_use_videojs($webinar_data);
			$languages = array(
					"Video Player" => esc_html__('Video Player', 'webinar-ignition'),
					"Play" => esc_html__('Play', 'webinar-ignition'),
					"Pause" => esc_html__('Pause', 'webinar-ignition'),
					"Replay" => esc_html__('Replay', 'webinar-ignition'),
					"Current Time" => esc_html__('Current Time', 'webinar-ignition'),
					"Duration" => esc_html__('Duration', 'webinar-ignition'),
					"Remaining Time" => esc_html__('Remaining Time', 'webinar-ignition'),
					"LIVE" => esc_html__('LIVE', 'webinar-ignition'),
					"Seek to live, currently behind live" => esc_html__('Seek to live, currently behind live', 'webinar-ignition'),
					"Seek to live, currently playing live" => esc_html__('Seek to live, currently playing live', 'webinar-ignition'),
					"Loaded" => esc_html__('Loaded', 'webinar-ignition'),
					"Progress" => esc_html__('Progress', 'webinar-ignition'),
					"Fullscreen" => esc_html__('Fullscreen', 'webinar-ignition'),
					"Non-Fullscreen" => esc_html__('Exit Fullscreen', 'webinar-ignition'),
					"Mute" => esc_html__('Mute', 'webinar-ignition'),
					"Unmute" => esc_html__('Unmute', 'webinar-ignition'),
					"Audio Player" => esc_html__('Audio Player', 'webinar-ignition'),
					"Caption Settings Dialog" => esc_html__('Caption Settings Dialog', 'webinar-ignition'),
					"Close" => esc_html__('Close', 'webinar-ignition'),
					"Descriptions" => esc_html__('Descriptions', 'webinar-ignition'),
					"Text" => esc_html__('Text', 'webinar-ignition'),
					"White" => esc_html__('White', 'webinar-ignition'),
					"Black" => esc_html__('Black', 'webinar-ignition'),
					"Red" => esc_html__('Red', 'webinar-ignition'),
					"Green" => esc_html__('Green', 'webinar-ignition'),
					"Blue" => esc_html__('Blue', 'webinar-ignition'),
					"Yellow" => esc_html__('Yellow', 'webinar-ignition'),
					"Magenta" => esc_html__('Magenta', 'webinar-ignition'),
					"Cyan" => esc_html__('Cyan', 'webinar-ignition'),
					"Background" => esc_html__('Background', 'webinar-ignition'),
					"Window" => esc_html__('Window', 'webinar-ignition'),
					"Opacity" => esc_html__('Opacity', 'webinar-ignition'),
					"Slider" => esc_html__('Slider', 'webinar-ignition'),
					"Volume Level" => esc_html__('Volume Level', 'webinar-ignition'),
					"Subtitles" => esc_html__('Subtitles', 'webinar-ignition'),
					"Captions" => esc_html__('Captions', 'webinar-ignition'),
					"Chapters" => esc_html__('Chapters', 'webinar-ignition'),
					"Close Modal Dialog" => esc_html__('Close Modal Dialog', 'webinar-ignition'),
					"Descriptions off" => esc_html__('Descriptions off', 'webinar-ignition'),
					"Captions off" => esc_html__('Captions off', 'webinar-ignition'),
					"Audio Track" => esc_html__('Audio Track', 'webinar-ignition'),
					"You aborted the media playback" => esc_html__('You aborted the media playback', 'webinar-ignition'),
					"A network error caused the media download to fail part-way." => esc_html__('A network error caused the media download to fail part-way.', 'webinar-ignition'),
					"The media could not be loaded, either because the server or network failed or because the format is not supported." => esc_html__('The media could not be loaded, either because the server or network failed or because the format is not supported.', 'webinar-ignition'),
					"No compatible source was found for this media." => esc_html__('No compatible source was found for this media.', 'webinar-ignition'),
					"The media is encrypted and we do not have the keys to decrypt it." => esc_html__('The media is encrypted and we do not have the keys to decrypt it.', 'webinar-ignition'),
					"Play Video" => esc_html__('Play Video', 'webinar-ignition'),
					"Close" => esc_html__('Close', 'webinar-ignition')
			);

			wp_localize_script('webinarignition_auto_video_inline_js', 'webinarParams', array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'is_auto_login_enabled' => $is_auto_login_enabled,
					'is_preview_page' => $is_preview_page,
					'lead_id' => $lead_id,
					'webinar_id' => $webinar_id,
					'nonce' => $nonce,
					'auto_redirect_url' => $auto_redirect_url,
					'auto_redirect_delay' => $auto_redirect_delay,
					'individual_offset' => $individual_offset,
					'lid' => $lid,
					'is_user_logged_in' => $is_user_logged_in,
					'auto_video_length' => $auto_video_length,
					'should_use_videojs' => $should_use_videojs,
					'languages' => $languages
			));
		}

		$webinar_data           = ! empty( $webinar_data ) ? $webinar_data : get_query_var( 'webinar_data' );
		$webinarignition_page   = ! empty( $webinarignition_page ) ? $webinarignition_page : get_query_var( 'webinarignition_page' );
		$webinar_type           = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';
		$tracking_tags_settings = isset( $webinar_data->tracking_tags ) ? $webinar_data->tracking_tags : array();
		$lead_id                = ! empty( $leadinfo->ID ) ? $leadinfo->ID : '';

		$is_replay_page = 'replay_custom' === $webinarignition_page || 'preview-replay' === $webinarignition_page || 'replay_page' === $webinarignition_page;

		$additional_autoactions_js = array();

		if ( 'evergreen' === $webinar_type ) {
			if ( 'time' === trim($webinar_data->auto_action) && ( ! empty( $webinar_data->webinar_iframe_source ) || ! empty( $webinar_data->auto_video_url ) ) ) {
					
					$cta_position_default = 'outer';
					$cta_position_allowed = 'outer';
					$cta_position_overlay_allowed = 'overlay';

					$additional_autoactions = array();

				if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && ! empty( $webinar_data->additional_autoactions ) ) {
						$additional_autoactions = maybe_unserialize( $webinar_data->additional_autoactions );
				}

				if (
					!empty($webinar_data->auto_action_time)
					// && !empty($webinar_data->auto_action_time_end)
					&& (
						!empty($webinar_data->auto_action_copy)
						|| (!empty($webinar_data->auto_action_btn_copy) && !empty($webinar_data->auto_action_url))
					)
				) {
					$webinar_main_auto_action = [
						'auto_action_time' => $webinar_data->auto_action_time,
						'auto_action_time_end' => '',
						'auto_action_copy' => '',
						'auto_action_btn_copy' => '',
						'auto_action_url' => '',
						'replay_order_color' => '#6BBA40',
					];
		
					if (!empty($webinar_data->auto_action_time_end)) {
						$webinar_main_auto_action['auto_action_time_end'] = $webinar_data->auto_action_time_end;
					}
		
					if (!empty($webinar_data->replay_order_color)) {
						$webinar_main_auto_action['replay_order_color'] = $webinar_data->replay_order_color;
					}
		
					if (!empty($webinar_data->auto_action_copy)) {
						$webinar_main_auto_action['auto_action_copy'] = $webinar_data->auto_action_copy;
					}
		
					if (!empty($webinar_data->auto_action_btn_copy) && !empty($webinar_data->auto_action_url)) {
						$webinar_main_auto_action['auto_action_btn_copy'] = $webinar_data->auto_action_btn_copy;
						$webinar_main_auto_action['auto_action_url'] = $webinar_data->auto_action_url;
					}
		
					if (!empty($webinar_data->cta_position) ) {
						$cta_position_default = $webinar_data->cta_position;
					}
		
					if ($cta_position_default === $cta_position_allowed) {
						$webinar_main_auto_action['cta_position'] = 'outer';
					} else {
						$webinar_main_auto_action['cta_position'] = 'overlay';
					}
		
					if(is_array($additional_autoactions) && !empty($additional_autoactions)) {
						$additional_autoactions = array_merge([$webinar_main_auto_action], $additional_autoactions);
					} else {
						$additional_autoactions[] = $webinar_main_auto_action;
					}
		
				}
				ksort($additional_autoactions);

				foreach ($additional_autoactions as $index => $additional_autoaction) {
					$cta_position = $cta_position_default;

					if (!empty($additional_autoaction['cta_position'])) {
						$cta_position = $additional_autoaction['cta_position'];
					}

					if (!empty($additional_autoaction['auto_action_time'])) {
						$auto_action_time_array = explode(':', $additional_autoaction['auto_action_time']);
						$delay = 10;

						if (!empty($auto_action_time_array[0])) $delay = $delay + ($auto_action_time_array[0] * 60000);
						if (!empty($auto_action_time_array[1])) $delay = $delay + (absint($auto_action_time_array[1]) * 1000);
						$start_delay = $delay;
						if ($start_delay > 10) $start_delay = $start_delay + 1000;

						if (empty($additional_autoaction['auto_action_time_end'])) {
							$delay = 0;
						} else {
							$auto_action_time_array = explode(':', $additional_autoaction['auto_action_time_end']);
							$delay = 0;
							if (!empty($auto_action_time_array[0])) $delay = $delay + ($auto_action_time_array[0] * 60000);
							if (!empty($auto_action_time_array[1])) $delay = $delay + ($auto_action_time_array[1] * 1000);
						}

						$end_delay = $delay;
						if ($end_delay > 0) $end_delay = $end_delay + 1000;
						$cta_index = 'additional-' . $index;

						$additional_autoactions_js[] = [
							'index' => $index,
							'end_delay' => $end_delay,
							'start_delay' => $start_delay,
							'is_videojs' => webinarignition_should_use_videojs( $webinar_data ),
						];
					}
				}
			}
		}
		
		$globalOffset = 0;
		if ('evergreen' !== $webinar_type) { // live webinar
			$timeStampNow               = time();
			$webinarDateTime            = $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time ;
			$date_picked                = DateTime::createFromFormat('m-d-Y H:i', $webinarDateTime, new DateTimeZone( $webinar_data->webinar_timezone ) );
			$too_late_lockout_minutes   = !empty( $webinar_data->too_late_lockout_minutes ) ? (int) $webinar_data->too_late_lockout_minutes * 60 : 3600;
			$date_picked_timestamp      = $date_picked->getTimestamp();
			$offset = $timeStampNow - $date_picked_timestamp;
	
			if (0 > $offset) $offset = 0;
	
			if (!empty($offset)) {
				$globalOffset = $offset * 1000;
			}
		}

		$tracking_tags_timeouts = array();

		if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && ! empty( $lead_id ) && ! empty( $tracking_tags_settings ) && ! $is_replay_page ) {
				foreach ( $tracking_tags_settings as $tracking_tag ) {
						if ( empty( $tracking_tag['time'] ) || empty( $tracking_tag['name'] ) ) {
								continue;
						}

						$time  = $tracking_tag['time'];
						$name  = $tracking_tag['name'];
						$slug  = empty( $tracking_tag['slug'] ) ? '' : $tracking_tag['slug'];
						$pixel = empty( $tracking_tag['pixel'] ) ? '' : $tracking_tag['pixel'];

						$timedActionArray = explode( ':', $time );
						$minutes          = $timedActionArray[0];

						if ( ! is_numeric( $minutes ) ) {
								$minutes = 0;
						} else {
								$minutes = (int) $minutes;
						}

						$seconds = 0;

						if ( ! empty( $timedActionArray[1] ) ) {
								$seconds = $timedActionArray[1];

								if ( ! is_numeric( $seconds ) ) {
										$seconds = 0;
								} else {
										$seconds = (int) $seconds;
								}
						}

						$timedAction = ( $minutes * 60 ) + $seconds;

						if ( empty( $timedAction ) ) {
								continue;
						}

						$timedAction = $timedAction * 1000;

						$time_array = array(
								'timeout' => $timedAction,
								'time'    => $time,
								'name'    => $name,
								'slug'    => $slug,
						);

						if ( ! empty( $pixel ) ) {
								$time_array['pixel'] = $pixel;
						}

						$tracking_tags_timeouts[] = $time_array;
				}
		}
		
		wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
			'autoVideoLength' 		=> isset( $webinar_data->auto_video_length ) ? absint( $webinar_data->auto_video_length ) : 0,
			'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
			'additionalAutoactions' => $additional_autoactions_js,
			'additionalCTA'         => maybe_unserialize($webinar_data->additional_autoactions),
			'trackingTags'			=> $tracking_tags_timeouts,
			'leadId' 				=> esc_html( $lead_id ),
			'webinarType' 			=> esc_html( $webinar_type ),
			'webinarId' 			=> absint( $webinar_id ),
			'webinar'               => $webinar_data,
			'globalOffset'          => $globalOffset,
            'nonce' => wp_create_nonce( 'webinarignition_ajax_nonce' ),
		));
		wp_add_inline_script( 'webinarignition_after_footer_js',
			webinarignition_inline_js_file( $after_footer_js, $webinar_data ),
			'before'
		);
		wp_enqueue_script( 'webinarignition_webinar_shared_js' );

		// TODO: Investigate why following localize script not wokring on "webinarignition_webinar_shared_js" handle,
		// assignining it to "jquery" instead to make it work
		wp_localize_script( 'webinarignition_webinar_shared_js', 'wiJsObj', array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'someWrong'     => __( 'Something went wrong', 'webinar-ignition' ),
		) );
	}

	public static function webinarignition_enqueue_countdown_page_script( $webinar_data ) {
		extract( webinarignition_get_countdown_templates_vars( $webinar_data ) );//phpcs:ignore

		wp_enqueue_style( 'webinarignition_font-awesome' );

		if ( empty( $webinar_data->custom_templates_styles ) || 'off' !== $webinar_data->custom_templates_styles ) {
			wp_enqueue_style( 'webinarignition_countdown' );
			wp_enqueue_style( 'webinarignition_main_template' );
			wp_add_inline_style( 'webinarignition_main_template',
				webinarignition_inline_css_file( WEBINARIGNITION_PATH . 'inc/lp/css/webinar_css.php', $webinar_data )
			);
		}

		// <head> js 
		wp_enqueue_script( 'webinarignition_cookie_js' );
		wp_enqueue_script( 'webinarignition_js_countdown' );
		if ('AUTO' === $webinar_data->webinar_date) {
			if ($leadinfo && isset($leadinfo->date_picked_and_live)) {
				$livedate = explode(' ', $leadinfo->date_picked_and_live);
				$expire = $livedate[0];
				$ex_date = strpos($expire, '-') ? explode('-', $expire) : explode('/', $expire);
				$ex_year = $ex_date[0];
				$ex_month = (int) $ex_date[1];
				$ex_day = $ex_date[2];
			} else {
				$ex_year = null;
				$ex_month = null;
				$ex_day = null;
			}
		} else {
			$expire = $webinar_data->webinar_date;
			$ex_date = strpos($expire, '-') ? explode('-', $expire) : explode('/', $expire);
			$ex_year = $ex_date[2];
			$ex_month = (int) $ex_date[0];
			$ex_day = $ex_date[1];
		}
		

		$time = 'AUTO' === $webinar_data->webinar_date && isset($livedate[1]) ? $livedate[1] : (isset($webinar_data->webinar_start_time) ? $webinar_data->webinar_start_time : 'default_value');
		$time = gmdate( 'H:i', strtotime( $time ) );
		$ex_time = explode( ':', $time );
		$ex_hr = $ex_time[0];
		$ex_min = $ex_time[1];
		$ex_sec = '00';
		$timezone_to_create = empty( $leadinfo->lead_timezone ) ? 'Asia/Beirut' : $leadinfo->lead_timezone;
		$tz = new DateTimeZone( 'AUTO' === $webinar_data->webinar_date ? $timezone_to_create : $webinar_data->webinar_timezone );
		$utc_offset = $tz->getOffset( new DateTime() ) / 3600;

		$webinar_url = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'webinar' );
		if (!is_null($webinar_url)) {
			$webinar_url = add_query_arg('live', '', $webinar_url);
		} else {
			$webinar_url = 'default_url'; 
		}
		$lead_id = '';

		if (is_array($input_get) && isset($input_get['lid'])) {
	    $lead_id = sanitize_text_field($input_get['lid']);
		} else {
	    $lead_id = 'default_value';
		}
		$webinar_url = add_query_arg( 'lid', $lead_id, $webinar_url );
		if ( WebinarignitionManager::webinarignition_is_paid_webinar( $webinar_data ) ) {
			$webinar_url = add_query_arg( md5( $webinar_data->paid_code ), '', $webinar_url );
		}
		$webinar_url = add_query_arg( 'watch_type', 'live', $webinar_url );
		$labels = array(
			__( 'Years', 'webinar-ignition' ),
			 __( 'Months', 'webinar-ignition' ),
			 __( 'Weeks', 'webinar-ignition' ),
			 __( 'Days', 'webinar-ignition' ),
			 __( 'Hours', 'webinar-ignition' ),
			 __( 'Minutes', 'webinar-ignition' ),
			 __( 'Seconds', 'webinar-ignition' ),
		);
	
	$labels1 = array(
			__( 'Year', 'webinar-ignition' ),
			 __( 'Month', 'webinar-ignition' ),
			 __( 'Week', 'webinar-ignition' ),
			 __( 'Day', 'webinar-ignition' ),
			 __( 'Hour', 'webinar-ignition' ),
			 __( 'Minute', 'webinar-ignition' ),
			 __( 'Second', 'webinar-ignition' ),
	);
	
	wp_localize_script(
			'webinarignition_js_countdown',
			'webinarData',
			array(
					'webinar_date'      => $webinar_data->webinar_date,
					'utc_offset'        => $utc_offset,
					'ex_year'           => $ex_year,
					'ex_month'          => $ex_month,
					'ex_day'            => $ex_day,
					'ex_hr'             => $ex_hr,
					'ex_min'            => $ex_min,
					'ex_sec'            => $ex_sec,
					'is_preview'        => $is_preview,
					'webinar_url'       => $webinar_url,
					'admin_ajax_url'    => admin_url( 'admin-ajax.php' ),
					'ajax_nonce'        => wp_create_nonce( 'webinarignition_ajax_nonce' ),
					'webinar_id'        => $webinar_data->id,
					'paid_status'       => $webinar_data->paid_status,
					'paid_webinar_url'  => isset($webinar_data->paid_webinar_url) ? $webinar_data->paid_webinar_url : '',
					'labels'            => $labels,
					'labels1'           => $labels1,
			)
	);

		// Footer JS
		wp_enqueue_script( 'webinarignition_after_footer_js' );
		
	}

	public static function webinarignition_enqueue_webinar_page_script( $webinar_data ) {
		$template_vars = (array) webinarignition_get_webinar_templates_vars( $webinar_data );
		extract( $template_vars );//phpcs:ignore

		wp_enqueue_style( 'webinarignition_font-awesome' );

		if ( empty( $webinar_data->custom_templates_styles ) || 'off' !== $webinar_data->custom_templates_styles ) {
			wp_enqueue_style( 'webinarignition_main_template' );
		}

		if ( webinarignition_should_use_videojs( $webinar_data ) ) {
			wp_enqueue_style( 'webinarignition_video_css' );
		}

		wp_enqueue_style( 'webinarignition_head_style_after' );

		if ( ! empty( $webinar_data->custom_webinar_css ) ) {
			wp_add_inline_style( 'webinarignition_head_style_after', esc_html( $webinar_data->custom_webinar_css ) );
		}

		wp_enqueue_style( 'webinarignition_webinar_shared' );
		
		wp_enqueue_script( 'webinarignition_cookie_js' );

		if ( ! empty( $webinar_data->custom_webinar_js ) ) {
			$custom_webinar_js = $webinar_data->custom_webinar_js;
			wp_add_inline_script( 'webinarignition_cookie_js', '(function ($) {' . $custom_webinar_js . '})(jQuery);' );
		}

		wp_enqueue_script( 'webinarignition_countdown_js' );
		wp_enqueue_script( 'webinarignition_polling_js' );
		wp_enqueue_script( 'webinarignition_updater_js' );

		if ( webinarignition_should_use_videojs( $webinar_data ) ) {
			wp_enqueue_script( 'webinarignition_video_js' );
		}

		$webinar_cta_by_position = WebinarignitionManager::webinarignition_get_webinar_cta_by_position( $webinar_data );

		if ( ! empty( $webinar_cta_by_position['overlay'] ) ) {
			wp_enqueue_script( 'webinarignition_webinar_cta_js' );
			wp_enqueue_script( 'webinarignition_webinar_modern_js' );
			wp_enqueue_script( 'webinarignition_backup_js' );
			
		}

		wp_enqueue_script( 'webinarignition_after_footer_js' );

		$after_footer_js = array(
			WEBINARIGNITION_PATH . 'inc/lp/partials/webinar_page/webinar_inline_js.php',
		);
		$webinar_data           = ! empty( $webinar_data ) ? $webinar_data : get_query_var( 'webinar_data' );
		$webinarignition_page   = ! empty( $webinarignition_page ) ? $webinarignition_page : get_query_var( 'webinarignition_page' );
		$webinar_type           = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';
		$tracking_tags_settings = isset( $webinar_data->tracking_tags ) ? $webinar_data->tracking_tags : array();
		$lead_id                = ! empty( $leadinfo->ID ) ? $leadinfo->ID : '';

		$is_replay_page = 'replay_custom' === $webinarignition_page || 'preview-replay' === $webinarignition_page || 'replay_page' === $webinarignition_page;

		$additional_autoactions_js = array();

		if ( 'evergreen' === $webinar_type ) {
			if ( 'time' === trim($webinar_data->auto_action) && ( ! empty( $webinar_data->webinar_iframe_source ) || ! empty( $webinar_data->auto_video_url ) ) ) {
					
					$cta_position_default = 'outer';
					$cta_position_allowed = 'outer';
					$cta_position_overlay_allowed = 'overlay';

					$additional_autoactions = array();

				if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && ! empty( $webinar_data->additional_autoactions ) ) {
						$additional_autoactions = maybe_unserialize( $webinar_data->additional_autoactions );
				}

				if (
					!empty($webinar_data->auto_action_time)
					// && !empty($webinar_data->auto_action_time_end)
					&& (
						!empty($webinar_data->auto_action_copy)
						|| (!empty($webinar_data->auto_action_btn_copy) && !empty($webinar_data->auto_action_url))
					)
				) {
					$webinar_main_auto_action = [
						'auto_action_time' => $webinar_data->auto_action_time,
						'auto_action_time_end' => '',
						'auto_action_copy' => '',
						'auto_action_btn_copy' => '',
						'auto_action_url' => '',
						'replay_order_color' => '#6BBA40',
					];
		
					if (!empty($webinar_data->auto_action_time_end)) {
						$webinar_main_auto_action['auto_action_time_end'] = $webinar_data->auto_action_time_end;
					}
		
					if (!empty($webinar_data->replay_order_color)) {
						$webinar_main_auto_action['replay_order_color'] = $webinar_data->replay_order_color;
					}
		
					if (!empty($webinar_data->auto_action_copy)) {
						$webinar_main_auto_action['auto_action_copy'] = $webinar_data->auto_action_copy;
					}
		
					if (!empty($webinar_data->auto_action_btn_copy) && !empty($webinar_data->auto_action_url)) {
						$webinar_main_auto_action['auto_action_btn_copy'] = $webinar_data->auto_action_btn_copy;
						$webinar_main_auto_action['auto_action_url'] = $webinar_data->auto_action_url;
					}
		
					if (!empty($webinar_data->cta_position) ) {
						$cta_position_default = $webinar_data->cta_position;
					}
		
					if ($cta_position_default === $cta_position_allowed) {
						$webinar_main_auto_action['cta_position'] = 'outer';
					} else {
						$webinar_main_auto_action['cta_position'] = 'overlay';
					}
		
					if(is_array($additional_autoactions) && !empty($additional_autoactions)) {
						$additional_autoactions = array_merge([$webinar_main_auto_action], $additional_autoactions);
					} else {
						$additional_autoactions[] = $webinar_main_auto_action;
					}
		
				}
				ksort($additional_autoactions);

				foreach ($additional_autoactions as $index => $additional_autoaction) {
					$cta_position = $cta_position_default;

					if (!empty($additional_autoaction['cta_position'])) {
						$cta_position = $additional_autoaction['cta_position'];
					}

					if (!empty($additional_autoaction['auto_action_time'])) {
						$auto_action_time_array = explode(':', $additional_autoaction['auto_action_time']);
						$delay = 10;

						if (!empty($auto_action_time_array[0])) $delay = $delay + ($auto_action_time_array[0] * 60000);
						if (!empty($auto_action_time_array[1])) $delay = $delay + (absint($auto_action_time_array[1]) * 1000);
						$start_delay = $delay;
						if ($start_delay > 10) $start_delay = $start_delay + 1000;

						if (empty($additional_autoaction['auto_action_time_end'])) {
							$delay = 0;
						} else {
							$auto_action_time_array = explode(':', $additional_autoaction['auto_action_time_end']);
							$delay = 0;
							if (!empty($auto_action_time_array[0])) $delay = $delay + ($auto_action_time_array[0] * 60000);
							if (!empty($auto_action_time_array[1])) $delay = $delay + ($auto_action_time_array[1] * 1000);
						}

						$end_delay = $delay;
						if ($end_delay > 0) $end_delay = $end_delay + 1000;
						$cta_index = 'additional-' . $index;

						$additional_autoactions_js[] = [
							'index' => $index,
							'end_delay' => $end_delay,
							'start_delay' => $start_delay,
							'is_videojs' => webinarignition_should_use_videojs( $webinar_data ),
						];
					}
				}
			}
		}
		
		$globalOffset = 0;
		if ('evergreen' !== $webinar_type) { // live webinar
			$timeStampNow               = time();
			$webinarDateTime            = $webinar_data->webinar_date . ' ' . $webinar_data->webinar_start_time ;
			$date_picked                = DateTime::createFromFormat('m-d-Y H:i', $webinarDateTime, new DateTimeZone( $webinar_data->webinar_timezone ) );
			$too_late_lockout_minutes   = !empty( $webinar_data->too_late_lockout_minutes ) ? (int) $webinar_data->too_late_lockout_minutes * 60 : 3600;
			$date_picked_timestamp      = $date_picked->getTimestamp();
			$offset = $timeStampNow - $date_picked_timestamp;
	
			if (0 > $offset) $offset = 0;
	
			if (!empty($offset)) {
				$globalOffset = $offset * 1000;
			}
		}

		$tracking_tags_timeouts = array();

		if ( WebinarignitionPowerups::webinarignition_is_multiple_cta_enabled( $webinar_data ) && ! empty( $lead_id ) && ! empty( $tracking_tags_settings ) && ! $is_replay_page ) {
				foreach ( $tracking_tags_settings as $tracking_tag ) {
						if ( empty( $tracking_tag['time'] ) || empty( $tracking_tag['name'] ) ) {
								continue;
						}

						$time  = $tracking_tag['time'];
						$name  = $tracking_tag['name'];
						$slug  = empty( $tracking_tag['slug'] ) ? '' : $tracking_tag['slug'];
						$pixel = empty( $tracking_tag['pixel'] ) ? '' : $tracking_tag['pixel'];

						$timedActionArray = explode( ':', $time );
						$minutes          = $timedActionArray[0];

						if ( ! is_numeric( $minutes ) ) {
								$minutes = 0;
						} else {
								$minutes = (int) $minutes;
						}

						$seconds = 0;

						if ( ! empty( $timedActionArray[1] ) ) {
								$seconds = $timedActionArray[1];

								if ( ! is_numeric( $seconds ) ) {
										$seconds = 0;
								} else {
										$seconds = (int) $seconds;
								}
						}

						$timedAction = ( $minutes * 60 ) + $seconds;

						if ( empty( $timedAction ) ) {
								continue;
						}

						$timedAction = $timedAction * 1000;

						$time_array = array(
								'timeout' => $timedAction,
								'time'    => $time,
								'name'    => $name,
								'slug'    => $slug,
						);

						if ( ! empty( $pixel ) ) {
								$time_array['pixel'] = $pixel;
						}

						$tracking_tags_timeouts[] = $time_array;
				}
		}
		wp_localize_script( 'webinarignition_after_footer_js', 'webinarData', array(
			'autoVideoLength' 		=> isset( $webinar_data->auto_video_length ) ? absint( $webinar_data->auto_video_length ) : 0,
			'ajaxurl' 				=> admin_url( 'admin-ajax.php' ),
			'additionalAutoactions' => $additional_autoactions_js,
			'additionalCTA'         => maybe_unserialize($webinar_data->additional_autoactions),
			'trackingTags'			=> $tracking_tags_timeouts,
			'leadId' 				=> esc_html( $lead_id ),
			'webinarType' 			=> esc_html( $webinar_type ),
			'webinarId' 			=> absint( $webinar_id ),
			'webinar'               => $webinar_data,
			'globalOffset'          => $globalOffset,
            'nonce' => wp_create_nonce( 'webinarignition_ajax_nonce' ),
		));

		if ( 'AUTO' === $webinar_data->webinar_date ) {
			wp_enqueue_script( 'webinarignition_auto_video_inline_js' );
			$lead_id = !empty($leadinfo->ID) ? $leadinfo->ID : '';
			$is_auto_login_enabled = absint(get_option('webinarignition_registration_auto_login', 1)) == 1;
			$is_user_logged_in = is_user_logged_in();
			$is_preview_page = WebinarignitionManager::webinarignition_url_is_preview_page();
			$nonce = wp_create_nonce('webinarignition_mark_lead_status');
			$webinar_id = $webinar_data->id;
			$auto_redirect_url = WebinarignitionManager::webinarignition_get_auto_redirect_url($webinar_data);
			$auto_redirect_delay = isset($webinar_data->auto_redirect_delay) ? absint($webinar_data->auto_redirect_delay) : 0;
			$individual_offset = !empty($individual_offset) ? $individual_offset : 0;
			$lid = !empty($_GET['lid']) ? $_GET['lid'] : '';
			$auto_video_length = !empty($webinar_data->auto_video_length) ? (int) $webinar_data->auto_video_length * 60 * 1000 : 0;
			$should_use_videojs = webinarignition_should_use_videojs($webinar_data);
			$languages = array(
					"Video Player" => esc_html__('Video Player', 'webinar-ignition'),
					"Play" => esc_html__('Play', 'webinar-ignition'),
					"Pause" => esc_html__('Pause', 'webinar-ignition'),
					"Replay" => esc_html__('Replay', 'webinar-ignition'),
					"Current Time" => esc_html__('Current Time', 'webinar-ignition'),
					"Duration" => esc_html__('Duration', 'webinar-ignition'),
					"Remaining Time" => esc_html__('Remaining Time', 'webinar-ignition'),
					"LIVE" => esc_html__('LIVE', 'webinar-ignition'),
					"Seek to live, currently behind live" => esc_html__('Seek to live, currently behind live', 'webinar-ignition'),
					"Seek to live, currently playing live" => esc_html__('Seek to live, currently playing live', 'webinar-ignition'),
					"Loaded" => esc_html__('Loaded', 'webinar-ignition'),
					"Progress" => esc_html__('Progress', 'webinar-ignition'),
					"Fullscreen" => esc_html__('Fullscreen', 'webinar-ignition'),
					"Non-Fullscreen" => esc_html__('Exit Fullscreen', 'webinar-ignition'),
					"Mute" => esc_html__('Mute', 'webinar-ignition'),
					"Unmute" => esc_html__('Unmute', 'webinar-ignition'),
					"Audio Player" => esc_html__('Audio Player', 'webinar-ignition'),
					"Caption Settings Dialog" => esc_html__('Caption Settings Dialog', 'webinar-ignition'),
					"Close" => esc_html__('Close', 'webinar-ignition'),
					"Descriptions" => esc_html__('Descriptions', 'webinar-ignition'),
					"Text" => esc_html__('Text', 'webinar-ignition'),
					"White" => esc_html__('White', 'webinar-ignition'),
					"Black" => esc_html__('Black', 'webinar-ignition'),
					"Red" => esc_html__('Red', 'webinar-ignition'),
					"Green" => esc_html__('Green', 'webinar-ignition'),
					"Blue" => esc_html__('Blue', 'webinar-ignition'),
					"Yellow" => esc_html__('Yellow', 'webinar-ignition'),
					"Magenta" => esc_html__('Magenta', 'webinar-ignition'),
					"Cyan" => esc_html__('Cyan', 'webinar-ignition'),
					"Background" => esc_html__('Background', 'webinar-ignition'),
					"Window" => esc_html__('Window', 'webinar-ignition'),
					"Opacity" => esc_html__('Opacity', 'webinar-ignition'),
					"Slider" => esc_html__('Slider', 'webinar-ignition'),
					"Volume Level" => esc_html__('Volume Level', 'webinar-ignition'),
					"Subtitles" => esc_html__('Subtitles', 'webinar-ignition'),
					"Captions" => esc_html__('Captions', 'webinar-ignition'),
					"Chapters" => esc_html__('Chapters', 'webinar-ignition'),
					"Close Modal Dialog" => esc_html__('Close Modal Dialog', 'webinar-ignition'),
					"Descriptions off" => esc_html__('Descriptions off', 'webinar-ignition'),
					"Captions off" => esc_html__('Captions off', 'webinar-ignition'),
					"Audio Track" => esc_html__('Audio Track', 'webinar-ignition'),
					"You aborted the media playback" => esc_html__('You aborted the media playback', 'webinar-ignition'),
					"A network error caused the media download to fail part-way." => esc_html__('A network error caused the media download to fail part-way.', 'webinar-ignition'),
					"The media could not be loaded, either because the server or network failed or because the format is not supported." => esc_html__('The media could not be loaded, either because the server or network failed or because the format is not supported.', 'webinar-ignition'),
					"No compatible source was found for this media." => esc_html__('No compatible source was found for this media.', 'webinar-ignition'),
					"The media is encrypted and we do not have the keys to decrypt it." => esc_html__('The media is encrypted and we do not have the keys to decrypt it.', 'webinar-ignition'),
					"Play Video" => esc_html__('Play Video', 'webinar-ignition'),
					"Close" => esc_html__('Close', 'webinar-ignition')
			);

			wp_localize_script('webinarignition_auto_video_inline_js', 'webinarParams', array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'is_auto_login_enabled' => $is_auto_login_enabled,
					'is_preview_page' => $is_preview_page,
					'lead_id' => $lead_id,
					'webinar_id' => $webinar_id,
					'nonce' => $nonce,
					'auto_redirect_url' => $auto_redirect_url,
					'auto_redirect_delay' => $auto_redirect_delay,
					'individual_offset' => $individual_offset,
					'lid' => $lid,
					'is_user_logged_in' => $is_user_logged_in,
					'auto_video_length' => $auto_video_length,
					'should_use_videojs' => $should_use_videojs,
					'languages' => $languages
			));
		}

		wp_add_inline_script( 'webinarignition_after_footer_js',
			webinarignition_inline_js_file( $after_footer_js, $webinar_data ),
			'before'
		);

		wp_enqueue_script( 'webinarignition_webinar_shared_js' );

		wp_localize_script( 'webinarignition_webinar_shared_js', 'wiJsObj', array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'someWrong'     => __( 'Something went wrong', 'webinar-ignition' ),
		) );
	}

	private static function webinarignition_set_page_params( $webinarId, $page ) {
		global $webinarignition_shortcodes_options;

		$webinarignition_shortcodes_options[ $webinarId ]->thankyou = array();
		$webinarignition_shortcodes_options[ $webinarId ]->registration = array();
		$webinarignition_shortcodes_options[ $webinarId ]->webinar = array();

		$params_array = array(
			'assets' => $assets = WEBINARIGNITION_URL . 'inc/lp/',
		);

		if ( 'thankyou' === $page ) {
			$webinarignition_shortcodes_options[ $webinarId ]->thankyou = $params_array;
		} elseif ( 'registration' === $page ) {
			$webinarignition_shortcodes_options[ $webinarId ]->registration = $params_array;
		}
	}

	public static function webinarignition_get_dummy_lead( $webinar_data ) {
		$webinar_type = 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live';
		$webinarId = $webinar_data->id;

		if ( 'evergreen' === $webinar_type ) {
			$lead = array(
				'ID' => '11111',
				'app_id' => $webinarId,
				'name' => __( 'John Smith', 'webinar-ignition' ),
				'email' => __( 'john.smith@gmail.com', 'webinar-ignition' ),
				'phone' => '',
				'skype' => null,
				'created' => gmdate( 'F j, Y', time() - ( 60 * 60 * 1 ) ),
				'date_picked_and_live' => gmdate( 'Y-m-d H:i', time() + ( 60 * 60 * 6 ) ),
				'date_picked_and_live_check' => 'sent',
				'date_1_day_before' => gmdate( 'Y-m-d H:i', time() + ( 60 * 60 * 6 ) - ( 60 * 60 * 24 ) ),
				'date_1_day_before_check' => 'sent',
				'date_1_hour_before' => gmdate( 'Y-m-d H:i', time() + ( 60 * 60 * 6 ) - ( 60 * 60 * 1 ) ),
				'date_1_hour_before_check' => 'sent',
				'date_after_live' => gmdate( 'Y-m-d H:i', time() + ( 60 * 60 * 7 ) ),
				'date_after_live_check' => 'sent',
				'date_1_day_after' => gmdate( 'Y-m-d H:i', time() + ( 60 * 60 * 31 ) ),
				'date_1_day_after_check' => 'sent',
				'lead_timezone' => 'Asia/Beirut',
				'lead_status' => 'complete',
				'event' => 'Yes',
				'replay' => 'No',
				'trk1' => 'Optin',
				'trk2' => null,
				'trk3' => '127.0.0.1',
				'trk4' => null,
				'trk5' => null,
				'trk6' => null,
				'trk7' => null,
				'trk8' => 'no',
				'trk9' => null,
				'lead_browser_and_os' => null,
				'gdpr_data' => '',
				'hash_ID' => '',
			);
		} else {
			$lead = array(
				'ID' => '11111',
				'app_id' => $webinarId,
				'name' => __( 'John Smith', 'webinar-ignition' ),
				'email' => __( 'john.smith@gmail.com', 'webinar-ignition' ),
				'phone' => '',
				'skype' => null,
				'event' => 'No',
				'replay' => 'No',
				'trk1' => 'Optin',
				'trk2' => null,
				'trk3' => '127.0.0.1',
				'trk4' => null,
				'trk5' => null,
				'trk6' => null,
				'trk7' => null,
				'trk8' => null,
				'trk9' => null,
				'lead_browser_and_os' => null,
				'gdpr_data' => '',
				'hash_ID' => '',
				'created' => gmdate( 'F j, Y', time() - ( 60 * 60 * 1 ) ),
			);
		}//end if

		$object = new stdClass();
		foreach ( $lead as $key => $value ) {
			$object->$key = $value;
		}

		return $object;
	}
}

WebinarignitionPowerupsShortcodes::init();
