<?php
/**
 * Admin View: Email Template Preview
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div style="margin-bottom: 40px;">
	<table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;">
		<tr>
			<td class="bg_white">
				<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td class="bg_white">
							<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td>
										<div class="heading-section">
											<?php /* Translators: %s is replaced with the user's first name */ ?>
											<p><?php echo sprintf( esc_html__( 'Hi %s.', 'webinar-ignition' ), '{FULLNAME}' ); ?></p>

											<p><?php esc_html_e( '%%INTRO%%', 'webinar-ignition' ); ?></p>
											
											<?php /* Translators: %s is replaced with the date of the webinar */ ?>
											<p><?php echo esc_html( sprintf( __( 'Date: Join us live on %s', 'webinar-ignition' ), '{DATE}' ) ); ?></p>

											<?php /* Translators: %s is replaced with the webinar title*/ ?>
											<p><?php echo esc_html( sprintf( __( 'Webinar Topic: %s', 'webinar-ignition' ), '{TITLE}' ) ); ?></p>

											<?php /* Translators: %s is replaced with the host's name*/ ?>
											<p><?php echo esc_html( sprintf( __( 'Hosts: %s', 'webinar-ignition' ), '{HOST}' ) ); ?></p>

											<p><strong><?php esc_html_e( 'How To Join The Webinar', 'webinar-ignition' ); ?></strong></p>

											<p><?php esc_html_e( 'Click the following link to join.', 'webinar-ignition' ); ?></p>

											<p style="text-align:center;"><a target="_blank" href="/"><?php esc_html_e( 'Join the webinar', 'webinar-ignition' ); ?></a></p>

											<p><?php esc_html_e( 'You will be connected to video via your browser using your computer, tablet, or mobile phone\'s microphone and speakers. A headset is recommended.', 'webinar-ignition' ); ?></p>

											<p><strong><?php esc_html_e( 'Webinar Requirements', 'webinar-ignition' ); ?></strong></p>

											<p><?php esc_html_e( 'A recent browser version of Mozilla Firefox, Google Chrome, Apple Safari, Microsoft Edge or Opera.', 'webinar-ignition' ); ?></p>

											<p><?php esc_html_e( 'You can join the webinar on mobile, tablet or desktop.', 'webinar-ignition' ); ?></p>

										</div>
									</td>
								</tr>

							</table>

						</td>
					</tr>

				</table>

			</td>
		</tr>

	</table>
</div>    

