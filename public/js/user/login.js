//////////////////////////// 	Validate Login form 	/////////////////////////////////////

$(document).ready(function() {


	$("#frmLogin").validate({
							
		rules: {
					user_name: {required: true},
					password: {required: true}
					
				},
		messages:{
					user_name: {required: ERR_ENTER_USERNAME},
					password: {required: ERR_ENTER_PASSWORD}
				 },
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 	
			
		}
	});
	
 });

//////////////////////////// 	Validate Forgot password form 	/////////////////////////////////////

function validateForgotPassForm()
{
	$("#forgotpass_form").validate({
								   
		rules:{ 
					forgotpass_email: {required: true, email: true,}					
			  },
			  
		messages:{
			  		forgotpass_email: {required: ERR_EMPTY_EMAIL, email: ERR_INVALID_EMAIL}			  
			  },
			  
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 				
		}
	});
}