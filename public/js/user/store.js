
/////////////////////  Validate user store form      ///////////////////////////

$(document).ready(function() {

	$("#Userstoresetting_form").validate({
							
		rules: {
					story_name: 	{  	required: true  },
					story_desc: 	{  	required: true  },
					story_email: 	{  	required: true  },
					paypal_password: {  required: true  },
					paypal_signature:{  required: true  },
					story_currency:	{  	required: true  },
					store_country: 	{  	required: true  },
					story_address: 	{  	required: true  },
					story_city: 	{  	required: true  },
					store_state: 	{  	required: true  },
					store_zipcode: 	{  	required: true  }
			   },
			   
		messages:{
					story_name: 	{  	required: ERR_STORE_NAME  },
					story_desc: 	{  	required: ERR_STORE_DESC  },
					story_email: 	{  	required: ERR_STORE_EMAIL },
					paypal_password: {  required: ERR_STORE_PAYPAL_PASSWORD  },
					paypal_signature:{  required: ERR_STORE_PAYPAL_SIGNATURE  },
					story_currency:	{  	required: ERR_STORE_CURRENCY  },
					store_country: 	{  	required: ERR_STORE_COUNTRY  },
					story_address: 	{  	required: ERR_STORE_ADDRESS  },
					story_city: 	{  	required: ERR_STORE_CITY  },
					store_state: 	{  	required: ERR_STORE_STATE  },
					store_zipcode: 	{  	required: ERR_STORE_ZIPCODE  }
				 },
				 
		errorPlacement: function(error, element) 
		{ 
			error.appendTo( element.next() ); 
		}
	});
	
});

function FillStateCombo(id)
{	
	$.ajax({
		  type: 'POST',
		  url: site_url+'user/store/fillstate',
		  data: "country_id="+id,
		  dataType:'html',
		  success: function(data) 
	 	  { 
		  	 $("#store_state").html(data);
		  }
	});
}