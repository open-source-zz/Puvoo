// JavaScript Document

function ValidateProductAddForm()
{
	
	var temp = document.getElementsByName("multiselect_product_category");
	
	var cate_string = '';
	
	for(var i=0; i< temp.length; i++)
	{
		if(temp[i].checked)
		{
			cate_string += temp[i].value+',';
		}
	}
	
	$('#multiselect_product_category_value').val(cate_string);
		
	$("#add_form").validate({
							
			rules: {
						product_name: {  required: true  },							  
						product_description: { required: true },
						product_price: { required: true, number: true },
						product_code: { required: true },
						product_weight: { required: true, number: true },
						weight_unit_id: { required: true },
						length: { required: true, number: true },
						length_unit_id: { required: true },
						width: { required: true, number: true },
						depth: { required: true, number: true},
						available_qty: { required: true, number: true },
						discount: { required: true, number: true, range: [0, 100]  },
						start_sales: { required: true },
						available_date: { required: true },
						expiration_date: { required: true },
						promotion_start_date: { required: true },
						promotion_end_date: { required: true }
				   },
		   
			messages:{
						product_name: {  required: ERR_PRODUCT_NAME  },							  
						product_description: { required: ERR_PRODUCT_DESC },
						product_price: { required: ERR_PRODUCT_PRICE, number: ERR_PRODUCT_INVALID_PRICE },
						product_code: { required: ERR_PRODUCT_CODE },
						product_weight: { required: ERR_PRODUCT_WEIGHT, number: ERR_PRODUCT_INVALID_WEIGHT },
						weight_unit_id: { required: ERR_PRODUCT_WEIGHT_UNIT },
						length: { required: ERR_PRODUCT_LENGTH, number: ERR_PRODUCT_INVALID_LENGTH },
						length_unit_id: { required: ERR_PRODUCT_LENGTH_UNIT },
						width: { required: ERR_PRODUCT_WIDTH, number: ERR_PRODUCT_INVALID_WIDTH },
						depth: { required: ERR_PRODUCT_DEPTH, number: ERR_PRODUCT_INVALID_DEPTH},
						available_qty: { required: ERR_PRODUCT_QUANTITY, number: ERR_PRODUCT_INVALID_QUANTITY },
						discount: { required: ERR_PRODUCT_DISCOUNT, number: ERR_PRODUCT_INVALID_DISCOUNT, range: ERR_PRODUCT_RANGE_DISCOUNT  },
						start_sales: { required: ERR_PRODUCT_START_SALE },
						start_sales: { required: ERR_PRODUCT_START_SALE },
						available_date: { required: ERR_PRODUCT_PRIMARY_AVAILABLE_DATE },
						expiration_date: { required: ERR_PRODUCT_PRIMARY_EXPIRATION_DATE },
						promotion_start_date: { required: ERR_PRODUCT_PRIMARY_PROMOTION_START_DATE },
						promotion_end_date: { required: ERR_PRODUCT_PRIMARY_PROMOTION_END_DATE }
					 },
					 
			errorPlacement: function(error, element) 
			{ 
				if ( element.is(":radio") ) { 
				
					error.appendTo (element.parent().next() ); 
					
				} else {
					
					error.appendTo( element.parent().next() ); 
					
				}
			}
	});
	
}


