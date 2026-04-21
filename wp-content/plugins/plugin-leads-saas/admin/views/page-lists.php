<?php
/**
 * Lists page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$webhook_base_url = rest_url( 'pls/v1/webhook' );
?>
<div class="pls-lists-page">
    <div class="pls-toolbar">
        <div class="pls-toolbar-left">
            <h2><?php esc_html_e( 'Listas de Envio', 'plugin-leads-saas' ); ?></h2>
        </div>
        <div class="pls-toolbar-right">
            <button class="pls-btn pls-btn-primary" id="btn-add-list">+ <?php esc_html_e( 'Nova Lista', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>

    <div class="pls-card pls-mb-4">
        <h3><?php esc_html_e( 'Barra de Integração da Lista', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Selecione a lista para gerar o webhook pronto para Eduzz/Hotmart (com verificação de URL) e o shortcode configurado para Elementor.', 'plugin-leads-saas' ); ?></p>

        <div class="pls-form-grid pls-lists-integration-grid">
            <div class="pls-field">
                <label for="list-tools-select"><?php esc_html_e( 'Lista', 'plugin-leads-saas' ); ?></label>
                <select id="list-tools-select" class="pls-select">
                    <option value=""><?php esc_html_e( 'Selecione uma lista', 'plugin-leads-saas' ); ?></option>
                </select>
            </div>

            <div class="pls-field">
                <label for="list-form-title"><?php esc_html_e( 'Título do Formulário', 'plugin-leads-saas' ); ?></label>
                <input type="text" id="list-form-title" class="pls-input" value="Entrar na Lista" />
            </div>

            <div class="pls-field">
                <label for="list-form-button"><?php esc_html_e( 'Texto do Botão', 'plugin-leads-saas' ); ?></label>
                <input type="text" id="list-form-button" class="pls-input" value="Quero me cadastrar" />
            </div>

            <div class="pls-field">
                <label for="list-form-class"><?php esc_html_e( 'Classe CSS (opcional)', 'plugin-leads-saas' ); ?></label>
                <input type="text" id="list-form-class" class="pls-input" value="" placeholder="minha-classe-form" />
            </div>
        </div>

        <div class="pls-field">
            <label><?php esc_html_e( 'Webhook de Recebimento (Eduzz/Hotmart)', 'plugin-leads-saas' ); ?></label>
            <div class="pls-input-group">
                <input type="text" id="list-webhook-url" class="pls-input" value="<?php echo esc_attr( $webhook_base_url ); ?>" readonly />
                <button class="pls-btn" onclick="PLS.copyToClipboard(document.getElementById('list-webhook-url').value)"><?php esc_html_e( 'Copiar', 'plugin-leads-saas' ); ?></button>
            </div>
            <p class="pls-text-muted"><?php esc_html_e( 'Este endpoint já responde validação GET/HEAD e também recebe POST com lead. Use o parâmetro list_id para inscrição automática na lista escolhida.', 'plugin-leads-saas' ); ?></p>
        </div>

        <div class="pls-field">
            <label><?php esc_html_e( 'Shortcode para Elementor', 'plugin-leads-saas' ); ?></label>
            <div class="pls-input-group">
                <input type="text" id="list-form-shortcode" class="pls-input" value="[pls_form list=&quot;0&quot;]" readonly />
                <button class="pls-btn" onclick="PLS.copyToClipboard(document.getElementById('list-form-shortcode').value)"><?php esc_html_e( 'Copiar', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>
    </div>

    <div class="pls-card">
        <div class="pls-table-wrapper">
            <table class="pls-table" id="lists-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Descrição', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Inscritos', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Shortcode', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Criado em', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Ações', 'plugin-leads-saas' ); ?></th>
                    </tr>
                </thead>
                <tbody id="lists-tbody">
                    <tr><td colspan="6" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- List Modal -->
<div class="pls-modal" id="list-modal" style="display:none;">
    <div class="pls-modal-overlay"></div>
    <div class="pls-modal-content">
        <div class="pls-modal-header">
            <h3 id="list-modal-title"><?php esc_html_e( 'Nova Lista', 'plugin-leads-saas' ); ?></h3>
            <button class="pls-modal-close">&times;</button>
        </div>
        <div class="pls-modal-body">
            <form id="list-form">
                <input type="hidden" name="id" id="list-id" />
                <div class="pls-field">
                    <label><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?> *</label>
                    <input type="text" name="name" id="list-name" class="pls-input" required />
                </div>
                <div class="pls-field">
                    <label><?php esc_html_e( 'Descrição', 'plugin-leads-saas' ); ?></label>
                    <textarea name="description" id="list-description" class="pls-input" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="pls-modal-footer">
            <button class="pls-btn" onclick="PLS.closeModal('list-modal')"><?php esc_html_e( 'Cancelar', 'plugin-leads-saas' ); ?></button>
            <button class="pls-btn pls-btn-primary" id="btn-save-list"><?php esc_html_e( 'Salvar', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>
</div>
