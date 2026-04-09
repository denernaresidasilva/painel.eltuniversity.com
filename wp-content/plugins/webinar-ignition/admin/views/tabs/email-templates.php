<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Renders the contents of the email templates settings submenu page
 */
?>
<div class="wrap">
	<div class="row">
		<div class="col-xs-12 col-md-8">
			<h2><?php esc_attr_e( 'WebinarIgnition Email Templates Settings', 'webinar-ignition' ); ?></h2>
		</div>
	</div>

	<?php require_once WEBINARIGNITION_PATH . 'admin/views/setting_tabs.php'; ?>

	<div id="webinarignition-settings-tab" class="container wrap" style="float: left;border: 1px solid #ccd0d4;box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff">
		<div class="row">
			<div class="col-xs-12">
				<form id="emailSettingsForm" action="" class="form-horizontal" method="post">

					<h4 style="margin-top:45px; margin-bottom:25px; font-weight:bold;"><?php esc_attr_e( 'Email Template Settings', 'webinar-ignition' ); ?></h4>

					<p><?php esc_attr_e( 'This section lets you customize the WebinarIgnition emails. ', 'webinar-ignition' ); ?> <a target="_blank" href="<?php echo esc_attr( wp_nonce_url( admin_url( '?preview-webinarignition-template=true' ), 'preview-mail' ) ); ?>"><?php esc_attr_e( 'Click here to preview your email template', 'webinar-ignition' ); ?></a>. </p>
					<p>
					<strong>Note: </strong><?php esc_attr_e( 'If you like to change the from email address and name for webinar notifications, you can change below. To change site wide, get better delivery, get bounces then install any', 'webinar-ignition' ); ?>
					<a href="/wp-admin/plugin-install.php?tab=plugin-information&plugin=fluent-smtp&TB_iframe=true&width=772&height=665" target="_blank"><b><?php esc_attr_e( 'SMTP plugin', 'webinar-ignition' ); ?></b></a>.
					<?php esc_attr_e( 'That will overwrite the settings below. Details', 'webinar-ignition' ); ?>
					<a href="https://webinarignition.tawk.help/article/smtp-setup" target="_blank"><b><?php esc_attr_e( 'here', 'webinar-ignition' ); ?></b></a>.
					</p>

					<p><strong><?php esc_attr_e( 'NB: Placeholders will not be replaced in the preview.', 'webinar-ignition' ); ?></strong></p>
					<div id="headerImgSettingsCont">

						<div class="form-group">
							<label class="col-sm-3 control-label"><?php esc_attr_e( 'Show Header Image In Emails?', 'webinar-ignition' ); ?></label>
							<div class="col-sm-9">
								<button type="button" data-enable="1" class="btn webinarignition_yes_no_switch <?php echo $webinarignition_show_email_header_img ? 'btn-primary' : ''; ?>"><?php esc_attr_e( 'Yes', 'webinar-ignition' ); ?></button>
								<button type="button" data-enable="0" class="btn webinarignition_yes_no_switch <?php echo $webinarignition_show_email_header_img ? '' : 'btn-primary'; ?>"><?php esc_attr_e( 'No', 'webinar-ignition' ); ?></button>
								<input type="hidden" class="form-control" id="webinarignition_yes_no_switch" name="webinarignition_show_email_header_img" value="<?php echo $webinarignition_show_email_header_img ? '1' : '0'; ?>">
							</div>
						</div>

						<div id="show_hide_header_settings" style="display:<?php echo empty( $webinarignition_show_email_header_img ) ? 'none' : 'block'; ?>">

							<?php 
							// Check the default site logo
							$theme_attach = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
							$default_logo = $theme_attach ? esc_url( $theme_attach[0] ) : '';

							// If the site logo is not found, set the webinar email logo instead
							if(WEBINARIGNITION_URL . 'images/wi-email-design-logo.png' != $webinarignition_email_logo_url && $webinarignition_email_logo_url != ''  ){
								$final_logo = $webinarignition_email_logo_url;
							}elseif(WEBINARIGNITION_URL . 'images/wi-email-design-logo.png' == $webinarignition_email_logo_url && $default_logo == ''){
								$final_logo = ! empty( $default_logo ) ? $default_logo : $webinarignition_email_logo_url;
							}elseif('' == $webinarignition_email_logo_url && $default_logo == ''){
								$final_logo = WEBINARIGNITION_URL . 'images/wi-email-design-logo.png';
							}else{
								$final_logo = $default_logo ;								
							}
							?>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php esc_attr_e( 'Header Image', 'webinar-ignition' ); ?></label>
								<div class="col-sm-9">

									<div id="input_image_holder" class="input_image_holder" style="width:70%; margin: 0 auto; float:<?php echo $header_img_algnmnt ? esc_attr( $header_img_algnmnt ) : esc_attr( 'none' ); ?>">
										<img src="<?php echo esc_url( $final_logo ); ?>">
									</div>

									<input type="text" class="imgUrlField form-control" name="webinarignition_email_logo_url" value="<?php echo esc_html( $webinarignition_email_logo_url ); ?>" placeholder="<?php esc_attr_e( 'Header Image URL', 'webinar-ignition' ); ?>">
									<span class="help-block"><?php esc_attr_e( 'This is your header image url', 'webinar-ignition' ); ?></span>
									<button type="button" class="btn wi_upload_image_btn btn-primary"><?php esc_attr_e( 'Choose Image', 'webinar-ignition' ); ?></button> 
									<button type="button" class="btn wi_delete_image_btn btn-danger" style="display:<?php echo empty( $webinarignition_email_logo_url ) ? 'none' : 'inline'; ?>"><?php esc_attr_e( 'Delete Image', 'webinar-ignition' ); ?></button>

								</div>
							</div>


							<div class="form-group">
								<label class="col-sm-3 control-label"><?php esc_attr_e( 'Header Image Alignment', 'webinar-ignition' ); ?></label>
								<div class="col-sm-9">

									<div class="row">
										<div class="col-sm-3">
											<input type="radio" class="header_img_algnmnt" <?php echo ( ! empty( $header_img_algnmnt ) && 'left' === $header_img_algnmnt ) ? 'checked' : ''; ?> name="header_img_algnmnt" value="left">
											<label><?php esc_attr_e( 'Left', 'webinar-ignition' ); ?></label>
										</div>
										<div class="col-sm-3">
											<input type="radio" class="header_img_algnmnt" <?php echo ( ! empty( $header_img_algnmnt ) && 'none' === $header_img_algnmnt ) ? 'checked' : ''; ?> name="header_img_algnmnt" value="none">
											<label><?php esc_attr_e( 'Center', 'webinar-ignition' ); ?></label>
										</div>
										<div class="col-sm-3">
											<input type="radio" class="header_img_algnmnt" <?php echo ( ! empty( $header_img_algnmnt ) && 'right' === $header_img_algnmnt ) ? 'checked' : ''; ?> name="header_img_algnmnt" value="right">
											<label><?php esc_attr_e( 'Right', 'webinar-ignition' ); ?></label>
										</div>
									</div>

								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label"><?php esc_attr_e( 'Enable max-width on header image?', 'webinar-ignition' ); ?></label>
								<div class="col-sm-9">
									<input type="checkbox" class="form-control" name="webinarignition_enable_header_img_max_width" <?php echo ( 'yes' === $webinarignition_enable_header_img_max_width ) ? 'checked' : ''; ?> value="yes">
								</div>
							</div>


							<div id="enable_header_img_max_width_div" class="form-group" style="display:<?php echo empty( $webinarignition_enable_header_img_max_width ) ? 'none' : 'block'; ?>">
								<label class="col-sm-3 control-label"><?php esc_attr_e( 'Max-width', 'webinar-ignition' ); ?></label>
								<div class="col-sm-9">
									<input type="number" class="form-control" name="webinarignition_email_logo_max_width" value="<?php echo esc_attr( $webinarignition_email_logo_max_width ); ?>">
								</div>
							</div>


						</div>

					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Background color', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control color_picker" name="webinarignition_email_background_color" value="<?php echo ! empty( $webinarignition_email_background_color ) ? esc_html( $webinarignition_email_background_color ) : '#ffffff'; ?>">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Headings color', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control color_picker" name="webinarignition_headings_color" value="<?php echo ! empty( $webinarignition_headings_color ) ? esc_html( $webinarignition_headings_color ) : '#ffffff'; ?>">
						</div>
					</div>



					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Heading background color', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control color_picker" name="webinarignition_heading_background_color" value="<?php echo ! empty( $webinarignition_heading_background_color ) ? esc_html( $webinarignition_heading_background_color ) : '#000000'; ?>">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Heading text color', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control color_picker" name="webinarignition_heading_text_color" value="<?php echo esc_html( $webinarignition_heading_text_color ); ?>">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Body Background color', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control color_picker" name="webinarignition_email_body_background_color" value="<?php echo ! empty( $webinarignition_email_body_background_color ) ? esc_html( $webinarignition_email_body_background_color ) : '#ededed'; ?>">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Text color', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control color_picker" name="webinarignition_email_text_color" value="<?php echo ! empty( $webinarignition_email_text_color ) ? esc_html( $webinarignition_email_text_color ) : '#3f3f3f'; ?>">
						</div>
					</div>


					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Body text font size', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="number" class="form-control" name="webinarignition_email_font_size" value="<?php echo ! empty( $webinarignition_email_font_size ) ? esc_html( $webinarignition_email_font_size ) : '14'; ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Body text line-height', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="webinarignition_body_text_line_height" value="<?php echo esc_html( $webinarignition_body_text_line_height ); ?>">
							<span class="help-block"><?php esc_attr_e( "Example values: 'normal', '1.6', '80%', '200%'", 'webinar-ignition' ); ?></span>
						</div>
					</div>

					<!-- Adding Fields For From Name & From Email below -->
					<!-- @todo confirm about language translation of labels -->
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'From Name', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="webinarignition_email_templates_from_name" value="<?php echo esc_html( $webinarignition_email_templates_from_name ); ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'From Email', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="text" class="form-control" name="webinarignition_email_templates_from_email" value="<?php echo esc_html( $webinarignition_email_templates_from_email ); ?>">
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label"><?php esc_attr_e( 'Email Signature', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<?php wp_editor( $webinarignition_email_signature, 'webinarignition_email_signature', $wp_editor_settings ); ?>
						</div>
					</div>
					<div class="form-group">
						<label
							class="col-sm-3 control-label"><?php esc_attr_e( 'Add unsubscribe links in mail?', 'webinar-ignition' ); ?></label>
						<div class="col-sm-9">
							<input type="checkbox" class="form-control" id="webinarignition_unsubscribe_links"
								name="webinarignition_unsubscribe_links"
								value="1" <?php checked( 1 === absint($webinarignition_unsubscribe_links), true ); ?>>
						</div>
					</div>

					<input type="hidden" name="submit-webinarignition-email-templ-settings" value="1">
					<p>
						<?php submit_button( __( 'Save', 'webinar-ignition' ), 'primary', 'submit-webinarignition-template-settings', false ); ?>
					</p>

					<?php wp_nonce_field( 'webinarignition-template-settings-save', 'webinarignition-template-settings-save-nonce' ); ?>
				</form>
			</div>
		</div>
	</div>
</div>