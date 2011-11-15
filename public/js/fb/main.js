/*
 * Image preview script 
 * powered by jQuery (http://www.jquery.com)
 * 
 * written by Alen Grakalic (http://cssglobe.com)
 * 
 * for more info visit http://cssglobe.com/post/1695/easiest-tooltip-and-image-preview-using-jquery
 *
 */
 
this.imagePreview = function(){	
	/* CONFIG */
	
	var wH = $(window).height();
	var wW = $(window).width();
	
	
	var mytop = 0;
	
	var myleft = 0;
	
	xOffset = 10;
	yOffset = 30;
		
	// these 2 variable determine popup's distance from the cursor
	// you might want to adjust to get the right result
		
	/* END CONFIG */
	$("a.preview").hover(function(e){ 
		
		var vScrollPosition = $(document).scrollTop();
		var windowH = $(document).height();
		var mytop1 = 0;
		
		this.t = this.title;
		this.title = "";	
		var c = (this.t != "") ? "<br/>" + this.t : "";
		$("body").append("<p id='preview'><img src='"+ $(this).attr("url") +"' alt='Image preview' />"+ c +"</p>");								 		
		mytop1 = vScrollPosition + $("#preview").height();
		
		if( e.pageY < mytop1 )
		{
			mytop = e.pageY + yOffset ;	
		}
		else
		{
			mytop =  e.pageY - $("#preview").height();	
		}
		
		if($("#preview").width() + e.pageX > wW)
		{
			myleft = e.pageX - $("#preview").width();	
		}
		else
		{
			myleft = e.pageX;	
		}
		
		
		$("#preview")
			.css("top",(mytop - xOffset) + "px")
			.css("left",(myleft + yOffset) + "px")
			.fadeIn("fast");						
    },
	function(){
		this.title = this.t;	
		$("#preview").remove();
    });	
	$("a.preview").mousemove(function(e){ 
		
		var vScrollPosition = $(document).scrollTop();
		var windowH = $(document).height();
		var mytop1 = 0;
		
		mytop1 = vScrollPosition + $("#preview").height();
		
		if( e.pageY < mytop1 )
		{
			mytop = e.pageY;	
		}
		else
		{
			mytop =  e.pageY - $("#preview").height();	
		}
		
		if($("#preview").width() + e.pageX > wW)
		{
			myleft = e.pageX - $("#preview").width();	
		}
		else
		{
			myleft = e.pageX;	
		}							  
									  
									  
		$("#preview")
			.css("top",(mytop - yOffset) + "px")
			.css("left",(myleft + xOffset) + "px");
	});			
};


// starting the script on page load
$(document).ready(function(){
	imagePreview();
});