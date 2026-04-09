<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div id="phoneReveal" style="display: none;">
	<?php webinarignition_display(
		$webinar_data->txt_reveal,
		__( 'Thanks! You will get a reminder before the webinar...', 'webinar-ignition' )
	); ?>
</div>