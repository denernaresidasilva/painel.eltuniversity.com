<?php
/**
 * Automations page view — includes flow builder UI.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-automations-page">
    <!-- List view -->
    <div id="automations-list-view">
        <div class="pls-toolbar">
            <div class="pls-toolbar-left">
                <h2><?php esc_html_e( 'Automações', 'plugin-leads-saas' ); ?></h2>
            </div>
            <div class="pls-toolbar-right">
                <button class="pls-btn pls-btn-primary" id="btn-add-automation">+ <?php esc_html_e( 'Nova Automação', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>

        <div class="pls-card">
            <div class="pls-table-wrapper">
                <table class="pls-table" id="automations-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Gatilho', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'plugin-leads-saas' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="automations-tbody">
                        <tr><td colspan="5" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Flow Builder view -->
    <div id="automation-builder" style="display:none;">
        <div class="pls-toolbar">
            <div class="pls-toolbar-left">
                <button class="pls-btn" id="btn-back-automations">← <?php esc_html_e( 'Voltar', 'plugin-leads-saas' ); ?></button>
                <input type="text" id="automation-name" class="pls-input pls-input-lg" placeholder="<?php esc_attr_e( 'Nome da automação...', 'plugin-leads-saas' ); ?>" />
            </div>
            <div class="pls-toolbar-right">
                <select id="automation-status" class="pls-select">
                    <option value="draft"><?php esc_html_e( 'Rascunho', 'plugin-leads-saas' ); ?></option>
                    <option value="active"><?php esc_html_e( 'Ativo', 'plugin-leads-saas' ); ?></option>
                    <option value="paused"><?php esc_html_e( 'Pausado', 'plugin-leads-saas' ); ?></option>
                </select>
                <button class="pls-btn pls-btn-primary" id="btn-save-automation"><?php esc_html_e( 'Salvar', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>

        <!-- Trigger selection -->
        <div class="pls-card pls-mb-4">
            <h4><?php esc_html_e( 'Gatilho', 'plugin-leads-saas' ); ?></h4>
            <select id="automation-trigger" class="pls-select">
                <optgroup label="<?php esc_attr_e( 'Contatos', 'plugin-leads-saas' ); ?>">
                    <option value="contact_created"><?php esc_html_e( 'Contato Criado', 'plugin-leads-saas' ); ?></option>
                    <option value="contact_updated"><?php esc_html_e( 'Contato Atualizado', 'plugin-leads-saas' ); ?></option>
                    <option value="tag_added"><?php esc_html_e( 'Tag Adicionada', 'plugin-leads-saas' ); ?></option>
                    <option value="tag_removed"><?php esc_html_e( 'Tag Removida', 'plugin-leads-saas' ); ?></option>
                    <option value="list_subscribed"><?php esc_html_e( 'Inscrito na Lista', 'plugin-leads-saas' ); ?></option>
                    <option value="form_submitted"><?php esc_html_e( 'Formulário Enviado', 'plugin-leads-saas' ); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e( 'Webinar', 'plugin-leads-saas' ); ?>">
                    <option value="webinar_inbox"><?php esc_html_e( '[Webinar] Entrou na INBOX', 'plugin-leads-saas' ); ?></option>
                    <option value="webinar_assistiu_oferta"><?php esc_html_e( '[Webinar] Assistiu a Oferta', 'plugin-leads-saas' ); ?></option>
                    <option value="webinar_nao_viu_oferta"><?php esc_html_e( '[Webinar] Não Viu a Oferta', 'plugin-leads-saas' ); ?></option>
                    <option value="webinar_replay"><?php esc_html_e( '[Webinar] Encaminhado para Replay', 'plugin-leads-saas' ); ?></option>
                    <option value="webinar_converteu"><?php esc_html_e( '[Webinar] Converteu (clicou oferta)', 'plugin-leads-saas' ); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e( 'Email', 'plugin-leads-saas' ); ?>">
                    <option value="email_opened"><?php esc_html_e( 'Email Aberto', 'plugin-leads-saas' ); ?></option>
                    <option value="link_clicked"><?php esc_html_e( 'Link Clicado', 'plugin-leads-saas' ); ?></option>
                    <option value="email_unsubscribed"><?php esc_html_e( 'Descadastrado', 'plugin-leads-saas' ); ?></option>
                </optgroup>
                <optgroup label="<?php esc_attr_e( 'Externo', 'plugin-leads-saas' ); ?>">
                    <option value="webhook_received"><?php esc_html_e( 'Webhook Recebido', 'plugin-leads-saas' ); ?></option>
                </optgroup>
            </select>
        </div>

        <!-- Flow canvas -->
        <div class="pls-flow-canvas" id="flow-canvas">
            <div class="pls-flow-nodes" id="flow-nodes">
                <!-- Nodes rendered by JS -->
            </div>
            <div class="pls-flow-add">
                <button class="pls-btn pls-btn-dashed" id="btn-add-step">+ <?php esc_html_e( 'Adicionar Etapa', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Step Modal -->
<div class="pls-modal" id="step-modal" style="display:none;">
    <div class="pls-modal-overlay"></div>
    <div class="pls-modal-content">
        <div class="pls-modal-header">
            <h3><?php esc_html_e( 'Adicionar Etapa', 'plugin-leads-saas' ); ?></h3>
            <button class="pls-modal-close">&times;</button>
        </div>
        <div class="pls-modal-body">
            <div class="pls-field">
                <label><?php esc_html_e( 'Tipo de Etapa', 'plugin-leads-saas' ); ?></label>
                <select id="step-type" class="pls-select">
                    <option value="condition"><?php esc_html_e( '🔀 Condição (SE/SENÃO)', 'plugin-leads-saas' ); ?></option>
                    <option value="action"><?php esc_html_e( '⚡ Ação', 'plugin-leads-saas' ); ?></option>
                    <option value="delay"><?php esc_html_e( '⏱ Atraso', 'plugin-leads-saas' ); ?></option>
                </select>
            </div>

            <!-- Condition fields -->
            <div id="step-condition-fields" style="display:none;">
                <div class="pls-field">
                    <label><?php esc_html_e( 'Tipo de Condição', 'plugin-leads-saas' ); ?></label>
                    <select id="condition-type" class="pls-select">
                        <optgroup label="<?php esc_attr_e( 'Tags', 'plugin-leads-saas' ); ?>">
                            <option value="has_tag"><?php esc_html_e( 'Possui Tag', 'plugin-leads-saas' ); ?></option>
                            <option value="contains_tag"><?php esc_html_e( 'Contém Tag', 'plugin-leads-saas' ); ?></option>
                            <option value="received_tag"><?php esc_html_e( 'Recebeu Tag', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Listas', 'plugin-leads-saas' ); ?>">
                            <option value="in_list"><?php esc_html_e( 'Na Lista', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Email', 'plugin-leads-saas' ); ?>">
                            <option value="email_opened"><?php esc_html_e( 'Email Aberto (nas últimas X horas)', 'plugin-leads-saas' ); ?></option>
                            <option value="email_clicked"><?php esc_html_e( 'Link Clicado (nas últimas X horas)', 'plugin-leads-saas' ); ?></option>
                            <option value="email_not_opened"><?php esc_html_e( 'Email NÃO Aberto (nas últimas X horas)', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Contato', 'plugin-leads-saas' ); ?>">
                            <option value="score_above"><?php esc_html_e( 'Pontuação Acima de', 'plugin-leads-saas' ); ?></option>
                            <option value="field_equals"><?php esc_html_e( 'Campo Igual a', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                    </select>
                </div>
                <div class="pls-field" id="condition-field-row" style="display:none;">
                    <label><?php esc_html_e( 'Campo', 'plugin-leads-saas' ); ?></label>
                    <input type="text" id="condition-field" class="pls-input" placeholder="first_name" />
                </div>
                <div class="pls-field">
                    <label id="condition-value-label"><?php esc_html_e( 'Valor', 'plugin-leads-saas' ); ?></label>
                    <input type="text" id="condition-value" class="pls-input" />
                </div>
            </div>

            <!-- Action fields -->
            <div id="step-action-fields" style="display:none;">
                <div class="pls-field">
                    <label><?php esc_html_e( 'Tipo de Ação', 'plugin-leads-saas' ); ?></label>
                    <select id="action-type" class="pls-select">
                        <optgroup label="<?php esc_attr_e( 'Tags', 'plugin-leads-saas' ); ?>">
                            <option value="add_tag"><?php esc_html_e( 'Adicionar Tag', 'plugin-leads-saas' ); ?></option>
                            <option value="remove_tag"><?php esc_html_e( 'Remover Tag', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Listas', 'plugin-leads-saas' ); ?>">
                            <option value="subscribe_list"><?php esc_html_e( 'Inscrever na Lista', 'plugin-leads-saas' ); ?></option>
                            <option value="unsubscribe_list"><?php esc_html_e( 'Remover da Lista', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Mensagens', 'plugin-leads-saas' ); ?>">
                            <option value="send_email"><?php esc_html_e( '📧 Enviar Email', 'plugin-leads-saas' ); ?></option>
                            <option value="send_whatsapp"><?php esc_html_e( '💬 Enviar WhatsApp', 'plugin-leads-saas' ); ?></option>
                            <option value="webhook"><?php esc_html_e( '🔗 Enviar Webhook', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                        <optgroup label="<?php esc_attr_e( 'Contato', 'plugin-leads-saas' ); ?>">
                            <option value="update_field"><?php esc_html_e( 'Atualizar Campo', 'plugin-leads-saas' ); ?></option>
                            <option value="update_score"><?php esc_html_e( 'Atualizar Pontuação', 'plugin-leads-saas' ); ?></option>
                        </optgroup>
                    </select>
                </div>
                <div id="action-config-fields"></div>
            </div>

            <!-- Delay fields -->
            <div id="step-delay-fields" style="display:none;">
                <div class="pls-form-grid">
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Quantidade', 'plugin-leads-saas' ); ?></label>
                        <input type="number" id="delay-amount" class="pls-input" value="1" min="1" />
                    </div>
                    <div class="pls-field">
                        <label><?php esc_html_e( 'Unidade', 'plugin-leads-saas' ); ?></label>
                        <select id="delay-unit" class="pls-select">
                            <option value="minutes"><?php esc_html_e( 'Minutos', 'plugin-leads-saas' ); ?></option>
                            <option value="hours"><?php esc_html_e( 'Horas', 'plugin-leads-saas' ); ?></option>
                            <option value="days"><?php esc_html_e( 'Dias', 'plugin-leads-saas' ); ?></option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="pls-modal-footer">
            <button class="pls-btn" onclick="PLS.closeModal('step-modal')"><?php esc_html_e( 'Cancelar', 'plugin-leads-saas' ); ?></button>
            <button class="pls-btn pls-btn-primary" id="btn-confirm-step"><?php esc_html_e( 'Adicionar Etapa', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>
</div>
