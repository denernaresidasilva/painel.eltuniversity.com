(function ($) {
	$(document).on("ready", function () {
		var webinar = window.WEBINARIGNITION.webinar;
		var current_user_can = window.WEBINARIGNITION.current_user_can;
		var home_url = window.WEBINARIGNITION.home_url;
		var current_user = window.WEBINARIGNITION.current_user;
		var translations = window.WEBINARIGNITION.translations;
		var webinar_user_role = window.WEBINARIGNITION.webinar_user_roles;
		var ajax_url = window.WEBINARIGNITION.ajax_url;
		var nonce = window.WEBINARIGNITION.nonce;
		var webinar_type = window.WEBINARIGNITION.webinar_type;
		var order_id = window.WEBINARIGNITION.order_id;
		var watermark = window.WEBINARIGNITION.assets.watermark;
		var is_support = window.WEBINARIGNITION.is_support;

		// Data getting from html custom attributes will goes here
		let webinarId = $('div[rel="js-webinar-id"]').attr("data-webinar-id");
		let thankYouSection = $('section[rel="js-thank-you-url"]');
		let dataProvider = document.querySelector('[rel="js-data-provider"]');

		if ("hide" !== webinar.webinar_qa) {
			$("#main-content").addClass("et_smooth_scroll_disabled");
		}

		const wiStorageUtil = {
			set: (key, value) => {
			  try {
				localStorage.setItem(key, JSON.stringify(value));
			  } catch (e) {
				console.error(`Error setting ${key} in localStorage:`, e);
			  }
			},
			get: (key) => {
			  try {
				const value = localStorage.getItem(key);
				return value ? JSON.parse(value) : null;
			  } catch (e) {
				console.error(`Error getting ${key} from localStorage:`, e);
				return null;
			  }
			},
			remove: (key) => {
			  try {
				localStorage.removeItem(key);
			  } catch (e) {
				console.error(`Error removing ${key} from localStorage:`, e);
			  }
			}
		  };

		if (webinar.webinar_ld_share === "on") {
			IN.init({
				lang: "en_US",
				url: webinar.webinar_permalink,
				counter: "right",
			});
		}

		if ("draft" === webinar.webinar_status && !current_user_can.edit_posts) {
			setTimeout(function () {
				window.location = home_url;
			}, 20000);
		}
		if($('#user-purchase-cookie').length){
		$.post(
			ajax_url,
			{
				action: 'webinarignition_track_order',
				security: nonce,
				id: webinar.id,
				lead: $.cookie('we-trk-' + webinar.id )
			}
		);
		}
		// facebook share
		window.fbAsyncInit = function () {
			FB.init({
				appId: "178580152294594",
				status: true,
				cookie: true,
				xfbml: true,
			});

			// FACEBOOK LIKE/SHARE
			FB.Event.subscribe("edge.create", function (response) {
				$(".sharePRE").hide();
				$(".shareREVEAL").show();
				jQuery(".sharePRE").hide();
				jQuery(".shareREVEAL").show();
			});
		};

		(function() {
			var fbRoot = document.getElementById("fb-root");

			if (fbRoot) {
				var e = document.createElement("script");
				e.async = true;
				e.src = document.location.protocol + "//connect.facebook.net/en_US/all.js";
				fbRoot.appendChild(e);
			}
		})();

		//Twitter Widgets JS
		// window.twttr = (function (d, s, id) {
		// 	var t,
		// 		js,
		// 		fjs = d.getElementsByTagName(s)[0];
		// 	if (d.getElementById(id)) return;
		// 	js = d.createElement(s);
		// 	js.id = id;
		// 	js.src = "https://platform.twitter.com/widgets.js";
		// 	fjs.parentNode.insertBefore(js, fjs);
		// 	return (
		// 		window.twttr ||
		// 		(t = {
		// 			_e: [],
		// 			ready: function (f) {
		// 				t._e.push(f);
		// 			},
		// 		})
		// 	);
		// })(document, "script", "twitter-wjs");

		//Once twttr is ready, bind a callback function to the tweet event
		// twttr.ready(function (twttr) {
		// 	twttr.events.bind("tweet", function (event) {
		// 		jQuery(".sharePRE").hide();
		// 		jQuery(".shareREVEAL").show();
		// 	});
		// });

		// Questions.php
		(function ($) {
			$(document.body).on("click", ".email-qa-enable", function () {
				var parent = jQuery(this).parents(".switch");
				parent.find(".email-qa-disable").removeClass("selected");
				$(this).addClass("selected");
				$("#EmailQAToggle").val("on");
				$("#EmailQAContainer").show();
			});

			$(document.body).on("click", ".email-qa-disable", function () {
				var parent = $(this).parents(".switch");
				parent.find(".email-qa-enable").removeClass("selected");
				$(this).addClass("selected");
				$("#EmailQAToggle").val("off");
				$("#EmailQAContainer").hide();
			});

			$(document.body).on("click", ".email-qaMore-enable", function () {
				var parent = jQuery(this).parents(".switch");
				parent.find(".email-qaMore-disable").removeClass("selected");
				$(this).addClass("selected");
				$("#EmailQAMoreToggle").val("on");
				$("#EmailQAMoreContainer").show();
			});

			$(document.body).on("click", ".email-qaMore-disable", function () {
				var parent = $(this).parents(".switch");
				parent.find(".email-qaMore-enable").removeClass("selected");
				$(this).addClass("selected");
				$("#EmailQAMoreToggle").val("off");
				$("#EmailQAMoreContainer").hide();
			});
		})(jQuery);

		jQuery(document).ready(function ($) {
			var overLay = $("#overlay"),
				answerForm = $("#answerForm"),
				answerFormContainer = $("#answerFormContainer"),
				closeAnswerForm = $("#closeAnswerForm");

			var answerMoreForm = $("#answerMoreForm"),
				answerMoreFormContainer = $("#answerMoreFormContainer"),
				closeAnswerMoreForm = $("#closeAnswerMoreForm");

			// QA Tabs
			$("#qa-done").on("click", function () {
				$(".questionTabIt").removeClass("questionTabSelected");
				$(this).addClass("questionTabSelected");
				$("#QAActive").hide();
				$("#QADone").show();
				return false;
			});

			$("#qa-active").on("click", function () {
				$(".questionTabIt").removeClass("questionTabSelected");
				$(this).addClass("questionTabSelected");
				$("#QADone").hide();
				$("#QAActive").show();
				return false;
			});

			/**
			 * @todo temporarily writing if statement but later we should transfer the console
			 * code to a sepereate file e.g webinarignition-console.js
			 *
			 * @Faheem
			 */

			$(".qa-lead-search").on("click", function () {
				var $getEmail = $(this).text();
				oTable.fnFilter($getEmail);
				$(".consoleTabs").hide();
				$("#leadTab").show();
				$(".dashTopBTN").removeClass("success");
				$(".dashTopBTN").addClass("secondary");
				$(".dashTopBTN").addClass("lc-btn");
				$("#leadTabBTN").addClass("secondary");
				$("#leadTabBTN").removeClass("lc-btn");
				$("#leadTabBTN").addClass("success");
				return false;
			});

			$.ajax({
				type: "post",
				url: ajax_url,
				data: {
					id: webinar.id,
					action: "webinarignition_get_questions",
					security: nonce,
					webinar_type: webinar_type,
					limit: 1000,
					offset: 0,
					is_support: (webinar_user_role && webinar_user_role.is_support) ? webinar_user_role.is_support : false,
				},
				success: function (response) {
					if (response && response.data && response.data.active_questions) {
						jQuery("#active_questions").html(response.data.active_questions);
					}

					if (response && response.data && response.data.answered_questions) {
						jQuery("#answered_questions").html(
							response.data.answered_questions
						);
					}

					$("#dashTotalQ").text(response.data.total_questions);
					$("#dashTotalActiveQ").text(response.data.total_active_questions);
				},
			});

			var markQuestionAsAnswered = function (questionId) {
				$("#QA-BLOCK-" + questionId)
					.detach()
					.appendTo("#answered_questions");
				$("#qbi-answer-" + questionId).remove();

				$totalActive = $("#totalQAActive").text();
				$totalActive = parseInt($totalActive);
				$totalDone = $("#totalQADone").text();
				$totalDone = parseInt($totalDone);
				$dashTotalActiveQ = $("#dashTotalActiveQ").text();
				$dashTotalActiveQ = parseInt($dashTotalActiveQ);

				if ($totalActive != 0) {
					$totalActive = $totalActive - 1;
					$totalDone = $totalDone + 1;
					$("#totalQAActive").text($totalActive);
					$("#totalQADone").text($totalDone);
					$dashTotalActiveQ = $dashTotalActiveQ - 1;
					$("#dashTotalActiveQ").text($dashTotalActiveQ);
				}
			};

			let objectifyForm = function (formArray) {
				let returnObj = {};
				for (let i = 0, len = formArray.length; i < len; i++) {
					returnObj[formArray[i]["name"]] = formArray[i]["value"];
				}
				return returnObj;
			};

			let holdOrReleaseConsoleQuestion = function (questionId, hold) {
				let questionData = {};
				questionData.action =
					"webinarignition_hold_or_release_console_question";
				questionData.webinarId = webinar.id;
				questionData.security = nonce;
				questionData.questionId = questionId;
				questionData.hold = hold;
				questionData.supportName = current_user.data.display_name;
				questionData.supportId = current_user.ID;

				$.post(ajax_url, questionData);
			};

			closeAnswerForm.on("click", function () {
				$(".questionOnHold").remove();
				answerFormContainer.hide();
				let questionId = $("#questionId").val();
				holdOrReleaseConsoleQuestion(questionId, false);
			});

			closeAnswerMoreForm.on("click", function () {
				answerMoreFormContainer.hide();
			});

			answerForm.on("submit", function (event) {
				event.preventDefault();

				overLay.show();

				let formData = answerForm.serializeArray();
				let answerData = objectifyForm(formData);

				var answer = wp.editor.getContent('answerContent');
				var answerText = wp.editor.getContent('answerText');

				answer = answer.replace("{ANSWER}", answerText);

				answerData.action = "webinarignition_answer_attendee_question";
				answerData.webinarId = webinar.id;
				answerData.security = nonce;
				answerData.answer = answer;
				answerData.answerText = answerText;
				answerData.attendeeQuestion = $("#attendeeQuestion").val();

				if (!answerData.answerText) return;

				$.post(ajax_url, answerData, function (response) {
					markQuestionAsAnswered(answerData.questionId);
					jQuery("#answerFormContainer").hide("slow", function () {
						if (response.success && response.success === true) {
							alert(translations.answer_sent_successfully);
						}
						overLay.hide();
						if (tinyMCE.get('answerContent')) {
							tinyMCE.get('answerContent').setContent('');
						}
						if (tinyMCE.get('answerText')) {
							tinyMCE.get('answerText').setContent('');
						}
					});
				});
			});

			if (answerMoreForm.length) {
				answerMoreForm.on("submit", function (event) {
					event.preventDefault();

					overLay.show();

					let formData = answerMoreForm.serializeArray();
					let answerData = objectifyForm(formData);

					answer = wp.editor.getContent('answerMoreContent');
					answerText = wp.editor.getContent('answerMoreText');
					
					answer = answer.replace("{ANSWER}", answerText);

					answerData.action = "webinarignition_answer_attendee_question";
					answerData.webinarId = webinar.id;
					answerData.security = nonce;
					answerData.answer = answer;
					answerData.answerText = answerText;
					answerData.attendeeQuestion = $("#attendeeMoreQuestion").val();
					answerData.isAnswerMore = "on";

					if (!answerData.answerText) return;

					$.post(ajax_url, answerData, function (response) {
						jQuery("#answerMoreFormContainer").hide("slow", function () {
							if (response.success && response.success === true) {
								alert(translations.answer_sent_successfully);
							}
							overLay.hide();
							if (tinyMCE.get('answerMoreContent')) {
								tinyMCE.get('answerMoreContent').setContent('');
							}
							if (tinyMCE.get('answerMoreText')) {
								tinyMCE.get('answerMoreText').setContent('');
							}
						});
					});
				});
			}

			if ($("a.answerMoreAttendee")) { 
				$("body").on("click", "a.answerMoreAttendee", function () {
					var attendeeEmail = $(this).data("attendeeEmail"),
						attendeeName = $(this).data("attendeeName"),
						questionId = $(this).data("questionid"),
						question = $(this)
							.parents(".questionBlockWrapperDone")
							.find(".questionBlockContainer")
							.find(".questionBlockQuestionText")
							.html();

					if (tinyMCE.get('answerMoreContent')) {
						tinyMCE.get('answerMoreContent').setContent('');
					}
					if (tinyMCE.get('answerMoreText')) {
						tinyMCE.get('answerMoreText').setContent('');
					}

					var data = {
						action: "webinarignition_get_answer_template",
						webinarId: webinar.id,
						security: nonce,
					};

					$("#questionMoreId").val(questionId);
					$("#attendeeMoreEmail").val(attendeeEmail);
					$("#attendeeMoreQuestion").val(question);
					$(".attendeeMoreName").text(attendeeName);

					answerMoreFormContainer.show();

					$.post(ajax_url, data, function (response) {
						var HTMLstring = response["data"]["template"];
						HTMLstring = HTMLstring.replace("{ATTENDEE}", attendeeName);
						(HTMLstring = HTMLstring.replace("{QUESTION}", question)),
							(HTMLstring = HTMLstring.replace(
								"{SUPPORTNAME}",
								current_user.data.display_name
							));

						if (tinyMCE.get('answerMoreContent')) {
							tinyMCE.get('answerMoreContent').setContent(HTMLstring);
						} else {
							// Fallback for text mode
							$('#answerMoreContent').val(HTMLstring);
						}
						// Focus on the editor - if needed
						if (tinyMCE.get('answerMoreText')) {
							tinyMCE.get('answerMoreText').focus();
						}


						$("#answerMoreText").val(questionId);
						$("#attendeeMoreEmail").val(attendeeEmail);

						$("#attendeeMoreName").text(attendeeName);
						answerMoreFormContainer.show();
					});
				});
			}

			$("body").on("click", "a.answerAttendee", function () {
				var attendeeEmail = $(this).data("attendeeEmail"),
					attendeeName = $(this).data("attendeeName"),
					questionId = $(this).data("questionid"),
					question = $(this)
						.parents(".questionBlockWrapper")
						.find(".questionBlockQuestion")
						.find(".questionBlockText")
						.html();

				if (tinyMCE.get('answerContent')) {
					tinyMCE.get('answerContent').setContent('');
				}
				if (tinyMCE.get('answerText')) {
					tinyMCE.get('answerText').setContent('');
				}
				$(".questionOnHold").remove();

				var data = {
					action: "webinarignition_get_answer_template",
					webinarId: webinar.id,
					security: nonce,
				};

				$("#questionId").val(questionId);
				$("#attendeeEmail").val(attendeeEmail);
				$("#attendeeQuestion").val(question);
				$(".attendeeName").text(attendeeName);

				answerFormContainer.show();
				holdOrReleaseConsoleQuestion(questionId, true);

				$.post(ajax_url, data, function (response) {
					var HTMLstring = response["data"]["template"];
					HTMLstring = HTMLstring.replace("{ATTENDEE}", attendeeName);
					(HTMLstring = HTMLstring.replace("{QUESTION}", question)),
						(HTMLstring = HTMLstring.replace(
							"{SUPPORTNAME}",
							current_user.data.display_name
						));

					// Replace Summernote methods with WordPress editor methods
					if (tinyMCE.get('answerContent')) {
						tinyMCE.get('answerContent').setContent(HTMLstring);
					} else {
						// Fallback for text mode
						$('#answerContent').val(HTMLstring);
					}
					
					// Focus on the editor - if needed
					if (tinyMCE.get('answerText')) {
						tinyMCE.get('answerText').focus();
					}

					$("#questionId").val(questionId);
					$("#attendeeEmail").val(attendeeEmail);

					$("#attendeeName").text(attendeeName);
					answerFormContainer.show();
					holdOrReleaseConsoleQuestion(questionId, true);
				});
			});

			if (webinar_user_role 
				&& !webinar_user_role.is_support) {
				function toggleQA(status) {
					var data = {
						action: "webinarignition_set_q_a_status",
						webinarId: webinar.id,
						security: nonce,
						status: status ? "show" : "hide",
					};

					$.post(ajax_url, data);
				}

				// Toggle Q&A
				$(".qa-enable").on("click", function () {
					var parent = $(this).parents(".switch");
					$(".qa-disable", parent).removeClass("selected");
					$(this).addClass("selected");
					$("#QAToggle").val("on");

					toggleQA(true);
				});

				$(".qa-disable").on("click", function () {
					var parent = $(this).parents(".switch");
					$(".qa-enable", parent).removeClass("selected");
					$(this).addClass("selected");
					$("#QAToggle").val("off");

					toggleQA(false);
				});

				// Delete Question
				$("body").on("click", ".qbi-remove, .qbi-removeDone", function () {
					var deleteConfirm = confirm(
						"<?php _e( 'Are you sure you would like to delete this question?', 'webinar-ignition' ); ?>"
					);

					if (!deleteConfirm) {
						return;
					}

					closeAnswerForm.trigger("click");

					var thisElem = $(this);
					var $ID = thisElem.attr("qaID");

					var data = {
						action: "webinarignition_delete_question",
						id: "" + $ID + "",
						security: nonce,
					};

					$.post(ajax_url, data, function (results) {
						$("#QA-BLOCK-" + $ID).fadeOut("fast");

						if (thisElem.hasClass("qbi-remove")) {
							$totalActive = $("#totalQAActive").text();
							$totalActive = parseInt($totalActive);
							$totalQ = $("#dashTotalQ").text();
							$totalQ = parseInt($totalQ);
							$dashTotalActiveQ = $("#dashTotalActiveQ").text();
							$dashTotalActiveQ = parseInt($dashTotalActiveQ);

							if ($totalActive != 0) {
								$totalActive = $totalActive - 1;
								$("#totalQAActive").text($totalActive);
								$totalQ = $totalQ - 1;
								$("#dashTotalQ").text($totalQ);
								$dashTotalActiveQ = $dashTotalActiveQ - 1;
								$("#dashTotalActiveQ").text($dashTotalActiveQ);
							}
						}

						if (thisElem.hasClass("qbi-removeDone")) {
							$totalQADone = $("#totalQADone").text();
							$totalQADone = parseInt($totalQADone);
							$totalQ = $("#dashTotalQ").text();
							$totalQ = parseInt($totalQ);

							if ($totalQADone != 0) {
								$totalQADone = $totalQADone - 1;
								$("#totalQADone").text($totalQADone);
								$totalQ = $totalQ - 1;
								$("#dashTotalQ").text($totalQ);
							}
						}
					});

					return false;
				});
			}
		});

		//custom-dates.php
		var wi_webinar_id = webinar.id;
		var wi_user_id = current_user.ID;
		var wi_cookie_name =
			"wi_selected_date" + "_" + wi_webinar_id + "_" + wi_user_id;
		var wi_selected_date = "";
		var wi_selected_time = "";

		/**
		 * When the DOM is ready:
		 */
		$(function () {
			var wi_selected_datetime = null;
			if (typeof $.cookie === "function") {
				wi_selected_datetime = $.cookie(wi_cookie_name);
			}
			if (wi_selected_datetime) {
				wi_selected_datetime = wi_selected_datetime.split(" ");
				wi_selected_date = wi_selected_datetime[0];
				wi_selected_time = wi_selected_datetime[1];
			}

			$("#webinar_start_date").one("DOMSubtreeModified", function () {
				setTimeout(function () {
					if (order_id) {
						$("#webinar_start_date").val(wi_selected_date).change();
						$("#webinar_start_time").val(wi_selected_time).change();
						$.removeCookie(wi_cookie_name);
					}

					$("#webinar_start_date, #webinar_start_time").change(function (e) {
						wi_selected_date = $("#webinar_start_date").val();
						wi_selected_time = $("#webinar_start_time").val();
						if (wi_selected_date !== "instant_access") {
							wi_selected_date += " ";
							wi_selected_date += wi_selected_time;
						}

						$.cookie(wi_cookie_name, wi_selected_date, { expires: 1 });
					});
				}, 100);
			});
		});

		// tw_share_js.php
		// !(function (d, s, id) {
		// 	var js,
		// 		fjs = d.getElementsByTagName(s)[0],
		// 		p = /^http:/.test(d.location) ? "http" : "https";
		// 	if (!d.getElementById(id)) {
		// 		js = d.createElement(s);
		// 		js.id = id;
		// 		js.src = p + "://platform.twitter.com/widgets.js";
		// 		fjs.parentNode.insertBefore(js, fjs);
		// 	}
		// })(document, "script", "twitter-wjs");

		// fb_share_js.php
		// (function (d, s, id) {
		// 	var js,
		// 		fjs = d.getElementsByTagName(s)[0];
		// 	if (d.getElementById(id)) return;
		// 	js = d.createElement(s);
		// 	js.id = id;
		// 	js.src =
		// 		"//connect.facebook.net/en_US/all.js#xfbml=1&appId=203159309749638";
		// 	fjs.parentNode.insertBefore(js, fjs);
		// })(document, "script", "facebook-jssdk");

		if ( Boolean( window.WEBINARIGNITION.branding ) ) {
			setTimeout(() => {
				$(".autoReplay-dimensions").append(
					'<a href="https://webinarignition.com/" target="_blank"><img style="position: absolute; z-index: 99999999999; bottom: 24px; width: 47px; right: 11px;" src="' +
					watermark +
					'" /></a>'
				);
			}, 1000);
		}

		// register-support.php
		$(document.body).on("click", "#registerSupport_button", function () {
			var btn = $(this);
			var formData = $("#registerSupport_form").serializeArray();
			var data = {
				action: "webinarignition_register_support",
				formData: formData,
				nonce : nonce,
			};
			var fields = $(".registerSupport_row");

			fields.each(function () {
				$(this).removeClass("errored");
			});

			ajaxRequest(
				data,
				function (response) {
					console.log(response);
				},
				function (response) {
					if (response.errors) {
						$.each(response.errors, function (field, error) {
							$("#registerSupport_" + field).addClass("errored");
						});
					}
					console.log(response);
				}
			);
		});

		function ajaxRequest(data, cb, cbError) {
			$.ajax({
				type: "post",
				url: ajax_url,
				data: data,
				success: function (response) {
					var decoded;

					try {
						decoded = $.parseJSON(response);
					} catch (err) {
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

							if (typeof cb === "function") {
								cb(decoded);
							}
						} else {
							if (decoded.message) {
								alert(decoded.message);
							}

							if (typeof cbError === "function") {
								cbError(decoded);
							}
						}
					} else {
						alert(translations.something_went_wrong);
					}
				},
			});
		}

		/**
		 * Code Starts...
		 * wi-js-103
		 * @ByFaheem
		 */ 
		jQuery(document).ready(function ($) {
			$(document).on('click', '.auto_action_header', function (e) {
				const $current = $(this);
			
				// Close all other opened blocks
				$('.auto_action_header').not($current).each(function () {
					const $header = $(this);
					$header.removeClass('active');
					$header.find('.icon-arrow-up').hide();
					$header.find('.icon-arrow-down').show();
					$header.nextAll('.auto_action_body, .auto_action_footer').slideUp();
				});
			
				// Toggle current one
				$current.toggleClass('active');
				$current.find('.icon-arrow-up').toggle($current.hasClass('active'));
				$current.find('.icon-arrow-down').toggle(!$current.hasClass('active'));
				$current.nextAll('.auto_action_body, .auto_action_footer').slideToggle();
			});

			$('label.cb-enable, label.cb-disable').on('click', function () {
				// Find the parent container of the clicked label
				var parentContainer = $(this).closest('.field.switch');
		
				// Find the hidden input field within the same container
				var hiddenInput = parentContainer.find('input[type="hidden"]');
		
				// Determine the new value based on the clicked label's class
				var newValue = $(this).hasClass('cb-enable') ? 'on' : 'off';
		
				// Update the hidden input field's value
				hiddenInput.val(newValue);
		
				// Update the selected state of the labels
				$(this).addClass('selected').siblings('label').removeClass('selected');
		
				// Log the updated value for debugging
			});

			$("#wi-close-notification, #wi-notification-overlay").click(function () {
				$("#wi-notification-box").fadeOut().addClass("wi-hidden");
				$("#wi-notification-overlay").fadeOut().addClass("wi-hidden");
			});


			// Function to update container visibility for a specific CTA item
			function updateContainerVisibility(ctaId) {
				var isOuter = $('#TabPos' + ctaId).is(':checked');
				$('.console-if-outer-container' + ctaId).toggle(isOuter);
				$('.console-if-overlay-container' + ctaId).toggle(!isOuter);
			}			

			// Initialize visibility for all CTA items on page load
			$('.additional_auto_action_item').each(function() {
				var ctaId = $(this).data('cta-id');
				updateContainerVisibility(ctaId);
			});

			// Handle radio button changes with event delegation
			$(document).on('change', '.live_webinar_ctas_position_radios', function() {
				// Extract the CTA ID from the radio button's name or ID
				var ctaId = $(this).attr('name').replace('ctaPosition', '');
				updateContainerVisibility(ctaId);
			});
			
		});

		/**
		 * Code Starts.
		 * wi-js-104
		 * @ByFaheem
		 * @thankYouSection declared at top
		 */
		if (thankYouSection.length) {
			// If the section exists, get the value of the data-thank-you-page-url attribute

			var thank_you_url = thankYouSection.attr("data-thank-you-page-url");

			$("#ar_submit_iframe").load(function () {
				if (!$(this).data("can_load")) return false;

				window.location.href = thank_you_url;
			});

			if (document.getElementById("AR-INTEGRATION")) {
				$("#ar_submit_iframe").data("can_load", "true");

				HTMLFormElement.prototype.submit.call(
					document.getElementById("AR-INTEGRATION")
				);
			} else {
				window.location.href = thank_you_url;
			}
		} else {
			// Log a message if the section does not exist
			//   Section with rel="js-thank-you-url" does not exist.
			//   console.error('Section with rel="js-thank-you-url" does not exist.');
		}

		/**
		 * Code Starts.
		 * wi-js-105
		 * @ByFaheem
		 */
		window.webinarignitionExportLeads = function(type) {
			document
				.getElementById("webinarignition_leads_type")
				.setAttribute("value", type);

			document.getElementById("webinarignition_export_leads_form").submit();
		}

		window.webinarignitionExportLeadsExample = function(type) {
			document
				.getElementById("webinarignition_leads_type")
				.setAttribute("value", type);

			document.getElementById("webinarignition_export_example_leads_form").submit();
		}

		window.wiShowTab = function($ID){
			$(".consoleTabs").hide();
			$("#" + $ID).show();
			wiStorageUtil.set("wiLastOpenTab"+ WEBINARIGNITION.webinar.id, $ID);

				if ($('#onairTab').is(':visible')) {
					$('.color-field-picker').colorpicker();
					const storageKey = "airCopy_editor_content";

					// Save editor content to localStorage
					const saveContentToLocalStorage = () => {
						const editor = tinymce.get("airCopy_editor"); // Get the TinyMCE editor instance
						if (editor) {
							const content = editor.getContent(); // Get the content
							localStorage.setItem(storageKey, content); // Save to localStorage
						}
					};

					const loadContentFromLocalStorage = () => {
						const savedContent = localStorage.getItem(storageKey);
						const editor = tinymce.get("airCopy_editor");
						if (editor && savedContent) {
						  editor.setContent(savedContent);
						}
					};

					// Polling mechanism to wait for TinyMCE to initialize
					const waitForEditor = (callback) => {
						const interval = setInterval(() => {
						const editor = tinymce.get("airCopy_editor");
						if (editor) {
							clearInterval(interval);
							callback(editor);
						}
						}, 100); // Check every 100ms
					};

					const editor = tinymce.get("airCopy_editor");
					if (editor) {
						// Wait for the editor to be ready, then load content
						waitForEditor((editor) => {
							loadContentFromLocalStorage();
						});

						editor.on("change keyup", () => {
							saveContentToLocalStorage();
						});
					}
				}

			// Style Link
			$(".dashTopBTN")
				.removeClass("success")
				.addClass("secondary")
				.addClass("lc-btn");
		}


		/**
		 * Code Starts.
		 * wi-js-106
		 * @ByFaheem
		 */
		if (dataProvider) {
			// Retrieve and assign data attributes to window properties

			window.webinarId = dataProvider.getAttribute("data-webinar-id");

			window.webinarType = dataProvider.getAttribute("data-webinar-type");

			window.ajaxurl = dataProvider.getAttribute("data-ajax-url");

			window.adminPostUrl = dataProvider.getAttribute("data-post-url");

			window.webinarUrl = dataProvider.getAttribute("data-webinar-url");

			window.webinarIgnitionUrl = dataProvider.getAttribute(
				"data-webinarignition-url"
			);

			window.wiRegJS = {};

			window.wiRegJS.ajax_nonce = dataProvider.getAttribute("data-ajax-nonce");

			window.is_support =
				dataProvider.getAttribute("data-is-support") === "true";
		}

		if (!is_support && dataProvider)


			

			$(".dashTopBTN").on("click", function () {
				$ID = $(this).attr("tabID");
				wiShowTab($ID);
				$(this).addClass("secondary success").removeClass("lc-btn");
				return false;
			});

			
				
				const lastOpenTabKey = "wiLastOpenTab" +  WEBINARIGNITION.webinar.id;
				const lastOpenTab = wiStorageUtil.get(lastOpenTabKey);
				const tab = lastOpenTab || "dashboardTab";
				wiShowTab(tab);
				$('[tabid="' + tab + '"]').addClass("success").removeClass("lc-btn");

			

			
		// TotalEvents

		var $totalEvent = 0;

		$(".checkEvent").each(function () {
			var $check = $(this).text();

			if ($check == "Yes") {
				$totalEvent = $totalEvent + 1;
				$("#eventTotal").text($totalEvent);

				// Get Conversion
				var $totalLeads = $("#leadTotal").text();
				$totalLeads = parseInt($totalLeads);
				var $conversion = Math.round(($totalEvent / $totalLeads) * 100);
				//$("#conversion1").text($conversion + "%");
			}
		});

		// TotalReplay
		/*var $totalReplay = 0;
			$('.checkReplay').each(function () {
			var $check = $(this).text();
			if ($check == "Yes") {
			$totalReplay = $totalReplay + 1;
			$("#replayTotal").text($totalReplay);
			// Get Conversion
			$conversion = Math.round(($totalReplay / $totalEvent) * 100);
			//$("#conversion2").text($conversion + "%");
			}
			});*/

		// TotalOrder
		$totalOrder = 0;
		$(".checkOrder").each(function () {
			var $check = $(this).text();

			if ($check == "Yes") {
				$totalOrder = $totalOrder + 1;
				$("#orderTotal").text($totalOrder);

				// Get Conversion
				$totalLeads = $("#leadTotal").text();
				$totalLeads = parseInt($totalLeads);
				$conversion = Math.round(($totalOrder / $totalLeads) * 100);
				//$("#conversion3").text($conversion + "%");
			}
		});

		// LEADS - DASHBOARD
		if ($("#leads").length) {
			$("#leads").dataTable({
				iDisplayLength: 10,
				"aaSorting": [],
				language: {
					emptyTable: WEBINARIGNITION.translations.empty_table,
					info: WEBINARIGNITION.translations.info,
					infoEmpty: WEBINARIGNITION.translations.info_empty,
					lengthMenu: WEBINARIGNITION.translations.length_menu,
					loadingRecords: WEBINARIGNITION.translations.loading_records,
					processing: WEBINARIGNITION.translations.text_processing,
					search: WEBINARIGNITION.translations.search,
					zeroRecords: WEBINARIGNITION.translations.zero_records,
					paginate: {
						first: WEBINARIGNITION.translations.paginate_first,
						last: WEBINARIGNITION.translations.paginate_last,
						next: WEBINARIGNITION.translations.paginate_next,
						previous: WEBINARIGNITION.translations.paginate_previous,
					},
				},
			});

			var oTable = $("#leads").dataTable();

			$("#leads_filter")
				.find("input")
				.attr("placeholder", WEBINARIGNITION.translations.search_your_leads);
		}

		// DELETE LEAD

		$("body").on("click", ".delete_lead", function () {
			var lead_id = $(this).attr("lead_id");
			var answer = confirm(WEBINARIGNITION.translations.del_lead_confirmation);
			var action =
				window.webinarType == "evergreen"
					? "webinarignition_delete_lead_auto"
					: "webinarignition_delete_lead";

			if (answer) {
				var data = {
					security: window.wiRegJS.ajax_nonce,
					action: action,
					id: "" + lead_id + "",
				};

				$.ajax({
					type: "post",
					url: window.ajaxurl,
					data: data,
					success: function () {
						$("#table_lead_" + lead_id).fadeOut("fast");
					},
				});
			}

			return false;
		});

		// setInterval(function () {
		// 	$.ajax({
		// 		type: "post",
		// 		url: ajax_url,
		// 		data: {
		// 			webinar_id: window.webinarId,
		// 			webinar_type: window.webinarType,
		// 			action: "webinarignition_get_users_online",
		// 			security: nonce,
		// 		},
		// 		success: function (response) {
		// 			var count = "0";
		// 			var decoded;

		// 			try {
		// 				decoded = $.parseJSON(response);

		// 				if (decoded.count) {
		// 					count = decoded.count;
		// 				}
		// 			} catch (err) {
		// 				console.log(err);
		// 				count = response;
		// 			}

		// 			$("#usersOnlineCount").html(count);
		// 		},
		// 	});
		// }, 5000);

		$("#showtrackingcode").on("click", function (event) {
			event.preventDefault();

			prompt(
				WEBINARIGNITION.translations.paste_iframe_code,
				"<img src='"+window.webinarUrl +
				"?trkorder=" +
				window.webinarId +
				"' width='1' height='1' style='display:none;'>"
			);
		});

		$("#importLeads").on("click", function () {
			$(".importCSVArea").toggle();

			return false;
		});

		$("#addCSV").on("click", function () {
			var $csv = $("#importCSV").val(),
				data = {
					action: "webinarignition_import_csv_leads",
					id: window.webinarId,
					csv: "" + $csv + "",
					security: window.wiRegJS.ajax_nonce,
				};

			$.post(window.ajaxurl, data, function () {
				location.reload();
			});

			return false;
		});
	}); //end doc.ready
})(jQuery);