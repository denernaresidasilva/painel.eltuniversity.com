<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );
?>
<div class="tabber" id="tab5" style="display: none;">

<div class="titleBar">

	<div class="titleBarText">
		<h2><?php esc_html_e( 'Webinar Replay Settings:', 'webinar-ignition' ); ?></h2>

		<p><?php esc_html_e( 'Here you can manage the settings for the webinar...', 'webinar-ignition' ); ?></p>
	</div>

	<?php
	$replay_preview_url = add_query_arg( array(
		'replay' => '',
		'lid' => '[lead_id]',
		'preview' => 'true',
	), get_the_permalink( $data->postID ) );
	?>

	<div class="launchConsole">
		<a
				href="<?php echo esc_url( $replay_preview_url ); ?>"
				target="_blank"
				data-default-href="<?php echo esc_url( $replay_preview_url ); ?>"
				class="custom_replay_page-webinarPreviewLinkDefaultHolder"
		>
			<i class="icon-external-link-sign"></i>
			<?php esc_html_e( 'Preview Webinar Replay', 'webinar-ignition' ); ?>
		</a>
	</div>

	<br clear="all"/>
	<?php 
	$input_get = array(
		'id' => isset( $_GET['id'] ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : null
	); 
	?>
</div>


<?php

if ( $webinar_data->webinar_date != 'AUTO' ) {

	webinarignition_display_edit_toggle(
		'film',
		esc_html__( 'Replay Video', 'webinar-ignition' ),
		'we_edit_replay_video',
		esc_html__( 'Setup for the video that is played on the webinar replay page...', 'webinar-ignition' )
	);

}

?>

<div id="we_edit_replay_video" class="we_edit_area">
	<?php
	webinarignition_display_textarea(
		$input_get['id'],
		$webinar_data->replay_video,
		esc_html__( 'Replay Video', 'webinar-ignition' ),
		'replay_video',
		esc_html__( 'This is the embed code for the video for the webinar replay...', 'webinar-ignition' ),
		esc_html__( 'Ex. Video embed code / iframe code', 'webinar-ignition' )
	);
	webinarignition_display_info(
		esc_html__( 'Note: Video Embed Code', 'webinar-ignition' ),
		esc_html__( 'If you are using Google Hangouts embed code, the same code they provide for the live boardcast will be the same code you enter here...  920px by 518px...', 'webinar-ignition' )
	);
	?>
</div>

<?php
switch_to_locale( $webinar_data->webinar_lang );
unload_textdomain( 'webinar-ignition' );
load_textdomain( 'webinar-ignition', WEBINARIGNITION_PATH . 'languages/webinar-ignition-' . $webinar_data->webinar_lang . '.mo' );

webinarignition_display_edit_toggle(
	'time',
	esc_html__( 'Countdown - Expiring Replay', 'webinar-ignition' ),
	'we_edit_replay_cd',
	esc_html__( 'Settings for when the replay expires...', 'webinar-ignition' )
);

?>

<div id="we_edit_replay_cd" class="we_edit_area">
	<?php

	if ( $webinar_data->webinar_date == 'AUTO' ) {

		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->auto_replay,
			esc_html__( 'Replay Availability', 'webinar-ignition' ),
			'auto_replay',
			__( 'The amount of time the auto replay is available for. Default its open for 3 days after the event. To disable replay, make it specify 00.<br/><strong>Disabling the replay will prevent Instant Webinar Access. Only disable the replay, if you are not using the instant access feature.</strong>', 'webinar-ignition' ),
			esc_html__( 'Eg. 3', 'webinar-ignition' ),
			'number'
		);

	} else {

		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->replay_optional,
			esc_html__( 'Optional:: Countdown', 'webinar-ignition' ),
			'replay_optional',
			esc_html__( 'You can choose to show the countdown timer or hide it on your replay page...', 'webinar-ignition' ),
			esc_html__( 'Show Countdown Timer', 'webinar-ignition' ) . ' [show], ' . esc_html__( 'Hide Countdown Timer', 'webinar-ignition' ) . ' [hide]'
		);
		?>
		<div class="replay_optional" id="replay_optional_show">
			<?php
			webinarignition_display_date_picker(
				$input_get['id'],
				$webinar_data->replay_cd_date,
				'm-d-Y',
				esc_html__( 'Countdown Close Date', 'webinar-ignition' ),
				'replay_cd_date',
				esc_html__( 'This is the date the webinar goes down by, after this date, the replay page will be replaced with the closed page...', 'webinar-ignition' ),
				'MM-DD-YYYY',
				$webinar_date_format
			);
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->replay_cd_time,
				esc_html__( 'Countdown Close Time', 'webinar-ignition' ),
				'replay_cd_time',
				__( 'This is the time when the replay ends, <b>MUST BE IN 24 TIME, ie:  12:00 or 17:30</b>', 'webinar-ignition' ),
				'12:00'
			);

			?>
		</div>
		<div class="clear"></div>
		<?php
	}//end if
	webinarignition_display_field(
		$input_get['id'],
		$webinar_data->replay_cd_headline,
		esc_html__( 'Countdown Headline', 'webinar-ignition' ),
		'replay_cd_headline',
		esc_html__( 'This is the headline above the countdown area for how long the replay is live for...', 'webinar-ignition' ),
		esc_html__( 'Ex. This Replay Is Being Taken Down On Tuesday May 23rd', 'webinar-ignition' )
	);
	?>

