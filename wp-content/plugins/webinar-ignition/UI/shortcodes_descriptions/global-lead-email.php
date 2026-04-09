<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * @var $webinar_data
 * @var $is_list
 */

$webinarId = $webinar_data->id;

if ( ! empty( $is_list ) ) {
	?><?php
} else {
	?><?php
}
?>

<p class="code-example">
	<span class="code-example-value">[wi_webinar_block id="<?php echo esc_attr( $webinarId ); ?>" block="global_lead_email"]</span><!--
	--><span class="code-example-copy"><?php echo esc_html__( 'copy', 'webinar-ignition' ); ?></span><!--
	--><span class="code-example-copied"><?php echo esc_html__( 'Copied. Input into your content!', 'webinar-ignition' ); ?></span>
</p>