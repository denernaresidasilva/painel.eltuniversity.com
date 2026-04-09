<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo esc_html( $webinar_data->webinar_desc ); ?></title>
	<?php wp_head(); ?>
</head>
<?php
// make the thank you page url

$webinar_status = 'draft';
if ( !isset( $webinar_data->webinar_status ) || ( 'draft' === $webinar_data->webinar_status )){
	$webinar_status = 'draft';

}else{
	$webinar_status = 'published';
}

if ( 'show' === $webinar_data->custom_ty_url_state && ! empty( $webinar_data->custom_ty_url ) ) {
	$thank_you_page_url = esc_url_raw($webinar_data->custom_ty_url);
	$reg_page_url = esc_url_raw($webinar_data->custom_ty_url);
} else {
	if('AUTO' != $webinar_data->webinar_date){
		$request_uri = get_the_permalink(WebinarignitionManager::webinarignition_get_webinar_post_id($webinar_data->id));
	}else{
		$request_uri = isset($_SERVER['REQUEST_URI']) ? esc_url_raw($_SERVER['REQUEST_URI']) : '';
		$request_uri = strtok($request_uri, '?');

	}
		$thank_you_page_url = ( isset($webinar_data->webinar_switch) && 'live' === $webinar_data->webinar_switch ) 
        ? wp_parse_url($request_uri, PHP_URL_PATH) . '?live' 
        : wp_parse_url($request_uri, PHP_URL_PATH) . '?confirmed';
	if ( 'paid' === $webinar_data->paid_status ) {
		$paid_code = isset($webinar_data->paid_code) ? sanitize_text_field($webinar_data->paid_code) : '';
		$thank_you_page_url .= '&' . rawurlencode( $paid_code );
	}
}
$current_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http" );
$current_url .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

// remove any ?query=string
$reg_page_url = strtok($current_url, '?');
$email               = isset( $_GET['e'] ) ? htmlspecialchars( $_GET['e'] ) : null;//phpcs:ignore
$plain_email         = $email;
$email               = is_email( $email ) ? $email : base64_decode( $email );//phpcs:ignore
$readonly            = isset( $_GET['readonly'] ) ? '&readonly=' . $_GET['readonly'] : '';//phpcs:ignore
$login               = isset( $_GET['login'] ) ? $_GET['login'] : false;//phpcs:ignore
$name 				 = isset( $_GET['n'] ) ? htmlspecialchars( $_GET['n'] ) : null; // phpcs:ignore
$salutation			 = isset( $_GET['sal'] ) ? htmlspecialchars( $_GET['sal'] ) : null; // phpcs:ignore
$reason			 	 = isset( $_GET['r'] ) ? htmlspecialchars( $_GET['r'] ) : null; // phpcs:ignore
$last_name			 = isset( $_GET['ln'] ) ? htmlspecialchars( $_GET['ln'] ) : null; // phpcs:ignore
$ty_salutation		 = isset( $salutation ) ? '&sal='.$salutation: ''; // phpcs:ignore
$ty_lname		 	 = isset( $last_name ) ? '&ln='.$last_name: ''; // phpcs:ignore
$ty_reason		 	 = isset( $reason ) ? '&r='.$reason: ''; // phpcs:ignore
$reg_page_url  		 .= '?n=' . $name . '&e=' . $email .$ty_salutation. $ty_reason. $ty_lname. $readonly ;

$thank_you_page_url .= '&webinar&first=' . $name . '&email=' . $email .$ty_salutation. $ty_reason. $ty_lname. $readonly . $login;//phpcs:ignore


$ip                  = esc_url(sanitize_text_field( $_SERVER['REMOTE_ADDR'] ));
$current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
$current_url .= "://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

?>
<body
	id="auto-register"
	style="text-align: center;"
	data-webinar-id="<?php echo absint( $webinar_id ); ?>"
	data-name="<?php echo esc_attr( $name ); ?>"
	data-lname="<?php echo esc_attr( $last_name ); ?>"
	data-salutation="<?php echo esc_attr( $salutation ); ?>"
	data-reason="<?php echo esc_attr( $reason ); ?>"
	data-email="<?php echo esc_attr( $email ); ?>"
	data-ip="<?php echo esc_attr( $ip ); ?>"
	data-thank-you-page-url="<?php echo esc_url( $thank_you_page_url ); ?>"
	data-reg-you-page-url="<?php echo esc_url( $reg_page_url ); ?>"
	data-webinar-type="<?php echo 'AUTO' === $webinar_data->webinar_date ? 'evergreen' : 'live'; ?>"
	data-email-verification-setting="<?php echo esc_html( $webinar_data->email_verification ); ?>"
	data-email-verification-enabled="<?php echo wp_json_encode( filter_var( get_option( 'webinarignition_email_verification', 0 ), FILTER_VALIDATE_BOOLEAN ) ); ?>"
	data-plain-email="<?php echo esc_attr( $plain_email ); ?>"
	data-auto-login-enabeled ="<?php echo esc_attr( $login ); ?>"
	data-webinar-status="<?php echo esc_attr( $webinar_status ); ?>"
>
<?php
$webinarignition_enable_honeypot_field        = absint(get_option('webinarignition_enable_honeypot_field', 1));
$webinarignition_enable_honeypot_field        = $webinarignition_enable_honeypot_field == 1 ? true : false;
if ( $webinarignition_enable_honeypot_field ) {
	?>
	<span style="display:none;">
		<input type="text" id="full_name_pot" class="full_name_pot" name="full_name" value="" autocomplete="off" >
	</span>
	<?php
} ?>
<div class="informationBox">
	<h2 style="margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px dashed #b3b3b3;"><?php echo esc_html( $webinar_data->webinar_desc ); ?></h2>
	<h4 style="font-weight:normal;"><?php echo esc_html( $webinar_data->webinar_host ); ?></h4>
</div>

<div class="loaderBox">
	<i class="fa fa-spinner fa-spin fa-4x"></i>
</div>
<div class="autoRegisterfalse" style="display:none; margin-top:20px;">
	<?php isset( $_GET['login'] ) ? $_GET['login'] : false; //phpcs:ignore ?>
</div>


<!-- AR OPTIN INTEGRATION -->
<div class="arintegration" style="display:none;">
	<?php require WEBINARIGNITION_PATH . 'inc/lp/ar_form.php'; ?>
</div>
</body>
</html>
<?php wp_footer(  ); ?>
