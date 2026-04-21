<?php
/**
 * Admin SPA shell — provides the main wrapper for all admin pages.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : 'pls-dashboard'; // phpcs:ignore WordPress.Security.NonceVerification
?>
<div id="pls-app" class="pls-app" data-page="<?php echo esc_attr( $current_page ); ?>">

    <!-- Main content -->
    <main class="pls-main">
        <!-- Top bar -->
        <header class="pls-topbar">
            <div class="pls-topbar-left">
                <?php
                $page_labels = array(
                    'pls-dashboard'   => __( 'Painel', 'plugin-leads-saas' ),
                    'pls-contacts'    => __( 'Contatos', 'plugin-leads-saas' ),
                    'pls-lists'       => __( 'Listas', 'plugin-leads-saas' ),
                    'pls-tags'        => __( 'Tags', 'plugin-leads-saas' ),
                    'pls-automations' => __( 'Automações', 'plugin-leads-saas' ),
                    'pls-webinars'    => __( 'Webinars', 'plugin-leads-saas' ),
                    'pls-forms'       => __( 'Formulários', 'plugin-leads-saas' ),
                    'pls-whatsapp'    => __( 'WhatsApp', 'plugin-leads-saas' ),
                    'pls-email'       => __( 'Email', 'plugin-leads-saas' ),
                    'pls-webhooks'    => __( 'Webhooks', 'plugin-leads-saas' ),
                    'pls-settings'    => __( 'Configurações', 'plugin-leads-saas' ),
                );
                ?>
                <h1 class="pls-page-title"><?php echo esc_html( $page_labels[ $current_page ] ?? 'Painel' ); ?></h1>
            </div>
            <div class="pls-topbar-right">
                <button class="pls-theme-toggle" title="<?php esc_attr_e( 'Alternar Modo Escuro', 'plugin-leads-saas' ); ?>" aria-label="<?php esc_attr_e( 'Alternar Modo Escuro', 'plugin-leads-saas' ); ?>">
                    <span class="pls-theme-toggle-track">
                        <span>☀️</span>
                        <span>🌙</span>
                    </span>
                    <span class="pls-theme-toggle-thumb"></span>
                </button>
            </div>
        </header>

        <!-- Page content container -->
        <div class="pls-content">
            <?php
            // Include the specific page view.
            $view_file = PLS_PLUGIN_DIR . 'admin/views/page-' . str_replace( 'pls-', '', $current_page ) . '.php';
            if ( file_exists( $view_file ) ) {
                include $view_file;
            } else {
                echo '<div class="pls-card"><p>' . esc_html__( 'Página não encontrada.', 'plugin-leads-saas' ) . '</p></div>';
            }
            ?>
        </div>
    </main>
</div>
