<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;
if (class_exists('NextendSocialLogin')) {
	$webinarignition_registration_shortcode		  	= get_option('webinarignition_registration_shortcode', '[nextend_social_login]');
} else {
	$webinarignition_registration_shortcode		  	= get_option('webinarignition_registration_shortcode', '');
}
if (! empty($is_list)) {
?><?php
} else {
	?><?php
	}
		?>


<div class="wi-code-example-wrap grid-col-start mt-20 grid-col-end-3">
	<h4>
		<?php echo esc_html__('Registration form', 'webinar-ignition'); ?>
	</h4>
</div>









<?php if (isset($webinarignition_registration_shortcode) && $webinarignition_registration_shortcode != '') { ?>
	<div class="wi-code-example-wrap">
		<p>
			<?php echo esc_html__('This shortcode will be used to show the auto login button on the registration page ', 'webinar-ignition'); ?>
		</p>
		<p class="code-example">
			<span class="code-example-value"><?php echo esc_html($webinarignition_registration_shortcode); ?></span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
		</p>
		<p>
			<?php echo esc_html__('If you need to show dates selection and registration form in different page blocks, you need to use both shortcodes below', 'webinar-ignition'); ?>
		</p>
	</div>
<?php } ?>




<div class="wi-code-example-wrap grid-col-start">

	<p class="wi-desc-top"><?php echo esc_html__('Webinar dates selection', 'webinar-ignition'); ?></p>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_optin_dates"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('Webinar dates selection', 'webinar-ignition'); ?></p>

</div>

<div class="wi-code-example-wrap">

	<p class="wi-desc-top"><?php echo esc_html__('Optin form fields', 'webinar-ignition'); ?></p>
	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_optin_form" readonly="false"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base"><?php echo esc_html__('Optin form fields', 'webinar-ignition'); ?></p>

</div>


<div class="wi-code-example-wrap">

	<p class="wi-desc-top">
		<?php echo esc_html__('If you want to show dates selection and optin fields without heading, you can use compact views below', 'webinar-ignition'); ?>
	</p>

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_optin_dates_compact"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
	</p>
	<p class="wi-desc-bottom text-light text-base">
		<?php echo esc_html__('If you want to show dates selection and optin fields without heading, you can use compact views below', 'webinar-ignition'); ?>
	</p>
</div>


<div class="wi-code-example-wrap">

	<p class="code-example">
		<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_optin_form_compact" readonly="false"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
	</p>

</div>



<?php
if ($webinar_data->webinar_date !== 'AUTO') {
?>
	<div class="wi-code-example-wrap">

		<p class="code-example">
			<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_date_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
		</p>
	</div>

	<div class="wi-code-example-wrap">
		<p class="code-example">
			<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_time_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
		</p>
	</div>
	<div class="wi-code-example-wrap">
		<p class="code-example">
			<span class="code-example-value">[wi_webinar_block id="<?php echo esc_html($webinarId); ?>" block="reg_timezone_inline"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__('copy', 'webinar-ignition'); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__('Copied!', 'webinar-ignition'); ?></span>
		</p>
	</div>
<?php
} //end if
?>
<div class="wi-code-example-wrap grid-col-start mt-20 grid-col-end-3 wi-info-wrap">
	<p class="wi-info text-light">
		<?php echo esc_html__('Only one set of shortcodes can be used per page!', 'webinar-ignition'); ?>
	</p>
</div>