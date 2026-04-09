(function($) {
	$(document).on('ready', function() {
		var nonce = window.WEBINARIGNITION.nonce,
			translations = window.WEBINARIGNITION.translations,
			images = window.WEBINARIGNITION.images,
			webinar_record = window.WEBINARIGNITION.webinar_record,
			webinar = window.WEBINARIGNITION.webinar;

		var log_parent = $("#we_view_log"),
			campaign_id = log_parent.data('campaign-id'),
			webinar_id = log_parent.data('webinar-id');
	
		$("#we_view_log").on("click", ".paginate", function() {
			$.ajax({
				url: ajaxurl,
				type: 'post',
				data: {
					action: "wi_show_logs_get",
					campaign_id:  campaign_id,
					page: $(this).attr("page"),
					security: nonce 
				}
			}).success(function (data) {
				$("#we_view_log").html(data);
			});
		});

		$("#we_view_log").on("click", "button#deleteLogs", function() {

			$("#we_view_log").html('');

			$.ajax({
				url: ajaxurl,
				type: 'post',
				data: {
					action:    "wi_delete_logs",
					campaign_id: campaign_id,
					security: nonce 
				}
			});
		});

		$("a.send_email_test").on("click", function(e) {
			e.preventDefault();

			var bodyeditorid,
			footereditorid,
			showhideinputid,
			emailfieldid,
			emailsubjectfieldid,
			emailheadingfieldid,
			templates_version,
			use_new_templatefieldid,
			use_new_templatefieldval,
			webinarid,
			emailpreviewfieldid;

			var $bodyContent    = '';
			var $footerContent  = '';
			var thisButton      = $(this);

			templates_version   = $( '#templates_version' ).val();

			use_new_templatefieldid        = thisButton.data('use_new_templatefieldid');
			use_new_templatefieldval       = $( '#' + use_new_templatefieldid ).val();

			emailfieldid        = thisButton.data('emailfieldid');
			emailfieldval       = $( '#' + emailfieldid ).val();

			emailsubjectfieldid     = thisButton.data('emailsubjectfieldid');
			emailsubjectval         = $( '#' + emailsubjectfieldid ).val();

			emailheadingfieldid     = thisButton.data('emailheadingfieldid');
			emailheadingval         = $( '#' + emailheadingfieldid ).val();

			emailpreviewfieldid     = thisButton.data('emailpreviewfieldid');
			emailpreviewval         = $( '#' + emailpreviewfieldid ).val();

			bodyeditorid    = thisButton.data('bodyeditorid');
			bodyeditorid    = bodyeditorid.replace("wp-", "");
			bodyeditorid    = bodyeditorid.replace("-wrap", "");

			if ($("#wp-" + bodyeditorid + "-wrap").hasClass("tmce-active")) {

				$bodyContent = tinyMCE.get(bodyeditorid).getContent();

			} else {

				$bodyContent = $("#" + bodyeditorid).val();
			}

			showhideinputid                 =   thisButton.data('showhideinputid');
			showhideinputElement            =   $( '#' + showhideinputid );
			showLocalFooter                 =   showhideinputElement.val();

			if( showLocalFooter == 'show' ){
				footereditorid = thisButton.data('footereditorid');
				footereditorid = footereditorid.replace("wp-", "");
				footereditorid = footereditorid.replace("-wrap", "");

				if ( $("#wp-" + footereditorid + "-wrap").hasClass("tmce-active") ) {

					$footerContent = tinyMCE.get(footereditorid).getContent();

				} else {

					$footerContent = $("#" + footereditorid).val();
				}
			}

			var data = {
				action                  : 'webinarignition_send_test_email',
				showLocalFooter         : showLocalFooter,
				security                : nonce,
				bodyContent             : $bodyContent,
				footerContent           : $footerContent,
				email                   : emailfieldval,
				subject                 : emailsubjectval,
				emailheadingval         : emailheadingval,
				emailpreviewval         : emailpreviewval,
				use_new_template        : use_new_templatefieldval,
				webinarid               : webinar_id,
				templates_version       : templates_version
			};

			$.post(ajaxurl, data, function ( response ) {
				const responseObj = JSON.parse(response);

				alert( responseObj.message );
			});

		});

		$('#add_support_member, #add_host_member').on('click', function(){

			var thisId       = $(this).attr('id');
			var templateStr;

			if( thisId ==  'add_host_member' ){
				templateStr = '<div class="newMember"> <div class="editSection" style="border-bottom:none;"> <div class="inputTitle"> <div class="inputTitleCopy">'+ translations.member_email +'</div><div class="inputTitleHelp">'+ translations.member_email_help +'</div></div><div class="inputSection"> <input class="inputField elem host_member_email" placeholder="'+ translations.host_email +'" type="email" value="" name="host_member_email_"> </div><br clear="left" > </div><div class="editSection" style="border-bottom:none;"> <div class="inputTitle"> <div class="inputTitleCopy">'+ translations.host_first_name +'</div></div><div class="inputSection"> <input class="inputField elem host_member_first_name" placeholder="'+ translations.member_first_name +'" type="text" value="" name="host_member_first_name_"> </div><br clear="left"> </div><div class="editSection"> <div class="inputTitle"> <div class="inputTitleCopy">'+ translations.host_last_name +'</div></div><div class="inputSection"> <input class="inputField elem host_member_last_name" placeholder="Doe" type="text" value="" name="host_member_last_name_"> </div><br clear="left"> </div><div class="editSection"> <div class="inputTitle"> <div class="inputTitleCopy">'+ translations.send_notifications +'</div></div><div class="inputSection"> <input type="checkbox" name="send_user_notification" id="send_user_notification" value="1" checked="checked"> </div><br clear="left" > </div><div class="editSection"> <div class="deleteMember"> <button type="button" class="btn btn-danger">'+ translations.delete_additional_host +'</button> </div></div></div>';
			} else {
				templateStr  = '<div class="newMember"><div class="editSection" style="border-bottom:none;"><div class="inputTitle"><div class="inputTitleCopy">'+ translations.support_staff_email_label +'</div><div class="inputTitleHelp">'+ translations.support_staff_email_placeholder +'</div></div><div class="inputSection"> <input class="inputField elem member_email" placeholder="'+ translations.member_email_placeholder +'" type="email" name="member_email_"></div><br clear="left" ></div><div class="editSection" style="border-bottom:none;"><div class="inputTitle"><div class="inputTitleCopy">'+ translations.support_staff_first_name +'</div></div><div class="inputSection"> <input class="inputField elem member_first_name" placeholder="'+ translations.member_first_name +'" type="text" name="member_first_name_"></div><br clear="left" ></div><div class="editSection"><div class="inputTitle"><div class="inputTitleCopy">'+ translations.support_staff_last_name +'</div></div><div class="inputSection"> <input class="inputField elem member_last_name" placeholder="'+ translations.member_last_name +'" type="text" name="member_last_name_"></div><br clear="left" ><div class="deleteMember"><button type="button" class="btn btn-danger">'+translations.delete_member+'</button></div></div></div>';
			}

			var memberCount     = ( thisId == 'add_support_member' ) ? $('#support_staff_count').val() : $('#host_member_count').val();
			var newMembercount  = parseInt(memberCount) + 1;

			templateStr = ( thisId == 'add_support_member' ) ? templateStr.replace(/member_email_/g,      'member_email_'+ newMembercount)      : templateStr.replace(/host_member_email_/g,      'host_member_email_'+ newMembercount);
			templateStr = ( thisId == 'add_support_member' ) ? templateStr.replace(/member_first_name_/g, 'member_first_name_'+ newMembercount) : templateStr.replace(/host_member_first_name_/g, 'host_member_first_name_'+ newMembercount);
			templateStr = ( thisId == 'add_support_member' ) ? templateStr.replace(/member_last_name_/g,  'member_last_name_'+ newMembercount)  : templateStr.replace(/host_member_last_name_/g,  'host_member_last_name_'+ newMembercount);

			if( thisId ==  'add_support_member' ){
				$('#support_staff_count').val( newMembercount );
				$( templateStr).insertBefore( "#addMemberButtonContainer" );
			} else {
				$('#host_member_count').val( newMembercount );
				$( templateStr).insertBefore( "#addHostMemberButtonContainer" );
			}

		});

		$(document).on('click', '.deleteMember button, .deleteHostMember button', function(){

			var thisButton      = $(this);
			var memberCount     = thisButton.hasClass('deleteMember') ? $('#support_staff_count').val() : $('#host_member_count').val();
			var newMembercount  = parseInt(memberCount) - 1;

			$(this).parents('.newMember' ).remove();

			if( thisButton.hasClass('deleteMember')  ){
				$('#support_staff_count').val(newMembercount);
			} else {
				$('#host_member_count').val(newMembercount);
			}

			var member_email_fields         = thisButton.hasClass('deleteMember') ? $('.member_email') : $('.host_member_email');
			var member_first_name_fields    = thisButton.hasClass('deleteMember') ? $('.member_first_name') : $('.host_member_first_name');
			var member_last_name_fields     = thisButton.hasClass('deleteMember') ? $('.member_last_name') : $('.host_member_last_name');

			if(member_email_fields.length){
				member_email_fields.each(function( index ) {
					var newNumber   = index + 1;
					var newName     = thisButton.hasClass('deleteMember') ? 'member_email_' + newNumber : 'host_member_email_' + newNumber ;
					$( this ).attr("name", newName );
				});
			}

			if(member_first_name_fields.length){
				member_first_name_fields.each(function( index ) {
					var newNumber   = index + 1;
					var newName     = thisButton.hasClass('deleteMember') ? 'member_first_name_' + newNumber : 'host_member_first_name_' + newNumber ;
					$( this ).attr("name", newName );
				});
			}

			if(member_last_name_fields.length){
				member_last_name_fields.each(function( index ) {
					var newNumber   = index + 1;
					var newName     = thisButton.hasClass('deleteMember') ? 'member_last_name_' + newNumber : 'host_member_last_name_' + newNumber ;
					$( this ).attr("name", newName );
				});
			}

		});
		
		$('#webinarignition_test_sms').on('click', function () {
			var phone_number = $('#webinarignition_test_sms_number').val();
			if (!phone_number) {
				alert(translations.provide_phone_number);
				return;
			}
			$trigger = $(this);
			if ($(this).find('img').length > 0)
				return;
			var backup_html = $trigger.html();
			$trigger.html('<img src="'+images.ajax_loader+'" />');
			$.post(ajaxurl, {
				action: 'webinarignition_test_sms',
				security: nonce,
				campaign_id: campaign_id,
				phone_number: phone_number
			}, function (data) {
				$trigger.html(backup_html);
				if (data && data.status === 1) {
					alert(translations.sms_sent);
				} else {
					alert('Error: ' + data.errors);
				}
			}, 'json');
		});
		
		if (webinar_record && typeof webinar_record.camtype !== 'undefined' && 'new' === webinar_record.camtype) {
			jQuery( 'input[name="date_format_custom_new"], input[name="date_format_custom"]' ).on( 'change', function() {
				if( jQuery(this).val() !== 'custom' ) {
					jQuery( 'input[name="date_format_custom"]' ).val(jQuery(this).val());
					console.log(moment().format(phpToMoment(jQuery(this).val())));
					jQuery('#date_format_preview').text(moment().format(phpToMoment(jQuery(this).val())));
				}
			});

			var custom_value = jQuery(  'input[name="date_format_custom"]').val();
			// jQuery('#date_format_preview').text(moment().format(phpToMoment(custom_value)));
			
				function phpToMoment(str) {
					let replacements = {
						'd': 'DD',
						'D': 'ddd',
						'j': 'D',
						'l': 'dddd',
						'N': 'E',
						'S': 'o',
						'w': 'e',
						'z': 'DDD',
						'W': 'W',
						'F': 'MMMM',
						'm': 'MM',
						'M': 'MMM',
						'n': 'M',
						't': '', // no equivalent
						'L': '', // no equivalent
						'o': 'YYYY',
						'Y': 'YYYY',
						'y': 'YY',
						'a': 'a',
						'A': 'A',
						'B': '', // no equivalent
						'g': 'h',
						'G': 'H',
						'h': 'hh',
						'H': 'HH',
						'i': 'mm',
						's': 'ss',
						'u': 'SSS',
						'e': 'zz', // deprecated since Moment.js 1.6.0
						'I': '', // no equivalent
						'O': '', // no equivalent
						'P': '', // no equivalent
						'T': '', // no equivalent
						'Z': '', // no equivalent
						'c': '', // no equivalent
						'r': '', // no equivalent
						'U': 'X'
					};

					return str.split('').map(chr => chr in replacements ? replacements[chr] : chr).join('');
				}
		} else {
				if (webinar) {
					jQuery('input[name="wi_show_day"]').prop('checked', webinar.wi_show_day).trigger('change');
				}	
		}

		$('#wi_test_ar').on('click', function (e) {
			e.preventDefault();
			$trigger = $(this);
			var bkpHtm = $trigger.html();
			$trigger.html(translations.saving);
			
			var ar_test_url = $(this).data('test-url');

			webinarignition_saveIt(
				function() {
					$trigger.html(bkpHtm);

					modal({
						name: $trigger.id + '_modal',
						head: translations.ar_integration_test,
						body: '<div style="width:100%; height:100%; padding:16px; overflow:auto">'+
							'<b>' + translations.in_order_to_test_ar + "<br><br>"+
							'</b>'+
							"<li>" + translations.click_the_button + "</li>"+
							"<li>" + translations.in_the_new_window + "</li>"+
							"<li>" + translations.if_all_went_well + "</li>"+
							'</div>',
						foot: [
							{
								'name': translations.test,
								'callback': function() {
									window.open(ar_test_url);
								}
							},
							{
								'name': translations.integration_tutorial,
								'callback': function() {
									window.open('https://webinarignition.tawk.help/category/e-mail-marketing-services');
								}
							},
							{
								'name': translations.done,
								'callback': function() {
									modal.exit();
								}
							}
						]
					});
				}
			);
		});
		
		var $thank_you_url = $('input[name="paid_thank_you_url"]'),
			$webinar_url = $('#paid_webinar_url'),
			$webinar_url_prefix = $('#webinar-url-prefix-container').data('url');

		$thank_you_url.add($webinar_url).attr('readonly', 'readonly').on( "click", function () {
			$(this).select();
		});

		$('#paid_code').on('input', function () {
			// Allow only alphanumeric characters
			let cleanValue = $(this).val().replace(/[^a-zA-Z0-9]/g, '');
			$(this).val(cleanValue);
		
			// Update related fields
			$('input[name="paid_thank_you_url"]').val($webinar_url_prefix + '?' + cleanValue);
			$webinar_url.val($webinar_url_prefix + '?live&' + CryptoJS.MD5(cleanValue));
		});

		jQuery(document).on('blur', '#paid_pay_url.inputField.elem.paypal_check', function (e) {
			var isValid = false;

			try {
				let urlString = (new URL($(this).val()));
				isValid = urlString.hostname.split('.').slice(-2).join('.') === 'paypal.com';
				if(!isValid) {
					$(this).val('');
				}
			} catch(err) {
				$(this).val('');
			}
		});

		/**
		 * EndOfSnack
		 * Js wi-102 Copied From wi-frontend-templates-functions.php 
		 * @ByFaheem
		 */

		//webinar-modern.php
		$('#main-content, .webinar_page').addClass('et_smooth_scroll_disabled');


		// document.ready function ends
	});
})(jQuery)