<?php
/**
 * Forms page view.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$lists = WPLA_List_Model::all();
?>
<div class="wpla-forms-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Shortcodes de Formulário', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Cada lista gera um formulário automaticamente. Copie o shortcode e cole em qualquer página ou post.', 'lc-crm' ); ?></p>

        <?php if ( empty( $lists ) ) : ?>
            <div class="wpla-empty-state">
                <p>📋 <?php esc_html_e( 'Nenhuma lista ainda. Crie uma lista primeiro para gerar shortcodes de formulário.', 'lc-crm' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=wpla-lists' ) ); ?>" class="wpla-btn wpla-btn-primary"><?php esc_html_e( 'Criar Lista', 'lc-crm' ); ?></a>
            </div>
        <?php else : ?>
            <div class="wpla-table-wrapper">
                <table class="wpla-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e( 'Lista', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Shortcode', 'lc-crm' ); ?></th>
                            <th><?php esc_html_e( 'Pré-visualizar', 'lc-crm' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $lists as $list ) : ?>
                            <tr>
                                <td><strong><?php echo esc_html( $list->name ); ?></strong></td>
                                <td>
                                    <code class="wpla-code wpla-copyable" data-copy="[wpla_form list=&quot;<?php echo esc_attr( $list->id ); ?>&quot;]">
                                        [wpla_form list="<?php echo esc_html( $list->id ); ?>"]
                                    </code>
                                </td>
                                <td>
                                    <button class="wpla-btn wpla-btn-sm" onclick="WPLA.copyShortcode(this)"><?php esc_html_e( 'Copiar', 'lc-crm' ); ?></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Widget Elementor', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Se você tem o Elementor instalado, procure por "LC CRM Form" no painel de widgets para adicionar formulários de captura de leads visualmente.', 'lc-crm' ); ?></p>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Opções do Shortcode', 'lc-crm' ); ?></h3>
        <div class="wpla-table-wrapper">
            <table class="wpla-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Atributo', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Descrição', 'lc-crm' ); ?></th>
                        <th><?php esc_html_e( 'Exemplo', 'lc-crm' ); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>list</code></td>
                        <td><?php esc_html_e( 'ID da lista para inscrição', 'lc-crm' ); ?></td>
                        <td><code>list="1"</code></td>
                    </tr>
                    <tr>
                        <td><code>title</code></td>
                        <td><?php esc_html_e( 'Título do formulário', 'lc-crm' ); ?></td>
                        <td><code>title="Join us"</code></td>
                    </tr>
                    <tr>
                        <td><code>button_text</code></td>
                        <td><?php esc_html_e( 'Texto do botão de envio', 'lc-crm' ); ?></td>
                        <td><code>button_text="Sign Up"</code></td>
                    </tr>
                    <tr>
                        <td><code>class</code></td>
                        <td><?php esc_html_e( 'Classe CSS personalizada', 'lc-crm' ); ?></td>
                        <td><code>class="my-form"</code></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
