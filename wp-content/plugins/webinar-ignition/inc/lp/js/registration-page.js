(function($) {
    'use strict';

    // Hint: If you are reading this file for the first time, then start at the the "init" method.

    // This file is the first attempt to merge the bulk of the Javascript code that is duplicated across index.php, index_ss.php and index_cp.php,
    // into one? external file that can be reused in all 3 registration templates.

    var initialWebinarignition = {
        webinarId: 0,
        webinarType: '',
        ajaxUrl: '',
        thankYouPageUrl: '',
        useCustomThankYouUrl: false,
        skipThankYouPage: false,
        arUrl: 'none',
        paidCode: '',
        paymentProvider: 'unknown',
        isPaidWebinar: false,
        isSigningUpWithFB: false,
        fbUserData: {},
        userTimezone: '',
        scheduleType: '',
        leadDeviceInfo: {},
        userIp: '',

        // This is for values only used in live webinars.
        live: {

        },

        // This is for values only used in evergreen webinars.
        evergreen: {
            'schedules': {
                'custom': {
                    maxTime: '',
                },
                'fixed': {},
                'delayed': {},
            }

        },
        addQueryArg: function(arg, arg_value, url) {

            if( url.indexOf('?') !== -1 ) {
                url += '&';
            } else {
                url += '?';
            }

            url += arg + '=' + arg_value;

            return url;
        },
        videoFixes: function() {
            //This is to prevent auto-playing videos on Thank You page; see https://support.digitalkickstart.com/helpdesk/tickets/179864
            if(window.self !== window.top) {
                $('#videoBlock').remove();
            }

            // VIDEO FIXES:
            var wi_video_fix_w;
            var wi_video_fix_h;
            if ($(window).width() < 480) {
                //mobile size
                wi_video_fix_w = 287;
                wi_video_fix_h = 215;
            } else {
                wi_video_fix_w = 500;
                wi_video_fix_h = 281;
            }
            $('.videoBlock').find("embed, object").height(wi_video_fix_h).width(wi_video_fix_w);

            var iframeBlocks = $('.videoBlock').find("iframe");

            if (iframeBlocks.length) {
                iframeBlocks.each(function() {
                    var iframeBlock = $(this);

                    var styles = {
                        "position" : "absolute",
                        "width": "100%",
                        "height": "100%",
                        "left": "0",
                        "top": "0"
                    };

                    iframeBlock.css( styles );

                    // iframeBlock.wrap( "<div class='ctaAreaVideo-aspect-ratio'></div>" );
                    iframeBlock.wrap( "<div class='ctaAreaVideo-aspect-ratio' style='position: relative;width: 100%;height: 0;padding-bottom: 56.25%;'></div>" );
                });
            }

            iframeBlocks = $('.ctaArea.video').find("iframe");

            if (iframeBlocks.length) {
                iframeBlocks.each(function() {
                    var iframeBlock = $(this);

                    var styles = {
                        "position" : "absolute",
                        "width": "100%",
                        "height": "100%",
                        "left": "0",
                        "top": "0"
                    };

                    iframeBlock.css( styles );

                    // iframeBlock.wrap( "<div class='ctaAreaVideo-aspect-ratio'></div>" );
                    iframeBlock.wrap( "<div class='ctaAreaVideo-aspect-ratio' style='position: relative;width: 100%;height: 0;padding-bottom: 56.25%;'></div>" );
                });
            }
        },

        trackVisitor: function() {
            var cookie = $.cookie('we-trk-lp-' + this.webinarId);

            if (cookie !== 'tracked') {
                // set cookie
                $.cookie('we-trk-lp-' + this.webinarId, "tracked", {expires: 30, path: '/'});
                $.post(this.ajaxUrl, {
                    action: 'webinarignition_track_view',
                    security: window.WEBINARIGNITION.nonce,
                    id: this.webinarId,
                    page: 'lp'
                }, function (results) {

                });
            }
			
            // Track +1 Total
            $.post(this.ajaxUrl, {
                action: 'webinarignition_track_view_total',
                security: window.WEBINARIGNITION.nonce,
                id: this.webinarId,
                page: 'lp'
            });
        },

        validateEmail: function(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },

       fieldError(field, errorId) {
            field.attr("aria-describedby", errorId);
            field.attr("aria-invalid", true);
            field.addClass("errorField");
            field.removeClass("successField");
            $("#" + errorId).show();
            return false;
        },

        fieldClear(field, errorId) {
            field.addClass("successField");
            field.removeClass("errorField");
            field.removeAttr("aria-describedby");
            field.removeAttr("aria-invalid");
            $("#" + errorId).hide();
        },

         validateField(field, errorId) {
            if (field.length && field.hasClass("required") && field.val() === "") {
                return wi.fieldError(field, errorId);
            } else if (field.val() !== "") {
                wi.fieldClear(field, errorId);
            }
        },



        objectifyForm: function (formArray) {

            var returnObj = {};
            for (var i = 0, len = formArray.length; i < len; i++) {
                returnObj[formArray[i]['name']] = formArray[i]['value'];
            }
            return returnObj;
        },

        validateForm: function() {
            var is_form_valid = true;
 
            // Get registration form values
            var fullNameField = $("#optName");
            var fullName = fullNameField.val();

            var firstNameField = $("#optFName");
            var firstName = firstNameField.val();

            var lastNameField = $("#optLName");
            var lastName = lastNameField.val();

            var emailField = $("#optEmail");
            var email = emailField.val();

            var phoneField = $("#optPhone");
            var phone = phoneField.val();

            var optSalutationField = $("#optSalutation");
            var optReasonField = $("#optReason");
           
            if (fullName == "#firstlast#") {
                // using first & last name
                fullName = firstName + " " + lastName;
                $("#ar-name").val(firstName);
                $("#ar-lname").val(lastName);
            } else {
                // just full name
                $("#ar-name").val(fullName);
            }


            // Validation
            if (wi.validateField(firstNameField, "firstNameError") == false) {
                is_form_valid = false;
            }

            if (wi.validateField(lastNameField, "lastNameError") == false) {
                is_form_valid = false;
            }

            if (wi.validateField(fullNameField, "fullNameError") == false) {
                is_form_valid = false;
            }

            if (wi.validateField(phoneField, "phoneError") == false) {
                is_form_valid = false;
            }

            if (wi.validateField(optSalutationField, "salutationError") == false) {
                is_form_valid = false;
            }

			if (wi.validateField(optReasonField, "reasonError") == false) {
                is_form_valid = false;
            }

            if ( ( email == "" ) || ( ! wi.validateEmail(email) ) ) {
                emailField.addClass("errorField");
                emailField.removeClass("successField");
                is_form_valid = false;
                $("#wiEmailError").show();
            }   else if( email != "" ) {
                $("#ar-email").val(email);
                emailField.addClass("successField");
                emailField.removeClass("errorField");
                $("#wiEmailError").hide();
            }


            for (let i = 1; i <= 18; i++) {
                let optCustom   = $('#optCustom_' + i);
                let ar_custom   = $('#ar_custom_' + i);
                let errorId     = "custom" + i + "Error";

                if (optCustom.length) {
                    let optCustom_val = optCustom.val();

                    if (optCustom.hasClass("required")) {
                        if (optCustom.attr('type') === 'checkbox' && !optCustom.is(':checked')) {
                            is_form_valid = wi.fieldError(optCustom, errorId);
                        } else if (optCustom.attr('type') !== 'checkbox' && optCustom_val === '') {
                            is_form_valid = wi.fieldError(optCustom, errorId);
                        } else {
                            wi.fieldClear(optCustom, errorId);
                        }
                    } else {
                        wi.fieldClear(optCustom, errorId);
                    }

                    // ✅ Keep your hidden field sync logic
                    if (ar_custom.length) {
                        if (optCustom.attr('type') === 'checkbox') {
                            ar_custom.val(optCustom.is(':checked') ? 'yes' : 'no');
                        } else {
                            ar_custom.val(optCustom_val);
                        }
                    }
                }
            }


       

            // var i;
            // for( i=1; i<=18; i++ ){
            //     var optCustom         = $('#optCustom_'  + i);
            //     var ar_custom         = $('#ar_custom_'  + i);

            //     if( optCustom.length ) {
            //         var optCustom_val   = optCustom.val();

            //         if( optCustom.hasClass( "required" ) && ( optCustom.attr('type') == 'checkbox' && optCustom.is(':checked') === false ) ){
            //             optCustom.addClass("errorField");
            //             optCustom.removeClass("successField");
            //             is_form_valid = false;
            //             $("#custom" + i + "Error").show();
            //         } else if( optCustom.hasClass( "required" ) && ( optCustom_val == '' ) ){
            //             optCustom.addClass("errorField");
            //             optCustom.removeClass("successField");
            //             is_form_valid = false;
            //             $("#custom" + i + "Error").show();

            //         } else {
            //             optCustom.addClass("successField");
            //             optCustom.removeClass("errorField");
            //             $("#custom" + i + "Error").hide();


            //         }

            //         if ( ar_custom.length ) {
            //             if( optCustom.attr('type') == 'checkbox' ) {
            //                 if( optCustom.is(':checked') ) {
            //                     ar_custom.val( 'yes' ); //Set "yes" if checked
            //                 } else {
            //                     ar_custom.val( 'no' );  //"no" otherwise
            //                 }
            //             } else {
            //                 ar_custom.val( optCustom_val );
            //             }
            //         }
            //     }
            // }

            let gdprFields = ['pp','tc','ml'];
            var gdprFields_valid = true;

            $.each(gdprFields, function(index, gdpr_field) {
                if( $('#gdpr-' + gdpr_field).length > 0 ) {
                    if( $('#gdpr-' + gdpr_field).attr('required') && $('#gdpr-' + gdpr_field).is(':checked') === false ) {
                        $('#gdpr-' + gdpr_field).addClass("errorField");
                        $('#gdpr-' + gdpr_field).removeClass("successField");
                        gdprFields_valid = false;
                    } else {
                        $('#gdpr-' + gdpr_field).removeClass("errorField");
                        $('#gdpr-' + gdpr_field).addClass("successField");
                    }
                }
            });

            if(!gdprFields_valid) {
                return false;
            }

            var data = {
                fullName: fullName,
                firstName: firstName,
                lastName: lastName,
                email: email,
                phone: phone
            };

            if( ! is_form_valid ){
                return false;
            }

            window.registrationData = data;

            return data;
        },

        getRegisterFormData: function() {
            let wiRegForm = {};
            let formField = $('.optinFormArea input, .optinFormArea select, .optinFormArea textarea');

            if (formField.length) {
                formField.each(function(index, field) {
                    let field_name = field.id;
                    let field_value = field.value;
                    if(field.name) {
                        field_name = field.name;
                    }

                    if(field.type === 'checkbox' || field.type === 'radio') {
                        if( $(field).is(':checked') ) {
                            field_value = 'yes';
                        } else {
                            field_value = 'no';
                        }
                    }

                    let field_label = $(field).prev('label[for="' + field.id + '"]').html();
                    field_label = $('<div>' + field_label + '</div>').text();

                    if( !field_label.trim() || field_label.trim() === 'null' || typeof field_label === 'undefined' || field_label === 'undefined' ) {

                        if($(field).attr('placeholder')) {
                            field_label = $(field).attr('placeholder');
                        }
                    }

                    if( (!field_label.trim() || typeof field_label === 'undefined' || field_label === 'undefined') ) {
                        field_label = field_name;
                    }

                    wiRegForm[field_name] = {
                        'label':field_label.trim(),
                        'value':field_value
                    };
                });
            }

            return wiRegForm;
        },

        // Verify email from registration form.
        verifyemailsub: function(e) {
            e.preventDefault();
            
            // Get validated data or false if validation failed
            var v = wi.validateForm();

            console.log("v232", v);

            // Exit if form validation failed
            if (v === false) {
                return;
            }
            
            $.ajax({
                type: 'post',
                url: wi.ajaxUrl,
                data: {
                    action: 'webinarignition_check_email_is_of_non_subscriber',
                    security: window.WEBINARIGNITION.nonce,
                    user_allowed: 'subscriber',
                    email: v.email,
                },
                success: function (response) {
                    if (response.status === true) {
                        wi.emailVerificationEnabled = true;
                    }
                    wi.verifyEmail(e);
                }
            });
        },
        verifyEmail: function(e) {
            e.preventDefault();
            // get validated data or false if validation failed.
            var v = wi.validateForm();

            // if form validation failed we exit.
            if (v === false) {
                return;
            }

            if (window.webinarignition_send_email_verification_code) {
                return;
            }

            window.webinarignition_send_email_verification_code = true;

            var email = v.email;

            if (!wi.emailVerificationEnabled) {
                wi.handleSubmit();
                return;
            }

            const send_email_verification_code_data = {
                action: 'webinarignition_send_email_verification_code',
                security: window.WEBINARIGNITION.nonce,
                email: email,
                id: wi.webinarId
            }

            if (wi.translations.current_locale) {
                send_email_verification_code_data.current_locale = wi.translations.current_locale
            }

            let optinBtn = document.getElementById("optinBTN");
            let classes = optinBtn.classList;
            let computedStyles = window.getComputedStyle(optinBtn);
            let btnClasses = '';

            classes.forEach(cl => {
                console.log(cl);
                if (cl === 'addedArrow') {
                    btnClasses += ` ${cl}`;
                }
                if (cl === 'wiButton') {
                    btnClasses += ` ${cl}`;
                }
                if (cl === 'wiButton-block') {
                    btnClasses += ` ${cl}`;
                }
                if (cl === 'wiButton-lg') {
                    btnClasses += ` ${cl}`;
                }
            });

            $.ajax({
                type: 'post',
                url: wi.ajaxUrl,
                data: send_email_verification_code_data,
                success: function (response) {
                    var decoded;                    
                    try {
                        decoded = $.parseJSON(response);
                        
                    } catch(err) {
                        console.log(err);
                        decoded = false;
                    }                    
                    if(decoded){
                        if (wi.emailVerificationEnabled) {
                            var popup = `
                            <div style="position: fixed;top: 0;height: 100%;background-color: rgba(0,0,0,0.7);width: 100%;font-size: 14px;z-index:99999;">
                                <div class="wiContainer container" style="position: relative;top: 50%;width: 100%;">
                                    <div style="color:black; width: 300px; max-width: 100%; margin: auto; padding: 10px;border-radius: 5px;background-color: #fff;">
                                        <p class="code_note" style="text-align: center; margin-bottom:5px;">
                                            ${wi.translations.verify_email_text}
                                        </p>
                                        <div class="wiFormGroup wiFormGroup-lg" style="display: block;">
                                            <input class="email_code" name="email_code" type="text" style="width: 100%;">
                                        </div>
                                        <button id="verifyEmailBTN" class="verify_now${btnClasses}" style="margin-top:5px;">
                                            ${wi.translations.verify_email_btn}
                                        </button>
                                    </div>
                                </div>
                            </div>
                            `;
                            $('body').append(popup);
                            $('input.email_code').focus();
                            $('input.email_code').css("pointer-events", "auto");
							$('.mfp-wrap.mfp-auto-cursor.mfp-ready').removeAttr("tabindex");

                        }
                        return;
                    }                    
                }
            });
        },

        // Handle registration form submission.
        handleSubmit: function(e) {
            window.webinarignition_add_lead_auto = true;
			if($('#full_name_pot').val() != '') {
				// If honeypot field is filled, do not proceed with email verification
				alert(wi.translations.bot_submission);
				return;
			}
            if (e) {
                e.preventDefault();
            }
            // get validated data or false if validation failed.
            var v = wi.validateForm();

            if (!v.email) {
                v = window.registrationData;
            }

            let skipVerifyEmail = !wi.emailVerificationEnabled ? true : false;
            let code = wi.emailVerificationEnabled ? $('.email_code').val() : '';

            // Lead data to be saved to database.
            var data = {
                action: 'webinarignition_add_lead',
                security: window.WEBINARIGNITION.nonce,
                id: wi.webinarId,
                name: v.fullName,
                firstName: v.firstName,
                lastName: v.lastName,
                email: v.email,
                phone: v.phone,
                ip: wi.userIp,
                source: 'Optin',
				timezone : $("#webinar_timezone").val()

            };

            let verifyEmailData = {
                action: 'webinarignition_verify_user_email',
                security: window.WEBINARIGNITION.nonce,
                email: data['email'],
                code: code,
            };
			var eve_webinar_data = {
                action: 'webinarignition_add_lead',
                security: window.WEBINARIGNITION.nonce,
                id: wi.webinarId,
                name: v.fullName,
                firstName: v.firstName,
                lastName: v.lastName,
                email: v.email,
                phone: v.phone,
                ip: wi.userIp,
                timezone: $("#webinar_timezone").val()
            };

            if (skipVerifyEmail) {
                verifyEmailData.skipVerifyEmail = true;

                // Toggle registration button busy loading animation.
                var isImgBtn = $('#optinBTN').hasClass('optinBTNimg');

                if (!isImgBtn) {
                    var active_btn_color = $('#optinBTN').css('background-color');

                    $('#optinBTN').css('background-color', '#EAEAEA');
                    $('#optinBTNText').css('display', 'none');
                    $('#optinBTNLoading').css('display', 'inline');
                }
            }
            
            $.ajax({
                type: 'post',
                url: wi.ajaxUrl,
                data: verifyEmailData,
                success: function (response){                    
                    var result = $.parseJSON(response);

                    if(result['status'] == 'success'){
                        // If it is a evergreen we also need the some extra values.
                        if (wi.webinarType === 'evergreen') {
                            data['action'] = 'webinarignition_add_lead_auto';
                            data['date'] = $("#webinar_start_date").val();
                            data['time'] = $("#webinar_start_time").val();
                            data['timezone'] = $("#timezone_user").val();

                        }
						if (wi.webinarType === 'evergreen') {
                            eve_webinar_data['action'] = 'webinarignition_add_lead_auto';
                            eve_webinar_data['date'] = $("#webinar_start_date").val();
                            eve_webinar_data['time'] = $("#webinar_start_time").val();
                            eve_webinar_data['timezone'] = $("#timezone_user").val();
                        }
                        else{
                            data['action'] = 'webinarignition_add_lead';
                        }

                        data['lead_browser_and_os'] = wi.leadDeviceInfo;

                        var sendToHost  = $('.wiRegForm');
                        var wiRegForm       = {};

                        data.wiRegForm = wi.getRegisterFormData();

                        if( getVar('artest') === '1' ){
                            $('#ar_submit_iframe').data('can_load', 'true');
                            wi.doARSubmit(0);
                            // HTMLFormElement.prototype.submit.call($("#AR-INTEGRATION")[0]);

                            setTimeout(function() {
                                    modal
                                    ({
                                    name:'ar_test_modal',
                                    head: wiParsed.translations.ar_modal_head,
                                    body:'<div style="width:100%; height:100%; padding:16px; overflow:auto">'+
                                                '<p>'+ wiParsed.translations.ar_modal_body +'</p>'+
                                            '</div>',
                                    foot: [
                                        {
                                            'name': wiParsed.translations.done,
                                            'callback': function() {
                                                modal.exit();
                                                window.close();//close window when Ar Trial Is Done
                                            }
                                        }
                                    ]
                                    });

                            }, 1000);

                            return;
                        }

                        if (wi.emailVerificationEnabled) {
                            // Toggle registration button busy loading animation.
                            var isImgBtn = $('#optinBTN').hasClass('optinBTNimg');

                            if (!isImgBtn) {
                                var active_btn_color = $('#optinBTN').css('background-color');

                                $('#optinBTN').css('background-color', '#EAEAEA');
                                $('#optinBTNText').css('display', 'none');
                                $('#optinBTNLoading').css('display', 'inline');
                            }
                        }

                        $.ajax({
                            type: 'post',
                            url: wi.ajaxUrl,
                            data: {
                                action: 'webinarignition_add_lead_check_secure',
                                security: window.WEBINARIGNITION.nonce,
                                email: data['email'],
                                id: data['id']
                            },
                            success: function (response) {
                                var decoded;

                                try {
                                    decoded = $.parseJSON(response);
                                } catch(err) {
                                    console.log(err);
                                    decoded = false;
                                }

                                if (decoded) {
                                    if (decoded.success) {
                                        console.log('add lead ajax data', data);
                                        // Store Lead using AJAX.
                                        $.post(
                                            wi.ajaxUrl,
                                            data,
                                            function (leadId) {

                                            // Set Cookie - Signed Up
                                            $.cookie('we-trk-' + wi.webinarId, leadId, {expires: 30, path: '/'});

                                            // Set unique thank you url for lead.
                                            if (wi.useCustomThankYouUrl !== true) {
                                                wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.thankYouPageUrl);

                                                if ('' !== wi.custom_thankyou_page_url) {
                                                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.custom_thankyou_page_url);
                                                }
                                            }

                                            //if user chose to watch instantly, take them directly to webinar.
                                            if(wi.webinarType === 'evergreen' && data['date'] === 'instant_access') {
                                                if( wi.evergreen.skip_instant_acces_confirm_page ) {
                                                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('live', '', wi.webinarUrl);
                                                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.thankYouPageUrl);
                                                }
                                            } else if( data['date'] !== 'instant_access' ) {
                                                if (wi.skipThankYouPage === true) {
                                                    // registrant will be redirected directly to the webinar event.
                                                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('live', '', wi.webinarUrl);
                                                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.thankYouPageUrl);
													wi.thankYouPageUrl = initialWebinarignition.addQueryArg('webinar', '', wi.thankYouPageUrl);
                                                    // wi.thankYouPageUrl = wi.webinarUrl + 'live&lid=' + leadId;

                                                    if ('' !== wi.custom_webinar_page_url) {
                                                        wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.custom_webinar_page_url);
                                                    }
                                                }
                                            }

                                            if( (wi.webinarType === 'live') && ( wi.live.webinar_switch == 'live' ) ){
                                                wi.thankYouPageUrl = initialWebinarignition.addQueryArg('live', '', wi.webinarUrl);
                                                wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.thankYouPageUrl);
												wi.thankYouPageUrl = initialWebinarignition.addQueryArg('webinar', '', wi.thankYouPageUrl);
                                            }

                                            var params = new window.URLSearchParams(window.location.search);
                                            var login = params.get('login');
                                            if(login != null){
                                                wi.thankYouPageUrl = initialWebinarignition.addQueryArg('login', login, wi.thankYouPageUrl);
                                            }
                                            wi.thankYouPageUrl = initialWebinarignition.addQueryArg('code', code, wi.thankYouPageUrl);
                                            wi.thankYouPageUrl = initialWebinarignition.addQueryArg('webinar', '', wi.thankYouPageUrl);
                                            // AR Integration
                                            // If no AR Integration was configured we just redirect registrant to the thank you page.
                                            if ($("#AR-INTEGRATION").length < 1 || wi.arUrl === 'none') {
                                                console.log('No AR Submission needed.');

                                                window.location.href = wi.thankYouPageUrl;
                                                return;
                                            }

                                            // Submit AR form.
                                            var eventUrl = initialWebinarignition.addQueryArg('live', '', wi.webinarUrl);
                                                eventUrl = initialWebinarignition.addQueryArg('lid', leadId, eventUrl);
												eventUrl = initialWebinarignition.addQueryArg('webinar', '', eventUrl);

                                            // var eventUrl = wi.webinarUrl + '?live&lid=' + leadId;

                                            if (wi.webinarType === 'live') {
                                                $("#ar-webinar-url").val(eventUrl);
                                                $('#ar_submit_iframe').data('can_load', 'true');
                                                wi.doARSubmit(leadId);
                                                // HTMLFormElement.prototype.submit.call($("#AR-INTEGRATION")[0]);
                                                setTimeout(function() {
                                                    window.location = wi.thankYouPageUrl;
                                                }, 1000);
                                            } else {

                                                // For evergreen registrants we need to fetch the newly created lead data from the server.
                                                $.get(wi.ajaxUrl, {
                                                        action: 'webinarignition_get_lead_auto',
                                                        security: window.WEBINARIGNITION.nonce,
                                                        lid: leadId
                                                    },
                                                    function (lead) {
                                                        var leadData = JSON.parse(lead);
                                                        $("#ar-webinar-url").val(eventUrl);
														$("#ar-webinar-user-date-time").val(data['date']+' '+data['time']+' '+data['timezone']);
                                                        $("#ar-webinar-date").val(leadData.webinar_date);
                                                        $("#ar-webinar-time").val(leadData.webinar_time);
                                                        $("#ar-webinar-timezone").val(leadData.lead_timezone);

                                                        $('#ar_submit_iframe').data('can_load', 'true');
                                                        wi.doARSubmit(leadId);
                                                        // HTMLFormElement.prototype.submit.call($("#AR-INTEGRATION")[0]);

                                                        setTimeout(function() {
                                                            window.location = wi.thankYouPageUrl;
                                                        }, 1000);
                                                    });
                                            }

                                        });
                                    } else {
                                        setTimeout(function() {
                                            if (decoded.message) {
                                                alert(decoded.message);
                                            }

                                            if (!isImgBtn) {
                                                $('#optinBTN').css('background-color', active_btn_color);
                                                $('#optinBTNText').css('display', 'inline');
                                                $('#optinBTNLoading').css('display', 'none');
                                            }
                                        }, 1000);
                                    }
                                } else {
                                    setTimeout(function() {
                                        alert(wiParsed.translations.someWrong);

                                        if (!isImgBtn) {
                                            $('#optinBTN').css('background-color', active_btn_color);
                                            $('#optinBTNText').css('display', 'inline');
                                            $('#optinBTNLoading').css('display', 'none');
                                        }
                                    }, 1000);
                                }
                            }
                        });    
                    }
                    else{
                        const wrong_code_text = wi.translations.wrong_code_text || "Entered code is wrong.";
                        $('.code_note').html(wrong_code_text);
                        return;
                    }
                }
            });
        },

        // This method is used by the evergreen custom schedule only.
        customDateFields: function() {
            // Get Dates
            var data = {
                action: 'webinarignition_auto_lp_get_dates',
                security: window.WEBINARIGNITION.nonce,
                tz: wi.userTimezone,
                id: wi.webinarId
            };
            $.post(wi.ajaxUrl, data, wi.populateCustomDatesField);

            // Show available times
            $('#webinar_start_date').change(wi.showAvailableTimes);
        },

        populateCustomDatesCustomField: function (decoded) {
            // Get Timezone - Today
            var dates = decoded.dates;
            var times_by_date = decoded.hasOwnProperty('times_by_date') ? decoded.times_by_date : false;
            var todaysDate = moment().format("YYYY-MM-DD");
            var maxTime = wi.evergreen.schedules.custom.maxTime;

            var dontShowTodaysDate = false;
            if (maxTime) {
                var dateAndTimeFormat = "YYYY-MM-DD HH:mm";
                var mlatest = moment(todaysDate + " " + maxTime, dateAndTimeFormat);
                if ( mlatest.isBefore(moment() )) {
                    dontShowTodaysDate = true;
                }
            }

            $('#timezone_user').val(decoded.tz);

            let dates_count = Object.keys(dates).length;

            if (dates_count < 1) {
                $("#webinar_start_date option[value='none']").remove();
                $('#webinar_start_date').append($('<option>', {value: 0}).text(window.webinarignition.translations?.noScheduledWebinars || ''));
            } else {
                if (dates_count === 1 && dates.hasOwnProperty('instant_access')) {
                    var eventDateContainer = $("#webinar_start_date option").parents('.eventDate');
                    eventDateContainer.hide();
                    // eventDateContainer.empty();
                    // eventDateContainer.text(dates.instant_access);
                    $( "<div class='optinHeadline1 wiOptinHeadline1' style='margin-bottom: 18px;text-align: center;'>"+dates.instant_access+"</div>" ).insertAfter( eventDateContainer );
                } else {

                    if (times_by_date !== false) {
                        var times = JSON.stringify(times_by_date);
                        $("#webinar_start_date").data('times', times);
                    }

                    $.each(dates, function (key, value) {
                        $("#webinar_start_date option[value='none']").remove();

                        dontShowTodaysDate = false;
                        if (dontShowTodaysDate) {
                            if (todaysDate !== key) {
                                var option = $("<option></option>").val(key).text(value);
                                
                                $('#webinar_start_date')
                                    .append(option);
                            }
                        } else {
                            var option = $("<option></option>").val(key).text(value);

                            $('#webinar_start_date')
                                .append(option);
                        }
                    });
                }
            }

            $('#webinar_start_date').change();
        },
		populateCustomDatesField: function (decoded) {
            // Get Timezone - Today
            var dates = decoded.dates;
            var times_by_date = decoded.hasOwnProperty('times_by_date') ? decoded.times_by_date : false;
            var todaysDate = moment().format("YYYY-MM-DD");
            var maxTime = wi.evergreen.schedules.custom.maxTime;

            var dontShowTodaysDate = false;
            if (maxTime) {
                var dateAndTimeFormat = "YYYY-MM-DD HH:mm";
                var mlatest = moment(todaysDate + " " + maxTime, dateAndTimeFormat);
                if ( mlatest.isBefore(moment() )) {
                    dontShowTodaysDate = true;
                }
            }

            $('#timezone_user').val(decoded.tz);

            let dates_count = Object.keys(dates).length;

            if (dates_count < 1) {
                $("#webinar_start_date option[value='none']").remove();
                $('#webinar_start_date').append($('<option>', {value: 0}).text(window.webinarignition.translations?.noScheduledWebinars || ''));
            } else {
                if (dates_count === 1 && dates.hasOwnProperty('instant_access')) {
                    var eventDateContainer = $("#webinar_start_date option").parents('.eventDate');
                    eventDateContainer.hide();
                    // eventDateContainer.empty();
                    // eventDateContainer.text(dates.instant_access);
                    $( "<div class='optinHeadline1 wiOptinHeadline1' style='margin-bottom: 18px;text-align: center;'>"+dates.instant_access+"</div>" ).insertAfter( eventDateContainer );
                } else {

                    if (times_by_date !== false) {
                        var times = JSON.stringify(times_by_date);
                        $("#webinar_start_date").data('times', times);
                    }

                    $.each(dates, function (key, value) {
                        $("#webinar_start_date option[value='none']").remove();

                        dontShowTodaysDate = false;
                        if (dontShowTodaysDate) {
                            if (todaysDate !== key) {
                                var option = $("<option></option>").val(key).text(value);
                                
                                $('#webinar_start_date')
                                    .append(option);
                            }
                        } else {
                            var option = $("<option></option>").val(key).text(value);

                            $('#webinar_start_date')
                                .append(option);
                        }
                    });
                }
            }

            $('#webinar_start_date').change();
        },

        showAvailableTimes: function () {
			if ($(this).val() === null) $(this).val('instant_access'); // Set default dropdown value when not set
		
			var times = $(this).data('times');
			var times_item = false;
		
			if (times) {
				var times_decoded = JSON.parse(times);
				times_item = times_decoded[$(this).val()];
			}
		
			if (!times_item || $(this).val() == 'instant_access') {
				$("#webinarTime, .autoSep").hide();
			} else {
				var webinar_start_time = $('#webinar_start_time');
				var defaultSelected = false; // Has a default value already been selected?
		
				// Loop through time options and select the earliest available time (also disable times in the past)
				webinar_start_time.find('option').each(function () {
					$(this).removeAttr('style');
					$(this).removeAttr('selected');
					$(this).removeAttr('disabled');
		
					var webinar_tz = $('#timezone_user').val();
					var is_available = true;
		
					if (typeof times_item === 'object' && !Array.isArray(times_item)) {
						// Convert object to array
						times_item = Object.values(times_item);
					}
		
					if (false !== times_item) {
						is_available = times_item.includes($(this).val());
					}
		
					var DateTime = luxon.DateTime;
					var datetime_now = DateTime.now().setZone(webinar_tz); // Current datetime in webinar timezone
		
					// Get the selected date and time for comparison
					var selectedDate = $('#webinar_start_date').val(); // e.g., '2024-10-08'
					var selectedTime = $(this).val(); // e.g., '02:00 AM' or '14:00'
					
					// Convert selected time to 24-hour format
					function to24HourFormat(time) {
						const [timePart, modifier] = time.split(' ');
						let [hours, minutes] = timePart.split(':');
						if (modifier === 'PM' && hours !== '12') {
							hours = parseInt(hours, 10) + 12; // Convert PM to 24-hour format
						} else if (modifier === 'AM' && hours === '12') {
							hours = '00'; // Midnight case
						}
						return `${hours}:${minutes}`;
					}
		
					var time24 = to24HourFormat(selectedTime); // Convert selected time to 24-hour format
					var datetime_compare = DateTime.fromISO(selectedDate + 'T' + time24, { zone: webinar_tz }); // Selected date and time in webinar timezone
					
					console.log('Selected Date:', selectedDate);
					console.log('Selected Time:', selectedTime);
					console.log('Datetime Compare:', datetime_compare.toString());
					console.log('Datetime Now:', datetime_now.toString());
		
					// Check if the selected datetime value is less than the current datetime value, then disable and hide the time
					if (datetime_compare.valueOf() < datetime_now.valueOf() || !is_available) {
						$(this).prop('disabled', 'disabled').css({ 'display': 'none' });
					} else if (defaultSelected === false) {
						$(this).attr('selected', 'selected');
						defaultSelected = true;
					}
				});
		


                // Always show webinarTime
                $("#webinarTime").show();
                // Check autoSep individually
                $(".autoSep").each(function () {
                    if ($.trim($(this).html()) !== "") {
                        $(this).show();   // show if it has content
                    }
                });



			}
		
			return false;
		},

        setStripeClickEvent: function($form) {
            var $form = $form;
            $('#order_button').on('click', function (event) {
                wi.handleStripeClickEvent.call(this, event, $form);
            });
        },

        handleStripeClickEvent: function(event, $form) {
            $(this).attr('disabled', 'disable');
            $(this).unbind('click');
            if($form.length) {
                var stripeInputs = jQuery('#stripepayment :input');
                var stripeFormValues = {};
                stripeInputs.each(function() {
                    stripeFormValues[this.name] = $(this).val();
                });

                if (!stripeFormValues.stripe_receipt_email) {
                    event.preventDefault();
                    $(this).removeAttr('disabled');
                    $('input[name="stripe_receipt_email"]').addClass('errorField');
                    wi.setStripeClickEvent($form); // Recursively add event listener.
                    return;
                }
                window.stripe_receipt_email = stripeFormValues.stripe_receipt_email;
                $('input[name="stripe_receipt_email"]').removeClass('errorField');

                var stripeValues = {
                    number: stripeFormValues['stripe_number'],
                    exp_month: stripeFormValues['stripe_exp_month'],
                    exp_year: stripeFormValues['stripe_exp_year'],
                    cvc: stripeFormValues['stripe_cvc'],
                };

                Stripe.card.createToken(stripeValues, wi.stripeResponseHandler);
                event.preventDefault();
            }
        },

        stripeResponseHandler: function(status, response) {
            // Grab the form:
            var $form = jQuery('#stripepayment');
            if (response.error) {
                wi.setStripeClickEvent($form);
                // Show the errors on the form
                $('.payment-errors').hide();
                var paymentErrorBlock = $('.payment-errors');
                paymentErrorBlock.text(response.error.message);
                paymentErrorBlock.show();
                $form.find('.submit').prop('disabled', false); // Re-enable submission
                $('#order_button').attr('disabled', false);

                return;
            }

            // Token was created!
            // Get the token ID:
            var token = response.id;
            var campaign_id = wi.webinarId;

            // Insert the token ID into the form so it gets submitted to the server:
            $form.append(jQuery('<input type="hidden" name="stripeToken">').val(token));
            $form.append(jQuery('<input type="hidden" name="campaign_id">').val(campaign_id));

            // Send the data using post
            var posting = $.post( wi.ajaxUrl, {
                action: 'webinarignition_process_stripe_charge',
                security: window.WEBINARIGNITION.nonce,
                token: token,
                campaign_id: campaign_id,
                stripe_receipt_email: window.stripe_receipt_email,
            } );

            posting.done(function( data ) {
                try {
                    var response = JSON.parse(data);
                    if (response.status !== 1) {
                        return;
                    }
                } catch (error) {
                    return;
                }

                $('.payment-errors').hide();
                var paymentSuccessBlock = $('.payment-success');
                paymentSuccessBlock.show();
                window.location.href = wi.webinarUrl + wi.paidCode + '&payment=success&sremail='+ window.stripe_receipt_email;
            });
        },

        handleFacebookSignup: function() {
            // Add Name & Email To Optin Field & Submit It...
            $("#optName").val(wi.fbUserData['name']);
            $("#optEmail").val(wi.fbUserData['email']);

            $("#ar-name").val(wi.fbUserData['name']);
            $("#ar-email").val(wi.fbUserData['email']);

            var data = {
                action  : 'webinarignition_add_lead',
                id      : wi.webinarId,
                name    : wi.fbUserData['name'],
                email   : wi.fbUserData['email'],
                security: window.WEBINARIGNITION.nonce,
                phone   : "N/A",
                ip      : wi.userIp,
                source  : "FB",
            };


            // Store Lead - Post
            $.post(wi.ajaxUrl, data, function (leadId) {

                // Set Cookie - Signed Up
                $.cookie('we-trk-' + wi.webinarId, leadId, {expires: 30, path: '/'});

                // Set unique thank you url for lead.
                if (wi.useCustomThankYouUrl !== true) {
                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.thankYouPageUrl);

                    // wi.thankYouPageUrl = wi.thankYouPageUrl + '&lid=' + leadId;
                }

                if (wi.skipThankYouPage === true) {
                    // registrant will be redirected directly to the webinar event.
                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('live', '', wi.webinarUrl);
                    wi.thankYouPageUrl = initialWebinarignition.addQueryArg('lid', leadId, wi.thankYouPageUrl);

                    // wi.thankYouPageUrl = wi.webinarUrl + 'live&lid=' + leadId;
                }

                // AR Integration
                // ---------------

                // If no AR Integration was configured we just redirect registrant to the thank you page.
                if ($("#AR-INTEGRATION").length < 1 || wi.arUrl === 'none') {
                    window.location.href = wi.thankYouPageUrl;
                    return;
                }

                // Submit AR form.
                let ar_webinar_url = initialWebinarignition.addQueryArg('live', '', wi.webinarUrl);
                    ar_webinar_url = initialWebinarignition.addQueryArg('lid', leadId, ar_webinar_url);

                 $("#ar-webinar-url").val(ar_webinar_url);
                // $("#ar-webinar-url").val(wi.webinarUrl + '?live&lid=' + leadId);
                $('#ar_submit_iframe').data('can_load', 'true');
                wi.doARSubmit(leadId);
                // HTMLFormElement.prototype.submit.call($("#AR-INTEGRATION")[0]);

                setTimeout(function() {
                    window.location = wi.thankYouPageUrl;
                }, 1000);
            });
        },

        init: function() {
            // USER TIMEZONE
            wi.userTimezone = jstz.determine_timezone().timezone.olson_tz;
            if( !wi.userTimezone ) {
                wi.userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            }
			// Create a function to get offset in minutes for a timezone
			function getTimezoneOffsetMinutes(timeZone) {
				const now = new Date();
				const options = { timeZone, timeZoneName: 'short' };
				const format = new Intl.DateTimeFormat('en-US', options);
				const parts = format.formatToParts(now);
				const timeZoneName = parts.find(part => part.type === 'timeZoneName').value;

				// Match offset like "GMT+5" or "GMT-3"
				const match = timeZoneName.match(/GMT([+-]\d+)/);
				if (match) {
					return parseInt(match[1], 10) * 60;
				}
				return now.getTimezoneOffset(); // fallback
			}

			// Get offsets
			if($('#webinar_timezone').val()){
				const siteOffset = getTimezoneOffsetMinutes($('#webinar_timezone').val());
				const userOffset = getTimezoneOffsetMinutes(wi.userTimezone);
				// Compare
				if (siteOffset === userOffset) {
					$('#webinar_timezone, .tz-reg-label').hide();
				} else {
					$('#webinar_timezone').val(wi.userTimezone).trigger('change'); // Set detected TZ
				}
			}
			
			if(wi.webinarType == 'live'){

				var data = {
					action  : 'webinarignition_get_tz_time_date',
					id      : wi.webinarId,
					userTimezone  : wi.userTimezone,
				};

                

				// Store Lead - Post
				$.post(wi.ajaxUrl, data, function (resp) {
					$('.dateHeadline').html(resp.date);	
					$('.dateSubHeadline').html(resp.time + " | " + resp.duration);	

				})
			}else{
				$('.eventDate').each(function() {
					if ($(this).hasClass('fixed-type')) {
						var data = {
							action  : 'webinarignition_get_tz_time_date_fixed',
							id      : wi.webinarId,
							userTimezone  : wi.userTimezone,
						};
		
		
						// Store Lead - Post
						$.post(wi.ajaxUrl, data, function (resp) {
							// Replace content of first span
							$('.fixed-web-date').text(resp.date);
									
							// Replace content of second span
							$('.fixed-web-time').text(resp.time);
						})
					  
					}
				  });
				  
			}
            wi.videoFixes();

            // Set Cookie and increment visited status in db.
            wi.trackVisitor();


            // EVENT LISTENERS
            // ----------------

            // Stripe
            if (wi.isPaidWebinar && wi.paymentProvider === 'stripe') {
                $(function() {
                    var $form = jQuery('#stripepayment');
                    wi.setStripeClickEvent($form);
                });
            }

            if (wi.isSigningUpWithFB) {
                wi.handleFacebookSignup();
                return;
            }

            // Listen for registration form submission.
            $(document.body).on('click', '#optinBTN', wi.verifyemailsub);
            $(document.body).on('click', '.verify_now', wi.handleSubmit);

            $(document).on('change', '.optinFormArea input.required.errorField', function() {
                if( $(this).val().length > 0 ) {
                    $(this).removeClass('errorField');
                } else {
                    $(this).addClass('errorField');
                }
            });

            $(document).on('change', '.optinFormArea input.required.errorField:checkbox', function() {
                if( $(this).is(':checked') ) {
                    $(this).removeClass('errorField');
                } else {
                    $(this).addClass('errorField');
                }
            });

            if (wi.scheduleType === 'custom') {
                var testAppearTmr = setInterval(function() {
                    if ($('#webinar_start_date').length) {
                        if (!window.customDateFieldsInit) {
                            window.customDateFieldsInit = true;
                            wi.customDateFields();
                        }
                    } else {
                        window.customDateFieldsInit = false;
                    }
                }, 1000);
            }

        },

        doARSubmit: function(leadId) {
            var target_form = $("#AR-INTEGRATION");
            HTMLFormElement.prototype.submit.call(target_form[0]);
        }
    };
	var playerElement = document.getElementById('wi_ctaVideoPlayer');
