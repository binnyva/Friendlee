function init() {
	$("#message").focus();
	$(".uncontacted-table").tablesorter({
		"textExtraction": {1:function(node) {
			if(!node) return 0;
			return $(node).attr("data");
		}}
	});

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