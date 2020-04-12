<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
	// Create the data table.
	var data = new google.visualization.DataTable();
	data.addColumn('string', 'People');
	data.addColumn('number', 'Points');

	data.addRows([
		<?php foreach($top_ten as $person) echo "['" . addslashes($person['name']) ."', $person[points] ],"; ?>
	]);

	// Set chart options
	var options = {
		'title':'Friendlee Growth Chart',
		'width':600,
		'height':300,
		'vAxis': {title: "People"},
		'hAxis': {title: "Points"}
	};

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
	chart.draw(data, options);
}
</script>

<h3><?php echo $text . ': ' . date('dS M', strtotime($from)) . ' to ' . date('dS M', strtotime($to)); ?></h3>

<div id="chart_div"></div>


<ul class="btn-group btn-group-justified center-block" role="group" aria-label="...">
<li class="btn btn-default"><a class="with-icon previous" href="?from=<?php echo date('Y-m-d', strtotime('-'.$interval,strtotime($from))); ?>&amp;type=<?php echo $type ?>">Last <?php echo ucfirst($type) ?></a></li>
<?php if($type == 'week') { ?>
<li class="btn btn-default"><a class="with-icon calendar" href="?from=<?php echo $from; ?>&amp;type=month">Show Month</a></li>
<?php } elseif($type == 'month') { ?>
<li class="btn btn-default"><a class="with-icon calendar" href="?from=<?php echo $from; ?>&amp;type=week">Show Week</a></li>
<?php } ?>
<li class="btn btn-default"><a class="with-icon next" href="?from=<?php echo date('Y-m-d', strtotime('+'.$interval,strtotime($to))); ?>&amp;type=<?php echo $type ?>">Next <?php echo ucfirst($type) ?></a></li>
</ul>
