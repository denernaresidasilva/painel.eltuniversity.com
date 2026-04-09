(function($) {
    class CTAManager {
        constructor(containerSelector, templateSelector, existingCTAs = {}) {
            this.$container = $(containerSelector);
            this.template = $(templateSelector).html();
            this.ctaCounter = 0;
            this.existingCTAs = existingCTAs;
            this.nextId = this.getNextAvailableId();
            this.webinarId = $('#webinarignition_console').attr("data-webinar-id") || null;
            this.proNoticeTemplate = $("#pro-cta-notice-template").html();
            this.isPremium = window.WEBINARIGNITION ? window.WEBINARIGNITION.can_use_multi_cta : 0;
            if (!this.webinarId) {
                console.error("Webinar ID is missing!");
                return;
            }
            if (!this.template) {
                console.error("CTA Template not found!");
                return;
            }

            this.init();
        }

        getNextAvailableId() {
            if (!this.existingCTAs || Object.keys(this.existingCTAs).length === 0) {
                return 1;
            }
            const maxId = Math.max(...Object.keys(this.existingCTAs).map(Number));
            return maxId + 1;
        }

        init() {
            this.bindEvents();
            this.loadExistingCTAs();
            this.initToggleSwitches();
            this.initCopyCodeButtons();
        }

       // Proper sequential reindexing after deletion
       reindexCTAs() {
            const $blocks = this.$container.find('.additional_auto_action_item');
            const blocksData = [];
            
            // First collect all existing data
            $blocks.each((index, block) => {
                const $block = $(block);
                const editorId = 'aircodyEditor' + $block.data('cta-id');
                let editorContent = '';
                if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                    editorContent = tinymce.get(editorId).getContent();
                    tinymce.get(editorId).remove();
                } else {
                    editorContent = $block.find('#' + editorId).val();
                }
                const blockData = this.getBlockData($block);
                blockData.response = editorContent;
                // Preserve the air_toggle status - use explicit check
                const toggleStatus = $block.data('air-toggle-status');
                blockData.air_toggle = (toggleStatus !== undefined && toggleStatus !== null) 
                    ? toggleStatus 
                    : 'off';
                console.log(`reindexCTAs: Preserving CTA ${$block.data('cta-id')} air_toggle = ${blockData.air_toggle}`);
                blocksData.push(blockData);
            });
            
            // Clear container
            this.$container.empty();
            
            // Recreate all CTAs with sequential IDs
            blocksData.forEach((data, index) => {
                const newId = index + 1;
                console.log(`reindexCTAs: Creating CTA ${newId} with air_toggle = ${data.air_toggle}`);
                this.addCTA({
                    ...data,
                    ctaId: newId
                });
                setTimeout(() => {
                    const newEditorId = 'aircodyEditor' + newId;
                    if (typeof tinymce !== 'undefined' && tinymce.get(newEditorId)) {
                        tinymce.get(newEditorId).setContent(data.response || '');
                    } else {
                        $newBlock.find('#' + newEditorId).val(data.response || '');
                    }
                }, 100);
            });
            
            // Reinitialize toggles after all CTAs are added
            this.initToggleSwitches();
         
            
            // Update nextId
            this.nextId = $blocks.length + 1;
            
            return $blocks.length;
        }

        // Helper to update all ID/name/class references in a block
        updateBlockReferences($block, oldId, newId) {
            // Update IDs
            $block.find('[id]').each(function() {
                $(this).attr('id', $(this).attr('id').replace(oldId, newId));
            });
            
            $block.find('[for]').each(function() {
                $(this).attr('for', $(this).attr('for').replace(oldId, newId));
            });
            
            // Update names
            $block.find('[name]').each(function() {
                $(this).attr('name', $(this).attr('name').replace(oldId, newId));
            });
            
            // Update classes
            $block.find('[class*="' + oldId + '"]').each(function() {
                $(this).attr('class', $(this).attr('class').replace(oldId, newId));
            });
        }
        

        bindEvents() {
            $('#add-cta').on('click', (e) => {
                e.preventDefault();
                // this.addCTA();
                // Check if user is premium or if they can add more CTAs
                if (this.isPremium == 1 || this.$container.find('.additional_auto_action_item').length === 0) {
                    this.addCTA();
                } else {
                    this.showProNotice();
                }
            });
            
            this.$container.on('click', '.remove_cta', (e) => {
                e.preventDefault();
                // if (this.deleteCTA(e)) {
                //     this.reindexCTAs(); // Only reindex after deletion
                // }
                if (this.isPremium == 1 || this.$container.find('.additional_auto_action_item').length > 1) {
                    this.deleteCTA(e)
                }

            });
            
            this.$container.on('click', '.clone-cta', (e) => {
                e.preventDefault();
                // this.cloneCTA(e);
                if (this.isPremium == 1) {
                    this.cloneCTA(e);
                } else {
                    this.showProNotice();
                }
            });
            
            this.$container.on('change', '.live_webinar_ctas_position_radios', (e) => {
                this.handlePositionChange($(e.target).closest('.additional_auto_action_item'));
            });

            this.$container.on('change', '.live_webinar_ctas_alignment_radios', (e) => {
                this.handleAlignmentChange($(e.target).closest('.additional_auto_action_item'));
            });

            this.$container.on('input', '[id^="air_tab_copy"]', (e) => {
                const $input = $(e.target);
                const ctaId = $input.attr('id').replace('air_tab_copy', '');
                const tabText = $input.val();
                $(`.additional_auto_action_item[data-cta-id="${ctaId}"] .auto_action_desc_holder`).text(tabText);
                $(`.additional_auto_action_item[data-cta-id="${ctaId}"] .cta_position_desc_holder_comma`).text(",");
                if( tabText.trim() === '') {
                    $(`.additional_auto_action_item[data-cta-id="${ctaId}"] .cta_position_desc_holder_comma`).text("");
                }
            });
            
            $('#save-cta').on('click', (e) => {
                $("#saveAirText").text(WEBINARIGNITION.translations.text_saving);
                e.preventDefault();
                // Validate all CTAs before saving
                if (!this.validateCTAs()) {
                    return; // Stop if validation fails
                }

                // First, fetch the latest air_toggle values from the database
                this.fetchLatestAirToggleAndSave();
            });
        }

        fetchLatestAirToggleAndSave() {
            console.log('Fetching latest air_toggle values from database...');
            
            // Fetch latest air_toggle values from the database
            $.ajax({
                url: window.ajaxurl,
                type: 'GET',
                data: {
                    action: 'webinarignition_get_latest_air_toggle',
                    id: this.webinarId,
                    nonce: window.wiRegJS.ajax_nonce
                },
                success: (response) => {
                    if (response.success && response.data.air_toggle_values) {
                        const latestAirToggleValues = response.data.air_toggle_values;
                        console.log('Latest air_toggle values from DB:', latestAirToggleValues);
                        
                        // Update the data attributes with latest values
                        this.$container.find('.additional_auto_action_item').each((i, blockEl) => {
                            const $block = $(blockEl);
                            const ctaId = $block.data('cta-id');
                            
                            // If we have a latest value for this CTA, update the data attribute
                            if (latestAirToggleValues[ctaId] !== undefined) {
                                const oldValue = $block.data('air-toggle-status');
                                const newValue = latestAirToggleValues[ctaId];
                                
                                if (oldValue !== newValue) {
                                    console.log(`CTA ${ctaId}: Updating air_toggle from "${oldValue}" to "${newValue}" (from live page)`);
                                    $block.data('air-toggle-status', newValue);
                                } else {
                                    console.log(`CTA ${ctaId}: air_toggle already up-to-date ("${oldValue}")`);
                                }
                            }
                        });
                        
                        // Now proceed with saving
                        this.saveCtaData();
                    } else {
                        console.warn('Failed to fetch latest air_toggle values, proceeding with save anyway');
                        // If fetch fails, still proceed with save (use current values)
                        this.saveCtaData();
                    }
                },
                error: (xhr, status, error) => {
                    console.error('Error fetching latest air_toggle values:', error);
                    // If fetch fails, still proceed with save (use current values)
                    this.saveCtaData();
                }
            });
        }

        saveCtaData() {
            let data = this.generateData();
            
            // Log the air_toggle status for each CTA being saved
            console.log('Saving CTAs with air_toggle status:', 
                Object.entries(data).map(([key, cta]) => ({
                    ctaId: key, 
                    air_toggle: cta.air_toggle,
                    tab_text: cta.tab_text,
                    cta_position: cta.cta_position
                }))
            );
            
            if (this.isPremium == 1) {
                data = data;
            } else {
                // Get first key
                const firstKey = Object.keys(data)[0];

                // Get the value
                const firstValue = data[firstKey];

                // Combine both key and value
                data = {
                    [firstKey]: firstValue
                };
            }
            
            // Save the data
            $.ajax({
                url: window.ajaxurl,
                type: 'POST',
                data: {
                    action: 'webinarignition_save_air',
                    ctas: JSON.stringify(data),
                    id: this.webinarId,
                    nonce: window.wiRegJS.ajax_nonce
                },
                success: (response) => {
                    var message = WEBINARIGNITION.translations.save_broadcast;

                    $("#wi-notification-message").text(message);
                    $("#wi-notification-message").css("color", "black");
                    $("#wi-close-notification").css("background-color", "#457a1a");
                    $("#wi-close-notification").css("color", "#fff");

                    
                    $("#wi-notification-box").removeClass("wi-hidden").fadeIn();

                    $("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();

                    $("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                },
                error: () => {
                    alert('Error saving CTAs');
                    $("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                }
            });
        }

        showProNotice() {
            const ctaId = this.nextId++;
            const newHTML = this.proNoticeTemplate.replace(/\{\{ctaId\}\}/g, ctaId);
            const $block = $(newHTML).attr('data-cta-id', ctaId);
            this.$container.append($block);
            $block.find('.auto_action_header').trigger('click');
            
            // Show the notice briefly then remove it
            setTimeout(() => {
                $block.fadeOut(500, () => {
                    $block.remove();
                });
            }, 3000);
        }


        // Add this new validation method:
        validateCTAs() {
            let isValid = true;
            const errors = [];
            
            this.$container.find('.additional_auto_action_item').each((index, blockEl) => {
                const $block = $(blockEl);
                const ctaId = $block.data('cta-id');
                const position = $block.find(`input[name="ctaPosition${ctaId}"]:checked`).val();
                const tabName = $block.find('#air_tab_copy' + ctaId).val();
                const isAdvaceIframeActive = $block.find('#isAdvaceIframeActive' + ctaId).val();
                const airAmeliaToggle = $block.find('#airAmeliaToggle' + ctaId).val();
                if (position === 'outer' && !tabName) {
                    const message = WEBINARIGNITION.translations.tabName_required;
                    errors.push(`CTA ${ctaId}: ${message}`);
                    isValid = false;
                    
                    // Highlight the problematic field
                    $block.find('#air_tab_copy' + ctaId)
                        .css('border-color', 'red')
                        .attr('placeholder', 'This field is required!');
                    $("#wi-notification-message").html(errors.join('<br>'));
                    $("#wi-notification-box").removeClass("wi-hidden").fadeIn();
                    $("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();
                    $("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                }

                // Check if content contains any shortcode using a generic regex
				var show_alert = true;
                let editor = tinymce.get('aircodyEditor'+ctaId);
                // let content = $block.find(`#aircodyEditor${ctaId}`).val();
                let contenta_lower = editor ? editor.getContent() : '';

					// Extract all shortcodes
				let shortcodes = [...contenta_lower.matchAll(/\[([a-z_]+)[^\]]*\]/g)].map(match => match[1]);

					// Check for <iframe> tag
				let contains_iframe = /<iframe\b[^>]*>/i.test(contenta_lower);

					// Allowed shortcodes
				let allowed = ['advanced_iframe', 'embedded'];

					// Check if ALL found shortcodes are allowed
				let only_allowed_shortcodes = shortcodes.every(shortcode => allowed.includes(shortcode));

					// Set show_alert based on conditions
				show_alert = !(only_allowed_shortcodes && (contains_iframe || shortcodes.length > 0));
				if (this.checkContentForShortcodesOrIframes(contenta_lower)) show_alert = false;

					
				if (shortcodes.includes("advanced_iframe") && WEBINARIGNITION.show_advance_iframe_message) {
                    var message = WEBINARIGNITION.translations.adv_ifram_plus_not_working_shortcode;
                    errors.push(`CTA ${ctaId}: ${message}`);
                    isValid = false;
					$("#wi-notification-message").text(`CTA ${ctaId}: `+ message);
					$("#wi-notification-message").css("color", "red");
                    $("#wi-close-notification").css("background-color", "red");
                    $("#wi-close-notification").css("color", "#fff");
			
                	$("#wi-notification-box").removeClass("wi-hidden").fadeIn();
                	$("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();

                	var link = WEBINARIGNITION.translations.adv_iframe_activate_link;
                		var pluginLink = WEBINARIGNITION.home_url + '/wp-admin/admin.php?page=webinarignition-dashboard-pricing&trial=paid&coupon=dfskjfhfhje45&hide_coupon=true';
                
                		var anchor = $('<a></a>')
                			.attr('href', pluginLink)
                			.text(link)
                			.attr('target', '_blank')
                			.attr('rel', 'noopener noreferrer');
                	$("#wi-notification-message").append(anchor);
                	$("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                	return;
                }
                if (shortcodes.includes("advanced_iframe") && shortcodes.length > 1) {
                    var message = WEBINARIGNITION.translations.adv_ifram_plus_another_shortcode;
                    errors.push(`CTA ${ctaId}: ${message}`);
                    isValid = false;
                    $("#wi-notification-message").text(`CTA ${ctaId}: `+ message);
                    $("#wi-notification-message").css("color", "red");
                    $("#wi-close-notification").css("background-color", "red");
                    $("#wi-close-notification").css("color", "#fff");
        
                    $("#wi-notification-box").removeClass("wi-hidden").fadeIn();
                    $("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();
                    $("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                    return;
                }

                if (show_alert) {
                	if (isAdvaceIframeActive === "0") {
                        var message = WEBINARIGNITION.translations.adv_iframe_activate;
                        errors.push(`CTA ${ctaId}: ${message}`);
                        isValid = false;
                		$("#wi-notification-message").text("CTA "+ctaId+": "+ message + " ");
                		$("#wi-notification-message").css("color", "red");
                		$("#wi-close-notification").css("background-color", "red");
                		$("#wi-close-notification").css("color", "#fff");
                
                		$("#wi-notification-box").removeClass("wi-hidden").fadeIn();
                		$("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();
                
                		var link = WEBINARIGNITION.translations.adv_iframe_activate_link;
                		var pluginLink = WEBINARIGNITION.home_url + '/wp-admin/plugin-install.php?tab=plugin-information&plugin=advanced-iframe';
                
                		var anchor = $('<a></a>')
                			.attr('href', pluginLink)
                			.text(link)
                			.attr('target', '_blank')
                			.attr('rel', 'noopener noreferrer');
                
                		$("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                		$("#wi-notification-message").append(anchor);
                		return;
                
                	} else {
                		if (airAmeliaToggle !== 'on') {
                            var message = WEBINARIGNITION.translations.adv_iframe_on;
                            errors.push(`CTA ${ctaId}: ${message}`);
                            isValid = false;
                			$("#wi-notification-message").text(`CTA ${ctaId}: `+message);
                			$("#wi-notification-message").css("color", "red");
                			$("#wi-close-notification").css("background-color", "red");
                			$("#wi-close-notification").css("color", "#fff");
                
                			$("#wi-notification-box").removeClass("wi-hidden").fadeIn();
                			$("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();
                			$("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                			return;
                		}
                	}
                } 
            });
            
            if (!isValid) {
                // Show all errors in an alert
                // alert('Please fix the following errors:\n\n' + errors.join('\n'));
               
                // $("#wi-notification-message").html(errors.join('<br>'));
                // $("#wi-notification-box").removeClass("wi-hidden").fadeIn();
                // $("#wi-notification-overlay").removeClass("wi-hidden").fadeIn();
                // $("#saveAirText").text(WEBINARIGNITION.translations.save_on_air);
                //  // ✅ stop the entire function
                
                // Scroll to the first error
                // this.$container.find('input[style*="border-color: red"]').first().get(0)?.scrollIntoView({
                //     behavior: 'smooth',
                //     block: 'center'
                // });
            }
            
            return isValid;
        }

        addCTA(settings = {}) {
            // this.ctaCounter++;
            let ctaId;
            if (settings.ctaId){
                ctaId= settings.ctaId ;
            }else{
                ctaId = this.nextId++;
            }

            const newHTML = this.template.replace(/\{\{ctaId\}\}/g, ctaId);
            
            const $block = $(newHTML).attr('data-cta-id', ctaId);

            // Store the air_toggle status from settings
            // Use explicit check to differentiate between undefined/null and the string 'off'
            const airToggleStatus = (settings.air_toggle !== undefined && settings.air_toggle !== null) 
                ? settings.air_toggle 
                : 'off';
            $block.data('air-toggle-status', airToggleStatus);
            
            console.log(`addCTA: CTA ${ctaId} created with air_toggle = ${airToggleStatus} (from settings: ${settings.air_toggle}), position = ${settings.cta_position || 'default'}`);
            
            // Initialize toggles for new block
            this.initToggleSwitches($block);
            
            // Apply settings if provided
            if (Object.keys(settings).length > 0) {
                this.applySettingsToBlock($block, settings);
            } else{
                $block.find('#TabPos' + ctaId).prop('checked', true);
            }

            this.$container.append($block);
            this.handlePositionChange($block);
            this.handleAlignmentChange($block);
            this.initWpEditor($block);
            this.initCopyCodeButtons();
            if (settings.tab_text) {
                $block.find('.cta_position_desc_holder_comma').text(",");
                $block.find('.auto_action_desc_holder').text(settings.tab_text);
            }

            if (this.isPremium == 0 && this.$container.find('.additional_auto_action_item').length === 1) {
                $block.find('.clone-cta, .remove_cta').hide();
            }
            $block.find('.auto_action_header').trigger('click');
            // this.toggleAddButton();
            
            return $block;
        }

        deleteCTA(e) {
            const $block = $(e.target).closest('.additional_auto_action_item');
            if (confirm('Are you sure you want to delete this CTA?')) {
                // const deletedData = this.getBlockData($block);
                const editorId = 'aircodyEditor' + $block.data('cta-id');
                if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                    tinymce.get(editorId).remove();
                }
                $block.remove();
                this.reindexCTAs();

                return true;
            }
            return false;
        }

        cloneCTA(e) {
            const $original = $(e.target).closest('.additional_auto_action_item');
            const settings = this.getBlockData($original);
            settings.ctaId = this.nextId++; // New unique ID for clone
            // Preserve the air_toggle status from the original
            const originalToggle = $original.data('air-toggle-status');
            settings.air_toggle = (originalToggle !== undefined && originalToggle !== null) 
                ? originalToggle 
                : 'off';
            console.log(`cloneCTA: Original CTA had air_toggle = ${originalToggle}, clone will have ${settings.air_toggle}`);
            // Get editor content if it exists
            const originalEditorId = 'aircodyEditor' + $original.data('cta-id');
            if (typeof tinyMCE !== 'undefined' && tinyMCE.get(originalEditorId)) {
                settings.contenta = tinyMCE.get(originalEditorId).getContent();
            }
            this.addCTA(settings);
        }

        applySettingsToBlock($block, settings) {
            // Main toggle
            // if (settings.air_toggle === "on") {
            //     $block.find('#airToggle' + $block.data('cta-id')).val('on');
            //     $block.find('#airToggleLableOn' + $block.data('cta-id')).addClass('selected');
            //     $block.find('#airToggleLableOff' + $block.data('cta-id')).removeClass('selected');
            // }
            
            // Iframe toggle
            if (settings.air_amelia_toggle === "on") {
                $block.find('#airAmeliaToggle' + $block.data('cta-id')).val('on');
                $block.find('#airAmeliaToggleLableOn' + $block.data('cta-id')).addClass('selected');
                $block.find('#airAmeliaToggleLableOff' + $block.data('cta-id')).removeClass('selected');
            }
            
            // Position
            if (settings.cta_position) {
                const position = settings.cta_position;
                const ctaId = $block.data('cta-id');
                
                // Set the radio button
                $block.find(`input[name="ctaPosition${ctaId}"][value="${position}"]`).prop('checked', true);
                
                // Update label active states
                $block.find('.live_webinar_ctas_position_label').removeClass('active');
                
                // Add active class to the correct label
                if (position === 'overlay') {
                    $block.find('#OverlayLabel' + ctaId).addClass('active');
                } else { // outer
                    $block.find('#TabLabel' + ctaId).addClass('active');
                }
            }
            
            // Tab name
            if (settings.tab_text) {
                $block.find('#air_tab_copy' + $block.data('cta-id')).val(settings.tab_text);
                $block.find('.cta_position_desc_holder_comma').text(",");
                $block.find('.auto_action_desc_holder').text(settings.tab_text);
            }
            
            // Button settings
            if (settings.button_text) {
                $block.find('#air_btn_copy' + $block.data('cta-id')).val(settings.button_text);
            }
            if (settings.button_url) {
                $block.find('#air_btn_url' + $block.data('cta-id')).val(settings.button_url);
            }
            if (settings.button_color) {
                $block.find('#air_btn_color' + $block.data('cta-id')).val(settings.button_color);
            }
            
            // Broadcast settings
            if (settings.bg_transparency) {
                $block.find('#air_broadcast_message_bg_transparency' + $block.data('cta-id')).val(settings.bg_transparency);
            }
            if (settings.box_width) {
                $block.find('#air_broadcast_message_width' + $block.data('cta-id')).val(settings.box_width);
            }
            if (settings.box_alignment) {
                $block.find(`input[name="alignment${$block.data('cta-id')}"][value="${settings.box_alignment}"]`).prop('checked', true);
            }

            //response
            if(settings.response){
                const editorId = 'aircodyEditor' + $block.data('cta-id');
                if (typeof tinyMCE !== 'undefined' && tinyMCE.get(editorId)) {
                    tinyMCE.get(editorId).setContent(settings.response);
                } else {
                    $block.find('#' + editorId).val(settings.response);
                }
            }
        }

        getBlockData($block) {
            const ctaId = $block.data('cta-id');
            this.initWpEditor($block);
            let editor = tinymce.get('aircodyEditor'+ctaId);
            
            // Try to get air_toggle from multiple sources in priority order:
            // 1. Data attribute (most reliable)
            // 2. Hidden input (if it exists)
            // 3. Default to 'off'
            let airToggleStatus = $block.data('air-toggle-status');
            
            // If data attribute doesn't exist, try hidden input
            if (!airToggleStatus) {
                const hiddenInput = $block.find('#airToggle' + ctaId);
                if (hiddenInput.length) {
                    airToggleStatus = hiddenInput.val();
                }
            }
            
            // Final fallback
            if (!airToggleStatus) {
                airToggleStatus = 'off';
            }
            
            console.log(`getBlockData for CTA ${ctaId}: air_toggle = ${airToggleStatus}`);
            
            return {
                ctaId: ctaId,
                air_toggle: airToggleStatus, // Preserve the current on/off status
                isAdvaceIframeActive: $block.find('#isAdvaceIframeActive' + ctaId).val(),
                air_amelia_toggle: $block.find('#airAmeliaToggle' + ctaId).val(),
                cta_position: $block.find(`input[name="ctaPosition${ctaId}"]:checked`).val(),
                tab_text: $block.find('#air_tab_copy' + ctaId).val(),
                isTabNameAvailable: $block.find('#isTabNameAvailable' + ctaId).val(),
                button_text: $block.find('#air_btn_copy' + ctaId).val(),
                button_url: $block.find('#air_btn_url' + ctaId).val(),
                button_color: $block.find('#air_btn_color' + ctaId).val(),
                box_width: $block.find('#air_broadcast_message_width' + ctaId).val(),
                bg_transparency: $block.find('#air_broadcast_message_bg_transparency' + ctaId).val(),
                box_alignment: $block.find(`input[name="alignment${ctaId}"]:checked`).val() || 'center',
                response: editor ? editor.getContent() : '' // Example for editor content
            };
        }

        generateData() {
            const data = {};
            
            this.$container.find('.additional_auto_action_item').each((i, blockEl) => {
                const $block = $(blockEl);
                let ctaId = $block.data('cta-id');

                let editor = tinymce.get('aircodyEditor'+ctaId);
                
                // Try to get air_toggle from multiple sources in priority order:
                // 1. Data attribute (most reliable)
                // 2. Hidden input (if it exists)
                // 3. Default to 'off'
                let airToggleStatus = $block.data('air-toggle-status');
                
                // If data attribute doesn't exist, try hidden input
                if (!airToggleStatus) {
                    const hiddenInput = $block.find('#airToggle' + ctaId);
                    if (hiddenInput.length) {
                        airToggleStatus = hiddenInput.val();
                    }
                }
                
                // Final fallback
                if (!airToggleStatus) {
                    airToggleStatus = 'off';
                }
                
                console.log(`generateData for CTA ${ctaId}: air_toggle = ${airToggleStatus}`);
                
                // Get the box_width value and log it for debugging
                const boxWidth = $block.find('#air_broadcast_message_width' + ctaId).val();
                console.log(`generateData for CTA ${ctaId}: box_width = ${boxWidth}`);
                
                // Get all current values from the UI
                // Use ctaId as the key instead of index to ensure correct mapping
                data[ctaId] = {
                    ctaId: ctaId,
                    air_toggle: airToggleStatus, // Preserve the current on/off status
                    isAdvaceIframeActive: $block.find('#isAdvaceIframeActive' + ctaId).val(),
                    air_amelia_toggle: $block.find('#airAmeliaToggle' + ctaId).val(),
                    cta_position: $block.find(`input[name="ctaPosition${ctaId}"]:checked`).val() || 'outer',
                    tab_text: $block.find('#air_tab_copy' + ctaId).val(),
                    isTabNameAvailable: $block.find('#isTabNameAvailable' + ctaId).val(),
                    button_text: $block.find('#air_btn_copy' + ctaId).val(),
                    button_url: $block.find('#air_btn_url' + ctaId).val(),
                    button_color: $block.find('#air_btn_color' + ctaId).val(),
                    box_width: boxWidth,
                    bg_transparency: $block.find('#air_broadcast_message_bg_transparency' + ctaId).val(),
                    box_alignment: $block.find(`input[name="alignment${ctaId}"]:checked`).val() || 'center',
                    // Add any other fields you need to capture
                    response: editor ? editor.getContent() : '' // Example for editor content
                };
            });
            
            return data;
        }

        loadExistingCTAs() {
            if (!this.existingCTAs || Object.keys(this.existingCTAs).length === 0) {
                return;
            }
            
            console.log('Loading existing CTAs with air_toggle status:', 
                Object.entries(this.existingCTAs).map(([ctaId, ctaData]) => ({
                    ctaId: ctaId,
                    air_toggle: ctaData.air_toggle,
                    cta_position: ctaData.cta_position,
                    tab_text: ctaData.tab_text
                }))
            );
            
            Object.entries(this.existingCTAs).forEach(([ctaId, ctaData]) => {
                console.log(`Loading CTA ${ctaId}: air_toggle = ${ctaData.air_toggle}, position = ${ctaData.cta_position}`);
                this.addCTA({
                    ctaId: ctaId,
                    ...ctaData
                });
                
                // Verify the data attribute was set correctly after adding
                const $addedBlock = this.$container.find(`.additional_auto_action_item[data-cta-id="${ctaId}"]`);
                const verifyStatus = $addedBlock.data('air-toggle-status');
                console.log(`Verified CTA ${ctaId} after adding: data attribute = ${verifyStatus}`);
            });
        }

        handlePositionChange($block) {
            const ctaId = $block.data('cta-id');
            const $radio = $block.find(`input[name="ctaPosition${ctaId}"]:checked`);
            const position = $radio.val();
            // const position = $block.find(`input[name="ctaPosition${$block.data('cta-id')}"]:checked`).val();

            const airToggleBefore = $block.data('air-toggle-status');
            console.log(`handlePositionChange for CTA ${ctaId}: position = ${position}, air_toggle before = ${airToggleBefore}`);

            if (position === 'overlay') {
                $block.find('.console-if-overlay-container' + $block.data('cta-id')).show();
                $block.find('.console-if-outer-container' + $block.data('cta-id')).hide();
            } else {
                $block.find('.console-if-overlay-container' + $block.data('cta-id')).hide();
                $block.find('.console-if-outer-container' + $block.data('cta-id')).show();
            }

             // Update label active states
            $block.find('.live_webinar_ctas_position_label').removeClass('active');
            if (position === 'overlay') {
                $block.find('#OverlayLabel' + ctaId).addClass('active');
                $block.find('.cta_position_desc_holder').text(position);
            } else {
                $block.find('#TabLabel' + ctaId).addClass('active');
                $block.find('.cta_position_desc_holder').text('Sidebar');

            }
            
            const airToggleAfter = $block.data('air-toggle-status');
            console.log(`handlePositionChange for CTA ${ctaId}: air_toggle after = ${airToggleAfter}`);
        }

        handleAlignmentChange($block) {
            const ctaId = $block.data('cta-id');
            const $alignmentRadio = $block.find(`input[name="alignment${ctaId}"]:checked`);
            const ctaAlignment = $alignmentRadio.val();
            $block.find('.live_webinar_ctas_alignment_label').removeClass('active');
            if (ctaAlignment === 'center') {
                $block.find('#centerLabel' + ctaId).addClass('active');
            } else if (ctaAlignment === 'flex-start') {
                $block.find('#LeftLabel' + ctaId).addClass('active');
            } else{
                $block.find('#rightLabel' + ctaId).addClass('active');
            }
        }

        initToggleSwitches($container = null) {
            const $context = $container || $(document);
            
            // $context.find('.airToggleO').off('click').on('click', function() {
            //     const $this = $(this);
            //     const isOn = $this.hasClass('cb-enable');
            //     const $container = $this.closest('.airSwitchRight');
                
            //     $this.addClass('selected').siblings('.airToggleO').removeClass('selected');
            //     $container.find('input[type="hidden"][id^="airToggle"]').val(isOn ? 'on' : 'off');
            // });
            
            // Iframe toggle switch
            $context.find('.airAmeliaToggleO').off('click').on('click', function() {
                const $this = $(this);
                const isOn = $this.hasClass('cb-enable');
                const $container = $this.closest('.airSwitchRight');
                
                // Update visual state
                $this.addClass('selected').siblings('.airAmeliaToggleO').removeClass('selected');
                
                // Update both hidden inputs
                $container.find('#airAmeliaToggle' + $this.closest('.additional_auto_action_item').data('cta-id')).val(isOn ? 'on' : 'off');
                // $container.find('#isAdvaceIframeActive' + $this.closest('.additional_auto_action_item').data('cta-id')).val(isOn ? '1' : '0');
            });
        }

        initWpEditor($block) {
            const ctaId = $block.data('cta-id');
            const editorId = 'aircodyEditor' + ctaId;
            const $editor = $block.find('#' + editorId);
            if (!$editor.length) {
                console.error('Editor container not found:', editorId);
                return;
            }

            if (typeof wp === 'undefined' || typeof wp.editor === 'undefined') {
                console.error('WordPress editor dependencies not loaded');
                return;
            }

             // Ensure wp.editor is available
            if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                tinymce.get(editorId).remove();
            }

            wp.editor.initialize(editorId,
                {
                tinymce: {
                    height: 200,
                    teeny: true,
                    mode: 'text/html', 
                    wpautop: true,
                    menubar: false, // Hide top menubar
                    branding: false, // Hide "Powered by TinyMCE"
                    statusbar: false, // Hide bottom status bar
                    plugins: "lists, link, image, media, paste, textcolor, fullscreen",
                    // Pass custom dropdown items from PHP
                    custom_dropdown_items: window.WEBINARIGNITION && window.WEBINARIGNITION.custom_dropdown_items ? window.WEBINARIGNITION.custom_dropdown_items : null,
                            
                    toolbar1: "styleselect custom_dropdown | bold italic underline | fontselect | forecolor | backcolor | bullist numlist | table | link | fullscreen | code | help",
                    
                    toolbar2: "alignleft aligncenter alignright alignjustify | outdent indent | hr | insertdatetime | preview | wp_page | wp_more",
                    
                    font_formats: "Arial=arial,helvetica,sans-serif;Courier New=courier new,courier,monospace;Georgia=georgia,palatino,serif;Tahoma=tahoma,arial,helvetica,sans-serif;Times New Roman=times new roman,times,serif;Verdana=verdana,geneva,sans-serif",
                    
                    style_formats: [
                        { title: 'Paragraph', block: 'p' },
                        { title: 'Heading 1', block: 'h1' },
                        { title: 'Heading 2', block: 'h2' },
                        { title: 'Heading 3', block: 'h3' },
                        { title: 'Heading 4', block: 'h4' },
                        { title: 'Quote', block: 'blockquote' },
                        { title: 'Red Text', inline: 'span', styles: { color: 'red' }},
                        { title: 'Blue Background', inline: 'span', styles: { backgroundColor: 'lightblue' }},
                    ],
             
                    setup: function(editor) {
                        // Get dynamic values from editor settings or use defaults
                        var dynamicValues = [];
                        var setting = editor.settings.custom_dropdown_items;
                        console.log("Live CTA Manager - setting:", setting);
                        
                        if (setting) {
                            // Case: string (JSON)
                            if (typeof setting === 'string') {
                                try {
                                    dynamicValues = JSON.parse(setting);
                                } catch (e) {
                                    console.warn('custom_dropdown_items: failed to JSON.parse', e);
                                }
                            }
                            // Case: already an array
                            else if (Array.isArray(setting)) {
                                dynamicValues = setting;
                            }
                            // Case: object (numeric keys) -> convert to array
                            else if (typeof setting === 'object') {
                                dynamicValues = Object.keys(setting).map(function(k) {
                                    return setting[k];
                                });
                            }
                        }

                        // Fallback (only used if nothing valid found)
                        if (!dynamicValues || dynamicValues.length === 0) {
                            dynamicValues = [
                                { text: 'Email Address', value: '{EMAIL}' },
                                { text: 'Participation Reason', value: '{REASON}' },
                                { text: 'Title/Salutation', value: '{SALUTATION}' },
                                { text: 'Webinar Link', value: '{LINK}' },
                                { text: 'Date', value: '{DATE}' },
                                { text: 'Title', value: '{TITLE}' },
                                { text: 'Host', value: '{HOST}' },
                                { text: 'Full Name', value: '{FULLNAME}' },
                                { text: 'First Name', value: '{FIRSTNAME}' },
                                { text: 'Last Name', value: '{LASTNAME}' },
                                { text: 'Phone No', value: '{PHONENUM}' },
                                { text: 'Custom Field1', value: '{CUSTOM1}' },
                                { text: 'Custom Field2', value: '{CUSTOM2}' },
                                { text: 'Custom Field3', value: '{CUSTOM3}' },
                                { text: 'Custom Field4', value: '{CUSTOM4}' },
                                { text: 'Custom Field5', value: '{CUSTOM5}' },
                                { text: 'Custom Field6', value: '{CUSTOM6}' },
                                { text: 'Custom Field7', value: '{CUSTOM7}' },
                                { text: 'Custom Field15', value: '{CUSTOM15}' },
                                { text: 'Custom Field16', value: '{CUSTOM16}' },
                                { text: 'Custom Field17', value: '{CUSTOM17}' },
                                { text: 'Custom Field18', value: '{CUSTOM18}' }
                            ];
                        }

                        // Add custom dropdown button
                        editor.addButton('custom_dropdown', {
                            type: 'listbox',
                            text: 'Insert Placeholder',
                            icon: false,
                            onselect: function() {
                                editor.insertContent(this.value());
                                this.value(null); // reset after selection
                            },
                            values: dynamicValues
                        });

                        editor.on('init', function() {
                            // Editor is fully initialized
                            const content = $editor.val();
                            if (content) {
                                editor.setContent(content);
                            }
                        });
                    }
                },
                quicktags: true,
                mediaButtons: true,
                }
            );

            $block.data('editorId', editorId);
        }

        initCopyCodeButtons(selector = ".copy-code") {
            $(selector).click(function () {
                var textToCopy = $(this).data("code");
        
                // Copy text to clipboard
                var tempInput = $("<input>");
                $("body").append(tempInput);
                tempInput.val(textToCopy).select();
                document.execCommand("copy");
                tempInput.remove();
        
                // Change text & style
                $(this).html("Copied!");
                $(this).css({
                    "color": "#ffffff",
                    "background": "#4CAF50",
                    "transition": "background 0.3s ease-in"
                });
        
                // Restore original text after timeout
                var element = $(this);
                setTimeout(function () {
                    element.css({
                        "background": "#ededed",
                        "color": "#4c4c4c",
                        "transition": "background 0.2s ease-in"
                    });
                    element.html(element.data("code")); // Restore original text
                }, 1300);
            });
        }

        checkContentForShortcodesOrIframes(contenta_lower) {
            // Regular expressions to check for WordPress shortcodes and iframe tags
            const shortcodeRegex = /\[[^\]]+\]/; // Matches anything inside []
            const iframeRegex = /<iframe[\s\S]*?>[\s\S]*?<\/iframe>/i; // Matches iframe tags
            
            // Check if content contains either shortcodes or iframes
            const hasShortcode = shortcodeRegex.test(contenta_lower);
            const hasIframe = iframeRegex.test(contenta_lower);
            
            // Set variable to false if neither is found
            const containsNeither = !hasShortcode && !hasIframe;
            
            return containsNeither;
        }
    }

    $(document).ready(function($) {
        if (window.WEBINARIGNITION.webinar_page !== "console") {
            return; // Exit if not a live webinar
        }
        const ctaManager = new CTAManager('#cta-container', '#cta-template', existingCTAs);
        $(".copy-code").css("cursor", "pointer");
    });
})(jQuery);