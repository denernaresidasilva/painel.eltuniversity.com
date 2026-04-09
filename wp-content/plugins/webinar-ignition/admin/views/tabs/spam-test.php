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
		<h2><?php esc_attr_e( 'WebinarIgnition Spammyness Test', 'webinar-ignition' ); ?></h2>
		</div>
	</div>

	<?php require_once WEBINARIGNITION_PATH . 'admin/views/setting_tabs.php'; ?>
	
			<div id="webinarignition-settings-tab" class="container wrap" style="float: left;border: 1px solid #ccd0d4;box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff">

			<?php if ( isset($emailSent) && true === $emailSent && $emailSent ) : ?>
			  

				<div class="row">
					<div class="col-xs-12 col-md-8">
					<div id="message" class="notice notice-success is-dismissible">
						<p><?php esc_attr_e( "Your spam test email from WebinarIgnition was successfully sent. Go back to mail-tester.com and click on 'Then check your score' to see the results.", 'webinar-ignition' ); ?></p>
					</div>
					</div>
				</div>              

				<?php elseif ( isset( $emailSent ) && ( false === $emailSent ) ) : ?>

				<div class="row">
					<div class="col-xs-12 col-md-8">
					<div id="message" class="notice notice-warning is-dismissible">
						<p><?php esc_attr_e( 'Your spam test email could not be sent. There seems to be a problem with your server.', 'webinar-ignition' ); ?></p>
					</div>
					</div>
				</div>              

				<?php endif; ?>

			<div class="row">

			</div>

			<div class="row">
				<div class="col-xs-12 col-md-8">
				<form action="" method="post">

					<h4 style="margin-top:45px;margin-bottom:25px;"><?php esc_attr_e( 'Test The Spammyness Of Your Email', 'webinar-ignition' ); ?></h4>
				  
					<p><?php esc_attr_e( 'Get your test email address at', 'webinar-ignition' ); ?> <a href="https://www.mail-tester.com/?lang=<?php echo isset($webinar_locale) ? esc_attr( $webinar_locale ) : '' ; ?>" target="_blank">mail-tester.com</a> <?php esc_attr_e( 'Copy and paste the email address from mail-tester.com into the field below, then click the "Send Email" button. Then go back to the Mail-tester.com website and click the "Then check your score" button to see the results of the spammyness test.', 'webinar-ignition' ); ?></p>

					<div class="form-group">
					<label for="webinarignition_spam_test_email"><?php esc_attr_e( 'Mail-tester email address', 'webinar-ignition' ); ?></label>
					<input type="email" class="form-control" name="webinarignition_spam_test_email" value="" placeholder="<?php esc_attr_e( 'Insert test email address from mail-tester.com', 'webinar-ignition' ); ?>">
					</div>     

					<p>
					<?php submit_button( __( 'Send Email', 'webinar-ignition' ), 'primary', 'submit-webinarignition-settings', false ); ?>
					</p>

					<?php wp_nonce_field( 'webinarignition-spam-test-save', 'webinarignition-spam-test-save-nonce' ); ?>

				</form>

				</div>

			</div>
			</div>    
	
</div>

