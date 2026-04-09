<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
if ( !function_exists( 'webinarignition_get_lp_header' ) ) {
    function webinarignition_get_lp_header(  $webinarId, $template_number, $webinar_data  ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        WebinarignitionManager::webinarignition_set_locale( $webinar_data );
        $custom_lp_css_path = WEBINARIGNITION_PATH . 'inc/lp/css/lp_css.php';
        if ( '02' === $template_number ) {
            $custom_lp_css_path = WEBINARIGNITION_PATH . 'inc/lp/css/ss_css.php';
        }
        include WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/header.php';
        WebinarignitionManager::webinarignition_restore_locale( $webinar_data );
    }

}
if ( !function_exists( 'webinarignition_get_lp_footer' ) ) {
    function webinarignition_get_lp_footer(
        $webinarId,
        $template_number,
        $webinar_data,
        $user_info
    ) {
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        WebinarignitionManager::webinarignition_set_locale( $webinar_data );
        include WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/footer.php';
        WebinarignitionManager::webinarignition_restore_locale( $webinar_data );
    }

}
// --------------------------------------------------------------------------------
// region Global Shortcodes
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_webinar_title' ) ) {
    function webinarignition_get_webinar_title(  $webinar_data, $display = false  ) {
        extract( webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        echo ( !empty( $webinar_data->webinar_desc ) ? wp_kses_post( $webinar_data->webinar_desc ) : '' );
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_host_name' ) ) {
    function webinarignition_get_host_name(  $webinar_data, $display = false  ) {
        extract( webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        echo ( !empty( $webinar_data->webinar_host ) ? wp_kses_post( $webinar_data->webinar_host ) : '' );
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_webinar_giveaway_compact' ) ) {
    function webinarignition_get_webinar_giveaway_compact(  $webinar_data, $display = false  ) {
        extract( webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        if ( 'hide' !== trim( $webinar_data->webinar_giveaway_toggle ) ) {
            webinarignition_display( $webinar_data->webinar_giveaway, '<h4>' . __( 'Your Awesome Free Gift', 'webinar-ignition' ) . '</h4><p>' . __( 'You can download this awesome report made you...', 'webinar-ignition' ) . '</p><p>[ ' . __( 'DOWNLOAD HERE', 'webinar-ignition' ) . ']</p>' );
        } else {
            echo '';
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lead_name' ) ) {
    function webinarignition_get_lead_name(  $webinar_data, $display = false  ) {
        extract( webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        if ( !empty( $leadinfo->name ) ) {
            echo esc_html( $leadinfo->name );
        } else {
            echo '';
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo esc_html( $html );
    }

}
if ( !function_exists( 'webinarignition_get_lead_email' ) ) {
    function webinarignition_get_lead_email(  $webinar_data, $display = false  ) {
        extract( webinarignition_get_global_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        if ( !empty( $leadinfo->email ) ) {
            echo esc_html( $leadinfo->email );
        } else {
            echo '';
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo esc_html( $html );
    }

}
// --------------------------------------------------------------------------------
// region Registration Page
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_lp_banner_short' ) ) {
    function webinarignition_get_lp_banner_short(  $webinar_data, $display = false  ) {
        $html = '';
        if ( 'show' === $webinar_data->lp_banner_bg_style ) {
            $uid = wp_unique_id( 'topArea-' );
            $background_color = ( empty( $webinar_data->lp_banner_bg_color ) ? '#FFF' : $webinar_data->lp_banner_bg_color );
            $background_image = ( empty( $webinar_data->lp_banner_bg_repeater ) ? 'border-top: 3px solid rgba(0,0,0,0.20); border-bottom: 3px solid rgba(0,0,0,0.20);' : "background-image: url({$webinar_data->lp_banner_bg_repeater});" );
            ob_start();
            ?>
			<style>
			.topArea.<?php 
            echo esc_attr( $uid );
            ?> {
				<?php 
            if ( 'hide' === $webinar_data->lp_banner_bg_style ) {
                echo 'display: none;';
            }
            ?>
				background-color: <?php 
            echo esc_attr( $background_color );
            ?>;
				<?php 
            echo wp_kses_post( $background_image );
            ?>
			}
			</style>
			<div class="topArea <?php 
            echo esc_attr( $uid );
            ?>">
				<div class="bannerTop">
					<?php 
            if ( !empty( $webinar_data->lp_banner_image ) ) {
                echo '<img src="' . esc_url( $webinar_data->lp_banner_image ) . '" alt="Webinar Banner Image" />';
            }
            ?>
				</div>
			</div>
			<?php 
            $html = ob_get_clean();
        }
        //end if
        if ( !$display ) {
            return $html;
        }
        echo wp_kses( $html, array(
            'style' => array(),
            'div'   => array(
                'class' => true,
                'style' => true,
            ),
            'img'   => array(
                'src' => true,
                'alt' => true,
            ),
        ) );
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_banner' ) ) {
    function webinarignition_get_lp_banner(  $webinar_data, $display = false  ) {
        $html = '';
        if ( isset( $webinar_data->lp_banner_bg_style ) && 'show' === $webinar_data->lp_banner_bg_style ) {
            $uid = wp_unique_id( 'topArea-' );
            $background_color = ( empty( $webinar_data->lp_banner_bg_color ) ? '#FFF' : $webinar_data->lp_banner_bg_color );
            $background_image = ( empty( $webinar_data->lp_banner_bg_repeater ) ? 'border-top: 3px solid rgba(0,0,0,0.20); border-bottom: 3px solid rgba(0,0,0,0.20);' : "background-image: url({$webinar_data->lp_banner_bg_repeater}); background-repeat: repeat;" );
            ob_start();
            ?>
			<style>
			.topArea.<?php 
            echo esc_attr( $uid );
            ?> {
				<?php 
            if ( 'hide' === $webinar_data->lp_banner_bg_style ) {
                echo 'display: none;';
            }
            ?>
				background-color: <?php 
            echo esc_attr( $background_color );
            ?>;
				<?php 
            echo wp_kses_post( $background_image );
            ?>
			}
			</style>
			<div class="topArea <?php 
            echo esc_attr( $uid );
            ?>">
				<div class="bannerTop container">
					<div class="row">
						<?php 
            if ( !empty( $webinar_data->lp_banner_image ) ) {
                echo '<img src="' . esc_url( $webinar_data->lp_banner_image ) . '" alt="Webinar Banner Image" />';
            }
            ?>
					</div>
				</div>
			</div>
			<?php 
            $html = ob_get_clean();
        }
        //end if
        if ( !$display ) {
            return $html;
        }
        echo wp_kses( $html, array(
            'style' => array(),
            'div'   => array(
                'class' => true,
                'style' => true,
            ),
            'img'   => array(
                'src' => true,
                'alt' => true,
            ),
        ) );
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_main_headline' ) ) {
    function webinarignition_get_lp_main_headline(  $webinar_data, $display = false  ) {
        if ( empty( $webinar_data->lp_main_headline ) ) {
            $html = '';
        } else {
            ob_start();
            webinarignition_display( $webinar_data->lp_main_headline, '' );
            $html = ob_get_clean();
        }
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_video_area' ) ) {
    function webinarignition_get_video_area(  $webinar_data, $display = false, $content_width = null  ) {
        global $webinarignition_shortcode_params;
        $settings_language = ( isset( $webinar_data->settings_language ) ? $webinar_data->settings_language : '' );
        if ( !empty( $settings_language ) ) {
            switch_to_locale( $settings_language );
            unload_textdomain( 'webinar-ignition' );
            load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $settings_language . '.mo' );
        }
        $uid = wp_unique_id( 'ctaAreaVideo-' );
        $assets = WEBINARIGNITION_URL . 'inc/lp/';
        $shortcode_params = ( !is_null( $webinarignition_shortcode_params ) ? (array) $webinarignition_shortcode_params[$webinar_data->id] : array() );
        $video_source = ( !empty( $shortcode_params['custom_video_url'] ) ? esc_url( $shortcode_params['custom_video_url'] ) : (( property_exists( $webinar_data, 'lp_cta_video_url' ) ? esc_url( $webinar_data->lp_cta_video_url ) : '' )) );
        $no_border = ( !empty( $shortcode_params['border'] ) ? wp_validate_boolean( $shortcode_params['border'] ) : false );
        ob_start();
        ?>
		<div class="ctaArea video <?php 
        echo esc_attr( $uid );
        echo ( !$no_border ? ' no-player-border' : '' );
        ?>">
			<?php 
        if ( !empty( $content_width ) && property_exists( $webinar_data, 'lp_cta_video_code' ) && has_shortcode( $webinar_data->lp_cta_video_code, 'video' ) ) {
            $GLOBALS['content_width'] = $content_width;
            // see /wp-includes/media.php::wp_video_shortcode();
        }
        ?>

			<?php 
        if ( empty( $webinar_data->lp_cta_type ) || 'video' === $webinar_data->lp_cta_type || !empty( $shortcode_params['custom_video_url'] ) ) {
            if ( !empty( $video_source ) ) {
                $is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
                wp_enqueue_style( 'webinarignition_video_css' );
                wp_enqueue_script( 'webinarignition_video_js' );
                $btn_color = ( isset( $webinar_data->lp_optin_btn_color ) && $webinar_data->lp_optin_btn_color !== '' ? $webinar_data->lp_optin_btn_color : '#74BB00' );
                $color_array = webinarignition_btn_color( $btn_color );
                $hover_color = $color_array['hover_color'];
                $text_color = $color_array['text_color'];
                // Add dynamic inline styles
                $dynamic_css = "\n\t\t\t\t\t\t#wi_ctaVideo .wi_arrow_button {\n\t\t\t\t\t\t\tbackground-color: {$btn_color};\n\t\t\t\t\t\t\tcolor: {$text_color};\n\t\t\t\t\t\t}\n\t\t\t\t\t\t#wi_ctaVideo .wi_arrow_button:hover {\n\t\t\t\t\t\t\tbackground-color: {$hover_color};\n\t\t\t\t\t\t\tcolor: {$text_color};\n\t\t\t\t\t\t}\n\t\t\t\t\t";
                wp_add_inline_style( 'webinarignition_main', $dynamic_css );
                ?>

					<div id="wi_ctaVideo">
						<button class="wi_arrow_button button wiButton wiButton-block wiButton-lg addedArrow wi_videoPlayerUnmute">
							<?php 
                echo esc_html( apply_filters( 'wi_cta_video_unmute_text', __( 'Unmute', 'webinar-ignition' ) ) );
                ?>
						</button>
						<button class="wi_videoPlayerMute">
							<img src="<?php 
                echo esc_url( $assets . 'images/mute.svg' );
                ?>" />
						</button>
						<video id="wi_ctaVideoPlayer" class="video-js vjs-default-skin wi_videoPlayer" disablePictureInPicture oncontextmenu="return false;" src="<?php 
                echo esc_url( $video_source );
                ?>" playsinline preload="auto" type="video/mp4">
					</div>
					<?php 
            } else {
                webinarignition_display( do_shortcode( ( isset( $webinar_data->lp_cta_video_code ) ? $webinar_data->lp_cta_video_code : '' ) ), '<img src="' . esc_url( $assets . 'images/novideo.png' ) . '" />' );
            }
            //end if
        } else {
            echo "<img src='";
            webinarignition_display( $webinar_data->lp_cta_image, esc_url( $assets . 'images/noctaimage.png' ) );
            echo "' height='281' width='500' />";
        }
        //end if
        ?>
		</div>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile',
                'required',
                'readonly'
            ], true );
        }
        echo do_shortcode( wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'    => true,
                    'class'    => true,
                    'id'       => true,
                    'data-*'   => true,
                    'required' => true,
                    'readonly' => true,
                ),
            ),
            $all_html_tags
         ) ) );
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_optin_headline' ) ) {
    function webinarignition_get_lp_optin_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_block_template( $webinar_data, 'optin-headline.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_sales_headline' ) ) {
    function webinarignition_get_lp_sales_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_block_template( $webinar_data, 'sales-headline.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_sales_copy' ) ) {
    function webinarignition_get_lp_sales_copy(  $webinar_data, $display = false  ) {
        WebinarignitionManager::webinarignition_set_locale( $webinar_data );
        ob_start();
        switch_to_locale( $webinar_data->webinar_lang );
        unload_textdomain( 'webinar-ignition' );
        load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
        ?>
		<div class="wiSalesCopy">
			<?php 
        webinarignition_display( do_shortcode( ( isset( $webinar_data->lp_sales_copy ) ? $webinar_data->lp_sales_copy : '' ) ), '<p>' . __( 'Your Amazing sales copy for your webinar would show up here...', 'webinar-ignition' ) . '</p>' );
        ?>
		</div>
		<?php 
        $html = ob_get_clean();
        restore_previous_locale();
        WebinarignitionManager::webinarignition_restore_locale( $webinar_data );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_optin_section' ) ) {
    function webinarignition_get_lp_optin_section(  $webinar_data, $display = false  ) {
        if ( isset( $_GET['register-now'] ) && !WebinarignitionManager::webinarignition_is_auto_webinar( $webinar_data ) ) {
            // Directly call these lines
            set_query_var( 'webinarignition_page', 'auto_register' );
            $html = webinarignition_display_auto_register_page( $webinar_data, $webinar_data->id );
            if ( !$display ) {
                return $html;
            }
        } else {
            $html = webinarignition_get_lp_block_template( $webinar_data, 'optin-section.php' );
            if ( !$display ) {
                return $html;
            }
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile',
                'required',
                'readonly'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'    => true,
                    'class'    => true,
                    'id'       => true,
                    'data-*'   => true,
                    'required' => true,
                    'readonly' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_lp_optin_form' ) ) {
    function webinarignition_get_lp_optin_form(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_block_template( $webinar_data, 'optin-form.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_optin_form_compact' ) ) {
    function webinarignition_get_lp_optin_form_compact(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_block_template( $webinar_data, 'optin-form.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_generate_optin_form' ) ) {
    function webinarignition_generate_optin_form(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_block_template( $webinar_data, 'optin-form-generate.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile',
                'required',
                'readonly'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'    => true,
                    'class'    => true,
                    'id'       => true,
                    'data-*'   => true,
                    'required' => true,
                    'readonly' => true,
                ),
            ),
            $all_html_tags
         ) );
        //echo  wp_kses_post($html);//phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_event_dates' ) ) {
    function webinarignition_get_lp_event_dates(  $webinar_data, $display = false  ) {
        ob_start();
        if ( isset( $webinar_data->webinar_date ) && 'AUTO' !== $webinar_data->webinar_date && isset( $webinar_data->paid_status ) && 'paid' === $webinar_data->paid_status ) {
            $paid_check = 'no';
        } else {
            $paid_check = 'yes';
        }
        // check if campaign ID is in the URL, if so, its the thank you url...
        if ( is_object( $webinar_data ) && property_exists( $webinar_data, 'paid_code' ) && isset( $input_get[$webinar_data->paid_code] ) ) {
            $paid_check = 'yes';
        }
        if ( is_object( $webinar_data ) && isset( $webinar_data->webinar_date ) && 'AUTO' === $webinar_data->webinar_date ) {
            // Evergreen
            if ( 'yes' === $paid_check ) {
                webinarignition_get_lp_auto_event_dates( $webinar_data, false, true );
            } else {
                ?>
				<div class="autoSep autoSep-a"></div>
				<?php 
            }
        } else {
            webinarignition_get_lp_live_event_dates( $webinar_data, false, true );
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
        //echo  wp_kses_post($html);//phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_event_dates' ) ) {
    function webinarignition_get_lp_event_dates(  $webinar_data, $display = false  ) {
        ob_start();
        if ( property_exists( $webinar_data, 'webinar_date' ) && 'AUTO' !== $webinar_data->webinar_date && 'paid' === $webinar_data->paid_status ) {
            $paid_check = 'no';
        } else {
            $paid_check = 'yes';
        }
        // check if campaign ID is in the URL, if so, its the thank you url...
        if ( is_object( $webinar_data ) && property_exists( $webinar_data, 'paid_code' ) && isset( $input_get[$webinar_data->paid_code] ) ) {
            $paid_check = 'yes';
        }
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_event_dates_compact' ) ) {
    function webinarignition_get_lp_event_dates_compact(  $webinar_data, $display = false  ) {
        ob_start();
        if ( 'AUTO' !== $webinar_data->webinar_date && 'paid' === $webinar_data->paid_status ) {
            $paid_check = 'no';
        } else {
            $paid_check = 'yes';
        }
        // check if campaign ID is in the URL, if so, its the thank you url...
        if ( is_object( $webinar_data ) && property_exists( $webinar_data, 'paid_code' ) && isset( $input_get[$webinar_data->paid_code] ) ) {
            $paid_check = 'yes';
        }
        if ( is_object( $webinar_data ) && 'AUTO' === $webinar_data->webinar_date ) {
            // Evergreen
            if ( 'yes' === $paid_check ) {
                webinarignition_get_lp_auto_event_dates( $webinar_data, true, true );
            } else {
                ?>
				<div class="autoSep autoSep-b"></div>
				<?php 
            }
        } else {
            webinarignition_get_lp_live_event_dates( $webinar_data, true, true );
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
        //echo  wp_kses_post($html);//phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_event_dates' ) ) {
    function webinarignition_get_lp_event_dates(  $webinar_data, $display = false  ) {
        ob_start();
        if ( 'AUTO' !== $webinar_data->webinar_date && 'paid' === $webinar_data->paid_status ) {
            $paid_check = 'no';
        } else {
            $paid_check = 'yes';
        }
        // check if campaign ID is in the URL, if so, its the thank you url...
        if ( is_object( $webinar_data ) && property_exists( $webinar_data, 'paid_code' ) && isset( $input_get[$webinar_data->paid_code] ) ) {
            $paid_check = 'yes';
        }
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_auto_event_dates' ) ) {
    function webinarignition_get_lp_auto_event_dates(  $webinar_data, $is_compact = false, $display = false  ) {
        $prefix = 'eventDate-';
        $uid = wp_unique_id( $prefix );
        ob_start();
        if ( 'fixed' === $webinar_data->lp_schedule_type ) {
            require WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/fixed-dates.php';
        } elseif ( 'delayed' === $webinar_data->lp_schedule_type ) {
            require WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/delayed-dates.php';
        } else {
            require WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/custom-dates.php';
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
        //echo  wp_kses_post($html);//phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_event_dates' ) ) {
    function webinarignition_get_lp_event_dates(  $webinar_data, $display = false  ) {
        ob_start();
        if ( 'AUTO' !== $webinar_data->webinar_date && 'paid' === $webinar_data->paid_status ) {
            $paid_check = 'no';
        } else {
            $paid_check = 'yes';
        }
        // check if campaign ID is in the URL, if so, its the thank you url...
        if ( is_object( $webinar_data ) && property_exists( $webinar_data, 'paid_code' ) && isset( $input_get[$webinar_data->paid_code] ) ) {
            $paid_check = 'yes';
        }
    }

}
if ( !function_exists( 'webinarignition_get_lp_live_event_dates' ) ) {
    function webinarignition_get_lp_live_event_dates(  $webinar_data, $is_compact = false, $display = false  ) {
        global $wp_locale;
        if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
            // old formats
            $webinar_data->time_format = get_option( 'time_format', 'H:i' );
        }
        $prefix = 'eventDate-';
        $uid = wp_unique_id( $prefix );
        $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format' ) );
        $time_format = ( isset( $webinar_data->time_format ) ? $webinar_data->time_format : '' );
        $webinarDateObject = ( isset( $webinar_data->webinar_date ) ? DateTime::createFromFormat( 'm-d-Y', $webinar_data->webinar_date ) : DateTime::createFromFormat( 'm-d-Y', gmdate( 'm-d-Y' ) ) );
        if ( $webinarDateObject instanceof DateTime ) {
            $webinarTimestamp = $webinarDateObject->getTimestamp();
            $localized_date = date_i18n( $date_format, $webinarTimestamp );
            $localized_month = $wp_locale->get_month( $webinarDateObject->format( 'm' ) );
            $localized_week_day = $wp_locale->get_weekday( $webinarDateObject->format( 'w' ) );
        }
        ob_start();
        if ( $webinarDateObject instanceof DateTime ) {
            if ( $is_compact ) {
                require WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/live-dates-compact.php';
            } else {
                require WEBINARIGNITION_PATH . 'inc/lp/partials/registration_page/live-dates.php';
            }
        }
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_host_info' ) ) {
    function webinarignition_get_lp_host_info(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_block_template( $webinar_data, 'host-info.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_lp_block_template' ) ) {
    function webinarignition_get_lp_block_template(  $webinar_data, $path  ) {
        extract( (array) webinarignition_get_lp_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        WebinarignitionManager::webinarignition_set_locale( $webinar_data );
        require_once WEBINARIGNITION_PATH . "inc/lp/partials/registration_page/{$path}";
        WebinarignitionManager::webinarignition_restore_locale( $webinar_data );
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_lp_templates_vars' ) ) {
    function webinarignition_get_lp_templates_vars(  $webinar_data  ) {
        global $webinarignition_lp_templates_vars;
        if ( empty( $webinarignition_lp_templates_vars ) ) {
            $webinarignition_lp_templates_vars = array();
        }
        $webinarignition_lp_templates_vars = array_merge( webinarignition_get_global_templates_vars( $webinar_data ), $webinarignition_lp_templates_vars );
        /**
         * @var $input_get
         * @var $is_preview
         * @var $webinar_id
         * @var $webinarId
         * @var $data
         * @var $isAuto
         * @var $pluginName
         * @var $leadinfo
         * @var $assets
         */
        extract( $webinarignition_lp_templates_vars );
        //phpcs:ignore
        if ( !isset( $webinarignition_lp_templates_vars['paid_check'] ) || !isset( $webinarignition_lp_templates_vars['paid_check_js'] ) ) {
            if ( isset( $webinar_data->paid_status ) && 'paid' === $webinar_data->paid_status ) {
                $paid_check = 'no';
            } else {
                $paid_check = 'yes';
            }
            // check if campaign ID is in the URL, if so, its the thank you url...
            if ( is_object( $webinar_data ) && property_exists( $webinar_data, 'paid_code' ) && isset( $input_get[$webinar_data->paid_code] ) ) {
                $paid_check = 'yes';
            }
            $webinarignition_lp_templates_vars['paid_check'] = $paid_check;
        }
        //end if
        if ( !isset( $webinarignition_lp_templates_vars['loginUrl'] ) || !isset( $webinarignition_lp_templates_vars['user_info'] ) ) {
            $user_info = array();
            $loginUrl = '';
            if ( !empty( $webinar_data->fb_id ) && !empty( $webinar_data->fb_secret ) ) {
                include WEBINARIGNITION_PATH . 'inc/lp/fbaccess.php';
                /**
                 * @var $user_info
                 */
                $isSigningUpWithFB = true;
                $fbUserData['name'] = $user_info['name'];
                $fbUserData['email'] = $user_info['email'];
            }
            $webinarignition_lp_templates_vars['loginUrl'] = $loginUrl;
            $webinarignition_lp_templates_vars['user_info'] = $user_info;
        }
        return $webinarignition_lp_templates_vars;
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lp_arintegration' ) ) {
    function webinarignition_get_lp_arintegration(  $webinar_data, $display = false  ) {
        $html = '';
        // if( !empty($webinar_data->ar_url) && !empty($webinar_data->ar_method)) {
        // ob_start();
        //
        ?>
	<!--        <div class="arintegration" style="display:none;">-->
	<!--            --><?php 
        // include(WEBINARIGNITION_PATH . "inc/lp/ar_form.php");
        ?>
	<!--        </div>-->
	<!--        -->
		<?php 
        // $html = ob_get_clean();
        // }
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
// --------------------------------------------------------------------------------
// region ThankYou Page
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_ty_banner' ) ) {
    function webinarignition_get_ty_banner(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_lp_banner( $webinar_data );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_headline' ) ) {
    function webinarignition_get_ty_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-headline.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_message_area' ) ) {
    function webinarignition_get_ty_message_area(  $webinar_data, $display = false  ) {
        ob_start();
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty_message_area.php' );
        $extra_output = ob_get_clean();
        $html .= $extra_output;
        $html = preg_replace( '/<style\\b[^>]*>(.*?)<\\/style>/is', '', $html );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_ty_reminders_block' ) ) {
    function webinarignition_get_ty_reminders_block(  $webinar_data, $display = false  ) {
        /**
         * @var $instantTest
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        ?>
		<div class="remindersBlock" <?php 
        echo esc_attr( $instantTest );
        ?> >
			<?php 
        webinarignition_get_ty_calendar_reminder( $webinar_data, true );
        ?>

			<!-- PHONE REMINDER -->
			<?php 
        webinarignition_get_ty_sms_reminder( $webinar_data, true );
        ?>
		</div>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
if ( !function_exists( 'webinarignition_get_ty_calendar_reminder' ) ) {
    function webinarignition_get_ty_calendar_reminder(  $webinar_data, $display = false  ) {
        if ( webinarignition_is_instant_lead( $webinar_data ) ) {
            return;
        }
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-calendar-reminder.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_calendar_reminder_google' ) ) {
    function webinarignition_get_ty_calendar_reminder_google(  $webinar_data, $display = false  ) {
        /**
         * @var $leadId
         * @var $is_preview
         * @var $input_get
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        $googleCalendarURL = '#';
        if ( !WebinarignitionManager::webinarignition_url_is_preview_page() ) {
            // If not preview page
            $calendarType = 'googlecalendar';
            if ( webinarignition_is_auto( $webinar_data ) ) {
                $calendarType .= 'A';
            }
            $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
            $googleCalendarURL = add_query_arg( array(
                $calendarType => '',
                'lid'         => $leadId,
                'id'          => $leadId,
            ), $thankyou_URL );
        }
        ob_start();
        ?>
		<a href="<?php 
        echo esc_url( $googleCalendarURL );
        ?>" target="_blank"><?php 
        webinarignition_display( $webinar_data->ty_calendar_google, __( 'Google Calendar', 'webinar-ignition' ) );
        ?></a>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
if ( !function_exists( 'webinarignition_get_ty_calendar_reminder_outlook' ) ) {
    function webinarignition_get_ty_calendar_reminder_outlook(  $webinar_data, $display = false  ) {
        /**
         * @var $leadId
         * @var $is_preview
         * @var $input_get["webinar"]
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        $iCalendarURL = '#';
        if ( !WebinarignitionManager::webinarignition_url_is_preview_page() ) {
            // If not preview page
            $calendarType = 'ics';
            if ( webinarignition_is_auto( $webinar_data ) ) {
                if ( $lead && 'yes' === $lead->trk8 ) {
                    return;
                    // Skip rendering for instant leads
                }
                $calendarType .= 'A';
            }
            $thankyou_URL = WebinarignitionManager::webinarignition_get_permalink( $webinar_data, 'thank_you' );
            $iCalendarURL = add_query_arg( array(
                $calendarType => '',
                'lid'         => $leadId,
                'id'          => $leadId,
            ), $thankyou_URL );
        }
        ob_start();
        ?>
		<a href="<?php 
        echo esc_url( $iCalendarURL );
        ?>" target="_blank"><?php 
        webinarignition_display( $webinar_data->ty_calendar_ical, __( 'iCal / Outlook', 'webinar-ignition' ) );
        ?></a>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
if ( !function_exists( 'webinarignition_is_instant_lead' ) ) {
    function webinarignition_is_instant_lead(  $webinar_data  ) {
        if ( isset( $webinar_data->webinar_date ) && 'AUTO' === $webinar_data->webinar_date ) {
            extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
            //phpcs:ignore
            return isset( $lead ) && isset( $lead->trk8 ) && 'yes' === $lead->trk8;
        }
        return false;
    }

}
if ( !function_exists( 'webinarignition_get_ty_sms_reminder' ) ) {
    function webinarignition_get_ty_sms_reminder(  $webinar_data, $display = false  ) {
        if ( webinarignition_is_instant_lead( $webinar_data ) ) {
            return;
        }
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-sms-reminder.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_sms_reminder_compact' ) ) {
    function webinarignition_get_ty_sms_reminder_compact(  $webinar_data, $display = false  ) {
        if ( webinarignition_is_instant_lead( $webinar_data ) ) {
            return;
        }
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-sms-reminder-compact.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_webinar_url' ) ) {
    function webinarignition_get_ty_webinar_url(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-webinar-url.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_ty_webinar_url_inline' ) ) {
    function webinarignition_get_ty_webinar_url_inline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-webinar-url-inline.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_ticket_date' ) ) {
    function webinarignition_get_ty_ticket_date(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-ticket-date.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_date_time_inline' ) ) {
    function webinarignition_get_date_time_inline(  $webinar_data, $display = false  ) {
        /**
         * @var $is_preview
         * @var $autoDate_format
         * @var $autoTime
         * @var $isAuto
         * @var $autoTimeNoTZ
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        echo esc_attr( $autoDate_format );
        echo ' ' . esc_attr( $autoTimeNoTZ );
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
//end if
if ( !function_exists( 'webinarignition_get_date_inline' ) ) {
    function webinarignition_get_date_inline(  $webinar_data, $display = false  ) {
        /**
         * @var $autoDate_format
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        ?>
		<div class="wi-date-inline">
			<?php 
        echo esc_html( $autoDate_format );
        ?>
		</div>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_time_inline' ) ) {
    function webinarignition_get_time_inline(  $webinar_data, $display = false  ) {
        /**
         * @var $autoTimeNoTZ
         * @var $autoTime
         * @var $isAuto
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        ?>
		<div class="wi-time-inline">
			<?php 
        echo esc_html( $autoTimeNoTZ );
        ?>
		</div>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_timezone_inline' ) ) {
    function webinarignition_get_timezone_inline(  $webinar_data, $display = false  ) {
        /**
         * @var $autoTimeTZ
         * @var $autoTimeNoTZ
         * @var $autoTime
         * @var $isAuto
         */
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        // phpcs:ignore
        ob_start();
        ?>
		<div class="wi-timezone-inline">
			<?php 
        echo esc_html( $webinar_data->webinar_timezone ) . ' ' . esc_html( $autoTimeTZ );
        ?>
		</div>
		<?php 
        $html = ob_get_clean();
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        // phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_ticket_webinar' ) ) {
    function webinarignition_get_ty_ticket_webinar(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-ticket-webinar.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_ticket_webinar_inline' ) ) {
    function webinarignition_get_ty_ticket_webinar_inline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-ticket-webinar-inline.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_ticket_host' ) ) {
    function webinarignition_get_ty_ticket_host(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-ticket-host.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_ticket_host_inline' ) ) {
    function webinarignition_get_ty_ticket_host_inline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-ticket-host-inline.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_countdown' ) ) {
    function webinarignition_get_ty_countdown(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-countdown.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_countdown_compact' ) ) {
    function webinarignition_get_ty_countdown_compact(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-countdown-compact.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_share_gift' ) ) {
    function webinarignition_get_ty_share_gift(  $webinar_data, $display = false  ) {
        if ( isset( $webinar_data->ty_share_toggle ) && 'none' === $webinar_data->ty_share_toggle ) {
            $html = '';
        } else {
            $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-share-gift.php' );
        }
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_share_gift_compact' ) ) {
    function webinarignition_get_ty_share_gift_compact(  $webinar_data, $display = false  ) {
        if ( 'none' === $webinar_data->ty_share_toggle ) {
            $html = '';
        } else {
            $html = webinarignition_get_ty_block_template( $webinar_data, 'ty-share-gift-compact.php' );
        }
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_ty_block_template' ) ) {
    function webinarignition_get_ty_block_template(  $webinar_data, $path  ) {
        extract( (array) webinarignition_get_ty_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        require_once WEBINARIGNITION_PATH . "inc/lp/partials/thank_you_page/{$path}";
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_ty_templates_vars' ) ) {
    function webinarignition_get_ty_templates_vars(  $webinar_data  ) {
        global $webinarignition_ty_templates_vars;
        $date_format = ( !empty( $webinar_data->date_format ) ? $webinar_data->date_format : get_option( 'date_format' ) );
        if ( !empty( $webinar_data->time_format ) && ('12hour' === $webinar_data->time_format || '24hour' === $webinar_data->time_format) ) {
            // old formats
            $webinar_data->time_format = get_option( 'time_format', 'H:i' );
        }
        if ( empty( $webinarignition_ty_templates_vars ) ) {
            $webinarignition_ty_templates_vars = array();
        }
        $webinarignition_ty_templates_vars = array_merge( webinarignition_get_global_templates_vars( $webinar_data ), $webinarignition_ty_templates_vars );
        /**
         * @var $input_get
         * @var $is_preview
         * @var $webinar_id
         * @var $webinarId
         * @var $data
         * @var $isAuto
         * @var $pluginName
         * @var $leadinfo
         * @var $assets
         */
        extract( $webinarignition_ty_templates_vars );
        //phpcs:ignore
        if ( !isset( $webinarignition_ty_templates_vars['instantTest'] ) ) {
            $instantTest = '';
            if ( $isAuto && !empty( $lead ) && !empty( $lead->trk8 ) && 'yes' === $lead->trk8 ) {
                $instantTest = "style='display:none;'";
            }
            $webinarignition_ty_templates_vars['instantTest'] = $instantTest;
        }
        if ( !isset( $webinarignition_ty_templates_vars['autoTZ'] ) || !isset( $webinarignition_ty_templates_vars['autoDate_format'] ) || !isset( $webinarignition_ty_templates_vars['autoTime'] ) || !isset( $webinarignition_ty_templates_vars['liveEventMonth'] ) || !isset( $webinarignition_ty_templates_vars['liveEventDateDigit'] ) ) {
            if ( $isAuto ) {
                $autoDate_format = webinarignition_display_date( $webinar_data, $lead );
                $autoTime = webinarignition_display_time( $webinar_data, $lead );
                $autoTimeNoTZ = webinarignition_display_time( $webinar_data, $lead, false );
                $liveEventMonth = webinarignition_event_month( $webinar_data, $lead );
                $liveEventDateDigit = webinarignition_event_day( $webinar_data, $lead );
                $autoTZ = false;
                $webinarignition_ty_templates_vars['autoTimeNoTZ'] = $autoTimeNoTZ;
            } else {
                $autoDate_format = webinarignition_get_translated_date( $webinar_data->webinar_date, 'm-d-Y', $date_format );
                $time_format = $webinar_data->time_format;
                $autoTime_format = $webinar_data->webinar_start_time;
                $timeonly = ( empty( $webinar_data->display_tz ) || !empty( $webinar_data->display_tz ) && 'yes' === $webinar_data->display_tz ? false : true );
                $autoTime = webinarignition_get_time_tz(
                    $autoTime_format,
                    $time_format,
                    $webinar_data->webinar_timezone,
                    false,
                    $timeonly
                );
                $autoTimeNoTZ = webinarignition_get_time_tz(
                    $autoTime_format,
                    $time_format,
                    $webinar_data->webinar_timezone,
                    false,
                    $timeonly
                );
                $autoTimeTZ = webinarignition_get_time_tz(
                    $autoTime_format,
                    $time_format,
                    $webinar_data->webinar_timezone,
                    true,
                    $timeonly
                );
                $webinarignition_ty_templates_vars['autoTimeNoTZ'] = $autoTimeNoTZ;
                $webinarignition_ty_templates_vars['autoTimeTZ'] = $autoTimeTZ;
                $dtz = new DateTimeZone($webinar_data->webinar_timezone);
                $time_in_sofia = new DateTime('now', $dtz);
                $autoTZ = $dtz->getOffset( $time_in_sofia ) / 3600;
                $autoTZ = ( $autoTZ < 0 ? $autoTZ : '+' . $autoTZ );
            }
            //end if
            $webinarignition_ty_templates_vars['autoTZ'] = $autoTZ;
            $webinarignition_ty_templates_vars['autoDate_format'] = $autoDate_format;
            $webinarignition_ty_templates_vars['autoTime'] = $autoTime;
        }
        //end if
        return $webinarignition_ty_templates_vars;
    }

}
//end if
// --------------------------------------------------------------------------------
// region Countdown page
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_countdown_main_headline' ) ) {
    function webinarignition_get_countdown_main_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_countdown_block_template( $webinar_data, 'main-headline-area.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_countdown_headline' ) ) {
    function webinarignition_get_countdown_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_countdown_block_template( $webinar_data, 'headline-area.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_countdown_counter' ) ) {
    function webinarignition_get_countdown_counter(  $webinar_data, $display = false  ) {
        if ( !property_exists( $webinar_data, 'webinar_date' ) ) {
            return '';
        }
        $html = webinarignition_get_countdown_block_template( $webinar_data, 'counter.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_countdown_signup' ) ) {
    function webinarignition_get_countdown_signup(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_countdown_block_template( $webinar_data, 'signup-area.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_countdown_block_template' ) ) {
    function webinarignition_get_countdown_block_template(  $webinar_data, $path  ) {
        extract( webinarignition_get_countdown_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        require_once WEBINARIGNITION_PATH . "inc/lp/partials/countdown_page/{$path}";
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_countdown_templates_vars' ) ) {
    function webinarignition_get_countdown_templates_vars(  $webinar_data  ) {
        global $webinarignition_countdown_templates_vars;
        if ( empty( $webinarignition_countdown_templates_vars ) ) {
            $webinarignition_countdown_templates_vars = array();
        }
        $webinarignition_countdown_templates_vars = array_merge( webinarignition_get_global_templates_vars( $webinar_data ), $webinarignition_countdown_templates_vars );
        return $webinarignition_countdown_templates_vars;
    }

}
// end region
// --------------------------------------------------------------------------------
// region Replay page
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_replay_main_headline' ) ) {
    function webinarignition_get_replay_main_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_replay_block_template( $webinar_data, 'main-headline-area.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_replay_video_under_cta' ) ) {
    function webinarignition_get_replay_video_under_cta(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_replay_block_template( $webinar_data, 'webinar-cta.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_replay_video' ) ) {
    function webinarignition_get_replay_video(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_replay_block_template( $webinar_data, 'webinar-video.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_replay_info' ) ) {
    function webinarignition_get_replay_info(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_replay_block_template( $webinar_data, 'webinar-info.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_replay_giveaway' ) ) {
    function webinarignition_get_replay_giveaway(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_replay_block_template( $webinar_data, 'webinar-giveaway.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_replay_headline' ) ) {
    function webinarignition_get_replay_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_replay_block_template( $webinar_data, 'headline-area.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_replay_block_template' ) ) {
    function webinarignition_get_replay_block_template(  $webinar_data, $path  ) {
        extract( webinarignition_get_replay_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        require_once WEBINARIGNITION_PATH . "inc/lp/partials/replay_page/{$path}";
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_replay_templates_vars' ) ) {
    function webinarignition_get_replay_templates_vars(  $webinar_data  ) {
        global $webinarignition_replay_templates_vars;
        if ( empty( $webinarignition_replay_templates_vars ) ) {
            $webinarignition_replay_templates_vars = array();
        }
        $webinarignition_replay_templates_vars = array_merge( webinarignition_get_global_templates_vars( $webinar_data ), $webinarignition_replay_templates_vars );
        return $webinarignition_replay_templates_vars;
    }

}
// end region
// --------------------------------------------------------------------------------
// region Closed page
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_closed_headline' ) ) {
    function webinarignition_get_closed_headline(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_closed_block_template( $webinar_data, 'headline-area.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_closed_block_template' ) ) {
    function webinarignition_get_closed_block_template(  $webinar_data, $path  ) {
        extract( webinarignition_get_closed_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        require_once WEBINARIGNITION_PATH . "inc/lp/partials/closed_page/{$path}";
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_closed_templates_vars' ) ) {
    function webinarignition_get_closed_templates_vars(  $webinar_data  ) {
        global $webinarignition_closed_templates_vars;
        // Only get the required input values
        $input_get = [
            'payment' => sanitize_text_field( filter_input( INPUT_GET, 'payment', FILTER_UNSAFE_RAW ) ),
        ];
        if ( empty( $webinarignition_closed_templates_vars ) ) {
            $webinarignition_closed_templates_vars = array();
        }
        return $webinarignition_closed_templates_vars;
    }

}
// end region
// --------------------------------------------------------------------------------
// region Webinar page
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_webinar_video_cta_comb' ) ) {
    function webinarignition_get_webinar_video_cta_comb(  $webinar_data, $display = false  ) {
        set_query_var( 'webinarignition_page', 'webinar' );
        set_query_var( 'webinar_data', $webinar_data );
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-video-cta.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => array(),
            'abbr'       => array(),
            'address'    => array(),
            'area'       => array(),
            'article'    => array(),
            'aside'      => array(),
            'audio'      => array(),
            'b'          => array(),
            'base'       => array(),
            'bdi'        => array(),
            'bdo'        => array(),
            'blockquote' => array(),
            'body'       => array(),
            'br'         => array(),
            'button'     => array(),
            'canvas'     => array(),
            'caption'    => array(),
            'cite'       => array(),
            'code'       => array(),
            'col'        => array(),
            'colgroup'   => array(),
            'data'       => array(),
            'datalist'   => array(),
            'dd'         => array(),
            'del'        => array(),
            'details'    => array(),
            'dfn'        => array(),
            'dialog'     => array(),
            'div'        => array(),
            'dl'         => array(),
            'dt'         => array(),
            'em'         => array(),
            'embed'      => array(),
            'fieldset'   => array(),
            'figcaption' => array(),
            'figure'     => array(),
            'footer'     => array(),
            'form'       => array(),
            'h1'         => array(),
            'h2'         => array(),
            'h3'         => array(),
            'h4'         => array(),
            'h5'         => array(),
            'h6'         => array(),
            'head'       => array(),
            'header'     => array(),
            'hgroup'     => array(),
            'hr'         => array(),
            'html'       => array(),
            'i'          => array(),
            'iframe'     => array(
                'allowfullscreen' => true,
                'allow'           => true,
            ),
            'img'        => array(),
            'input'      => array(),
            'ins'        => array(),
            'kbd'        => array(),
            'keygen'     => array(),
            'label'      => array(),
            'legend'     => array(),
            'li'         => array(),
            'link'       => array(),
            'main'       => array(),
            'map'        => array(),
            'mark'       => array(),
            'menu'       => array(),
            'menuitem'   => array(),
            'meta'       => array(),
            'meter'      => array(),
            'nav'        => array(),
            'noscript'   => array(),
            'object'     => array(),
            'ol'         => array(),
            'optgroup'   => array(),
            'option'     => array(),
            'output'     => array(),
            'p'          => array(),
            'param'      => array(),
            'picture'    => array(),
            'pre'        => array(),
            'progress'   => array(),
            'q'          => array(),
            'rp'         => array(),
            'rt'         => array(),
            'ruby'       => array(),
            's'          => array(),
            'samp'       => array(),
            'script'     => array(
                'type'  => true,
                'src'   => true,
                'async' => true,
                'defer' => true,
            ),
            'section'    => array(),
            'select'     => array(),
            'small'      => array(),
            'source'     => array(),
            'span'       => array(),
            'strong'     => array(),
            'style'      => array(),
            'sub'        => array(),
            'summary'    => array(),
            'sup'        => array(),
            'table'      => array(),
            'tbody'      => array(),
            'td'         => array(),
            'textarea'   => array(),
            'tfoot'      => array(),
            'th'         => array(),
            'thead'      => array(),
            'time'       => array(),
            'title'      => array(),
            'tr'         => array(),
            'track'      => array(),
            'u'          => array(),
            'ul'         => array(),
            'var'        => array(),
            'video'      => array(),
            'wbr'        => array(),
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_merge( $attributes, array_fill_keys( array(
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ), true ) );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_webinar_video_cta_sidebar' ) ) {
    function webinarignition_get_webinar_video_cta_sidebar(  $webinar_aside, $display = false  ) {
        set_query_var( 'webinarignition_page', 'webinar' );
        // set_query_var( 'webinar_data' ,$webinar_data );
        $html = webinarignition_get_webinar_block_template( $webinar_aside, 'webinar-video-cta-sidebar.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_webinar_video_cta' ) ) {
    function webinarignition_get_webinar_video_cta(  $webinar_data, $display = false  ) {
        set_query_var( 'webinarignition_page', 'webinar' );
        set_query_var( 'webinar_data', $webinar_data );
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-video.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_webinar_sidebar' ) ) {
    function webinarignition_get_webinar_sidebar(  $webinar_data, $display = false  ) {
        set_query_var( 'webinarignition_page', 'webinar' );
        set_query_var( 'webinar_data', $webinar_data );
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-sidebar.php' );
        if ( !$display ) {
            return do_shortcode( $html );
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_webinar_video_cta_sidebar_combine' ) ) {
    function webinarignition_get_webinar_video_cta_sidebar_combine(  $webinar_data, $display = false  ) {
        set_query_var( 'webinarignition_page', 'webinar' );
        set_query_var( 'webinar_data', $webinar_data );
        ob_start();
        echo '<div class="webinar_video_cta_sidebar_combine">';
        if ( function_exists( 'webinarignition_display_replay_page' ) ) {
            echo wp_kses_post( webinarignition_display_replay_page( $webinar_data, $webinar_data->id ) );
            //phpcs:ignore
        }
        echo '</div>';
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_webinar_video_under_cta' ) ) {
    function webinarignition_get_webinar_video_under_cta(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-cta.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_webinar_video_under_overlay_cta' ) ) {
    function webinarignition_get_webinar_video_under_overlay_cta(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-overlay-cta.php' );
        if ( !$display ) {
            return $html;
        }
        $all_html_tags = array(
            'a'          => true,
            'abbr'       => true,
            'address'    => true,
            'area'       => true,
            'article'    => true,
            'aside'      => true,
            'audio'      => true,
            'b'          => true,
            'base'       => true,
            'bdi'        => true,
            'bdo'        => true,
            'blockquote' => true,
            'body'       => true,
            'br'         => true,
            'button'     => true,
            'canvas'     => true,
            'caption'    => true,
            'cite'       => true,
            'code'       => true,
            'col'        => true,
            'colgroup'   => true,
            'data'       => true,
            'datalist'   => true,
            'dd'         => true,
            'del'        => true,
            'details'    => true,
            'dfn'        => true,
            'dialog'     => true,
            'div'        => true,
            'dl'         => true,
            'dt'         => true,
            'em'         => true,
            'embed'      => true,
            'fieldset'   => true,
            'figcaption' => true,
            'figure'     => true,
            'footer'     => true,
            'form'       => true,
            'h1'         => true,
            'h2'         => true,
            'h3'         => true,
            'h4'         => true,
            'h5'         => true,
            'h6'         => true,
            'head'       => true,
            'header'     => true,
            'hgroup'     => true,
            'hr'         => true,
            'html'       => true,
            'i'          => true,
            'iframe'     => true,
            'img'        => true,
            'input'      => true,
            'ins'        => true,
            'kbd'        => true,
            'keygen'     => true,
            'label'      => true,
            'legend'     => true,
            'li'         => true,
            'link'       => true,
            'main'       => true,
            'map'        => true,
            'mark'       => true,
            'menu'       => true,
            'menuitem'   => true,
            'meta'       => true,
            'meter'      => true,
            'nav'        => true,
            'noscript'   => true,
            'object'     => true,
            'ol'         => true,
            'optgroup'   => true,
            'option'     => true,
            'output'     => true,
            'p'          => true,
            'param'      => true,
            'picture'    => true,
            'pre'        => true,
            'progress'   => true,
            'q'          => true,
            'rp'         => true,
            'rt'         => true,
            'ruby'       => true,
            's'          => true,
            'samp'       => true,
            'script'     => true,
            'section'    => true,
            'select'     => true,
            'small'      => true,
            'source'     => true,
            'span'       => true,
            'strong'     => true,
            'style'      => true,
            'sub'        => true,
            'summary'    => true,
            'sup'        => true,
            'table'      => true,
            'tbody'      => true,
            'td'         => true,
            'textarea'   => true,
            'tfoot'      => true,
            'th'         => true,
            'thead'      => true,
            'time'       => true,
            'title'      => true,
            'tr'         => true,
            'track'      => true,
            'u'          => true,
            'ul'         => true,
            'var'        => true,
            'video'      => true,
            'wbr'        => true,
        );
        foreach ( $all_html_tags as $tag => $attributes ) {
            $all_html_tags[$tag] = array_fill_keys( [
                'class',
                'id',
                'style',
                'src',
                'href',
                'alt',
                'title',
                'type',
                'value',
                'name',
                'target',
                'action',
                'method',
                'checked',
                'selected',
                'placeholder',
                'width',
                'height',
                'border',
                'align',
                'valign',
                'lang',
                'xml:lang',
                'aria-label',
                'role',
                'data-*',
                'aria-hidden',
                'aria-labelledby',
                'aria-describedby',
                'rel',
                'media',
                'accept',
                'accept-charset',
                'charset',
                'async',
                'defer',
                'property',
                'http-equiv',
                'content',
                'viewBox',
                'd',
                'x',
                'y',
                'viewbox',
                'preserveAspectRatio',
                'xmlns',
                'version',
                'baseProfile'
            ], true );
        }
        echo wp_kses( $html, array_merge( 
            wp_kses_allowed_html( 'post' ),
            // Allow default WordPress post tags and attributes.
            array(
                '*' => array(
                    'style'  => true,
                    'class'  => true,
                    'id'     => true,
                    'data-*' => true,
                ),
            ),
            $all_html_tags
         ) );
    }

}
if ( !function_exists( 'webinarignition_get_webinar_info' ) ) {
    function webinarignition_get_webinar_info(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-info.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_webinar_giveaway' ) ) {
    function webinarignition_get_webinar_giveaway(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-giveaway.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_webinar_qa' ) ) {
    function webinarignition_get_webinar_qa(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-qa.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_webinar_qa_compact' ) ) {
    function webinarignition_get_webinar_qa_compact(  $webinar_data, $display = false  ) {
        $html = webinarignition_get_webinar_block_template( $webinar_data, 'webinar-qa-compact.php' );
        if ( !$display ) {
            return $html;
        }
        echo wp_kses_post( $html );
        //phpcs:ignore
    }

}
if ( !function_exists( 'webinarignition_get_webinar_block_template' ) ) {
    function webinarignition_get_webinar_block_template(  $webinar_data, $path  ) {
        extract( (array) webinarignition_get_webinar_templates_vars( $webinar_data ) );
        //phpcs:ignore
        ob_start();
        require_once WEBINARIGNITION_PATH . "inc/lp/partials/webinar_page/{$path}";
        return ob_get_clean();
    }

}
if ( !function_exists( 'webinarignition_get_webinar_templates_vars' ) ) {
    function webinarignition_get_webinar_templates_vars(  $webinar_data  ) {
        global $webinarignition_webinar_templates_vars;
        if ( empty( $webinarignition_webinar_templates_vars ) ) {
            $webinarignition_webinar_templates_vars = array();
        }
        $webinarignition_webinar_templates_vars = array_merge( webinarignition_get_global_templates_vars( $webinar_data ), $webinarignition_webinar_templates_vars );
        /**
         * @var $input_get
         * @var $webinar_id
         * @var $webinarId
         * @var $data
         * @var $isAuto
         * @var $pluginName
         * @var $leadinfo
         * @var $assets
         */
        extract( $webinarignition_webinar_templates_vars );
        //phpcs:ignore
        if ( !isset( $webinarignition_webinar_templates_vars['individual_offset'] ) ) {
            global $wpdb;
            $individual_offset = 0;
            if ( 'AUTO' === $webinar_data->webinar_date ) {
                $evergreen_leads_table = $wpdb->prefix . 'webinarignition_leads_evergreen';
                $individual_offset = 0;
                if ( !empty( $input_get['lid'] ) && wp_validate_boolean( $input_get['lid'] ) ) {
                    if ( !empty( $leadinfo->ID ) ) {
                        $lead_row = $wpdb->get_row( $wpdb->prepare( "SELECT date_picked_and_live FROM {$evergreen_leads_table} WHERE id = %d ", $leadinfo->ID ) );
                        if ( !empty( $lead_row ) ) {
                            $st_timestamp = strtotime( $lead_row->date_picked_and_live );
                            $individual_offset = time() - $st_timestamp;
                        }
                    }
                }
            }
            $webinarignition_webinar_templates_vars['individual_offset'] = $individual_offset;
        }
        if ( !isset( $webinarignition_webinar_templates_vars['webinarignition_page'] ) ) {
            $webinarignition_webinar_templates_vars['webinarignition_page'] = 'webinar';
        }
        return $webinarignition_webinar_templates_vars;
    }

}
//end if
// end region
// --------------------------------------------------------------------------------
// region Template vars
// --------------------------------------------------------------------------------
if ( !function_exists( 'webinarignition_get_global_templates_vars' ) ) {
    function webinarignition_get_global_templates_vars(  $webinar_data  ) {
        global $webinarignition_global_templates_vars;
        if ( empty( $webinarignition_global_templates_vars ) ) {
            $webinarignition_global_templates_vars = array();
        }
        $input_get = filter_input_array( INPUT_GET );
        extract( $webinarignition_global_templates_vars );
        //phpcs:ignore
        $webinarignition_global_templates_vars['input_get'] = $input_get;
        if ( !isset( $is_preview ) ) {
            $is_preview = WebinarignitionManager::webinarignition_url_is_preview_page();
            $webinarignition_global_templates_vars['is_preview'] = $is_preview;
        }
        if ( empty( $webinarId ) || empty( $webinar_id ) ) {
            $webinarId = $webinar_data->id;
            $webinarignition_global_templates_vars['webinarId'] = $webinarId;
            $webinarignition_global_templates_vars['webinar_id'] = $webinarId;
        }
        if ( !isset( $is_webinar_available ) ) {
        }
        if ( !isset( $data ) ) {
            global $wpdb;
            $db_table_name = $wpdb->prefix . 'webinarignition';
            $data = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$db_table_name} WHERE id = %d", $webinarId ), OBJECT );
            $webinarignition_global_templates_vars['data'] = $data;
        }
        if ( !isset( $isAuto ) ) {
            $isAuto = webinarignition_is_auto( $webinar_data );
            $webinarignition_global_templates_vars['isAuto'] = $isAuto;
        }
        if ( empty( $pluginName ) ) {
            $webinarignition_global_templates_vars['pluginName'] = 'webinarignition';
        }
        if ( !isset( $leadId ) || !isset( $lead ) ) {
            if ( !empty( $is_preview ) ) {
                $lead = WebinarignitionPowerupsShortcodes::webinarignition_get_dummy_lead( $webinar_data );
                $leadId = $lead->ID;
            } else {
                $lead = false;
                $leadId = '';
                if ( !empty( $input_get['lid'] ) ) {
                    $leadId = $input_get['lid'];
                }
                if ( empty( $leadId ) && !empty( $input_get['email'] ) ) {
                    $is_lead_protected = !empty( $webinar_data->protected_lead_id ) && 'protected' === $webinar_data->protected_lead_id;
                    $getLiveIDByEmail = webinarignition_live_get_lead_by_email( $webinarId, $input_get['email'], $is_lead_protected );
                    $leadId = ( isset( $getLiveIDByEmail->ID ) ? $getLiveIDByEmail->ID : '' );
                }
                /*
                				if ( ! empty( $_COOKIE[ 'we-trk-' . $webinarId ] ) ) {
                					$leadId = ! empty( $input_get['lid'] ) ? $input_get['lid'] : $_COOKIE[ 'we-trk-' . $webinarId ];
                				}*/
                if ( !empty( $leadId ) ) {
                    $lead = webinarignition_get_lead_info( $leadId, $webinar_data );
                }
            }
            //end if
            $webinarignition_global_templates_vars['leadId'] = $leadId;
            $webinarignition_global_templates_vars['lead'] = $lead;
        }
        //end if
        if ( !isset( $leadinfo ) ) {
            $leadinfo = $lead;
            $webinarignition_global_templates_vars['leadinfo'] = $lead;
        }
        if ( !isset( $webinar_status ) && !empty( $lead ) ) {
            $webinarignition_global_templates_vars['webinar_status'] = webinarignition_get_lead_status( $webinar_data, $lead );
        }
        if ( empty( $assets ) ) {
            $webinarignition_global_templates_vars['assets'] = WEBINARIGNITION_URL . 'inc/lp/';
        }
        return $webinarignition_global_templates_vars;
    }

}
//end if
if ( !function_exists( 'webinarignition_get_lead_status' ) ) {
    function webinarignition_get_lead_status(  $webinar_data, $lead = null  ) {
        if ( empty( $webinar_data ) || webinarignition_is_auto( $webinar_data ) && empty( $lead ) ) {
            // lead is required only for auto webinar
            return;
            // bail here
        }
        $lead_status = 'countdown';
        $watch_type = 'live';
        if ( !empty( filter_input( INPUT_GET, 'watch_type', FILTER_SANITIZE_SPECIAL_CHARS ) ) ) {
            $watch_type = sanitize_text_field( trim( filter_input( INPUT_GET, 'watch_type', FILTER_SANITIZE_SPECIAL_CHARS ) ) );
        }
        if ( webinarignition_is_auto( $webinar_data ) ) {
            // Get lead status from the URL when pre-viewing
            if ( WebinarignitionManager::webinarignition_url_is_preview_page() ) {
                $lead_status = 'countdown';
                if ( !empty( filter_input( INPUT_GET, 'countdown', FILTER_UNSAFE_RAW ) ) ) {
                    $lead_status = 'countdown';
                } elseif ( !empty( filter_input( INPUT_GET, 'webinar', FILTER_UNSAFE_RAW ) ) ) {
                    $lead_status = 'live';
                } elseif ( !empty( filter_input( INPUT_GET, 'replay', FILTER_UNSAFE_RAW ) ) ) {
                    $lead_status = 'replay';
                } else {
                    $lead_status = '';
                }
            } else {
                $webinar_timezone = webinarignition_get_webinar_timezone( $webinar_data, null, $lead );
                if ( preg_match( '/UTC([+-]\\d+(\\.\\d+)?)/', $webinar_timezone, $matches ) ) {
                    // Convert offset to valid "+HH:MM" format
                    $offset = floatval( $matches[1] );
                    $hours = floor( $offset );
                    $minutes = ($offset - $hours) * 60;
                    $webinar_timezone = sprintf( '%+03d:%02d', $hours, abs( $minutes ) );
                } else {
                    // Assume the input is already a valid timezone identifier
                    $webinar_timezone = $webinar_timezone;
                }
                $datetime_lead = date_create( $lead->date_picked_and_live, new DateTimeZone($webinar_timezone) );
                $video_length_in_minutes = absint( $webinar_data->auto_video_length );
                if ( empty( $video_length_in_minutes ) ) {
                    $video_length_in_minutes = 60;
                }
                $replay_length_in_days = ( empty( sanitize_text_field( $webinar_data->auto_replay ) ) ? 3 : absint( $webinar_data->auto_replay ) );
                if ( $datetime_lead ) {
                    // If valid datetime provided
                    $datetime_now = date_create( 'now', new DateTimeZone($webinar_timezone) );
                    // Live start timestamp
                    $lead_live_start_ts = $datetime_lead->getTimestamp();
                    // Live end timestamp
                    $datetime_lead->modify( "+{$video_length_in_minutes} minutes" );
                    $lead_live_end_ts = $datetime_lead->getTimestamp();
                    // Replay end timestamp
                    $datetime_lead->modify( "+{$replay_length_in_days} days" );
                    $datetime_lead->modify( "-{$video_length_in_minutes} minutes" );
                    $lead_replay_end_ts = $datetime_lead->getTimestamp();
                    $lead_live_started = $datetime_now->getTimestamp() > $lead_live_start_ts;
                    $lead_live_ended = $datetime_now->getTimestamp() > $lead_live_end_ts;
                    $lead_replay_ended = $datetime_now->getTimestamp() > $lead_replay_end_ts;
                    if ( $lead_replay_ended || 'watched' === $lead->lead_status ) {
                        $lead_status = 'closed';
                    } elseif ( $lead_live_ended ) {
                        $lead_status = 'replay';
                    } elseif ( $lead_live_started ) {
                        $lead_status = 'live';
                    }
                }
                //end if
            }
            //end if
        } else {
            $lead_status = ( empty( $webinar_data->webinar_switch ) ? 'countdown' : trim( $webinar_data->webinar_switch ) );
        }
        //end if
        return $lead_status;
    }

}
//end if
if ( !function_exists( 'webinarignition_showGDPRHeading' ) ) {
    function webinarignition_showGDPRHeading(  $webinar, $inline_style = ''  ) {
        global $wi_showingGDPRHeading;
        if ( !$wi_showingGDPRHeading ) {
            ?>
			<div class="gdprSectionWrapper">
				<div class="gdprHeading" <?php 
            echo ( !empty( $inline_style ) ? 'style="' . esc_attr( $inline_style ) . '"' : '' );
            ?>>
					<?php 
            echo ( !empty( $webinar->gdpr_heading ) ? esc_attr( $webinar->gdpr_heading ) : esc_html__( 'Please confirm that you', 'webinar-ignition' ) );
            ?>
				</div>
			<?php 
            $wi_showingGDPRHeading = true;
        }
    }

}
if ( !function_exists( 'webinarignition_closeGDPRSection' ) ) {
    function webinarignition_closeGDPRSection() {
        global $wi_showingGDPRHeading;
        if ( $wi_showingGDPRHeading ) {
            echo '</div>';
        }
    }

}