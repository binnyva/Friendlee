$=jQuery.noConflict();
//Framework Specific
function showMessage(data) {
	console.log(data);
	var type = 'error';
	if(data.success) var type = 'success';
	
	$("#"+type+"-message").html(stripSlashes(data[type]));
	$("#"+type+"-message").fadeIn(500);
	
	window.setTimeout(function() {
		$("#"+type+"-message").fadeOut(500);
	}, 3000); // Amount of time message should be shown.
	
	return type;
}
function stripSlashes(text) {
	if(!text) return "";
	return text.replace(/\\([\'\"])/,"$1");
}


function ajaxError() {
	alert("Error communicating with server. Please try again");
}
function loading() {
	$("#loading").show();
}
function loaded() {
	$("#loading").hide();
}
function makeCalender() {
	console.log("Make");
	calendar.opt['display_element'] = this.id;
	calendar.opt['input'] = "date";
	calendar.showCalendar();
}

function setDate(year, month, day) {
	document.getElementById(calendar.opt["input"]).value = year + "-" + month + "-" + day;
	calendar.hideCalendar();
	document.getElementById("change-day-form").submit();
}

function siteInit() {
	$("a.confirm").click(function(e) { //If a link has a confirm class, confrm the action
		var action = (this.title) ? this.title : "do this";
		action = action.substr(0,1).toLowerCase() + action.substr(1); //Lowercase the first char.
		
		if(!confirm("Are you sure you want to " + action + "?")) {
			e.stopPropagation();
		}
	});
	
	$(".auto-show").click(function() {
		var id = $(this).attr("id");
		var area_id = id.replace(/^show\-/, "") + "-area";
		$("#" + area_id).show();
	});
	$(".auto-hide").click(function() {
		var id = $(this).attr("id");
		var area_id = id.replace(/^hide\-/, "") + "-area";
		$("#" + area_id).hide();
	});
	
	if(document.getElementById("change-day")) calendar.set("change-day", {"onclick": makeCalender, "onDateSelect":setDate});
	if(window.init && typeof window.init == "function") init(); //If there is a function called init(), call it on load
}
jQuery(window).load(siteInit);