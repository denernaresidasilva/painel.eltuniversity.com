<?php
/**
 * Automations page view — includes flow builder UI.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-automations-page">
    <!-- List view -->
    <div id="automations-list-view">
        <div class="wpla-toolbar">
            <div class="wpla-toolbar-left">
                <h2><?php esc_html_e( 'Automações', 'roket-crm' ); ?></h2>
            </div>
            <div class="wpla-toolbar-right">
                <button class="wpla-btn wpla-btn-primary" id="btn-add-automation">+ <?php esc_html_e( 'Nova Automação', 'roket-crm' ); ?></button>
            </div>
        </div>

        <div class="wpla-card">
            <div class="wpla-table-wrapper">
                <table class="wpla-table" id="automations-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'roket-crm' ); ?></th>
                            <th><?php esc_html_e( 'Gatilho', 'roket-crm' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'roket-crm' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'roket-crm' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'roket-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="automations-tbody">
                        <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'roket-crm' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Flow Builder view -->
    <div id="automation-builder" style="display:none;">
        <div class="wpla-toolbar">
            <div class="wpla-toolbar-left">
                <button class="wpla-btn" id="btn-back-automations">← <?php esc_html_e( 'Voltar', 'roket-crm' ); ?></button>
                <input type="text" id="automation-name" class="wpla-input wpla-input-lg" placeholder="<?php esc_attr_e( 'Nome da automação...', 'roket-crm' ); ?>" />
            </div>
            <div class="wpla-toolbar-right">
                <select id="automation-status" class="wpla-select">
                    <option value="draft"><?php esc_html_e( 'Rascunho', 'roket-crm' ); ?></option>
                    <option value="active"><?php esc_html_e( 'Ativo', 'roket-crm' ); ?></option>
                    <option value="paused"><?php esc_html_e( 'Pausado', 'roket-crm' ); ?></option>
                </select>
                <button class="wpla-btn wpla-btn-primary" id="btn-save-automation"><?php esc_html_e( 'Salvar', 'roket-crm' ); ?></button>
            </div>
        </div>

        <!-- Trigger selection -->
        <div class="wpla-card wpla-mb-4">
            <h4><?php esc_html_e( 'Gatilho', 'roket-crm' ); ?></h4>
            <select id="automation-trigger" class="wpla-select">
                <option value="contact_created"><?php esc_html_e( 'Contato Criado', 'roket-crm' ); ?></option>
                <option value="contact_updated"><?php esc_html_e( 'Contato Atualizado', 'roket-crm' ); ?></option>
                <option value="tag_added"><?php esc_html_e( 'Tag Adicionada', 'roket-crm' ); ?></option>
                <option value="tag_removed"><?php esc_html_e( 'Tag Removida', 'roket-crm' ); ?></option>
                <option value="list_subscribed"><?php esc_html_e( 'Inscrito na Lista', 'roket-crm' ); ?></option>
                <option value="form_submitted"><?php esc_html_e( 'Formulário Enviado', 'roket-crm' ); ?></option>
                <option value="webhook_received"><?php esc_html_e( 'Webhook Recebido', 'roket-crm' ); ?></option>
                <option value="email_opened"><?php esc_html_e( 'Email Aberto', 'roket-crm' ); ?></option>
                <option value="link_clicked"><?php esc_html_e( 'Link Clicado', 'roket-crm' ); ?></option>
            </select>
        </div>

        <!-- Flow canvas -->
        <div class="wpla-flow-canvas" id="flow-canvas">
            <div class="wpla-flow-nodes" id="flow-nodes">
                <!-- Nodes rendered by JS -->
            </div>
            <div class="wpla-flow-add">
                <button class="wpla-btn wpla-btn-dashed" id="btn-add-step">+ <?php esc_html_e( 'Adicionar Etapa', 'roket-crm' ); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Step Modal -->
<div class="wpla-modal" id="step-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content">
        <div class="wpla-modal-header">
            <h3><?php esc_html_e( 'Adicionar Etapa', 'roket-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Tipo de Etapa', 'roket-crm' ); ?></label>
                <select id="step-type" class="wpla-select">
                    <option value="condition"><?php esc_html_e( '🔀 Condição (SE/SENÃO)', 'roket-crm' ); ?></option>
                    <option value="action"><?php esc_html_e( '⚡ Ação', 'roket-crm' ); ?></option>
                    <option value="delay"><?php esc_html_e( '⏱ Atraso', 'roket-crm' ); ?></option>
                </select>
            </div>

            <!-- Condition fields -->
            <div id="step-condition-fields" style="display:none;">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Tipo de Condição', 'roket-crm' ); ?></label>
                    <select id="condition-type" class="wpla-select">
                        <option value="has_tag"><?php esc_html_e( 'Possui Tag', 'roket-crm' ); ?></option>
                        <option value="in_list"><?php esc_html_e( 'Na Lista', 'roket-crm' ); ?></option>
                        <option value="score_above"><?php esc_html_e( 'Pontuação Acima de', 'roket-crm' ); ?></option>
                        <option value="field_equals"><?php esc_html_e( 'Campo Igual a', 'roket-crm' ); ?></option>
                    </select>
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Valor', 'roket-crm' ); ?></label>
                    <input type="text" id="condition-value" class="wpla-input" />
                </div>
            </div>

            <!-- Action fields -->
            <div id="step-action-fields" style="display:none;">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Tipo de Ação', 'roket-crm' ); ?></label>
                    <select id="action-type" class="wpla-select">
                        <option value="add_tag"><?php esc_html_e( 'Adicionar Tag', 'roket-crm' ); ?></option>
                        <option value="remove_tag"><?php esc_html_e( 'Remover Tag', 'roket-crm' ); ?></option>
                        <option value="subscribe_list"><?php esc_html_e( 'Inscrever na Lista', 'roket-crm' ); ?></option>
                        <option value="send_email"><?php esc_html_e( 'Enviar Email', 'roket-crm' ); ?></option>
                        <option value="send_whatsapp"><?php esc_html_e( 'Enviar WhatsApp', 'roket-crm' ); ?></option>
                        <option value="update_field"><?php esc_html_e( 'Atualizar Campo', 'roket-crm' ); ?></option>
                        <option value="update_score"><?php esc_html_e( 'Atualizar Pontuação', 'roket-crm' ); ?></option>
                        <option value="webhook"><?php esc_html_e( 'Enviar Webhook', 'roket-crm' ); ?></option>
                    </select>
                </div>
                <div id="action-config-fields"></div>
            </div>

            <!-- Delay fields -->
            <div id="step-delay-fields" style="display:none;">
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Quantidade', 'roket-crm' ); ?></label>
                        <input type="number" id="delay-amount" class="wpla-input" value="1" min="1" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Unidade', 'roket-crm' ); ?></label>
                        <select id="delay-unit" class="wpla-select">
                            <option value="minutes"><?php esc_html_e( 'Minutos', 'roket-crm' ); ?></option>
                            <option value="hours"><?php esc_html_e( 'Horas', 'roket-crm' ); ?></option>
                            <option value="days"><?php esc_html_e( 'Dias', 'roket-crm' ); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('step-modal')"><?php esc_html_e( 'Cancelar', 'roket-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-confirm-step"><?php esc_html_e( 'Adicionar Etapa', 'roket-crm' ); ?></button>
        </div>
    </div>
</div>
