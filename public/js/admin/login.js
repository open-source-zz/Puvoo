//////////////////////////// 	Validate Login form 	/////////////////////////////////////

$(document).ready(function() {


	$("#frmLogin").validate({
							
		rules: {
					user_name: {required: true},
					password: {required: true}
					
				},
		messages:{
					user_name: {required: "Please enter username"},
					password: {required: "Please enter password"}
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
			  		forgotpass_email: {required: "Please enter email Id", email: "Invalid email Id",}			  
			  },
			  
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 				
		}
	});
}