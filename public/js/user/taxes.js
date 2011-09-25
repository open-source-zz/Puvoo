// JavaScript Document


/////////////////  For the TAX RATES   //////////////////////////


function ValidateAddRatesFrom()
{
	
	$("#add_form").validate({
							
		rules: {	tax_name: {  required: true  }, 
					tax_zone: { required: true },
					tax_price: { required: true, number: true },
				},
			   
		messages:{	tax_name: {  required: ERR_TAX_NAME  }, 
					tax_zone: {  required: ERR_TAX_ZONE  },
					tax_price: {  required: ERR_TAX_PRICE, number: ERR_TAX_PRICE_INVALID  },
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

function ValidateEditRatesFrom()
{
	$("#edit_form").validate({
							
		rules: {	tax_name: {  required: true  }, 
					tax_zone: { required: true },
					tax_price: { required: true, number: true },
				},
			   
		messages:{	tax_name: {  required: ERR_TAX_NAME  }, 
					tax_zone: {  required: ERR_TAX_ZONE  },
					tax_price: {  required: ERR_TAX_PRICE, number: ERR_TAX_PRICE_INVALID  },
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



/////////////////  For the TAX ZONE COUNTRY   //////////////////////////



var country_array = new Array(); 
var current_country = null; 
function ShowCountryDialog()
{	
	var zone_value = $("#tax_zone").val();
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

	$("#Zone_Result").html($("#tax_zone").val());
	$( "#dialog-addCountry" ).dialog({
		resizable: false,
		height:350,
		width:600,
		modal: true,
		buttons: {
			'Save': function() {
				$("#tax_zone").val($("#Zone_Result").html());
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$("#Zone_Result").html("");
				$( this ).dialog( "close" );
			},
			'Clear': function() {
				$("#Zone_Result").html("");
			}
		}
	});	
	
}

function AddCountry()
{
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
				  url: site_url+'user/taxrates/fillstate/',
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
