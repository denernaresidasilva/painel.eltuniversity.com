
jQuery(document).ready(function ($) {
	var webinar_data = window.WEBINARIGNITION.webinar;
	var ajaxurl = webinar_data.ajax_url;
	var security = webinar_data.security;
	var webinar_id = webinar_data.webinar_id;
	var input_get = webinar_data.input_get;
	var webinar_date = webinar_data.webinar_date;
	var lead_timezone = webinar_data.lead_timezone;
	var webinar_timezone = webinar_data.webinar_timezone;
	var auto_replay = webinar_data.auto_replay;
	var date_picked_and_live = webinar_data.date_picked_and_live;
	var replay_cd_date = webinar_data.replay_cd_date;
	var replay_optional = webinar_data.replay_optional;
	var is_multiple_cta_enabled = webinar_data.webinar_is_multiple_cta_enabled;
	var current_time = window.WEBINARIGNITION.current_time;
	var autoTZ   = window.WEBINARIGNITION.autoTZ;

	function formatDateToMySQL() {
        var date = new Date();
        
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2); // Months are 0-based
        var day = ('0' + date.getDate()).slice(-2);
        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        var seconds = ('0' + date.getSeconds()).slice(-2);

        return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;
    }

	if (webinar_date == 'AUTO') {

			var addDays = 3;
			if (auto_replay != '') {
					addDays = parseInt(auto_replay);
			}

			var liveDate = formatDateToMySQL(); 
			if (date_picked_and_live && date_picked_and_live === '') {
					liveDate = date_picked_and_live;
			}
			autoTZ = (autoTZ < 0 ? autoTZ : '+' + autoTZ);
	} else {
			var expire = replay_cd_date;
	}

	if (expire) {
			var exDate;
			if (expire.indexOf('-') !== -1) {
					exDate = expire.split('-');
			} else {
					exDate = expire.split('/');
			}
	}

	if (exDate && exDate.length === 3) {
			var austDay;
			if (webinar_date == 'AUTO') {
					var exYear = exDate[0];
					var exMonth = exDate[1];
					var exDay = exDate[2];
					austDay = jQuery.wi_countdown.UTCDate(autoTZ, exYear ? exYear : '0', exMonth ? exMonth : '0' - 1, exDay ? exDay : '0');
			} else {
					var exYear = exDate[2];
					var exMonth = exDate[0];
					var exDay = exDate[1];
					var expire_time = webinar_data.replay_cd_time;
					if (expire_time == '') {
							var expire_time_hour = '00';
							var expire_time_minute = '00';
					} else {
							expire_time = expire_time.split(':');
							var expire_time_hour = expire_time[0];
							var expire_time_minute = expire_time[1];
					}
					var tz = new DateTimeZone(webinar_timezone);
					austDay = jQuery.wi_countdown.UTCDate(tz.getOffset(new DateTime()) / 3600, exYear ? exYear : '0', exMonth ? exMonth : '0' - 1, exDay ? exDay : '0', expire_time_hour, expire_time_minute, '0o0');
			}
	}

	if (exDate && exDate.length === 3 && replay_optional && replay_optional !== 'hide') {
			if (!webinar_data.auto_replay || webinar_data.auto_replay != 0) {
					jQuery('#cdExpire').wi_countdown({
							until: austDay,
							onExpiry: webinarignition_closeWebinar,
							alwaysExpire: true,
							labels: ['Years', webinar_data.cd_months, webinar_data.cd_weeks, webinar_data.cd_days, webinar_data.cd_hours, webinar_data.cd_minutes, webinar_data.cd_seconds],
							labels1: ['Year', webinar_data.cd_months, webinar_data.cd_weeks, webinar_data.cd_days, webinar_data.cd_hours, webinar_data.cd_minutes, webinar_data.cd_seconds]
					});
			}
	}

	var input_cookie = document.cookie;

	if (input_cookie) {
			if (input_cookie['we-trk-replay' + webinar_id]) {
					var getTrackingCookie = input_cookie['we-trk-replay' + webinar_id];

					if (getTrackingCookie != "tracked") {
							jQuery.cookie('we-trk-replay-' + webinar_id, "tracked", {expires: 30});
							var data = {
									action: 'webinarignition_track_view',
									security: security,
									id: webinar_id,
									page: "replay"
							};
							jQuery.post(ajaxurl, data, function (results) {});
					}
			}
	}

	// var data = {
	// 		action: 'webinarignition_track_view_total',
	// 		security: security,
	// 		id: webinar_id,
	// 		page: "replay"
	// };
	// jQuery.post(ajaxurl, data, function (results) {});

	jQuery(".ctaArea").find("embed, object").height(518).width(920);

	jQuery('#askQuestion').on('click', function () {
			function validateEmail(email) {
					var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
					return re.test(email);
			}

			if (!validateEmail($Email)) {
					jQuery('#optEmail').addClass("errorField");
					return false;
			}

			var question = jQuery("#question").val();

			if (question == "") {
					jQuery("#question").addClass("errorField");
			} else {
					jQuery("#question").removeClass("errorField");
					var data = {
							action: 'webinarignition_submit_question',
							security: security,
							id: webinar_id,
							question: "" + question + ""
					};
					jQuery.post(ajaxurl, data, function (results) {
							jQuery("#askQArea").hide();
							jQuery("#askQThankyou").show();

							setTimeout(function () {
									jQuery("#askQArea").show();
									jQuery("#askQThankyou").hide();
									jQuery("#question").val("");
							}, 15000);
					});
			}

			return false;
	});

	function webinarignition_closeWebinar() {
		// hide webinar
		var closeWebinarIntervalID = setInterval(function () {

			// wrap in timeout otherwise audio still plays
			jQuery(".webinarBlock").hide();
			jQuery(".webinarExtraBlock").hide();
			jQuery(".webinarBlockRight").hide();
			if (window.myPlayer) {
				window.myPlayer.dispose();
				clearInterval(closeWebinarIntervalID);
			}

			// Replace any video area here..
			jQuery(".ctaArea").html("closed");

			// show closed area
			jQuery("#closed").show();
		}, 10);

	}
	if (webinar_data.webinar_date === 'AUTO') {
		var webinar_source_toggle = webinar_data.webinar_source_toggle || '';
		var is_webinar_iframe_source = webinar_data.webinar_iframe_source || false;

		if (webinar_data.auto_action === 'time' && is_webinar_iframe_source === 'iframe') {
				var delay = 10;

				if (webinar_data.auto_action_time) {
						var auto_action_time_array = webinar_data.auto_action_time.split(':');
						delay += (parseInt(auto_action_time_array[0], 10) || 0) * 60000;
						delay += (parseInt(auto_action_time_array[1], 10) || 0) * 1000;
				}

				var start_delay = delay;

				if (webinar_data.auto_action_time_end) {
						var auto_action_time_end_array = webinar_data.auto_action_time_end.split(':');
						delay = 0;
						delay += (parseInt(auto_action_time_end_array[0], 10) || 0) * 60000;
						delay += (parseInt(auto_action_time_end_array[1], 10) || 0) * 1000;
				}

				var end_delay = delay;

				setTimeout(timedAction, start_delay);

				if (end_delay > start_delay) {
						setTimeout(timedEndAction, end_delay);
				}

				if (is_multiple_cta_enabled) {
						var additional_autoactions = webinar_data.additional_autoactions || [];

						additional_autoactions.forEach(function(additional_autoaction, index) {
								if (additional_autoaction.auto_action_time) {
										var auto_action_time_array = additional_autoaction.auto_action_time.split(':');
										var delay = 10;
										delay += (parseInt(auto_action_time_array[0], 10) || 0) * 60000;
										delay += (parseInt(auto_action_time_array[1], 10) || 0) * 1000;

										var start_delay = delay;

										if (additional_autoaction.auto_action_time_end) {
												var auto_action_time_end_array = additional_autoaction.auto_action_time_end.split(':');
												delay = 0;
												delay += (parseInt(auto_action_time_end_array[0], 10) || 0) * 60000;
												delay += (parseInt(auto_action_time_end_array[1], 10) || 0) * 1000;
										}

										var end_delay = delay;

										setTimeout(function() {
												var orderBTN = $("#orderBTN");
												orderBTN.hide();
												var orderBTNCopyHtml = $('#orderBTNCopy_' + index).html();
												var orderBTNAreaHtml = $('#orderBTNArea_' + index).html();

												setTimeout(function() {
														$('#orderBTNCopy').html(orderBTNCopyHtml);
														$('#orderBTNArea').html(orderBTNAreaHtml);
														var pre_hurrytimer = $('#orderBTNCopy').find('.pre-hurrytimer-campaign');

														if (pre_hurrytimer.length) {
																pre_hurrytimer.each(function() {
																		$(this).addClass('hurrytimer-campaign');
																});
														}

														orderBTN.show();
														orderBTN.data('cta-index', 'additional-' + index);
												}, 1000);
										}, start_delay);

										if (end_delay > start_delay) {
												setTimeout(function() {
														var orderBTN = $("#orderBTN");
														var ctaIndex = orderBTN.data('cta-index');

														if ('additional-' + index === ctaIndex) {
																orderBTN.hide();
														}
												}, end_delay);
										}
								}
						});
				}

				function timedAction() {
						var orderBTN = $("#orderBTN");
						orderBTN.show();

						var pre_hurrytimer = $('#orderBTNCopy').find('.pre-hurrytimer-campaign');

						if (pre_hurrytimer.length) {
								pre_hurrytimer.each(function() {
										$(this).addClass('hurrytimer-campaign');
								});
						}

						orderBTN.data('cta-index', 'default');
				}

				function timedEndAction() {
						var orderBTN = $("#orderBTN");
						var ctaIndex = orderBTN.data('cta-index');

						if ('default' === ctaIndex) {
								orderBTN.hide();
						}
				}
		}
	} else {
		// Only For Live Webinars
		if (webinar_data.replay_order_time === '') {
				// NO TIME SET - SHOW BUTTON
				$("#orderBTN").show();
				$('.webinarVideoCTA').addClass('webinarVideoCTAActive');
		} else {
				// TIME IS SET
				setTimeout(timedAction, (webinar_data.replay_order_time === '') ? 50 : webinar_data.replay_order_time * 1000);

				function timedAction() {
						$("#orderBTN").show();
						$('.webinarVideoCTA').addClass('webinarVideoCTAActive');
				}
		}
	}

	var is_auto_login_enabled = webinar_data.is_auto_login_enabled;

	if ((is_auto_login_enabled && webinar_data.is_user_logged_in || !is_auto_login_enabled) && webinar_data.webinarignition_page !== 'preview-replay') {
		// Track Event Attending
		var checkCookie = $.cookie('we-trk-' + webinar_data.webinar_id);

		// Post & Track
		if (webinar_data.input_get?.lid) {
				$.post(webinar_data.ajax_url, {
						action: 'webinarignition_update_view_status',
						security: webinar_data.security,
						id: webinar_data.webinar_id,
						lead_id: webinar_data.input_get?.lid
				});
		}
	}

});
