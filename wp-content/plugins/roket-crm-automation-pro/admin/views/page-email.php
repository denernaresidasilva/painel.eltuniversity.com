<?php
/**
 * Email campaigns page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-email-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Mensagens de Email', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Visualizar mensagens de email enviadas e na fila.', 'roket-crm' ); ?></p>

        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="email-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Contato', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Para', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Assunto', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Enviado em', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="email-tbody">
                    <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test send -->
    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Enviar Email de Teste', 'roket-crm' ); ?></h3>
        <form id="test-email-form" class="wpla-form-grid">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Email de Destino', 'roket-crm' ); ?></label>
                <input type="email" id="test-email-to" class="wpla-input" placeholder="test@example.com" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Assunto', 'roket-crm' ); ?></label>
                <input type="text" id="test-email-subject" class="wpla-input" value="Test Email from Roket CRM" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Corpo (HTML)', 'roket-crm' ); ?></label>
                <textarea id="test-email-body" class="wpla-input" rows="4"><p>Hello {{first_name}}, this is a test email from <strong>Roket CRM</strong>.</p></textarea>
            </div>
            <div class="wpla-field">
                <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Enviar Teste', 'roket-crm' ); ?></button>
            </div>
        </form>
    </div>
</div>
