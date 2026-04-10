<?php
/**
 * WhatsApp messages page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-whatsapp-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'WhatsApp Messages', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'View sent and queued WhatsApp messages.', 'roket-crm' ); ?></p>

        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="whatsapp-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Contact', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Recipient', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Message', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="whatsapp-tbody">
                    <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Loading...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test send -->
    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Send Test Message', 'roket-crm' ); ?></h3>
        <form id="test-whatsapp-form" class="wpla-form-grid">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Phone Number', 'roket-crm' ); ?></label>
                <input type="text" id="test-wa-phone" class="wpla-input" placeholder="+5511999999999" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Message', 'roket-crm' ); ?></label>
                <textarea id="test-wa-message" class="wpla-input" rows="3" placeholder="<?php esc_attr_e( 'Hello from Roket CRM!', 'roket-crm' ); ?>"></textarea>
            </div>
            <div class="wpla-field">
                <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Send Test', 'roket-crm' ); ?></button>
            </div>
        </form>
    </div>
</div>
