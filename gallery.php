<?php
REQUIRE_ONCE "database_connection.php";

$page = 1;
if(isset($_GET["page"])) {
    $page = $_GET["page"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Minecraft Custom Skull Generator - Create Custom Skulls">
    <title>Mineskull - Gallery</title>
    <link rel="stylesheet" href="/skull/bootstrap/css/bootstrap.min.css">
    <link href="//fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="/analytics/favicon.ico" />
</head>

<style>
    p{font-size:20px}.navbar{border:0!important;border-radius:0!important}
</style>

<body>

<div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a href="/skull/" class="navbar-brand">Mineskull</a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li>
                    <a target="_blank" href="https://github.com/CrushedPixel/Mineskull">Source Code on GitHub</a>
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
<div class="container bcon">
    <div class="col-lg-8 col-sm-offset-2">
        <div class="page-header">
            <div class="jumbotron" style="margin-top: 40px;">
                <h1><center>Skull Gallery</center></h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8 col-sm-offset-2">
            <?php
            global $con;

            $pagesize = 30;

            $sql = "SELECT COUNT(*) AS count FROM generated";
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $pagecount = (int)ceil($stmt->fetch()["count"]/$pagesize);

            $offset = $pagesize*($page-1);

            $sql = "SELECT url, id FROM generated ORDER BY id DESC LIMIT $pagesize OFFSET $offset";
            $stmt = $con->prepare($sql);
            $stmt->execute();

            $count = $stmt->rowCount();

            echo '<center>';

            if($page === 1) {
                echo "<h2>The most recent $count skulls:</h2>";
            }
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
<center>
    <?php
    function printbutton($id) {
        global $page;
        if($page == $id) {
            echo "<li class=\"active\">";
        } else {
            echo "<li>";
        }
        echo "<a href=\"/skull/gallery?page=$id\">$id</a>";
        echo "</li>";
    }

    $toprint = array(1,2,3);
    $toprint[] = $page-1;
    $toprint[] = $page;
    $toprint[] = $page+1;
    $toprint[] = $pagecount-2;
    $toprint[] = $pagecount-1;
    $toprint[] = $pagecount;

    echo '<ul class="pagination">';

    $print = array();
    foreach($toprint as $id) {
        if(!(in_array($id, $print) or $id < 1 or $id > $pagecount)) {
            if(!in_array($id-1, $print) and $id > 1) {
                echo "<li><a>&#183;&#183;&#183;</a></li>";
            }
            printbutton($id);
            $print[] = $id;
        }
    }

    echo '</ud>';

    ?>

</center>
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