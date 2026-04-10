<?php
/**
 * Webhooks page view.
 *
 * @package LC_CRM_Automation
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$api_key     = get_option( 'wpla_api_key', '' );
$webhook_url = rest_url( 'wpla/v1/webhook' );
?>
<div class="wpla-webhooks-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Endpoint Webhook', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Use este endpoint para receber leads de sistemas externos (Zapier, Make, n8n, etc.).', 'lc-crm' ); ?></p>

        <div class="wpla-field">
            <label><?php esc_html_e( 'URL', 'lc-crm' ); ?></label>
            <div class="wpla-input-group">
                <input type="text" class="wpla-input" value="<?php echo esc_attr( $webhook_url ); ?>" readonly id="webhook-url" />
                <button class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('webhook-url').value)"><?php esc_html_e( 'Copiar', 'lc-crm' ); ?></button>
            </div>
        </div>

        <div class="wpla-field">
            <label><?php esc_html_e( 'Chave API', 'lc-crm' ); ?></label>
            <div class="wpla-input-group">
                <input type="text" class="wpla-input" value="<?php echo esc_attr( $api_key ); ?>" readonly id="webhook-apikey" />
                <button class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('webhook-apikey').value)"><?php esc_html_e( 'Copiar', 'lc-crm' ); ?></button>
            </div>
        </div>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Como Usar', 'lc-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Envie uma requisição POST com o seguinte corpo JSON:', 'lc-crm' ); ?></p>
        <pre class="wpla-code-block">{
  "email": "john@example.com",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+5511999999999",
  "company": "Acme Inc",
  "tags": ["lead", "website"],
  "lists": [1, 2]
}</pre>
        <p class="wpla-text-muted"><?php esc_html_e( 'Cabeçalhos:', 'lc-crm' ); ?></p>
        <pre class="wpla-code-block">Content-Type: application/json
X-API-Key: <?php echo esc_html( $api_key ); ?></pre>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Testar Webhook', 'lc-crm' ); ?></h3>
        <pre class="wpla-code-block">curl -X POST "<?php echo esc_html( $webhook_url ); ?>" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <?php echo esc_html( $api_key ); ?>" \
  -d '{"email":"test@example.com","first_name":"Test","tags":["lead"]}'</pre>
    </div>
</div>
