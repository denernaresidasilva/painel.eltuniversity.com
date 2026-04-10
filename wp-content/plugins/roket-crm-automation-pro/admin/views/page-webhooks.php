<?php
/**
 * Webhooks page view.
 *
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$api_key     = get_option( 'wpla_api_key', '' );
$webhook_url = rest_url( 'wpla/v1/webhook' );
?>
<div class="wpla-webhooks-page">
    <div class="wpla-card">
        <h3><?php esc_html_e( 'Webhook Endpoint', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Use this endpoint to receive leads from external systems (Zapier, Make, n8n, etc.).', 'roket-crm' ); ?></p>

        <div class="wpla-field">
            <label><?php esc_html_e( 'URL', 'roket-crm' ); ?></label>
            <div class="wpla-input-group">
                <input type="text" class="wpla-input" value="<?php echo esc_attr( $webhook_url ); ?>" readonly id="webhook-url" />
                <button class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('webhook-url').value)"><?php esc_html_e( 'Copy', 'roket-crm' ); ?></button>
            </div>
        </div>

        <div class="wpla-field">
            <label><?php esc_html_e( 'API Key', 'roket-crm' ); ?></label>
            <div class="wpla-input-group">
                <input type="text" class="wpla-input" value="<?php echo esc_attr( $api_key ); ?>" readonly id="webhook-apikey" />
                <button class="wpla-btn" onclick="WPLA.copyToClipboard(document.getElementById('webhook-apikey').value)"><?php esc_html_e( 'Copy', 'roket-crm' ); ?></button>
            </div>
        </div>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'How to Use', 'roket-crm' ); ?></h3>
        <p class="wpla-text-muted"><?php esc_html_e( 'Send a POST request with the following JSON body:', 'roket-crm' ); ?></p>
        <pre class="wpla-code-block">{
  "email": "john@example.com",
  "first_name": "John",
  "last_name": "Doe",
  "phone": "+5511999999999",
  "company": "Acme Inc",
  "tags": ["lead", "website"],
  "lists": [1, 2]
}</pre>
        <p class="wpla-text-muted"><?php esc_html_e( 'Headers:', 'roket-crm' ); ?></p>
        <pre class="wpla-code-block">Content-Type: application/json
X-API-Key: <?php echo esc_html( $api_key ); ?></pre>
    </div>

    <div class="wpla-card wpla-mt-4">
        <h3><?php esc_html_e( 'Test Webhook', 'roket-crm' ); ?></h3>
        <pre class="wpla-code-block">curl -X POST "<?php echo esc_html( $webhook_url ); ?>" \
  -H "Content-Type: application/json" \
  -H "X-API-Key: <?php echo esc_html( $api_key ); ?>" \
  -d '{"email":"test@example.com","first_name":"Test","tags":["lead"]}'</pre>
    </div>
</div>
