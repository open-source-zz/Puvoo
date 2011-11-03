// JavaScript Document


/////////////////////  Validate Add Category form      ///////////////////////////

function ValidateAddFrom()
{
	
	$("#add_form").validate({
							
		rules: {
					user_fname: 		{  required: true  },
					user_lname: 		{  required: true  },
					user_email:			{  required: true, email : true },
					user_password: 		{  required: true, minlength:6, maxlength:16 },
					user_facebook_id: 	{  required: true  },
					user_status:  		{  required: true  }
			   },
			   
		messages:{
					user_fname: 		{  required: ERR_MERCHANTS_FNAME  },
					user_lname: 		{  required: ERR_MERCHANTS_LNAME  },
					user_email:			{  required: ERR_MERCHANTS_EMAIL,  email: ERR_MERCHANTS_INVALID_EMAIL },
					user_password: 		{  required: ERR_MERCHANTS_PASSWORD, minlength:ERR_MERCHANTS_PASSWORD_MIN_MAX, maxlength:ERR_MERCHANTS_PASSWORD_MIN_MAX  },
					user_facebook_id: 	{  required: ERR_MERCHANTS_FB  },
					user_status:  		{  required: ERR_USER_STATUS  }
							  
				 },
				 
		errorPlacement: function(error, element) 
		{ 
			if ( element.is(":radio") ) { 
			
				error.appendTo (element.parent().next() ); 
				
			} else {
				
				error.appendTo( element.next() ); 
				
			}
		}
	});
	
}

function ValidateEditForm()
{
	$("#edit_form").validate({
							
		rules: {
					user_fname: 		{  required: true  },
					user_lname: 		{  required: true  },
					user_email:			{  required: true, email : true },
					user_facebook_id: 	{  required: true  },
					user_status:  		{  required: true  }
			   },
			   
		messages:{
					user_fname: 		{  required: ERR_MERCHANTS_FNAME  },
					user_lname: 		{  required: ERR_MERCHANTS_LNAME  },
					user_email:			{  required: ERR_MERCHANTS_EMAIL,  email: ERR_MERCHANTS_INVALID_EMAIL },					
					user_facebook_id: 	{  required: ERR_MERCHANTS_FB  },
					user_status:  		{  required: ERR_USER_STATUS  }
							  
				 },
				 
		errorPlacement: function(error, element) 
		{ 
			if ( element.is(":radio") ) { 
			
				error.appendTo (element.parent().next() ); 
				
			} else {
				
				error.appendTo( element.next() ); 
				
			}
		}
	});	
	
}