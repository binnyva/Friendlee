function init() {
	$(".photo-sent").click(removePerson);
}

function removePerson (e) {
	var links = $(this.parentNode).children("a");
	if(links.length <= 1) {
		$(this.parentNode.parentNode).remove(); // Remove the row - there is no more rewards
	} else {
		$(this).remove();
	}
}