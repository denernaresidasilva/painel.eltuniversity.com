<?php
/**
 * Email page view — log, templates, and stats tabs.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-email-page">

    <!-- Tab navigation -->
    <div class="pls-tabs">
        <button class="pls-tab-btn active" data-tab="email-log"><?php esc_html_e( '📋 Log de Envios', 'plugin-leads-saas' ); ?></button>
        <button class="pls-tab-btn" data-tab="email-templates"><?php esc_html_e( '📝 Modelos', 'plugin-leads-saas' ); ?></button>
        <button class="pls-tab-btn" data-tab="email-stats"><?php esc_html_e( '📊 Estatísticas', 'plugin-leads-saas' ); ?></button>
        <button class="pls-tab-btn" data-tab="email-test"><?php esc_html_e( '🧪 Teste', 'plugin-leads-saas' ); ?></button>
    </div>

    <!-- ── Tab: Email Log ── -->
    <div class="pls-tab-panel active" id="tab-email-log">
        <div class="pls-card">
            <h3><?php esc_html_e( 'Mensagens de Email Enviadas', 'plugin-leads-saas' ); ?></h3>
            <p class="pls-text-muted"><?php esc_html_e( 'Histórico de emails na fila e enviados.', 'plugin-leads-saas' ); ?></p>
            <div class="pls-table-wrapper">
                <table class="pls-table" id="email-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Contato', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Para', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Assunto', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Enviado em', 'plugin-leads-saas' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="email-tbody">
                        <tr><td colspan="5" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Tab: Email Templates ── -->
    <div class="pls-tab-panel" id="tab-email-templates" style="display:none;">
        <div class="pls-toolbar">
            <div class="pls-toolbar-left">
                <h3><?php esc_html_e( 'Modelos de Email', 'plugin-leads-saas' ); ?></h3>
            </div>
            <div class="pls-toolbar-right">
                <button class="pls-btn pls-btn-primary" id="btn-add-email-template">+ <?php esc_html_e( 'Novo Modelo', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>

        <div class="pls-card">
            <p class="pls-text-muted"><?php esc_html_e( 'Crie modelos reutilizáveis com suporte a variáveis: {{first_name}}, {{last_name}}, {{name}}, {{email}}, {{phone}}, {{company}}, {{unsubscribe_url}}.', 'plugin-leads-saas' ); ?></p>
            <div class="pls-table-wrapper">
                <table class="pls-table" id="email-templates-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Assunto', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'plugin-leads-saas' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="email-templates-tbody">
                        <tr><td colspan="5" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Tab: Email Stats ── -->
    <div class="pls-tab-panel" id="tab-email-stats" style="display:none;">
        <div class="pls-stats-grid">
            <div class="pls-stat-card">
                <div class="pls-stat-icon">📤</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="estat-sent">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Enviados (7 dias)', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card">
                <div class="pls-stat-icon">👁️</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="estat-opened">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Abertos', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card">
                <div class="pls-stat-icon">🖱️</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="estat-clicked">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Cliques', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card">
                <div class="pls-stat-icon">❌</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="estat-failed">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Falhas', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card">
                <div class="pls-stat-icon">🚫</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="estat-unsub">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Descadastrados', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Tab: Test Send ── -->
    <div class="pls-tab-panel" id="tab-email-test" style="display:none;">
        <div class="pls-card">
            <h3><?php esc_html_e( 'Enviar Email de Teste', 'plugin-leads-saas' ); ?></h3>
            <form id="test-email-form" class="pls-form-grid">
                <div class="pls-field">
                    <label><?php esc_html_e( 'Email de Destino', 'plugin-leads-saas' ); ?></label>
                    <input type="email" id="test-email-to" class="pls-input" placeholder="test@example.com" />
                </div>
                <div class="pls-field">
                    <label><?php esc_html_e( 'Assunto', 'plugin-leads-saas' ); ?></label>
                    <input type="text" id="test-email-subject" class="pls-input" value="Test Email from Leads SaaS" />
                </div>
                <div class="pls-field pls-field-full">
                    <label><?php esc_html_e( 'Corpo (HTML)', 'plugin-leads-saas' ); ?></label>
                    <textarea id="test-email-body" class="pls-input" rows="6"><p>Olá {{first_name}}, este é um email de teste do <strong>Leads SaaS</strong>.</p><p><a href="{{unsubscribe_url}}">Cancelar inscrição</a></p></textarea>
                </div>
                <div class="pls-field">
                    <button type="submit" class="pls-btn pls-btn-primary"><?php esc_html_e( 'Enviar Teste', 'plugin-leads-saas' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email Template Modal -->
<div class="pls-modal" id="email-template-modal" style="display:none;">
    <div class="pls-modal-overlay"></div>
    <div class="pls-modal-content pls-modal-lg">
        <div class="pls-modal-header">
            <h3 id="email-template-modal-title"><?php esc_html_e( 'Novo Modelo de Email', 'plugin-leads-saas' ); ?></h3>
            <button class="pls-modal-close">&times;</button>
        </div>
        <div class="pls-modal-body">
            <input type="hidden" id="tpl-id" value="0" />
            <div class="pls-field">
                <label><?php esc_html_e( 'Nome do Modelo', 'plugin-leads-saas' ); ?></label>
                <input type="text" id="tpl-name" class="pls-input" placeholder="Ex: Boas-vindas" />
            </div>
            <div class="pls-field">
                <label><?php esc_html_e( 'Assunto', 'plugin-leads-saas' ); ?></label>
                <input type="text" id="tpl-subject" class="pls-input" placeholder="Olá {{first_name}}, bem-vindo(a)!" />
            </div>
            <div class="pls-field">
                <label><?php esc_html_e( 'Status', 'plugin-leads-saas' ); ?></label>
                <select id="tpl-status" class="pls-select">
                    <option value="draft"><?php esc_html_e( 'Rascunho', 'plugin-leads-saas' ); ?></option>
                    <option value="active"><?php esc_html_e( 'Ativo', 'plugin-leads-saas' ); ?></option>
                </select>
            </div>
            <div class="pls-field">
                <label><?php esc_html_e( 'Corpo (HTML)', 'plugin-leads-saas' ); ?></label>
                <div class="pls-editor-toolbar">
                    <button type="button" class="pls-btn pls-btn-sm" onclick="PLS.insertVar('tpl-body', '{{first_name}}')">{{first_name}}</button>
                    <button type="button" class="pls-btn pls-btn-sm" onclick="PLS.insertVar('tpl-body', '{{name}}')">{{name}}</button>
                    <button type="button" class="pls-btn pls-btn-sm" onclick="PLS.insertVar('tpl-body', '{{email}}')">{{email}}</button>
                    <button type="button" class="pls-btn pls-btn-sm" onclick="PLS.insertVar('tpl-body', '{{phone}}')">{{phone}}</button>
                    <button type="button" class="pls-btn pls-btn-sm" onclick="PLS.insertVar('tpl-body', '{{unsubscribe_url}}')">{{unsubscribe_url}}</button>
                </div>
                <textarea id="tpl-body" class="pls-input pls-code-editor" rows="12" placeholder="<p>Olá {{first_name}},</p>..."></textarea>
            </div>
            <div class="pls-field">
                <label><?php esc_html_e( 'Pré-visualização', 'plugin-leads-saas' ); ?></label>
                <button type="button" class="pls-btn" id="btn-preview-template"><?php esc_html_e( '👁 Visualizar', 'plugin-leads-saas' ); ?></button>
                <div id="tpl-preview" class="pls-email-preview" style="display:none;"></div>
            </div>
        </div>
        <div class="pls-modal-footer">
            <button class="pls-btn" onclick="PLS.closeModal('email-template-modal')"><?php esc_html_e( 'Cancelar', 'plugin-leads-saas' ); ?></button>
            <button class="pls-btn pls-btn-primary" id="btn-save-email-template"><?php esc_html_e( 'Salvar Modelo', 'plugin-leads-saas' ); ?></button>
        </div>
    </div>
</div>
