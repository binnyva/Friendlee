 
function init() {
	$("#more-info-last-contact").click(function() {
		$("#more-info").toggle();
	});

	$("#show-more-options").click(function() {
		$("#more-options-area").show();
	});

	$("#toggle-advanced-options").click(function() {
		$("#advanced-options").show();
		$("#toggle-advanced-options").hide();
	});
}

function closeOptions() {
	$("#more-options-area").hide();	
}