<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * @var $webinar_data
 * @var $assets
 * @var $paid_check
 * @var $loginUrl
 * @var $user - Who is user
 */
?>

<?php 
if ( !empty( $webinar_data->webinar_switch ) && 'closed' === $webinar_data->webinar_switch ) {
    echo ( $webinar_data->lp_optin_closed ? esc_html( $webinar_data->lp_optin_closed ) : esc_html__( 'Registration is closed for this webinar.', 'webinar-ignition' ) );
} elseif ( isset( $webinar_data->webinar_status ) && 'draft' === $webinar_data->webinar_status && !current_user_can( 'edit_posts' ) ) {
    ?>
			
			<p class="wi-webinar-unpublished"><span style="font-weight:bold;"><?php 
    esc_html_e( 'This Webinar Is Unpublished. Publish It To Show', 'webinar-ignition' );
    ?></span> <span><a target="_blank" href="https://webinarignition.tawk.help/article/this-webinar-is-unpublished-what-to-do-when-your-registration-form-wont-show"><?php 
    esc_html_e( 'Read More...', 'webinar-ignition' );
    ?></a></span></p>
	
		<?php 
} else {
    ?>
			
			<?php 
    if ( 'paid' === $webinar_data->paid_status ) {
        ?>
			
			<!-- PAID WEBINAR AREA -->
			<div class="paidWebinarBlock" <?php 
        echo ( 'no' === $paid_check ? "style='display:block;'" : "style='display:none;'" );
        ?>>
				<div>
					<?php 
        webinarignition_display( $webinar_data->paid_headline, "<h5 style='text-align: center;'>" . __( 'Join The Webinar', 'webinar-ignition' ) . '<br>' . __( 'Order Your Spot Now!', 'webinar-ignition' ) . '</h5>' );
        ?>
					<p class="payment-errors" style="color: #EE3B3B; padding: 1em 1em 0 1em; font-size: .9em; text-align:center; display:none;"></p>
					<p class="payment-success" style="color: #659D32; padding: 1em 1em 0 1em; font-size: .9em; text-align:center; display:none;"></p>
				</div>
	
				<?php 
        if ( webinarignition_usingStripePaymentOption( $webinar_data ) ) {
            ?>
					<form action="" method="POST" id="stripepayment">
						<span class="payment­errors"></span>
						<div class="form-row">
							<label>
								<span><?php 
            esc_html_e( 'Card Number', 'webinar-ignition' );
            ?></span>
								<input type="text" size="20" data-stripe="number" name="stripe_number">
							</label>
						</div>
						<div class="form­row">
							<label>
								<span style="display:block;"><?php 
            esc_html_e( 'Expiration (MM/YY)', 'webinar-ignition' );
            ?></span>
								<input style="width:48%; display:inline;" type="text" maxlength="2" data­stripe="exp_month" name="stripe_exp_month">
								<span> / </span>
								<input style="width:48%; display:inline;" type="text" maxlength="2" data­stripe="exp_year" name="stripe_exp_year">
							</label>
						</div>
						<div class="form­row">
							<label>
								<span>CVC</span>
								<input type="text" size="4" data­stripe="cvc" name="stripe_cvc">
							</label>
						</div>
						<div class="form­row">
							<label>
								<span><?php 
            esc_html_e( 'Your Email Address', 'webinar-ignition' );
            ?></span>
								<input type="text" size="4" data­stripe="email" name="stripe_receipt_email">
							</label>
						</div>
					</form>
				<?php 
        } elseif ( !webinarignition_usingStripePaymentOption( $webinar_data ) && !webinarignition_usingPaypalPaymentOption( $webinar_data ) && 'woocommerce' !== $webinar_data->paid_button_type && $webinar_data->payment_form ) {
            ?>
					<?php 
            echo esc_html( $webinar_data->payment_form );
            ?>
				<?php 
        }
        ?>
	
				<?php 
        if ( webinarignition_usingStripePaymentOption( $webinar_data ) ) {
            ?>
					<div class="ccCards" style="margin-top: 10px; font-size: 12px; background-color: #F9F9F9; padding: 10px; color: #878787; padding-right: 20px;padding-left: 0px; padding-bottom: 20px;border-radius: 6px; text-align: right;">
						<img src="<?php 
            echo esc_url( $assets . 'images/powered-by-stripe.png' );
            ?>" style="margin-top: -5px; width: 22%;height: auto;float: left;"><i class="icon-lock" style="margin-right: 10px;"></i> <?php 
            esc_html_e( 'Secure Credit Card Processing', 'webinar-ignition' );
            ?>
					</div>
				<?php 
        }
        ?>
	
				<?php 
        if ( 'custom' !== $webinar_data->paid_button_type ) {
            if ( webinarignition_usingStripePaymentOption( $webinar_data ) ) {
                $wi_paymentUrl = '';
            } elseif ( webinarignition_usingPaypalPaymentOption( $webinar_data ) ) {
                $wi_paymentUrl = $webinar_data->paid_pay_url;
            } elseif ( in_array( $webinar_data->paid_button_type, array('woocommerce', 'other'), true ) ) {
                $wi_paymentUrl = ( isset( $webinar_data->paid_pay_url ) ? $webinar_data->paid_pay_url : '' );
            } else {
                $wi_paymentUrl = '';
            }
            ?>
					<a href="<?php 
            webinarignition_display( $wi_paymentUrl, '#' );
            ?>" class="large button" id="order_button"
						style=" width:100%; background-color:
						<?php 
            webinarignition_display( $webinar_data->paid_btn_color, '#5DA423' );
            ?>
						; border: 1px solid rgba(0, 0, 0, 0.5) !important;">
						<?php 
            webinarignition_display( ( 'stripe' === $webinar_data->paid_button_type ? $webinar_data->stripe_paid_btn_copy : $webinar_data->paypal_paid_btn_copy ), __( 'Order Webinar Now', 'webinar-ignition' ) );
            ?>
						</a>
					<?php 
        } else {
            echo do_shortcode( $webinar_data->paid_button_custom );
        }
        //end if
        ?>
			</div>
			
			<?php 
    }
    ?>
				<!-- OPTIN FORM -->
				<?php 
    webinarignition_generate_optin_form( $webinar_data, true );
    ?>
	
				<div class="arintegration" style="display:none;">
					<?php 
    include WEBINARIGNITION_PATH . 'inc/lp/ar_form.php';
    ?>
				</div>
	
				<?php 
}
//end if