(function($) {

})(jQuery);
jQuery.expr.pseudos.parents = function (a, i, m) {
	return jQuery(a).parents(m[3]).length < 1;
};
document.addEventListener('DOMContentLoaded', function() {
    // Check if #vidBox contains an iframe
    const videoIframe = document.querySelector('#vidBox iframe');
    if (videoIframe) {
      // Set width to 100% directly on the style attribute
      videoIframe.style.setProperty('width', '100%', 'important');
    }
  });
(function ($) {
	// AJAX FOR WP
	var ajaxurl = webinarData.ajaxurl;

	// TRACK +1 VIEW
	var $getTrackingCookie = $.cookie(webinarData.trackingCookie);

	if ($getTrackingCookie != "tracked") {
			// No Cookie Set - Track View
			// $.cookie(webinarData.trackingCookie, "tracked", { expires: 30 });
			// var data = { 
            //     action: 'webinarignition_track_view', 
            //     security: webinarData.nonce, 
            //     id: webinarData.webinarId, 
            //     page: "ty" };
			// $.post(ajaxurl, data, function (results) { });
	}
	// Track +1 Total
	// var data = { action: 'webinarignition_track_view_total', security: webinarData.nonce, id: webinarData.webinarId, page: "ty" };
	// $.post(ajaxurl, data);

	// VIDEO FIXES:
	var wi_video_fix_w, wi_video_fix_h;
	if ($(window).width() < 825) {
			wi_video_fix_w = 290;
			wi_video_fix_h = 218;
	} else if ($(window).width() < 480) {
			//mobile size
			wi_video_fix_w = 278;
			wi_video_fix_h = 209;
	} else {
			wi_video_fix_w = 410;
			wi_video_fix_h = 231;
	}
	$('.ctaArea').find("embed, object").height(wi_video_fix_h).width(wi_video_fix_w);

	var exYear = webinarData.exYear;
	var exMonth = webinarData.exMonth;
	var exDay = webinarData.exDay;
	var exHr = webinarData.exHr;
	var exMin = webinarData.exMin;
	var exSec = '0';
	var tzOffset = webinarData.tzOffset;
    if(window.WEBINARIGNITION){
        if ( window.WEBINARIGNITION.current_webinar_page === 'thank_you' || window.WEBINARIGNITION.current_webinar_page === 'countdown' ) {
            
            $('#defaultCountdown').wi_countdown({
                until: $.wi_countdown.UTCDate(tzOffset, exYear, exMonth, exDay, exHr, exMin, exSec),
                onExpiry: webinarignition_expired_cd,
                compact: true,
                alwaysExpire: true,
                compactLabels: ['', '', '', '']
            });
        }
    }

	function webinarignition_expired_cd() {
			$(".ticketCDAreaBTN").text(webinarData.webinarInProgress);
			$("#defaultCountdown").hide();
			$("#webinarBTNNN").removeClass("disabled").removeClass("alert").addClass("success");
			$.post(
				ajaxurl,
				{
					action          :   'webinarignition_update_admin_webinar_status',
					webinarId       :   webinarData.webinarId,
					security        :   webinarData.nonce,
					webinar_switch  :   'live'
				}
			);  
			setTimeout(function () {
                if (!window.location.search.includes('preview=true')) {
                    window.location.href = $(".ticketCDAreaBTN").attr('href');
                }
			}, 1000);
	}

	// Save Phone && Reveal Text
	$('#storePhone').on('click', function () {
			// Lead ID
			var $ID = $("#leadID").val();
			// Phone Number
			var $PHONE = $("#optPhone").val();

			// Post & Save & Reveal
			var data;
			if (webinarData.isAuto) {
					data = {
							action: 'webinarignition_store_phone_auto',
							security: webinarData.nonce,
							id: webinarData.leadId,
							phone: "" + $PHONE + ""
					};
			} else {
					data = { action: 'webinarignition_store_phone', security: webinarData.nonce, id: webinarData.leadId, phone: "" + $PHONE + "" };
			}
			$.post(ajaxurl, data, function (results) {
					$("#phonePre").hide();
					$("#phoneReveal").show();
			});

			return false;
	});
})(jQuery);