</div>

<?php

webinarignition_display_edit_toggle(
	'money',
	esc_html__( 'Timed Action - Order Button', 'webinar-ignition' ),
	'we_edit_replay_timed',
	esc_html__( 'Setup the timed action - order button / html...', 'webinar-ignition' )
);

?>

<div id="we_edit_replay_timed" class="we_edit_area">
	<?php

	if ( $webinar_data->webinar_date == 'AUTO' ) { ?>
		<h3><?php esc_html_e( "Timed Actions From The 'Live' Webinar are used for the replay...", 'webinar-ignition' ); ?></h3>
		<?php
	} else {

		webinarignition_display_option(
			$input_get['id'],
			$webinar_data->replay_timed_style,
			esc_html__( 'Timed Action Style', 'webinar-ignition' ),
			'replay_timed_style',
			esc_html__( 'You can choose between a simple order button or custom HTML...', 'webinar-ignition' ),
			esc_html__( 'Order Button', 'webinar-ignition' ) . ' [button], ' . esc_html__( 'Custom HTML Copy', 'webinar-ignition' ) . ' [custom]'
		);
		?>
		<div class="replay_timed_style" id="replay_timed_style_button">
			<?php
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->replay_order_copy,
				esc_html__( 'Order Button Copy', 'webinar-ignition' ),
				'replay_order_copy',
				esc_html__( 'This is what the order button says...', 'webinar-ignition' ),
				esc_html__( 'Ex. Order Your Copy Now', 'webinar-ignition' )
			);
			webinarignition_display_field(
				$input_get['id'],
				$webinar_data->replay_order_url,
				esc_html__( 'Order URL', 'webinar-ignition' ),
				'replay_order_url',
				esc_html__( 'This is the URL where the order button will go...', 'webinar-ignition' ),
				esc_html__( 'Ex. http://yoursite.com/order-now', 'webinar-ignition' )
			);
			?>
		</div>
		<div class="replay_timed_style" id="replay_timed_style_custom">
			<?php
			webinarignition_display_wpeditor(
				$input_get['id'],
				$webinar_data->replay_order_html,
				esc_html__( 'Custom HTML Copy', 'webinar-ignition' ),
				'replay_order_html',
				esc_html__( 'This is custom html you can have for the timed area which will show under the replay...', 'webinar-ignition' )
			);
			?>
		</div>
		<?php
		webinarignition_display_field(
			$input_get['id'],
			$webinar_data->replay_order_time,
			esc_html__( 'Time For Button To Appear', 'webinar-ignition' ),
			'replay_order_time',
			esc_html__( 'This is the time in seconds you want the button to appear...', 'webinar-ignition' ),
			esc_html__( 'Ex. 60', 'webinar-ignition' )
		);

		webinarignition_display_info(
			esc_html__( 'Note: Timed Action', 'webinar-ignition' ),
			esc_html__( 'The timed action is in seconds, one second is 1, one minute would be 60, 15 minutes would be 900...', 'webinar-ignition' )
		);

	}//end if
	?>
</div>

<?php

webinarignition_display_edit_toggle(
	'remove-sign',
	esc_html__( 'Webinar Closed Copy', 'webinar-ignition' ),
	'we_edit_replay_closed',
	esc_html__( 'Copy / Settings for the closed page - when the replay has expired...', 'webinar-ignition' )
);

?>

<div id="we_edit_replay_closed" class="we_edit_area">
	<?php
	webinarignition_display_wpeditor(
		$input_get['id'],
		$webinar_data->replay_closed,
		esc_html__( 'Webinar Closed Copy', 'webinar-ignition' ),
		'replay_closed',
		esc_html__( 'This is the copy that is displayed when the countdown reaches zero, or when you select webinar closed in the main webinar control...', 'webinar-ignition' )
	);
	?>
</div>

<div class="bottomSaveArea">
	<a href="#" class="blue-btn-44 btn saveIt" style="color:#FFF;"><i class="icon-save"></i> <?php esc_html_e( 'Save & Update', 'webinar-ignition' ); ?></a>
</div>

</div>
