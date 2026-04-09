<?php 

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @var $webinarId
 * @var $webinar_data
 */
$prefix = 'webinarInfo-';
$uid = wp_unique_id( $prefix );
?>

<div id="<?php echo esc_attr( $uid ); ?>" class="webinarInfo webinarInfo-<?php echo esc_attr( $webinarId ); ?>">
	<div class="webinarTopBar">
		<i class="icon-exclamation-sign"></i>
		<?php webinarignition_display( $webinar_data->webinar_info_block_title, __( 'Webinar Information', 'webinar-ignition' ) ); ?>
	</div>

	<div class="webinarInner">
		<div class="webinarTitleBar"><i class="icon-microphone"></i>
			<?php webinarignition_display( $webinar_data->webinar_info_block_host, __( 'Your Host:', 'webinar-ignition' ) ); ?>
		</div>
		<div class="webinarInfoCopy">
			<?php webinarignition_display( $webinar_data->webinar_host, __( 'Your Name Here', 'webinar-ignition' ) ); ?>
		</div>
		<div class="webinarTitleBar webinarTitleBarAdded"><i class="icon-info"></i>
			<?php webinarignition_display( $webinar_data->webinar_info_block_desc, __( 'What You Will Learn:', 'webinar-ignition' ) ); ?>
		</div>
		<div class="webinarInfoCopy">
			<?php webinarignition_display( $webinar_data->webinar_desc, __( 'In this webinar, you will learn everything you need to know about the webinar...', 'webinar-ignition' ) ); ?>
		</div>
	</div>
</div>
