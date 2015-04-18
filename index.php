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
    .invis_button {
        opacity: 1;
    }
    .fileUpload {
        position: relative;
        overflow: hidden;
        margin: 10px;
    }
    .fileUpload input.upload {
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        padding: 0;
        font-size: 20px;
        cursor: pointer;
        opacity: 0;
        filter: alpha(opacity=0);
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
            </ul>

        </div>
    </div>
</div>

<div class="container bcon">
    <div class="row">
        <div class="col-lg-8 col-sm-offset-2">
            <div class="page-header">
                <div class="jumbotron">
                    <h1><center>Custom Skull Generator</center></h1>
                    <br>
                    <center>
                        <form action="/skull/generate_skull.php" method="post" enctype="multipart/form-data">
                            <input id="uploadFile" placeholder="Choose File" disabled="disabled" />
                            <div class="fileUpload btn btn-primary">
                                <span>Choose Skin</span>
                                <input type="file" class="upload" name="fileToUpload" id="uploadBtn"/>
                            </div>
                            <input type="submit" class="fileUpload btn btn-primary" value="Generate Skull" name="submit">
                        </form>
                    </center>
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
                                $err_msg = "Invalid skin uploaded!";
                                break;
                            case 1:
                                $err_msg = "Sorry, we don't have enough resources to process your request. Please try again in a couple of seconds.";
                                break;
                            case 2:
                                $err_msg = "You can only convert one player head per minute. Please try again in a couple of seconds.";
                                break;
                            default:
                                $err_msg = "An unknown error occured.";
                                break;
                        }
                        echo "<p>".$err_msg."</p>";
                    } elseif(isset($_GET["id"])) {
                        REQUIRE_ONCE "database_connection.php";
                        global $con;

                        $sql = "SELECT * FROM generated WHERE id=?";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(1, $_GET["id"]);
                        $stmt->execute();

                        $command = "";

                        while($row = $stmt->fetch()) {
                            $command = $row["command"];
                            break;
                        }

                        echo '<div class="form-group">';
                        echo '<label class="control-label">Copy Command</label>
										  <div class="input-group col-xs-6">';
                        echo '<input class="form-control copyme" id="focusedInput" type="text" value="'.$command.'" readonly="readonly" style="width:600px;">';
                        echo '<span class="input-group-btn">';
                        echo '<button class="btn btn-default copyable" type="button" data-clipboard-text="'.$command.'">Copy</button>';
                        echo '</span>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.2.0/ZeroClipboard.min.js"></script>
<script src="/skull/bootstrap/js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
    var client = new ZeroClipboard($(".copyable"));
</script>

