// JavaScript Document


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
						start_sales: { required: true }
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
						start_sales: { required: ERR_PRODUCT_START_SALE }
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
			action: siteurl+'admin/image/index/?id='+product_id,
			debug: true,
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
			url:siteurl+'admin/products/deleteimage',
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

function EditProductOption(id)
{
	if(id > 0) {
			
		PD_Option[id] = $("#PD_Option_"+id).html();
		PD_Option_Detail[id] = jQuery.trim($("#PD_Option_Detail_"+id).html());
		PD_Option_Action[id] = $("#PD_Option_Action_"+id).html();
		
		$("#PD_Option_"+id).html('<input class = "Field150" type = "text" name = "PDO_'+id+'" id = "PDO_'+id+'" value = "'+PD_Option[id]+'" >');
		$("#PD_Option_Detail_"+id).html('<input type = "text" name = "PDOD_'+id+'" id = "PDOD_'+id+'" value = "'+PD_Option_Detail[id]+'" class = "Field300" />');
		$("#PD_Option_Action_"+id).html('<a style="color:#17649E; cursor:pointer;" onclick="UpdateProductOption('+id+')" >'+UPDATE+'</a>');
		
	}
}

function UpdateProductOption(id)
{
	if(PD_Option_Action[id] != '') {
		
		$.ajax({
			type:'POST',
			url:siteurl+'admin/products/updateoption',
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
					url:siteurl+'admin/products/deleteoption',
					data: "option_id="+id,
					success:function(data)	{ $("#PD_Row_"+id).remove(); }
				});
				
				$( this ).dialog( "close" );
				
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});	
}

var counter = 1;

function AddProductOption(prod_id)
{
	var row_id = $("#last_product_option_id").val();
	
	var OptHtml = '';
		OptHtml += '<tr id="add_demo_'+counter+'">';
		OptHtml += '<td width="25%"><input class = "Field150" type = "text" name = "PDO_add_'+counter+'" id = "PDO_add_'+counter+'" ></td>';
		OptHtml += '<td width="55%"><input type = "text" name = "PDOD_add_'+counter+'" id = "PDOD_add_'+counter+'"  class = "Field300" /></td>';
		OptHtml += '<td width="20%"><a  onclick="Javascript: return AddOption('+counter+','+prod_id+')" style="color:#17649E; cursor:pointer;" >'+ADD+'</a></td>';
		OptHtml += '</tr>';
	
	if(row_id != '') {
		$(OptHtml).insertAfter("#PD_Row_"+row_id);
 	} else {
		$("#No_Option").children().html('');
		$("#No_Option").html(OptHtml);
	}
	
	counter++; 
}

function AddOption(no,pid)
{
	var title = $("#PDO_add_"+no).val();
	var option = $("#PDOD_add_"+no).val();
	
	if(title == '' )
	{
		alert(ERR_PRODUCT_OPTION_TITLE);
		return false;
	}
	if(option == '')
	{
		alert(ERR_PRODUCT_OPTION_DETAIL);	
		return false;
	}
	
	$.ajax({
			type:'POST',
			url:siteurl+'admin/products/addoption',
			data: "product_id="+pid+"&option_title="+title+"&option_detail="+option,
			success:function(data)	{ 
			
				$("#add_demo_"+no).remove();
				var success_array = data.split("///");
				
				var action = '<a  onclick="EditProductOption('+success_array[1]+')" style="color:#17649E; cursor:pointer;" >'+EDIT+'</a>&nbsp;/&nbsp;<a style="color:#17649E; cursor:pointer;" onclick="DeleteProductOption('+success_array[1]+')" >'+DELETE+'</a>';
				
				var OptHtml = '';
					OptHtml += '<tr id="PD_Row_'+success_array[1]+'">';
					OptHtml += '<td width="25%" id="PD_Option_'+success_array[1]+'" >'+title+'</td>';
					OptHtml += '<td width="55%" id="PD_Option_Detail_'+success_array[1]+'" >'+option+'</td>';
					OptHtml += '<td width="20%" id="PD_Option_Action_'+success_array[1]+'" >'+action+'</td>';
					OptHtml += '</tr>';
				var row_id = $("#last_product_option_id").val();
				$("#last_product_option_id").val(success_array[1]);
				
				if(row_id != '') {
					$(OptHtml).insertAfter("#PD_Row_"+row_id);
				} else {
					$("#No_Option").html(OptHtml);
				}
			}
		});
	
}