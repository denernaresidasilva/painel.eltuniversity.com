<?php
/**
 * Tags page view.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-tags-page">
    <div class="wpla-toolbar">
        <div class="wpla-toolbar-left">
            <h2><?php esc_html_e( 'Tags de Contato', 'lc-crm' ); ?></h2>
        </div>
        <div class="wpla-toolbar-right">
            <button class="wpla-btn wpla-btn-primary" id="btn-add-tag">+ <?php esc_html_e( 'Nova Tag', 'lc-crm' ); ?></button>
        </div>
    </div>

    <div class="wpla-card">
        <div class="wpla-tags-grid" id="tags-grid">
            <p class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></p>
        </div>
    </div>
</div>

<!-- Tag Modal -->
<div class="wpla-modal" id="tag-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content wpla-modal-sm">
        <div class="wpla-modal-header">
            <h3 id="tag-modal-title"><?php esc_html_e( 'Nova Tag', 'lc-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <form id="tag-form">
                <input type="hidden" name="id" id="tag-id" />
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Nome', 'lc-crm' ); ?> *</label>
                    <input type="text" name="name" id="tag-name" class="wpla-input" required />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Cor', 'lc-crm' ); ?></label>
                    <input type="color" name="color" id="tag-color" class="wpla-input-color" value="#6366f1" />
                </div>
            </form>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('tag-modal')"><?php esc_html_e( 'Cancelar', 'lc-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-save-tag"><?php esc_html_e( 'Salvar', 'lc-crm' ); ?></button>
        </div>
    </div>
</div>
