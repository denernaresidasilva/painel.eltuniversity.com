(function($) {

	$(document).on('ready', function() {
		if (!window.WEBINARIGNITION_LOADED) {
			window.WEBINARIGNITION_LOADED = true;
		var ajax_url              = window.WEBINARIGNITION.ajax_url,
			nonce                 = window.WEBINARIGNITION.nonce,
			translations          = window.WEBINARIGNITION.translations,
			auto_register_wrapper = $('#auto-register');

		if ( ! auto_register_wrapper.length ) {
			return;
		}

		var webinar_id            = auto_register_wrapper.data('webinar-id'),
			name                  = auto_register_wrapper.data('name'),
			lname                 = auto_register_wrapper.data('lname'),
			salutation            = auto_register_wrapper.data('salutation'),
			reason                = auto_register_wrapper.data('reason'),
			timezone              = Intl.DateTimeFormat().resolvedOptions().timeZone,
			email                 = auto_register_wrapper.data('email'),
			ip                    = auto_register_wrapper.data('ip'),
			thank_you_url         = auto_register_wrapper.data('thank-you-page-url'),
			webinar_type          = auto_register_wrapper.data('webinar-type'),
			plain_email           = auto_register_wrapper.data('plain-email'),
			webinar_auto_login_enabeled = auto_register_wrapper.data('auto-login-enabeled'),
			webinar_reg_login_url = auto_register_wrapper.data('reg-you-page-url'),
			is_draft              = auto_register_wrapper.data('webinar-status');

		if(is_draft == 'draft'){
			window.location.href = thank_you_url;
		}
		if( !webinar_auto_login_enabeled ){
				window.location.href = webinar_reg_login_url;
				return false;
		}
		$('#ar_submit_iframe').on('load', function (event) {
			if ( ! $(this).data('can_load') ) {
				return false;
			}
			// if( webinar_auto_login_enabeled ){
			// 	window.location.href = thank_you_url;
			// }else{
			// 	window.location.href = webinar_reg_login_url;
			// }
		});

		/**
		 *Email was creating issue so we will validate the email first before moving one
		 * wig prefix
		 **/
		function wigValidateEmail(email) {
			return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
		}

		var registerEmail = plain_email;
		if (!wigValidateEmail(registerEmail)) {
			alert(translations.verify_email);
			return;
		}

		// on load submit information & submit AR Form...

		// AJAX FOR WP
		var ajaxurl = ajax_url;
		var data = {
			action: 'webinarignition_add_lead_auto_reg',
			security: nonce,
			id:     webinar_id,
			weibnar_type:  webinar_type,
			name:   name,
			lname:   lname,
			salutation:   salutation,
			reason:   reason,
			userTimezone:   timezone,
			email:  email,
			ip: ip,
			source: "AutoReg"
		};
		
		if( webinar_type == 'live' ){
			if ($('#full_name_pot').length) {
				// Delay only if the honeypot field is present
				setTimeout(function () {
					runEmailVerificationLogic();
				}, 1000);
			} else {
				// Run immediately if honeypot field is not present
				runEmailVerificationLogic();
			}
			
			function runEmailVerificationLogic() {
				if($('#full_name_pot').val() != '') {
					// If honeypot field is filled, do not proceed with email verification
					alert(translations.bot_submission);
					return;
				}
				/**
				 * Before send email verification code. Let's get permission from settings.
				 */
				var webinar_email_verification_setting = auto_register_wrapper.data('email-verification-setting');
				var webinar_email_verification_enabled = auto_register_wrapper.data('email-verification-enabled');
			
				var email_verification_enabled = true;
				if (webinar_email_verification_setting === 'global') {
					email_verification_enabled = webinar_email_verification_enabled;
				} else if (webinar_email_verification_setting !== 'global' && webinar_email_verification_setting === 'no') {
					email_verification_enabled = false;
				}
			
				$.ajax({
					type: 'post',
					url: ajaxurl,
					data: {
						action: 'webinarignition_check_email_is_of_non_subscriber',
						security: nonce,
						email: data['email'],
						user_allowed: 'subscriber',
					},
					success: function (response) {
						if (response.status === true) {
							sendVerificationCode();
						} else {
							if (!email_verification_enabled) {
								wi_add_lead_auto_reg(ajaxurl, data);
							} else {
								sendVerificationCode();
							}
						}
					}
				});
			
				function sendVerificationCode() {
					$.ajax({
						type: 'post',
						url: ajaxurl,
						data: {
							action: 'webinarignition_send_email_verification_code',
							security: nonce,
							email: data['email'],
							id: data['id'],
						},
						success: function (response) {
							var decoded;
							try {
								decoded = JSON.parse(response);
							} catch (err) {
								console.log(err);
								decoded = false;
							}
			
							if (decoded && decoded['success'] == 1) {
								var popup = '<div style="position:absolute;top:0;height:100%;background-color:rgba(0,0,0,0.7);width:100%;font-size:14px;min-height:300px;"><div class="wiContainer container" style="height:100%;width:100%;display:flex;align-items:center;justify-content:center;"><div style="color:white;width:fit-content;margin:auto;padding:15px;border-radius:5px;background-color:#0496ac;text-align: center;"><div class="code_note">Please enter the code was sent to your email.</div><input class="email_code" name="email_code" style="color: black;height: 35px;margin:0;margin-top:0px;font-family:inherit;font-size:inherit;line-height:inherit;display:block;width:100%;margin-top:5px;"/><button class="verify_now" style="width:auto;background-color:#6fb200;margin-top:4px;color:white;font-weight:bold;border:1px solid rgba(0,0,0,0.2);padding: 8px 30px;">Verify</button></div></div></div>';
								$('body').append(popup);
							}
						}
					});
				}
			}
			
		}else{
			// webinar is not live but `AUTO`
			window.location.href = thank_you_url;

			// jQuery.post(ajaxurl, data, function ( response ) {
			// 	var result;
			// 	var decoded = true;
			// 	try {
			// 		result = JSON.parse(response);
			// 	}
			// 	catch(err) {
			// 		decoded = false;
			// 	}
			// 	if( result['success'] == 0 ){
			// 		alert(result['message']);
			// 		return;
			// 	}
			// 	else{
			// 		thank_you_url = thank_you_url;                                
			// 		if( jQuery("#AR-INTEGRATION" ).length > 0) {
			// 			jQuery('#ar_submit_iframe').data('can_load', 'true');
			// 			HTMLFormElement.prototype.submit.call(jQuery("#AR-INTEGRATION")[0]);
			// 		}
			// 		else {
			// 			window.location.href = thank_you_url;
			// 		}
			// 	}
			// });
		}

		// verify 
		jQuery(document).click('.verify_now', function(e){
			var code = jQuery('.email_code').val();
			jQuery.ajax({
				type: 'post',
				url: ajaxurl,
				data: {
					action: 'webinarignition_verify_user_email',
					security: nonce,
					email: data['email'],
					code: code,
				},
				success: function (response){
					var result = JSON.parse(response);
					if(result['status'] == 'success'){
						wi_add_lead_auto_reg(ajaxurl,data,code);
					}
				}
			});
		});

		/**
		 * The reason this function is created i was required to use this code twice in this file 
		 * based on email settings
		 */
		function wi_add_lead_auto_reg(ajaxurl,data,code = null){
			jQuery.post(ajaxurl, data, function ( response ) {
				var result;
				var decoded = true;
				try {
					result = JSON.parse(response);
				}
				catch(err) {
					decoded = false;
				}
				if( result['success'] == 0 ){
					alert(result['message']);
					return;
				} else{
					thank_you_url = thank_you_url+'&lid='+result['lid']+'&code='+code;   
				if( webinar_auto_login_enabeled ){
					window.location.href = thank_you_url;
				}else{
					window.location.href = webinar_reg_login_url;
				}
				}
			});
		}
	
		 

		/**
		 * JS wi-101
		 * if webinar status is draft return the user to home page
		 * Changes made by Faheem
		 */

		var elem = $('p[data-url-redirect]');

		if (elem.length) {
			var redirectUrl = elem.data('url-redirect');
			
			setTimeout(function () {
				window.location = redirectUrl;
			}, 15000);
		}
	
	

	// jQuery document.ready function ends... 
}

	});
})(jQuery);