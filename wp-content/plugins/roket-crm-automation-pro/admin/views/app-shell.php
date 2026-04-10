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

    <!-- Main content -->
    <main class="wpla-main">
        <!-- Top bar -->
        <header class="wpla-topbar">
            <div class="wpla-topbar-left">
                <?php
                $page_labels = array(
                    'wpla-dashboard'   => __( 'Dashboard', 'roket-crm' ),
                    'wpla-contacts'    => __( 'Contacts', 'roket-crm' ),
                    'wpla-lists'       => __( 'Lists', 'roket-crm' ),
                    'wpla-tags'        => __( 'Tags', 'roket-crm' ),
                    'wpla-automations' => __( 'Automations', 'roket-crm' ),
                    'wpla-forms'       => __( 'Forms', 'roket-crm' ),
                    'wpla-whatsapp'    => __( 'WhatsApp', 'roket-crm' ),
                    'wpla-email'       => __( 'Email', 'roket-crm' ),
                    'wpla-webhooks'    => __( 'Webhooks', 'roket-crm' ),
                    'wpla-settings'    => __( 'Settings', 'roket-crm' ),
                );
                ?>
                <h1 class="wpla-page-title"><?php echo esc_html( $page_labels[ $current_page ] ?? 'Dashboard' ); ?></h1>
            </div>
            <div class="wpla-topbar-right">
                <button class="wpla-theme-toggle" title="<?php esc_attr_e( 'Toggle Dark Mode', 'roket-crm' ); ?>" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'roket-crm' ); ?>">
                    <span class="wpla-theme-toggle-track">
                        <span>☀️</span>
                        <span>🌙</span>
                    </span>
                    <span class="wpla-theme-toggle-thumb"></span>
                </button>
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
