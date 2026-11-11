$(document).ready(function(){	
	var menuIndex=0;
	
	//hide the content but only show the initial FACIAL content
	hide_service_items();
	$(".facial").show();
	
	
	$('#service_list li').bind('mouseover',function(){
		if(!$(this).hasClass('active')){
			$(this).children('.service_list_top').css('background-image','url(images/service/overlay_top.png)');
			$(this).children('.service_list_mid').css('background-image','url(images/service/overlay_mid.png)');
			$(this).children('.service_list_btm').css('background-image','url(images/service/overlay_btm.png)');
		}
	});
	
	$('#service_list li').bind('mouseout',function(){
		if(!$(this).hasClass('active')){
			$(this).children('.service_list_top').css('background-image','none');
			$(this).children('.service_list_mid').css('background-image','none');
			$(this).children('.service_list_btm').css('background-image','none');
		}
	});
	
	$('#service_list li').bind('click',function(){
		if(!$(this).hasClass('active')){
			$('#service_list li').removeClass('active');
			
			var service_item = $(this).attr('class');
			
			$(this).addClass('active');
			
			$('#service_list li').children('.service_list_top').css('background-image','none');
			$('#service_list li').children('.service_list_mid').css('background-image','none');
			$('#service_list li').children('.service_list_btm').css('background-image','none');
			
			$(this).children('.service_list_top').css('background-image','url(images/service/overlay_top.png)');
			$(this).children('.service_list_mid').css('background-image','url(images/service/overlay_mid.png)');
			$(this).children('.service_list_btm').css('background-image','url(images/service/overlay_btm.png)');
			
			//change the content
			hide_service_items();
			$('.service_content' + '.' + service_item).show();
		}
		
	});
	
	$('.service1').children('.service_list_top').css('background-image','url(images/service/overlay_top.png)');
	$('.service1').children('.service_list_mid').css('background-image','url(images/service/overlay_mid.png)');
	$('.service1').children('.service_list_btm').css('background-image','url(images/service/overlay_btm.png)');
});

function hide_service_items() {
	$('.service_content').hide();
}
