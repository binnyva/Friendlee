<?php $i_plugin->callHook("profile_top_display", array($person)); ?>

<form action="ajax/change_person_details.php" method="post">
<div class="camouflage-area">

<input type="text" class="camouflage big" name="nickname" value="<?php echo $person['nickname'] ?>" />
<input type="submit" name="action" value="Save" class="stealth btn btn-primary" />
<input type="button" name="more" id="show-more-options" value="More Options" class="stealth auto-show btn btn-success btn-sm" />
</div>

<div id="more-options-area" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Details for '<?php echo $person['nickname'] ?>'</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="closeOptions()">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="popup-area">
<?php
buildInput("name", 'Name', 'text', $person['name'], ['class' => 'form-control', 'no_br' => true]);
buildInput("email", 'Email', 'text', $person['email'], ['class' => 'form-control', 'no_br' => true]);
buildInput("phone", 'Phone', 'text', $person['phone'], ['class' => 'form-control', 'no_br' => true]);
buildInput("sex", "Sex", 'select', $person['sex'], ['options' => ['m' => 'Male','f' => 'Female'], 'class' => 'form-control popup-dropdown']);
buildInput("city_id", 'City', 'select', $person['city_id'], ['options' => $all_cities, 'class' => 'form-control popup-dropdown', 'no_br' => true]);
?><a href="cities.php?action=add">Another City?</a><br /><?php
buildInput("locality", 'Locality', 'text', $person['locality'], ['class' => 'form-control', 'no_br' => true]);
buildInput("level_id", "Level", 'select', $person['level_id'], ['options' => $all_levels, 'class' => 'form-control popup-dropdown']);
buildInput("note", "Note...", 'textarea', $person['note'], ['class' => 'form-control', 'no_br' => true]);
?>
<a href="#" id="toggle-advanced-options" class="with-icon settings">Advanced Options...</a>

<div id="advanced-options">
<fieldset>
<legend>Advanced Options</legend>
<?php
buildInput("priority", "Priority", 'select', $person['priority'], ['options' => $all_priorities, 'class' => 'form-control popup-dropdown']);
buildInput("facebook_id", 'Facebook ID', 'text', $person['facebook_id'], ['class' => 'form-control', 'no_br' => true]);
buildInput("facebook", 'Facebook Username', 'text', $person['facebook'], ['class' => 'form-control', 'no_br' => true]);
buildInput("twitter", 'Twitter Handle', 'text', $person['twitter'], ['class' => 'form-control', 'no_br' => true]);
buildInput("birthday", 'Birthday', 'text', $person['birthday'], ['class' => 'form-control', 'no_br' => true]);

buildInput("autocomplete", 'Show in Auto Complete List', 'checkbox', $person['autocomplete']);
buildInput("automanaged", 'Hide in Uncontacted List', 'checkbox', $person['automanaged']);
?>
</fieldset>
</div>
<?php

buildInput("person_id", "", 'hidden', $person['id']);
?><br />

<input type="submit" name="action" value="Save" class="big btn btn-primary" />

<a href="ajax/delete_person.php?person_id=<?php echo $person['id'] ?>" title="Delete <?php echo firstName($person['nickname']) ?>"
	class='confirm btn btn-sm btn-warning float-right'><i class="fas fa-trash"></i> Delete <?php echo firstName($person['nickname']) ?></a>
	  </div>
    </div>
  </div>
</div>

<!-- <input type="button" name="more" id="hide-more-options" class="auto-hide" value="Hide Options" /><br /> -->
</form>


<?php if($last_contact) { ?>
<p>Last Contact: <?php echo ucfirst($last_contact['type']) . ' on '; showDate($last_contact) ?> <a href="#" id='more-info-last-contact'>+</a></p>

<div id="more-info">
<ul>
<?php if($last_met)		{ ?><li>Last Met: <?php	showDate($last_met); ?></li><?php } ?>
<?php if($last_phone)	{ ?><li>Last Call: <?php showDate($last_phone); ?></li><?php } ?>
<?php if($last_message) { ?><li>Last Message: <?php	showDate($last_message); ?></li><?php } ?>
<?php if($last_chat)	{ ?><li>Last Chat: <?php showDate($last_chat); ?></li><?php } ?>
</ul>
</div>
<?php } ?>

<table id="score-table">
<tr><th>Met</th><th>Phone</th><th>Message</th><th>Other</th></tr>
<tr><td>
<span class="count"><?php echo $met_count ?></span> x 
<span class="point"><?php echo $points['met'] ?></span><br />
<span class="score"><?php echo $met_count * $points['met'] ?></span>
</td><td>
<span class="count"><?php echo $phone_count ?></span> x 
<span class="point"><?php echo $points['phone'] ?></span><br />
<span class="score"><?php echo $phone_count * $points['phone'] ?></span>
</td><td>
<span class="count"><?php echo $message_count ?></span> x 
<span class="point"><?php echo $points['message'] ?></span><br />
<span class="score"><?php echo $message_count * $points['message'] ?></span>
</td><td>
<span class="count"><?php echo $other_count ?></span> x 
<span class="point"><?php echo $points['other'] ?></span><br />
<span class="score"><?php echo $other_count * $points['other'] ?></span>
</td></tr>
<tr><td colspan="4"><span class="total-score"><?php echo $total_score ?></span></td></tr>
</table>

<p><a href="analytics/person.php?person_id=<?php echo $person_id ?>" class="with-icon analytics">Analytics for <?php echo firstName($person['nickname']) ?></a></p>

<?php $i_plugin->callHook("profile_middle_display", array($person)); ?>

<h3>Interaction Log</h3>
<ul>
<?php foreach($interaction_log as $interaction) { ?>
	<li> <a href='popup/connection_details.php?connection_id=<?php echo $interaction['id']; ?>' class='popup'><?php 
				echo ucfirst($interaction['type']) ?></a> on <a href="index.php?date=<?php echo date('Y-m-d', strtotime($interaction['start_on'])) ?>"><?php 
				echo date($config['date_format_php'], strtotime($interaction['start_on'])) ?></a></li>
<?php } ?>
</ul>

<?php 
$i_plugin->callHook("profile_end_display", array($person));

function getDistanceColor($contact, $person) {
	global $sql, $frequency;
	
	$curdate = new DateTime();
	$target_date = new DateTime($contact['start_on']);
	$interval = $target_date->diff($curdate);
	$day_count = $interval->format('%a');

	$type_freq = $frequency;

	$percent_over = 0;
	if($type_freq) $percent_over = intval($day_count / $type_freq * 100);
	$color_gradiant = array('delay-20','delay-40','delay-60','delay-80','delay-100','delayed');

	if($percent_over < 20) $color = $color_gradiant[0];
	else if($percent_over < 40) $color = $color_gradiant[1];
	else if($percent_over < 60) $color = $color_gradiant[2];
	else if($percent_over < 80) $color = $color_gradiant[3];
	else if($percent_over < 100) $color = $color_gradiant[4];
	else $color = $color_gradiant[5];
	
	return $color;
}

function showDate($contact) {
	global $person;
	
	$date = date('dS M, Y', strtotime($contact['start_on']));
	$color = getDistanceColor($contact, $person);
	
	print "<span class='$color'>$date</span>";
}