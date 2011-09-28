

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
								required: OLD_PASSWORD_NEEDED,
								minlength:PASSWORD_CHARACTER_LIMIT,
								maxlength:PASSWORD_CHARACTER_LIMIT
							  },
							  
					new_password: {	
								required: EMPTY_NEW_PASSWORD,
								minlength:PASSWORD_CHARACTER_LIMIT,
								maxlength:PASSWORD_CHARACTER_LIMIT
							  },
							  
					confirm_password: {
								required: EMPTY_CONFIRM_PASSWORD,
								minlength:PASSWORD_CHARACTER_LIMIT,
								maxlength:PASSWORD_CHARACTER_LIMIT,
								equalTo:PASSWORD_MISMATCH
							   }
				 },
				 
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 
		}
	});
	
});

///////////////////////// User Orders Search ////////////////////////

function searchDashOrder(frm,value)
{
	$("#order_status").val(value);
	$("#"+frm).submit();
}