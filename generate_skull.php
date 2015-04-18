<?php
REQUIRE_ONCE "database_connection.php";
REQUIRE_ONCE "api_utils.php";
REQUIRE_ONCE "change_skin.php";
REQUIRE_ONCE "get_skull.php";

global $con;

$time = time();
$ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT * FROM traffic WHERE ip=?";
$stmt = $con->prepare($sql);
$stmt->bindParam(1, $ip);
$stmt->execute();

$used = 0;
while($row = $stmt->fetch()) {
    $used = $row["used"];
    break;
}

if($used > $time-60) { //Limit the usage for 1 generation per minute/ip
    header("Location: http://crushedpixel.eu/skull?error=2");
    exit;
}

foreach($_FILES as $file) {
    $tmp = $file['tmp_name'];

    if(strlen($tmp) == 0) {
        header("Location: http://crushedpixel.eu/skull?error=3");
        exit;
    }

    if ($file["size"] > 100 * 1024) { //if more than 100KB, probably no valid skin
        header("Location: http://crushedpixel.eu/skull?error=0");
    }

    $hash = md5_file($tmp);
    $sql = "SELECT * FROM generated WHERE hash=?";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(1, $hash);
    $stmt->execute();

    while($row = $stmt->fetch()) {
        $sql = "UPDATE generated SET duplicate=duplicate+1 WHERE hash=?";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $hash);
        $stmt->execute();

        header("Location: http://crushedpixel.eu/skull?id=".$row["id"]);
        exit;
    }

    $sql = "SELECT * FROM accounts WHERE used<? LIMIT 1";
    $stmt = $con->prepare($sql);
    $stmt->bindValue(1, $time - 30); //only allow accounts that haven't been used in the past 30 seconds
    $stmt->execute();

    $found = false;

    while($row = $stmt->fetch()) {
        $username = $row["username"];
        $uuid = $row["UUID"];
        $success = set_skin($row["username"], $row["password"], realpath($tmp));
        if($success === false) {
            header("Location: http://crushedpixel.eu/skull?error=0");
            exit;
        }
        $found = true;
        break;
    }

    if($found) {
        $sql = "UPDATE accounts SET used=? WHERE username=?";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $time);
        $stmt->bindParam(2, $username);
        $stmt->execute();

        $sql = "INSERT INTO stats VALUES(?)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $time);
        $stmt->execute();

        $sql = "DELETE FROM traffic WHERE ip=?";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $ip);
        $stmt->execute();

        $sql = "INSERT INTO traffic VALUES(?,?)";
        $stmt = $con->prepare($sql);
        $stmt->bindParam(1, $ip);
        $stmt->bindParam(2, $time);
        $stmt->execute();

        $sql = "INSERT INTO generated(hash,command,duplicate) VALUES(?,?,0)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $hash);
        $stmt->bindValue(2, get_skull($uuid));
        $stmt->execute();

        $id = $con->lastInsertId();

        header("Location: http://crushedpixel.eu/skull?id=".$id);
        exit;
    } else {
        header("Location: http://crushedpixel.eu/skull?error=1");
        exit;
    }

    break;
}

header("Location: http://crushedpixel.eu/skull?error=0");

?>