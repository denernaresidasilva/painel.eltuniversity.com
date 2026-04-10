<?php
/**
 * Admin SPA shell — provides the main wrapper for all admin pages.
 *
 * @package LC_CRM_Automation
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
                    'wpla-dashboard'   => __( 'Painel', 'lc-crm' ),
                    'wpla-contacts'    => __( 'Contatos', 'lc-crm' ),
                    'wpla-lists'       => __( 'Listas', 'lc-crm' ),
                    'wpla-tags'        => __( 'Tags', 'lc-crm' ),
                    'wpla-automations' => __( 'Automações', 'lc-crm' ),
                    'wpla-webinars'    => __( 'Webinars', 'lc-crm' ),
                    'wpla-forms'       => __( 'Formulários', 'lc-crm' ),
                    'wpla-whatsapp'    => __( 'WhatsApp', 'lc-crm' ),
                    'wpla-email'       => __( 'Email', 'lc-crm' ),
                    'wpla-webhooks'    => __( 'Webhooks', 'lc-crm' ),
                    'wpla-settings'    => __( 'Configurações', 'lc-crm' ),
                );
                ?>
                <h1 class="wpla-page-title"><?php echo esc_html( $page_labels[ $current_page ] ?? 'Painel' ); ?></h1>
            </div>
            <div class="wpla-topbar-right">
                <button class="wpla-theme-toggle" title="<?php esc_attr_e( 'Alternar Modo Escuro', 'lc-crm' ); ?>" aria-label="<?php esc_attr_e( 'Alternar Modo Escuro', 'lc-crm' ); ?>">
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
                echo '<div class="wpla-card"><p>' . esc_html__( 'Página não encontrada.', 'lc-crm' ) . '</p></div>';
            }
            ?>
        </div>
    </main>
</div>
