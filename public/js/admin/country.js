
/////////////////////  Validate Add Country form      ///////////////////////////

function ValidateAddForm()
{
	$("#add_form").validate({
							
		rules: {
					country_name: {  required: true  },
					country_iso2: {  required: true  },
					country_iso3: {  required: true  }
			   },
			   
		messages:{
					country_name: { required: ERR_COUNTRY_NAME  },							  
					country_iso2: { required: ERR_ISO2_CODE  },							  
					country_iso3: { required: ERR_ISO3_CODE  }					  
					
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


function ValidateEditForm()
{
	
	$("#edit_form").validate({
							
		rules: {
					country_name: {  required: true  },
					country_iso2: {  required: true  },
					country_iso3: {  required: true  }
			   },
			   
		messages:{
					country_name: { required: ERR_COUNTRY_NAME  },							  
					country_iso2: { required: ERR_ISO2_CODE  },							  
					country_iso3: { required: ERR_ISO3_CODE  }					  
					
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



/////////////////////  Validate State forms      ///////////////////////////

function viewStateRecords(id, frmname, action)
{
	$("#"+frmname).attr("action",action+"/cid/"+id).submit();		
}

function stateAddClick(frmname,action,id)
{
	window.location = action+"/cid/"+id;	
}

function editStateRecord(id, frmname, action, country_id)
{
	$("#hidden_primary_state_id").val(id);
	$("#"+frmname).attr("action",action+"/cid/"+country_id).submit();		
}

function deleteStateRecord(id,formname,action){
	
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height:'auto',
		modal: true,
		buttons: {
			"Delete": function() {
				$("#hidden_primary_state_id").val(id);
				$("#"+formname).attr("action",action).submit();			
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});		
	
}

function ValidateAddStateForm()
{
	$("#add_state_form").validate({
							
		rules: { state_name: {  required: true  } },
			   
		messages:{ state_name: { required: ERR_STATE_NAME  } },
				 
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 
		}
	});
	
}

function ValidateEditStateForm()
{
	$("#edit_state_form").validate({
							
		rules: { state_name: {  required: true  } },
			   
		messages:{ state_name: { required: ERR_STATE_NAME  } },
				 
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 
		}
	});
	
}