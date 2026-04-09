<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if (! empty($is_list)) {
?><?php
} else {
	?><?php
	}
		?>

<div class="wi-wrap bg-white wi-global-shortcodes">


	<div class="flex flex-row md-flex-col sm-flex-col gap-20 mt-20">
		<div class="wi-webinar-info w-half md-w-full sm-w-full">
			<h4>
				<?php echo esc_html__('Webinar info', 'webinar-ignition'); ?>
			</h4>


			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="global_webinar_title"]</span><!--
				--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
				--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
			</p>
			<p class="text-base text-light">
				<?php echo esc_html__('Webinar Title', 'webinar-ignition'); ?>: <?php echo ! empty($webinar_data->webinar_desc) ? esc_attr($webinar_data->webinar_desc) : ''; ?>
			</p>




			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="global_host_name"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
			</p>
			<p class="text-base text-light">
				<?php echo esc_html__('Webinar Host Name', 'webinar-ignition'); ?>: <?php echo ! empty($webinar_data->webinar_host) ? esc_html($webinar_data->webinar_host) : ''; ?>
			</p>

			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr($webinarId); ?>" block="global_webinar_giveaway"]</span><!--
			--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
			--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
			</p>
			<p class="text-base text-light">
				<?php echo esc_html__('Webinar Giveaway section content', 'webinar-ignition'); ?>:
			</p>
			<div style="" class="wi-gift-bg">
				<?php webinarignition_display($webinar_data->webinar_giveaway, '<h4>' . __('Your Awesome Free Gift</h4><p>You can download this awesome report made you...', 'webinar-ignition') . '</p><p>[ ' . __('DOWNLOAD HERE', 'webinar-ignition') . ' ]</p>'); ?>
			</div>
		</div>
		<div class="wi-lead-info w-half md-w-full sm-w-full">
			<h4>
				<?php echo esc_html__('Lead info', 'webinar-ignition'); ?>
			</h4>
			


			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="global_lead_name"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
			</p>



			<p class="text-base text-light">
				<?php echo esc_html__('Lead Name', 'webinar-ignition'); ?>: <?php echo esc_html__('John Doe', 'webinar-ignition'); ?>
			</p>



			<p class="code-example">
				<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="global_lead_email"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
			</p>
			<p class="text-base text-light">
				<?php echo esc_html__('Lead Email', 'webinar-ignition'); ?>: <?php echo esc_html__('john.doe@maildomain.com', 'webinar-ignition'); ?>
			</p>


			<p class="code-example">
				<span class="code-example-value">[webinarignition_footer]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
			</p>
			<p class="text-base text-light">
				<?php echo esc_html__('Footer Shortcode', 'webinar-ignition'); ?>
			</p>
			<div style="" class="wi-gift-bg">

			<p>
				<?php echo esc_html__('Lead info could be only get after registration. So you should not use shortcodes below it on registration pages.', 'webinar-ignition'); ?>
			</p>


			</div>

		</div>
	</div>







</div>