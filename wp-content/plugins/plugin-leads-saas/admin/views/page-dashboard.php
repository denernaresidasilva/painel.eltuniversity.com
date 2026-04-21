<?php
/**
 * Dashboard page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="pls-dashboard" id="pls-dashboard">
    <!-- Stats cards -->
    <div class="pls-stats-grid">
        <div class="pls-stat-card">
            <div class="pls-stat-icon">👥</div>
            <div class="pls-stat-info">
                <span class="pls-stat-value" id="stat-contacts">—</span>
                <span class="pls-stat-label"><?php esc_html_e( 'Total de Contatos', 'plugin-leads-saas' ); ?></span>
            </div>
        </div>
        <div class="pls-stat-card">
            <div class="pls-stat-icon">✅</div>
            <div class="pls-stat-info">
                <span class="pls-stat-value" id="stat-active">—</span>
                <span class="pls-stat-label"><?php esc_html_e( 'Contatos Ativos', 'plugin-leads-saas' ); ?></span>
            </div>
        </div>
        <div class="pls-stat-card">
            <div class="pls-stat-icon">📋</div>
            <div class="pls-stat-info">
                <span class="pls-stat-value" id="stat-lists">—</span>
                <span class="pls-stat-label"><?php esc_html_e( 'Listas', 'plugin-leads-saas' ); ?></span>
            </div>
        </div>
        <div class="pls-stat-card">
            <div class="pls-stat-icon">⚡</div>
            <div class="pls-stat-info">
                <span class="pls-stat-value" id="stat-automations">—</span>
                <span class="pls-stat-label"><?php esc_html_e( 'Automações Ativas', 'plugin-leads-saas' ); ?></span>
            </div>
        </div>
        <div class="pls-stat-card">
            <div class="pls-stat-icon">🏷️</div>
            <div class="pls-stat-info">
                <span class="pls-stat-value" id="stat-tags">—</span>
                <span class="pls-stat-label"><?php esc_html_e( 'Tags', 'plugin-leads-saas' ); ?></span>
            </div>
        </div>
        <div class="pls-stat-card">
            <div class="pls-stat-icon">📈</div>
            <div class="pls-stat-info">
                <span class="pls-stat-value" id="stat-new7d">—</span>
                <span class="pls-stat-label"><?php esc_html_e( 'Novos (7 dias)', 'plugin-leads-saas' ); ?></span>
            </div>
        </div>
    </div>

    <!-- Email stats -->
    <div class="pls-card">
        <h3><?php esc_html_e( 'Email — Últimos 7 dias', 'plugin-leads-saas' ); ?></h3>
        <div class="pls-stats-grid">
            <div class="pls-stat-card pls-stat-card-sm">
                <div class="pls-stat-icon">📤</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="dash-email-sent">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Enviados', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card pls-stat-card-sm">
                <div class="pls-stat-icon">👁️</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="dash-email-opened">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Abertos', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card pls-stat-card-sm">
                <div class="pls-stat-icon">🖱️</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="dash-email-clicked">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Cliques', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
            <div class="pls-stat-card pls-stat-card-sm">
                <div class="pls-stat-icon">❌</div>
                <div class="pls-stat-info">
                    <span class="pls-stat-value" id="dash-email-failed">—</span>
                    <span class="pls-stat-label"><?php esc_html_e( 'Falhas', 'plugin-leads-saas' ); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Queue status -->
    <div class="pls-card">
        <h3><?php esc_html_e( 'Fila de Mensagens', 'plugin-leads-saas' ); ?></h3>
        <div class="pls-queue-stats" id="queue-stats">
            <span class="pls-badge pls-badge-info">Pendente: <b id="q-pending">0</b></span>
            <span class="pls-badge pls-badge-warning">Processando: <b id="q-processing">0</b></span>
            <span class="pls-badge pls-badge-success">Enviado: <b id="q-sent">0</b></span>
            <span class="pls-badge pls-badge-danger">Falhou: <b id="q-failed">0</b></span>
        </div>
    </div>

    <!-- Recent events -->
    <div class="pls-card">
        <h3><?php esc_html_e( 'Atividade Recente', 'plugin-leads-saas' ); ?></h3>
        <div class="pls-table-wrapper">
            <table class="pls-table" id="recent-events-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Evento', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Contato', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Data', 'plugin-leads-saas' ); ?></th>
                    </tr>
                </thead>
                <tbody id="recent-events-body">
                    <tr><td colspan="3" class="pls-text-center"><?php esc_html_e( 'Carregando...', 'plugin-leads-saas' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
