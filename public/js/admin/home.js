

/////////////////////  Validate Change Password form      ///////////////////////////

$(document).ready(function() {

	$("#changepassword_form").validate({
							
		rules: {
					old_password: {  
								required: true,  
								minlength:6,  
								maxlength:16 
							  },
							  
					new_password: {
								required: true,
								minlength:6,
								maxlength:16
							  },
							  
					confirm_password: {
								required: true,
								minlength:6,
								maxlength:16,
								equalTo:'#new_password'
							 }	
			   },
			   
		messages:{
					old_password: {
								required: ERR_OLD_PASSWORD,
								minlength: ERR_OLD_MAX_PASSWORD,
								maxlength: ERR_OLD_MAX_PASSWORD
							  },
							  
					new_password: {	
								required: ERR_NEW_PASSWORD,
								minlength: ERR_NEW_MAX_PASSWORD,
								maxlength: ERR_NEW_MAX_PASSWORD
							  },
							  
					confirm_password: {
								required: ERR_CONF_PASSWORD,
								minlength:ERR_CONF_MAX_PASSWORD,
								maxlength: ERR_CONF_MAX_PASSWORD,
								equalTo: ERR_CONF_INVALID_PASSWORD
							   }
				 },
				 
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 
		}
	});
	
});