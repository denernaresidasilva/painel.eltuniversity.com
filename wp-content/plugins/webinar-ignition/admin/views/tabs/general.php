<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Renders the contents of the settings submenu page
 *
 * @since    2.2.7     *
 */

?>
<div class="wrap">
		<h1><?php echo esc_attr__( 'WebinarIgnition Settings', 'webinar-ignition' ); ?></h1>
		<?php require_once WEBINARIGNITION_PATH . 'admin/views/setting_tabs.php'; ?>

		<div id="webinarignition-settings-tab" class="container wrap"
		style="float: left;border: 1px solid #ccd0d4;box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff">
		<div class="row">
		<div class="col-xs-12">
		<form id="general_settings" action="" class="form-horizontal" method="post">
		<h4 style="margin-top:45px; margin-bottom:25px; font-weight:bold;"><?php esc_attr_e( 'General Settings', 'webinar-ignition' ); ?></h4>

		<p><?php esc_attr_e( 'Like the plugin? Become our ambassador and earn cash! Refer new customers to WebinarIgnition by showing the branding on your footer and earn 40% commission on each successful sale you refer! You can sign up for an affiliate link', 'webinar-ignition' ); ?>
		<a href="<?php echo esc_attr( admin_url( 'admin.php' ) ); ?>?page=webinarignition-dashboard-affiliation"><b><?php esc_attr_e( 'here', 'webinar-ignition' ); ?></b></a>.
		</p>

		<br>

		<?php if ( ! empty( $statusCheck->account_url ) ) : ?>

		<div class="form-group">
		<label class="col-sm-3 control-label"><?php esc_attr_e( 'Footer text', 'webinar-ignition' ); ?></label>
		<div class="col-sm-9">
	
		<?php
		$translated_string = sprintf(
			// translators: %1$s is the privacy policy, %2$s is the imprint, %3$s is the year, %4$s is the site title.
			esc_html__( '%1$s | %2$s | © Copyright %3$s %4$s', 'webinar-ignition' ),
			esc_html__( 'Privacy Policy', 'webinar-ignition' ),
			esc_html__( 'Imprint', 'webinar-ignition' ),
			gmdate( 'Y' ),
			get_bloginfo( 'name' )
		);
		?>
		<textarea name="webinarignition_footer_text" id="webinarignition_footer_text"
			style="width:100%; height: 75px;" class=""
			placeholder="<?php echo esc_attr( $translated_string ); ?>"><?php 
				echo ! empty( $webinarignition_footer_text ) ? esc_html( $webinarignition_footer_text ) : sprintf( '%s | %s | © Copyright %s %s', '{privacy_policy}', '{imprint}', '{year}', '{site_title}' );
				// echo ! empty( $webinarignition_footer_text ) ? esc_html( $webinarignition_footer_text ): ''; 
			?></textarea>
		<span
		class="help-block"><?php esc_attr_e( 'The text to appear in the footer of all WebinarIgnition pages and emails. Available placeholders: ', 'webinar-ignition' ); ?>{site_title}, {year}, {imprint}, {privacy_policy}, {site_description}</span>
		<span
		class="help-block"><?php esc_attr_e( 'To display the footer please copy this shortcode', 'webinar-ignition' ); ?><code>[webinarignition_footer]</code></span>
		</div>
		</div>

		<?php else : ?>

		<div class="form-group">
		<label class="col-sm-3 control-label"><?php esc_attr_e( 'Footer text', 'webinar-ignition' ); ?></label>
		<div class="col-sm-9">
		<textarea name="webinarignition_footer_text" id="webinarignition_footer_text"
		style="width:100%; height: 75px;" class=""
		placeholder="{site_title} | © Copyright {year} All rights reserved. {imprint} - {privacy_policy} {site_description}"><?php echo ! empty( $webinarignition_footer_text ) ? esc_html( $webinarignition_footer_text ) : '{site_title} | © Copyright {year} All rights reserved. {imprint} - {privacy_policy} {site_description}'; ?></textarea>
		<span
		class="help-block"><?php esc_attr_e( 'The text to appear in the footer of all WebinarIgnition pages and emails. Available placeholders:', 'webinar-ignition' ); ?> {site_title}, {year}, {imprint}, {privacy_policy}, {site_description}</span>
		<span
		class="help-block"><?php esc_attr_e( 'To display the footer please copy this shortcode', 'webinar-ignition' ); ?><code>[webinarignition_footer]</code></span>
		</div>
		</div>

		<?php endif; ?>

		<div class="form-group">
			<label
			class="col-sm-3 control-label"><?php esc_attr_e( 'Footer Text color', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<input type="text" class="form-control color_picker"
			name="webinarignition_footer_text_color"
			value="<?php echo ! empty( $webinarignition_footer_text_color ) ? esc_html( $webinarignition_footer_text_color ) : '#3f3f3f'; ?>">
			</div>
		</div>


		<?php
		$wi_is_block_theme = wp_is_block_theme();
 			if($wi_is_block_theme){
				?>

					<div class="form-group">
						<label for="webinarignition_use_grid_custom_color" class="col-sm-3 control-label">
							<?php esc_html_e( 'Use custom colors for grid and registration layout', 'webinar-ignition' ); ?>
						</label>
						<div class="col-sm-9">
							<input type="checkbox" 
								class="form-control" 
								id="webinarignition_use_grid_custom_color"
								name="webinarignition_use_grid_custom_color"
								value="1" 
								<?php checked( 1, absint( $webinarignition_use_grid_custom_color ) ); ?>>
							<span class="help-block">
								<?php 
								esc_html_e( 'If you check this box, the default FSE colors will not be applied to the grid layout on the frontend. Instead, you can select custom colors to change the theme of the grid layout..', 'webinar-ignition' ); 
								?>
							</span>
						</div>
					</div>
					<?php
			}
		?>

<div id="wiWrapBrandColors" class="wi_wrap_brand_colors <?php if($wi_is_block_theme && $webinarignition_use_grid_custom_color == 0){ echo "wi_hidden";}  ?>">
		<div class="form-group">
			<label
			class="col-sm-3 control-label"><?php esc_attr_e( 'Base Color', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<input type="text" class="form-control color_picker"
			name="webinarignition_brand_color"
			<?php if(wp_is_block_theme() && absint( $webinarignition_use_grid_custom_color ) == 0 ){ ?>
				value="<?php echo esc_html(wi_get_fse_color_by_slug('base')) ?>"
			<?php }else {  ?>
			value="<?php echo ! empty( $webinarignition_brand_color ) ? esc_html( $webinarignition_brand_color ) : '#3f3f3f'; ?>"
			<?php  } ?>
			>
			</div>
		</div>
		<div class="form-group">
			<label
			class="col-sm-3 control-label"><?php esc_attr_e( 'Contrast Color', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<input type="text" class="form-control color_picker"
			name="webinarignition_brand_contrast_color"
			<?php if(wp_is_block_theme() && absint( $webinarignition_use_grid_custom_color ) == 0 ){ ?>
				value="<?php echo esc_html(wi_get_fse_color_by_slug('contrast')) ?>"
			<?php }else {  ?>
			value="<?php echo ! empty( $webinarignition_brand_contrast_color ) ? esc_html( $webinarignition_brand_contrast_color ) : '#ffffff'; ?>"
			<?php  } ?>
			>
			</div>
		</div>
		</div>
		



				<?php 
			/*
			<?php if ( ! empty( $latest_webinar_permalink ) ) : ?>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<p>
							<a target="_blank"
							href="<?php echo esc_attr( $latest_webinar_permalink ); ?>">
								<?php esc_attr_e( 'Click here to preview your webinar page template', 'webinar-ignition' ); ?>
							</a>.
						</p>
					</div>
				</div>
			<?php endif; ?>
			*/
			?>

		<div id="branding_settings">
			<div class="form-group">
				<label
					class="col-sm-3 control-label"><?php esc_attr_e( 'Show WebinarIgnition branding?', 'webinar-ignition' ); ?></label>
				<div class="col-sm-9">
					<input
						type="checkbox"
						class="form-control"
						id="webinarignition_show_footer_branding"
						name="webinarignition_show_footer_branding"
						<?php echo ! empty( $webinarignition_show_footer_branding ) ? 'checked' : ''; ?>
					>
					<span class="help-block"><?php esc_attr_e( 'You can optionally show this text on your WebinarIgnition pages and emails. Useful for affiliate marketing.', 'webinar-ignition' ); ?></span>
				</div>
			</div>
			<div id="show_hide_branding_settings"
				style="display:<?php echo empty( $webinarignition_show_footer_branding ) ? 'none' : 'block'; ?>">
			<div class="form-group">
			<label
				class="col-sm-3 control-label"><?php esc_attr_e( 'Branding Copy', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<?php echo ! empty( $webinarignition_branding_copy ) ? esc_html( $webinarignition_branding_copy ) : esc_attr__( 'Webinar Powered By WebinarIgnition', 'webinar-ignition' ); ?>
				<input type="hidden" name="webinarignition_branding_copy" value="<?php echo ! empty( $webinarignition_branding_copy ) ? esc_html( $webinarignition_branding_copy ) : esc_attr__( 'Webinar Powered By WebinarIgnition', 'webinar-ignition' ); ?>">
				<br>
				<span
					class="help-block"><?php esc_attr_e( 'This is what the link says for WebinarIgnition to your affiliate link... "Webinar Powered By WebinarIgnition" text is necessary to available free registrations upto 100.', 'webinar-ignition' ); ?></span>
			</div>
		</div>

		<div class="form-group">
			<label
				class="col-sm-3 control-label"><?php esc_attr_e( 'Branding Background color', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<input type="text" class="form-control color_picker"
					name="webinarignition_branding_background_color"
					value="<?php echo ! empty( $webinarignition_branding_background_color ) ? esc_html( $webinarignition_branding_background_color ) : '#000'; ?>">
				<span
					class="help-block"><?php esc_attr_e( 'Background color for branding. Make sure your branding text is visible to avail free registrations.', 'webinar-ignition' ); ?></span>
			</div>
		</div>

		<div class="form-group">
			<label
				class="col-sm-3 control-label"><?php esc_attr_e( 'Show WebinarIgnition logo in footer?', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<div class="row">
					<div class="col-sm-9">
						<input type="checkbox" class="form-control"
							id="show_webinarignition_footer_logo"
							name="show_webinarignition_footer_logo"
							value="1" <?php echo ! empty( $show_webinarignition_footer_logo ) ? 'checked' : ''; ?>>
						<span
							class="help-block"><?php esc_attr_e( "You can optionally show WebinarIgnition's logo in your email footer as part of the branding", 'webinar-ignition' ); ?></span>
					</div>
				</div>
			</div>
		</div>

		<?php if ( ! empty( $statusCheck->account_url ) ) : ?>

		<div class="form-group">
			<label
				class="col-sm-3 control-label"><?php esc_attr_e( 'Your Affiliate Link', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="webinarignition_affiliate_link"
					id="webinarignition_affiliate_link"
					value="<?php echo ! empty( $webinarignition_affiliate_link ) ? esc_html( $webinarignition_affiliate_link ) : 'https://webinarignition.com/'; ?>">
				<span
					class="help-block"><?php esc_attr_e( 'Your freemius affiliate link if you want to earn money from this branding. This can be used in your webinar reminder emails and in email answers to attendee questions.', 'webinar-ignition' ); ?></span>
			</div>
		</div>
	<?php else : ?>
	<div class="form-group">
		<label
			class="col-sm-3 control-label"><?php esc_attr_e( 'Your Affiliate Link', 'webinar-ignition' ); ?></label>
		<div class="col-sm-9">
			<a href="/wp-admin/admin.php?page=webinarignition-dashboard-affiliation">
				<button type="button"
					class="btn btn-primary"><?php esc_attr_e( 'Yes, show more!', 'webinar-ignition' ); ?></button>
			</a>
			<span
				class="help-block"><?php esc_attr_e( 'Please activate freemius to join the affiliate program and to avoid page with "Sorry, you are not allowed to access this page."', 'webinar-ignition' ); ?></span>
		</div>
	</div>
<?php endif; ?>
</div>
<div class="form-group">
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Auto Clean Log Database?', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="checkbox" class="form-control" id="webinarignition_auto_clean_log_db"
			name="webinarignition_auto_clean_log_db"
			value="1" <?php echo ! empty( $webinarignition_auto_clean_log_db ) ? 'checked' : ''; ?>>
		<span class="help-block">
			<?php esc_attr_e( 'WebinarIgnition can automatically delete notification logs older than 14 days', 'webinar-ignition' ); ?>
		</span>
	</div>
</div>
<div class="form-group">
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Allow auto-login on registration?', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="checkbox" class="form-control" id="webinarignition_registration_auto_login"
			name="webinarignition_registration_auto_login"
			value="1" <?php checked( 1 === absint($webinarignition_registration_auto_login), true ); ?>>
		<span class="help-block">
			<?php esc_attr_e( 'Automatically log-in user on webinar registration.', 'webinar-ignition' ); ?><br>
			<?php esc_attr_e( 'If user email does not found, it will create a new user before auto-login.', 'webinar-ignition' ); ?>
		</span>
	</div>
</div>
<div class="form-group">
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Enable HoneyPot bot catcher field?', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="checkbox" class="form-control" id="webinarignition_enable_honeypot_field"
			name="webinarignition_enable_honeypot_field"
			value="1" <?php checked( 1 === absint($webinarignition_enable_honeypot_field), true ); ?>>
		<span class="help-block">
			<?php esc_attr_e( 'It checks for bot submissions and clics on registration and stop registration of suspected bot', 'webinar-ignition' ); ?><br>
		</span>
	</div>
</div>
<div class="form-group">
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Auto Login Registration shortcode', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="text" class="form-control" id="webinarignition_registration_shortcode"
			name="webinarignition_registration_shortcode"
			value="<?php echo esc_html($webinarignition_registration_shortcode); ?>" >
		<span class="help-block">
			<?php 
			printf(
				/* translators: %s: Link to the documentation */
				esc_html__( 'Shortcode to show the login and signup option with social media. %s', 'webinar-ignition' ),
				'<a href="https://webinarignition.tawk.help/article/social-webinar-registration" target="_blank">' . esc_html__( 'See details here', 'webinar-ignition' ) . '</a>'
			);?><br>
		</span>
	</div>
</div>
<div class="form-group"
	id="wi-auto-login-password-email" <?php echo 1 !== absint($webinarignition_registration_auto_login) ? 'style="display: none;"' : ''; ?>>
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Send password email to new auto-login users?', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="checkbox" class="form-control"
			id="webinarignition_auto_login_password_email"
			name="webinarignition_auto_login_password_email"
			value="1" <?php checked( 1 === $webinarignition_auto_login_password_email, true ); ?>>
		<span class="help-block">
			<?php esc_attr_e( 'Enable to send login details/password reset emails, to new users who got logged-in for the first time.', 'webinar-ignition' ); ?>
		</span>
	</div>
</div>
<div class="form-group">
	<label for="webinarignition_email_verification" class="col-sm-3 control-label">
		<?php esc_html_e( 'Email verification', 'webinar-ignition' ); ?>
	</label>
	<div class="col-sm-9">
		<input type="checkbox" 
		       class="form-control" 
		       id="webinarignition_email_verification"
		       name="webinarignition_email_verification"
		       value="1" 
		       <?php checked( 1, absint( $webinarignition_email_verification ) ); ?>>
		<span class="help-block">
			<?php 
			esc_html_e( 'Enable to send verification code. Can be overwritten by specific Webinar settings.', 'webinar-ignition' ); 
			?>
			<br>
			<strong>
				<?php 
				esc_html_e( 'Extra Settings > Protected access > Enable / disable email verification.', 'webinar-ignition' ); 
				?>
			</strong>
		</span>
	</div>
</div>
<div class="form-group">
	<label for="webinarignition_email_verification_template" class="col-sm-3 control-label">
		<?php esc_attr_e( 'Verification email template', 'webinar-ignition' ); ?>
	</label>
	<div class="col-sm-9">
	<?php
		wp_editor(
			stripcslashes( $webinarignition_email_verification_template ),
			'webinarignition_email_verification_template',
			array(
				'wpautop'       => true,
				'teeny'         => false,
				'textarea_name' => 'webinarignition_email_verification_template',
				'tinymce'       => array(
					'height' => '180', // the height of the editor
				),
			)
		);
		?>
	<span class="help-block">
		<?php
		printf(
			/* translators: %s: Placeholder for verification code */
			esc_html__( 'Make sure that you included %s placeholder in template, otherwise template will be switched to the default one.', 'webinar-ignition' ),
			'{VERIFICATION_CODE}'
		);
		?>
	</span>
</div>
</div>
<div class="form-group">
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Hide top admin bar from webinar pages?', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="checkbox" class="form-control" id="webinarignition_hide_top_admin_bar"
			name="webinarignition_hide_top_admin_bar"
			value="1" <?php checked( 1 === absint($webinarignition_hide_top_admin_bar), true ); ?>>
		<span class="help-block">
			<?php esc_attr_e( 'Enable to hide admin bar for logged-in users from webinar pages. Users with administrator role can still see it.', 'webinar-ignition' ); ?>
		</span>
	</div>
</div>
<div class="form-group">
	<label
		class="col-sm-3 control-label"><?php esc_attr_e( 'Enable third party server for CTAs of live webinar?', 'webinar-ignition' ); ?></label>
	<div class="col-sm-9">
		<input type="checkbox" class="form-control" id="webinarignition_enable_third_party_server"
			name="webinarignition_enable_third_party_server"
			value="1" <?php checked( 1 === absint(get_option('webinarignition_enable_third_party_server', 1)), true ); ?>>
		<span class="help-block">
			<?php esc_attr_e( 'Enable third party server for CTAs of live webinar or keep the ajax on own server?', 'webinar-ignition' ); ?>
		</span>
	</div>
</div>
</div>
	<input type="hidden" name="submit-webinarignition-general-settings" value="1">
	<p>
		<?php submit_button( esc_attr__( 'Save', 'webinar-ignition' ), 'primary', 'submit-webinarignition-general-settings', false ); ?>
	</p>
	<?php wp_nonce_field( 'webinarignition-general-settings-save', 'webinarignition-general-settings-save-nonce' ); ?>
</form>
</div>
</div>
</div>
</div>