jQuery(function(e) {
	var windowWidth = jQuery(window).width();
	if(windowWidth > 1500){
    	jQuery('.app-container').removeClass('closed-sidebar-mobile');
    	jQuery('.app-container').removeClass('closed-sidebar');
	}else{
    	jQuery('.app-container').addClass('closed-sidebar-mobile');
    	jQuery('.app-container').addClass('closed-sidebar');
	}
});
 