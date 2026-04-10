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
                <span class="wpla-stat-label"><?php esc_html_e( 'Total Contacts', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">✅</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-active">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Active Contacts', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">📋</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-lists">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Lists', 'roket-crm' ); ?></span>
            </div>
        </div>
        <div class="wpla-stat-card">
            <div class="wpla-stat-icon">⚡</div>
            <div class="wpla-stat-info">
                <span class="wpla-stat-value" id="stat-automations">—</span>
                <span class="wpla-stat-label"><?php esc_html_e( 'Active Automations', 'roket-crm' ); ?></span>
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
                <span class="wpla-stat-label"><?php esc_html_e( 'New (7 days)', 'roket-crm' ); ?></span>
            </div>
        </div>
    </div>

    <!-- Queue status -->
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Message Queue', 'roket-crm' ); ?></h3>
        <div class="wpla-queue-stats" id="queue-stats">
            <span class="wpla-badge wpla-badge-info">Pending: <b id="q-pending">0</b></span>
            <span class="wpla-badge wpla-badge-warning">Processing: <b id="q-processing">0</b></span>
            <span class="wpla-badge wpla-badge-success">Sent: <b id="q-sent">0</b></span>
            <span class="wpla-badge wpla-badge-danger">Failed: <b id="q-failed">0</b></span>
        </div>
    </div>

    <!-- Recent events -->
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Recent Activity', 'roket-crm' ); ?></h3>
        <div class="wpla-table-wrapper">
            <table class="wpla-table" id="recent-events-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Event', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Contact', 'roket-crm' ); ?></th>
                        <th><?php esc_html_e( 'Date', 'roket-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody id="recent-events-body">
                    <tr><td colspan="3" class="wpla-text-center"><?php esc_html_e( 'Loading...', 'roket-crm' ); ?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
