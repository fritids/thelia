var obj = null;

function checkHover() {
	if (obj) {
		obj.find('ul').fadeOut('fast');	
	} //if
} //checkHover

$(document).ready(function() {
	$('#Nav > li').hover(function() {
		if (obj) {
			obj.find('ul').fadeOut('fast');
			obj = null;
		} //if
		
		$(this).find('ul').fadeIn('fast');
	}, function() {
		obj = $(this);
		setTimeout(
			"checkHover()",
			0); // si vous souhaitez retarder la disparition, c'est ici
	});
	
	$('#Nav2 > li').hover(function() {
		if (obj) {
			obj.find('ul').fadeOut('fast');
			obj = null;
		} //if
		
		$(this).find('ul').fadeIn('fast');
	}, function() {
		obj = $(this);
		setTimeout(
			"checkHover()",
			0); // si vous souhaitez retarder la disparition, c'est ici
	});
});