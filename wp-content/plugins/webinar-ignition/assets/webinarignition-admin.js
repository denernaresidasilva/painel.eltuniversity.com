
(function($) {

	document.addEventListener('DOMContentLoaded', function() {
		var dismissLink = document.getElementById('dismiss_old_webinars_notification');
		if (dismissLink) {
			dismissLink.addEventListener('click', function(event) {
				event.preventDefault();
				var notification = document.getElementById('old_webinars_notification');
				if (notification) {
					notification.style.display = 'none';
					// Send AJAX request to mark notification as dismissed
					var xhr = new XMLHttpRequest();
					xhr.open('GET', WEBINARIGNITION.ajax_url + '?action=dismiss_old_webinars_notification');
					xhr.send();
				}
			});
		}
	});

	
	jQuery(document).ready(function($){
		$('.update-message').find('p').each( function(){
			if( $(this).text().length < 1 ){
				$(this).remove();
			}
		});
	});


	$(document).on('ready', function() {
			ajax_url = window.WEBINARIGNITION.ajax_url,
			nonce = window.WEBINARIGNITION.nonce;

		jQuery(document).on('click', '#webinarignition-smtp-failed-notice .notice-dismiss', function() {
			jQuery.ajax({
				url: ajax_url,
				data: {
					action: 'webinarignition_delete_smtp_failed_notice',
					security: nonce
				}
			});
		});

		jQuery(document).on( 'click', '#webinarignition-smtp-notice .notice-dismiss', function() {
			jQuery.ajax({
				url: ajax_url,
				data: {
					action: 'webinarignition_delete_smtp_updated_status',
					security: nonce
				}
			});
		});

		var dismissLink = document.getElementById('dismiss_old_webinars_notification');
		if (dismissLink) {
			dismissLink.addEventListener('click', function(event) {
				event.preventDefault();
				var notification = document.getElementById('old_webinars_notification');
				if (notification) {
					notification.style.display = 'none';
					// Send AJAX request to mark notification as dismissed
					// ! TODO: convert this to a wp ajax req.
					var xhr = new XMLHttpRequest();
					xhr.open('GET', ajaxurl + '?action=dismiss_old_webinars_notification');
					xhr.send();
				}
			});
		}

		$('.update-message').find('p').each( function(){
			if( $(this).text().length < 1 ){
				$(this).remove();
			}
		});

		 $('#webinarignition_use_grid_custom_color').on('change', function() {
			if ($(this).is(':checked')) {
				$('#wiWrapBrandColors').removeClass('wi_hidden');
				} else {
				$('#wiWrapBrandColors').addClass('wi_hidden');
				}
			});


		var progress = parseInt( $('#webinarignition-reg-progress-counter').data('progress') );
		
		$(".meter > span").each(function () {
			$(this).animate({
				width: progress + '%'
			}, 4000 );
		});

		// edit.php
		$('#editApp').on('change', function(event){
			if (document.readyState === 'complete'){
				$(this).addClass("dirty")
			}else{
			}
		});


		window.onbeforeunload = function() {
			// Check if tinyMCE and activeEditor are defined and if isDirty() is available
			if (typeof tinyMCE !== 'undefined' && tinyMCE.activeEditor && typeof tinyMCE.activeEditor.isDirty === 'function') {
				if (tinyMCE.activeEditor.isDirty()) {
					return 'There is unsaved data.';
				}
			}
			return undefined;
		};

	});
})(jQuery)