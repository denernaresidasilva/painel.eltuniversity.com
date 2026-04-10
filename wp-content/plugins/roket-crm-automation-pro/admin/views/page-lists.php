<?php
/**
 * Lists page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-lists-page">
    <div class="wpla-toolbar">
        <div class="wpla-toolbar-left">
            <h2><?php esc_html_e( 'Mailing Lists', 'roket-crm' ); ?></h2>
        </div>
        <div class="wpla-toolbar-right">
            <button class="wpla-btn wpla-btn-primary" id="btn-add-list">+ <?php esc_html_e( 'New List', 'roket-crm' ); ?></button>
        </div>
    </div>

    <div class="wpla-card">
        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="lists-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Name', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Description', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Subscribers', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Shortcode', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Created', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Actions', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="lists-tbody">
                    <tr><td colspan="6" class="wpla-text-center"><?php esc_html_e( 'Loading...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- List Modal -->
<div class="wpla-modal" id="list-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content">
        <div class="wpla-modal-header">
            <h3 id="list-modal-title"><?php esc_html_e( 'New List', 'roket-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <form id="list-form">
                <input type="hidden" name="id" id="list-id" />
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Name', 'roket-crm' ); ?> *</label>
                    <input type="text" name="name" id="list-name" class="wpla-input" required />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Description', 'roket-crm' ); ?></label>
                    <textarea name="description" id="list-description" class="wpla-input" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('list-modal')"><?php esc_html_e( 'Cancel', 'roket-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-save-list"><?php esc_html_e( 'Save', 'roket-crm' ); ?></button>
        </div>
    </div>
</div>
