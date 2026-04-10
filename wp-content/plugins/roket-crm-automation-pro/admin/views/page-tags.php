<?php
/**
 * Tags page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-tags-page">
    <div class="wpla-toolbar">
        <div class="wpla-toolbar-left">
            <h2><?php esc_html_e( 'Contact Tags', 'roket-crm' ); ?></h2>
        </div>
        <div class="wpla-toolbar-right">
            <button class="wpla-btn wpla-btn-primary" id="btn-add-tag">+ <?php esc_html_e( 'New Tag', 'roket-crm' ); ?></button>
        </div>
    </div>

    <div class="wpla-card">
        <div class="wpla-tags-grid" id="tags-grid">
            <p class="wpla-text-center"><?php esc_html_e( 'Loading...', 'roket-crm' ); ?></p>
        </div>
    </div>
</div>

<!-- Tag Modal -->
<div class="wpla-modal" id="tag-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content wpla-modal-sm">
        <div class="wpla-modal-header">
            <h3 id="tag-modal-title"><?php esc_html_e( 'New Tag', 'roket-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <form id="tag-form">
                <input type="hidden" name="id" id="tag-id" />
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Name', 'roket-crm' ); ?> *</label>
                    <input type="text" name="name" id="tag-name" class="wpla-input" required />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Color', 'roket-crm' ); ?></label>
                    <input type="color" name="color" id="tag-color" class="wpla-input-color" value="#6366f1" />
                </div>
            </form>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('tag-modal')"><?php esc_html_e( 'Cancel', 'roket-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-save-tag"><?php esc_html_e( 'Save', 'roket-crm' ); ?></button>
        </div>
    </div>
</div>
