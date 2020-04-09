function init() {
	$("#message").focus();

	autocomplete("#message", people_with_points, ',');
	autocomplete("#met", people_with_points, '+');
	autocomplete("#phone", people_with_points, ',');
	autocomplete("#chat", people_with_points, ',');	
	autocomplete("#email", people_with_points, ',');
	autocomplete("#other", people_with_points, ',');
}

function ajaxify_customHandler() {
	closePopup();
}