<?php
/**
 * Settings page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wpla-settings-page">
    <form id="settings-form">

        <!-- WhatsApp Settings -->
        <div class="wpla-card wpla-mb-4">
            <h3>💬 <?php esc_html_e( 'Configurações WhatsApp', 'roket-crm' ); ?></h3>

            <div class="wpla-field">
                <label><?php esc_html_e( 'Provedor', 'roket-crm' ); ?></label>
                <select name="wpla_whatsapp_provider" class="wpla-select">
                    <option value="evolution" <?php selected( get_option( 'wpla_whatsapp_provider' ), 'evolution' ); ?>><?php esc_html_e( 'Evolution API', 'roket-crm' ); ?></option>
                    <option value="meta" <?php selected( get_option( 'wpla_whatsapp_provider' ), 'meta' ); ?>><?php esc_html_e( 'WhatsApp Cloud API (Meta)', 'roket-crm' ); ?></option>
                </select>
            </div>

            <fieldset class="wpla-fieldset" id="evolution-settings">
                <legend><?php esc_html_e( 'Evolution API', 'roket-crm' ); ?></legend>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'API URL', 'roket-crm' ); ?></label>
                    <input type="url" name="wpla_evolution_api_url" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_evolution_api_url' ) ); ?>" placeholder="https://your-evolution-api.com" />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Chave API', 'roket-crm' ); ?></label>
                    <input type="text" name="wpla_evolution_api_key" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_evolution_api_key' ) ); ?>" />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Nome da Instância', 'roket-crm' ); ?></label>
                    <input type="text" name="wpla_evolution_instance" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_evolution_instance' ) ); ?>" />
                </div>
            </fieldset>

            <fieldset class="wpla-fieldset" id="meta-settings">
                <legend><?php esc_html_e( 'WhatsApp Cloud API (Meta)', 'roket-crm' ); ?></legend>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Token de Acesso', 'roket-crm' ); ?></label>
                    <input type="text" name="wpla_meta_whatsapp_token" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_meta_whatsapp_token' ) ); ?>" />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'ID do Número de Telefone', 'roket-crm' ); ?></label>
                    <input type="text" name="wpla_meta_phone_number_id" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_meta_phone_number_id' ) ); ?>" />
                </div>
            </fieldset>
        </div>

        <!-- Email Settings -->
        <div class="wpla-card wpla-mb-4">
            <h3>📧 <?php esc_html_e( 'Configurações de Email', 'roket-crm' ); ?></h3>
            <div class="wpla-form-grid">
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Nome do Remetente', 'roket-crm' ); ?></label>
                    <input type="text" name="wpla_email_from_name" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_email_from_name', get_bloginfo( 'name' ) ) ); ?>" />
                </div>
                <div class="wpla-field">
                    <label><?php esc_html_e( 'Email do Remetente', 'roket-crm' ); ?></label>
                    <input type="email" name="wpla_email_from_address" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_email_from_address', get_bloginfo( 'admin_email' ) ) ); ?>" />
                </div>
            </div>
        </div>

        <!-- API Key -->
        <div class="wpla-card wpla-mb-4">
            <h3>🔑 <?php esc_html_e( 'Chave API', 'roket-crm' ); ?></h3>
            <div class="wpla-field">
                <label><?php esc_html_e( 'Chave API Atual', 'roket-crm' ); ?></label>
                <div class="wpla-input-group">
                    <input type="text" class="wpla-input" value="<?php echo esc_attr( get_option( 'wpla_api_key' ) ); ?>" readonly />
                    <button type="button" class="wpla-btn" onclick="WPLA.copyToClipboard(this.previousElementSibling.value)"><?php esc_html_e( 'Copiar', 'roket-crm' ); ?></button>
                </div>
            </div>
            <label class="wpla-checkbox">
                <input type="checkbox" name="regenerate_api_key" value="1" />
                <?php esc_html_e( 'Regenerar chave API ao salvar', 'roket-crm' ); ?>
            </label>
        </div>

        <button type="submit" class="wpla-btn wpla-btn-primary wpla-btn-lg" id="btn-save-settings">
            <?php esc_html_e( 'Salvar Configurações', 'roket-crm' ); ?>
        </button>
    </form>
</div>
