function init() {
	$(".uncontacted-table").tablesorter({
		"textExtraction": {1:function(node) {
			if(!node) return 0;
			return $(node).attr("data");
		}}
	});

}