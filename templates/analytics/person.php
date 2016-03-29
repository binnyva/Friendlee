<script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart', 'calendar']
            }]
          }"></script>
<script type="text/javascript">
google.setOnLoadCallback(drawChart);

function drawChart() {
	var data = google.visualization.arrayToDataTable(
		<?php echo json_encode(array_merge(array(array('Index', 'Count', 'Point')), $data)); ?>
	);

	var options = {
		title: "Points Over Time",
		orientation: "horizontal",
		height: 400
	};

	var chart = new google.visualization.BarChart(document.getElementById('bar_chart'));
	chart.draw(data, options);


	// Distribution
	var dataTable = new google.visualization.DataTable();
	dataTable.addColumn({ type: 'date', id: 'Date' });
	dataTable.addColumn({ type: 'number', id: 'Type' });
	dataTable.addRows([
		<?php
		$dates = array();
		foreach($distribution as $f) { 
			$info = $f['note'];
			if($f['location']) $info .= "(" . $f['location'] . ")";
			$info = addslashes($info);

			if($connection_type == 'met' and $more_data_type = 'ratio') {
				$count = 0;
				if(preg_match('/(\d+)\:(\d+)/', $info, $matches)) {
					$count = intval($matches[1]);
				}
			} else {
				$count = $points[$f['type']];
			}

			$dates[] = "[ new Date('".date('Y-m-d', strtotime($f['start_on']))."'), $count ]"; 
		}
		print implode(",\n", $dates);
		?>
	]);

	var calendar = new google.visualization.Calendar(document.getElementById('calendar'));
	var calendar_options = {
		title: "Distribution for <?php echo $person_name ?>",
		height: 600,
		colorAxis: {
			colors:['#43609c','#0dc143','#2da5e0','#f44336'],
			values:[2,3,5,10]
		}
	};
	calendar.draw(dataTable, calendar_options);
}
</script>

<h1>Analytics for <?php echo $person_name ?></h1>

<h4>Longest Streak: <?php echo $longest_streak ?> days</h4>
<?php showFromTo($longest_streak, $longest_streak_to); ?>

<h5>Longest Gap: <?php echo $longest_gap ?> days</h5>
<?php showFromTo($longest_gap, $longest_gap_to); ?>


<h3>Legend</h3>

<span style="background:#f44336;" class="legend-cell">Meet</span>
<span style="background:#2da5e0;" class="legend-cell">Call</span>
<span style="background:#0dc143;" class="legend-cell">Message</span>
<span style="background:#43609c;" class="legend-cell">Chat</span>
<br /><br />

<div id="calendar"></div>

<h2>Growth Rate</h2>

<div id="bar_chart"></div>

<br /><br />