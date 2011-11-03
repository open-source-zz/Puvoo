
/////////////////////  Validate Add Category form      ///////////////////////////

function ValidateAddFrom()
{
	$("#add_form").validate({
							
		rules: {
					category_name: {  required: true  },							  
					is_active: { required: true }
			   },
			   
		messages:{
					category_name: { required: ERR_CATEGORY_NAME  },							  
					is_active: { required: ERR_IS_ACTIVE  }
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
					category_name: {  
								required: true
							  },
							  
					is_active: {
								required: true
							  }
			   },
			   
		messages:{
					category_name: {
								required: ERR_IS_ACTIVE								
							  },
							  
					is_active: {	
								required: ERR_IS_ACTIVE
							  }
							  
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
