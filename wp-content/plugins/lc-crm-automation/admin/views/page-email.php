<?php
/**
 * Email page view — log, templates, and stats tabs.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-email-page">

    <!-- Tab navigation -->
    <div class="wpla-tabs">
        <button class="wpla-tab-btn active" data-tab="email-log"><?php esc_html_e( '📋 Log de Envios', 'lc-crm' ); ?></button>
        <button class="wpla-tab-btn" data-tab="email-templates"><?php esc_html_e( '📝 Modelos', 'lc-crm' ); ?></button>
        <button class="wpla-tab-btn" data-tab="email-stats"><?php esc_html_e( '📊 Estatísticas', 'lc-crm' ); ?></button>
        <button class="wpla-tab-btn" data-tab="email-test"><?php esc_html_e( '🧪 Teste', 'lc-crm' ); ?></button>
    </div>

    <!-- ── Tab: Email Log ── -->
    <div class="wpla-tab-panel active" id="tab-email-log">
        <div class="wpla-card">
            <h3><?php esc_html_e( 'Mensagens de Email Enviadas', 'lc-crm' ); ?></h3>
            <p class="wpla-text-muted"><?php esc_html_e( 'Histórico de emails na fila e enviados.', 'lc-crm' ); ?></p>
            <div class="wpla-table-wrapper">
                <table class="wpla-table" id="email-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Contato', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Para', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Assunto', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Enviado em', 'lc-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="email-tbody">
                        <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Tab: Email Templates ── -->
    <div class="wpla-tab-panel" id="tab-email-templates" style="display:none;">
        <div class="wpla-toolbar">
            <div class="wpla-toolbar-left">
                <h3><?php esc_html_e( 'Modelos de Email', 'lc-crm' ); ?></h3>
            </div>
            <div class="wpla-toolbar-right">
                <button class="wpla-btn wpla-btn-primary" id="btn-add-email-template">+ <?php esc_html_e( 'Novo Modelo', 'lc-crm' ); ?></button>
            </div>
        </div>

        <div class="wpla-card">
            <p class="wpla-text-muted"><?php esc_html_e( 'Crie modelos reutilizáveis com suporte a variáveis: {{first_name}}, {{last_name}}, {{name}}, {{email}}, {{phone}}, {{company}}, {{unsubscribe_url}}.', 'lc-crm' ); ?></p>
            <div class="wpla-table-wrapper">
                <table class="wpla-table" id="email-templates-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Nome', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Assunto', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Status', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Criado em', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Ações', 'lc-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody id="email-templates-tbody">
                        <tr><td colspan="5" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'lc-crm' ); ?></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ── Tab: Email Stats ── -->
    <div class="wpla-tab-panel" id="tab-email-stats" style="display:none;">
        <div class="wpla-stats-grid">
            <div class="wpla-stat-card">
                <div class="wpla-stat-icon">📤</div>
                <div class="wpla-stat-info">
                    <span class="wpla-stat-value" id="estat-sent">—</span>
                    <span class="wpla-stat-label"><?php esc_html_e( 'Enviados (7 dias)', 'lc-crm' ); ?></span>
                </div>
            </div>
            <div class="wpla-stat-card">
                <div class="wpla-stat-icon">👁️</div>
                <div class="wpla-stat-info">
                    <span class="wpla-stat-value" id="estat-opened">—</span>
                    <span class="wpla-stat-label"><?php esc_html_e( 'Abertos', 'lc-crm' ); ?></span>
                </div>
            </div>
            <div class="wpla-stat-card">
                <div class="wpla-stat-icon">🖱️</div>
                <div class="wpla-stat-info">
                    <span class="wpla-stat-value" id="estat-clicked">—</span>
                    <span class="wpla-stat-label"><?php esc_html_e( 'Cliques', 'lc-crm' ); ?></span>
                </div>
            </div>
            <div class="wpla-stat-card">
                <div class="wpla-stat-icon">❌</div>
                <div class="wpla-stat-info">
                    <span class="wpla-stat-value" id="estat-failed">—</span>
                    <span class="wpla-stat-label"><?php esc_html_e( 'Falhas', 'lc-crm' ); ?></span>
                </div>
            </div>
            <div class="wpla-stat-card">
                <div class="wpla-stat-icon">🚫</div>
                <div class="wpla-stat-info">
                    <span class="wpla-stat-value" id="estat-unsub">—</span>
                    <span class="wpla-stat-label"><?php esc_html_e( 'Descadastrados', 'lc-crm' ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Tab: Test Send ── -->
    <div class="wpla-tab-panel" id="tab-email-test" style="display:none;">
        <div class="wpla-card">
            <h3><?php esc_html_e( 'Enviar Email de Teste', 'lc-crm' ); ?></h3>
            <form id="test-email-form" class="wpla-form-grid">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Email de Destino', 'lc-crm' ); ?></label>
                    <input type="email" id="test-email-to" class="wpla-input" placeholder="test@example.com" />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Assunto', 'lc-crm' ); ?></label>
                    <input type="text" id="test-email-subject" class="wpla-input" value="Test Email from LC CRM" />
                </div>
                <div class="wpla-field wpla-field-full">
                    <label><?php esc_html_e( 'Corpo (HTML)', 'lc-crm' ); ?></label>
                    <textarea id="test-email-body" class="wpla-input" rows="6"><p>Olá {{first_name}}, este é um email de teste do <strong>LC CRM</strong>.</p><p><a href="{{unsubscribe_url}}">Cancelar inscrição</a></p></textarea>
                </div>
                <div class="wpla-field">
                    <button type="submit" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Enviar Teste', 'lc-crm' ); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Email Template Modal -->
<div class="wpla-modal" id="email-template-modal" style="display:none;">
    <div class="wpla-modal-overlay"></div>
    <div class="wpla-modal-content wpla-modal-lg">
        <div class="wpla-modal-header">
            <h3 id="email-template-modal-title"><?php esc_html_e( 'Novo Modelo de Email', 'lc-crm' ); ?></h3>
            <button class="wpla-modal-close">&times;</button>
        </div>
        <div class="wpla-modal-body">
            <input type="hidden" id="tpl-id" value="0" />
            <div class="wpla-field">
                <label><?php esc_html_e( 'Nome do Modelo', 'lc-crm' ); ?></label>
                <input type="text" id="tpl-name" class="wpla-input" placeholder="Ex: Boas-vindas" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Assunto', 'lc-crm' ); ?></label>
                <input type="text" id="tpl-subject" class="wpla-input" placeholder="Olá {{first_name}}, bem-vindo(a)!" />
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Status', 'lc-crm' ); ?></label>
                <select id="tpl-status" class="wpla-select">
                    <option value="draft"><?php esc_html_e( 'Rascunho', 'lc-crm' ); ?></option>
                    <option value="active"><?php esc_html_e( 'Ativo', 'lc-crm' ); ?></option>
                </select>
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Corpo (HTML)', 'lc-crm' ); ?></label>
                <div class="wpla-editor-toolbar">
                    <button type="button" class="wpla-btn wpla-btn-sm" onclick="WPLA.insertVar('tpl-body', '{{first_name}}')">{{first_name}}</button>
                    <button type="button" class="wpla-btn wpla-btn-sm" onclick="WPLA.insertVar('tpl-body', '{{name}}')">{{name}}</button>
                    <button type="button" class="wpla-btn wpla-btn-sm" onclick="WPLA.insertVar('tpl-body', '{{email}}')">{{email}}</button>
                    <button type="button" class="wpla-btn wpla-btn-sm" onclick="WPLA.insertVar('tpl-body', '{{phone}}')">{{phone}}</button>
                    <button type="button" class="wpla-btn wpla-btn-sm" onclick="WPLA.insertVar('tpl-body', '{{unsubscribe_url}}')">{{unsubscribe_url}}</button>
                </div>
                <textarea id="tpl-body" class="wpla-input wpla-code-editor" rows="12" placeholder="<p>Olá {{first_name}},</p>..."></textarea>
            </div>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Pré-visualização', 'lc-crm' ); ?></label>
                <button type="button" class="wpla-btn" id="btn-preview-template"><?php esc_html_e( '👁 Visualizar', 'lc-crm' ); ?></button>
                <div id="tpl-preview" class="wpla-email-preview" style="display:none;"></div>
            </div>
        </div>
        <div class="wpla-modal-footer">
            <button class="wpla-btn" onclick="WPLA.closeModal('email-template-modal')"><?php esc_html_e( 'Cancelar', 'lc-crm' ); ?></button>
            <button class="wpla-btn wpla-btn-primary" id="btn-save-email-template"><?php esc_html_e( 'Salvar Modelo', 'lc-crm' ); ?></button>
        </div>
    </div>
</div>
