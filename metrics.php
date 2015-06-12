<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Automation-audit</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
	<script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
	<script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>


	<style type="text/css">
		body { padding-top: 70px; 
			margin: 50px;}
		.label-as-badge {
    border-radius: 1em;
    font-size: 15px;

}


path { 
  stroke: black;
  stroke-width: 2;
  fill: none;
}

.pre_ingest{
  stroke: orange;
  stroke-width: 2;
  fill: none;
 }

 .run_component{
  stroke: blue;
  stroke-width: 2;
  fill: none;
 }

 .readyForIngest{
  stroke: green;
  stroke-width: 2;
  fill: none;
 }

 .artworkBacklog{
  stroke: red;
  stroke-width: 2;
  fill: none;
 }
 
.axis path,
.axis line {
	fill: none;
	stroke: grey;
	stroke-width: 1;
	shape-rendering: crispEdges;
}
	</style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>


<?php 
	$selectedDB = 'metrics.db'
?>

	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
		    <!-- Brand and toggle get grouped for better mobile display -->
		    <div class="navbar-header">
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand" href="#">Media cons pre-ingest stats</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
				<li> </li>
				
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
	</nav>

<?php
	$db = new SQLite3($selectedDB);
	$query = $db->query('SELECT * FROM counting');
	$pre_ingest_data = array();
	$run_component_data = array();
	$readyForIngest_data = array();
	$artworkBacklog_data = array();

	while ($row = $query->fetchArray()) {
		$date = $row[0];
		$pre_ingest = $row[1];
		$run_component = $row[2];
		$readyForIngest = $row[3];
		$artworkBacklog = $row[4];

		$pre_ingest_data[] = array("date" => $date, "close" => $pre_ingest);
		$run_component_data[] = array("date" => $date, "close" => $run_component);
		$readyForIngest_data[] = array("date" => $date, "close" => $readyForIngest);
		$artworkBacklog_data[] = array("date" => $date, "close" => $artworkBacklog);

		};

	$pre_ingest_data = json_encode($pre_ingest_data);
	$run_component_data = json_encode($run_component_data);
	$readyForIngest_data = json_encode($readyForIngest_data);
	$artworkBacklog_data = json_encode($artworkBacklog_data);

		// echo $date.$pre_ingest.$run_component.$readyForIngest.$artworkBacklog;
		// add these to the JSON for the D3 chart

	
?>

</body>

<script type="text/javascript">

// set dimensions of the graph

var margin = { top: 30, right: 20, bottom: 30, left: 50 },
    width = 1200 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

// parse the date format
var	parseDate = d3.time.format("%Y-%m-%d").parse;

// set the ranges
var x = d3.time.scale().range([0, width]);
var y = d3.scale.linear().range([height, 0]);

// define the axis
var xAxis = d3.svg.axis().scale(x)
	.orient("bottom").ticks(5);
var yAxis = d3.svg.axis().scale(y)
	.orient("left").ticks(5);
  
// Define the line
var	valueline = d3.svg.line()
	.x(function(d) { return x(d.date); })
	.y(function(d) { return y(d.close); });
    
// Adds the svg canvas
var	svg = d3.select("body")
	.append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
	.append("g")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
 
// Get the data
     var pre_ingest_data = <?php echo $pre_ingest_data; ?>;
     var run_component_data = <?php echo $run_component_data; ?>;
     var readyForIngest_data = <?php echo $readyForIngest_data; ?>;
     var artworkBacklog_data = <?php echo $artworkBacklog_data; ?>;
 
 	//get data for pre-ingest
	pre_ingest_data.forEach(function(d) {
		d.date = parseDate(d.date);
		d.close = +d.close;
	});

	//get data for run_component
	run_component_data.forEach(function(d) {
		d.date = parseDate(d.date);
		d.close = +d.close;
	});

	//get data for readyForIngest_data
	readyForIngest_data.forEach(function(d) {
		d.date = parseDate(d.date);
		d.close = +d.close;
	});

	//get data for artworkBacklog_data
	artworkBacklog_data.forEach(function(d) {
		d.date = parseDate(d.date);
		d.close = +d.close;
	});

	// Scale the range of the data
	x.domain(d3.extent(pre_ingest_data, function(d) { return d.date; }));
	y.domain([0, 1000]);
 
	// draw pre-ingest
	svg.append("path")	
		.attr("class", "pre_ingest")
		.attr("d", valueline(pre_ingest_data));
 
 	// draw run_component
	svg.append("path")
		.attr("class", "run_component")
		.attr("d", valueline(run_component_data));
 	// draw readyForIngest_data
	svg.append("path")
		.attr("class", "readyForIngest")
		.attr("d", valueline(readyForIngest_data));
 	// draw artworkBacklog_data
	svg.append("path")
		.attr("class", "artworkBacklog")
		.attr("d", valueline(artworkBacklog_data));

	// Add the X Axis
	svg.append("g")		
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis);
 
	// Add the Y Axis
	svg.append("g")		
		.attr("class", "y axis")
		.call(yAxis);


</script>

