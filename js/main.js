$(document).ready(function() {

	$('#sidebar').affix({
		offset : {
			top : 75
		}
	});

	var $body = $(document.body);
	var navHeight = $('.top-nav').outerHeight(true) + 10;

	$body.scrollspy({
		target : '#leftCol',
		offset : navHeight
	});

});
