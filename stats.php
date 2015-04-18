<?php
REQUIRE_ONCE "database_connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<link rel="stylesheet" href="/skull/bootstrap/css/bootstrap.min.css">
	<link href='https://fonts.googleapis.com/css?family=Ubuntu+Mono' rel='stylesheet' type='text/css'>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<style>
	p {
		font-size: 20px;
	}
	code /*If you want code*/ {
		font-size: 130%;
	}
	code#inline {
		font-size: 100%;
	}
	.navbar {
		border: 0px !important;
		border-radius: 0px !important;
	}
</style>

<body>

<div class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<div class="navbar-header">
			<a href="/skull/" class="navbar-brand">Minecraft Custom Skull Generator</a>
		</div>
		<div class="navbar-collapse collapse" id="navbar-main">
			<ul class="nav navbar-nav">
				<li>
					<a target="_blank">Source Code on GitHub</a>
				</li>
			</ul>

			<ul class="nav navbar-nav">
				<li>
					<a href="/skull/stats">Statistics</a>
				</li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				<li><a href="http://crushedpixel.eu" target="_blank">CrushedPixel</a></li>
			</ul>

		</div>
	</div>
</div>

<div class="container bcon">
	<div class="row">
		<div class="col-lg-8 col-sm-offset-2">
			<div class="page-header">
				<div class="jumbotron">
					<h1><center>General Statistics</center></h1>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8 col-sm-offset-2">
			<?php
			$time = time();

			global $con;

			$sql = 'SELECT * FROM stats LIMIT 1';
			$stmt = $con->prepare($sql);
			$stmt->execute();

			$first = $stmt->fetch()["time"];

			$sql = 'SELECT COUNT(*) as count FROM stats';
			$stmt = $con->prepare($sql);
			$stmt->execute();

			$total = $stmt->fetch()["count"];

			$sql = 'SELECT SUM(duplicate) AS duplicates FROM generated';
			$stmt = $con->prepare($sql);
			$stmt->execute();

			$duplicates = $stmt->fetch()["duplicates"];

			$sql = 'SELECT COUNT(*) as count FROM stats WHERE time>?';
			$stmt = $con->prepare($sql);
			$stmt->bindValue(1, $time-(24*(60*60))); //Last 24 hours
			$stmt->execute();

			$lastday = $stmt->fetch()["count"];

			$average = (int)(($time-$first)/($total+$duplicates));

			echo '<h2>Total Skulls generated: <b>'. ($total+$duplicates) .'</b>,</h2>';
			echo '<h2><b>'. $duplicates .'</b> of them being duplicates.</h2>';
			echo '<h2>Skulls generated in the past 24 hours: <b>'. $lastday .'</b></h2>';
			echo '<h2>One Skull generated every <b>'. $average .' seconds</b></h2>';

			echo '<br>';

			?>
		</div>
	</div>
</div>
<hr/>

<ul class="breadcrumb">
	<center><li class="active">&copy; 2015 <a href="http://crushedpixel.eu" target="_blank">CrushedPixel</a></li></center>
</ul>
</body>
</html>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.2.0/ZeroClipboard.min.js"></script>
<script src="/analytics/static/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
	var client = new ZeroClipboard( $(".copyable") );
</script>
<script src="/analytics/static/bootstrap.min.js"></script> <!--Just in case-->