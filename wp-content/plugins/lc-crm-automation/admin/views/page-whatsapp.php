<?php
/**
 * WhatsApp messages page view.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-whatsapp-page">

    <!-- Variables reference -->
    <div class="wpla-card wpla-mb-4">
        <h3>🔖 <?php esc_html_e( 'Variáveis Disponíveis', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Clique em uma variável para copiá-la e colá-la na sua mensagem.', 'lc-crm' ); ?></p>
        <div class="wpla-variables-grid wpla-mt-2">
            <?php
            $variables = array(
                '{nome}'            => __( 'Nome completo do contato', 'lc-crm' ),
                '{primeiro_nome}'   => __( 'Primeiro nome', 'lc-crm' ),
                '{sobrenome}'       => __( 'Sobrenome', 'lc-crm' ),
                '{email}'           => __( 'E-mail do contato', 'lc-crm' ),
                '{telefone}'        => __( 'Telefone do contato', 'lc-crm' ),
                '{empresa}'         => __( 'Empresa do contato', 'lc-crm' ),
                '{webinar_nome}'    => __( 'Nome do webinar', 'lc-crm' ),
                '{webinar_link}'    => __( 'Link do webinar', 'lc-crm' ),
                '{oferta_titulo}'   => __( 'Título da oferta do webinar', 'lc-crm' ),
                '{oferta_link}'     => __( 'Link da oferta do webinar', 'lc-crm' ),
                '{lista}'           => __( 'Nome da lista atual', 'lc-crm' ),
                '{utm_source}'      => __( 'UTM Source', 'lc-crm' ),
                '{utm_campaign}'    => __( 'UTM Campaign', 'lc-crm' ),
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
        <h3><?php esc_html_e( 'Mensagens WhatsApp', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Visualizar mensagens WhatsApp enviadas e na fila.', 'lc-crm' ); ?></p>

        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="whatsapp-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Contato', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Destinatário', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Mensagem', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Data', 'lc-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="whatsapp-tbody">
                    <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test send -->
    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Enviar Mensagem de Teste', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted wpla-mb-3"><?php esc_html_e( 'Use as variáveis acima no campo de mensagem (elas não serão substituídas no teste, pois não há um contato real).', 'lc-crm' ); ?></p>
        <form id="test-whatsapp-form" class="wpla-form-grid">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Número de Telefone', 'lc-crm' ); ?></label>
                <input type="text" id="test-wa-phone" class="wpla-input" placeholder="+5511999999999" />
            </div>
            <div class="wpla-field" style="grid-column: 1/-1;">
                <label><?php esc_html_e( 'Mensagem', 'lc-crm' ); ?></label>
                <textarea id="test-wa-message" class="wpla-input" rows="4" placeholder="<?php esc_attr_e( 'Olá {primeiro_nome}, seja bem-vindo ao {webinar_nome}!', 'lc-crm' ); ?>"></textarea>
            </div>
            <div class="wpla-field">
                <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Enviar Teste', 'lc-crm' ); ?></button>
            </div>
        </form>
    </div>
</div>
