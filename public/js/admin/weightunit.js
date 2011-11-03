
/////////////////////  Validate Add Category form      ///////////////////////////

function ValidateAddFrom()
{
	$("#add_form").validate({
							
		rules: {
					from_id: {  required: true  },
					to_id: {  required: true  },
					value: {  required: true  }
			   },
			   
		messages:{
					from_id: { required: ERR_WEIGHT_FROM_ID  },							  
					to_id: { required: ERR_WEIGHT_TO_ID  },
					value: { required: ERR_WEIGHT_VALUE  }
				 },
				 
		errorPlacement: function(error, element) 
		{ 
			if ( element.is(":radio") ) { 
			
				error.appendTo (element.parent().next() ); 
				
			} else {
				
				error.appendTo( element.next().next() ); 
				
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
					value: {  required: true  }
			   },
			   
		messages:{
					from_id: { required: ERR_WEIGHT_FROM_ID  },							  
					to_id: { required: ERR_WEIGHT_TO_ID  },
					value: { required: ERR_WEIGHT_VALUE  }
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