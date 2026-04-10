<?php
/**
 * Admin SPA shell — provides the main wrapper for all admin pages.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 'wpla-dashboard'; // phpcs:ignore WordPress.Security.NonceVerification
?>
<div id="wpla-app" class="wpla-app" data-page="<?php echo esc_attr( $current_page ); ?>">

    <!-- Sidebar -->
    <aside class="wpla-sidebar">
        <div class="wpla-sidebar-header">
            <div class="wpla-logo">
                <span class="wpla-logo-icon">🚀</span>
                <span class="wpla-logo-text">Roket CRM</span>
            </div>
            <small class="wpla-version">v<?php echo esc_html( WPLA_VERSION ); ?></small>
        </div>

        <nav class="wpla-nav">
            <?php
            $nav_items = array(
                'wpla-dashboard'   => array( 'icon' => '📊', 'label' => __( 'Dashboard', 'roket-crm' ) ),
                'wpla-contacts'    => array( 'icon' => '👥', 'label' => __( 'Contacts', 'roket-crm' ) ),
                'wpla-lists'       => array( 'icon' => '📋', 'label' => __( 'Lists', 'roket-crm' ) ),
                'wpla-tags'        => array( 'icon' => '🏷️', 'label' => __( 'Tags', 'roket-crm' ) ),
                'wpla-automations' => array( 'icon' => '⚡', 'label' => __( 'Automations', 'roket-crm' ) ),
                'wpla-forms'       => array( 'icon' => '📝', 'label' => __( 'Forms', 'roket-crm' ) ),
                'wpla-whatsapp'    => array( 'icon' => '💬', 'label' => __( 'WhatsApp', 'roket-crm' ) ),
                'wpla-email'       => array( 'icon' => '📧', 'label' => __( 'Email', 'roket-crm' ) ),
                'wpla-webhooks'    => array( 'icon' => '🔗', 'label' => __( 'Webhooks', 'roket-crm' ) ),
                'wpla-settings'    => array( 'icon' => '⚙️', 'label' => __( 'Settings', 'roket-crm' ) ),
            );

            foreach ( $nav_items as $slug => $item ) :
                $active = $current_page === $slug ? ' wpla-nav-active' : '';
                ?>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=' . $slug ) ); ?>" class="wpla-nav-item<?php echo $active; ?>">
                    <span class="wpla-nav-icon"><?php echo $item['icon']; ?></span>
                    <span class="wpla-nav-label"><?php echo esc_html( $item['label'] ); ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="wpla-sidebar-footer">
            <small>by <strong>Dener Naresi</strong></small>
        </div>
    </aside>

    <!-- Main content -->
    <main class="wpla-main">
        <!-- Top bar -->
        <header class="wpla-topbar">
            <div class="wpla-topbar-left">
                <button class="wpla-sidebar-toggle" title="Toggle Sidebar">☰</button>
                <h1 class="wpla-page-title"><?php echo esc_html( $nav_items[ $current_page ]['label'] ?? 'Dashboard' ); ?></h1>
            </div>
            <div class="wpla-topbar-right">
                <button class="wpla-theme-toggle" title="Toggle Dark Mode">🌙</button>
            </div>
        </header>

        <!-- Page content container -->
        <div class="wpla-content">
            <?php
            // Include the specific page view.
            $view_file = WPLA_PLUGIN_DIR . 'admin/views/page-' . str_replace( 'wpla-', '', $current_page ) . '.php';
            if ( file_exists( $view_file ) ) {
                include $view_file;
            } else {
                echo '<div class="wpla-card"><p>' . esc_html__( 'Page not found.', 'roket-crm' ) . '</p></div>';
            }
            ?>
        </div>
    </main>
</div>
