function split( val ) {return val.split( /[\,\+]\s*/ );}
function extractLast( term ) {return split( term ).pop();}

function autocomplete(id, list, splitter) {
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
				return false;
			}
		});
}

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
			}, 500);
			
			$("#connection-details").submit(saveDetails);
		},
		"error": function(data){loaded(); showMessage(data)},
	});
	return false;
}
function closePopup(e) {
	e.stopPropagation();
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

function init() {
	$("#message").focus();
	
	autocomplete("#message", people, ',');
	autocomplete("#met", people, '+');
	autocomplete("#phone", people, ',');
	autocomplete("#chat", people, ',');	
	
	$(".popup").click(openPopup);
	$("#popup-close").click(closePopup);
}