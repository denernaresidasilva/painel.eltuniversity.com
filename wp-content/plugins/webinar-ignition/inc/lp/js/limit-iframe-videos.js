jQuery(document).ready(function($){

	if( !$('#vidBox') ||  $('#vidBox').length < 1 ) {
		return;
	}

	var ajax_url = lcv_php_var.ajax_url;
	var nonce    = lcv_php_var.nonce;
	var lead_id  = lcv_php_var.lead_id;
	wi_paused_interval       = false;

	setInterval( function() {
		jQuery.ajax({
			url: ajax_url,
			type: 'POST',
			data: {
				action     : 'wi_track_embeded_videos_time',
				nonce      : nonce,
				lead_id    : lead_id,
			},
			success: function ( response ) {
				
				if( response.show_countdown ) {
					console.log('show_countdown triggered');
					wi_paused_interval   = false;
					jQuery(document.body).trigger('wi_start_timeout_countdown');
				}

				if( response.timeover ) {
					window.onbeforeunload = null;
					$(document.body).append( response.popup );

					if( undefined === wi_timeover_popop_display ) {
						var wi_timeover_popop_display = false;
					}

					wi_timeover_popop_display = true;

					// setInterval( function(){
						window.location.reload();
					// }, 1000 );
				}
			},
			error: function (response) {
				console.log( response );
			}
		});
	}, 1000 * 15 );
});