
   $ = jQuery;

   jQuery(function() {

      jQuery('form').areYouSure();
      jQuery('form.dirty-check').areYouSure();
      jQuery('form').areYouSure( {'message':'Your changes are not saved!'} );

   });
