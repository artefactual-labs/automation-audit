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

	<style type="text/css">
		body { padding-top: 70px; }
		.label-as-badge {
    border-radius: 1em;
    font-size: 15px;

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
		      <a class="navbar-brand" href="#">MoMA-utils</a>
		    </div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
				<li> </li>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">menu<span class="caret"></span></a>
				  <ul class="dropdown-menu" role="menu">

				  </ul>
				</li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		      	<li><a href=""><span class="glyphicon glyphicon-user" aria-hidden="true"></span><span class="user"><?php echo " {$_SERVER['PHP_AUTH_USER']}"; ?></span></a></li>
		      </ul>
		    </div><!-- /.navbar-collapse -->
		  </div><!-- /.container-fluid -->
	</nav>

<div class='container-fluid'>

<?php 

	if (isset($_GET['objectnum'])){
		$artwork = $_GET['objectnum'];
		$url = file_get_contents('http://vmsqlsvcs.museum.moma.org/TMSAPI/TmsObjectSvc/TmsObjects.svc/GetTombstoneDataRest/Object/'.$artwork);
		$json = json_decode($url, true);
		$title = $json["GetTombstoneDataRestResult"]["Title"];
		$artist = $json["GetTombstoneDataRestResult"]["AlphaSort"];
		$objectnum = $json["GetTombstoneDataRestResult"]["ObjectNumber"];
		$objectid = $json["GetTombstoneDataRestResult"]["ObjectID"];
		$components = json_decode($json["GetTombstoneDataRestResult"]["Components"], true);
		$dirname = $artist."---".$title."---".$objectnum."---".$objectid;

		$dirname = preg_replace('/\s+/', '_', $dirname);

		print "<div class='well'><h1><span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>&nbsp;&nbsp;".$dirname."</h1></div>";

		
		foreach ($components as $component) {
    		$componentNumber = $component['ComponentNumber'];
    		$componentId = $component['ComponentID'];
    		$componentFoldername = $componentNumber."---".$componentId."---".$objectid;
    		echo "<div class='well col-md-offset-1'><h3><span class='glyphicon glyphicon-folder-open' aria-hidden='true'></span>&nbsp;&nbsp;".$componentFoldername."</h3></div>";
		}


		
	}
	else {
		echo "<h1>Please enter an object number in the url with '?objectnum='</h1>";
	};

	?>

</div>
</body>