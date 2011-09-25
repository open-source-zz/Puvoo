
/////////////////////  Validate Add Category form      ///////////////////////////

function ValidateAddFrom()
{
	$("#add_form").validate({
							
		rules: {
					weight_unit_name: {  required: true  },
					weight_unit_key: {  required: true  },
			   },
			   
		messages:{
					weight_unit_name: { required: ERR_WEIGHT_UNIT_NAME  },							  
					weight_unit_key: { required: ERR_WEIGHT_UNIT_KEY  },
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
					weight_unit_name: {  required: true  },
					weight_unit_key: {  required: true  },
			   },
			   
		messages:{
					weight_unit_name: { required: ERR_WEIGHT_UNIT_NAME  },							  
					weight_unit_key: { required: ERR_WEIGHT_UNIT_KEY  },
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
