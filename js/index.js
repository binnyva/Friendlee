function init() {
	$("#message").focus();
	$(".uncontacted-table").tablesorter({
		"textExtraction": {1:function(node) {
			if(!node) return 0;
			return $(node).attr("data");
		}}
	});

	autocomplete("#message", people, ',')
	autocomplete("#met", people, '+');
	autocomplete("#phone", people, ',');
	autocomplete("#chat", people, ',');	
	autocomplete("#email", people, ',');
	autocomplete("#other", people, ',');
}
