
<form action="ajax/change_person_details.php" method="post">
<div class="camouflage-area">

<input type="text" class="camouflage big" name="nickname" value="<?php echo $person['nickname'] ?>" />
<input type="submit" name="action" value="Save" class="stealth" />
<input type="button" name="more" id="show-more-options" value="More Options" class="stealth auto-show" />
</div>

<div id="more-options-area"  class="form-area hidden">
<?php
$html->buildInput("name", 'Name', 'text', $person['name']);
$html->buildInput("email", 'Email', 'text', $person['email']);
$html->buildInput("phone", 'Phone', 'text', $person['phone']);
$html->buildInput("sex", "Sex", 'select', $person['sex'],
	 		array('options' => array('m' => 'Male','f' => 'Female')));

$html->buildInput("facebook_id", 'Facebook ID', 'text', $person['facebook_id']);
$html->buildInput("twitter", 'Twitter Handle', 'text', $person['twitter']);
$html->buildInput("birthday", 'Birthday', 'text', $person['birthday']);

$html->buildInput("city_id", 'City', 'select', $person['city_id'], array('options' => $all_cities));
$html->buildInput("locality", 'Locality', 'text', $person['locality']);
$html->buildInput("level_id", "Level", 'select', $person['level_id'], array('options' => $all_levels));

$html->buildInput("person_id", "", 'hidden', $person['id']);
?>
<label for="action">&nbsp;</label><input type="submit" name="action" value="Save" class="big" /><br />
<input type="button" name="more" id="hide-more-options" class="auto-hide" value="Hide Options" /><br />
</div>
</form>


<?php if($last_contact) { ?>
<p>Last Contact: <?php echo ucfirst($last_contact['type']) . ' on '; showDate($last_contact) ?> <a href="#" id='more-info-last-contact'>+</a></p>

<div id="more-info">
<ul>
<?php if($last_met)		{ ?><li>Last Met: <?php	showDate($last_met); ?></li><?php } ?>
<?php if($last_phone)	{ ?><li>Last Call: <?php showDate($last_phone); ?></li><?php } ?>
<?php if($last_message) {?><li>Last Message: <?php	showDate($last_message); ?></li><?php } ?>
<?php if($last_chat)	{ ?><li>Last Chat: <?php showDate($last_chat); ?></li><?php } ?>
</ul>
</div>
<?php } ?>

<table id="score-table">
<tr><th>Met</th><th>Phone</th><th>Message</th><th>Chat</th></tr>
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
<span class="count"><?php echo $chat_count ?></span> x 
<span class="point"><?php echo $points['chat'] ?></span><br />
<span class="score"><?php echo $chat_count * $points['chat'] ?></span>
</td></tr>
<tr><td colspan="4"><span class="total-score"><?php echo $total_score ?></span></td></tr>
</table>


<?php
function getDistanceColor($contact, $person) {
	global $sql, $frequency;
	
	$curdate = new DateTime();
	$target_date = new DateTime($contact['start_on']);
	$interval = $target_date->diff($curdate);
	$day_count = $interval->format('%a');
	
	$type_freq = $frequency[$contact['type']];

	$percent_over = intval($day_count / $type_freq * 100);
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