
/////////////////////  Validate Add Language form      ///////////////////////////

function ValidateAddForm()
{
	$("#add_form").validate({
							
		rules: {
					name: { required: true },
					code: { required: true },
					charset: { required: true },
					numeric_separator_decimal: { required: true },
					numeric_separator_thousands: { required: true },
			   },
			   
		messages:{
					name: { required: ERR_LANGUAGE_NAME },
					code: { required: ERR_LANGUAGE_CODE },
					charset: { required: ERR_LANGUAGE_CHARSET },
					numeric_separator_decimal: { required: ERR_LANGUAGE_DECIMAL },
					numeric_separator_thousands: { required: ERR_LANGUAGE_THOUSANDS },
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


/////////////////////  Validate Edit Language form      ///////////////////////////
function ValidateEditForm()
{
	
	$("#edit_form").validate({
							
		rules: {
					name: { required: true },
					code: { required: true },
					charset: { required: true },
					numeric_separator_decimal: { required: true },
					numeric_separator_thousands: { required: true },
			   },
			   
		messages:{
					name: { required: ERR_LANGUAGE_NAME },
					code: { required: ERR_LANGUAGE_CODE },
					charset: { required: ERR_LANGUAGE_CHARSET },
					numeric_separator_decimal: { required: ERR_LANGUAGE_DECIMAL },
					numeric_separator_thousands: { required: ERR_LANGUAGE_THOUSANDS },
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
