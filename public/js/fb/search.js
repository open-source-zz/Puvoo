// JavaScript Document
function PerformSearch()
{
	
	var searchtext = $('#q').val();
	var catid = $('#catid').val();
	  
	 
 	if(searchtext == 'Search the Mall'){
		alert('Please Enter Proper Text');
 		return false;
	}else{
		 
		$('#frmSearch').attr('action',''+site_url+'product/search?q='+searchtext+'&Search=1&cid='+catid+'');
		$('#frmSearch').submit();
		//$('#frmcatproduct').submit();
		
	}
}