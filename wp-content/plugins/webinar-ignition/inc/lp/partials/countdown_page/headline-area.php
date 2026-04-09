<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinar_data
 */
?>

<?php if ( 'AUTO' === $webinar_data->webinar_date ) { ?>
	<div class="headlineArea">
		<?php
		webinarignition_display(
			$webinar_data->cd_headline,
			'<h4 class="subheader">' . __( 'You Are Viewing A Webinar That Is Not Yet Live', 'webinar-ignition' ) . ' - <b>' . __( 'We Go Live Soon!', 'webinar-ignition' ) . '</b></h4>'
		);
		?>
	</div>
<?php } else { ?>
	<div class="headlineArea">
		<?php
		webinarignition_display(
				$webinar_data->cd_headline,
				'<h4 class="subheader">' . __( 'You Are Viewing A Webinar That Is Not Yet Live', 'webinar-ignition' ) . ' - <b>' . __( 'We Go Live Soon!', 'webinar-ignition' ) . '</b></h4>
					<h2 style="margin-top: -10px; margin-bottom: 30px;" >' . __( 'Webinar Starts', 'webinar-ignition' ) . ' {DATE}</h2>'
			);
		?>
	</div>
<?php } ?>
