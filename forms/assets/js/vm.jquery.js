; (function ($, window, document, undefined) {
    "use strict";

    $.VM_Behavior = $.VM_Behavior || {};

     var $docContact = $(document),
		$formIdContact = "#vm_contact_form",
		$submitButtonContact = "#vm_contact_button";

    $(document).ready(function () {
  
         $.VM_Behavior.validate_contact();
    })



      $.VM_Behavior.validate_contact = function () {

    
        // contact
        if ($($formIdContact).length > 0) // only working if that form exists
        {
            $($formIdContact).validate(
			{

			    rules:
	            {
	                contact_name:
	            	{
	            	    required: true,
	            	    minlength: 2
	            	},

	                contact_email:
	                {
	                    required: true,
	                    email: true
	                },

                    contact_subject:
	            	{
	            	    required: true,
	            	    minlength: 2
	            	},

	                contact_message:
	                {
	                    required: true,
	                    minlength: 10
	                }
	            },

			    // Specify the validation error messages
			    messages:
	            {
	                contact_name:
	            	{
	            	    required: "<i class='fa fa-close'></i>Please enter your name.",
	            	    minlength: "<i class='fa fa-close'></i>Name must be at least 2 characters."
	            	},

	                contact_email:
	            	{
	            	    required: "<i class='fa fa-close'></i>Your email address is required.",
	            	    email: "<i class='fa fa-close'></i>Invalid email adress."
	            	},

                    contact_subject:
	            	{
	            	    required: "<i class='fa fa-close'></i>You must enter a message subject.",
	            	    minlength: "<i class='fa fa-close'></i>Subject must be at least 2 characters."
	            	},

	                contact_message:
	            	{
	            	    required: "<i class='fa fa-close'></i>Please enter a message.",
	            	    minlength: "<i class='fa fa-close'></i>Message must be at least 10 characters."
	            	}
	            },

			    submitHandler: function (form) {
			        $(form).ajaxSubmit(
	                {
	                    beforeSend: function () {
	                       
	                        $($submitButtonContact).html("Sending>");
	                    },
	                    success: function (responseText, statusText, xhr, $form) {

	                        $(form).html("<p class='vm_success'>" + responseText + "</p>");

	                    }
	                });

			        return false;
			    }

			});
        }
        
    }

})(jQuery, window, document)

