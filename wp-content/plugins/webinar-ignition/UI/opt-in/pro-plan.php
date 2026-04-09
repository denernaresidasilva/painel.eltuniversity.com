<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>
<div class="opt-in-popup">
	<a class="btn btn-primary btn-orange popup-btn-opt-in" href="<?php echo esc_url( $statusCheck->reconnect_url ); ?>">
		<span class="dashicons dashicons-arrow-right-alt"></span>
		Opt-In
	</a>
</div>