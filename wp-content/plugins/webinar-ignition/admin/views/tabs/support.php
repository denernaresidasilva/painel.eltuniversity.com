<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
* Renders the contents of the settings submenu page
 *
* @since    2.2.7    *
*/
?>
<style>
	.wi_kb_iframe_wrap {
		/* width: 100%;
		height: 96%;
		padding: 0;
		overflow: hidden; */
	}
	#wi_kb_iframe {
		width: 100%;
		height: 1400px;
		border: 0px;
	}
	#wi_kb_iframe {
		zoom: 1;
		-moz-transform: scale(1);
		-moz-transform-origin: 0 0;
		-o-transform: scale(1);
		-o-transform-origin: 0 0;
		-webkit-transform: scale(1);
		-webkit-transform-origin: 0 0;
	}
	@media screen and (-webkit-min-device-pixel-ratio:0) {
		#wi_kb_iframe {
			zoom: 1;
			height: 1500px;
		}
	}
</style>
<div class="wrap wi_kb_iframe_wrap">
	<iframe id="wi_kb_iframe" scrolling="no" frameborder="0" src="<?php echo esc_attr( $support_link ); ?>"></iframe>
</div>