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
        <h3><?php esc_html_e( 'Mensagens WhatsApp', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Visualizar mensagens WhatsApp enviadas e na fila.', 'roket-crm' ); ?></p>

        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="whatsapp-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Contato', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Destinatário', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Mensagem', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Data', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="whatsapp-tbody">
                    <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test send -->
    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Enviar Mensagem de Teste', 'roket-crm' ); ?></h3>
        <form id="test-whatsapp-form" class="wpla-form-grid">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Número de Telefone', 'roket-crm' ); ?></label>
                <input type="text" id="test-wa-phone" class="wpla-input" placeholder="+5511999999999" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Mensagem', 'roket-crm' ); ?></label>
                <textarea id="test-wa-message" class="wpla-input" rows="3" placeholder="<?php esc_attr_e( 'Olá do Roket CRM!', 'roket-crm' ); ?>"></textarea>
            </div>
            <div class="wpla-field">
                <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Enviar Teste', 'roket-crm' ); ?></button>
            </div>
        </form>
    </div>
</div>
