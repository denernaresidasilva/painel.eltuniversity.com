jQuery.fn.extend({
	hideCTA: function () {
		if (!this.hasClass("wi-tab-pane")) {
			this.css({ visibility: 'hidden', zIndex: -1 });
		}

		this.removeClass("active");

		return this;
	},
	showCTA: function () {

		// this line is to solve iframe issue
		// jQuery(this).find("iframe").eq(0).css({ visibility: "visible", height: "650px" });
		jQuery(this).find("iframe").eq(0).each(function() {
			if (jQuery(this).closest("aside").length > 0) {
				// If an <aside> tag exists in the parent hierarchy
				jQuery(this).css({ visibility: "visible", height: "650px" });
			} else {
				// Optional: Handle the case where <aside> is not found
				jQuery(this).css({ visibility: "visible"});
			}
			if ( jQuery(this).html().trim() === '') {
				jQuery(this).parent(".Test_Class_HOLA").attr("style", "padding: 0px !important;");
			} else {
				jQuery('#overlayOrderBTNCopy_1').parent(".Test_Class_HOLA").css("padding", ""); // Remove the padding style
			}
		});

		/**
		 * On line webainar cta's were not hiding and showing correctly
		 * this line was solving the problem but creating issues in other places so always beware to use this
		 * this.css({ display: "block" });
		 */
		if (!this.hasClass("wi-tab-pane")) {
			this.css({ visibility: "visible",
				zIndex: "100" 
			});
		}
		this.addClass("active");

		return this;
	},
});

