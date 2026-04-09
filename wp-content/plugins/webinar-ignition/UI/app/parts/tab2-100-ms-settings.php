<?php
/**
 * @var $input_get
 * @var $webinar_data
 */

 $statusCheck = WebinarignitionLicense::webinarignition_get_license_level();

$hundered_ms_management_token = get_option( 'hundered_ms_management_token', '' );
$room_id = !empty($webinar_data->hunderedms_template_room_id) ? esc_html($webinar_data->hunderedms_template_room_id) : '';
$hundered_template = !empty($webinar_data->hunderedms_template) ? esc_html($webinar_data->hunderedms_template) : '';
$hundered_ms_template_subdomain = !empty($webinar_data->hundered_ms_template_subdomain) ? esc_html($webinar_data->hundered_ms_template_subdomain) : '';
?>
<div class="editSection infoSection">
    <p>
        <?php
        printf(
            /* translators: 1: open anchor tag, 2: close anchor tag */
            esc_html__(
                'Go to 100ms %1$sDeveloper Settings%2$s and copy management_token form there and paste in this field. Then save the settings to get the remaining options.',
                'webinar-ignition'
            ),
            '<a href="https://dashboard.100ms.live/developer" target="_blank">',
            '</a>',
        );
        ?>
    </p>
</div>
<?php
webinarignition_display_field(
    $input_get['id'],
    $hundered_ms_management_token,
    esc_html__( '100MS Management Token', 'webinar-ignition' ),
    'hundered_ms_token',
    esc_html__( 'Input your 100MS Management Token here', 'webinar-ignition' ),
    'Input your 100MS Management Token here'
);
?>
<div class="editSection infoSection">
    <p>
        <?php
        printf(
            /* translators: 1: open anchor tag, 2: close anchor tag */
            esc_html__(
                'Go to 100ms %1$sDashboard%2$s and create three templates from there as per your settings requirements. You will see the templates in the dropdown below. If you want to use a custom template, you can create it from there.',
                'webinar-ignition'
            ),
            '<a href="https://dashboard.100ms.live/dashboard" target="_blank">',
            '</a>',
        );
        ?>
    </p>
</div>
<?php
$hundered_ms_templates = WebinarignitionManager::get_100ms_templates( $hundered_ms_management_token );
?>
<div class="editSection">
    <div class="inputTitle">
        <div class="inputTitleCopy" ><?php esc_html_e('Select Template', 'webinar-ignition'); ?></div>
    </div>
    <div class="inputSection">
        <select name="hunderedms_template" id="hunderedms_template" class="form-control">
			<option value=""><?php esc_html_e( 'Select 100MS Template', 'webinar-ignition' ); ?></option>
			<?php
			if ( is_array( $hundered_ms_templates ) && ! empty( $hundered_ms_templates ) ) {
				foreach ( $hundered_ms_templates as $template ) {
					$selected = ( $hundered_template == $template['id'] ) ? 'selected' : '';
					echo '<option value="' . esc_attr( $template['id'] ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $template['name'] ) . '</option>';
				}
			}
			?>
		</select>
    </div>
</div>
<div class="editSection infoSection">
    <p>
        <?php
        printf(
            /* translators: 1: open anchor tag, 2: close anchor tag */
            esc_html__(
                'Copy the subdomain of the selected template and paste it below field it will belike this test-subdomain.app.100ms.live.',
                'webinar-ignition'
            )
        );
        ?>
    </p>
</div>
<?php
webinarignition_display_field(
    $input_get['id'],
    $hundered_ms_template_subdomain,
    esc_html__( '100MS Template sub domain', 'webinar-ignition' ),
    'hundered_ms_template_subdomain',
    esc_html__( 'Input your 100MS Template sub domain here', 'webinar-ignition' ),
    'Input your 100MS Template sub domain here'
);

