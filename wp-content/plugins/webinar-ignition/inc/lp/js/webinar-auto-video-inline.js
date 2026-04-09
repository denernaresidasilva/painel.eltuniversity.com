var globalOffset = 0;
var ajaxurl = webinarParams.ajaxurl;

(function ($) {
    var is_auto_login_enabled = webinarParams.is_auto_login_enabled;
    var is_preview_page = webinarParams.is_preview_page;
    var lead_id = webinarParams.lead_id;
    var webinar_id = webinarParams.webinar_id;
    var nonce = webinarParams.nonce;
    var auto_redirect_url = webinarParams.auto_redirect_url;
    var auto_redirect_delay = webinarParams.auto_redirect_delay;
    var individual_offset = webinarParams.individual_offset;
    var lid = webinarParams.lid;
    var videoResumeTime = $.cookie('videoResumeTime-' + lid);
    var auto_video_length = webinarParams.auto_video_length;
	var urlParams = new URLSearchParams(window.location.search);
	if (urlParams.get('preview') === 'true') {
		videoResumeTime = sessionStorage.getItem('videoResumeTime-' + lid);
	}
    if ((is_auto_login_enabled && webinarParams.is_user_logged_in) || true) {
        $(document).on('wi_player_play', function (e) {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'webinarignition_lead_mark_attended',
                    nonce: nonce,
                    webinar_id: webinar_id,
                    lead_id: lead_id,
                    is_preview_page: is_preview_page
                },
                success: function (response) {
                }
            });
        });

        $(document).on('wi_video_ended', function (e) {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'webinarignition_lead_mark_watched',
                    nonce: nonce,
                    webinar_id: webinar_id,
                    lead_id: lead_id,
                    is_preview_page: is_preview_page
                },
                success: function (response) {
                    if (response.success || is_preview_page) {
                        if (auto_redirect_url) {
                            window.onbeforeunload = undefined; // Unbind beforeunload, to avoid confirmation alert and further ajax calls

                            if (auto_redirect_delay > 0) {
								window.onbeforeunload = undefined; // Unbind beforeunload, to avoid confirmation alert and further ajax calls

                                setTimeout(function () {
                                    window.location.href = auto_redirect_url;
                                }, auto_redirect_delay * 1000);
                            } else {
								window.onbeforeunload = undefined; // Unbind beforeunload, to avoid confirmation alert and further ajax calls

                                window.location.href = auto_redirect_url;
                            }
                        } else if (!is_preview_page) {
                            window.location.reload();
                        }
                    }
                }
            });
        });
		console.log(webinarParams.should_use_videojs);
        if (!webinarParams.should_use_videojs) {
            if (auto_redirect_url && auto_video_length) {
                var webinarLength = auto_video_length * 60 * 1000;

                setTimeout(function () {
                    $(document.body).trigger('wi_video_ended');
                }, webinarLength);
            }
        } else {
            if (!document.getElementById('autoReplay')) return;

            var isUserAdmin = $("#is-user-admin").val();
			var isvideocontrolenabeled = $("#is-video-control-enabeled").val();
			var showControls = false;
			if (isUserAdmin == 1  || isvideocontrolenabeled == 1) {
				showControls = true;
			}
            var no_autoplay_block = $('#no-autoplay-block').css({ "top": "0", "left": "0" }).hide();
            var myPlayerOptions = {
                preload: "auto",
                controls: showControls, // Dynamically set based on user role
                width: "920",
                height: "500",
                fluid: true,
                playsinline: true,
                autoplay: true,
                bigPlayButton: false,
                languages: {
                    en: webinarParams.languages
                }
            };

            var initdone = false;
            var offset = individual_offset ? individual_offset : 0;

            offset = (typeof (videoResumeTime) !== 'undefined') ? videoResumeTime : offset;
            globalOffset = Math.ceil(offset * 1000);

            var myPlayer = videojs('autoReplay', myPlayerOptions);

            myPlayer.on('loadedmetadata', function () {
                var playButton = document.getElementById('mobile-play-button');
                if (!playButton) {
                    return;
                }

                playButton.addEventListener('click', function () {
                    myPlayer.currentTime(videoResumeTime);

                    if (myPlayer.duration() > offset) {
                        myPlayer.muted(false);
                        myPlayer.play();
                        no_autoplay_block.hide();
                    }
                });

                var unmuteButton = document.getElementById('unmute-button');
                if (!unmuteButton) {
                    return;
                }

                unmuteButton.addEventListener('click', function () {
                    myPlayer.muted(false);
                    document.getElementById('muted-autoplay-block').style.display = 'none';
                });

                $('#video-loading-block').css('display', 'none');

                myPlayer.ready(function () {
                    no_autoplay_block.appendTo('#autoReplay');
                    if ($('.webinarVideoCTA.webinarVideoCTAActive').parent().hasClass('webinarVideoCtaCombined')) {
                        $('.webinarVideoCTA.webinarVideoCTAActive').appendTo('#autoReplay');
                    }

                    myPlayer.currentTime(videoResumeTime);

                    if (myPlayer.duration() > offset) {
                        myPlayer.play().then(function () {
                            $(document.body).trigger('wi_player_play');
                            no_autoplay_block.hide();
                        })
                            .catch(function (error) {
                                no_autoplay_block.show();
                                return;
                            });

                        // if (!is_preview_page) {
                            myPlayer.on('pause', function () {
                                $(document.body).trigger('wi_player_pause', myPlayer);
                            });
                        // }

                        myPlayer.on('play', function () {
                            $(document.body).trigger('wi_player_play');
                        });

                        myPlayer.on('timeupdate', function () {
                            var videoResumeTime = myPlayer.currentTime();
                            var newResumeTime = Math.ceil(videoResumeTime);
                            var oldResumeTime = Math.ceil(globalOffset / 1000);
                            var differenceTime = Math.abs(newResumeTime - oldResumeTime);

                            globalOffset = Math.ceil(videoResumeTime * 1000);

                            if (1 < differenceTime) {
                                $(document.body).trigger('wi_player_rewind', [newResumeTime, oldResumeTime, differenceTime]);
                            }

                            $.cookie('videoResumeTime-' + lid, videoResumeTime);
							if (urlParams.get('preview') === 'true') {
								sessionStorage.setItem('videoResumeTime-' + lid, videoResumeTime);
							}
                            var num = myPlayer.currentTime();
                            num = Math.floor(num);
                            var minutes = Math.floor(num / 60);
                            jQuery("#autoVideoTime").val(minutes);
                            jQuery(".autoWebinarLoading").fadeOut("fast");
                        });

                        myPlayer.on('ended', function () {
                            $(document.body).trigger('wi_video_ended');
                        });
                    } else {
                        myPlayer.on('play', function () {
                            $(document.body).trigger('wi_player_play');
                        });
                    }
                });
            });
        }
    }
})(jQuery);
