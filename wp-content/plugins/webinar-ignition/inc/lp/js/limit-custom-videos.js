jQuery(document).ready(function($){

	if( !$('#vidBox') ||  $('#vidBox').length < 1 ) {
		return;
	}

	var wi_timeover_popop_display = false;
	var wi_countdown_started = false;
	var ajax_url = lcv_php_var.ajax_url;
	var nonce    = lcv_php_var.nonce;
	var lead_id  = lcv_php_var.lead_id;
	var prev_record_time = 0;

	wi_paused_interval = true;

	$('#vidBox video').on('timeupdate', function() {
		let video = this;
		let viewTime = video.currentTime;
		let preview  = false;

		let current_url = window.location.href;

		if( current_url.indexOf('preview=true') >= 0 ) {
			preview = true;
		}

		if( true === wi_timeover_popop_display ) {
			return;
		}

		if ( parseInt( video.currentTime ) !== prev_record_time && parseInt( video.currentTime ) % 60 === 0) {
			viewTime = parseInt(video.currentTime);
			prev_record_time = viewTime;

			jQuery.ajax({
				url: ajax_url,
				type: 'POST',
				data: {
					action     : 'wi_track_self_hosted_videos_time',
					nonce      : nonce,
					lead_id    : lead_id,
					watch_time : viewTime,
					preview    : preview
				},
				success: function ( response ) {
					
					if( response.show_countdown && !wi_countdown_started ) {
						wi_countdown_started = true;
						wi_paused_interval   = false;
						wi_timeover_timeLeft = response.timeleft;
						WI_TIME_LIMIT        = response.timeleft;
						jQuery(document.body).trigger('wi_start_timeout_countdown');
					}

					if( response.timeover ) {

						$(document.body).append( response.popup );

						if( undefined === wi_timeover_popop_display ) {
							var wi_timeover_popop_display = false;
						}
						window.onbeforeunload = null;
						wi_timeover_popop_display = true;

						setInterval( function(){
							video.pause();
							const url = new URL(window.location.href);
							url.searchParams.set('reloadTime', Date.now().toString());
							window.location.href = url.toString();
						}, 1000 );
					}
				},
				error: function (response) {
					console.log( response );
				}
			});
		}
	});

	$('#vidBox video').on( 'seeking', function(){

		let current_url = window.location.href;

		if( current_url.indexOf('preview=true') < 0 ) {
			return;
		}

		let video = this;
		let viewTime = video.currentTime;

		prev_record_time = 0;

		if( true === wi_timeover_popop_display ) {
			video.pause();
			const url = new URL(window.location.href);
			url.searchParams.set('reloadTime', Date.now().toString());
			window.location.href = url.toString();
			return;
		}

		jQuery.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				action     : 'wi_get_self_hosted_videos_time_left',
				nonce      : nonce,
				lead_id    : lead_id,
				viewTime   : viewTime,
			},
			success: function ( response ) {

				if( !wi_countdown_started && response.show_countdown ) {
					wi_countdown_started = true;
					wi_paused_interval   = false;
					wi_timeover_timeLeft = response.timeleft;
					WI_TIME_LIMIT        = response.timeleft;
					wi_timeover_timePassed = 0;
					jQuery(document.body).trigger('wi_start_timeout_countdown');
				}

				if( wi_countdown_started && response.show_countdown ) {
					if(document.getElementById("wi_count_down_5_mint")){
						document.getElementById("wi_count_down_5_mint").innerHTML = '';
					}
					wi_paused_interval   = false;
					wi_timeover_timeLeft = response.timeleft;
					WI_TIME_LIMIT        = response.timeleft;
					wi_timeover_timePassed = 0;
					if( response.timeleft > 0 ) {
						jQuery(document.body).trigger('wi_start_timeout_countdown');
					} else {
						wi_timeover_timeLeft = 0;
						WI_TIME_LIMIT        = 0;
						jQuery(document.body).trigger('wi_start_timeout_countdown');
					}
				}

				if( wi_countdown_started && !response.show_countdown ) {
					if(document.getElementById("wi_count_down_5_mint")){
						document.getElementById("wi_count_down_5_mint").innerHTML = '';
					}
					wi_countdown_started = false;
				}

				if( response.timeover ) {

					$(document.body).append( response.popup );

					if( undefined === wi_timeover_popop_display ) {
						var wi_timeover_popop_display = false;
					}

					wi_timeover_popop_display = true;

					setInterval( function(){
						video.pause();
						const url = new URL(window.location.href);
						url.searchParams.set('reloadTime', Date.now().toString());
						window.location.href = url.toString();
						}, 1000 );
					}
				},
				error: function (response) {
					console.log( response );
				}
			});
	});

	$('#vidBox video').on('pause', function() {

		if( undefined == wi_countdown_started ) {
			return;
		}

		if( wi_countdown_started && undefined != wi_paused_interval ) {
			wi_paused_interval = true;
		}
	});

	$('#vidBox video').on('playing', function() {

		if( undefined == wi_countdown_started ) {
			return;
		}

		if( wi_countdown_started && undefined != wi_paused_interval ) {
			wi_paused_interval = false;
		}
	});
});