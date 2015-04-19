<?php
REQUIRE_ONCE "database_connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Minecraft Custom Skull Generator - Create Custom Skulls">
    <title>Minecraft Custom Skull Generator - Gallery</title>
    <link rel="stylesheet" href="/skull/bootstrap/css/bootstrap.min.css">
    <link href="//fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<style>
    p{font-size:20px}.navbar{border:0!important;border-radius:0!important}
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
                    <a target="_blank" href="https://github.com/CrushedPixel/MCSkullGenerator">Source Code on GitHub</a>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li>
                    <a href="/skull/stats">Statistics</a>
                </li>
            </ul>

            <ul class="nav navbar-nav">
                <li>
                    <a href="/skull/gallery">Gallery</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><a href="http://crushedpixel.eu" target="_blank">CrushedPixel</a></li>
                <li><a href="http://thedestruc7i0n.ca" target="_blank">TheDestruc7i0n</a></li>
            </ul>

        </div>
    </div>
</div>
<br>
<br>
<div class="container bcon">
    <div class="col-lg-8 col-sm-offset-2">
        <div class="page-header">
            <div class="jumbotron">
                <h1><center>Skull Gallery</center></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-offset-2">
            <?php
            global $con;

            $sql = "SELECT url, id FROM generated ORDER BY id DESC LIMIT 30";
            $stmt = $con->prepare($sql);
            $stmt->execute();

            $count = $stmt->rowCount();

            echo '<center>';

            echo "<h2>The most recent $count skulls:</h2>";
            while($row = $stmt->fetch()) {
                echo '<a href="/skull?id='.$row["id"].'"><img style="margin:10px;" width="93px" height="100px"
                src="http://heads.freshcoal.com/3d/3d.php?headOnly=true&aa=true&user='.$row["url"].'"/></a>';
            }
            echo '</center>';

            ?>
        </div>
    </div>
</div>
<hr/>

<ul class="breadcrumb">
    <center><li class="active">&copy; 2015 <a href="http://crushedpixel.eu" target="_blank">CrushedPixel</a> &amp; <a href="http://thedestruc7i0n.ca" target="_blank">TheDestruc7i0n</a></li></center>
</ul>
</body>
</html>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.2.0/ZeroClipboard.min.js"></script>
<script src="/analytics/static/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
    var client = new ZeroClipboard( $(".copyable") );
</script>
<script src="/analytics/static/bootstrap.min.js"></script> <!--Just in case-->