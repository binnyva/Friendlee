function init() {
	$("#message").focus();

	autocomplete("#message", people, ',');
	autocomplete("#met", people, '+');
	autocomplete("#phone", people, ',');
	autocomplete("#chat", people, ',');	
}