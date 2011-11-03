
/////////////////////  Validate Add Category form      ///////////////////////////

function ValidateAddFrom()
{
	$("#add_form").validate({
							
		rules: {
					currency_name: {  required: true  },
					currency_code: {  required: true  },
					currency_symbol: {  required: true  },
					currency_value: {  required: true, number: true   }
			   },
			   
		messages:{
					currency_name: { required: ERR_CURRENCY_NAME  },							  
					currency_code: { required: ERR_CURRENCY_CODE  },							  
					currency_symbol: { required: ERR_CURRENCY_SYMBOL  },							  
					currency_value: { required: ERR_CURRENCY_VALUE,  number: ERR_CURRENCY_INVALID_VALUE }
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


function ValidateEditFrom()
{
	
	$("#edit_form").validate({
							
		rules: {
					currency_name: {  required: true  },
					currency_code: {  required: true  },
					currency_symbol: {  required: true  },
					currency_value: {  required: true, number: true   }
			   },
			   
		messages:{
					currency_name: { required: ERR_CURRENCY_NAME  },							  
					currency_code: { required: ERR_CURRENCY_CODE  },							  
					currency_symbol: { required: ERR_CURRENCY_SYMBOL  },							  
					currency_value: { required: ERR_CURRENCY_VALUE,  number: ERR_CURRENCY_INVALID_VALUE }
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
