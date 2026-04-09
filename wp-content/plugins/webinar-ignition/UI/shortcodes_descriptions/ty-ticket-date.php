<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;
?>
<div class="wi-code-example-wrap grid-col-start grid-col-end-3 mt-20">
	<?php if (! empty($is_list)) {  ?>
		<h4><?php echo esc_html__('Selected Date / Time', 'webinar-ignition'); ?></h4>
	<?php } ?>
	<div class="wi-grid">

		<div class="wi-code-example-wrap">

			<?php if (! empty($is_list)) { ?>
				<p class="wi-desc-top"><?php echo esc_html__('Selected Date / Time section', 'webinar-ignition'); ?></p>
			<?php } ?>

			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_ticket_date"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
			</p>

			<?php if (! empty($is_list)) { ?>
				<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('Selected Date / Time section', 'webinar-ignition'); ?></p>
			<?php } ?>
		</div>





		<div class="wi-code-example-wrap">
			<?php if (! empty($is_list)) {	?>
				<p class="wi-desc-top"><?php echo esc_html__('Inline Selected Date', 'webinar-ignition'); ?></p>
			<?php }  ?>

			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_date_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
			</p>
			<?php if (! empty($is_list)) {	?>
				<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('Inline Selected Date', 'webinar-ignition'); ?></p>
			<?php }  ?>
		</div>







		<div class="wi-code-example-wrap">
			<?php if (! empty($is_list)) {	?>
				<p class="wi-desc-top"><?php echo esc_html__('InlineSelected Time', 'webinar-ignition'); ?></p>
			<?php } ?>

			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_time_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
			</p>
			<?php if (! empty($is_list)) {	?>
				<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('InlineSelected Time', 'webinar-ignition'); ?></p>
			<?php } ?>
		</div>


		<?php if ($webinar_data->webinar_date !== 'AUTO') { ?>

			<div class="wi-code-example-wrap">

				<?php if (! empty($is_list)) { ?>
					<p class="wi-desc-top"><?php echo esc_html__('InlineSelected Timezone (works only for live webinars)', 'webinar-ignition'); ?></p>
				<?php } ?>

				<p class="code-example">
					<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="ty_timezone_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied. Input into your content!', 'webinar-ignition'); ?></span>
				</p>

				<?php if (! empty($is_list)) { ?>
					<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('InlineSelected Timezone (works only for live webinars)', 'webinar-ignition'); ?></p>
				<?php } ?>
			</div>
		<?php } ?>

		<?php if (! empty($is_list)) { ?>
			<hr>
		<?php } ?>
	</div>
</div>