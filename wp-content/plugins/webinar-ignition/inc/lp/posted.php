<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WP_USE_THEMES', false );
require ABSPATH . 'wp-blog-header.php';
status_header( 200 );

// universal variables
$full_path  = get_site_url();
$assets     = WEBINARIGNITION_URL . 'inc/lp/';

// Only get the required values from INPUT_POST
$campaignID = isset( $_POST['campaignID'] ) ? sanitize_text_field( $_POST['campaignID'] ) : null;
$name       = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : null;
$email      = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : null;
$phone      = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : null;

// Get DB Info
global $wpdb;
$table_db_name = $wpdb->prefix . 'webinarignition';
$ID            = intval($campaignID); // Ensure $ID is an integer

// Prepare and execute the query
$data = $wpdb->get_row(
    $wpdb->prepare(
        "SELECT * FROM `{$table_db_name}` WHERE `id` = %d",
        $ID
    ),
    OBJECT
);
$pluginName = 'webinarignition';
$sitePath   = WEBINARIGNITION_URL;

// Get Results
$results = WebinarignitionManager::webinarignition_get_webinar_data( $campaignID );

// JUST CONNECTED - STORE LEAD - REDIRECT TO AR OR THANK YOU PAGE
webinarignition_add_lead_callback(); ?>

<!DOCTYPE HTML>

<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<title>You are being registered for the webinar</title>
	<style>
		.main {
			margin: 0 auto;
			font-family: "Lato", sans-serif;
			text-align: center
		}

		@import url("//fonts.googleapis.com/css?family=Lato:100,300,700");
		h1 {
			font-family: Lato;
			color: #000;
			text-transform: uppercase;
			display: inline-block;
			font-size: 1em;
			letter-spacing: 1.5px;
			text-align: center;
			margin-top: 20px;
			-webkit-animation: fade 3s infinite
		}

		.container {
			width: 110px;
			padding-top: 180px;
			margin: auto;
			vertical-align: middle
		}

		.ex {
			color: #000;
			text-transform: uppercase;
			letter-spacing: 1.5px;
			text-align: center;
			-webkit-animation: fade 3s infinite;
			font-family: flamenco;
			font-size: 4em;
			width: 40px;
			height: 40px;
			margin-top: -17px;
			display: inline-block;
			border: 4px double #333
		}

		.ex:nth-child(1) {
			-webkit-animation: spin1 3s infinite 1s;
			-webkit-transform-origin: 50% 52%;
			margin-left: 10px
		}

		.ex:nth-child(2) {
			-webkit-animation: spin2 3s infinite 1s;
			-webkit-transform-origin: 50% 52%;
			margin-left: -20px
		}

		.ex:nth-child(3) {
			-webkit-animation: spin2 3s infinite 1s;
			-webkit-transform-origin: 50% 52%;
			margin-left: 10px
		}

		.ex:nth-child(4) {
			-webkit-animation: spin1 3s infinite 1s;
			-webkit-transform-origin: 50% 52%;
			margin-left: -20px
		}

		@-webkit-keyframes spin1 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(360deg)
			}
		}

		@-moz-keyframes spin1 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(360deg)
			}
		}

		@-o-keyframes spin1 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(360deg)
			}
		}

		@keyframes spin1 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(360deg)
			}
		}

		@-webkit-keyframes spin2 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(-360deg)
			}
		}

		@-moz-keyframes spin2 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(-360deg)
			}
		}

		@-o-keyframes spin2 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(-360deg)
			}
		}

		@keyframes spin2 {
			0% {
				-webkit-transform: rotate(0deg)
			}
			100% {
				-webkit-transform: rotate(-360deg)
			}
		}

		@-webkit-keyframes fade {
			50% {
				opacity: .5
			}
			100% {
				opacity: 1
			}
		}

		@-moz-keyframes fade {
			50% {
				opacity: .5
			}
			100% {
				opacity: 1
			}
		}

		@-o-keyframes fade {
			50% {
				opacity: .5
			}
			100% {
				opacity: 1
			}
		}

		@keyframes fade {
			50% {
				opacity: .5
			}
			100% {
				opacity: 1
			}
		}
	</style>
<?php wp_head(); ?>
</head>

<body>


<?php
// make the thank you page url
$thank_you_page_url = 'show' === $results->custom_ty_url_state && ! empty( $results->custom_ty_url ) ? $results->custom_ty_url : $results->webinar_permalink . '?confirmed';
?>


<section class="main" rel="js-thank-you-url" data-thank-you-page-url="<?php echo esc_url( $thank_you_page_url ); ?>" >
		<div class="wiContainer container">
			<div class="ex"></div>
			<div class="ex"></div>
			<div class="ex"></div>
			<div class="ex"></div>
		</div>
		<h1>Signing you up ...</h1>
	</section>

	<!-- AR OPTIN INTEGRATION -->
	<div class="arintegration" style="display:none;">

		<?php
		if ( ! empty( $results->ar_url ) ) {
			$webinar_data = $results;
			include WEBINARIGNITION_PATH . 'inc/lp/ar_form.php';
		}
		?>

		<!-- wi-js-104 Copied to webinarignition-frontend.js File -->
	</div>
	<?php wp_footer(); ?>
</body>

</html>
