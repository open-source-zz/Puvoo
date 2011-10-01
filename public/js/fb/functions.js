

function getPage(page)
{
	$('#page_no').val(page);
	$("#frmcatproduct").submit();
}

function reloadPageWithNewSort(val)
{
	$("#frmcatproduct").submit();
}

function validateAddToCart(URL)
{	
		
		var NoOfCombo = $('#Opt_Combo').val();
		var value = new Array();
		for(i=0; i < NoOfCombo; i++)
		{
			value[i] = $('#OptionCombo'+i).val();
			
			if($('#OptionCombo'+i).val() == 0){
				
				var value = $('#OptionCombo'+i+' option').html();
 				var msg = "Please "+value.toLowerCase()+" option";
				alert(msg);
				return false;
			}

		}
	 	$.ajax({
 	    type: "POST",
		url:URL,
 		data: {
			  prodid: function() 
			  {
					return $('#productId').val();
			  },
			  option: function() 
			  {
					var NoOfCombo = $('#Opt_Combo').val();
					var value = new Array();
					for(i=0; i < NoOfCombo; i++)
					{
						value[i] = $('#OptionCombo'+i).val(); 
					}
					return value;
			  },
         },
 		dataType:'json',
		success:function(data)
		{ 
			alert(data);
  			top.location.href = '';
 		}		
	});

}

function DeleteCartProduct(prodId,cartId)
{
	
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#breadCrumb_cart').hide();
					$('#order_review').hide();
					$('#buttonArea').hide();
					$('#SubtotalDiv').hide();
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});
	
 	 	$.ajax({
 	    type: "POST",
		url:site_url+"cart/deletecartproduct",
 		data: {
			  prodid: function() 
			  {
					return prodId;
			  },
			  cartid: function()
			  {
				  return cartId;
			  }
         },
 		dataType:'json',
		success:function(data)
		{ 
			//alert(data);return false;
				$('#cartCounterNumber').html(data);
				if(data != 0){
					
					$('#breadCrumb_cart').show();
					$('#SubtotalDiv').show();
					$('#order_review').show();
					$('#merchantProd'+prodId).hide();
 					$('#cartRow_'+prodId).hide();
					$('#buttonArea').show();
				}else{
					$('#breadCrumb_cart').hide();
					$('#contactArea').hide();
					$('#buttonArea').hide();
					$('#EmptyCart').show();
				}
			//top.location.href = '';
			//
		}		
	});
}

function updatecart(prodId,cartId)
{
	
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#breadCrumb_cart').hide();
					$('#order_review').hide();
					$('#buttonArea').hide();
					$('#SubtotalDiv').hide();
					
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});
	
	
  	 	$.ajax({
 	    type: "POST",
		url:site_url+"cart/updatecart",
 		data: {
			  prodid: function() 
			  {
					return prodId;
			  },
			  cartid: function()
			  {
				  return cartId;
			  },
			  prodPrice: function()
			  {
				  return $('#ProductTotalPrice'+prodId).val();
			  },
			  prodQty: function()
			  {
				  return $('#ProductQty'+prodId).val();
			  }

         },
 		dataType:'json',
		success:function(data)
		{ 
			//alert(data);return false;
			var totalCost = 0;
			var ProdQty = 0;
			
 			for(i in data)
			{
						
				$('#itemPrice'+data[i]['product_id']).html("$"+data[i]['product_total_cost']);
				$('#ProductQty'+data[i]['product_id']).val(data[i]['product_qty']);
				ProdQty = parseInt(ProdQty)+parseInt(data[i]['product_qty']);
				totalCost = parseFloat(totalCost)+parseFloat(data[i]['product_total_cost']);
				$('#cartCounterNumber').html(ProdQty);
				$('#SubtotalDiv').show();
				$('#cartSubTotal').html("$"+totalCost);
				$('#breadCrumb_cart').show();
				$('#order_review').show();
				$('#buttonArea').show();
			}	
			
		}		
	});
}


function addShippingDetail(cartId)
{
		//alert(id);
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#breadCrumb_cart').hide();
					$('#div2').hide();
					$('#buttonArea').hide();
						
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});
		
		$.ajax({
 	    type: "POST",
		url:site_url+"cart/updateshipping",
 		data: {
			  cartid: function()
			  {
				  return cartId;
			  },
			  fname: function()
			  {
				  return $('#firstname').val();
			  },
			  lname: function()
			  {
				 return $('#lastname').val();
			  },
			  email: function()
			  {
				  return $('#email').val();
			  },
			  telephone: function()
			  {
				  return $('#phone').val();
			  },
//			  carr_email: function()
//			  {
//				  return $('#c_email').val();
//			  },
			  add_type: function()
			  {
				  return $('#addressType').val();
			  },
			  country: function()
			  {
				  return $('#Shippingcountry').val();
			  },
			  add: function()
			  {
				  add = $('#address').val();
				  add1 = $('#address1').val();
				  return add+"@"+add1;
			  },
			  city: function()
			  {
				  return $('#city').val();
			  },
			  state: function()
			  {
				  return $('#Shippingstate').val();
			  },
			  postcode: function()
			  {
				  return $('#postcode').val();
			  },
			  isbilling: function()
			  {
				  
				  return $('#IsBilling').is(':checked');
			  }
			  
         },
 		dataType:'html',
		success:function(data)
		{ 
			//alert(data);
			//return false;
			//alert($('#IsBilling').is(':checked'));
					if($('#IsBilling').is(':checked') == true)
					{
						//alert("in");
						//$('#div2').show();
						//$('#cartRow_'+prodId).hide();
						ShowTab(3);
						$('#div3').html(data);
						$('#breadCrumb_cart').show();
						
					}else{
						//alert("out");
						$('#breadCrumb_cart').show();
						$('#buttonArea').show();
						$('#div8').show();
						$('#shipping_li').hide();
						$('#billing_li').show();
						$('#billing_li a').addClass('active');
						$('#div2').hide();
					}
				
			//top.location.href = '';
			//
		}		
		});
	
}

