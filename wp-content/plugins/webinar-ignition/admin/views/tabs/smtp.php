<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Renders the contents of the settings submenu page
 *
* @since    2.2.7    *
*/

?>



<div class="wrap">
	
	<div class="row">
		<div class="col-xs-12 col-md-8">
		<h2><?php esc_attr_e( 'WebinarIgnition SMTP Settings', 'webinar-ignition' ); ?></h2>
		</div>
	</div>

	<?php require_once WEBINARIGNITION_PATH . 'admin/views/setting_tabs.php'; ?>

	<div id="webinarignition-settings-tab" class="container wrap" style="float: left;border: 1px solid #ccd0d4;box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff">

	<?php if ( isset( $_POST['submit-webinarignition-smtp-settings'] ) ) { ?>

		<?php if ( isset( $smtp_test_results_array['status'] ) && ( 0 === $smtp_test_results_array['status'] ) ) : ?>

			<div class="row">
			<div class="col-xs-12 col-md-8">
				<div id="message" class="notice notice-warning is-dismissible">
					<p><?php echo esc_attr( $smtp_test_results_array['message'] ); ?></p>
					<p><?php esc_attr_e( "Webinarignition cannot use SMTP to send your notifications. PHP's mail() function will be used instead.", 'webinar-ignition' ); ?></p>
				</div>
			</div>
			</div>
		
		<?php endif; ?>

		<?php if ( isset( $smtp_test_results_array['status'] ) && ( 1 === $smtp_test_results_array['status'] ) ) : ?>
		<div class="row">
			<div class="col-xs-12 col-md-8">
			<div id="message" class="notice notice-success is-dismissible">
				<p><?php echo esc_attr( $smtp_test_results_array['message'] ); ?></p>
			</div>
			</div>
		</div>

		<?php endif; ?>

	<?php }//end if
	?>

	<div class="row">
		<div class="col-xs-12">
		<form action="" class="form-horizontal" method="post">

			<h4 style="margin-top:45px; margin-bottom:25px; font-weight:bold;"><?php esc_attr_e( 'SMTP Settings', 'webinar-ignition' ); ?></h4>
			<p style="margin-bottom:25px;"><?php esc_attr_e( 'Need Help? See the <a href="https://webinarignition.tawk.help/article/smtp-setup" target="_blank">SMTP Integration Tutorial</a>', 'webinar-ignition' ); ?></p>
						
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_connect"><?php esc_attr_e( 'Use WebinarIgnition SMTP Settings?', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<button type="button" data-enable="1" class="enable_disable_wi_smtp btn webinarignition_yes_no_switch <?php echo esc_attr( $webinarignition_smtp_connect ) ? 'btn-primary' : ''; ?>"><?php esc_html_e( 'Yes', 'webinar-ignition' ); ?></button>
				<button type="button" data-enable="0"  class="enable_disable_wi_smtp btn webinarignition_yes_no_switch <?php echo esc_attr( $webinarignition_smtp_connect ) ? '' : 'btn-primary'; ?>"><?php esc_html_e( 'No', 'webinar-ignition' ); ?></button>
				<input type="hidden" class="form-control" id="webinarignition_smtp_connect" name="webinarignition_smtp_connect" value="<?php echo esc_attr( $webinarignition_smtp_connect ); ?>">
				<span class="help-block"><?php esc_html_e( 'You may disable Webinarignition SMTP settings if you prefer to use WordPress\'s default settings or settings from an SMTP plugin (like  <a href="https://wordpress.org/plugins/fluent-smtp/ " target="_blank">FluentSMTP</a>).', 'webinar-ignition' ); ?></span>
			</div>
			</div> 
			
			<div id="show_hide_smtp_settings" style="display:<?php echo empty( $webinarignition_smtp_connect ) ? 'none' : 'block'; ?>">
			
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_host"><?php esc_attr_e( 'SMTP Host', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="webinarignition_smtp_host" value="<?php echo ! empty( $webinarignition_smtp_host ) ? esc_html( $webinarignition_smtp_host ) : ''; ?>" placeholder="<?php esc_attr_e( 'SMTP Host', 'webinar-ignition' ); ?>"> 
				<span class="help-block"><?php esc_attr_e( 'This is your SMTP host, you can get this from your email provider', 'webinar-ignition' ); ?></span>
			</div>
			</div>

			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_protocol"><?php esc_attr_e( 'Transfer Protocol', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<label class="radio-inline">
				<input type="radio" class="webinarignition_smtp_protocol" name="webinarignition_smtp_protocol" <?php echo ! empty( $webinarignition_smtp_protocol ) ? checked( $webinarignition_smtp_protocol, 'ssl', false ) : 'checked'; ?> value="ssl"> SSL
			</label>
			<label class="radio-inline">
				<input type="radio" class="webinarignition_smtp_protocol" name="webinarignition_smtp_protocol" <?php echo ! empty( $webinarignition_smtp_protocol ) ? checked( $webinarignition_smtp_protocol, 'tls', false ) : 'checked'; ?> value="tls"> TLS
			</label>
			<label class="radio-inline">
				<input type="radio" class="webinarignition_smtp_protocol" name="webinarignition_smtp_protocol" <?php echo ! empty( $webinarignition_smtp_protocol ) ? checked( $webinarignition_smtp_protocol, 'none', false ) : 'checked'; ?> value="none"> <?php esc_attr_e( 'None', 'webinar-ignition' ); ?>
			</label>
			<span class="help-block"><?php esc_attr_e( 'Choose transfer protocol. If not sure, choose TLS', 'webinar-ignition' ); ?></span>                        
			</div>
			</div>
			

			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_port"><?php esc_attr_e( 'SMTP Port', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<input type="number" class="form-control" name="webinarignition_smtp_port" id="webinarignition_smtp_port" value="<?php echo ! empty( $webinarignition_smtp_port ) ? esc_html( $webinarignition_smtp_port ) : '587'; ?>" placeholder="<?php esc_attr_e( 'SMTP Port', 'webinar-ignition' ); ?>">
			<span class="help-block"><?php esc_attr_e( 'This is your SMTP port, you can get this from your email provider', 'webinar-ignition' ); ?></span>                        
			</div>
			</div>                  

			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_user"><?php esc_attr_e( 'SMTP Username', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" name="webinarignition_smtp_user" value="<?php echo ! empty( $webinarignition_smtp_user ) ? esc_html( $webinarignition_smtp_user ) : ''; ?>" placeholder="<?php esc_attr_e( 'SMTP Username', 'webinar-ignition' ); ?>">
				<br>
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_pass"><?php esc_attr_e( 'SMTP Password', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<input type="password" class="form-control" name="webinarignition_smtp_pass" value="<?php echo ! empty( $webinarignition_smtp_pass ) ? esc_html( $webinarignition_smtp_pass ) : ''; ?>" placeholder="<?php esc_attr_e( 'SMTP Password', 'webinar-ignition' ); ?>">
			</div>
			<br><br>
			</div>
			
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_settings_global"><?php esc_attr_e( 'Use WebinarIgnition SMTP Settings Globally?', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<button type="button" data-enable="1" class="btn webinarignition_yes_no_switch <?php echo $webinarignition_smtp_settings_global ? 'btn-primary' : ''; ?>"><?php esc_attr_e( 'Yes', 'webinar-ignition' ); ?></button>
				<button type="button" data-enable="0" class="btn webinarignition_yes_no_switch <?php echo $webinarignition_smtp_settings_global ? '' : 'btn-primary'; ?>"><?php esc_attr_e( 'No', 'webinar-ignition' ); ?></button>
				<input type="hidden" class="form-control" id="webinarignition_smtp_settings_global" name="webinarignition_smtp_settings_global" value="<?php echo esc_attr( $webinarignition_smtp_settings_global ); ?>"> 
				<span class="help-block"><?php esc_attr_e( 'Click "Yes" if you would like these SMTP settings to override those of other plugins (like <a href="https://wordpress.org/plugins/wp-mail-smtp/" target="_blank">WP Mail SMTP</a>). This means these SMTP settings will be used for even non-Webinarignition mail.', 'webinar-ignition' ); ?></span>
			</div>
			</div>                         
			
			</div>    
			
			<h4 style="margin-top:45px; margin-bottom:45px;padding-left: 45px; font-weight:bold;"><?php esc_attr_e( 'Email Sender Settings', 'webinar-ignition' ); ?></h4>  
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_user"><?php esc_attr_e( 'From Name', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<input type="text" class="form-control" name="webinarignition_smtp_name" value="<?php echo esc_html( $webinarignition_smtp_name ); ?>">
			<span class="help-block"><?php esc_attr_e( 'This is the name that the emails will be from', 'webinar-ignition' ); ?></span>                        
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_smtp_email"><?php esc_attr_e( 'From Email', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
			<input type="email" class="form-control" id="fromEmail" name="webinarignition_smtp_email" value="<?php echo esc_html( $webinarignition_smtp_email ); ?>" <?php echo esc_attr( $is_from_email_disabled ); ?>>
			<span class="help-block"><?php esc_attr_e( 'This is the sender ("From") email address. NB: If you choose to use an SMTP server to send your email, your sending address will be defined by the SMTP settings of your server.', 'webinar-ignition' ); ?></span>                       
			</div>
			</div>
			<div class="form-group">
			<label class="col-sm-3 control-label" for="webinarignition_reply_to_email"><?php esc_attr_e( 'Reply-to Email', 'webinar-ignition' ); ?></label>
			<div class="col-sm-9">
				<input type="email" class="form-control" name="webinarignition_reply_to_email" value="<?php echo esc_html( $webinarignition_reply_to_email ); ?>">
			</div>
			</div>                     
			
			
			<input type="hidden" name="submit-webinarignition-smtp-settings" value="1"> 
			<p>
			<?php submit_button( 'Save', 'primary', 'submit-webinarignition-settings', false ); ?>
			</p>

			<?php wp_nonce_field( 'webinarignition-settings-submenu-save', 'webinarignition-settings-submenu-save-nonce' ); ?>
			


		</form>

		</div>

	</div>
	</div>
</div>

