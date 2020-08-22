/*
  Jquery Validation using jqBootstrapValidation
   example is taken from jqBootstrapValidation docs
  */
$(function() {

 $("input,textarea").jqBootstrapValidation(
    {

     preventSubmit: true,
     submitError: function($form, event, errors) {
      // something to have when submit produces an error ?
      // Not decided if I need it yet
     },
     submitSuccess: function($form, event) {
      event.preventDefault(); // prevent default submit behaviour
       // get values from FORM

       var name = encodeURIComponent($("input#name").val());
       var email = encodeURIComponent($("input#email").val());
       var message = encodeURIComponent($("textarea#message").val());
       var contactSelection =  $('#contactselection option:selected').attr('value');
       var urlField =  $('#urlField').val();
	 $.ajax({
              url: "./bin/submit.php",
            	type: "POST",
            	data: {name: name, email: email, message: message, urlField: urlField},
              cache: false,
            	success: function() {
            	// Success message
            	   $('#success').html("<div class='alert alert-success'>");
            	   $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            		.append( "</button>");
                if ($('html').attr('lang') == 'en')$('#success > .alert-success').append("<strong>Your message has been sent.</strong>");

 		  $('#success > .alert-success')
 			.append('</div>');

 		  //clear all fields
 	$('#contactForm').trigger("reset");
 	      },
 	    error: function() {
 		// Fail message
 		 $('#success').html("<div class='alert alert-danger'>");
            	$('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
            	 .append( "</button>");
               if ($('html').attr('lang') == 'en')$('#success > .alert-danger').append("<strong>Sorry "+name+" it seems that our mail server is not responding...</strong>Could you please email us directly to rentals@zeitraum.re? <br>We apologize for the inconvenience!");
              
          $('#success > .alert-danger').append('</div>');
 		//clear all fields
 	$('#contactForm').trigger("reset");
  },

           })
           },
         filter: function() {
                   return $(this).is(":visible");
         },
       });

      $("a[data-toggle=\"tab\"]").click(function(e) {
                    e.preventDefault();
                    $(this).tab("show");
        });
  });


/*When clicking on Full hide fail/success boxes */
$('#name').focus(function() {
     $('#success').html('');
  });
