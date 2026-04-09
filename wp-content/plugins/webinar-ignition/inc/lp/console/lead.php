<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!-- ON AIR AREA -->
<div id="leadTab" style="display:none;" class="consoleTabs">
	<div class="statsDashbord">
		<div class="statsTitle statsTitle-Lead">
			<div class="statsTitleIcon">
				<i class="icon-group icon-2x"></i>
			</div>

			<div class="statsTitleCopy">
				<h2><?php 
esc_html_e( 'Manage Registrants For Webinar', 'webinar-ignition' );
?></h2>

				<p><?php 
esc_html_e( 'All your Registrants / Leads for the event...', 'webinar-ignition' );
?></p>
			</div>

			<br clear="left"/>
		</div>
	</div>

	
	<?php 
$mapped_fields = get_post_meta( $webinar_data->id, 'webinar_import_mapped_fields', true );
?>
		<div class="container">
			<?php 
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?>
		</div>
		<?php 
?>
</div>

