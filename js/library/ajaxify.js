(function($){
$.ajaxify = {};

$.ajaxify.init = function() {
	$("a.ajaxify").click($.ajaxify.handleClick);
	$("form.ajaxify").submit($.ajaxify.handleSubmit);
};

$.ajaxify.handleRequest = function(ele, data) {
	if(!data) return;

	if($(ele).hasClass("ajaxify-custom-handler")) {
		if(data.success) {
			if(ajaxify_customHandler) {
				ajaxify_customHandler(data, ele);
			}
		}
	} else if($(ele).hasClass("ajaxify-replace")) {
		if(data.success) {
			var new_node = document.createElement("span");
			new_node.innerHTML = data.message;
			ele.parentNode.replaceChild(new_node, ele);
		} else {
			alert("Attempt failed: " + data.message);
		}
		
	} else if($(ele).hasClass("ajaxify-remove-parent")) {
		if(data.success) {
			ele.parentNode.parentNode.removeChild(ele.parentNode);
		} else {
			alert("Attempt failed: " + data.message);
		}
	}
}

$.ajaxify.handleClick = function(e) {
	e.stopPropagation();
	
	if($(this).hasClass("ajaxify-confirm")) {
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		
		if(!confirm("Are you sure you want to " + action + "?")) {
			return false;
		}
	}
	
	var url = this.href;
	var anchor = this;
	loading();
	$.ajax({
		"url": url,
		"dataType": 'json',
		"success": function(data){loaded(); $.ajaxify.handleRequest(anchor, data);},
		"error": function(data){loaded(); alert("Call Error: "+ data.error);},
	});
	return false;
}

$.ajaxify.handleSubmit = function(e) {
	e.stopPropagation();
	e.preventDefault();

	var form = $(this);
	var url = form.attr("action");
	loading();
	$.ajax({
		"url": url,
		"data": form.serialize() + "&action=Save&ajaxify=1",
		"success":  function(data){loaded(); $.ajaxify.handleRequest(form, data);},
	});
	return false;
}


})(jQuery);
jQuery(document).ready(jQuery.ajaxify.init);
