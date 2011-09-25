// JavaScript Document

$(document).ready(function() {
						   
	$("#user_registration_form").validate({
							
		rules: {
					user_fname: 		{  required: true,  },
					user_lname: 		{  required: true,  },
					user_email:			{  required: true, email : true },
					user_password: 		{  required: true, minlength:6, maxlength:16 },
					user_conf_password:	{  required: true, equalTo:'#user_password'  },
					user_facebook_id: 	{  required: true,  },
			   },
			   
		messages:{
					user_fname: 		{  required: ERR_REG_FNAME,  },
					user_lname: 		{  required: ERR_REG_LNAME,  },
					user_email:			{  required: ERR_REG_EMAIL,  email: ERR_REG_INVALID_EMAIL },
					user_password: 		{  required: ERR_REG_PASSWORD, minlength:ERR_REG_PASSWORD_MIN_MAX, maxlength:ERR_REG_PASSWORD_MIN_MAX  },
					user_conf_password:	{  required: ERR_REG_CONF_PASSWORD, equalTo:ERR_REG_CONF_PASSWORD_MATCH  },
					user_facebook_id: 	{  required: ERR_REG_FB,  },
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
});	