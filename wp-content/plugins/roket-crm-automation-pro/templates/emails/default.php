<?php
/**
 * Default email template.
 *
 * @var string $body The email body content.
 * @package Roket_CRM_Automation_Pro
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
    body { margin:0; padding:0; background:#f4f4f7; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; }
    .wrapper { max-width:600px; margin:0 auto; background:#ffffff; border-radius:8px; overflow:hidden; margin-top:20px; margin-bottom:20px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
    .header { background:linear-gradient(135deg,#6366f1,#8b5cf6); padding:30px; text-align:center; }
    .header h1 { color:#fff; margin:0; font-size:22px; font-weight:600; }
    .content { padding:30px; color:#374151; line-height:1.7; font-size:15px; }
    .footer { padding:20px 30px; background:#f9fafb; text-align:center; font-size:12px; color:#9ca3af; }
    a { color:#6366f1; }
</style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
    </div>
    <div class="content">
        <?php echo $body; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- HTML email body, sanitized upstream. ?>
    </div>
    <div class="footer">
        &copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ); ?>. All rights reserved.
    </div>
</div>
</body>
</html>
