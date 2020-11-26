(function ($, Drupal) {
  //'use strict';
  Drupal.behaviors.serempreUsr = {
    attach: function (context, settings) {

      $(document, context).once('serempreUsr').each( function() {

        var $form = $("form#user-register", context);
        var $submitButton = $('input[type="submit"]', $form);
        
        // validate user register form
        $("#user-register").validate({
          rules: {
            name: {
              required: true,
              //minlength: 5,
              lettersonly:true 
            }
          },
          messages: {
            name: {
              required: "Enter a name",
              //minlength: "Enter at least 5 characters",
              lettersonly: "Please Enter Only Letters"
            }
          },
          errorPlacement: function(error, element) {
            error.appendTo(element.parent());
          },
          submitHandler: function() {
            $submitButton.trigger('validateUserForm');
          }
        });

      });

    }
  };
})(jQuery, Drupal);
