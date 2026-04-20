<?php
/**
 * Automations page view — includes flow builder UI.
 *
 * @package LC_CRM_Automation
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
                <h2><?php esc_html_e( 'Automações', 'lc-crm' ); ?></h2>
            </div>
            <div class="wpla-toolbar-right">
                <button class="wpla-btn wpla-btn-primary" id="btn-add-automation">+ <?php esc_html_e( 'Nova Automação', 'lc-crm' ); ?></button>
            </div>
        </div>

        <div class="wpla-card">
            <div class="wpla-table-wrapper">
                <table class="wpla-table" id="automations-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Gatilho', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'lc-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="automations-tbody">
                        <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Flow Builder view -->
    <div id="automation-builder" style="display:none;">
        <div class="wpla-toolbar">
            <div class="wpla-toolbar-left">
                <button class="wpla-btn" id="btn-back-automations">← <?php esc_html_e( 'Voltar', 'lc-crm' ); ?></button>
                <input type="text" id="automation-name" class="wpla-input wpla-input-lg" placeholder="<?php esc_attr_e( 'Nome da automação...', 'lc-crm' ); ?>" />
            </div>
            <div class="wpla-toolbar-right">
                <select id="automation-status" class="wpla-select">
                    <option value="draft"><?php esc_html_e( 'Rascunho', 'lc-crm' ); ?></option>
                    <option value="active"><?php esc_html_e( 'Ativo', 'lc-crm' ); ?></option>
                    <option value="paused"><?php esc_html_e( 'Pausado', 'lc-crm' ); ?></option>
                </select>
                <button class="wpla-btn wpla-btn-primary" id="btn-save-automation"><?php esc_html_e( 'Salvar', 'lc-crm' ); ?></button>
            </div>
        </div>

        <!-- Trigger selection -->
        <div class="wpla-card wpla-mb-4">
            <h4><?php esc_html_e( 'Gatilho', 'lc-crm' ); ?></h4>
            <select id="automation-trigger" class="wpla-select">
                <optgroup label="<?php esc_attr_e( 'Contatos', 'lc-crm' ); ?>">
                    <option value="contact_created"><?php esc_html_e( 'Contato Criado', 'lc-crm' ); ?></option>
                    <option value="contact_updated"><?php esc_html_e( 'Contato Atualizado', 'lc-crm' ); ?></option>
                    <option value="tag_added"><?php esc_html_e( 'Tag Adicionada', 'lc-crm' ); ?></option>
                    <option value="tag_removed"><?php esc_html_e( 'Tag Removida', 'lc-crm' ); ?></option>
                    <option value="list_subscribed"><?php esc_html_e( 'Inscrito na Lista', 'lc-crm' ); ?></option>
                    <option value="form_submitted"><?php esc_html_e( 'Formulário Enviado', 'lc-crm' ); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e( 'Webinar', 'lc-crm' ); ?>">
                    <option value="webinar_inbox"><?php esc_html_e( '[Webinar] Entrou na INBOX', 'lc-crm' ); ?></option>
                    <option value="webinar_assistiu_oferta"><?php esc_html_e( '[Webinar] Assistiu a Oferta', 'lc-crm' ); ?></option>
                    <option value="webinar_nao_viu_oferta"><?php esc_html_e( '[Webinar] Não Viu a Oferta', 'lc-crm' ); ?></option>
                    <option value="webinar_replay"><?php esc_html_e( '[Webinar] Encaminhado para Replay', 'lc-crm' ); ?></option>
                    <option value="webinar_converteu"><?php esc_html_e( '[Webinar] Converteu (clicou oferta)', 'lc-crm' ); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e( 'Email', 'lc-crm' ); ?>">
                    <option value="email_opened"><?php esc_html_e( 'Email Aberto', 'lc-crm' ); ?></option>
                    <option value="link_clicked"><?php esc_html_e( 'Link Clicado', 'lc-crm' ); ?></option>
                    <option value="email_unsubscribed"><?php esc_html_e( 'Descadastrado', 'lc-crm' ); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e( 'Externo', 'lc-crm' ); ?>">
                    <option value="webhook_received"><?php esc_html_e( 'Webhook Recebido', 'lc-crm' ); ?></option>
                </optgroup>
            </select>
        </div>

        <!-- Flow canvas -->
        <div class="wpla-flow-canvas" id="flow-canvas">
            <div class="wpla-flow-nodes" id="flow-nodes">
                <!-- Nodes rendered by JS -->
            </div>
            <div class="wpla-flow-add">
                <button class="wpla-btn wpla-btn-dashed" id="btn-add-step">+ <?php esc_html_e( 'Adicionar Etapa', 'lc-crm' ); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Step Modal -->
<div class="wpla-modal" id="step-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content">
        <div class="wpla-modal-header">
            <h3><?php esc_html_e( 'Adicionar Etapa', 'lc-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <div class="wpla-field">
                <label><?php esc_html_e( 'Tipo de Etapa', 'lc-crm' ); ?></label>
                <select id="step-type" class="wpla-select">
                    <option value="condition"><?php esc_html_e( '🔀 Condição (SE/SENÃO)', 'lc-crm' ); ?></option>
                    <option value="action"><?php esc_html_e( '⚡ Ação', 'lc-crm' ); ?></option>
                    <option value="delay"><?php esc_html_e( '⏱ Atraso', 'lc-crm' ); ?></option>
                </select>
            </div>

            <!-- Condition fields -->
            <div id="step-condition-fields" style="display:none;">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Tipo de Condição', 'lc-crm' ); ?></label>
                    <select id="condition-type" class="wpla-select">
                        <optgroup label="<?php esc_attr_e( 'Tags', 'lc-crm' ); ?>">
                            <option value="has_tag"><?php esc_html_e( 'Possui Tag', 'lc-crm' ); ?></option>
                            <option value="contains_tag"><?php esc_html_e( 'Contém Tag', 'lc-crm' ); ?></option>
                            <option value="received_tag"><?php esc_html_e( 'Recebeu Tag', 'lc-crm' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Listas', 'lc-crm' ); ?>">
                            <option value="in_list"><?php esc_html_e( 'Na Lista', 'lc-crm' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Email', 'lc-crm' ); ?>">
                            <option value="email_opened"><?php esc_html_e( 'Email Aberto (nas últimas X horas)', 'lc-crm' ); ?></option>
                            <option value="email_clicked"><?php esc_html_e( 'Link Clicado (nas últimas X horas)', 'lc-crm' ); ?></option>
                            <option value="email_not_opened"><?php esc_html_e( 'Email NÃO Aberto (nas últimas X horas)', 'lc-crm' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Contato', 'lc-crm' ); ?>">
                            <option value="score_above"><?php esc_html_e( 'Pontuação Acima de', 'lc-crm' ); ?></option>
                            <option value="field_equals"><?php esc_html_e( 'Campo Igual a', 'lc-crm' ); ?></option>
                        </optgroup>
                    </select>
                </div>
                <div class="wpla-field" id="condition-field-row" style="display:none;">
                    <label><?php esc_html_e( 'Campo', 'lc-crm' ); ?></label>
                    <input type="text" id="condition-field" class="wpla-input" placeholder="first_name" />
                </div>
                <div class="wpla-field">
                    <label id="condition-value-label"><?php esc_html_e( 'Valor', 'lc-crm' ); ?></label>
                    <input type="text" id="condition-value" class="wpla-input" />
                </div>
            </div>

            <!-- Action fields -->
            <div id="step-action-fields" style="display:none;">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Tipo de Ação', 'lc-crm' ); ?></label>
                    <select id="action-type" class="wpla-select">
                        <optgroup label="<?php esc_attr_e( 'Tags', 'lc-crm' ); ?>">
                            <option value="add_tag"><?php esc_html_e( 'Adicionar Tag', 'lc-crm' ); ?></option>
                            <option value="remove_tag"><?php esc_html_e( 'Remover Tag', 'lc-crm' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Listas', 'lc-crm' ); ?>">
                            <option value="subscribe_list"><?php esc_html_e( 'Inscrever na Lista', 'lc-crm' ); ?></option>
                            <option value="unsubscribe_list"><?php esc_html_e( 'Remover da Lista', 'lc-crm' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Mensagens', 'lc-crm' ); ?>">
                            <option value="send_email"><?php esc_html_e( '📧 Enviar Email', 'lc-crm' ); ?></option>
                            <option value="send_whatsapp"><?php esc_html_e( '💬 Enviar WhatsApp', 'lc-crm' ); ?></option>
                            <option value="webhook"><?php esc_html_e( '🔗 Enviar Webhook', 'lc-crm' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Contato', 'lc-crm' ); ?>">
                            <option value="update_field"><?php esc_html_e( 'Atualizar Campo', 'lc-crm' ); ?></option>
                            <option value="update_score"><?php esc_html_e( 'Atualizar Pontuação', 'lc-crm' ); ?></option>
                        </optgroup>
                    </select>
                </div>
                <div id="action-config-fields"></div>
            </div>

            <!-- Delay fields -->
            <div id="step-delay-fields" style="display:none;">
                <div class="wpla-form-grid">
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Quantidade', 'lc-crm' ); ?></label>
                        <input type="number" id="delay-amount" class="wpla-input" value="1" min="1" />
                    </div>
                    <div class="wpla-field">
                        <label><?php esc_html_e( 'Unidade', 'lc-crm' ); ?></label>
                        <select id="delay-unit" class="wpla-select">
                            <option value="minutes"><?php esc_html_e( 'Minutos', 'lc-crm' ); ?></option>
                            <option value="hours"><?php esc_html_e( 'Horas', 'lc-crm' ); ?></option>
                            <option value="days"><?php esc_html_e( 'Dias', 'lc-crm' ); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('step-modal')"><?php esc_html_e( 'Cancelar', 'lc-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-confirm-step"><?php esc_html_e( 'Adicionar Etapa', 'lc-crm' ); ?></button>
        </div>
    </div>
</div>
