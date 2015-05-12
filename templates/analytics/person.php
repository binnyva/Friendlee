<!--Load the AJAX API-->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

// Load the Visualization API and the piechart package.
google.load('visualization', '1.0', {'packages':['corechart', 'calendar']});

// Set a callback to run when the Google Visualization API is loaded.
google.setOnLoadCallback(drawChart);


<?php if($visualization_type == 'calendar') { ?>
// Calender Chart
function drawChart() {
	var dataTable = new google.visualization.DataTable();
	dataTable.addColumn({ type: 'date', id: 'Date' });
	dataTable.addColumn({ type: 'number', id: 'Type' });
	dataTable.addRows([
		<?php
		$dates = array();
		foreach($freq as $f) { 
			$info = $f['note'];
			if($f['location']) $info .= "(" . $f['location'] . ")";
			$info = addslashes($info);

			if($connection_type == 'any') {
				$count = $points[$f['type']];

			} elseif($connection_type == 'met' AND $more_data_type = 'ratio') {
				$count = 0;
				if(preg_match('/(\d+)\:(\d+)/', $info, $matches)) {
					$count = intval($matches[1]);
				}
			}

			$dates[] = "[ new Date('".date('Y-m-d', strtotime($f['start_on']))."'), $count ]"; 
		}
		print implode(",\n", $dates);
		?>
	]);

	var chart = new google.visualization.Calendar(document.getElementById('chart_div'));

	var options = {
	title: "<?php echo $page_title ?>",
	height: 700,
	};

	chart.draw(dataTable, options);

}
<?php } else { ?>


// Callback that creates and populates a data table,
// instantiates the Bar chart, passes in the data and
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
	var options = {'title':'Friendlee Growth Chart',
	               'width':600,
	               'height':300,
	               'vAxis': {title: "People"},
	               'hAxis': {title: "Points"},
	           };

	// Instantiate and draw our chart, passing in some options.
	var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
	chart.draw(data, options);
}
<?php } ?>

</script>

<div id="chart_div"></div>

<h3>Legend</h3>

<span style="background:#4273e0;padding:3px;border:1px solid #000;">Meet</span>
<span style="background:#b8caf3;padding:3px;border:1px solid #000;">Call</span>
<span style="background:#e7edfb;padding:3px;border:1px solid #000;">Message</span>
<span style="background:#ffffff;padding:3px;border:1px solid #000;">Chat</span>


