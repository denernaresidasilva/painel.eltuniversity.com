<?php
/**
 * Dashboard page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-dashboard" id="wpla-dashboard">
    <!-- Stats cards -->
    <div class="wpla-stats-grid">
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">👥</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-contacts">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Total de Contatos', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">✅</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-active">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Contatos Ativos', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">📋</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-lists">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Listas', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">⚡</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-automations">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Automações Ativas', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">🏷️</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-tags">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Tags', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">📈</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-new7d">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Novos (7 dias)', 'roket-crm' ); ?></span>
            </div>
        </div>
    </div>

    <!-- Queue status -->
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Fila de Mensagens', 'roket-crm' ); ?></h3>
        <div class="wpla-queue-stats" id="queue-stats">
            <span class="wpla-badge wpla-badge-info">Pendente: <b id="q-pending">0</b></span>
            <span class="wpla-badge wpla-badge-warning">Processando: <b id="q-processing">0</b></span>
            <span class="wpla-badge wpla-badge-success">Enviado: <b id="q-sent">0</b></span>
            <span class="wpla-badge wpla-badge-danger">Falhou: <b id="q-failed">0</b></span>
        </div>
    </div>

    <!-- Recent events -->
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Atividade Recente', 'roket-crm' ); ?></h3>
        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="recent-events-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Evento', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Contato', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Data', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="recent-events-body">
                    <tr><td colspan="3" class="wpla-text-center"><?php esc_html_e( 'Carregando...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
