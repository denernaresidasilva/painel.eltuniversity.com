<?php
/**
 * WhatsApp messages page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-whatsapp-page">

    <!-- Variables reference -->
    <div class="pls-card pls-mb-4">
        <h3>🔖 <?php esc_html_e( 'Variáveis Disponíveis', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Clique em uma variável para copiá-la e colá-la na sua mensagem.', 'plugin-leads-saas' ); ?></p>
        <div class="pls-variables-grid pls-mt-2">
            <?php
            $variables = array(
                '{nome}'            => __( 'Nome completo do contato', 'plugin-leads-saas' ),
                '{primeiro_nome}'   => __( 'Primeiro nome', 'plugin-leads-saas' ),
                '{sobrenome}'       => __( 'Sobrenome', 'plugin-leads-saas' ),
                '{email}'           => __( 'E-mail do contato', 'plugin-leads-saas' ),
                '{telefone}'        => __( 'Telefone do contato', 'plugin-leads-saas' ),
                '{empresa}'         => __( 'Empresa do contato', 'plugin-leads-saas' ),
                '{webinar_nome}'    => __( 'Nome do webinar', 'plugin-leads-saas' ),
                '{webinar_link}'    => __( 'Link do webinar', 'plugin-leads-saas' ),
                '{oferta_titulo}'   => __( 'Título da oferta do webinar', 'plugin-leads-saas' ),
                '{oferta_link}'     => __( 'Link da oferta do webinar', 'plugin-leads-saas' ),
                '{lista}'           => __( 'Nome da lista atual', 'plugin-leads-saas' ),
                '{utm_source}'      => __( 'UTM Source', 'plugin-leads-saas' ),
                '{utm_campaign}'    => __( 'UTM Campaign', 'plugin-leads-saas' ),
            );
            foreach ( $variables as $var => $label ) :
            ?>
            <div class="pls-variable-chip" title="<?php echo esc_attr( $label ); ?>" onclick="PLS.copyToClipboard('<?php echo esc_attr( $var ); ?>', this)">
                <code><?php echo esc_html( $var ); ?></code>
                <span class="pls-variable-label"><?php echo esc_html( $label ); ?></span>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Message queue -->
    <div class="pls-card">
        <h3><?php esc_html_e( 'Mensagens WhatsApp', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Visualizar mensagens WhatsApp enviadas e na fila.', 'plugin-leads-saas' ); ?></p>

        <div class="pls-table-wrapper">
            <table class="pls-table" id="whatsapp-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Contato', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Destinatário', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Mensagem', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Data', 'plugin-leads-saas' ); ?></th>
                    </tr>
                </thead>
                <tbody id="whatsapp-tbody">
                    <tr><td colspan="5" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Test send -->
    <div class="pls-card pls-mt-4">
        <h3><?php esc_html_e( 'Enviar Mensagem de Teste', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted pls-mb-3"><?php esc_html_e( 'Use as variáveis acima no campo de mensagem (elas não serão substituídas no teste, pois não há um contato real).', 'plugin-leads-saas' ); ?></p>
        <form id="test-whatsapp-form" class="pls-form-grid">
            <div class="pls-field">
                <label><?php esc_html_e( 'Número de Telefone', 'plugin-leads-saas' ); ?></label>
                <input type="text" id="test-wa-phone" class="pls-input" placeholder="+5511999999999" />
            </div>
            <div class="pls-field" style="grid-column: 1/-1;">
                <label><?php esc_html_e( 'Mensagem', 'plugin-leads-saas' ); ?></label>
                <textarea id="test-wa-message" class="pls-input" rows="4" placeholder="<?php esc_attr_e( 'Olá {primeiro_nome}, seja bem-vindo ao {webinar_nome}!', 'plugin-leads-saas' ); ?>"></textarea>
            </div>
            <div class="pls-field">
                <button type="submit" class="pls-btn pls-btn-primary"><?php esc_html_e( 'Enviar Teste', 'plugin-leads-saas' ); ?></button>
            </div>
        </form>
    </div>
</div>
