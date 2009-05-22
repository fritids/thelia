var obj = null;

function checkHover() {
	if (obj) {
		obj.find('ul').fadeOut('fast');	
	} //if
} //checkHover

$(document).ready(function() {
	$('#contenuPanier > li').hover(function() {
		if (obj) {
			obj.find('ul').slideUp('fast');
			obj = null;
		} //if
		
		$(this).find('ul').slideDown('fast');
	}, function() {
		obj = $(this);
		setTimeout(
			"checkHover()",
			0); // si vous souhaitez retarder la disparition, c'est ici
	});
});