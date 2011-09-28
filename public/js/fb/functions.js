
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
		
/*		$('.add_cartbtn').ajaxStart(function(){
 				$(this).addClass('ui-widget-loading');
  		});
*/		
		//alert(URL);return false;
		
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
		$(this).ajaxStart(function(){
			$('#loader').show();
			$('#loader').css('visibility','visible');
			$('#loader').addClass('ui-widget-loading');

			$(this).fadeIn(3000, function() {
				$('#breadCrumb_cart').hide();
				$('#order_review').hide();
				$('#buttonArea').hide();
       		});
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
					$('#order_review').show();
					$('#merchantProd'+prodId).hide();
 					$('#cartRow_'+prodId).hide();
					$('#buttonArea').show();
 					$('#loader').hide();
					$('#loader').removeClass('ui-widget-loading');
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
		$(this).ajaxStart(function(){
			$('#loader').show();
			$('#loader').css('visibility','visible');
			$('#loader').addClass('ui-widget-loading');
			//$('#cartSubTotal').addClass('ui-widget-loading');
			$(this).fadeIn(3000, function() {
				$('#breadCrumb_cart').hide();
				$('#order_review').hide();
				$('#buttonArea').hide();
       		});
 		});
		//alert("test");return false;
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
				$('#cartSubTotal').html("$"+totalCost);
				$('#breadCrumb_cart').show();
				$('#order_review').show();
				$('#buttonArea').show();
				$('#loader').hide();
				$('#loader').removeClass('ui-widget-loading');
			}	
		}		
	});
}


function addShippingDetail(cartId)
{
		//alert(id);
		$(this).ajaxStart(function(){
			$('#loader').show();
			$('#loader').css('visibility','visible');
			$('#loader').addClass('ui-widget-loading');

			$(this).fadeIn(3000, function() {
				//$('#breadCrumb_cart').hide();
				$('#div2').hide();
				$('#buttonArea').hide();

      		});
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
				  return $('#IsBilling').val();
			  }
			  
         },
 		dataType:'html',
		success:function(data)
		{ 
			//alert(data);
 					$('#div2').show();
					//$('#cartRow_'+prodId).hide();
					$('#buttonArea').show();
 					$('#loader').css('display','none');
					$('#loader').removeClass('ui-widget-loading');
				
			//top.location.href = '';
			//
		}		
		});
	
}

function addShippingDetail1(cartId)
{
		//alert(id);
		$(this).ajaxStart(function(){
			$('#loader').show();
			$('#loader').css('visibility','visible');
			$('#loader').addClass('ui-widget-loading');

			$(this).fadeIn(3000, function() {
				//$('#breadCrumb_cart').hide();
				$('#div7').hide();
				$('#buttonArea').hide();

      		});
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
				  return add+" "+add1;
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
					//$('#cartRow_'+prodId).hide();
					$('#buttonArea').show();
 					$('#loader').css('display','none');
					$('#loader').removeClass('ui-widget-loading');
				
			//
		}		
		});
	
}


function GetStateforCountry(id)
{
		 //alert(id);
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
		 //alert(id);
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
	
	var ThumbURLs = ImagePath+"/"+Imagename;
 	
	$('#bigimg1 img').attr('src', ThumbURLs);
 
}

function GetShippingCost(ShippMethodId,prodId)
{
		if(ShippMethodId == 0)
		{
			$('#ShippingSpace'+prodId).hide();
			$('#ShippingRow'+prodId).hide();
			return false;
		}
		
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
			//alert(data);
			if(data != '')
			{
				$('#ShippingSpace'+prodId).show();
				$('#ShippingRow'+prodId).show();
				$('#ShippingCost'+prodId).html("$"+data);
			}
 		}		
	});

	
}


function UpdateShippingCost(ShippMethodId,prodId)
{
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
