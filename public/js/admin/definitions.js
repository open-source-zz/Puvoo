/////////////////////  Validate Add Definition form      ///////////////////////////

function ValidateAddForm()
{
	$("#add_form").validate({
							
		rules: {
					definition_key: { required: true },
					definition_value: { required: true },
					
			   },
			   
		messages:{
					definition_key: { required: ERR_DEFINITION_KEY },
					definition_value: { required: ERR_DEFINITION_VALUE },
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


/////////////////////  Validate Edit Definition form      ///////////////////////////
function ValidateEditForm()
{
	
	$("#edit_form").validate({
							
		rules: {
					definition_key: { required: true },
					definition_value: { required: true },
			   },
			   
		messages:{
					definition_key: { required: ERR_DEFINITION_KEY },
					definition_value: { required: ERR_DEFINITION_VALUE },
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
