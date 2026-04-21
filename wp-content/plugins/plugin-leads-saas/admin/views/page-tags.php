<?php
/**
 * Tags page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-tags-page">
    <div class="pls-toolbar">
        <div class="pls-toolbar-left">
            <h2><?php esc_html_e( 'Tags de Contato', 'plugin-leads-saas' ); ?></h2>
        </div>
        <div class="pls-toolbar-right">
            <button class="pls-btn pls-btn-primary" id="btn-add-tag">+ <?php esc_html_e( 'Nova Tag', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>

    <div class="pls-card">
        <div class="pls-tags-grid" id="tags-grid">
            <p class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></p>
        </div>
    </div>
</div>

<!-- Tag Modal -->
<div class="pls-modal" id="tag-modal" style="display:none;">
    <div class="pls-modal-overlay"></div>
    <div class="pls-modal-content pls-modal-sm">
        <div class="pls-modal-header">
            <h3 id="tag-modal-title"><?php esc_html_e( 'Nova Tag', 'plugin-leads-saas' ); ?></h3>
            <button class="pls-modal-close">&times;</button>
        </div>
        <div class="pls-modal-body">
            <form id="tag-form">
                <input type="hidden" name="id" id="tag-id" />
                <div class="pls-field">
                    <label><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?> *</label>
                    <input type="text" name="name" id="tag-name" class="pls-input" required />
                </div>
                <div class="pls-field">
                    <label><?php esc_html_e( 'Cor', 'plugin-leads-saas' ); ?></label>
                    <input type="color" name="color" id="tag-color" class="pls-input-color" value="#6366f1" />
                </div>
            </form>
        </div>
        <div class="pls-modal-footer">
            <button class="pls-btn" onclick="PLS.closeModal('tag-modal')"><?php esc_html_e( 'Cancelar', 'plugin-leads-saas' ); ?></button>
            <button class="pls-btn pls-btn-primary" id="btn-save-tag"><?php esc_html_e( 'Salvar', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>
</div>
