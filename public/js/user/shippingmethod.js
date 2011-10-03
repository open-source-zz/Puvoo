// JavaScript Document

function ValidateAddFrom()
{
	
	$("#add_form").validate({
							
		rules: {
					shipping_method_name: {  required: true  },
					shipping_zone: {  required: true  },
					shipping_price: {  required: true },
				},
			   
		messages:{
					shipping_method_name: {  required: ERR_SHIPPING_METHOD_NAME  },
					shipping_zone: {  required: ERR_SHIPPING_ZONE  },
					shipping_price: {  required: ERR_SHIPPING_METHOD_RATE  },
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
					shipping_method_name: {  required: true  },
					shipping_zone: {  required: true  },
					shipping_rate: {  required: true },
				},
			   
		messages:{
					shipping_method_name: {  required: ERR_SHIPPING_METHOD_NAME  },
					shipping_zone: {  required: ERR_SHIPPING_METHOD_NAME  },
					shipping_rate: {  required: ERR_SHIPPING_METHOD_RATE  },
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

var country_array = new Array(); 
var current_country = null; 
function ShowCountryDialog()
{	

	var zone_value = $("#shipping_zone").val();
	if (zone_value != '') { 
		var country_str = zone_value.split(",");
		
		for(tmp in country_str)
		{
			if(country_str[tmp].indexOf(":") > 0) {
				
				var temp = country_str[tmp].split(":");
				country_array[temp[0]] = '';
				
			} else {
				country_array[country_str[tmp]] = '';
			}
		}
		
	} 
	
	$("#Zone_Result").html($("#shipping_zone").val());
	$( "#dialog-addCountry" ).dialog({
		resizable: false,
		height:350,
		width:600,
		modal: true,
		buttons: {
			'Save': function() {
				$("#shipping_zone").val($("#Zone_Result").html());
				$( this ).dialog( "close" );
				StopBlink();
				document.getElementById("store_country").options.selectedIndex = 0;
			},
			Cancel: function() {
				$("#Zone_Result").html("");
				$( this ).dialog( "close" );
				StopBlink();
				document.getElementById("store_country").options.selectedIndex = 0;
			},
			'Clear': function() {
				$("#Zone_Result").html("");
				country_array = new Array();
				StopBlink();
				document.getElementById("store_country").options.selectedIndex = 0;
			}
		}
	});	
	
}

function AddCountry()
{
	StopBlink();
	var array = $("#store_country").val().split("/");
	var flag = 0;
		
	for(tmp in country_array)
	{
		if(tmp == array[1]) {
			flag = 1;
			break;
		}
	}
	country_array[array[1]] = '';
	current_country = array[1];
	if(flag != 1) {
		if(array != "" ) {
		
			var country_result = $("#Zone_Result").html();
			if(country_result != "") {
				
				$("#Zone_Result").html(country_result+","+array[1]);
				
			} else {
				
				$("#Zone_Result").html(country_result+array[1]);		
			}
			
			$.ajax({
				  type: 'POST',
				  url: site_url+'user/shippingmethod/fillstate/',
				  data: "country_id="+array[0],
				  dataType:'html',
				  success: function(data) 
				  { 
					 $("#store_country_state").html(data);
				  }
			});
		} else {
			
			alert(ERR_SHIPPING_SELECT_COUNTRY);
		}
	} else {
		alert(ERR_COUNTRY_EXIST);
	}
	
	document.getElementById("store_country").options.selectedIndex = 0;
}

function AddCountryState()
{
	StopBlink();
	var state = $("#store_country_state").val();
	var string = '';
	var flag = 0;
	
	if(state != '')
	{
		for(tmp in country_array)
		{
			if(tmp == current_country) {
				string = country_array[tmp];
				break;
			}
		}
			
		if(string != '' ) {
	
			var state_array = country_array[current_country].split(",");
			
			for(tmp in state_array)
			{
				if(state_array[tmp] == state) {
					flag = 1;
					break;
				}
			}
			
			if (flag != 1 ) {
				country_array[current_country] = string+','+state;	
				var country_result = $("#Zone_Result").html();
				$("#Zone_Result").html(country_result+":"+state);	
				
			} else {
				alert(ERR_STATE_EXIST);
			}
			
		} else {
			country_array[current_country] = state;
			var country_result = $("#Zone_Result").html();
			$("#Zone_Result").html(country_result+":"+state);	
		}
		
	} else {
		alert(ERR_SHIPPING_SELECT_STATE);	
	}
	
	document.getElementById("store_country_state").options.selectedIndex = 0;
}

var BlickID = '';
function BlinckLink(val,id)
{
	BlickID = id;
	if( val != "" )	{
		$("#"+BlickID).css({'text-decoration' : 'blink', 'color' : 'red'});		
	}
}

function StopBlink()
{
	$("#"+BlickID).css({'text-decoration' : 'none', 'color' : '#333333'});	
}