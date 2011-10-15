// JavaScript Document
function PerformSearch()
{
	
	var searchtext = $('#q').val();
	var catid = $('#catid').val();
	  
	 
 	if(searchtext == 'Search the Mall'){
		alert('Please Enter Proper Text');
 		return false;
	}else{
		 
		$('#frmSearch').attr('action',''+site_fb_url+'product/search?q='+searchtext+'&search=1&cid='+catid+'');
		$('#frmSearch').submit();
		//$('#frmcatproduct').submit();
		$('#loader').hide();
		$('#loader').removeClass('ui-widget-loading');

	}
}