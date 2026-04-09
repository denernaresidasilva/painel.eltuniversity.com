(function ($) {
	$(document).on("ready", function () {

		var webinar = window.WEBINARIGNITION.webinar;

		if ( webinar.ty_cta_type === 'video' && webinar.ty_cta_video_url !== '' ) {
			jQuery(function() {
				var wi_ctaVideoPlayer = videojs("wi_ctaVideoPlayer", {
					fluid: true,
					playsinline: true,
					muted: true,
					bigPlayButton: false,
					controls: false,
					controlBar: false
				});

				function wi_videojs_do_autoplay(player, muted, success_cb, error_cb) {

					player.muted(muted);

					var played_promise = player.play();

					if ( played_promise !== undefined ) {

						if( success_cb === null ) {
							success_cb = function() {}
						}

						if( error_cb === null ) {
							error_cb = function() {}
						}

						played_promise.then(success_cb).catch(error_cb);
					}
				}

				//Immediate autoplay stopped working in chrome,
				//Workaround: Play the video programatically, few seconds after player is ready,
				// and detect if that fails then do autoplay in muted mode.
				wi_ctaVideoPlayer.ready(function() {
					setTimeout(function() {

						wi_ctaVideoPlayer.fluid('true')

						wi_videojs_do_autoplay(wi_ctaVideoPlayer, false, function() {
							jQuery('#wi_ctaVideo > .wi_videoPlayerMute').show();
						}, function(error) {
							console.log(error);
							wi_videojs_do_autoplay(wi_ctaVideoPlayer, true, function() {
								jQuery('#wi_ctaVideo > .wi_videoPlayerUnmute').show();
							}, function(error) {
								console.log(error);
							});
						});

					}, 500);
				});

				jQuery('#wi_ctaVideo > .wi_videoPlayerUnmute').click(function(e) {
					e.preventDefault();
					wi_ctaVideoPlayer.muted(false);
					jQuery(this).hide();
					jQuery('#wi_ctaVideo > .wi_videoPlayerMute').show();
				});

				jQuery('#wi_ctaVideo > .wi_videoPlayerMute').click(function(e) {
					e.preventDefault();
					wi_ctaVideoPlayer.muted(true);
					jQuery(this).hide();
					jQuery('#wi_ctaVideo > .wi_videoPlayerUnmute').show();
				});
			});
		}
	});
})(jQuery);
