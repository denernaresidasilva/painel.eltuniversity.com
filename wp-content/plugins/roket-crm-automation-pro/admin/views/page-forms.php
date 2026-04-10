<?php
/**
 * Forms page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$lists = WPLA_List_Model::all();
?>
<div class="wpla-forms-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Form Shortcodes', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Each list generates a form automatically. Copy the shortcode and paste it in any page or post.', 'roket-crm' ); ?></p>

        <?php if ( empty( $lists ) ) : ?>
            <div class="wpla-empty-state">
                <p>📋 <?php esc_html_e( 'No lists yet. Create a list first to generate form shortcodes.', 'roket-crm' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpla-lists' ) ); ?>" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Create List', 'roket-crm' ); ?></a>
            </div>
        <?php else : ?>
            <div class="wpla-table-wrapper">
                <table class="wpla-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'List', 'roket-crm' ); ?></th>
                            <th><?php esc_html_e( 'Shortcode', 'roket-crm' ); ?></th>
                            <th><?php esc_html_e( 'Preview', 'roket-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $lists as $list ) : ?>
                            <tr>
                                <td><strong><?php echo esc_html( $list->name ); ?></strong></td>
                                <td>
                                    <code class="wpla-code wpla-copyable" data-copy="[wpla_form list=&quot;<?php echo esc_attr( $list->id ); ?>&quot;]">
                                        [wpla_form list="<?php echo esc_html( $list->id ); ?>"]
                                    </code>
                                </td>
                                <td>
                                    <button class="wpla-btn wpla-btn-sm" onclick="WPLA.copyShortcode(this)"><?php esc_html_e( 'Copy', 'roket-crm' ); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Elementor Widget', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'If you have Elementor installed, search for "Roket CRM Form" in the widget panel to add lead capture forms visually.', 'roket-crm' ); ?></p>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Shortcode Options', 'roket-crm' ); ?></h3>
        <div class="wpla-table-wrapper">
            <table class="wpla-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Attribute', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Description', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Example', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>list</code></td>
                        <td><?php esc_html_e( 'List ID to subscribe to', 'roket-crm' ); ?></td>
                        <td><code>list="1"</code></td>
                    </tr>
                    <tr>
                        <td><code>title</code></td>
                        <td><?php esc_html_e( 'Form title', 'roket-crm' ); ?></td>
                        <td><code>title="Join us"</code></td>
                    </tr>
                    <tr>
                        <td><code>button_text</code></td>
                        <td><?php esc_html_e( 'Submit button text', 'roket-crm' ); ?></td>
                        <td><code>button_text="Sign Up"</code></td>
                    </tr>
                    <tr>
                        <td><code>class</code></td>
                        <td><?php esc_html_e( 'Custom CSS class', 'roket-crm' ); ?></td>
                        <td><code>class="my-form"</code></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