if (playerElement) {
    var myPlayer = videojs('wi_ctaVideoPlayer');
} else {
	var myPlayer = null;

}

if (myPlayer) {
    // When player is ready
    myPlayer.ready(function() {
        // Check if there's a saved time in localStorage
        var savedTime = localStorage.getItem('wi_ctaVideoPlayer_time');
        if (savedTime) {
            myPlayer.currentTime(parseFloat(savedTime));
        }

        // Listen for timeupdate and save the current time
        myPlayer.on('timeupdate', function() {
            localStorage.setItem('wi_ctaVideoPlayer_time', myPlayer.currentTime());
        });

        // Optional: Clear the saved time when video ends
        myPlayer.on('ended', function() {
            localStorage.removeItem('wi_ctaVideoPlayer_time');
        });
    });
}
    /*
    * |--------------------------------------------------------------------------
    * | INITIALIZE
    * |--------------------------------------------------------------------------
    * */

    if (typeof(window.webinarignition) == 'undefined') {
        console.log('Something is wrong: window.webinarignition does not exist.');
        return;
    }

    try {
        var wiParsed = 'string' === typeof window.webinarignition ? JSON.parse(window.webinarignition) : window.webinarignition;
        if( typeof webinarignitionTranslations != 'undefined' ){
            wiParsed.translations = webinarignitionTranslations;
        }

    } catch(error) {
        console.log('JSON parse error, could not parse window.webinarignition : ', error);
        return;
    }

    // Object.assign polyfill for IE11
    if (typeof Object.assign != 'function') {
        // Must be writable: true, enumerable: false, configurable: true
        Object.defineProperty(Object, "assign", {
            value: function assign(target, varArgs) { // .length of function is 2
                'use strict';
                if (target == null) { // TypeError if undefined or null
                    throw new TypeError('Cannot convert undefined or null to object');
                }

                var to = Object(target);

                for (var index = 1; index < arguments.length; index++) {
                    var nextSource = arguments[index];

                    if (nextSource != null) { // Skip over if undefined or null
                        for (var nextKey in nextSource) {
                            // Avoid bugs when hasOwnProperty is shadowed
                            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                                to[nextKey] = nextSource[nextKey];
                            }
                        }
                    }
                }
                return to;
            },
            writable: true,
            configurable: true
        });
    }
	$('#webinar_timezone').change(function() {
		var selectedTimezone = $(this).val();	
		$('#timezone_user').val(selectedTimezone);
		if(wi.webinarType == 'live'){
			var data = {
				action  : 'webinarignition_get_tz_time_date',
				id      : wi.webinarId,
				userTimezone  : selectedTimezone,
			};


			// Store Lead - Post
			$.post(wi.ajaxUrl, data, function (resp) {
				$('.dateHeadline').html(resp.date);	
				$('.dateSubHeadline').html(resp.time + " | " + resp.duration);	

			})
		}else{
			console.log('Changing timezone for fixed type webinar');
			console.log(selectedTimezone);
			$('.eventDate').each(function() {
				if ($(this).hasClass('fixed-type')) {

					var data = {
						action  : 'webinarignition_get_tz_time_date_fixed',
						id      : wi.webinarId,
						userTimezone  : selectedTimezone,
					};
	
	
					// Store Lead - Post
					$.post(wi.ajaxUrl, data, function (resp) {
						// Replace content of first span
						$('.fixed-web-date').text(resp.date);
								
						// Replace content of second span
						$('.fixed-web-time').text(resp.time);
					})
				  
				}
			  });
		}
		// Get Dates	
	})
    window.customDateFieldsInit = false;
    var wi = Object.assign(initialWebinarignition, wiParsed);
    window.addEventListener('DOMContentLoaded', wi.init);

})(jQuery);