(function ($) {
	"use strict";

	const WI_CTA = {
		init: function () {

			/**
			 * we can remove this if any other functionality is disturbed
			 * purpose to add this line is to rest the cta on page load as well.
			 * 
			 * Notes: Calling rest function on init generate error that amelia booking shortcode does not displayed correctly
			 * but this solution was working fine for a problem on live webinar
			 */
			
			// WI_CTA.reset();


			$(document.body).on("wi_player_play", function (e, player) {
				WI_CTA.reset();
			});

			$(document.body).on("wi_video_ended", function (e) {
				WI_CTA.reset(true);
			});

			$(document.body).on("wi_player_pause", function (e, player) {
				// if (player.seeking() === false && player.ended() === false) {
				// 	WI_CTA.reset();
				// }
			});

			$(document).on(
				"wi_video_timeupdate",
				function (e, cta, currentTime, isPaused, videoDuration) {
					if (isPaused) return; //Do not trigger CTA show/hide when video is paused

					let currentTimeInSeconds = currentTime * 1000; //Convert currentTime seconds into milliseconds
					let videoDurationInSeconds = videoDuration * 1000; //Convert videoDuration seconds into milliseconds

					if (
						currentTimeInSeconds > cta.start &&
						currentTimeInSeconds < cta.end
					) {
						$(document.body).trigger("wi_cta_show", [cta.index]);
					} else {
						//Do not hide CTA if end time is greater than the video time
						if (cta.end > videoDurationInSeconds) {
							return;
						}

						$(document.body).trigger("wi_cta_hide", [cta.index]);
					}
					WI_CTA.toggleSidebar();
				}
			);

			$(document.body).on('wi_video_replay', function () {
				console.log('Replay button clicked!');
				// Optional: Reset or restart your CTA logic here
				WI_CTA.reset();
			});

			$(document).on("wi_cta_show", function (e, ctaIndex) {
				let cta_element_id = "wi-cta-" + ctaIndex;
				let cta_tab = $("#" + cta_element_id + "-tab");
				let cta_tab_contents_element = cta_tab;

				if (cta_tab.length > 0) {
					if (
						cta_tab.hasClass("timedUnderAreaOverlay") ||
						cta_tab.hasClass("timedUnderArea")
					) {
						cta_tab_contents_element
							.find(".pre-hurrytimer-campaign")
							.each(function () {
								$(this).addClass("hurrytimer-campaign");
							});

						cta_tab.showCTA();
					} else {
						let cta_tab_contents_element = $(
							"div#webinarTabsContent #" + cta_element_id
						);
						let cta_tab_parent = cta_tab.parent("li");
						if (cta_tab_parent.is("visible") === false) {
							if (cta_tab.hasClass("clicked") === false) {
								cta_tab.addClass("clicked").trigger("click");

								cta_tab_contents_element
									.find(".pre-hurrytimer-campaign")
									.each(function () {
										$(this).addClass("hurrytimer-campaign");
									});

								cta_tab_parent.show();
								cta_tab_contents_element.showCTA(500, function () {
									$(window).trigger("wi_webinar_refresh"); //Refresh webinar contents
								});

								WI_CTA.toggleSidebar();
							}
						}
					}
				}
			});

			$(document).on("wi_cta_hide", function (e, ctaIndex) {
				let cta_element_id = "wi-cta-" + ctaIndex;
				let cta_tab = $("#" + cta_element_id + "-tab");

				if (cta_tab.length > 0) {
					if (
						cta_tab.hasClass("timedUnderAreaOverlay") ||
						cta_tab.hasClass("timedUnderArea")
					) {
						cta_tab.hideCTA();
					} else {
						let cta_tab_contents_element = $(
							"div#webinarTabsContent #" + cta_element_id
						);
						let cta_tab_parent = cta_tab.parent("li");

						if (cta_tab_parent.is(":visible")) {
							if (
								cta_tab_parent.hasClass("wi-cta-tab") &&
								cta_tab.hasClass("clicked") === true
							) {
								cta_tab.removeClass("clicked").removeClass("active");
								cta_tab_parent.hide();
								cta_tab_contents_element.hideCTA(500, function () {
									if ($("#webinarTabs li:visible").length === 0) {
										$("#webinarTabsContent").css({ height: "auto" });
										$(window).trigger("wi_webinar_refresh"); //Refresh webinar contents
									}
								});

								var first_visible_li_in_ul = cta_tab_parent
									.closest("ul")
									.find("li.wi-cta-tab:visible")
									.eq(0);
								if (first_visible_li_in_ul.length === 0) {
									first_visible_li_in_ul = cta_tab_parent
										.closest("ul")
										.find("li:visible")
										.eq(0);
								}
								first_visible_li_in_ul
									.find("a")
									.trigger("click")
									.addClass("clicked");
								WI_CTA.toggleSidebar();
							} else {
								if (cta_tab_parent.hasClass("wi-cta-tab") === false) {
									cta_tab.trigger("click");
								}
							}
						}
					}
				}
			});
		},
		reset: function (keepCTA) {
			if (typeof keepCTA === undefined) keepCTA = false;

			//Keep CTAs intact
			if (!keepCTA) {
				WI_CTA.hideAllCTA();
			}

			$("#webinarTabs").find("li:visible").eq(0).find("a").trigger("click");
		},
		hideAllCTA: function () {
			$("#webinarTabs")
				.find("li.wi-cta-tab")
				.each(function (wi_cta_tab_li_index, wi_cta_tab) {
					$(wi_cta_tab).hide().find("a.clicked").removeClass("clicked");
					let wi_cta_tab_index = $(wi_cta_tab)
						.children("a")
						.attr("id")
						.replace("-tab", "");
					$("div#webinarTabsContent #" + wi_cta_tab_index).hideCTA();
				});

				if ( $('[id^="tab-"][id$="-tab"]').length === 0 ) {
					// If NO matching tabs exist, remove the classes
					$("#webinarVideo").removeClass("wi-col-lg-9");
					$("#webinarSidebar").removeClass("wi-col-lg-3");
				}
				
			$("div.wi-cta-tab").each(function (i, div) {
				if ($(div).hasClass("wi-cta-tab-keep") === false) {
					// $(div).hide();
				}
			});
		},
		toggleSidebar: function () {
			if ($("#webinarTabs li:visible").length > 0) {
				$("#webinarVideo").addClass("wi-col-lg-9");
				$("#webinarSidebar").addClass("wi-col-lg-3");
			} else {
				$("#webinarVideo").removeClass("wi-col-lg-9");
				$("#webinarSidebar").removeClass("wi-col-lg-3");
			}
		},
	};

	$(document).ready(function (e) {
		WI_CTA.init();
		var isUserAdmin = $("#is-user-admin").val();
		var isvideocontrolenabeled = $("#is-video-control-enabeled").val();
		var showControls = false;
		if (isUserAdmin == 1  || isvideocontrolenabeled == 1) {
			showControls = true;
		}
		if(showControls){
			$('.vjs-tech, .Test_Class_HOLA, .vjs-control-bar').hover(
				function () {
					$('.vjs-audio-only-mode .vjs-control-bar, .vjs-has-started .vjs-control-bar').css({
						'pointer-events': 'unset'
					});
				},
				function () {
					$('.vjs-audio-only-mode .vjs-control-bar, .vjs-has-started .vjs-control-bar').css({
						'pointer-events': 'unset'
					});
	
				}
			);
		}
		
		$('.timedUnderAreaOverlay').css("visibility", 'hidden');
	});
})(jQuery);
