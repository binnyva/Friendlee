function init() {
	$(".photo-sent").click(removePerson);
}

function removePerson (e) {
	$(this.parentNode.parentNode).remove();
}