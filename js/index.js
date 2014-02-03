function showTab() {
	var id = $(this).attr("id").replace(/\D/g,"");
	$(".tab-content").removeClass("active-tab-content");
	$("#uncontacted-people-tab-content-"+id).addClass("active-tab-content");

	$(".tab").removeClass("active-tab");
	$(this).addClass("active-tab");
}

function init() {
	$("#message").focus();

	$(".tab").click(showTab)
	
	autocomplete("#message", people, ',');
	autocomplete("#met", people, '+');
	autocomplete("#phone", people, ',');
	autocomplete("#chat", people, ',');	
}