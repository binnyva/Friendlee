 function ca_init() {
 	$(".contact-attempt").click(ca_recordAttempt);
 }

 function ca_recordAttempt(e) {
 	e.stopPropagation();

	var url = this.href;
	loading();
	$.ajax({
		"url": url,
		"dataType": 'json',
		"success": function(data){loaded(); ca_done(data); },
		"error": function(data){loaded(); alert("Call Error: "+ data.error);},
	});
	return false;

 }

 function ca_done(data) {
 	console.log(data);
 	$("#attempts-"+data['person_id']).html(data['value']);
 }

 $(ca_init);