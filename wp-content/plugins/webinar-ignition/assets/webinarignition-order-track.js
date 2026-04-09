(function($){
	$(document).on('ready', function() {
		var ajax_url = window.WEBINARIGNITION.ajax_url,
			webinar_id = window.WEBINARIGNITION?.webinar?.id,
			nonce = window.WEBINARIGNITION.nonce;

		if ( ! webinar_id ) {
			return;
		}

		$.post(
			ajax_url,
			{
				action: 'webinarignition_track_order',
				security: nonce,
				id: webinar_id,
				lead: $.cookie('we-trk-' + webinar_id )
			}
		);
	})
})(jQuery)