<script type="text/javascript">
$("#popup-title").html("Connection Details");
jQuery.ajaxify.init();
</script>
<form action="<?php echo $config['site_url'] ?>popup/connection_details.php" method="post" id="connection-details">

<?php
$html->buildInput("people", '', 'text', implode(", ", $names), ['class' => "data form-control", 'no_br' => true]);
$html->buildInput("people_existing", '', 'hidden', implode(",", $names));
$intensity = [
	'5'	=> 'Once in a life-time Experiance',
	'4'	=> 'Great Experiance',
	'3'	=> 'Very Good Experiance',
	'2'	=> 'Good Experiance',
	'1'	=> 'Normal',
	'0'	=> 'A Forgatable experiance',
	'-1'=> 'Bad Experiance',
	'-2'=> 'Very Bad Experiance',
	'-3'=> 'Horrible Experiance',
	'-4'=> 'Death would have been better'
];
$html->buildInput("intensity", "Intensity",'select',$connection['intensity'], ['options' => $intensity, 'class' => 'form-control', 'no_br' => true]);

$html->buildInput("start_on", "Start Time", 'text', $connection['start_on'], ['class' => 'form-control', 'no_br' => true]);
$html->buildInput("location", "Place", 'text', $connection['location'], ['class' => 'form-control', 'no_br' => true]);
$html->buildInput("note", 'Note', 'textarea', $connection['note'], ['class' => 'form-control', 'no_br' => true]);
$html->buildInput("initiated_by", 'Initiated By Me', 'checkbox', $connection['initiated_by'] == 'me');

$html->buildInput("end_on", "", 'hidden', $connection['end_on']);
$html->buildInput("connection_id", '', 'hidden', $connection['id']);
$html->buildInput("action", '', 'submit', 'Save', ['class' => 'btn btn-primary']);

?>

<a href="ajax/delete_connection.php?connection_id=<?php echo $connection['id'] ?>" title="Delete connection"
	class='ajaxify ajaxify-confirm ajaxify-custom-handler btn btn-sm btn-warning float-right'><i class="fas fa-trash"></i> Delete</a>

</form>