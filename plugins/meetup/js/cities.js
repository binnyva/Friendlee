function init() {
	$( ".friend-city" ).sortable({
		revert: true,
		connectWith: [".friend-city"]
	});
	$( "ul, li" ).disableSelection();
	
	$( "#city-area-0" ).draggable();
}

function saveOrder() {
	var config = [];
	$(".friend-city").each(function() {
		var order = [];
		$("li", this).each(function(i) {
			order.push(this.id.replace(/\D/g,""));
		});
		var level_id = this.id.replace(/\D/g,"");
		
		if(order.length) config.push("city_config["+level_id+"]=" + order.join(","));
	});
	
	loading();
	$.ajax({
		"url": "ajax/save_city_config.php",
		"data": config.join("&"),
//		"type": "POST",
		"dataType": 'json',
		"success": function(data){loaded(); showMessage(data);},
		"error": function(data){loaded(); showMessage(data);},
	});
}