function ValidateProductDetailForm()
{
	
	var temp = document.getElementsByName("multiselect_product_category");
	
	var cate_string = '';
	
	for(var i=0; i< temp.length; i++)
	{
		if(temp[i].checked)
		{
			cate_string += temp[i].value+',';
		}
	}
	
	$('#multiselect_product_category_value').val(cate_string);
	
	$("#detail_edit_form").validate({
							
			rules: {
						product_name: {  required: true  },							  
						product_description: { required: true },
						product_price: { required: true, number: true },
						product_code: { required: true },
						product_weight: { required: true, number: true },
						weight_unit_id: { required: true },
						length: { required: true, number: true },
						length_unit_id: { required: true },
						width: { required: true, number: true },
						depth: { required: true, number: true},
						available_qty: { required: true, number: true },
						discount: { required: true, number: true, range: [0, 100]  },
						start_sales: { required: true },
						available_date: { required: true  },
						expiration_date: { required: true },
						promotion_start_date: { required: true },
						promotion_end_date: { required: true }
				   },
		   
			messages:{
						product_name: {  required: ERR_PRODUCT_NAME  },							  
						product_description: { required: ERR_PRODUCT_DESC },
						product_price: { required: ERR_PRODUCT_PRICE, number: ERR_PRODUCT_INVALID_PRICE },
						product_code: { required: ERR_PRODUCT_CODE },
						product_weight: { required: ERR_PRODUCT_WEIGHT, number: ERR_PRODUCT_INVALID_WEIGHT },
						weight_unit_id: { required: ERR_PRODUCT_WEIGHT_UNIT },
						length: { required: ERR_PRODUCT_LENGTH, number: ERR_PRODUCT_INVALID_LENGTH },
						length_unit_id: { required: ERR_PRODUCT_LENGTH_UNIT },
						width: { required: ERR_PRODUCT_WIDTH, number: ERR_PRODUCT_INVALID_WIDTH },
						depth: { required: ERR_PRODUCT_DEPTH, number: ERR_PRODUCT_INVALID_DEPTH},
						available_qty: { required: ERR_PRODUCT_QUANTITY, number: ERR_PRODUCT_INVALID_QUANTITY },
						discount: { required: ERR_PRODUCT_DISCOUNT, number: ERR_PRODUCT_INVALID_DISCOUNT, range: ERR_PRODUCT_RANGE_DISCOUNT  },
						start_sales: { required: ERR_PRODUCT_START_SALE },
						available_date: { required: ERR_PRODUCT_PRIMARY_AVAILABLE_DATE },
						expiration_date: { required: ERR_PRODUCT_PRIMARY_EXPIRATION_DATE },
						promotion_start_date: { required: ERR_PRODUCT_PRIMARY_PROMOTION_START_DATE },
						promotion_end_date: { required: ERR_PRODUCT_PRIMARY_PROMOTION_END_DATE }
					 },
					 
			errorPlacement: function(error, element) 
			{ 
				if ( element.is(":radio") ) { 
				
					error.appendTo (element.parent().parent().next() ); 
					
				} else {
					
					error.appendTo( element.parent().next() ); 
					
				}
			}
	});
}

function createUploader(){
	
	var uploader = new qq.FileUploader({
			element: document.getElementById('file-uploader-demo1'),
			action: siteurl+'user/image/index/?id='+product_id,
			debug: true,
			actionType:'edit',
			allowedExtensions:['jpg','png','gif','jpeg'],
			multiple: true
		});  
}


function DeleteProductImage(product_id,image_id)
{
	var img_array = document.getElementsByName("product_primary_image");
	var flag = 0;
	if(img_array.length > 0 )
	{
		for(var i=0; i< img_array.length; i++)
		{
			if(img_array[i].checked)
			{
				flag = img_array[i].value;
			}
		}
	}
	
	if( flag > 0 && flag != image_id ) {
		
		$.ajax({
			type:'POST',
			url:siteurl+'user/products/deleteimage',
			data: "image_id="+image_id+"&product_id="+product_id,
			success:function(data)
			{
				$("#imagegallery"+image_id).remove();
				alert(data);		
			}
	    });
			
	} else {
		
		if(flag == 0) {
			alert(ERR_PRODUCT_PRIMARY_IMAGE);
		} else {
			alert(ERR_PRODUCT_PRIMARY_IMAGE_OTHER);
		}
		
	}
}

var PD_Option = new Array();
var PD_Option_Detail = new Array();
var PD_Option_Action = new Array();
var product_option_id = 0 ;

