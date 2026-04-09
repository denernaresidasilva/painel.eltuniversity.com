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

	jQuery.expr.pseudos.parents = function (a, i, m) {
		return jQuery(a).parents(m[3]).length < 1;
	};

	(function ($) {
		if ( $.trim(lead_info) !== '' && ( $is_webinar_page || $is_alt_webinar_page ) ) {
			
			$(window).on('beforeunload', function(e) {
				e.preventDefault();
				webinarignition_mark_lead_status('complete');

				return null;
			});
		}

		if ( webinar_page === 'webinar' || 'webinar' === webinar_page ) {
			// TRACK +1 VIEW
			if (typeof $.cookie === "function") {
				$getTrackingCookie = $.cookie('we-trk-live-' + webinar.id );

				// if not tracked yet
				if ($getTrackingCookie != "tracked") {
					// No Cookie Set - Track View
					$.cookie('we-trk-live-' + webinar.id, "tracked", {expires: 30});
					var data = {
						action: 'webinarignition_track_view',
						security:nonce, 
						id: webinar.id,
						page: "live"
					};
					$.post(ajaxurl, data, function (results) {
					});
				}
			}
			// Track +1 Total
			var data = {
				action: 'webinarignition_track_view_total',
				security:nonce, 
				id: webinar.id,
				page: "live"
			};
			$.post(ajaxurl, data, function (results) {
			});
		}

		// VIDEO FIXES:
		$(".ctaArea").find("embed, object").height(518).width(920);

		var $lead_id               = window.WEBINARIGNITION.lead_id; 
		var $is_auto_login_enabled = window.WEBINARIGNITION.is_auto_login_enabled;
		var $is_user_registered = window.WEBINARIGNITION.is_user_registered;
		
		if ( ( $is_user_registered || ! $is_auto_login_enabled ) && ( $is_webinar_page || $is_alt_webinar_page ) )  {
			if (typeof $.cookie === "function") {
				// Track Event Attendance
				$checkCookie = $.cookie('we-trk-' + webinar.id );
				// Post & Track
				$.post(ajaxurl, {
					action: 'webinarignition_update_view_status',
					security: nonce, 
					id: webinar.id, 
					lead_id: $lead_id 
				});
			}

			if ( lead_info !== '' ) { 
				webinarignition_mark_lead_status('attending');
			}

			if ( 'hide' !== webinar.webinar_qa ) {
				// Get Name / Email
				if ( 'AUTO' === webinar.webinar_date ) {
					var data = {
						action: 'webinarignition_get_qa_name_email2',
						security:nonce, 
						cookie: $lead_id, 
						ip: lid 
					};
				} else {
					var data = {
						action: 'webinarignition_get_qa_name_email',
						security:nonce, 
						cookie: lid, 
						ip: ip 
					};
				}

				let optNameVal = '';
				if( $('#optName').length > 0 ) {
					optNameVal = $('#optName').val();
				}

				let optEmailVal = '';
				if( $('#optEmail').length > 0 ) {
					optEmailVal = $('#optEmail').val();
				}

				let leadIDVal = '';
				if( $('#leadID').length > 0 ) {
					leadIDVal = $('#leadID').val();
				}

				if ('' === optNameVal || '' === optEmailVal || '' === leadIDVal) {
					$.post(ajaxurl, data, function (results) {
						if (results) {
							$qaInfo = results.split("//");

							if ('' === optNameVal.trim()) {
								$("#optName").val($qaInfo[0] !== 'undefined' ? $qaInfo[0] : "");
							}

							if ('' === optEmailVal.trim()) {
								$("#optEmail").val($qaInfo[1]);
							}

							if ('' === leadIDVal.trim()) {
								$("#leadID").val($qaInfo[2]);
							}
							
							// $("#optName").attr("disabled","disabled");
							// $("#optEmail").attr("disabled","disabled");
						}
					});
				}
			}
		}

		if ( webinar.hasOwnProperty( 'webinar_date') && webinar.hasOwnProperty( 'live_stats') ) { 
		}//end if
		function webinarignition_mark_lead_status(status, always_callback) {
			return $.ajax({
				url: ajaxurl,
				method: 'POST',
				dataType: 'JSON',
				async: (/Firefox[\/\s](\d+)/.test(navigator.userAgent) && new Number(RegExp.$1) >= 4) === false,
				data: {
					action: 'webinarignition_lead_mark_' + status,
					nonce: nonce,
					webinar_id: webinar.id, 
					lead_id: $lead_id 
				},
				always: function(xhr, xhr_status, xhr_error) {
					if( always_callback && typeof always_callback === 'function' ) {
						always_callback(xhr, xhr_status, xhr_error);
					}
				}
			});
		}
		
	})(jQuery);
