(function ($) {
	var ajaxurl = bFwebinarData.ajaxurl;
	var webinar_id = bFwebinarData.webinar_id;
	window.wiRegJS = {};
	wiRegJS.ajax_nonce = bFwebinarData.ajax_nonce;

	var zoom_container = $('#zoom_video_uri');

	if (zoom_container.length) {
			zoom_container.hide();

			$('#zoom_video_uri iframe').on('load', function () {
					$('#zoom_video_uri iframe').contents().find('.vczapi-zoom-browser-meeting--info').remove();

					if (bFwebinarData.lead_name) {
							var lead_name = bFwebinarData.lead_name;
							var lead_name_field = $('#zoom_video_uri iframe').contents().find('#vczapi-jvb-display-name');

							if (lead_name_field.length) {
									lead_name_field.val(lead_name);
							}
					}

					if (bFwebinarData.lead_email) {
							var lead_email = bFwebinarData.lead_email;
							var lead_email_field = $('#zoom_video_uri iframe').contents().find('#vczapi-jvb-email');

							if (lead_email_field.length) {
									lead_email_field.val(lead_email);
							}
					}
					console.log('iframe loaded');

					zoom_container.show();
			});
	}
	$(document).ready(function(){
		$('#askQuestion').on('click', function () {
				function validateEmail(email) {
						var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
						return re.test(email);
				}
	
				var $question = $("#question").val();
				var $Name = $("#optName").val();
				var $Email = $("#optEmail").val();
				var $ID = $("#leadID").val();
	
				if (!validateEmail($Email)) {
						$('#optEmail').addClass("errorField");
						return false;
				}
	
				const is_first_question = localStorage.getItem('webinar_' + webinar_id + '_question_submitted') ? false : true;
	
				if ($question == "") {
						$("#question").addClass("errorField");
				} else {
						$("#question").removeClass("errorField");
	
						var video_live_time = new Date(bFwebinarData.video_live_time).getTime();
						var timeNow = Date.now();
						var timeDifference = timeNow - video_live_time;
						var webinarTime = (timeDifference) / 60000;
	
						var data = {
								action: 'webinarignition_submit_question',
								security: wiRegJS.ajax_nonce,
								id: webinar_id,
								question: "" + $question + "",
								name: "" + $Name + "",
								email: "" + $Email + "",
								lead: "" + $ID + "",
								webinar_type: bFwebinarData.webinar_type,
								is_first_question: is_first_question,
								webinarTime: webinarTime.toFixed()
						};
	
						$("#askQArea").hide();
						$("#askQThankyou").show();
	
						$.post(ajaxurl, data, function (results) {
								if (is_first_question && results) {
										localStorage.setItem('webinar_' + webinar_id + '_question_submitted', true);
								}
	
								setTimeout(function () {
										$("#askQArea").show();
										$("#askQThankyou").hide();
										$("#question").val("");
								}, 15000);
						});
				}
	
				return false;
		});
	})
	

	if (bFwebinarData.is_auto_webinar) {
			function maybeSendAfterWebinarQuestionsNotification() {
					const is_after_auto_webinar_questions_sent = localStorage.getItem('after_auto_webinar_' + webinar_id + '_questions_sent') ? true : false;

					if (is_after_auto_webinar_questions_sent) {
							return;
					}

					var attendeeName = $("#optName").val();
					var attendeeEmail = $("#optEmail").val();
					var leadID = $("#leadID").val();

					var data = {
							action: 'webinarignition_after_auto_webinar',
							security: wiRegJS.ajax_nonce,
							webinar_id: webinar_id,
							name: "" + attendeeName + "",
							email: "" + attendeeEmail + "",
							lead: "" + leadID + ""
					};

					if (!is_after_auto_webinar_questions_sent) {
							$.post(ajaxurl, data, function (results) {
									localStorage.setItem('after_auto_webinar_' + webinar_id + '_questions_sent', true);
							});
					}
			}

			function handleBeforeUnload(e) {
				e.preventDefault();
				maybeSendAfterWebinarQuestionsNotification();
				return undefined;
			}
			
			window.addEventListener('beforeunload', handleBeforeUnload);
			
			// Later, when you want to remove it
			window.removeEventListener('beforeunload', handleBeforeUnload);
			
	} else {
			if (typeof webinarignition_check_qna_enabled === "function") {
					webinarignition_check_qna_enabled();
			}
	}

	function webinarignition_check_qna_enabled() {
			var webinarExtraBlock = $('.webinarExtraBlock');

			$.post(ajaxurl, {
					action: 'webinarignition_check_if_q_and_a_enabled',
					security: wiRegJS.ajax_nonce,
					webinar_id: webinar_id
			}, function (results) {
					if (results && results.data && results.data.enable_qa && (results.data.enable_qa == 'no')) {
							webinarExtraBlock.hide();
					} else {
							webinarExtraBlock.show();
					}
			});
	}

	function webinarignition_iframe_init() {
		var iframeBlocks = $("#vidBox").find("iframe");
			var webinarVideo = $('#webinarContent');

			if (webinarVideo.length) {
        document.body.classList.add('webinar_page_modern');
      }

			if (iframeBlocks.length) {
					var isModern = webinarVideo.length;

					if (!isModern) {
							iframeBlocks.each(function () {
									var iframeBlock = $(this);
										var styles = {
											"position": "absolute",
											"width": "100%",
											"height": "100%",
											"left": "0",
											"top": "0"
										};

										iframeBlock.css(styles);

										iframeBlock.wrap("<div class='ctaAreaVideo-aspect-ratio' style='position: relative;width: 100%;height: 0;padding-bottom: 56.25%;'></div>");
							});
					}
			}
	}

	$(document).ready(function () {
			webinarignition_iframe_init();
	});

	window.addEventListener('video_sdk_iframe_loaded', function () {
    // webinarignition_iframe_init();
  });
})(jQuery);
