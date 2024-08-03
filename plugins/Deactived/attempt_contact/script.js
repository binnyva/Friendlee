function ca_init() {
	$(".contact-attempt").click(ca_handleClick);
	$(".demote").click(ca_handleClick);
}

function ca_handleClick(e) {
	e.stopPropagation();

	var url = this.href;
	loading();
	var that = $(this);
	$.ajax({
		"url": url,
		"dataType": 'json',
		"success": function(data){loaded();
			if(that.hasClass("contact-attempt")) {
				ca_attepmt_done(data); 
			} else if(that.hasClass("demote")) {
				ca_demote_done(that);
			}
		},
		"error": function(data){loaded(); alert("Call Error: "+ data.error);},
	});
	return false;
}

function ca_attepmt_done(data) {
	$("#attempts-"+data['person_id']).html(data['value']);
}

function ca_demote_done(ele) {
	ele.parents("tr").remove(); // Remove the row in the uncontacted table. 
}

$(ca_init);