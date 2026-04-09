<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
$action = sanitize_text_field( filter_input( INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS ) );
?>
<div class="wrap">
	<h1><?php 
echo esc_attr( 'WebinarIgnition Settings' );
?></h1>
	<?php 
require_once WEBINARIGNITION_PATH . 'admin/views/setting_tabs.php';
?>

	<div style="background-color: #FFF; padding:20px 10px 10px 10px; margin:20px 0;">
		<?php 
echo '<a href="https://webinarignition.tawk.help/category/webhooks">' . esc_html__( 'See how you can send leads data anywhere with webhooks', 'webinar-ignition' ) . '</a>';
include WEBINARIGNITION_PATH . 'templates/notices/pro-notice.php';
?>
	</div>
</div>