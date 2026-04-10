<?php
/**
 * Lists page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$webhook_base_url = rest_url( 'wpla/v1/webhook' );
?>
<div class="wpla-lists-page">
    <div class="wpla-toolbar">
        <div class="wpla-toolbar-left">
            <h2><?php esc_html_e( 'Listas de Envio', 'roket-crm' ); ?></h2>
        </div>
        <div class="wpla-toolbar-right">
            <button class="wpla-btn wpla-btn-primary" id="btn-add-list">+ <?php esc_html_e( 'Nova Lista', 'roket-crm' ); ?></button>
        </div>
    </div>

    <div class="wpla-card wpla-mb-4">
        <h3><?php esc_html_e( 'Barra de Integração da Lista', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Selecione a lista para gerar o webhook pronto para Eduzz/Hotmart (com verificação de URL) e o shortcode configurado para Elementor.', 'roket-crm' ); ?></p>

        <div class="wpla-form-grid wpla-lists-integration-grid">
            <div class="wpla-field">
                <label for="list-tools-select"><?php esc_html_e( 'Lista', 'roket-crm' ); ?></label>
                <select id="list-tools-select" class="wpla-select">
                    <option value=""><?php esc_html_e( 'Selecione uma lista', 'roket-crm' ); ?></option>
                </select>
            </div>

            <div class="wpla-field">
                <label for="list-form-title"><?php esc_html_e( 'Título do Formulário', 'roket-crm' ); ?></label>
                <input type="text" id="list-form-title" class="wpla-input" value="Entrar na Lista" />
            </div>

            <div class="wpla-field">
                <label for="list-form-button"><?php esc_html_e( 'Texto do Botão', 'roket-crm' ); ?></label>
                <input type="text" id="list-form-button" class="wpla-input" value="Quero me cadastrar" />
            </div>

            <div class="wpla-field">
                <label for="list-form-class"><?php esc_html_e( 'Classe CSS (opcional)', 'roket-crm' ); ?></label>
                <input type="text" id="list-form-class" class="wpla-input" value="" placeholder="minha-classe-form" />
            </div>
        </div>

        <div class="wpla-field">
            <label><?php esc_html_e( 'Webhook de Recebimento (Eduzz/Hotmart)', 'roket-crm' ); ?></label>
            <div class="wpla-input-group">
                <input type="text" id="list-webhook-url" class="wpla-input" value="<?php echo esc_attr( $webhook_base_url ); ?>" readonly />
                <button class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('list-webhook-url').value)"><?php esc_html_e( 'Copiar', 'roket-crm' ); ?></button>
            </div>
            <p class="wpla-text-muted"><?php esc_html_e( 'Este endpoint já responde validação GET/HEAD e também recebe POST com lead. Use o parâmetro list_id para inscrição automática na lista escolhida.', 'roket-crm' ); ?></p>
        </div>

        <div class="wpla-field">
            <label><?php esc_html_e( 'Shortcode para Elementor', 'roket-crm' ); ?></label>
            <div class="wpla-input-group">
                <input type="text" id="list-form-shortcode" class="wpla-input" value="[wpla_form list=&quot;0&quot;]" readonly />
                <button class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('list-form-shortcode').value)"><?php esc_html_e( 'Copiar', 'roket-crm' ); ?></button>
            </div>
        </div>
    </div>

    <div class="wpla-card">
        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="lists-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Nome', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Descrição', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Inscritos', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Shortcode', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Criado em', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Ações', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="lists-tbody">
                    <tr><td colspan="6" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- List Modal -->
<div class="wpla-modal" id="list-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content">
        <div class="wpla-modal-header">
            <h3 id="list-modal-title"><?php esc_html_e( 'Nova Lista', 'roket-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <form id="list-form">
                <input type="hidden" name="id" id="list-id" />
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Nome', 'roket-crm' ); ?> *</label>
                    <input type="text" name="name" id="list-name" class="wpla-input" required />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Descrição', 'roket-crm' ); ?></label>
                    <textarea name="description" id="list-description" class="wpla-input" rows="3"></textarea>
                </div>
            </form>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('list-modal')"><?php esc_html_e( 'Cancelar', 'roket-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-save-list"><?php esc_html_e( 'Salvar', 'roket-crm' ); ?></button>
        </div>
    </div>
</div>
