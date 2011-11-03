// JavaScript Document

/////////////////////  Validate Add Length form      ///////////////////////////

function ValidateAddFrom()
{
	$("#add_form").validate({
							
		rules: {
					length_unit_name: {  required: true  },
					length_unit_key: {  required: true  }
			   },
			   
		messages:{
					length_unit_name: { required: ERR_LENGTH_UNIT_NAME  },							  
					length_unit_key: { required: ERR_LENGTH_UNIT_KEY  }
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

/////////////////////  Validate Edit Length form      ///////////////////////////
function ValidateEditFrom()
{
	
	$("#edit_form").validate({
							
		rules: {
					length_unit_name: {  required: true  },
					length_unit_key: {  required: true  }
			   },
			   
		messages:{
					length_unit_name: { required: ERR_LENGTH_UNIT_NAME  },							  
					length_unit_key: { required: ERR_LENGTH_UNIT_KEY  }
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
