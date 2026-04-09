(function( $ ) {
    $( window ).on( "load", function() {
        if ($('.wi_phone_number').length > 0) {
            $.get(window.location.protocol + "//ipinfo.io", function (response) {
                $(".wi_phone_number").intlTelInput({
                    defaultCountry: response.country.toLowerCase()
                });
            }, "jsonp");
        }
    });
})( jQuery );
