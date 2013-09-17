<?php /*
<script type="text/javascript">
var people = <?php echo json_encode(array_values($all_people)); ?>;
</script>
<script type="text/javascript" src="../js/index.js"></script>
<link href="../js/library/jquery-ui/css/jquery-ui.css" rel="stylesheet" type="text/css">
<link href="../css/index.css" rel="stylesheet" type="text/css">
*/ ?>

<form action="<?php echo $config['site_url'] ?>popup/connection_details.php" method="post" class="form-area" id="connection-details">

<?php
$html->buildInput("people", '', 'text', implode(", ", $names), array('class'=>"data"));
$html->buildInput("people_existing", '', 'hidden', implode(",", $names));
$intensity = array(
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
);
$html->buildInput("intensity", "Intensity",'select',$connection['intensity'],array('options'=>$intensity));

$html->buildInput("start_on", "Start Time", 'text', $connection['start_on']);
$html->buildInput("end_on", "End Time", 'text', $connection['end_on']);

$html->buildInput("location", "Place", 'text', $connection['location']);
$html->buildInput("note", 'Note', 'textarea', $connection['note']);

$html->buildInput("connection_id", '', 'hidden', $connection['id']);
$html->buildInput("action", '&nbsp;', 'submit', 'Save');
?>

</form>