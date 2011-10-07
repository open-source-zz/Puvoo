
/////////////////////  Validate Add Language form      ///////////////////////////

function ValidateAddForm()
{
	$("#add_form").validate({
							
		rules: {
					configuration_group_key: { required: true },
					
			   },
			   
		messages:{
					configuration_group_key: { required: ERR_CONFIGURATION_GROUP_KEY },
					
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
					configuration_group_key: { required: true },
					
			   },
			   
		messages:{
					configuration_group_key: { required: ERR_CONFIGURATION_GROUP_KEY },
					
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


function ValidateDefAddForm()
{
	$("#def_add_form").validate({
							
		rules: {
					configuration_key: { required: true },
					configuration_value: { required: true },
			   },
			   
		messages:{
					configuration_key: { required: ERR_CONFIGURATION_KEY },
					configuration_value: { required: ERR_CONFIGURATION_VALUE },
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
function ValidateDefEditForm()
{
	
	$("#def_edit_form").validate({
							
		rules: {
					configuration_key: { required: true },
					configuration_value: { required: true },
			   },
			   
		messages:{
					configuration_key: { required: ERR_CONFIGURATION_KEY },
					configuration_value: { required: ERR_CONFIGURATION_VALUE },
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
