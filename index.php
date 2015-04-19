<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="description" content="Minecraft Custom Skull Generator - Create Custom Skulls">
    <title>Minecraft Custom Skull Generator - Front Page</title>
    <link rel="stylesheet" href="/skull/bootstrap/css/bootstrap.min.css">
    <link href="//fonts.googleapis.com/css?family=Ubuntu+Mono" rel="stylesheet" type="text/css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,
                                    initial-scale=1.0,
                                    maximum-scale=1.0,
                                    user-scalable=no">
    ￼￼￼￼￼￼￼￼￼
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>

<style>
    p{font-size:20px}.navbar{border:0!important;border-radius:0!important}
    .contentBorder{
        outline: 2px solid #CCC;
        margin-bottom: 15px;
    }
    .invis_button{opacity:1}.fileUpload{position:relative;overflow:hidden;margin:10px}
    .fileUpload input.upload{position:absolute;top:0;right:0;margin:0;padding:0;font-size:20px;cursor:pointer;opacity:0;filter:alpha(opacity=0)}
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
    <div class="row">
        <div class="col-lg-8 col-sm-offset-2">
            <div class="page-header">
                <div class="jumbotron">
                    <h1><center>Custom Skull Generator</center></h1>
                    <br>
                    <div class="row">
                        <form action="/skull/generate_skull.php" method="post" enctype="multipart/form-data">
                            <center>
                                <div class="contentBorder" style="width: 350px; padding-bottom: 10px;">
                                    <input id="uploadFile" placeholder="Choose File" disabled="disabled" style="width:160px;" />
                                    <div class="fileUpload btn">
                                        <span>Choose Skin</span>
                                        <input type="file" class="upload uploadBtn" id="uploadBtn" name="fileToUpload"/>
                                    </div>
                                    <br>
                                    <input id="linkFile" placeholder="Skin URL" name="fileURL" style="width:300px;margin-bottom: 10px" />
                                    <br>Upload a skin file or provide a link to the image.
                                </div>
                                <input type="submit" class="fileUpload btn btn-primary" value="Generate Skull" name="submit">
                            </center>
                        </form>
                    </div>

                    <script>
                        document.getElementById("uploadBtn").onchange = function () {
                            document.getElementById("uploadFile").value = this.value;
                        };
                    </script>

                    <?php
                    if(isset($_GET["error"])) {
                        $error = $_GET["error"];
                        switch($error) {
                            case 0:
                                $err_msg = "Invalid skin provided!";
                                break;
                            case 1:
                                $err_msg = "We don't have enough resources to process your request. Please try again in a couple of seconds.";
                                break;
                            case 2:
                                $err_msg = "You can only convert one player head per minute. Please try again in a couple of seconds.";
                                break;
                            case 3:
                                $err_msg = "No skin uploaded!";
                                break;
                            default:
                                $err_msg = "An unknown error occured.";
                                break;
                        }

                        echo '<br>';
                        echo '<div class="contentBorder"><center><p class="text-danger">'.$err_msg."</p></center></div>";
                    } elseif(isset($_GET["id"])) {
                        REQUIRE_ONCE "database_connection.php";
                        global $con;

                        $sql = "SELECT * FROM generated WHERE id=?";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(1, $_GET["id"]);
                        $stmt->execute();

                        $command = "";
                        $url = "";

                        while($row = $stmt->fetch()) {
                            $command = $row["command"];
                            $url = $row["url"];
                            break;
                        }

                        echo '<br>';
                        echo '<div class="form-group">';
                        echo '<label class="control-label">Copy Command</label>';
                        echo '<div class="input-group col-xs-6">';
                        echo '<input onclick="select()" class="form-control copyme" id="focusedInput" type="text" value="'.$command.'" readonly="readonly" style="width:565px;">';
                        echo '<span class="input-group-btn">';
                        echo '<button class="btn btn-default copyable" type="button" data-clipboard-text="'.$command.'">Copy</button>';
                        echo '</span>';
                        echo '</div>';
                        echo '<br>';
                        echo '<br>';
                        echo '<center><img width="93px" height="100px" src="http://heads.freshcoal.com/3d/3d.php?headOnly=true&aa=true&user='.$url.'"/></center>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="breadcrumb">
    <center><li class="active">&copy; 2015 <a href="http://crushedpixel.eu" target="_blank">CrushedPixel</a> &amp; <a href="http://thedestruc7i0n.ca" target="_blank">TheDestruc7i0n</a></li></center>
</ul>

</body>
</html>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.2.0/ZeroClipboard.min.js"></script>
<script src="/skull/bootstrap/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="/skull/bootstrap/js/main.js"></script>