function EditProductOption(option_id)
{
	$.ajax({
			type:'GET',
			url:siteurl+'user/products/option/option_detail_id/'+option_id,
			dataType:"json",  
			success:function(data)	{ 
				ProductOptionArray = data;
				for(tmp in data)
				{
					$("#product_option_form #prod_"+tmp).val(data[tmp]);
				}
			}
	});
	ValidateProductOptionFrom();
	$( "#dialog-product-option" ).dialog({
		resizable: false,
		height:'auto',
		width:600,
		modal: true,
		buttons: {
			Update: function() {
				$("#product_option_form").submit();
			},
			Cancel: function() {
				$(".productoptval").val('');
				$( this ).dialog( "close" );
			}
		}
	});	
	
	$("#dialog-product-option").next().find(".ui-dialog-buttonset button:first-child span").html(UPDATE);
	$("#dialog-product-option").next().find(".ui-dialog-buttonset button:nth-child(2) span").html(CANCEL);
	
}



function ValidateProductOptionFrom()
{
	$("#product_option_form").validate({
							
			rules: {
						prod_option_name: {  required: true  },		
						prod_option_weight: { number: true },
						prod_option_price: {  number: true },
						prod_option_quantity: { number: true }
				   },
		   
			messages:{
						prod_option_name: {  required: ERR_PRODUCT_OPTION_VALUE_NAME  },		
						prod_option_weight: { number: ERR_PRODUCT_OPTION_VALUE_INVALID_WEIGHT },
						prod_option_price: { number: ERR_PRODUCT_OPTION_VALUE_INVALID_PRICE },
						prod_option_quantity: { number: ERR_PRODUCT_OPTION_VALUE_INVALID_QUANTITY  }
					 },
					 
			errorPlacement: function(error, element) 
			{ 
				error.appendTo( element.parent().next() ); 
			},
			submitHandler: function() {  
				var ValueString = '';
				var counter = 0;
				for( tmp in ProductOptionArray ) 
				{ 
					if( tmp == "product_id" ) {
						
						ValueString += "&product_id="+$("#product_id").val();						
					
					} else {
						
						if( counter == 0 ) {
							ValueString += tmp+"="+$("#product_option_form #prod_"+tmp).val();
						} else {
							ValueString += "&"+tmp+"="+$("#product_option_form #prod_"+tmp).val();
						}
					}
					counter++;
				}
				
				UpdateProductOptionValue(ValueString);
				$( "#dialog-product-option" ).dialog( "close" );
			}
	});	
}


function UpdateProductOptionValue(value)
{
	if(value != '') {
		$(".productoptval").val('');
		$.ajax({
			type:'POST',
			url:siteurl+'user/products/updateoptionvalue',
			data: value,
			dataType: 'json',
			success:function(returnArray)
			{	
				if( returnArray["error"] == undefined ) {
					
					alert(PRODUCT_OPTION_VALUE_EDIT_SUCCESS);
					$("#PD_Option_"+returnArray["product_options_id"]).html( returnArray["option_title"] );
					$("#POD_Name_"+returnArray["product_options_detail_id"]).html( returnArray["option_name"] );
					$("#POD_Code_"+returnArray["product_options_detail_id"]).html( returnArray["option_code"] );
					
				} else {					
					alert(returnArray["error"]);	
				}
				
			}
	    });
		
	}
}


function UpdateProductOption(id)
{
	if(PD_Option_Action[id] != '') {
		
		$.ajax({
			type:'POST',
			url:siteurl+'user/products/updateoption',
			data: "option_id="+id+"&option_title="+$("#PDO_"+id).val()+"&option_detail="+$("#PDOD_"+id).val(),
			success:function(data)
			{
				alert(data);		
			}
	    });
		$("#PD_Option_"+id).html($("#PDO_"+id).val());
		$("#PD_Option_Detail_"+id).html($("#PDOD_"+id).val());
		$("#PD_Option_Action_"+id).html(PD_Option_Action[id]);
		PD_Option_Action[id] = '';
	}
}

