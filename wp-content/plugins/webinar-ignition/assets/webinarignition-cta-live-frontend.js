(function ($) {
    // webinar-cta-manager.js
    class WebinarCTAManager {
        constructor(options) {
            this.defaults = {
                webinar: {},
                nonce: '',
                lead_id: '',
                ip: '',
                isPreview: false,
                showNotifications: true,
                socketEnabled: true,
                pollingInterval: 2000
            };
            
            this.settings = $.extend({}, this.defaults, options);
            this.displayedCTAs = {};
            this.pollingInterval = null;
            this.socket = null;
            this.connectionState = 'disconnected';
            this.connectionTimeout = null;
            this.reconnectAttempts = 0;
            this.maxReconnectAttempts = 5;
            
            this.init();
        }
        
        init() {
            if (!this.shouldInitialize()) return;
            
            this.cleanSessionStorage();
            this.setupEventHandlers();
            this.initializeCTAControls();
            
            // If no MainHash present yet, fetch CTAs from backend
            try {
                const mainHash = this.getMainHash && this.getMainHash();
                if (!mainHash) {
                    this.fetchLatestCTAs();
                }
            } catch (e) {}
            
            // Check if third party server is enabled, otherwise use polling
            // console.log('Third party server enabled:', window.WEBINARIGNITION);
            if (window.WEBINARIGNITION?.enable_third_party_server !== "1") {
                // console.log('Third party server disabled, using polling mechanism');
                this.fallbackToPolling();
                
            } else {
                    // Try socket.io first, fallback to polling if it fails
                // console.log('Third party server enabled: socket connection');
                this.startSocketConnection();
            }
        }
        
        shouldInitialize() {
            const { webinar } = this.settings;
            return (
                webinar.hasOwnProperty('webinar_date') && 
                webinar.hasOwnProperty('live_stats') &&
                webinar.webinar_date !== 'AUTO' &&
                webinar.live_stats !== 'disabled'
            );
        }
        
        cleanSessionStorage() {
            for (let i = 0; i < sessionStorage.length; i++) {
                const key = sessionStorage.key(i);
                if (key.startsWith('hash')) {
                    sessionStorage.removeItem(key);
                    i--;
                }
            }
        }
        
        setupEventHandlers() {
            // Remove any existing handlers to prevent duplicates
            this.removeEventHandlers();
            
            this.setupToggleBarHandler();
            this.setupSettingsIconHandler();
            this.setupToggleButtonHandler();
            this.setupReloadIframeHandler();
            $(document).on('shown.bs.tab', '#webinarTabs a[data-toggle="tab"]', () => {
                if (typeof window.setWebinarPageDimensions === 'function') {
                  window.setWebinarPageDimensions();
                }
            });
        }

        removeEventHandlers() {
            $('body').off('click', '.cta-status-bar');
            $('body').off('click', '.settings-icon-air');
            $(document).off('click', '.toggle-btn');
            $(document).off('click', '.cta-reload-btn');
        }
        
        setupToggleBarHandler() {
            $('body').on('click', '.cta-status-bar', () => {
                let upperBar = $('#cta-controls-bar');
                let icon = $('#sidebarToggleIconU');
                
                if (upperBar.hasClass('bar-closed')) {
                    this.openControlBar(upperBar, icon);
                } else {
                    this.closeControlBar(upperBar, icon);
                }
            });
        }
        
        openControlBar(upperBar, icon) {
            upperBar.removeClass('bar-closed').animate({
                height: upperBar.get(0).scrollHeight + 'px',
                opacity: 1
            }, 300);
            
            icon.html(
                '<path d="M484.136 328.473 264.988 109.329c-5.064-5.064-11.816-7.844-19.172-7.844-7.208 0-13.964 2.78-19.02 7.844L7.852 328.265C2.788 333.333 0 340.089 0 347.297s2.784 13.968 7.852 19.032l16.124 16.124c5.064 5.064 11.824 7.86 19.032 7.86s13.964-2.796 19.032-7.86l183.852-183.852 184.056 184.064c5.064 5.06 11.82 7.852 19.032 7.852 7.208 0 13.96-2.792 19.028-7.852l16.128-16.132c10.488-10.492 10.488-27.568 0-38.06z" fill="#000000" opacity="1" data-original="#000000" class=""></path>'
            );
        }
        
        closeControlBar(upperBar, icon) {
            upperBar.animate({
                height: '0px',
                opacity: 0
            }, 300, () => {
                upperBar.addClass('bar-closed');
            });
            
            icon.html(
                '<path d="M225.923 354.706c-8.098 0-16.195-3.092-22.369-9.263L9.27 151.157c-12.359-12.359-12.359-32.397 0-44.751 12.354-12.354 32.388-12.354 44.748 0l171.905 171.915 171.906-171.909c12.359-12.354 32.391-12.354 44.744 0 12.365 12.354 12.365 32.392 0 44.751L248.292 345.449c-6.177 6.172-14.274 9.257-22.369 9.257z" fill="#000000" opacity="1" data-original="#000000" class=""></path>'
            );
        }
        
        applyIframeVersion(containerSelector, version) {
			try {
				if (!version) return;
				var $container = $(containerSelector);
				if (!$container || !$container.length) return;
				$container.find('iframe').each(function() {
					var $iframe = $(this);
					var src = $iframe.attr('src') || '';
					if (!src) return;
					var newSrc = src.replace(/[?&]wi_ver=[^&]*/, '');
					var hasQuery = newSrc.indexOf('?') !== -1;
					newSrc += (hasQuery ? '&' : '?') + 'wi_ver=' + encodeURIComponent(version);
					if (newSrc !== src) {
						$iframe.attr('src', newSrc);
					}
				});
			} catch(err) {}
		}
        setupSettingsIconHandler() {
            $('body').on('click', '.settings-icon-air', (e) => {
                e.preventDefault();
                window.open(`${this.settings.webinar.webinar_permalink}?console`, '_blank');
            });
        }
        
        setupToggleButtonHandler() {
            $(document).on('click', '.toggle-btn', (e) => {
                const button = $(e.target).closest('.toggle-btn');
                // Removed wi_webinar_refresh trigger to prevent content refresh on toggle

                const ctaId = button.closest('.cta-toggle').data('cta-id');
                const isCurrentlyActive = button.hasClass('active');
                console.log("Current active: ", isCurrentlyActive);
                const newState = isCurrentlyActive ? 'off' : 'on';
        
                console.log("CTA status value: ", newState);
                
                this.updateCTAState(ctaId, newState, button);
            });

            // Handle hide-show button clicks
            $(document).on('click', '.hide-show-cta-btn', (e) => {
                e.preventDefault();
                const button = $(e.target).closest('.hide-show-cta-btn');
                const ctaToggle = button.closest('.cta-toggle');
                const toggleBtn = ctaToggle.find('.toggle-btn');
                const ctaId = ctaToggle.data('cta-id');
                
                // Trigger the same logic as the toggle button
                const isCurrentlyActive = toggleBtn.hasClass('active');
                const newState = isCurrentlyActive ? 'off' : 'on';
                
                this.updateCTAState(ctaId, newState, toggleBtn);
            });
        }
        
        setupReloadIframeHandler() {
            $(document).on('click', '.cta-reload-btn', (e) => {
                e.preventDefault();
                const button = $(e.target).closest('.cta-reload-btn');
                const ctaToggle = button.closest('.cta-toggle');
                const ctaId = ctaToggle.data('cta-id');
                
                if (!ctaId) return;
                
                // First, bump version on server so all clients will pick it up via polling
                try {
                    $.ajax({
                        url: ajaxurl,
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'webinarignition_bump_cta_version',
                            security: this.settings.nonce,
                            id: this.settings.webinar.id,
                            cta_id: ctaId
                        },
                        success: (resp) => {
                            if (resp && resp.success && resp.data && resp.data.iframe_ver) {
                                const ver = resp.data.iframe_ver;
                                const ctaPosition = resp.data.cta_position;
                                console.log('_____________________ctaPosition', ctaPosition);
                                // Apply iframe version to overlay CTAs (even if hidden)
                                const overlayCTA = $(`#overlayCta${ctaId}`);
                                const orderBTN = $(`#orderBTN${ctaId}`);
                                const sidebarCTA = $(`#tab-new-tab-content${ctaId}`);
                                
                                // Check if overlay CTA exists (reload even if hidden)
                                if (overlayCTA.length && orderBTN.length) {
                                    console.log('_____________________applyIframeVersion overlayCTA', ver);
                                    this.applyIframeVersion(`#orderBTNCopy${ctaId}`, ver);
                                    
                                    // When overlay CTA is reloaded, unhide it if it was hidden
                                    const showIcon = $(`.cta-overlay-show-icon[data-cta-id="${ctaId}"]`);
                                    if (showIcon.length && showIcon.is(':visible')) {
                                        // CTA was hidden, unhide it after reload
                                        orderBTN.show();
                                        showIcon.hide();
                                    }
                                }
                                
                                // Check if sidebar CTA exists (reload even if not visible in tab)
                                if (sidebarCTA.length && ctaPosition === 'outer') {
                                    console.log('_____________________applyIframeVersion sidebarCTA', ver);
                                    this.applyIframeVersion(`#tab-new-tab-content${ctaId} .orderBTNCopy`, ver);
                                    
                                    // Activate the reloaded CTA tab if sidebar is visible
                                    this.activateSidebarCTATab(ctaId);
                                }
                                
                                if (window.liveCtas && window.liveCtas[ctaId]) {
                                    window.liveCtas[ctaId].iframe_ver = ver;
                                }
                            }
                        }
                    });
                } catch(err) {
                    console.error('Error bumping CTA version:', err);
                }
                
                try {
                    // Overlay CTA iframes - reload even if hidden
                    const overlayCTA = $(`#overlayCta${ctaId}`);
                    const orderBTN = $(`#orderBTN${ctaId}`);
                    // Check if overlay CTA exists (reload even if hidden)
                    if (overlayCTA.length && orderBTN.length) {
                        $(`#orderBTNCopy${ctaId} iframe`).each(function() {
                            const $iframe = $(this);
                            const currentSrc = $iframe.attr('src') || '';
                            if (!currentSrc && this.contentWindow) {
                                try { 
                                    this.contentWindow.location.reload(); 
                                } catch(ex) {
                                    console.error('Error reloading iframe content window:', ex);
                                }
                                return;
                            }
                            if (currentSrc) {
                                const hasQuery = currentSrc.indexOf('?') !== -1;
                                const ts = Date.now();
                                let newSrc = currentSrc.replace(/[?&]wi_reload=\d+/, '');
                                newSrc += (hasQuery ? '&' : '?') + 'wi_reload=' + ts;
                                $iframe.attr('src', newSrc);
                            }
                        });
                        
                        // When overlay CTA is reloaded, unhide it if it was hidden
                        const showIcon = $(`.cta-overlay-show-icon[data-cta-id="${ctaId}"]`);
                        if (showIcon.length && showIcon.is(':visible')) {
                            // CTA was hidden, unhide it after reload
                            orderBTN.show();
                            showIcon.hide();
                        }
                    }
                    
                    // Sidebar/tab CTA iframes - reload if exists
                    const sidebarCTA = $(`#tab-new-tab-content${ctaId}`);
                    if (sidebarCTA.length) {
                        $(`#tab-new-tab-content${ctaId} .orderBTNCopy iframe`).each(function() {
                            const $iframe = $(this);
                            const currentSrc = $iframe.attr('src') || '';
                            if (!currentSrc && this.contentWindow) {
                                try { 
                                    this.contentWindow.location.reload(); 
                                } catch(ex) {
                                    console.error('Error reloading iframe content window:', ex);
                                }
                                return;
                            }
                            if (currentSrc) {
                                const hasQuery = currentSrc.indexOf('?') !== -1;
                                const ts = Date.now();
                                let newSrc = currentSrc.replace(/[?&]wi_reload=\d+/, '');
                                newSrc += (hasQuery ? '&' : '?') + 'wi_reload=' + ts;
                                $iframe.attr('src', newSrc);
                            }
                        });
                        
                        // Activate the reloaded CTA tab if sidebar is visible
                        this.activateSidebarCTATab(ctaId);
                    }
                } catch(err) {
                    console.error('Error reloading iframes:', err);
                }
            });
        }
        
            updateCTAState(ctaId, newState, button) {
            $.ajax({
                url: ajaxurl,
                method: 'GET',
                data: {
                    action: 'webinarignition_update_cta_state',
                    id: this.settings.webinar.id,
                    cta_id: ctaId,
                    new_state: newState,
                    security: this.settings.nonce
                },
                success: (response) => {
                    if (response.success) {
                        button.toggleClass('active');
                        button.css('background', newState === 'on' ? 'rgba(76,175,80,0.5)' : 'rgba(244,67,54,0.5)');
                        
                        // Update the hide-show button icon
                        const ctaToggle = button.closest('.cta-toggle');
                        const hideShowBtn = ctaToggle.find('.hide-show-cta-btn');
                        this.updateHideShowButtonIcon(hideShowBtn, newState === 'on');
                        
                        if (window.liveCtas && window.liveCtas[ctaId]) {
                            window.liveCtas[ctaId].air_toggle = newState;
                        }
                        
                        // Manually show/hide the CTA without refreshing content
                        // Hash no longer includes air_toggle, so polling won't detect this change
                        console.log(`Toggle CTA ${ctaId} to ${newState} without content refresh`);
                        if (newState === 'on') {
                            // Check if it's overlay or sidebar and show accordingly
                            if ($(`#overlayCta${ctaId}`).length) {
                                this.showOverlayCTA(ctaId);
                            } else if ($(`#tab-cta-sidebar-tab${ctaId}`).length) {
                                this.showOuterCTA(ctaId);
                            }
                        } else {
                            // Hide the CTA
                            if ($(`#overlayCta${ctaId}`).length) {
                                this.hideOverlayCTA(ctaId);
                            } else if ($(`#tab-cta-sidebar-tab${ctaId}`).length) {
                                this.hideOuterCTA(ctaId);
                            }
                        }
                        
                        // Show success notification
                        if (this.settings.showNotifications) {
                            this.showNotification(`CTA ${newState === 'on' ? 'enabled' : 'disabled'}`, 'success', 2000);
                        }
                        
                        // Update page dimensions after hiding/showing
                        setTimeout(() => {
                            if (typeof window.setWebinarPageDimensions === 'function') {
                                window.setWebinarPageDimensions();
                            }
                            // Removed wi_webinar_refresh to prevent content refresh
                        }, 400);
                    } else {
                        console.error('Failed to update CTA state:', response.data);
                        if (this.settings.showNotifications) {
                            this.showNotification('Failed to update CTA state', 'error', 3000);
                        }
                    }
                },
                error: (xhr, status, error) => {
                    console.error('AJAX error:', error);
                    if (this.settings.showNotifications) {
                        this.showNotification('Network error updating CTA', 'error', 3000);
                    }
                }
            });
        }
        
        updateHideShowButtonIcon(hideShowBtn, isActive) {
            const eyeIcon = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
            </svg>`;
            
            const eyeSlashIcon = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.17 2.17C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" fill="currentColor"/>
            </svg>`;
            
            hideShowBtn.html(isActive ? eyeIcon : eyeSlashIcon);
        }
        
        initializeCTAControls() {
            const controlBar = `
                <div id="cta-controls-bar">
                    <div id="cta-toggle-container"></div>
                </div>
                <div class="toggle-icon-container">
                    <div class="down-icon cta-status-bar">
                        <svg id="sidebarToggleIconU" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="21" height="21" x="0" y="0" viewBox="0 0 451.847 451.847" style="enable-background:new 0 0 512 512" xml:space="preserve" class="">
                            <g>
                                <path d="M484.136 328.473 264.988 109.329c-5.064-5.064-11.816-7.844-19.172-7.844-7.208 0-13.964 2.78-19.02 7.844L7.852 328.265C2.788 333.333 0 340.089 0 347.297s2.784 13.968 7.852 19.032l16.124 16.124c5.064 5.064 11.824 7.86 19.032 7.86s13.964-2.796 19.032-7.86l183.852-183.852 184.056 184.064c5.064 5.06 11.82 7.852 19.032 7.852 7.208 0 13.96-2.792 19.028-7.852l16.128-16.132c10.488-10.492 10.488-27.568 0-38.06z" fill="#000000" opacity="1" data-original="#000000" class=""></path>
                            </g>
                        </svg>
                    </div>
                    <div class="down-icon settings-icon-air">
                        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="22" height="22" x="0" y="0" viewBox="0 0 682.667 682.667" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g><defs><clipPath id="a" clipPathUnits="userSpaceOnUse"><path d="M0 512h512V0H0Z" fill="#000000" opacity="1" data-original="#000000"></path></clipPath></defs><g clip-path="url(#a)" transform="matrix(1.33333 0 0 -1.33333 0 682.667)"><path d="M0 0a191.783 191.783 0 0 0 49.719-20.638l15.688 15.69a32.121 32.121 0 0 0 22.727 9.415 32.116 32.116 0 0 0 22.718-9.415l22.718-22.719a32.11 32.11 0 0 0 9.415-22.718 32.113 32.113 0 0 0-9.415-22.726L117.881-88.8a191.838 191.838 0 0 0 20.638-49.719h22.147c17.746 0 32.134-14.388 32.134-32.133v-32.134c0-17.745-14.388-32.133-32.134-32.133h-22.147a191.831 191.831 0 0 0-20.638-49.718l15.689-15.689a32.117 32.117 0 0 0 9.415-22.727 32.11 32.11 0 0 0-9.415-22.718l-22.718-22.718a32.112 32.112 0 0 0-22.718-9.415 32.117 32.117 0 0 0-22.727 9.415L49.719-352.8A191.78 191.78 0 0 0 0-373.437v-22.148c0-17.746-14.388-32.134-32.134-32.134h-32.133c-17.746 0-32.133 14.388-32.133 32.134v22.148a191.78 191.78 0 0 0-49.719 20.637l-15.689-15.689a32.115 32.115 0 0 0-22.726-9.415 32.108 32.108 0 0 0-22.718 9.415l-22.719 22.718a32.114 32.114 0 0 0-9.415 22.718 32.121 32.121 0 0 0 9.415 22.727l15.69 15.689a191.796 191.796 0 0 0-20.638 49.718h-22.147c-17.746 0-32.134 14.388-32.134 32.133v32.134c0 17.745 14.388 32.133 32.134 32.133h22.147A191.803 191.803 0 0 0-214.281-88.8l-15.69 15.689a32.117 32.117 0 0 0-9.415 22.726 32.114 32.114 0 0 0 9.415 22.718l22.719 22.719a32.112 32.112 0 0 0 22.718 9.415 32.119 32.119 0 0 0 22.726-9.415l15.689-15.69A191.783 191.783 0 0 0-96.4 0v22.148c0 17.746 14.387 32.133 32.133 32.133h32.133C-14.388 54.281 0 39.894 0 22.148Z" style="stroke-width:30;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform="translate(304.2 442.719)" fill="none" stroke="#000000" stroke-width="30" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity="" data-original="#000000"></path><path d="M0 0c53.205 0 96.4-43.195 96.4-96.4 0-53.204-43.195-96.4-96.4-96.4-53.205 0-96.4 43.196-96.4 96.4C-96.4-43.195-53.205 0 0 0Z" style="stroke-width:30;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;stroke-dasharray:none;stroke-opacity:1" transform="translate(256 352.4)" fill="none" stroke="#000000" stroke-width="30" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-dasharray="none" stroke-opacity="" data-original="#000000"></path></g></g></svg>
                    </div>		
                </div>
            `;
            
            $('#cta-toggle-parent-container').append(controlBar);
            
            setTimeout(() => {
                if (typeof window.setWebinarPageDimensions === 'function') {
                    window.setWebinarPageDimensions();
                }
            }, 600);
        }

        detectIframeInCTA(cta) {
            // Check both showHTML (for active CTAs) and response (for inactive CTAs)
            const htmlContent = cta.showHTML || cta.response || '';
            
            // Return false if no content
            if (!htmlContent || !htmlContent.trim()) {
                return false;
            }
            
            // Direct HTML iframe/embed detection
            const hasDirectIframe = 
                /<iframe[\s>]/i.test(htmlContent) ||          // iframe tags
                /<embed[\s>]/i.test(htmlContent) ||           // embed tags  
                /<object[\s>]/i.test(htmlContent);            // object tags
        
            // Common embed platform detection
            const hasEmbedPlatform =
                /youtube\.com\/embed|youtube-nocookie\.com\/embed|youtu\.be/i.test(htmlContent) || // YouTube
                /player\.vimeo\.com\/video/i.test(htmlContent) ||                                  // Vimeo
                /wistia\.com\/embed/i.test(htmlContent) ||                                         // Wistia
                /loom\.com\/embed/i.test(htmlContent);                                             // Loom
        
            // WordPress shortcode detection (more specific patterns)
            const hasShortcode = 
                /\[(youtube|vimeo|video|wpvideo|embed|iframe|wistia|loom|jwplayer|brightcove)\s[^\]]*\]/i.test(htmlContent) || // Specific shortcodes with attributes
                /\[advanced_iframe[^\]]*\]/i.test(htmlContent);                                                                   // Advanced iframe shortcode
        
            const result = hasDirectIframe || hasEmbedPlatform || hasShortcode;
            
            return result;
        }
        
        updateCTAControls(liveCtas) {
            const toggleContainer = $('#cta-toggle-container');
            const currentStates = {};
            
            // Store current states for comparison
            toggleContainer.find('.cta-toggle').each(function() {
                const ctaId = $(this).data('cta-id');
                const toggleBtn = $(this).find('.toggle-btn');
                const tabText = toggleBtn.find('span').text().replace(/^\d+\.\s*/, ''); // Extract tab text without CTA ID
                currentStates[ctaId] = {
                    position: toggleBtn.data('tooltip'),
                    active: toggleBtn.hasClass('active'),
                    tabText: tabText
                };
            });
            
            // Check if we need to update anything
            let needsUpdate = false;
            const ctaIds = Object.keys(liveCtas);
            
            for (const ctaId in liveCtas) {
                if (liveCtas.hasOwnProperty(ctaId)) {
                    const cta = liveCtas[ctaId];
                    const isActive = cta.air_toggle === "on";
                    const positionText = (cta.cta_position === 'overlay' ? '(overlay)' : '(sidebar)').trim();
                    const tabText = cta.tab_text || 'No tab found.';
                    
                    if (!currentStates[ctaId] || 
                        currentStates[ctaId].position !== positionText || 
                        currentStates[ctaId].active !== isActive ||
                        currentStates[ctaId].tabText !== tabText) {
                        needsUpdate = true;
                        console.log(`CTA ${ctaId} needs update:`, {
                            position: currentStates[ctaId]?.position !== positionText,
                            active: currentStates[ctaId]?.active !== isActive,
                            tabText: currentStates[ctaId]?.tabText !== tabText,
                            oldTabText: currentStates[ctaId]?.tabText,
                            newTabText: tabText
                        });
                        break;
                    }
                }
            }
            
            // Only update if something changed
            if (!needsUpdate && ctaIds.length === Object.keys(currentStates).length) {
                return;
            }
            
            toggleContainer.empty();

            console.log("Live ctas: ", liveCtas);
            
            for (const ctaId in liveCtas) {
                if (liveCtas.hasOwnProperty(ctaId)) {
                    const cta = liveCtas[ctaId];
                    const isActive = cta.air_toggle === "on";
                    
                    const positionText = (cta.cta_position === 'overlay' ? '(overlay)' : '(sidebar)').trim();
                    const tab_text = cta.tab_text || 'No tab found.';
                    const ctaIds = Object.keys(liveCtas);
                    const isLastCTA = ctaId === ctaIds[ctaIds.length - 1];
                    // const hasIframe = /<iframe[\s>]/i.test(cta.showHTML || '');
                    // const hasIframe = this.detectIframeInCTA(cta);
                    const hasIframe = true;
                    // console.log("CTA ID: ", ctaIds);

                    console.log("hasIframe: ",hasIframe);
                    
                    const toggleButton = `
                        <div class="cta-toggle" data-cta-id="${ctaId}">
                            <button class="hide-show-cta-btn" type="button" title="Hide/Show CTA" aria-label="Hide/Show CTA" style="display:inline-flex;align-items:center;justify-content:center;padding:4px;width:26px;height:26px;border:1px solid rgba(0,0,0,0.2);border-top-left-radius: 8px;border-bottom-left-radius: 8px;border-top-right-radius:0px;border-bottom-right-radius:0px; background:#fff;color:#111;cursor:pointer">
                                ${isActive ? 
                                    `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" fill="currentColor"/>
                                    </svg>` : 
                                    `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.43-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.17 2.17C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z" fill="currentColor"/>
                                    </svg>`
                                }    
                            </button>
                            <button class="toggle-btn tooltip-btn ${isActive ? 'active' : ''}" 
                                    style="padding: 5px 10px; background: ${isActive ? 'rgba(76,175,80,0.5)' : 'rgba(244,67,54,0.5)'}; 
                                        color: white; border: none; border-top-left-radius: 0px;border-bottom-left-radius: 0px;${hasIframe ? 'border-top-right-radius:0px;border-bottom-right-radius:0px;': 'border-top-right-radius:8px; border-bottom-right-radius:8px;'} cursor: pointer;" data-tooltip="${positionText}" data-status="${isActive ? 'on' : 'off'}">
                                        <span>${ctaId}. ${tab_text}</span>
                            </button>
                            ${hasIframe ? `
                                <button class="cta-reload-btn" type="button" title="Reload iframe" aria-label="Reload iframe" style="display:inline-flex;align-items:center;justify-content:center;padding:4px;width:26px;height:26px;border:1px solid rgba(0,0,0,0.2); border-top-right-radius: 8px;border-bottom-right-radius: 8px; border-top-left-radius: 0px; border-bottom-left-radius: 0px;background:#fff;color:#111;cursor:pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="23 4 23 10 17 10"></polyline>
                                        <polyline points="1 20 1 14 7 14"></polyline>
                                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"></path>
                                        <path d="M20.49 15a9 9 0 0 1-14.85 3.36L1 14"></path>
                                    </svg>
                                </button>` : ''}
                        </div>
                        ${!isLastCTA ? '<hr class="hr-line-on-off">' : ''}
                    `;
                    
                    toggleContainer.append(toggleButton);
                }
            }
        }
        
        // startPolling() {
        //     this.pollWebinarStatus({
        //         data: {
        //             action: 'webinarignition_broadcast_msg_poll_callback',
        //             security: this.settings.nonce,
        //             id: this.settings.webinar.id,
        //             lead_id: this.settings.lead_id, 
        //             ip: this.settings.ip,
        //             isPreview: this.settings.isPreview
        //         }
        //     });
        // }
        
        // pollWebinarStatus(options) {
        //     const settings = $.extend({
        //         method: 'GET',
        //         dataType: 'json',
        //         interval: 1000,
        //         data: {},
        //         url: ajaxurl
        //     }, options);
            
        //     const poll = () => {
        //         $.ajax({
        //             url: settings.url,
        //             method: settings.method,
        //             dataType: settings.dataType,
        //             data: settings.data,
        //             success: (jsonString) => {
        //                 this.handlePollResponse(jsonString);
        //                 setTimeout(poll, settings.interval);
        //             },
        //             error: (xhr, status, error) => {
        //                 console.error("Error in AJAX request: ", status, error);
        //                 setTimeout(poll, settings.interval);
        //             }
        //         });
        //     };
            
        //     poll();
        // }

        startSocketConnection() {
            // Check if socket.io is available and enabled
            console.log('Starting socket connection');
            console.log('Socket.io available:', typeof io !== 'undefined');
            console.log('Socket.io enabled:', this.settings.socketEnabled);
            console.log('Third party server enabled:', window.WEBINARIGNITION?.enable_third_party_server);
            
            // // Check if third party server is enabled
            // if (window.WEBINARIGNITION?.enable_third_party_server === 1) {
            //     console.log('Third party server is enabled, using socket connection');
            // } else {
            //     console.log('Third party server is disabled, falling back to polling');
            //     this.fallbackToPolling();
            //     return;
            // }
            
            if (typeof io === 'undefined' || !this.settings.socketEnabled) {
                console.warn('Socket.io not available or disabled, falling back to polling');
                this.fallbackToPolling();
                return;
            }

            try {
                const socketUrl = 'https://nodejs-small-wildflower-8030.fly.dev/';
                console.log('Socket URL:', socketUrl);
                
                // Socket.io connection with proper configuration
                this.socket = io(socketUrl, {
                    transports: ['websocket', 'polling'],
                    upgrade: true,
                    rememberUpgrade: true,
                    timeout: 20000,
                    forceNew: false,
                    reconnection: true,
                    reconnectionAttempts: 5,
                    reconnectionDelay: 1000,
                    reconnectionDelayMax: 5000,
                    maxReconnectionAttempts: 5
                });
                
                // Connection state management
                this.connectionState = 'connecting';
                this.reconnectAttempts = 0;
                this.maxReconnectAttempts = 5;
                
                this.socket.on('connect', () => {
                    console.log('Socket.io connected for real-time CTA updates');
                    this.connectionState = 'connected';
                    this.reconnectAttempts = 0;
                    
                    // Clear any existing connection timeout
                    if (this.connectionTimeout) {
                        clearTimeout(this.connectionTimeout);
                        this.connectionTimeout = null;
                    }
                    
                    // Authenticate with the server
                    this.socket.emit('authenticate', {
                        webinar_id: this.settings.webinar.id,
                        lead_id: this.settings.lead_id,
                        nonce: this.settings.nonce,
                        user_role: window.WEBINARIGNITION?.current_user?.roles || 'viewer'
                    });
                });
                
                this.socket.on('authenticated', (data) => {
                    console.log('Socket authentication successful');
                    this.connectionState = 'authenticated';
                    // Join the specific webinar room
                    this.socket.emit('join_webinar', this.settings.webinar.id);
                });
                
                this.socket.on('authentication_failed', (error) => {
                    console.error('Socket authentication failed:', error);
                    this.socket.disconnect();
                    // this.fallbackToPolling();
                });
                
                // Listen for CTA updates from the Socket.io server
                this.socket.on('cta_updated', (data) => {
                    console.log('CTA Updated via Socket.io:', data);
                    
                    // Handle the CTA update data
                    if (data && data.ctas) {
                        this.handleCTAsUpdate(data.ctas);
                    } else if (data && data.live_ctas) {
                        // Handle different data structure
                        this.handleCTAsUpdate(data.live_ctas);
                    } else {
                        // If no CTA data, fetch fresh data
                        this.fetchLatestCTAs();
                    }
                    
                    // Show notification if enabled
                    if (this.settings.showNotifications !== false) {
                        this.showNotification('CTA has been updated!', 'success', 2000);
                    }
                });
                
                this.socket.on('cta_toggle_changed', (data) => {
                    console.log('CTA toggle changed:', data);
                    this.handleCTAToggleChange(data.cta_id, data.new_state);
                });
                
                // Listen for general webinar updates
                this.socket.on('webinar_updated', (data) => {
                    console.log('Webinar updated via Socket.io:', data);
                    if (data.live_ctas) {
                        this.handleCTAsUpdate(data.live_ctas);
                    }
                });
                
                // Listen for specific CTA changes
                this.socket.on('cta_added', (data) => {
                    console.log('CTA added via Socket.io:', data);
                    this.showNotification('New CTA added!', 'success', 2000);
                    this.fetchLatestCTAs(); // Fetch fresh data
                });
                
                this.socket.on('cta_removed', (data) => {
                    console.log('CTA removed via Socket.io:', data);
                    this.showNotification('CTA removed!', 'info', 2000);
                    this.fetchLatestCTAs(); // Fetch fresh data
                });
                
                this.socket.on('webinar_closed', () => {
                    console.log('Webinar closed via Socket.io');
                    window.location.reload();
                });
                
                this.socket.on('reconnect_attempt', (attemptNumber) => {
                    console.log(`Attempting to reconnect Socket.io... (attempt ${attemptNumber})`);
                    this.connectionState = 'reconnecting';
                    this.reconnectAttempts = attemptNumber;
                });
                
                this.socket.on('reconnect', (attemptNumber) => {
                    console.log(`Socket.io reconnected after ${attemptNumber} attempts`);
                    this.connectionState = 'connected';
                    this.reconnectAttempts = 0;
                    
                    // Re-authenticate and re-join rooms
                    this.socket.emit('authenticate', {
                        webinar_id: this.settings.webinar.id,
                        lead_id: this.settings.lead_id,
                        nonce: this.settings.nonce,
                        user_role: window.WEBINARIGNITION?.current_user?.roles || 'viewer'
                    });
                });
                
                this.socket.on('reconnect_failed', () => {
                    console.error('Socket.io reconnection failed after maximum attempts');
                    this.connectionState = 'failed';
                    this.fallbackToPolling();
                });
                
                this.socket.on('disconnect', (reason) => {
                    console.log('Socket.io disconnected:', reason);
                    this.connectionState = 'disconnected';
                    
                    if (reason === 'io server disconnect') {
                        // Server forced disconnect, may need to reauthenticate
                        this.socket.connect();
                    } else if (reason === 'io client disconnect') {
                        // Client initiated disconnect, don't reconnect
                        return;
                    }
                });
                
                this.socket.on('error', (error) => {
                    console.error('Socket.io error:', error);
                });
                
                // Set timeout to detect connection issues
                // this.connectionTimeout = setTimeout(() => {
                //     if (this.connectionState !== 'connected' && this.connectionState !== 'authenticated') {
                //         console.log('Socket.io connection timeout, falling back to polling');
                //         this.fallbackToPolling();
                //     }
                // }, 10000);
                
            } catch (error) {
                console.error('Socket.io initialization failed:', error);
                // this.fallbackToPolling();
            }
        }

        // Handle CTA updates from Socket.io
        handleCTAsUpdate(liveCtas) {
            this.updateCTAControls(liveCtas);
            
            const ctaIdsToUpdate = new Set();
            const currentCTAs = {};
            
            for (const ctaId in liveCtas) {
                currentCTAs[ctaId] = true;
                const cta = liveCtas[ctaId];
                const storedHash = this.getCTAHash(ctaId);
                const currentHash = cta.hash;
                
                if (!storedHash || storedHash !== currentHash) {
                    ctaIdsToUpdate.add(ctaId);
                    this.setCTAHash(ctaId, currentHash);
                }
            }
            
            // Remove CTAs that are no longer in the response
            for (const displayedId in this.displayedCTAs) {
                if (!currentCTAs[displayedId]) {
                    this.removeCTA(displayedId);
                    delete this.displayedCTAs[displayedId];
                    this.removeCTAHash(displayedId);
                }
            }
            
            // Process each CTA
            if (Object.keys(liveCtas).length > 0) {
                for (const ctaId in liveCtas) {
                    if (liveCtas.hasOwnProperty(ctaId)) {
                        this.displayedCTAs[ctaId] = true;
                        const cta = liveCtas[ctaId];
                        
                        if (cta.cta_position === 'overlay') {
                            this.handleOverlayCTA(ctaId, cta, ctaIdsToUpdate);
                        } else if (cta.cta_position === 'outer') {
                            this.handleOuterCTA(ctaId, cta, ctaIdsToUpdate);
                        }
                    }
                }
            } else {
                this.handleNoCTAs();
            }
        }

        // Show notification to user
        showNotification(message, type = 'info', duration = 3000) {
            // Check if current user is an administrator
            var current_user = window.WEBINARIGNITION.current_user.roles;
            if (!current_user || !Array.isArray(current_user) || !current_user.includes('administrator')) {
                return; // Don't show notification if user is not an admin
            }
            
            // Remove any existing notifications
            $('.wi-socket-notification').remove();
            
            const notificationClass = type === 'error' ? 'wi-notification-error' : 
                                   type === 'success' ? 'wi-notification-success' : 
                                   'wi-notification-info';
            
            const notification = $(`
                <div class="wi-socket-notification ${notificationClass}" style="
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: ${type === 'error' ? '#f44336' : type === 'success' ? '#4caf50' : '#2196f3'};
                    color: white;
                    padding: 12px 20px;
                    border-radius: 4px;
                    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                    z-index: 10000;
                    font-size: 14px;
                    max-width: 300px;
                    word-wrap: break-word;
                ">
                    ${message}
                </div>
            `);
            
            $('body').append(notification);
            
            // Auto-remove after duration
            setTimeout(() => {
                notification.fadeOut(300, () => {
                    notification.remove();
                });
            }, duration);
        }

        // Fallback to polling when socket.io fails
        fallbackToPolling() {
            console.log('Falling back to polling mechanism');
            this.connectionState = 'polling';
            
            // Disconnect socket if connected
            if (this.socket) {
                this.socket.disconnect();
                this.socket = null;
            }
            
            // Clear socket timeout
            if (this.connectionTimeout) {
                clearTimeout(this.connectionTimeout);
                this.connectionTimeout = null;
            }
            
            // Start polling
            this.startPolling();
        }

        // Start polling mechanism
        startPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }
            
            this.pollWebinarStatus({
                data: {
                    action: 'webinarignition_broadcast_msg_poll_callback',
                    security: this.settings.nonce,
                    id: this.settings.webinar.id,
                    lead_id: this.settings.lead_id, 
                    ip: this.settings.ip,
                    isPreview: this.settings.isPreview
                }
            });
        }

        // Get current connection status
        getConnectionStatus() {
            return {
                state: this.connectionState,
                isConnected: this.connectionState === 'connected' || this.connectionState === 'authenticated',
                isPolling: this.connectionState === 'polling',
                reconnectAttempts: this.reconnectAttempts,
                hasSocket: this.socket !== null
            };
        }

        // Poll webinar status
        pollWebinarStatus(options) {
            const settings = $.extend({
                method: 'GET',
                dataType: 'json',
                interval: this.settings.pollingInterval,
                data: {},
                url: ajaxurl
            }, options);
            
            const poll = () => {
                $.ajax({
                    url: settings.url,
                    method: settings.method,
                    dataType: settings.dataType,
                    data: settings.data,
                    success: (jsonString) => {
                        this.handlePollResponse(jsonString);
                        this.pollingInterval = setTimeout(poll, settings.interval);
                    },
                    error: (xhr, status, error) => {
                        console.error("Error in AJAX polling request: ", status, error);
                        this.pollingInterval = setTimeout(poll, settings.interval);
                    }
                });
            };
            
            poll();
        }

        // Handle no CTAs scenario
        handleNoCTAs() {
            // Remove all CTAs
            for (const displayedId in this.displayedCTAs) {
                this.removeCTA(displayedId);
                delete this.displayedCTAs[displayedId];
                this.removeCTAHash(displayedId);
            }
            
            // Hide control bar if no CTAs
            $('#cta-controls-bar').hide();
        }

        // Fetch latest CTAs from server when Socket.io doesn't provide complete data
        fetchLatestCTAs() {
            console.log('Fetching latest CTAs from server...');
            
            $.ajax({
                url: ajaxurl,
                method: 'GET',
                dataType: 'json',
                data: {
                    action: 'webinarignition_broadcast_msg_poll_callback',
                    security: this.settings.nonce,
                    id: this.settings.webinar.id,
                    lead_id: this.settings.lead_id,
                    ip: this.settings.ip,
                    isPreview: this.settings.isPreview
                },
                success: (response) => {
                    console.log('Latest CTAs fetched:', response);
                    this.handlePollResponse(response);
                },
                error: (xhr, status, error) => {
                    console.error('Error fetching latest CTAs:', error);
                    if (this.settings.showNotifications) {
                        this.showNotification('Failed to fetch latest CTAs', 'error', 3000);
                    }
                }
            });
        }

        // Cleanup method for proper resource management
        destroy() {
            console.log('Destroying WebinarCTAManager...');
            
            // Disconnect socket
            if (this.socket) {
                this.socket.disconnect();
                this.socket = null;
            }
            
            // Clear polling interval
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
                this.pollingInterval = null;
            }
            
            // Clear connection timeout
            if (this.connectionTimeout) {
                clearTimeout(this.connectionTimeout);
                this.connectionTimeout = null;
            }
            
            // Remove event handlers
            this.removeEventHandlers();
            
            // Clean up displayed CTAs
            for (const displayedId in this.displayedCTAs) {
                this.removeCTA(displayedId);
                this.removeCTAHash(displayedId);
            }
            
            // Clean up main hash
            this.removeMainHash();
            
            this.displayedCTAs = {};
            this.connectionState = 'disconnected';
            
            console.log('WebinarCTAManager destroyed');
        }

        // Handle individual CTA toggle changes
        handleCTAToggleChange(ctaId, newState) {
            // Update the specific CTA toggle button
            const toggleBtn = $(`.cta-toggle[data-cta-id="${ctaId}"] .toggle-btn`);
            if (toggleBtn.length) {
                const isActive = newState === 'on';
                toggleBtn.toggleClass('active', isActive);
                toggleBtn.css('background', isActive ? 'rgba(76,175,80,0.5)' : 'rgba(244,67,54,0.5)');
                toggleBtn.attr('data-status', newState);
                
                // Update local state
                if (window.liveCtas && window.liveCtas[ctaId]) {
                    window.liveCtas[ctaId].air_toggle = newState;
                }
            }
        }


        
        
        handlePollResponse(jsonString) {
            if (jsonString.hasOwnProperty('success') && jsonString.success === false && 
                jsonString.data.message === 'Invalid nonce' && $is_webinar_page) {
                location.reload();
                return;
            }
            
            const jsonobj = typeof jsonString === "string" ? JSON.parse(jsonString) : jsonString;

            console.log("jsonobj: ",jsonobj)
            
            if (jsonobj.webinar_closed === 'YES') {
                window.location.reload();
                return;
            }
            
            // Store main CTA hash if it exists
            if (jsonobj.hash) {
                sessionStorage.setItem('MainHash', jsonobj.hash);
            }
            
            const liveCtas = jsonobj.live_ctas || {};
            this.updateCTAControls(liveCtas);
            
            const ctaIdsToUpdate = new Set();
            const currentCTAs = {};
            
            for (const ctaId in liveCtas) {
                currentCTAs[ctaId] = true;
                const cta = liveCtas[ctaId];
                const storedHash = this.getCTAHash(ctaId);
                const currentHash = cta.hash;
                
                console.log(`CTA ${ctaId} Hash Check:`);
                console.log(`  - Stored hash: ${storedHash}`);
                console.log(`  - Current hash: ${currentHash}`);
                console.log(`  - Match: ${storedHash === currentHash}`);
                console.log(`  - air_toggle: ${cta.air_toggle}`);
                
                // Determine if update is needed
                let needsUpdate = false;
                let reason = '';
                
                if (currentHash) {
                    // CTA is ON (has a hash)
                    if (!storedHash || storedHash !== currentHash) {
                        needsUpdate = true;
                        reason = 'CTA is ON and hash changed or first load';
                    }
                    // Always update stored hash when CTA is ON
                    this.setCTAHash(ctaId, currentHash);
                } else if (storedHash) {
                    // CTA is OFF but had a hash (was previously ON) - needs to be hidden
                    needsUpdate = true;
                    reason = 'CTA was ON, now OFF - needs to be hidden';
                    // DON'T overwrite stored hash with undefined - preserve it for when CTA is turned back ON
                }
                // If both undefined (always OFF), don't add to update set
                
                if (needsUpdate) {
                    console.log(`  → Adding CTA ${ctaId} to update set (${reason})`);
                    ctaIdsToUpdate.add(ctaId);
                } else {
                    console.log(`  → CTA ${ctaId} no update needed (hash unchanged or always OFF)`);
                }
            }
            
            console.log('ctaIdsToUpdate:', ctaIdsToUpdate);
            
            // Remove CTAs that are no longer in the response
            for (const displayedId in this.displayedCTAs) {
                if (!currentCTAs[displayedId]) {
                    this.removeCTA(displayedId);
                    delete this.displayedCTAs[displayedId];
                    this.removeCTAHash(displayedId);
                }
            }
            
            // Process each CTA
            if (Object.keys(liveCtas).length > 0) {
                for (const ctaId in liveCtas) {
                    if (liveCtas.hasOwnProperty(ctaId)) {
                        this.displayedCTAs[ctaId] = true;
                        const cta = liveCtas[ctaId];
                        
                        if (cta.cta_position === 'overlay') {
                            this.handleOverlayCTA(ctaId, cta, ctaIdsToUpdate);
                            console.log("handle overlay cta overlay CTA !");
                        } else if (cta.cta_position === 'outer') {
                            this.handleOuterCTA(ctaId, cta, ctaIdsToUpdate);
                        }
                    }
                }
            } else {
                this.handleNoCTAs();
            }
        }
        handleOuterCTA(ctaId, cta, ctaIdsToUpdate) {
            const btnColorA = this.getTextColorFromBgColor(cta.button_color);
            
            // Check if CTA exists and if tab name has changed
            const existingTab = $(`#tab-cta-sidebar-tab${ctaId}`);
            const existingTabName = existingTab.length ? existingTab.text().trim() : '';
            const newTabName = (cta.tab_text || 'No tab found.').trim();
            const tabNameChanged = existingTabName !== newTabName;
            
            // Only refresh content if CTA is in the update set (hash changed)
            // Tab name changes should not trigger content refresh
            if (ctaIdsToUpdate.has(ctaId)) {
                if (cta.air_toggle === "on") {
                    console.log("handle outer cta outer CTA on!!");
                    this.createOuterCTA(ctaId, cta, btnColorA);
                } else if (cta.air_toggle === "off") {
                    // Hide CTA but keep in DOM (toggle is off but CTA still in array)
                    this.hideOuterCTA(ctaId);
                }
            } else if (cta.air_toggle === "on") {
                // If no content update needed but tab name changed, update only the tab text
                if (tabNameChanged && existingTab.length) {
                    console.log(`Updating only tab name for CTA ${ctaId} without content refresh`);
                    existingTab.text(newTabName);
                }
                // Ensure CTA is visible without refreshing content
                this.showOuterCTA(ctaId);
            } else if (cta.air_toggle === "off") {
                // If no update needed but toggle is off, ensure it's hidden
                this.hideOuterCTA(ctaId);
            }
        }
        
        // Activate a sidebar CTA tab when it's reloaded
        activateSidebarCTATab(ctaId) {
            // Check if sidebar is visible
            const sidebar = $('#webinarSidebar');
            if (!sidebar.length || !sidebar.hasClass('wi-col-lg-3')) {
                return; // Sidebar is not visible
            }
            
            const tab = $(`#tab-cta-sidebar-tab${ctaId}`);
            const content = $(`#tab-new-tab-content${ctaId}`);
            
            if (tab.length && content.length) {
                // Remove active class from all tabs
                $('#webinarTabs .wi-nav-link').removeClass('active').attr('aria-selected', 'false');
                $('.wi-tab-content .wi-tab-pane').removeClass('active');
                
                // Add active class to the reloaded CTA tab
                tab.addClass('active').attr('aria-selected', 'true');
                content.addClass('active');
            }
        }
        createNewTabs(ctaId, cta, tabText, btnColorA) {
            const buttonHTML = cta.button_url && cta.button_url.trim() !== '' ? 
                `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>` : '';
                
            const tabsHtml = `
                <ul class="wi-nav wi-nav-tabs wi-bg-light wi-px-1 wi-pt-1" id="webinarTabs" role="tablist" kt-hidden-height="48" style="">
                    <li class="wi-nav-item nav-item">
                        <a class="wi-nav-link nav-link active" id="tab-cta-sidebar-tab${ctaId}" data-toggle="tab" href="#tab-new-tab-content${ctaId}" role="tab" aria-controls="tab-new-tab-content${ctaId}" aria-selected="true">
                            ${tabText}
                        </a>
                    </li>
                </ul>
            `;
            
            const tabsContentHtml = `
                <div class="wi-tab-pane active" id="tab-new-tab-content${ctaId}" aria-labelledby="tab-cta-sidebar-tab${ctaId}" role="tabpanel">
                    <div class="timedUnderArea test-7" id="orderBTN" style="margin: auto; width: 100%;">
                        <div class="orderBTNCopy">${cta.showHTML || ''}</div>
                        ${buttonHTML ? `<div id="orderBTNArea${ctaId}">${buttonHTML}</div>` : ''}
                    </div>
                </div>
            `;
            
            $('#webinarSidebar').addClass("wi-col-lg-3");
            $("#webinarVideo").addClass("wi-col-lg-9");
            $('#webinarSidebar .wi-col-12').prepend(tabsHtml);
            $('#webinarTabsContent .webinarTabsContent-inner .wi-tab-content').append(tabsContentHtml);
            
            if (!$('.sidebar-toggle-container').hasClass('sidebar-visible')) {
                $('.sidebar-toggle-container').addClass('sidebar-visible');
            }
        }
        createOuterCTA(ctaId, cta, btnColorA) {
            const tabText = cta.tab_text;
            
            // Hide overlay CTA if it exists
            $(`#overlayCta${ctaId}`).css('display', 'none');
            
            // Check if sidebar CTA already exists
            const existingTab = $(`#tab-cta-sidebar-tab${ctaId}`);
            const existingContent = $(`#tab-new-tab-content${ctaId}`);
            
            if (existingTab.length && existingContent.length) {
                // CTA exists, update its content
                console.log(`Updating sidebar CTA ${ctaId} content via createOuterCTA`);
                existingTab.closest('.wi-nav-item').css('display', 'block');
                $('#webinarSidebar').addClass("wi-col-lg-3");
                $('#webinarVideo').addClass("wi-col-lg-9");
                
                // Update tab text
                if (existingTab.text() !== tabText) {
                    existingTab.text(tabText);
                }
                
                // Update the HTML content
                $(`#tab-new-tab-content${ctaId} .orderBTNCopy`).html(cta.showHTML || '');
                
                // Update or remove button
                $(`#orderBTNAreaPanel${ctaId}`).remove();
                if (cta.button_url && cta.button_url.trim() !== '') {
                    const buttonHTML = `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>`;
                    $(`#orderBTNSidebar${ctaId}`).append(`<div id="orderBTNAreaPanel${ctaId}">${buttonHTML}</div>`);
                }
                
                // Apply iframe version if present
                if (cta.iframe_ver) {
                    this.applyIframeVersion(`#tab-new-tab-content${ctaId} .orderBTNCopy`, cta.iframe_ver);
                }
                
                // Activate the reloaded CTA tab if sidebar is visible
                this.activateSidebarCTATab(ctaId);
            } else {
                // CTA doesn't exist, create it
                if ($("#webinarSidebar").length) {
                    if ($("#webinarTabs").length) {
                        this.updateExistingTabs(ctaId, cta, tabText, btnColorA);
                    } else {
                        this.createNewTabs(ctaId, cta, tabText, btnColorA);
                    }
                } else {
                    this.createSidebarWithTabs(ctaId, cta, tabText, btnColorA);
                }
            }
    
            setTimeout(() => {
                if (typeof window.setWebinarPageDimensions === 'function') {
                    window.setWebinarPageDimensions();
                }
            }, 600);
        }

        updateExistingTabs(ctaId, cta, tabText, btnColorA) {
            $("#webinarVideo").addClass("wi-col-lg-9");
            $("#webinarSidebar").addClass("wi-col-lg-3");
    
            // Check if the tab already exists
            const existingTab = $(`#tab-cta-sidebar-tab${ctaId}`);
            const existingNavItem = existingTab.closest('.wi-nav-item');
            const existingContent = $(`#tab-new-tab-content${ctaId}`);
            
            if (existingTab.length && existingNavItem.length && existingContent.length) {
                // CTA exists but might be hidden - show it and update content
                console.log(`Updating sidebar CTA ${ctaId} content via updateExistingTabs`);
                existingNavItem.css('display', 'block');
                
                // Update the tab text if it has changed
                if (existingTab.text() !== tabText) {
                    existingTab.text(tabText);
                }
                
                // Update the HTML content
                $(`#tab-new-tab-content${ctaId} .orderBTNCopy`).html(cta.showHTML || '');
                
                // Update or remove button
                $(`#orderBTNAreaPanel${ctaId}`).remove();
                if (cta.button_url && cta.button_url.trim() !== '') {
                    const buttonHTML = `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>`;
                    $(`#orderBTNSidebar${ctaId}`).append(`<div id="orderBTNAreaPanel${ctaId}">${buttonHTML}</div>`);
                }
                
                // Apply iframe version if present
                if (cta.iframe_ver) {
                    // this.applyIframeVersion(`#tab-new-tab-content${ctaId} .orderBTNCopy`, cta.iframe_ver);
                }
                
                // Activate the reloaded CTA tab if sidebar is visible
                this.activateSidebarCTATab(ctaId);
            } else if (!$(`#tab-cta-sidebar-tab${ctaId}`).length) {
                // Create the new tab (this is first time creation)
                const newTab = `
                    <li class="wi-nav-item nav-item">
                        <a class="wi-nav-link nav-link active tab-new-tab-content-new" id="tab-cta-sidebar-tab${ctaId}" data-toggle="tab" href="#tab-new-tab-content${ctaId}" role="tab" aria-controls="tab-new-tab-content${ctaId}" aria-selected="true">
                            ${tabText}
                        </a>
                    </li>
                `;
                $("#webinarTabs").prepend(newTab);
    
                // Remove 'active' class from other tabs
                $("#webinarTabs .wi-nav-link")
                    .not(`#tab-cta-sidebar-tab${ctaId}`)
                    .removeClass("active")
                    .attr("aria-selected", "false");
    
                // Remove 'active' class from other content divs
                $(".wi-tab-content .wi-tab-pane").removeClass("active");
    
                // Append the new content
                const buttonHTML = cta.button_url && cta.button_url.trim() !== '' ? 
                    `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>` : '';
                    
                const newDiv = `
                    <div class="wi-tab-pane active tab-new-tab-content-new" id="tab-new-tab-content${ctaId}" aria-labelledby="tab-cta-sidebar-tab${ctaId}" role="tabpanel">
                        <div class="timedUnderArea test-7 tabContented" id="orderBTNSidebar${ctaId}">
                            <div class="orderBTNCopy">${cta.showHTML || ''}</div>
                            ${buttonHTML ? `<div id="orderBTNAreaPanel${ctaId}">${buttonHTML}</div>` : ''}
                        </div>
                    </div>
                `;
                $(".wi-tab-content").prepend(newDiv);
    
                // Remove any old internal reload buttons from CTA content (sidebar)
                $(`#tab-new-tab-content${ctaId} .orderBTNCopy .wi-iframe-reload-btn`).remove();
                
                if (cta.iframe_ver) {
                    // this.applyIframeVersion(`#tab-new-tab-content${ctaId} .orderBTNCopy`, cta.iframe_ver);
                }
            } else {
                // Update existing tab content (don't change active state)
                const existingTabName = $(`#tab-cta-sidebar-tab${ctaId}`).text();
                if (existingTabName !== cta.tab_text) {
                    $(`#tab-cta-sidebar-tab${ctaId}`).text(tabText);
                }
                
                // Always update the content when hash changes
                $(`#tab-new-tab-content${ctaId} .orderBTNCopy`).html(cta.showHTML || '');
                $("#webinarSidebar").addClass("wi-col-lg-3");
                
                // Update or remove button
                $(`#orderBTNAreaPanel${ctaId}`).remove();
                if (cta.button_url && cta.button_url.trim() !== '') {
                    const buttonHTML = `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>`;
                    $(`#orderBTNSidebar${ctaId}`).append(`<div id="orderBTNAreaPanel${ctaId}">${buttonHTML}</div>`);
                }
                
                // Apply iframe version if present
                if (cta.iframe_ver) {
                    // this.applyIframeVersion(`#tab-new-tab-content${ctaId} .orderBTNCopy`, cta.iframe_ver);
                }
                
                // Activate the reloaded CTA tab if sidebar is visible
                this.activateSidebarCTATab(ctaId);
            }
            
            if (cta.hash) {
                this.setCTAHash(ctaId, cta.hash);
            }
        }
        createOverlayCTA(ctaId, cta, btnColorA) {
            if ($("#webinarTabs").length == 0) {
                $("#webinarVideo").removeClass("wi-col-lg-9");
                $("#webinarSidebar").removeClass("wi-col-12");
            }
            
            // Hide any existing sidebar/tab instance for this CTA when moving to overlay
            $(`#tab-cta-sidebar-tab${ctaId}`).closest('.wi-nav-item').css('display', 'none');
            $(`#tab-new-tab-content${ctaId}`).removeClass('active');

            // Check if overlay CTA already exists
            const existingOverlayCTA = $(`#overlayCta${ctaId}`);
            
            if (existingOverlayCTA.length) {
                // CTA exists, update its content
                console.log(`Updating overlay CTA ${ctaId} content`);
                existingOverlayCTA.css('display', 'block');
                
                // Update the HTML content
                $(`#orderBTNCopy${ctaId}`).html(cta.showHTML || '');
                
                // Update or remove button
                $(`#orderBTNArea${ctaId}`).remove();
                if (cta.button_url && cta.button_url.trim() !== '') {
                    const buttonHTML = `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>`;
                    $(`#orderBTN${ctaId}`).append(`<div id="orderBTNArea${ctaId}">${buttonHTML}</div>`);
                }
                
                // Apply iframe version if present
                // if (cta.iframe_ver) {
                //     this.applyIframeVersion(`#orderBTNCopy${ctaId}`, cta.iframe_ver);
                // }
                
                this.styleOverlayCTA(ctaId, cta);
            } else {
                // CTA doesn't exist, create it
                const buttonHTML = cta.button_url && cta.button_url.trim() !== '' ? 
                    `<a href="${cta.button_url}" target="_blank" class="large radius button success addedArrow replayOrder wiButton wiButton-lg wiButton-block wi-live-btn" style="background-color:${cta.button_color};color:${btnColorA};border: 1px solid rgba(0,0,0,0.20);">${cta.button_text}</a>` : '';
                
                const overlayCTA = `
                    <div id="overlayCta${ctaId}">
                        <div class="cta-overlay-show-icon" data-cta-id="${ctaId}">
                            <svg fill="#000000" width="34px" height="33px" viewBox="0 0 64 64" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                            <g id="eye">
                                    <path d="M32.513,13.926c10.574,0.15 19.141,9.894 23.487,18.074c0,0 -1.422,2.892 -2.856,4.895c-0.694,0.969 -1.424,1.913 -2.191,2.826c-0.547,0.65 -1.112,1.283 -1.698,1.898c-5.237,5.5 -12.758,9.603 -20.7,8.01c-8.823,-1.77 -15.732,-9.498 -20.058,-17.629c0,0 1.248,-2.964 2.69,-4.964c0.646,-0.897 1.324,-1.77 2.034,-2.617c0.544,-0.649 1.108,-1.282 1.691,-1.897c4.627,-4.876 10.564,-8.63 17.601,-8.596Zm-0.037,4c-5.89,-0.022 -10.788,3.267 -14.663,7.35c-0.527,0.555 -1.035,1.127 -1.527,1.713c-0.647,0.772 -1.265,1.569 -1.854,2.386c-0.544,0.755 -1.057,1.805 -1.451,2.59c3.773,6.468 9.286,12.323 16.361,13.742c6.563,1.317 12.688,-2.301 17.016,-6.846c0.529,-0.555 1.04,-1.128 1.534,-1.715c0.7,-0.833 1.366,-1.694 1.999,-2.579c0.557,-0.778 1.144,-1.767 1.588,-2.567c-3.943,-6.657 -10.651,-13.944 -19.003,-14.074Z"/>
                                    <path d="M32.158,23.948c4.425,0 8.018,3.593 8.018,8.017c0,4.425 -3.593,8.017 -8.018,8.017c-4.424,0 -8.017,-3.592 -8.017,-8.017c0,-4.424 3.593,-8.017 8.017,-8.017Zm0,4.009c2.213,0 4.009,1.796 4.009,4.008c0,2.213 -1.796,4.009 -4.009,4.009c-2.212,0 -4.008,-1.796 -4.008,-4.009c0,-2.212 1.796,-4.008 4.008,-4.008Z"/>
                            </g>
                        </svg>
                        </div>
                        <div class="timedUnderArea test-7 hippopotemus" id="orderBTN${ctaId}" style="width: ${cta.box_width === '100%' ? '97%' : cta.box_width}; display: none;margin:auto;">
                            <div class="cta-overlay-hide-icon" data-cta-id="${ctaId}">
                                <svg width="32px" height="32px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M3.61399 4.21063C3.17804 3.87156 2.54976 3.9501 2.21069 4.38604C1.87162 4.82199 1.95016 5.45027 2.38611 5.78934L4.66386 7.56093C3.78436 8.54531 3.03065 9.68043 2.41854 10.896L2.39686 10.9389C2.30554 11.1189 2.18764 11.3514 2.1349 11.6381C2.09295 11.8661 2.09295 12.1339 2.1349 12.3618C2.18764 12.6485 2.30554 12.881 2.39686 13.0611L2.41854 13.104C4.35823 16.956 7.71985 20 12.0001 20C14.2313 20 16.2129 19.1728 17.8736 17.8352L20.3861 19.7893C20.8221 20.1284 21.4503 20.0499 21.7894 19.6139C22.1285 19.178 22.0499 18.5497 21.614 18.2106L3.61399 4.21063ZM16.2411 16.5654L14.4434 15.1672C13.7676 15.6894 12.9201 16 12.0001 16C9.79092 16 8.00006 14.2091 8.00006 12C8.00006 11.4353 8.11706 10.898 8.32814 10.4109L6.24467 8.79044C5.46659 9.63971 4.77931 10.6547 4.20485 11.7955C4.17614 11.8525 4.15487 11.8948 4.13694 11.9316C4.12114 11.964 4.11132 11.9853 4.10491 12C4.11132 12.0147 4.12114 12.036 4.13694 12.0684C4.15487 12.1052 4.17614 12.1474 4.20485 12.2045C5.9597 15.6894 8.76726 18 12.0001 18C13.5314 18 14.9673 17.4815 16.2411 16.5654ZM10.0187 11.7258C10.0064 11.8154 10.0001 11.907 10.0001 12C10.0001 13.1046 10.8955 14 12.0001 14C12.2667 14 12.5212 13.9478 12.7538 13.8531L10.0187 11.7258Z" fill="#0F1729"/>
                                    <path d="M10.9506 8.13908L15.9995 12.0661C15.9999 12.0441 16.0001 12.022 16.0001 12C16.0001 9.79085 14.2092 7.99999 12.0001 7.99999C11.6369 7.99999 11.285 8.04838 10.9506 8.13908Z" fill="#0F1729"/>
                                    <path d="M19.7953 12.2045C19.4494 12.8913 19.0626 13.5326 18.6397 14.1195L20.2175 15.3467C20.7288 14.6456 21.1849 13.8917 21.5816 13.104L21.6033 13.0611C21.6946 12.881 21.8125 12.6485 21.8652 12.3618C21.9072 12.1339 21.9072 11.8661 21.8652 11.6381C21.8125 11.3514 21.6946 11.1189 21.6033 10.9389L21.5816 10.896C19.6419 7.04402 16.2803 3.99998 12.0001 3.99998C10.2848 3.99998 8.71714 4.48881 7.32934 5.32257L9.05854 6.66751C9.98229 6.23476 10.9696 5.99998 12.0001 5.99998C15.2329 5.99998 18.0404 8.31058 19.7953 11.7955C19.824 11.8525 19.8453 11.8948 19.8632 11.9316C19.879 11.964 19.8888 11.9853 19.8952 12C19.8888 12.0147 19.879 12.036 19.8632 12.0684C19.8453 12.1052 19.824 12.1474 19.7953 12.2045Z" fill="#0F1729"/>
                                </svg>
                            </div>
                            <div id="orderBTNCopy${ctaId}">${cta.showHTML || ''}</div>
                            ${buttonHTML ? `<div id="orderBTNArea${ctaId}">${buttonHTML}</div>` : ''}
                        </div>
                    </div>
                `;
        
                if ($('.webinarVideoCTA .ctaArea').length) {
                    $('.webinarVideoCTA .ctaArea').append(overlayCTA);
                } else {
                    console.error("Target element '.webinarVideoCTA .ctaArea' not found in the DOM.");
                }
            }
            
            this.styleOverlayCTA(ctaId, cta);
            
            // When overlay CTA is reloaded, unhide it if it was hidden
            const showIcon = $(`.cta-overlay-show-icon[data-cta-id="${ctaId}"]`);
            if (showIcon.length && showIcon.is(':visible')) {
                // CTA was hidden, unhide it after reload
                $(`#orderBTN${ctaId}`).show();
                showIcon.hide();
            }
            
            if (cta.iframe_ver) {
                // this.applyIframeVersion(`#orderBTNCopy${ctaId}`, cta.iframe_ver);
            }
    
            setTimeout(() => {
                if (typeof window.setWebinarPageDimensions === 'function') {
                    window.setWebinarPageDimensions();
                }
            }, 600);
        }
        styleOverlayCTA(ctaId, cta) {
            const orderBTN = $(`#orderBTN${ctaId}`);
            const boxAlignment = cta.box_alignment;
            const $timedUnderArea = $(`#overlayCta${ctaId} .timedUnderArea.test-7.hippopotemus`);
            
            $timedUnderArea.css("flex-direction", "column");
    
            // Set box alignment
            if (boxAlignment && boxAlignment.trim() === "flex-end") {
                $timedUnderArea.css({
                    'right': '10px',
                    'left': 'auto'
                });
            } else if (boxAlignment && boxAlignment.trim() === "flex-start") {
                $timedUnderArea.css({
                    'left': '10px',
                    'margin': '0'
                });
            } else if (boxAlignment && boxAlignment.trim() === "center") {
                $timedUnderArea.css({
                    'margin': 'auto',
                    'left': '0',
                    'right': '0'
                });
            } else {
                $timedUnderArea.css({
                    'margin': 'auto',
                    'left': '0',
                    'right': '0'
                });
            }

            if(cta.box_width === '100%'){
                $timedUnderArea.css('width', '97%');
            }else{
                $timedUnderArea.css('width', cta.box_width);
            }

            
            // Set background transparency
            this.setBackgroundTransparency(".timedUnderArea.test-7", cta.bg_transparency);
            
            // Show the CTA, but preserve manual hide state if user manually hid it
            // The show icon is only visible when user clicks hide, so check that as the indicator
            const showIcon = $(`.cta-overlay-show-icon[data-cta-id="${ctaId}"]`);
            const isManuallyHidden = showIcon.length && showIcon.is(':visible');
            
            if (!isManuallyHidden) {
                $('#webinarVideoCTA').addClass('webinarVideoCTAActive');
                orderBTN.show();
            }
    
            // Handle iframes
            $(`#orderBTNCopy${ctaId} iframe`).on("load", function () {
                const parentHeight = $('.webinarVideo').height();
                try {
                    const iframeDocument = this.contentDocument || this.contentWindow.document;
                    if (iframeDocument) {
                        const htmlElement = iframeDocument.documentElement;
                        htmlElement.style.setProperty("margin-top", "0px", "important");
                        htmlElement.style.setProperty("height", `${parentHeight-132}px`, "important");
                    }
                } catch (e) {
                    // Cross-origin; cannot access iframe's document
                }
            });
        }
        
        setBackgroundTransparency(element, transparency) {
			// Ensure transparency is between 0 and 100
			transparency = Math.max(0, Math.min(100, transparency));
			
			// Convert transparency to a scale from 0 (fully opaque) to 1 (fully transparent)
			let alpha = 1 - (transparency / 100);
			
			// Get the current background color of the element
			let currentBgColor = $(element).css("background-color");
			
			// If the current background color is not rgba, convert it
			if (currentBgColor && !currentBgColor.includes("rgba")) {
				let rgb = currentBgColor.match(/\d+/g); // Extract RGB values
				if (rgb && rgb.length >= 3) {
					currentBgColor = `rgba(${rgb[0]}, ${rgb[1]}, ${rgb[2]}, ${alpha})`;
				} else {
					// Fallback to black if background color is undefined or invalid
					currentBgColor = `rgba(0, 0, 0, ${alpha})`;
				}
			} else {
				// Update the alpha value if it's already rgba
				currentBgColor = currentBgColor.replace(/[\d\.]+\)$/g, alpha + ")");
			}
		
			// Apply the new background color with transparency
			$(element).css("background-color", currentBgColor);
		}
        getCTAHash(ctaId) {
            return sessionStorage.getItem(`hash${ctaId}`) || '';
        }
        
        setCTAHash(ctaId, hash) {
            sessionStorage.setItem(`hash${ctaId}`, hash);
        }
        
        removeCTAHash(ctaId) {
            sessionStorage.removeItem(`hash${ctaId}`);
        }
        
        getMainHash() {
            return sessionStorage.getItem('MainHash') || '';
        }
        
        setMainHash(hash) {
            sessionStorage.setItem('MainHash', hash);
        }
        
        removeMainHash() {
            sessionStorage.removeItem('MainHash');
        }
        
        // Hide CTA (when toggle is off, but CTA still exists in array)
        hideCTA(ctaId) {
            // Hide overlay CTA
            $(`#overlayCta${ctaId}`).css('display', 'none');
            
            // Hide sidebar CTA if it exists
            const tabElement = $(`#tab-cta-sidebar-tab${ctaId}`);
            if (tabElement.length) {
                const wasActive = tabElement.hasClass('active');
                
                // Hide the nav item completely
                tabElement.closest('.wi-nav-item').css('display', 'none');
                tabElement.removeClass('active').attr('aria-selected', 'false');
                
                // Remove active class from content (don't use inline display style)
                $(`#tab-new-tab-content${ctaId}`).removeClass('active');
                
                // If the hidden tab was active, activate another visible tab
                if (wasActive) {
                    const visibleNavItems = $('#webinarTabs .wi-nav-item:visible .wi-nav-link');
                    if (visibleNavItems.length > 0) {
                        const firstVisibleTab = visibleNavItems.first();
                        // Get the content ID from the href attribute (removes the # prefix)
                        // Fallback to aria-controls if href is not available
                        let firstContentId = firstVisibleTab.attr('href');
                        if (firstContentId && firstContentId.startsWith('#')) {
                            firstContentId = firstContentId.substring(1);
                        } else {
                            firstContentId = firstVisibleTab.attr('aria-controls');
                        }
                        
                        if (firstContentId) {
                            firstVisibleTab.addClass('active').attr('aria-selected', 'true');
                            $(`#${firstContentId}`).addClass('active');
                        }
                    }
                }
            }
        }
        
        // Completely remove CTA from DOM (when CTA is not in array)
        removeCTA(ctaId) {
            // Check if the tab being removed was active
            const tabElement = $(`#tab-cta-sidebar-tab${ctaId}`);
            const wasActive = tabElement.hasClass('active');
            
            // Remove overlay CTA completely
            $(`#overlayCta${ctaId}`).remove();
            
            // Remove sidebar tab and content completely
            tabElement.closest('.wi-nav-item').remove();
            $(`#tab-new-tab-content${ctaId}`).remove();
            
            // If the removed tab was active, activate another tab
            if (wasActive) {
                const visibleNavItems = $('#webinarTabs .wi-nav-item:visible .wi-nav-link');
                if (visibleNavItems.length > 0) {
                    const firstVisibleTab = visibleNavItems.first();
                    // Get the content ID from the href attribute (removes the # prefix)
                    // Fallback to aria-controls if href is not available
                    let firstContentId = firstVisibleTab.attr('href');
                    if (firstContentId && firstContentId.startsWith('#')) {
                        firstContentId = firstContentId.substring(1);
                    } else {
                        firstContentId = firstVisibleTab.attr('aria-controls');
                    }
                    
                    // Activate the first visible tab
                    if (firstContentId) {
                        firstVisibleTab.addClass('active').attr('aria-selected', 'true');
                        $(`#${firstContentId}`).addClass('active');
                    }
                }
            }
            
            // If no tabs left, adjust layout
            const remainingTabs = $('#webinarTabs .wi-nav-item .wi-nav-link');
            if (remainingTabs.length === 0) {
                $('#webinarVideo').removeClass('wi-col-lg-9');
                $('#webinarSidebar').removeClass('wi-col-lg-3');
            }
        }
        // Hide outer CTA (when toggle is off)
        hideOuterCTA(ctaId) {
            const tabElement = $(`#tab-cta-sidebar-tab${ctaId}`);
            const wasActive = tabElement.hasClass('active');
            
            // Hide the nav item completely
            tabElement.closest('.wi-nav-item').css('display', 'none');
            tabElement.removeClass('active').attr('aria-selected', 'false');
            
            // Remove active class from content (don't use inline display style)
            $(`#tab-new-tab-content${ctaId}`).removeClass('active');
    
            // If the hidden tab was active, activate another visible tab
            if (wasActive) {
                const visibleNavItems = $('#webinarTabs .wi-nav-item:visible .wi-nav-link');
                if (visibleNavItems.length > 0) {
                    const firstVisibleTab = visibleNavItems.first();
                    // Get the content ID from the href attribute (removes the # prefix)
                    // Fallback to aria-controls if href is not available
                    let firstContentId = firstVisibleTab.attr('href');
                    if (firstContentId && firstContentId.startsWith('#')) {
                        firstContentId = firstContentId.substring(1);
                    } else {
                        firstContentId = firstVisibleTab.attr('aria-controls');
                    }
                    
                    // Activate the first visible tab
                    if (firstContentId) {
                        firstVisibleTab.addClass('active').attr('aria-selected', 'true');
                        $(`#${firstContentId}`).addClass('active');
                    }
                }
            }
            
            // Check if any tabs are still visible
            if ($('#webinarTabs li:visible').length === 0) {
                console.log("No visible tabs remaining.");
                var webinarVideoElement = document.getElementById("webinarVideo");
                if (webinarVideoElement) {
                    webinarVideoElement.classList.remove("wi-col-lg-9");
                }
            }
        }
        
        // Show outer CTA (when toggle is on and CTA already exists)
        showOuterCTA(ctaId) {
            const existingTab = $(`#tab-cta-sidebar-tab${ctaId}`);
            const existingContent = $(`#tab-new-tab-content${ctaId}`);
            
            if (existingTab.length && existingContent.length) {
                // Show the nav item
                existingTab.closest('.wi-nav-item').css('display', 'block');
                $('#webinarSidebar').addClass("wi-col-lg-3");
                $('#webinarVideo').addClass("wi-col-lg-9");
                
                // Only make it active if no other tab is currently active
                const hasActiveTab = $('#webinarTabs .wi-nav-link.active:visible').length > 0;
                if (!hasActiveTab) {
                    // Remove active from all tabs first
                    $('#webinarTabs .wi-nav-link').removeClass('active').attr('aria-selected', 'false');
                    $('.wi-tab-content .wi-tab-pane').removeClass('active');
                    
                    // Make this tab active
                    existingTab.addClass('active').attr('aria-selected', 'true');
                    existingContent.addClass('active');
                }
            }
        }
        
        getTextColorFromBgColor(hexColor) {
			// Remove the hash symbol if it exists
			let hexCode = '';
			if(hexColor){
				hexCode = hexColor.replace(/^#/, '');	
			}

		
			// Expand shorthand hex color (e.g., #abc to #aabbcc)
			if (hexCode.length === 3) {
				hexCode = hexCode.split('').map(char => char + char).join('');
			}
		
			// Convert hex to RGB
			const r = parseInt(hexCode.substring(0, 2), 16);
			const g = parseInt(hexCode.substring(2, 4), 16);
			const b = parseInt(hexCode.substring(4, 6), 16);
		
			// Calculate the YIQ value
			const yiq = (r * 299 + g * 587 + b * 114) / 1000;
		
			// Return black or white based on the YIQ value
			return (yiq >= 198) ? 'black' : 'white';
		}
        
        handleOverlayCTA(ctaId, cta, ctaIdsToUpdate) {
            const btnColorA = this.getTextColorFromBgColor(cta.button_color);
            console.log("handle overlay cta overlay CTA",  ctaIdsToUpdate);
            if (ctaIdsToUpdate.has(ctaId)) {
                if (cta.air_toggle === "on") {
                    console.log("handle overlay cta overlay CTA on!!");
                    this.createOverlayCTA(ctaId, cta, btnColorA);
                } else if (cta.air_toggle === "off") {
                    // Hide CTA but keep in DOM (toggle is off but CTA still in array)
                    this.hideOverlayCTA(ctaId);
                }
            } else if (cta.air_toggle === "on") {
                // If no update needed but toggle is on, ensure it's visible
                // BUT only if user hasn't manually hidden it
                // The show icon is only visible when user clicks hide, so check that as the indicator
                const showIcon = $(`.cta-overlay-show-icon[data-cta-id="${ctaId}"]`);
                const isManuallyHidden = showIcon.length && showIcon.is(':visible');
                
                if (!isManuallyHidden) {
                    console.log("handle overlay cta overlay CTA on!!! show overlay CTA");
                    this.showOverlayCTA(ctaId);
                } else {
                    console.log("handle overlay cta overlay CTA on but manually hidden, skipping show");
                }
            } else if (cta.air_toggle === "off") {
                // If no update needed but toggle is off, ensure it's hidden
                this.hideOverlayCTA(ctaId);
            }
        }
        
        // Hide overlay CTA (when toggle is off)
        hideOverlayCTA(ctaId) {
            $(`#overlayCta${ctaId}`).css('display', 'none');
            // Hide the overlay container if it exists
            $(`#orderBTN${ctaId}`).css('display', 'none');
        }
        
        // Show overlay CTA (when toggle is on and CTA already exists)
        showOverlayCTA(ctaId) {
            const existingOverlay = $(`#overlayCta${ctaId}`);
            
            if (existingOverlay.length) {
                existingOverlay.css('display', 'block');
                $(`#orderBTN${ctaId}`).css('display', 'block');
                $('#webinarVideoCTA').addClass('webinarVideoCTAActive');
            }
        }
    }
        
    var webinar = window.WEBINARIGNITION.webinar;
    var $lead_id      = window.WEBINARIGNITION.lead_id;
    var lead_info = window.WEBINARIGNITION.lead_info;
    var ajaxurl = window.WEBINARIGNITION.ajax_url;
    var webinar_page = window.WEBINARIGNITION.webinar_page;
    var nonce = window.WEBINARIGNITION.nonce;
    var $webinar_type = 'AUTO' === webinar.webinar_date ? 'evergreen' : 'live';
    var ip = window.WEBINARIGNITION.ip;
    var lid = window.WEBINARIGNITION.lid;
    var globalOffset = 0;
    var isPreview = new URLSearchParams(window.location.search).has('preview');
    var $is_webinar_page = window.WEBINARIGNITION.is_webinar_page;
    var $is_alt_webinar_page = window.WEBINARIGNITION.is_alt_webinar_page;
    var ctaManager = new WebinarCTAManager({
        webinar: webinar,
        nonce: nonce,
        lead_id: $lead_id,
        ip: ip,
        isPreview: isPreview
    });
    
    // Clean up when needed
    $(window).on('beforeunload', function() {
        if (typeof ctaManager !== 'undefined' && ctaManager.destroy) {
            ctaManager.destroy();
        }
    });
    
    // Also clean up on page hide (mobile browsers)
    $(window).on('pagehide', function() {
        if (typeof ctaManager !== 'undefined' && ctaManager.destroy) {
            ctaManager.destroy();
        }
    });

}(jQuery))
