function cq_init() {
	$(".contact-queue").click(cq_handleClick);
	$(".demote").click(cq_handleClick);
}

function cq_handleClick(e) {
	e.stopPropagation();

	var that = $(this);
	var url = that.attr("data-url");
	loading();
	
	$.ajax({
		"url": url,
		"dataType": 'json',
		"success": function(data){
			loaded();
			if(that.hasClass("contact-queue")) {
				that.val("Qed").attr("disabled", true);
			}
		},
		"error": function(data){loaded(); alert("Call Error: "+ data.error);},
	});
	return false;
}

$(cq_init);

