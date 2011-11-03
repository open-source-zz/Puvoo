
/////////////////////  Validate Add Category form      ///////////////////////////

function ValidateAddFrom()
{
	$("#add_form").validate({
							
		rules: {
					from_id: {  required: true  },
					to_id: {  required: true  },
					value: {  required: true, number: true  }
			   },
			   
		messages:{
					from_id: { required: ERR_LENGTH_FROM_ID  },							  
					to_id: { required: ERR_LENGTH_TO_ID  },
					value: { required: ERR_LENGTH_VALUE, number: ERR_LENGTH_INVALID_VALUE  }
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
					from_id: {  required: true  },
					to_id: {  required: true  },
					value: {  required: true, number: true  }
			   },
			   
		messages:{
					from_id: { required: ERR_LENGTH_FROM_ID  },							  
					to_id: { required: ERR_LENGTH_TO_ID  },
					value: { required: ERR_LENGTH_VALUE, number: ERR_LENGTH_INVALID_VALUE  }
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
// JavaScript Document