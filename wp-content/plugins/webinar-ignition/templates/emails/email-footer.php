<?php defined( 'ABSPATH' ) || exit; ?>                


										</td><!-- #content_cell -->
									</tr><!-- #content_row -->

									<?php if ( ! empty( $email_data ) && ! empty( $email_data->footerContent ) ) : ?>
										<?php echo wp_kses_post( $email_data->footerContent ); ?>
									<?php else : ?>

										<?php if ( get_option( 'webinarignition_show_footer_branding' ) ) { ?>
										<tr id="footer_row">
											<td style="padding:30px;text-align:center;font-size:12px;color:#fff;">

													<a href="<?php echo esc_url( get_option( 'webinarignition_affiliate_link' ) ); ?>"  target="_blank">
														<p><?php echo esc_html( get_option( 'webinarignition_branding_copy' ) ); ?></p>
														<?php if ( ( $show_webinarignition_footer_logo == 'yes' ) || ( $show_webinarignition_footer_logo == '1' ) ) {
															?><img alt="WebinarIgnition Logo" border="0" class="welogo" src="<?php echo esc_url( WEBINARIGNITION_URL . 'images/wi-logo.png' );
															?>" width="284"><?php } ?>
													</a>

											</td>
										</tr>
									<?php } ?>

									<?php endif; ?>
										
									<tr id="webinarignition_email_signature">
									<td style="padding-top:5px;">
										<?php $email_signature = get_option( 'webinarignition_email_signature', '' ); $safe_email_signature = wp_kses_post( $email_signature ); echo wp_kses_post( wpautop( $safe_email_signature ) );?>
										</td>
									</tr>                                        

									<tr id="credit"> 
										<td style="padding:5px;text-align:center;font-size:12px;">
											<?php echo wp_kses_post( wpautop( wptexturize( apply_filters( 'webinarignition_email_footer_text', get_option( 'webinarignition_footer_text', '{site_title} | © Copyright {year} All rights reserved. {imprint} - {privacy_policy} {site_description}' ) ) ) ) ); ?>
										</td>
									</tr>

								</table>
							<!--[if mso]>
							</td>
							</tr>
							</table>
							<![endif]-->


					</td>
				</tr>
			</table>


		</div>
	</body>
</html>
