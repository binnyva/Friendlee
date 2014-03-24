function init() {
	$( ".friend-gender" ).sortable({
		revert: true,
		connectWith: [".friend-gender"]
	});
	$( "ul, li" ).disableSelection();
	
	$( "#gender-area-0" ).draggable();
}

function saveOrder() {
	var config = [];
	$(".friend-gender").each(function() {
		var order = [];
		$("li", this).each(function(i) {
			order.push(this.id.replace(/\D/g,""));
		});
		var level_id = this.id.replace(/gender\-/g,"");
		
		if(order.length) config.push("gender_config["+level_id+"]=" + order.join(","));
	});
	
	loading();
	$.ajax({
		"url": "ajax/save_gender_config.php",
		"data": config.join("&"),
//		"type": "POST",
		"dataType": 'json',
		"success": function(data){loaded(); showMessage(data);},
		"error": function(data){loaded(); showMessage(data);},
	});
}

