<?php
/**
 * @var $webinar_id
 * @var $is_support_stuff_token
 * @var $is_host_presenters_token
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><!DOCTYPE html>
<html lang="en" style="margin-top:0 !important;">
<head>
	<title><?php esc_html_e( 'WebinarIgnition - Live Webinar Console', 'webinar-ignition' ); ?></title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

	<?php wp_head(); ?>

	<style>
		.registerSupport {
			width: 100%;
			max-width: 920px;
			padding: 25px 15px;
			margin: 0 auto;
		}

		#registerSupport_form {
			width: 100%;
			max-width: 460px;
			margin: 0 auto;
		}

		.registerSupport_row {
			margin-bottom: 25px;
		}

		.registerSupport_row.errored label {
			color: #900000;
		}

		.registerSupport_row.errored input {
			border-color: #900000;
		}

		.registerSupport_row:last-child {
			margin-bottom: 0;
		}

		#registerSupport_button {
			margin: 0 !important;
			width: 100%;
		}
	</style>
</head>

<body id="webinarignition_console" class="webinarignition console">

	<!-- TOP AREA -->
	<div class="topArea">
		<div class="consoleLogo">
			<?php
			$logo = $assets . 'images/logoC.png';

			if ( ! empty( $webinar_data->live_console_logo ) ) {
				$logo = $webinar_data->live_console_logo;
			}
			?>
			<img src="<?php echo esc_url( $logo ); ?>">
		</div>
	</div>

	<div class="mainWrapper">
		<div id="registerSupport_container" class="registerSupport">
			<form id="registerSupport_form">
				<input name="app_id" type="hidden" value="<?php echo esc_html( $webinar_id ); ?>">
				<input name="support_stuff_url" type="hidden" value="<?php echo esc_html( $is_support_stuff_token ); ?>">
				<input name="host_presenters_url" type="hidden" value="<?php echo esc_html( $is_host_presenters_token ); ?>">

				<p id="registerSupport_email" class="registerSupport_row">
					<label for="support_email">
						<?php esc_html_e( 'Email (required)', 'webinar-ignition' ); ?>
					</label>

					<input class="registerSupport_field" type="email" name="email" id="support_email">
				</p>

				<p id="registerSupport_first_name" class="registerSupport_row">
					<label for="support_first_name">
						<?php esc_html_e( 'First Name (required)', 'webinar-ignition' ); ?>
					</label>

					<input class="registerSupport_field" type="text" name="first_name" id="support_first_name">
				</p>

				<p id="registerSupport_last_name" class="registerSupport_row">
					<label for="support_last_name">
						<?php esc_html_e( 'Last Name (required)', 'webinar-ignition' ); ?>
					</label>

					<input class="registerSupport_field" type="text" name="last_name" id="support_last_name">
				</p>

				<p id="registerSupport_salutation" class="registerSupport_row">
					<label for="support_salutation">
						<?php esc_html_e( 'Salutation', 'webinar-ignition' ); ?>
					</label>

					<input class="registerSupport_field" type="text" name="salutation" id="support_salutation">
				</p>

				<p class="registerSupport_row">
					<button id="registerSupport_button" type="button" class="button radius success">
						<i class="icon-save"></i> <?php esc_html_e( 'Register', 'webinar-ignition' ); ?>
					</button>
				</p>
			</form>
		</div>

		<?php require 'partials/footerArea.php'; ?>
	</div>

	<?php wp_footer(); ?>
</body>
</html>