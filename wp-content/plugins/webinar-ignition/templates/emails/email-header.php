<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;} ?>

<!DOCTYPE html>
<html lang="en" xmlns="https://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
	<head>
		<meta charset=<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<meta name="x-apple-disable-message-reformatting">
		<title><?php // echo get_bloginfo( 'name', 'display' ); ?></title>
	</head> 
	<body style="margin:0;padding:0;word-spacing:normal;">
		
		<?php if ( ! empty( $email_data ) && ! empty( $email_data->emailpreview ) ) : ?>
			<div id="preview_text" style="font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;"> <?php echo esc_html( $email_data->emailpreview ); ?> </div>
		<?php endif; ?>              
		
		<div id="article" role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">
			
			<table role="presentation" style="width:100%;border:none;border-spacing:0;">
				<tr>
					<td align="center" style="padding:0;">
						
							<!--[if mso]>
							<table role="presentation" align="center" style="width:600px;">
							<tr>
							<td>
							<![endif]-->
						<table id="container" role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;line-height:22px;">
							  
							
						<?php // First, check the site's default logo
								$theme_attach = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );
								$default_logo = $theme_attach ? esc_url( $theme_attach[0] ) : '';
								$img = get_option( 'webinarignition_email_logo_url' );
								// If the site logo is not found, set the webinar email logo instead
								if(WEBINARIGNITION_URL . 'images/wi-email-design-logo.png' != $img && $img != ''  ){
									$logo_url = $img;
								}elseif(WEBINARIGNITION_URL . 'images/wi-email-design-logo.png' == $img && $default_logo == ''){
									$logo_url = ! empty( $default_logo ) ? $default_logo : $img;
								}elseif('' == $img && $default_logo == ''){
									$logo_url = WEBINARIGNITION_URL . 'images/wi-email-design-logo.png';
								}else{
									$logo_url = $default_logo ;								
								}
								

								// If a logo is available from any source, then display it
								if ( $logo_url ) : ?>   
															
								<tr id="template_header_image">
									<td style="padding:40px 30px 30px 30px;font-size:24px;font-weight:bold;<?php echo ( get_option( 'header_img_algnmnt' ) == 'none' ) ? 'text-align: center;' : ''; ?>">
										<a href="<?php echo esc_url( get_site_url() ); ?>" target="_blank" style="text-decoration:none;">
											<img src="<?php echo esc_url( $logo_url ); ?>" 
												alt="" 
												style="width:100%;<?php echo esc_attr( $email_data->max_width_css ); ?>height:auto;border:none;text-decoration:none;color:#ffffff;">
										</a>
									</td>
								</tr>  
							
							<tr><td style="padding:10px;"><!-- Just for spacing--></td></tr>                
							
							
						<?php else : ?>   
							
							<tr><td style="padding:40px 30px 30px 30px;font-size:24px;font-weight:bold;"><!-- Just for spacing--></td></tr>                              
							
						<?php endif; ?>      

						<tr id="heading">
							<td style="padding:30px; text-align: center;">
							<h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">
    <?php echo esc_html( ! empty( $email_data ) && ! empty( $email_data->emailheading ) ? $email_data->emailheading : __( 'Email Heading Text', 'webinar-ignition' ) ); ?></h1>
							</td>
						</tr>

						<tr id="content_row">
							<td id="content_cell" style="padding:30px;">