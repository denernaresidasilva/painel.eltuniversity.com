(function($) {
	$(document).on('ready', function() {
		var urlParam = function(name){
			var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
			if (results==null) {
				return null;
			}
			return decodeURI(results[1]) || 0;
		}

		var activeTab = ( urlParam( 'tab' ) ) ? urlParam( 'tab' ) : 'general' ;

		/* ================================================= */
		/* General Settings.
		/* ================================================= */
		if ( 'general' === activeTab ) {
			var affiliate_button_text = window?.WEBINARIGNITION?.settings?.general_settings?.affiliate_button_text;
			var powered_by_button_text = window?.WEBINARIGNITION?.settings?.general_setting?.powered_by_button_text;
			var powered_by_text_alert = window?.WEBINARIGNITION?.settings?.general_setting?.powered_by_text_alert;
			$('.color_picker').wpColorPicker();
			const show_hide_branding_settings = $('#show_hide_branding_settings');
			$(":checkbox").on('change', function () {
				if (this.checked) {
					this.value = 1;
				} else {
					this.value = '';
				}
			});
			$('#webinarignition_show_footer_branding').on('change', function (e) {
				if (this.checked) {
					show_hide_branding_settings.show();
				} else {
					show_hide_branding_settings.hide();
				}
			});
			const $webinarignition_affiliate_link = $('input#webinarignition_affiliate_link');
			const $webinarignition_affiliate_link_val_before = $('input#webinarignition_affiliate_link').val();
			$webinarignition_affiliate_link.on("blur", function () {
					const $webinarignition_affiliate_link_val = $('input#webinarignition_affiliate_link').val();

					if ($webinarignition_affiliate_link_val.length && $webinarignition_affiliate_link_val.indexOf("https://r.freemius.com") < 0) {
						alert(affiliate_button_text);

					if ($webinarignition_affiliate_link_val_before.indexOf("https://r.freemius.com") < 0) {
						$webinarignition_affiliate_link.val('');
					} else {
						$webinarignition_affiliate_link.val($webinarignition_affiliate_link_val_before);
					}
				}
			});
			var $webinarignition_branding_text = $('input#webinarignition_branding_copy');
			var acceptedWords = /Webinarignition|WebinarIgnition|webinarignition/;
			$webinarignition_branding_text.on("blur", function () {
			const $webinarignition_branding_text_val = $('input#webinarignition_branding_copy').val();
			if ($webinarignition_branding_text_val.length && !acceptedWords.test($webinarignition_branding_text_val)) {
				alert(powered_by_text_alert);
				$webinarignition_branding_text.val(powered_by_button_text);
			}
			});

			$(document).on('change', '#webinarignition_registration_auto_login', function (e) {
				if ($(this).is(':checked')) {
					$('#wi-auto-login-password-email').slideDown();
				} else {
					$('#wi-auto-login-password-email').slideUp();
				}
			});

			$('#wi-auto-login-password-email').trigger('change');
		}

		/* ================================================= */
		/* Email Settings.
		/* ================================================= */
		if ( 'email-templates' === activeTab ) {
			var media_uploader_title = window?.WEBINARIGNITION?.settings?.email_settings?.media_upload_title;
			var media_updater_title = window?.WEBINARIGNITION?.settings?.email_settings?.media_update_title;

			const form = $('#emailSettingsForm');
			const header_img_settings_container = $('#headerImgSettingsCont');
			const show_hide_header_settings = $('#show_hide_header_settings');

			$('.webinarignition_yes_no_switch').on('click', function(e) {

				if ($(this).hasClass('btn-primary')) {
					return;
				}

				var input = header_img_settings_container.find("input[type=hidden]");
				header_img_settings_container.find('.webinarignition_yes_no_switch').toggleClass('btn-primary');

				if ($(this).data('enable') === 1) {

					show_hide_header_settings.show();
					$(input).val(1);

				} else {

					show_hide_header_settings.hide();
					$(input).val(0);
				}

			});


			$('.color_picker').wpColorPicker();


			$(document.body).on('click', '.wi_upload_image_btn', function() {

				var btn = $(this);
				var container = btn.parents('.form-group');
				var img_holder = container.find('#input_image_holder');
				var input = container.find('.imgUrlField');
				var delete_btn = container.find('.wi_delete_image_btn');

				var custom_uploader = wp.media({
					title: media_uploader_title,
					library: {
						type: 'image'
					},
					button: {
						text: media_updater_title
					},
					multiple: false
				}).on('select', function() {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					var url = attachment.url;

					img_holder.html('<img src="' + attachment.url + '" />');
					input.val(url);
					delete_btn.show();
				}).open();

			});

			$(document.body).on('click', '.wi_delete_image_btn', function() {

				var btn = $(this);
				var container = btn.parents('.form-group');
				var img_holder = container.find('#input_image_holder');
				var input = container.find('.imgUrlField');

				btn.hide();
				img_holder.empty();
				input.val('');

			});

			$(document.body).on('change', 'input[name=header_img_algnmnt]', function() {

				var btn = $(this);
				var newValue = btn.val();
				var input_image_holder = $('#input_image_holder');

				if ('center' === newValue) {
					input_image_holder.css('float', 'none');
				} else {
					input_image_holder.css('float', newValue);
				}

			});


			$(document.body).on('change', 'input[name=webinarignition_enable_header_img_max_width]', function() {

				var btn = $(this);
				var max_width_div = $('#enable_header_img_max_width_div');

				if (btn.is(":checked")) {
					max_width_div.show();
				} else {
					max_width_div.hide();
				}

			});
		}

		/* ================================================= */
		/* Smtp Settings.
		/* ================================================= */
		if ( 'smtp-settings' === activeTab ) {
			const show_hide_smtp_settings   = $('#show_hide_smtp_settings');        
			var port                        = $('#webinarignition_smtp_port'); 
			const fromEmail                 = document.getElementById('fromEmail');

			$('.webinarignition_smtp_protocol').on('click', function (e) {
				var thisChoice = $(this).val(); 
				
				if( 'ssl' === thisChoice ){
					port.val(465);
				} 
				
				if( 'tls' === thisChoice ){
					port.val(587);
				}
				
				if( 'none' === thisChoice ){
					port.val(25);
				}           
			});

			$('.webinarignition_yes_no_switch').on('click', function (e) {
				if( $(this).hasClass( 'btn-primary') ) { return; }
				
				var parent = $(this).parent();
				var input = $(parent).find("input[type=hidden]");
				$(parent).find('.webinarignition_yes_no_switch').toggleClass('btn-primary');

				if( $(this).data('enable') === 1) { 
					if( $(this).hasClass( 'enable_disable_wi_smtp') ) { show_hide_smtp_settings.show(); } 
					$(input).val( 1 );
					fromEmail.disabled = true; 
				} else { 
					if( $(this).hasClass( 'enable_disable_wi_smtp') ) { show_hide_smtp_settings.hide(); } 
					$(input).val( 0 );
					fromEmail.disabled = false; 
				}
			});
		}
		
		/* ================================================= */
		/* Webhooks Settings.
		/* ================================================= */
		if ( 'webhooks' === activeTab ) {
			var confirm_delete_text = window?.WEBINARIGNITION?.settings?.webhooks_settings?.confirm_delete;
			var condition_table = $('#wi_webhooks_condition_table');

			$('#wi_webhooks_condition_add').on('click', function(e) {
				e.preventDefault();

				let condition_row = $('#wi_webhooks_condition_row').clone().removeAttr('id').addClass('wi-webhook-condition-row').show();
				condition_table.append(condition_row);

				if( condition_table.find('.wi-webhook-condition-row').length > 0) {
					$('.wi-webhook-condition-no-rows-message').hide();
					let tableDiv = $(condition_table).parent('div');
					$(tableDiv).animate({
						scrollTop: $(tableDiv)[0].scrollHeight - $(tableDiv)[0].clientHeight
					}, 1000);
				}
			});

			$(document).on('click', '.wi_webhooks_condition_delete', function(e) {
				e.preventDefault();

				var result = confirm(confirm_delete_text);

				if (result) {
					$(this).closest('tr', condition_table).remove();
					if (condition_table.find('.wi-webhook-condition-row').length === 0) {
						$('.wi-webhook-condition-no-rows-message').show();
					}
				}
			});

			$(document).on('change', '.wi_webhooks_condition_operator > select', function(e) {
				let this_value = $(this).val();
				let condition_value_input = $(this).closest('tr', condition_table).find('.wi_webhooks_condition_value input');
				let condition_new_field_value_input = $(this).closest('tr', condition_table).find('.wi_webhooks_condition_new_field_value input');
				if(this_value === 'map') {
					condition_value_input.val('').attr('readonly', true);
					condition_new_field_value_input.val('').attr('readonly', true);
				} else {
					condition_value_input.attr('readonly', false);
					condition_new_field_value_input.attr('readonly', false);
				}
			});

			$('#webinar_ignition_test_webhook').on('click', function(e) {
				e.preventDefault();
				var form_data = $(this).parent('form').serialize();

				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: form_data
				}).done(function(response) {
					console.log(response);
				});
			});
		}
		
	});
})(jQuery)