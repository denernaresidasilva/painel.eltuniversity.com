<?php
/**
 * @var $webinar_data
 */

 if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?><style type="text/css">
.mainWrapper{
	background-color: <?php if ( $webinar_data->webinar_background_color == '' ) {
		echo '#f1f1f1;';
					  } else {
						  echo esc_attr($webinar_data->webinar_background_color); } ?>;
	<?php
	if ( $webinar_data->webinar_background_image == '' ) {
		echo 'border-top: 3px solid rgba(0,0,0,0.05);
                  border-bottom: 3px solid rgba(0,0,0,0.05);';
	} else {
		echo "background-image: url(" . esc_url($webinar_data->webinar_background_image) . ");";
	}
	?>
	padding-bottom: 130px;
}

.webinarVideo{
	background-color: <?php if ( $webinar_data->webinar_live_bgcolor == '' ) {
		echo '#212121;';
						} else {
							echo esc_attr($webinar_data->webinar_live_bgcolor) . '!important'; } ?>;
}

.webinarTopBar{
	background-color: <?php if ( $webinar_data->webinar_live_bgcolor == '' ) {
		echo '#212121;';
						} else {
							echo esc_attr($webinar_data->webinar_live_bgcolor) . '!important'; } ?>;
}

.webinarExtireTop{
	color: <?php if ( ! isset( $webinar_data->replay_cd_color ) || $webinar_data->replay_cd_color == '' ) {
		echo '#2d2d2d;';
			} else {
				echo esc_attr($webinar_data->replay_cd_color) . '!important'; } ?>;
}

.countdown_section{
	background-color: #2d2d2d;
}


.webinarReplayExpireCopy span{
	background-color: none;
}

.autoWebinarLoadingCopy{
	color: #FFF;
	text-align: center;
	width: 500px;
	margin-right: auto;
	margin-left: auto;
	padding-top: 100px;
}

.autoWebinarLoadingCopy h2{
	color: #fff;
}

.autoWebinarLoader{
	font-size: 72px;
}

.topArea {
	background-color: <?php if ( $webinar_data->webinar_banner_bg_color == '' ) {
		echo '#fff;';
						} else {
							echo esc_attr($webinar_data->webinar_banner_bg_color); } ?>;
}
</style>
