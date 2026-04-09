(function( $ ) {
    $(document).ready(function() {	
        $(document.body).on('click', '.wi_upload_media_btn', function() {
            var btn = $(this);
            var container = btn.parents('.inputSection');
            var input = container.find('.inputField');
            var delete_btn = container.find('.wi_delete_media_btn');

            var custom_uploader = wp.media({
                title: webinarignitionTranslations.wpMediaVidTitle,
                library : {
                    // uncomment the next line if you want to attach image to the current post
                    // uploadedTo : wp.media.view.settings.post.id,
                    type : 'video'
                },
                button: {
                    text: webinarignitionTranslations.wpMediaVidButtonText // button label text
                },
                multiple: false // for multiple image selection set to true
            }).on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                var url = attachment.url;

                input.val(url);
                delete_btn.show();
            }).open();
        });
        /**
         * When set this on click listner outside of jQuery document.ready function
         * it do not works properly so best paractice would be first we should let the page loads properly 
         * and then set this click listner on Delete button
         */
        $(document.body).on('click', '.wi_delete_media_btn', function() {
            var btn = $(this);
            var container = btn.parents('.inputSection');
            var input = container.find('.inputField');
    
            btn.hide();
            input.val('');
        });
    });
    

    $(document.body).on('click', '.wi_upload_image_btn', function() {
        var btn = $(this);
        var container = btn.parents('.inputSection');
        var img_holder = container.find('.input_image_holder');
        var input = container.find('.inputField');
        var delete_btn = container.find('.wi_delete_image_btn');

        var custom_uploader = wp.media({
            title: webinarignitionTranslations.wpMediaImgTitle,
            library : {
                // uncomment the next line if you want to attach image to the current post
                // uploadedTo : wp.media.view.settings.post.id,
                type : 'image'
            },
            button: {
                text: webinarignitionTranslations.wpMediaImgButtonText // button label text
            },
            multiple: false // for multiple image selection set to true
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
        var container = btn.parents('.inputSection');
        var img_holder = container.find('.input_image_holder');
        var input = container.find('.inputField');

        btn.hide();
        img_holder.empty();
        input.val('');
    });

    $(document.body).on('click', '#wi_activate_freemius', function() {
        var btn = $(this);

        var data = {action: 'webinarignition_activate_freemius', security: webinarignitionTranslations.ajax_nonce};

        ajaxRequest(data, function() {}, function() {});
    });

    $(document.body).on('change', '#protected_webinar_id', function() {
        var selected = $(this).val();
        var inputFieldTemplateSelect = $('.inputFieldTemplateSelect');

        if (inputFieldTemplateSelect.length) {
            inputFieldTemplateSelect.each(function() {
                $(this).data('webinar-access', selected);
                $(this).trigger('change');
            });
        }
    });

    // $(document.body).on('change', '.inputFieldTemplateSelect', function() {
    //     var select = $(this);
    //     var container = select.parents('.editSection');
    //     var val = select.val();
    //     var url = '';

    //     return;

    //     if ('' !== val.trim()) {
    //         var webinar_access = select.data('webinar-access');
    //         var url_type = webinar_access + '-url';

    //         url = select.find(':selected').data(url_type);
    //     }

    //     var preview = container.find('.webinarPreviewLinkInput');
    //     preview.val(url);

    //     update_custom_template_urls();
    // });

    $(document).ready(function() {
        $(document.body).on('click', '.code-example-copy', function() {
            var $control = $(this);
            var $parent = $control.parents('.code-example');
            var $message = $parent.find('.code-example-copied');
            var $toCopy = $parent.find('.code-example-value');

            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($toCopy.text()).select();
            document.execCommand("copy");
            $temp.remove();

            $toCopy.addClass('copied');
            $control.hide();
            $message.show();

            setTimeout(function() {
                $toCopy.removeClass('copied');
                $control.show();
                $message.hide();
            }, 2000);
        });
    });

    $(document).ready(function(){
        $('.min_sec_mask_field').inputmask({
            mask: "9{1,6}:59",
            definitions: {'5': {validator: "[0-5]"}}
        });  //static mask

        // $('.cp-picker').inputmask({
        //     mask: "RCCCCCC",
        //     definitions: {'C': {validator: "[a-fA-F0-9]"},'R': {validator: "#"}}
        // });  //static mask
        //#b53e3e

        update_custom_template_urls();
    });

    function update_custom_template_urls() {
        var custom_templates_urls = $('.webinarPreviewLinkInput');

        if (custom_templates_urls.length) {
            custom_templates_urls.each(function() {
                var url = $(this).val();
                var page = $(this).data('page');

                var defaults_to_update = $('.' + page + '-webinarPreviewLinkDefaultHolder');
                var share_to_update = $('#' + page + '-shareUrl');

                if (defaults_to_update.length) {
                    defaults_to_update.each(function() {
                        var default_to_update = $(this);
                        var default_url = default_to_update.data('default-href');

                        if ('' == url) {
                            // default_to_update.attr('href', default_url);
                        } else {
                            // default_to_update.attr('href', url);
                        }
                    });
                }

                if (share_to_update.length) {
                    share_to_update.each(function() {
                        var reg_url = '';
                        if(url == '') {
                            reg_url = $(this).data('default-value');
                        } else {
                            reg_url = url;
                        }

                        //Remove preview parameter from "Registation Page URL" input field value on first tab
                        reg_url = reg_url.replace(/\?preview=true/g, '');
                        reg_url = reg_url.replace(/&preview=true/g, '');

                        $(this).val(reg_url);

                        $(this).attr('readonly', false);
                    });
                }



                var custom_to_update = $('.' + page + '-webinarPreviewLinkHolder');
                custom_to_update.attr('href', url);

                var custom_empty_to_update = $('.' + page + '-webinarPreviewLinkEmptyHolder');

                if ('' == url) {
                    custom_to_update.hide();
                    custom_empty_to_update.show();
                } else {
                    custom_empty_to_update.hide();
                    custom_to_update.show();
                }
            });
        }
    }

    function ajaxRequest(data, cb, cbError) {
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: data,
            success: function (response) {
                var decoded;

                try {
                    decoded = JSON.parse(response);
                } catch(err) {
                    console.log(err);
                    decoded = false;
                }

                if (decoded) {
                    if (decoded.success) {
                        if (decoded.message) {
                            alert(decoded.message);
                        }

                        if (decoded.url) {
                            window.location.replace(decoded.url);
                        } else if (decoded.reload) {
                            window.location.reload();
                        }

                        if (typeof cb === 'function') {
                            cb();
                        }
                    } else {
                        if (decoded.message) {
                            alert(decoded.message);
                        }

                        if (typeof cbError === 'function') {
                            cbError();
                        }
                    }
                } else {
                    alert(webinarignitionTranslations.someWrong);
                }
            }
        });
    }
})( jQuery );

