$=jQuery.noConflict();
//Framework Specific
function showMessage(data) {
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
	calendar.opt['display_element'] = this.id;
	calendar.opt['input'] = "date";
	calendar.showCalendar();
}

function setDate(year, month, day) {
	document.getElementById(calendar.opt["input"]).value = year + "-" + month + "-" + day;
	calendar.hideCalendar();
	document.getElementById("change-day-form").submit();
}

/// Autocomplete Code
function split( val ) {return val.split( /[\,\+]\s*/ );}
function extractLast( term ) {return split( term ).pop();}

function autocomplete(id, list, splitter) {
	if(!splitter) splitter = '';
	$(id)
		// don't navigate away from the field on tab when selecting an item
		.bind( "keydown", function( event ) {
			if ( event.keyCode === $.ui.keyCode.TAB &&
			$( this ).data( "ui-autocomplete" ).menu.active ) {
				event.preventDefault();
			}
		})
		.autocomplete({
			minLength: 3,
			autoFocus: true,
			source: function( request, response ) {
				// This will remove the stuff that already appeared in the selection earlier.
				var terms = split( request.term );
				var current_input = extractLast( request.term );
				
				var hash_terms = {};
				terms.forEach(function(val) {
					hash_terms[val] = true;
				});
				
				var final_list = list.filter(function(val) {
					if(!hash_terms[val]) {
						var regexp = new RegExp("^" + current_input,"i"); // The typed text should match the beginning of the name - not anywhere. And Case insensitive.
						if(val.match(regexp)) return val;
					}
				});
				response(final_list);
			},
			focus: function() {	// prevent value inserted on focus
				return false;
			},
			select: function( event, ui ) {
				var value = this.value;
				var terms = split( value );
				var current_input = terms.pop(); // get the current input
				regexp = new RegExp(current_input + '$'); // And remove the current input.
				result = value.replace(regexp , '');
				
				this.value = result + ui.item.value + splitter + " ";

				if(window.searchPerson && typeof window.searchPerson == "function") searchPerson(ui.item.value, id);
				return false;
			}
		});
}

function searchPerson(name, ele_id) { // Called when a name is selected by autocomplete...
	if(ele_id == "#search") window.location.href = $("#search-area").attr("action") + "?nickname="+name;
}


/// Needed for popups - specifically, the interaction edit popup.
function openPopup(e) {
	e.stopPropagation();
	var url = this.href;
	
	loading();
	$.ajax({
		"url": url,
		"dataType": 'html',
		"success": function(data){
			loaded();
			$("#popup-area").html(data);
			$("#popup-area-holder").show();
			
			// Put in a small delay before calling the library function - or the element will not be there.
			window.setTimeout(function() {
				if($("#people")) autocomplete("#people", people, ',');
				
				$(document).bind( "keydown", escapeKey);
			}, 500);
			
			$("#connection-details").submit(saveDetails);
		},
		"error": function(data){loaded(); showMessage(data)},
	});
	return false;
}

function escapeKey(e) {
	if ( e.keyCode === $.ui.keyCode.ESCAPE ) closePopup(e);
}

function closePopup(e) {
	e.stopPropagation();
	$(document).unbind("keydown", escapeKey);
	$("#popup-area-holder").hide();
}


function saveDetails(e) {
	e.stopPropagation();
	var url = $(this).attr("action");
	var data = $(this).serialize() + "&action=Save";
	
	loading();
	$.ajax({
		"url": url,
		"dataType": 'json',
		"data": data,
		"success": function(data){
			loaded();
			closePopup(e);
			showMessage(data);
		},
		"error": function(data){loaded(); showMessage(data);},
	});
	
	return false;
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

	$(".popup").click(openPopup);
	$("#popup-close").click(closePopup);

	if(typeof people != "undefined") autocomplete("#search", people);
	
	if(document.getElementById("change-day")) calendar.set("change-day", {"onclick": makeCalender, "onDateSelect":setDate});
	if(window.init && typeof window.init == "function") init(); //If there is a function called init(), call it on load
}
jQuery(window).load(siteInit);