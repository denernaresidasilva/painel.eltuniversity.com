<?php
/**
 * Contacts page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-contacts-page">
    <!-- Toolbar -->
    <div class="pls-toolbar">
        <div class="pls-toolbar-left">
            <input type="text" id="contacts-search" class="pls-input" placeholder="<?php esc_attr_e( 'Buscar contatos...', 'plugin-leads-saas' ); ?>" />
            <select id="contacts-status-filter" class="pls-select">
                <option value=""><?php esc_html_e( 'Todos os Status', 'plugin-leads-saas' ); ?></option>
                <option value="active"><?php esc_html_e( 'Ativo', 'plugin-leads-saas' ); ?></option>
                <option value="unsubscribed"><?php esc_html_e( 'Desinscrito', 'plugin-leads-saas' ); ?></option>
                <option value="bounced"><?php esc_html_e( 'Rejeitado', 'plugin-leads-saas' ); ?></option>
            </select>
        </div>
        <div class="pls-toolbar-right">
            <button class="pls-btn pls-btn-primary" id="btn-add-contact">+ <?php esc_html_e( 'Adicionar Contato', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>

    <!-- Contacts table -->
    <div class="pls-card">
        <div class="pls-table-wrapper">
            <table class="pls-table" id="contacts-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Email', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Telefone', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Pontuação', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Tags', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Criado em', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Ações', 'plugin-leads-saas' ); ?></th>
                    </tr>
                </thead>
                <tbody id="contacts-tbody">
                    <tr><td colspan="8" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                </tbody>
            </table>
        </div>
        <div class="pls-pagination" id="contacts-pagination"></div>
    </div>
</div>

<!-- Contact Modal -->
<div class="pls-modal" id="contact-modal" style="display:none;">
    <div class="pls-modal-overlay"></div>
    <div class="pls-modal-content">
        <div class="pls-modal-header">
            <h3 id="contact-modal-title"><?php esc_html_e( 'Adicionar Contato', 'plugin-leads-saas' ); ?></h3>
            <button class="pls-modal-close">&times;</button>
        </div>
        <div class="pls-modal-body">
            <form id="contact-form">
                <input type="hidden" name="id" id="contact-id" />
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Email', 'plugin-leads-saas' ); ?> *</label>
                        <input type="email" name="email" id="contact-email" class="pls-input" required />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="first_name" id="contact-fname" class="pls-input" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Sobrenome', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="last_name" id="contact-lname" class="pls-input" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Telefone', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="phone" id="contact-phone" class="pls-input" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Empresa', 'plugin-leads-saas' ); ?></label>
                        <input type="text" name="company" id="contact-company" class="pls-input" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></label>
                        <select name="status" id="contact-status" class="pls-select">
                            <option value="active"><?php esc_html_e( 'Ativo', 'plugin-leads-saas' ); ?></option>
                            <option value="unsubscribed"><?php esc_html_e( 'Desinscrito', 'plugin-leads-saas' ); ?></option>
                            <option value="bounced"><?php esc_html_e( 'Rejeitado', 'plugin-leads-saas' ); ?></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="pls-modal-footer">
            <button class="pls-btn" onclick="PLS.closeModal('contact-modal')"><?php esc_html_e( 'Cancelar', 'plugin-leads-saas' ); ?></button>
            <button class="pls-btn pls-btn-primary" id="btn-save-contact"><?php esc_html_e( 'Salvar', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>
</div>
