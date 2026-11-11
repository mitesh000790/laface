$(document).ready(function(){
	$(function(){
			//Active Navigation
				$page = jQuery.url.attr("file");
				if(!$page) {
					$page = 'index.php';
				}
				jQuery('#navi a').each(function(){
					$href = jQuery(this).attr('href');
					var src = $(this).children('img').attr('alt');
					if ( ($href == $page) || ($href == '') ) {
						$(this).children('img').attr('src','images/navi/'+src+'_m.png');
						$(this).children('img').addClass('active');
					} else {
						$(this).children('img').attr('src','images/navi/'+src+'.png');
						$(this).children('img').removeClass('active');
				}
				
			}); 
		});
		
	$('#navi li img').bind('mouseover',function(){
		var src = $(this).attr('alt');
		
			$(this).attr('src','images/navi/'+src+'_m.png');

	});
	
	$('#navi li img').bind('mouseout',function(){
		var src = $(this).attr('alt');
		if(!$(this).hasClass('active')){
			$(this).attr('src','images/navi/'+src+'.png');
		}
	});
		
});