document.addEventListener('DOMContentLoaded', async function() {
    const accordions = document.querySelectorAll('.wi_accordeon_section');

    accordions.forEach((accordion) => {
        const controlItems = accordion.querySelectorAll('.wi_accordeon_control_items li');
        const contentItems = accordion.querySelectorAll('.wi_accordeon_item');
        controlItems.forEach((item) => {
            item.addEventListener('click', function() {
                controlItems.forEach((li) => li.classList.remove('wi_accordeon_control_active'));
                this.classList.add('wi_accordeon_control_active');

                const inputTargetId = this.getAttribute('data-input-target');
                const inputElem = accordion.querySelector(`#${inputTargetId}`);
                if (inputElem) {
                    inputElem.value = this.getAttribute('data-value') || '';
                }

                const targetId = this.getAttribute('data-target');
                contentItems.forEach((content) => {
                    content.style.display = content.id === targetId ? 'block' : 'none';
                });
            });
        });

        const initialItem = accordion.querySelector('.wi_accordeon_control_active') || controlItems[0];
        if (initialItem) {
            initialItem.click();
        }
    });

    const selectElement = document.getElementById("videoSdkUsersList");
    const hostList = document.getElementById("videoSdkHosts");
    const hiddenInput = document.querySelector("input[name='video_sdk_hosts']");

    function updateHiddenInput() {
		if(hostList){
			const ids = Array.from(hostList.querySelectorAll("li span")).map(
				(span) => span.dataset.id
			);
			hiddenInput.value = ids.join("|");
		}
    }

    function checkRemoveButtonState() {
		if(hostList){
			const removeButtons = hostList.querySelectorAll(".wi_editable_item_remove");
			removeButtons.forEach((btn) => {
				btn.style.pointerEvents = removeButtons.length === 1 ? "none" : "auto";
				btn.style.opacity = removeButtons.length === 1 ? "0.5" : "1";
			});
		}
    }
	if(selectElement){
		selectElement.addEventListener("change", function () {
			const selectedOption = selectElement.options[selectElement.selectedIndex];
			const userId = selectedOption.value;
			const userName = selectedOption.textContent.trim();
	
			if (userId && !hostList.querySelector(`span[data-id="${userId}"]`)) {
				// Create new LI element
				const newListItem = document.createElement("li");
				newListItem.innerHTML = `
					${userName}
					<span class="wi_editable_item_remove" data-id="${userId}">×</span>
				`;
	
				hostList.appendChild(newListItem);
				selectedOption.disabled = true;
	
				updateHiddenInput();
				checkRemoveButtonState();
			}
	
			selectElement.selectedIndex = 0; // Reset selection
		});
	}
	if(hostList){
		hostList.addEventListener("click", function (event) {
			if (event.target.classList.contains("wi_editable_item_remove")) {
				const userId = event.target.dataset.id;
				event.target.parentElement.remove();
	
				// Enable option back in the select list
				const optionToEnable = selectElement.querySelector(`option[value="${userId}"]`);
				if (optionToEnable) {
					optionToEnable.disabled = false;
				}
	
				updateHiddenInput();
				checkRemoveButtonState();
			}
		});
	}

    // Initialize remove button state on load
    checkRemoveButtonState();
});

