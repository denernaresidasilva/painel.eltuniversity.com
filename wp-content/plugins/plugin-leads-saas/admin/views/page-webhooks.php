<?php
/**
 * Webhooks page view.
 *
 * @package Plugin_Leads_SaaS
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$api_key     = get_option( 'pls_api_key', '' );
$webhook_url = rest_url( 'pls/v1/webhook' );
?>
<div class="pls-webhooks-page">
    <div class="pls-card">
        <h3><?php esc_html_e( 'Endpoint Webhook', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Use este endpoint para receber leads de sistemas externos (Zapier, Make, n8n, etc.).', 'plugin-leads-saas' ); ?></p>

        <div class="pls-field">
            <label><?php esc_html_e( 'URL', 'plugin-leads-saas' ); ?></label>
            <div class="pls-input-group">
                <input type="text" class="pls-input" value="<?php echo esc_attr( $webhook_url ); ?>" readonly id="webhook-url" />
                <button class="pls-btn" onclick="PLS.copyToClipboard(document.getElementById('webhook-url').value)"><?php esc_html_e( 'Copiar', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>

        <div class="pls-field">
            <label><?php esc_html_e( 'Chave API', 'plugin-leads-saas' ); ?></label>
            <div class="pls-input-group">
                <input type="text" class="pls-input" value="<?php echo esc_attr( $api_key ); ?>" readonly id="webhook-apikey" />
                <button class="pls-btn" onclick="PLS.copyToClipboard(document.getElementById('webhook-apikey').value)"><?php esc_html_e( 'Copiar', 'plugin-leads-saas' ); ?></button>
            </div>
        </div>
    </div>

    <div class="pls-card pls-mt-4">
        <h3><?php esc_html_e( 'Como Usar', 'plugin-leads-saas' ); ?></h3>
        <p class="pls-text-muted"><?php esc_html_e( 'Envie uma requisição POST com o seguinte corpo JSON:', 'plugin-leads-saas' ); ?></p>
        <pre class="pls-code-block">{
  "email": "john@example.com",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+5511999999999",
  "company": "Acme Inc",
  "tags": ["lead", "website"],
  "lists": [1, 2]
}</pre>
        <p class="pls-text-muted"><?php esc_html_e( 'Cabeçalhos:', 'plugin-leads-saas' ); ?></p>
        <pre class="pls-code-block">Content-Type: application/json
X-API-Key: <?php echo esc_html( $api_key ); ?></pre>
    </div>

    <div class="pls-card pls-mt-4">
        <h3><?php esc_html_e( 'Testar Webhook', 'plugin-leads-saas' ); ?></h3>
        <pre class="pls-code-block">curl -X POST "<?php echo esc_html( $webhook_url ); ?>" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <?php echo esc_html( $api_key ); ?>" \
  -d '{"email":"test@example.com","first_name":"Test","tags":["lead"]}'</pre>
    </div>
</div>
