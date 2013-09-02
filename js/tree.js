function init() {
	$( ".friend-level" ).sortable({
		revert: true,
		connectWith: [".friend-level"]
	});
	$( "ul, li" ).disableSelection();
}

function saveOrder() {
	var config = [];
	$(".friend-level").each(function() {
		var order = [];
		$("li", this).each(function(i) {
			order.push(this.id.replace(/\D/g,""));
		});
		var level_id = this.id.replace(/\D/g,"");
		
		if(order.length) config.push("level_config["+level_id+"]=" + order.join(","));
	});
	
	loading();
	$.ajax({
		"url": "ajax/save_level_config.php",
		"data": config.join("&"),
		"type": "POST",
		"dataType": 'json',
		"success": function(data){loaded(); showMessage(data);},
		"error": function(data){loaded(); showMessage(data);},
	});
}

