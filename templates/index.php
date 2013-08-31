<link href="js/tag-it/css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="js/tag-it/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<link href="js/library/jquery-ui/css/jquery-ui.css" rel="stylesheet" type="text/css">

<script>
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
			minLength: 1,
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
						var regexp = new RegExp("^" + current_input); // The typed text should match the beginning of the name - not anywhere.
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

function init() {
	$("#message").focus();
	
	var people = <?php echo json_encode($all_people); ?>;
	autocomplete("#message", people, ',');
	autocomplete("#met", people, '+');
	autocomplete("#phone", people, ',');
	autocomplete("#chat", people, ',');
	
};
</script>

<form action="" method="get" id="change-day-form"><input type="hidden" name="date" id="date" value="" /></form>

<div id="date-changer">
<a class="previous previous-day with-icon" href="?date=<?php echo date('Y-m-d', strtotime($date) - (60*60*24)); ?>">Previous Day(<?php echo date('dS M', strtotime($date) - (60*60*24)); ?>)</a>
<h3 class="curdate">Data for <?php echo date('dS M', strtotime($date)); ?> <a href="#" id="change-day" class="icon calendar">Change</a></h3>
<div class="next-holder"><?php if(date('Y-m-d', strtotime($date) + (60*60*24)) <= date('Y-m-d')) { ?><a class="next next-day  with-icon" href="?date=<?php echo date('Y-m-d', strtotime($date) + (60*60*24)); ?>">Next Day(<?php echo date('dS M', strtotime($date) + (60*60*24)); ?>)</a><?php } ?></div>
</div>
<br />

<form action="" method="post">
<input type="hidden" name="date" value="<?php echo $date ?>" />

<?php 
showBox('message', 'Whatsapp/Text');
showBox('met');
showBox('chat', 'Chat');
showBox('phone');
?>


<br />
<input type="submit" name="action" value="Save" />
</form>

<?php
function showBox($name, $title='') {
	if(!$title) $title = format($name);
	?>
<fieldset>
<legend><?php echo $title; ?></legend>
<input type="text" class="data" name="<?php echo $name ?>" id="<?php echo $name ?>" value="" />
<ul id="<?php echo $name ?>_display"></ul>

<?php showConnections($name); ?>
</fieldset>
<?php
}

function showConnections($name) {
	global $sql, $people, $date;
	$all_connections = $sql->getAll("SELECT id FROM Connection WHERE user_id=$_SESSION[user_id] AND DATE(start_on)='$date' AND type='$name'");
	
	if($all_connections) {
		print "<ul>";
		foreach($all_connections as $con) {
			$all_people = array();

			$all_people_connections = $sql->getAll("SELECT person_id FROM PersonConnection WHERE connection_id=$con[id]");
			foreach($all_people_connections as $pep_con) {
				$person = $people[$pep_con['person_id']];
				$all_people[] = '<a href="person.php?person_id='.$pep_con['person_id'].'">'. ($person['nickname'] | $person['name']) .'</a>';
			}
			
			if($all_people)  print "<li>".implode(', ', $all_people)." <a href='ajax/delete_connection.php?connection_id=$con[id]' "
									. "class='ajaxify ajaxify-remove-parent ajaxify-confirm delete icon' title='Delete ". ($person['nickname'] | $person['name']) ." connection'>Delete</a>"
									. " <a href='#' class='popup edit icon'>Details</a></li>";
		}
		print "</ul>";
	}
}
