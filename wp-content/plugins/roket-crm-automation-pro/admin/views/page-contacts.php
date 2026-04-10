<?php
/**
 * Contacts page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-contacts-page">
    <!-- Toolbar -->
    <div class="wpla-toolbar">
        <div class="wpla-toolbar-left">
            <input type="text" id="contacts-search" class="wpla-input" placeholder="<?php esc_attr_e( 'Buscar contatos...', 'roket-crm' ); ?>" />
            <select id="contacts-status-filter" class="wpla-select">
                <option value=""><?php esc_html_e( 'Todos os Status', 'roket-crm' ); ?></option>
                <option value="active"><?php esc_html_e( 'Ativo', 'roket-crm' ); ?></option>
                <option value="unsubscribed"><?php esc_html_e( 'Desinscrito', 'roket-crm' ); ?></option>
                <option value="bounced"><?php esc_html_e( 'Rejeitado', 'roket-crm' ); ?></option>
            </select>
        </div>
        <div class="wpla-toolbar-right">
            <button class="wpla-btn wpla-btn-primary" id="btn-add-contact">+ <?php esc_html_e( 'Adicionar Contato', 'roket-crm' ); ?></button>
        </div>
    </div>

    <!-- Contacts table -->
    <div class="wpla-card">
        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="contacts-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Nome', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Email', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Telefone', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Pontuação', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Tags', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Criado em', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Ações', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="contacts-tbody">
                    <tr><td colspan="8" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
        <div class="wpla-pagination" id="contacts-pagination"></div>
    </div>
</div>

<!-- Contact Modal -->
<div class="wpla-modal" id="contact-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content">
        <div class="wpla-modal-header">
            <h3 id="contact-modal-title"><?php esc_html_e( 'Adicionar Contato', 'roket-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <form id="contact-form">
                <input type="hidden" name="id" id="contact-id" />
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Email', 'roket-crm' ); ?> *</label>
                        <input type="email" name="email" id="contact-email" class="wpla-input" required />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Nome', 'roket-crm' ); ?></label>
                        <input type="text" name="first_name" id="contact-fname" class="wpla-input" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Sobrenome', 'roket-crm' ); ?></label>
                        <input type="text" name="last_name" id="contact-lname" class="wpla-input" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Telefone', 'roket-crm' ); ?></label>
                        <input type="text" name="phone" id="contact-phone" class="wpla-input" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Empresa', 'roket-crm' ); ?></label>
                        <input type="text" name="company" id="contact-company" class="wpla-input" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Status', 'roket-crm' ); ?></label>
                        <select name="status" id="contact-status" class="wpla-select">
                            <option value="active"><?php esc_html_e( 'Ativo', 'roket-crm' ); ?></option>
                            <option value="unsubscribed"><?php esc_html_e( 'Desinscrito', 'roket-crm' ); ?></option>
                            <option value="bounced"><?php esc_html_e( 'Rejeitado', 'roket-crm' ); ?></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('contact-modal')"><?php esc_html_e( 'Cancelar', 'roket-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-save-contact"><?php esc_html_e( 'Salvar', 'roket-crm' ); ?></button>
        </div>
    </div>
</div>
