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

    <!-- Variables reference -->
    <div class="wpla-card wpla-mb-4">
        <h3>🔖 <?php esc_html_e( 'Variáveis Disponíveis', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Clique em uma variável para copiá-la e colá-la na sua mensagem.', 'roket-crm' ); ?></p>
        <div class="wpla-variables-grid wpla-mt-2">
            <?php
            $variables = array(
                '{nome}'            => __( 'Nome completo do contato', 'roket-crm' ),
                '{primeiro_nome}'   => __( 'Primeiro nome', 'roket-crm' ),
                '{sobrenome}'       => __( 'Sobrenome', 'roket-crm' ),
                '{email}'           => __( 'E-mail do contato', 'roket-crm' ),
                '{telefone}'        => __( 'Telefone do contato', 'roket-crm' ),
                '{empresa}'         => __( 'Empresa do contato', 'roket-crm' ),
                '{webinar_nome}'    => __( 'Nome do webinar', 'roket-crm' ),
                '{webinar_link}'    => __( 'Link do webinar', 'roket-crm' ),
                '{oferta_titulo}'   => __( 'Título da oferta do webinar', 'roket-crm' ),
                '{oferta_link}'     => __( 'Link da oferta do webinar', 'roket-crm' ),
                '{lista}'           => __( 'Nome da lista atual', 'roket-crm' ),
                '{utm_source}'      => __( 'UTM Source', 'roket-crm' ),
                '{utm_campaign}'    => __( 'UTM Campaign', 'roket-crm' ),
            );
            foreach ( $variables as $var => $label ) :
            ?>
            <div class="wpla-variable-chip" title="<?php echo esc_attr( $label ); ?>" onclick="WPLA.copyToClipboard('<?php echo esc_attr( $var ); ?>', this)">
                <code><?php echo esc_html( $var ); ?></code>
                <span class="wpla-variable-label"><?php echo esc_html( $label ); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Message queue -->
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
        <p class="wpla-text-muted wpla-mb-3"><?php esc_html_e( 'Use as variáveis acima no campo de mensagem (elas não serão substituídas no teste, pois não há um contato real).', 'roket-crm' ); ?></p>
        <form id="test-whatsapp-form" class="wpla-form-grid">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Número de Telefone', 'roket-crm' ); ?></label>
                <input type="text" id="test-wa-phone" class="wpla-input" placeholder="+5511999999999" />
            </div>
            <div class="wpla-field" style="grid-column: 1/-1;">
                <label><?php esc_html_e( 'Mensagem', 'roket-crm' ); ?></label>
                <textarea id="test-wa-message" class="wpla-input" rows="4" placeholder="<?php esc_attr_e( 'Olá {primeiro_nome}, seja bem-vindo ao {webinar_nome}!', 'roket-crm' ); ?>"></textarea>
            </div>
            <div class="wpla-field">
                <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Enviar Teste', 'roket-crm' ); ?></button>
            </div>
        </form>
    </div>
</div>

<style>
.wpla-variables-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}
.wpla-variable-chip {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    background: var(--wpla-surface, #f1f5f9);
    border: 1px solid var(--wpla-border, #e2e8f0);
    border-radius: 20px;
    cursor: pointer;
    transition: background .15s;
}
.wpla-variable-chip:hover { background: var(--wpla-primary-light, #e0e7ff); }
.wpla-variable-chip code { font-size: 12px; color: var(--wpla-primary, #6366f1); font-weight: 600; }
.wpla-variable-label { font-size: 11px; color: var(--wpla-muted, #94a3b8); }
</style>
