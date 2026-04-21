<?php
/**
 * Forms page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$lists = PLS_List_Model::all();
?>
<div class="pls-forms-page">
    <div class="pls-card">
        <h3><?php esc_html_e( 'Shortcodes de Formulário', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Cada lista gera um formulário automaticamente. Copie o shortcode e cole em qualquer página ou post.', 'plugin-leads-saas' ); ?></p>

        <?php if ( empty( $lists ) ) : ?>
            <div class="pls-empty-state">
                <p>📋 <?php esc_html_e( 'Nenhuma lista ainda. Crie uma lista primeiro para gerar shortcodes de formulário.', 'plugin-leads-saas' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=pls-lists' ) ); ?>" class="pls-btn pls-btn-primary"><?php esc_html_e( 'Criar Lista', 'plugin-leads-saas' ); ?></a>
            </div>
        <?php else : ?>
            <div class="pls-table-wrapper">
                <table class="pls-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Lista', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Shortcode', 'plugin-leads-saas' ); ?></th>
                            <th><?php esc_html_e( 'Pré-visualizar', 'plugin-leads-saas' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $lists as $list ) : ?>
                            <tr>
                                <td><strong><?php echo esc_html( $list->name ); ?></strong></td>
                                <td>
                                    <code class="pls-code pls-copyable" data-copy="[pls_form list=&quot;<?php echo esc_attr( $list->id ); ?>&quot;]">
                                        [pls_form list="<?php echo esc_html( $list->id ); ?>"]
                                    </code>
                                </td>
                                <td>
                                    <button class="pls-btn pls-btn-sm" onclick="PLS.copyShortcode(this)"><?php esc_html_e( 'Copiar', 'plugin-leads-saas' ); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="pls-card pls-mt-4">
        <h3><?php esc_html_e( 'Widget Elementor', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Se você tem o Elementor instalado, procure por "Leads SaaS Form" no painel de widgets para adicionar formulários de captura de leads visualmente.', 'plugin-leads-saas' ); ?></p>
    </div>

    <div class="pls-card pls-mt-4">
        <h3><?php esc_html_e( 'Opções do Shortcode', 'plugin-leads-saas' ); ?></h3>
        <div class="pls-table-wrapper">
            <table class="pls-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Atributo', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Descrição', 'plugin-leads-saas' ); ?></th>
                        <th><?php esc_html_e( 'Exemplo', 'plugin-leads-saas' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>list</code></td>
                        <td><?php esc_html_e( 'ID da lista para inscrição', 'plugin-leads-saas' ); ?></td>
                        <td><code>list="1"</code></td>
                    </tr>
                    <tr>
                        <td><code>title</code></td>
                        <td><?php esc_html_e( 'Título do formulário', 'plugin-leads-saas' ); ?></td>
                        <td><code>title="Join us"</code></td>
                    </tr>
                    <tr>
                        <td><code>button_text</code></td>
                        <td><?php esc_html_e( 'Texto do botão de envio', 'plugin-leads-saas' ); ?></td>
                        <td><code>button_text="Sign Up"</code></td>
                    </tr>
                    <tr>
                        <td><code>class</code></td>
                        <td><?php esc_html_e( 'Classe CSS personalizada', 'plugin-leads-saas' ); ?></td>
                        <td><code>class="my-form"</code></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
