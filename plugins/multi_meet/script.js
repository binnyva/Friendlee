$(function () {
	peopleFilter();
	$("#multi-meet-form").submit(insertFilteredPeople);
	$("#multi-meet").on("click", showMultiMeet);
	$("#multi-meet-closer").on("click", closeMultiMeet);
});

function showMultiMeet() {
	$("#multi-meet-area-holder").show();
	$("#multi-meet-filter").focus();
}

function closeMultiMeet() {
	$("#multi-meet-area-holder").hide();
}

function peopleFilter() {
	$(".filter-list").on('keyup', function(e) {
		var filter = this.value.toLowerCase();
		var target = $(this).attr("target-field");

		$("#"+target+" li").each(function() {
			var ele = $(this);
			var lab = ele.children("label");

			if(lab.text().toLowerCase().search(filter) == -1) {
				ele.hide();
			} else {
				ele.show();
			}
		});
	});
}

function insertFilteredPeople(e) {
	var people = $(".people-filter").map(function() {
		if(this.checked) return this.value;
    }).get().join("+");

    var existing_mets = $("#met").val().trim();
    if(existing_mets) people = existing_mets + ", " + people;
    $("#met").val(people);

    closeMultiMeet();

	e.stopPropagation();
	return false;
}