function DeleteProductOption(id)
{
	$( "#dialog-confirm" ).dialog({
		resizable: false,
		height:'auto',
		modal: true,
		buttons: {
			"Delete": function() {
				
				$.ajax({
					type:'POST',
					url:siteurl+'user/products/deleteoption',
					data: "option_id="+id,
					success:function(data)	{
						$("#PD_Row_"+id).next().remove();
						$("#PD_Row_"+id).remove();
					}
				});
				
				$( this ).dialog( "close" );
				
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});	
	
	$("#dialog-confirm").next().find(".ui-dialog-buttonset button:first-child span").html(DELETE);
	$("#dialog-confirm").next().find(".ui-dialog-buttonset button:nth-child(2) span").html(CANCEL);
	
}

var counter = 1;

function AddProductOption(prod_id)
{
	var OptHtml = '';
		OptHtml += '<tr id="add_demo_'+counter+'">';
		OptHtml += '<td width="25%"><input class = "Field150" type = "text" name = "PDO_add_'+counter+'" id = "PDO_add_'+counter+'" ></td>';
		OptHtml += '<td width="20%"><a onclick="Javascript: return AddOption('+counter+','+prod_id+')" style="color:#17649E; cursor:pointer;" >'+ADD+'</a>&nbsp;&nbsp;&nbsp;<a onclick="Javascript: return AddOptionCancle('+counter+','+prod_id+')" style="color:#17649E; cursor:pointer;" >'+CANCEL+'</a></td>';
		OptHtml += '</tr>';
	
	$(OptHtml).insertAfter("#PrDOptVal tr:last");
	
	counter++; 
}

function AddOptionCancle(no,pid) 
{
	$("#add_demo_"+no).remove();
}

function AddOption(no,pid)
{
	var title = $("#PDO_add_"+no).val();
	
	if(title == '' )
	{
		alert(ERR_PRODUCT_OPTION_TITLE);
		return false;
	}
	
	$.ajax({
			type:'POST',
			url:siteurl+'user/products/addoption',
			data: "product_id="+pid+"&option_title="+title,
			success:function(data)	{ 
			
				$("#add_demo_"+no).remove();
				var success_array = data.split("///");
				if( success_array[1] > 0 ) {
					var OptHtml = '';
						OptHtml += '<tr id="PD_Row_'+success_array[1]+'">';
						OptHtml += '<td width="25%" id="PD_Option_'+success_array[1]+'" valign="top" >'+title+'</td>';
						OptHtml += '<td width="55%" id="PD_Option_Detail_'+success_array[1]+'" >'
						OptHtml += '<table width="100%" id="PD_Option_Table_'+success_array[1]+'">';
						OptHtml += '</table>';
						OptHtml += '</td>';
						OptHtml += '</tr>';
						OptHtml += '<tr><td></td><td>';
						OptHtml += '<input type="button" onclick="AddMoreOptionValue('+success_array[1]+')"  value="'+PRODUCT_ADD_OPTION+'"> ';
						OptHtml += '<input type="button" onclick="DeleteProductOption('+success_array[1]+')"  value="'+PRODUCT_DELETE_OPTION+'">';
						OptHtml += '</td></tr>';
						OptHtml += '<script>$(function() { $( "input:button" ).button(); });</script>';
						
					$(OptHtml).insertAfter("#PrDOptVal tr:last");
					$("#No_Option").remove();
					
				} else {
					alert( success_array[2] );	
				}
			}
		});
}


///////////////////////////	For Adding Product Options Value  /////////////////////////

function AddMoreOptionValue(id)
{
	$("#product_option_form_add .productoptval").val('');
	ValidateAddProductOptionFrom(id);
	$( "#dialog-product-option-add" ).dialog({
		resizable: false,
		height:'auto',
		width:600,
		modal: true,
		buttons: {
			Add: function() {
				$("#product_option_form_add").submit();
			},
			Cancel: function() {
				$(".productoptval").val('');
				$( this ).dialog( "close" );
			}
		}
	});	
	
	$("#dialog-product-option-add").next().find(".ui-dialog-buttonset button:first-child span").html(ADD);
	$("#dialog-product-option-add").next().find(".ui-dialog-buttonset button:nth-child(2) span").html(CANCEL);
}

function ValidateAddProductOptionFrom(Option_Id)
{
	product_option_id = Option_Id;
	$("#product_option_form_add").validate({
							
			rules: {
						add_prod_option_name: {  required: true  },		
						add_prod_option_weight: { number: true },
						add_prod_option_price: {  number: true },
						add_prod_option_quantity: { number: true }
				   },
		   
			messages:{
						add_prod_option_name: {  required: ERR_PRODUCT_OPTION_VALUE_NAME  },		
						add_prod_option_weight: { number: ERR_PRODUCT_OPTION_VALUE_INVALID_WEIGHT },
						add_prod_option_price: { number: ERR_PRODUCT_OPTION_VALUE_INVALID_PRICE },
						add_prod_option_quantity: { number: ERR_PRODUCT_OPTION_VALUE_INVALID_QUANTITY  }
					 },
					 
			errorPlacement: function(error, element) 
			{ 
				error.appendTo( element.parent().next() ); 
			},
			submitHandler: function() {  
				AddProductOptionValue();
				$( "#dialog-product-option-add" ).dialog( "close" );
			}
	});	
}

function AddProductOptionValue(id)
{
	var OptValName 		= $("#product_option_form_add #add_prod_option_name").val();
	var OptValCode 		= $("#product_option_form_add #add_prod_option_code").val();
	var OptValWeight 	= $("#product_option_form_add #add_prod_option_weight").val();
	var OptValWeightUid	= $("#product_option_form_add #add_prod_option_weight_unit_id").val();
	var OptValPrice		= $("#product_option_form_add #add_prod_option_price").val();
	var OptValQuantity	= $("#product_option_form_add #add_prod_option_quantity").val();
	
	var ValueString = '';
		ValueString += 'product_options_id='+product_option_id+'&option_name='+OptValName+'&option_code='+OptValCode+'&option_weight='+OptValWeight+'&option_weight_unit_id='+OptValWeightUid+'&option_price='+OptValPrice+'&option_quantity='+OptValQuantity;
	
	$.ajax({
			type:'POST',
			url:siteurl+'user/products/addoptionvalue',
			data: ValueString,
			dataType: 'json',
			success:function(retArray)	{
				
				if( retArray["error"] == undefined ) {
				
					var HtmlStr = $("#PD_Option_Table_"+retArray["product_options_id"]).html();
					
					var NewRow =  "<tr id='POD_Row_"+retArray["product_options_detail_id"]+"' >";
						NewRow += "<td width='35%' id='POD_Name_"+retArray["product_options_detail_id"]+"' >";
						NewRow += retArray["option_name"];
						NewRow += "</td>";
						NewRow += "<td width='35%' id='POD_Code_"+retArray["product_options_detail_id"]+"' >";
						NewRow += retArray["option_code"];
						NewRow += "</td>";
						NewRow += "<td width='25%'>";
						NewRow += "<a  onclick='EditProductOption("+retArray["product_options_detail_id"]+")' style='color:#17649E; cursor:pointer;' >";
						NewRow += EDIT;
						NewRow += "</a>&nbsp;&nbsp;/&nbsp;&nbsp;";
						NewRow += "<a  style='color:#17649E; cursor:pointer;' onclick='DeleteProductOptionValue("+retArray["product_options_detail_id"]+")' >";
						NewRow += DELETE;
						NewRow += "</a></td></tr>";
						
					$("#PD_Option_Table_"+retArray["product_options_id"]).html(HtmlStr+NewRow);
					
				} else {
					alert( retArray["error"] );
				}
					
			}
		});
}

///////////////////////////	For Deleting Product Options Value  /////////////////////////

function DeleteProductOptionValue(id)
{
	$( "#dialog-confirm-delete-pod" ).dialog({
		resizable: false,
		height:'auto',
		modal: true,
		buttons: {
			"Delete": function() {
				
				$.ajax({
					type:'POST',
					url:siteurl+'user/products/deleteoptionval',
					data: "product_options_detail_id="+id,
					success:function(data)	{ 
					
						alert(data);
						$("#POD_Row_"+id).remove(); 
					}
					
				});
				
				$( this ).dialog( "close" );
				
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});	
	
	$("#dialog-confirm-delete-pod").next().find(".ui-dialog-buttonset button:first-child span").html(DELETE);
	$("#dialog-confirm-delete-pod").next().find(".ui-dialog-buttonset button:nth-child(2) span").html(CANCEL);
}
