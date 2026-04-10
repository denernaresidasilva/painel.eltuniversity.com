<?php
/**
 * Email campaigns page view.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-email-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Mensagens de Email', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Visualizar mensagens de email enviadas e na fila.', 'lc-crm' ); ?></p>

        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="email-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Contato', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Para', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Assunto', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Enviado em', 'lc-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="email-tbody">
                    <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test send -->
    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Enviar Email de Teste', 'lc-crm' ); ?></h3>
        <form id="test-email-form" class="wpla-form-grid">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Email de Destino', 'lc-crm' ); ?></label>
                <input type="email" id="test-email-to" class="wpla-input" placeholder="test@example.com" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Assunto', 'lc-crm' ); ?></label>
                <input type="text" id="test-email-subject" class="wpla-input" value="Test Email from LC CRM" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Corpo (HTML)', 'lc-crm' ); ?></label>
                <textarea id="test-email-body" class="wpla-input" rows="4"><p>Hello {{first_name}}, this is a test email from <strong>LC CRM</strong>.</p></textarea>
            </div>
            <div class="wpla-field">
                <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Enviar Teste', 'lc-crm' ); ?></button>
            </div>
        </form>
    </div>
</div>
