// JavaScript Document

// JavaScript Document

$(function() {

	function runEffect() {
		// get effect type from 
		var selectedEffect = "blind"// $( "#effectTypes" ).val();

		// most effect types need no options passed by default
		var options = {};
		// some effects have required parameters
		if ( selectedEffect === "scale" ) 
		{
			options = { percent: 100 };
		}
		else if ( selectedEffect === "size" ) 
		{
			options = { to: { width: 280, height: 185 } };
		}

		// run the effect
		$( "#effect" ).show( selectedEffect, options, 500, callback );
	};

		//callback function to bring a hidden box back
		function callback() {
			
		};
		
	$( "#search" ).click(function() {
								  
			if ( $( "#search" ).attr('show') == "1")
			{
				runEffect(); 
			 	$( "#search" ).attr('show',"0") ;
				$( "#search" ).val(HIDE);
			}else
			{
				$( "#search" ).val(SEARCH);
				$( "#search" ).attr('show',"1") ;				
				$( "#effect:visible" ).removeAttr( "style" ).fadeOut('fast');				
			}
			return false;
		}); 
		
	$( "#effect" ).hide();

});

function getPage(page){
		
	$('#page_no').val(page);	
	$("form:first").submit();
	
}



/////////////////////  For Record Searching  //////////////////////////


function SearchRecords(s,frmname){
	
	$('#is_search').val(s);
	$('#'+frmname).submit();		
	
}

////////////////////// For View Record ////////////////////////////////

function viewRecord(id,frmname,action)
{
	$("#hidden_primary_id").val(id);
	$("#"+frmname).attr("action",action).submit();		
}

/////////////////////  For Record Searching  //////////////////////////


function editRecord(id,frmname,action){
	
	$("#hidden_primary_id").val(id);
	$("#"+frmname).attr("action",action).submit();		
}

/////////////////////  For Record Searching  //////////////////////////


function deleteRecord(id,formname,action){
	
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height:'auto',
		modal: true,
		buttons: {
			"Delete": function() {
				$("#hidden_primary_id").val(id);
				$("#"+formname).attr("action",action).submit();			
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});		
	
}

//////////////////////////	For Multiple Delete Category ///////////////////

function deleteAllRecords(formname,action)
{	
	$( "#dialog-confirm2" ).dialog({
		resizable: false,
		height:'auto',
		modal: true,
		buttons: {
			"Delete All": function() {
				$("#"+formname).attr("action",action).submit();	
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
}