function addBillingDetail(cartId)
{
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#breadCrumb_cart').hide();
					$('#div8').hide();
					$('#buttonArea').hide();
						
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});

	$.ajax({
 	    type: "POST",
		url:site_url+"cart/updatebilling",
 		data: {
			  Bill_cartid: function()
			  {
				  return cartId;
			  },
			  Bill_fname: function()
			  {
				  return $('#bfirstname').val();
			  },
			  Bill_lname: function()
			  {
				 return $('#blastname').val();
			  },
			  Bill_email: function()
			  {
				  return $('#bemail').val();
			  },
			  Bill_telephone: function()
			  {
				  return $('#bphone').val();
			  },
//			  carr_email: function()
//			  {
//				  return $('#c_email').val();
//			  },
			  Bill_add_type: function()
			  {
				  return $('#baddressType').val();
			  },
			  Bill_country: function()
			  {
				  return $('#Billingcountry').val();
			  },
			  Bill_add: function()
			  {
				  add = $('#baddress').val();
				  add1 = $('#baddress1').val();
				  return add+"@"+add1;
			  },
			  Bill_city: function()
			  {
				  return $('#bcity').val();
			  },
			  Bill_state: function()
			  {
				  return $('#Billingstate').val();
			  },
			  Bill_postcode: function()
			  {
				  return $('#bpostcode').val();
			  }
			  
         },
 		dataType:'html',
		success:function(data)
		{ 
			ShowTab(3);
			$('#breadCrumb_cart').show();
			$('#SubtotalDiv').show();
 			$('#buttonArea').show();
			$('#div8').hide();
			$('#shipping_li').hide();
			$('#billing_li').show();
		}		
		});
	
}

function addShippingDetail1(cartId)
{
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader2').show();
				$('#loader2').css('visibility','visible');
				$('#loader2').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#settingarea').hide();						  
					$('#div7').hide();
					$('#buttonArea').hide();
						
				});
		   },
		   complete : function(){ 
				$('#loader2').hide();
				$('#loader2').removeClass('ui-widget-loading');
		   }		   
		});

	$.ajax({
 	    type: "POST",
		url:site_url+"cart/updateshipping",
 		data: {
			  cartid: function()
			  {
				  return cartId;
			  },
			  fname: function()
			  {
				  return $('#firstname1').val();
			  },
			  lname: function()
			  {
				 return $('#lastname1').val();
			  },
			  email: function()
			  {
				  return $('#email1').val();
			  },
			  telephone: function()
			  {
				  return $('#phone1').val();
			  },
//			  carr_email: function()
//			  {
//				  return $('#c_email1').val();
//			  },
			  add_type: function()
			  {
				  return $('#addressType1').val();
			  },
			  country: function()
			  {
				  return $('#Shippingcountry1').val();
			  },
			  add: function()
			  {
				  add = $('#address2').val();
				  add1 = $('#address3').val();
					return add+"@"+add1;
			  },
			  city: function()
			  {
				  return $('#city1').val();
			  },
			  state: function()
			  {
				  return $('#Shippingstate1').val();
			  },
			  postcode: function()
			  {
				  return $('#postcode1').val();
			  }
			  
         },
 		dataType:'html',
		success:function(data)
		{ 
			//alert(data);
			
 					$('#div7').show();
					$('#div3').html(data);
					$('#settingarea').show();
					//$('#cartRow_'+prodId).hide();
					$('#buttonArea').show();
				
			//
		}		
		});
	
}


