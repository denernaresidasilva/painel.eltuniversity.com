jQuery(document).ready(function($){
	'use strict';
	$('.notice-webinarignition-free button.notice-dismiss').click(function(){

		$.ajax({
			url: wi_notice_var.ajaxurl,
	        dataType: "json",
	        type: "GET",
	        async: true,
	        data: {
	        	dismiss_wi_notice : true,
	        	action            : 'webinarignition_dismiss_notice',
				security 		  : wi_notice_var.nonce
	        },
	        success: function (data) {
	           
	        },
		});
	});
});