(function ($) {
    var auto_video_length = webinarData.auto_video_length;
    var globalOffset;
    if(webinarData.globalOffset){
        var globalOffset = webinarData.globalOffset;
    }
    else{
        globalOffset = 0;
    }
    
    var ajaxurl = webinarData.ajaxurl;
    var webinar_type = webinarData.webinar_type || webinarData.webinarType;
    var lead_id = webinarData.lead_id || webinarData.leadId;
    var webinar_id = webinarData.webinar_id || webinarData.webinarId;
    var additional_autoactions = webinarData.additionalAutoactions;
    var security = webinarData.nonce;
    var trackingTags = webinarData.trackingTags;

    $(document.body).on('wi_player_play', function(e) {
        if (additional_autoactions && additional_autoactions.length) {
            scheduleAdditionalAutoactions();
        }
        $('#vidOvl').show();
        if ( webinarData.webinar.auto_action && webinarData.webinar.auto_action !== 'time' ) {
            setTimeout(function() {
                $('#wi-cta-default-overlay').css('visibility','visible'); //Show first CTA if defined
                $('#wi-cta-default-overlay').show();
            }, 5);
        }
    });

    if ('evergreen' === webinarData.webinarType) { 
        if (webinarData.webinar && webinarData.webinar.auto_action === "time" && 
            ( ( webinarData.webinar.webinar_iframe_source ) || ( webinarData.webinar.auto_video_url ) ) 
           ) {
            if ( ! shouldUseVideojs( webinarData.webinar ) ) { 
                if (
                    (additional_autoactions && additional_autoactions.length)
                ) {
                    scheduleAdditionalAutoactions();
                }
            }
        }
    }

    $(document).ready(function() {
        if (trackingTags && trackingTags.length) {
            $('head').append('<div id="tracking_pixel_holder"></div>');
            trackingTags.forEach(function(item, index) {
                var timeout = item.timeout;
                var time = item.time;
                var name = item.name;
                var slug = item.slug;
                var pixel = item.pixel;

                if (timeout > globalOffset) {
                    setTimeout(function() {
                        $('#tracking_pixel_holder').empty();
                        if (pixel) {
                            var pixel_html = $(pixel);
                            $('#tracking_pixel_holder').html(pixel_html);
                        }

                        var data = {
                            action: 'webinarignition_tracking_tags',
                            time,
                            name,
                            slug,
                            lead_id,
                            webinar_type,
                            webinar_id,
                            security,
                        };
                        $.post(ajaxurl, data, function(results) {});
                    }, timeout);
                }
            });
        }
    });

    function scheduleAdditionalAutoactions() {
        if (additional_autoactions.length) {
            var myPlayer = null;
            var wiPlayer = $('#autoReplay').find('video').get(0);
            var wiVideoInterval = [];
            var endDelays = [];
            $.each(additional_autoactions, function(index, autoaction) {
                let start_delay = autoaction.start_delay - globalOffset;
                if (start_delay < 10) start_delay = 10;
                if (start_delay > 1010) start_delay = start_delay - 1000;
                let end_delay = autoaction.end_delay - globalOffset;
                if (autoaction.is_videojs) myPlayer = videojs('autoReplay');

                if (myPlayer) {
                    myPlayer.on('timeupdate', function() {
                        $(document.body).trigger('wi_video_timeupdate', [{
                            'index': autoaction.index,
                            'start': autoaction.start_delay,
                            'end': autoaction.end_delay
                        }, myPlayer.currentTime(), (myPlayer.paused() === true && myPlayer.ended() === false), myPlayer.duration()]);
                    });
                } else if (wiPlayer) {
                    wiPlayer.ontimeupdate = function() {
                        $(document.body).trigger('wi_video_timeupdate', [{
                            'index': autoaction.index,
                            'start': autoaction.start_delay,
                            'end': autoaction.end_delay
                        }, wiPlayer.currentTime, (wiPlayer.paused === true && wiPlayer.ended === false), wiPlayer.duration()]);
                    };
                } else {
                    endDelays[autoaction.index] = autoaction.end_delay;
                    var wiIntervalTime = 1;
                    setTimeout(function() {
                        wiVideoInterval[autoaction.index] = setInterval(function() {
                            wiIntervalTime = (wiIntervalTime + 1);
                            $(document.body).trigger('wi_video_timeupdate', [{
                                'index': autoaction.index,
                                'start': autoaction.start_delay,
                                'end': autoaction.end_delay
                            }, wiIntervalTime, false, (auto_video_length * 60)]);
                        }, 1000);
                    }, 5000);
                }
            });
            myPlayer.on('play', function() {
                if (myPlayer.currentTime() === 0) {
                    $(document.body).trigger('wi_video_replay'); // Custom replay event
                }
            });

            if (myPlayer) {
                myPlayer.on('ended', function() {
                    $(document.body).trigger('wi_video_ended');
                });
            } else if (wiPlayer) {
                wiPlayer.onended = function() {
                    $(document.body).trigger('wi_video_ended');
                };
            } else {
                var stopTimer = (auto_video_length * 60) * 1000;
                if (endDelays.length > 0) {
                    endDelays.sort(function(a, b) {
                        return a - b;
                    });
                    stopTimer = endDelays[endDelays.length - 1];
                }
                stopTimer = (stopTimer + 5000);
                setTimeout(function() {
                    if (wiVideoInterval.length > 0) {
                        $.each(wiVideoInterval, function(index, interval) {
                            clearInterval(interval);
                        });
                        $(document.body).trigger('wi_video_ended');
                    }
                }, stopTimer);
            }
        }
    }

    function shouldUseVideojs(results) {
        // don't use Videojs if it is a live webinar
        if (!results.webinar_date || results.webinar_date !== 'AUTO') {
            return false;
        }
    
        // don't use Videojs if iframe (3rd party e.g Youtube) is used
        if (!results.webinar_source_toggle || results.webinar_source_toggle === 'iframe') {
            return false;
        }
    
        return true;
    }

})(jQuery);