function GetStateforCountry(id)
{
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					//$('#breadCrumb_cart').hide();
					//$('#div8').hide();
					$('#div2').hide();
					$('#buttonArea').hide();
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});

	$.ajax({
 	    type: "POST",
		url:site_url+"cart/getcountrystate",
 		data: {
			  countryid: function() 
			  {
					return $('#Shippingcountry').val();
			  }  
         },
 		dataType:'json',
		success:function(data)
		{ 
			$('#breadCrumb_cart').show();
			//$('#div8').show();
			$('#div2').show();
			$('#buttonArea').show();
			
			var combo = '';
			if(data != '')
			{
				for(i in data)
				{
					//alert(i+"=>"+data[i]['state_name']);
					combo +="<option value='"+data[i]['state_id']+"'>"+data[i]['state_name']+"</option>";
					$('#Shippingstate').html(combo);
				}
			}else{
					combo ="<option value='0'>Select State</option>";
					$('#Shippingstate').html(combo);
			}
  			//top.location.href = '';
 		}		
	});

}

function GetStateforCountry1(id)
{
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#settingarea').hide();						  
					$('#div7').hide();
					$('#buttonArea').hide();
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});

	$.ajax({
 	    type: "POST",
		url:site_url+"cart/getcountrystate",
 		data: {
			  countryid: function() 
			  {
					return $('#Shippingcountry1').val();
			  }  
         },
 		dataType:'json',
		success:function(data)
		{ 
			$('#settingarea').show();						  
			$('#div7').show();
			$('#buttonArea').show();
			
			var combo = '';
			if(data != '')
			{
				for(i in data)
				{
					//alert(i+"=>"+data[i]['state_name']);
					combo +="<option value='"+data[i]['state_id']+"'>"+data[i]['state_name']+"</option>";
					$('#Shippingstate1').html(combo);
				}
			}else{
					combo ="<option value='0'>Select State</option>";
					$('#Shippingstate1').html(combo);
			}
  			//top.location.href = '';
 		}		
	});

}


function showProductThumbImage(ThumbIndex,ImagePath,Imagename) {
	for(i=0; i<4; i++)
	{
		otherid = '#Tiny'+i;
		$(otherid).addClass("otherimg");
	}
	
	var id = '#Tiny'+ThumbIndex;
	
	$(id).removeClass("otherimg");
	$(id).addClass("active");
	
	var ThumbURLs = produImagePath+ImagePath+"/"+Imagename;
	
	//alert(ThumbURLs);
 	
	$('#bigimg1 img').attr('src', ThumbURLs);
 
}

var ShippingCost = new Array();

function GetShippingCost(ShippMethodId,prodId)
{
		$.ajaxSetup({
		   beforeSend : function(){ 
				//$('#loader').show();
				//$('#loader').css('visibility','visible');
				$('#ShippingCost'+prodId).html('');
				$('#ShippingCost'+prodId).addClass('ui-widget2-loading');
				
				$(this).fadeIn(3000, function() {
					//$('#breadCrumb_cart').hide();
					//$('#div3').hide();
					//$('#buttonArea').hide();
				});
		   },
		   complete : function(){ 
				//$('#loader').hide();
				$('#ShippingCost'+prodId).removeClass('ui-widget2-loading');
		   }		   
		});

	$.ajax({
 	    type: "POST",
		url:site_url+"cart/getshippingcost",
 		data: {
			  shipmethodid: function() 
			  {
					return ShippMethodId;
			  },
			  prodid: function() 
			  {
					return prodId;
			  }  
         },
 		dataType:'json',
		success:function(data)
		{ 
			$(this).fadeIn(3000, function() {
				$('#breadCrumb_cart').show();
				$('#buttonArea').show();
				$('#div3').show();
			
				var ship_total = new Array();
					$('#ShippingSpace'+prodId).show();
					$('#ShippingRow'+prodId).show();
					$('#ShippingCost'+prodId).html("$"+data);
					
					ShippingCost[prodId] =  parseFloat(data);
					
					var total = 0;
					for(temp in ShippingCost)
					{
						total = total + ShippingCost[temp]
					}
					
					$('#ShippingtotalAmount').val(total);
			});
 		}		
	});

	
}


function UpdateShipping(CartId,prodId)
{
	return false;
		$.ajaxSetup({
		   beforeSend : function(){ 
				$('#loader').show();
				$('#loader').css('visibility','visible');
				$('#loader').addClass('ui-widget-loading');
				
				$(this).fadeIn(3000, function() {
					$('#breadCrumb_cart').hide();
					$('#buttonArea').hide();
				});
		   },
		   complete : function(){ 
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
		   }		   
		});
	
		$.ajax({
 	    type: "POST",
		url:site_url+"cart/updateshippingcost",
 		data: {
			  shipmethodid: function() 
			  {
					return ShippMethodId;
			  },
			  prodid: function() 
			  {
					return prodId;
			  }  
         },
 		dataType:'json',
		success:function(data)
		{ 
			$('#breadCrumb_cart').show();
			$('#buttonArea').show();

			var combo = '';
			if(data != '')
			{
				for(i in data)
				{
					//alert(i+"=>"+data[i]['state_name']);
					combo +="<option value='"+data[i]['state_id']+"'>"+data[i]['state_name']+"</option>";
					$('#Shippingstate1').html(combo);
				}
			}else{
					combo ="<option value='0'>Select State</option>";
					$('#Shippingstate1').html(combo);
			}
  			//top.location.href = '';
 		}		
	});

	
}
