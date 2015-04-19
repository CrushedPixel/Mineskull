<?php
function curl_get_file_size( $url ) {
    // Assume failure.
    $result = -1;

    $curl = curl_init( $url );

    // Issue a HEAD request and follow any redirects.
    curl_setopt( $curl, CURLOPT_NOBODY, true );
    curl_setopt( $curl, CURLOPT_HEADER, true );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );

    $data = curl_exec( $curl );
    curl_close( $curl );

    if( $data ) {
        $content_length = "unknown";
        $status = "unknown";

        if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
            $status = (int)$matches[1];
        }

        if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
            $content_length = (int)$matches[1];
        }

        // http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
        if( $status == 200 || ($status > 300 && $status <= 308) ) {
            $result = $content_length;
        }
    }

    return $result;
}

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

if(isset($_POST["fileURL"])) {
    $fileURL = $_POST["fileURL"];
}

foreach($_FILES as $file) {
    $tmp = $file['tmp_name'];

    $unlink = false;

    if(strlen($tmp) == 0) {
        if(!isset($fileURL)) {
            header("Location: http://crushedpixel.eu/skull?error=3");
            exit;
        }

        $size = curl_get_file_size($fileURL);
        if($size <= 0 or $size > 100*1024) {
            header("Location: http://crushedpixel.eu/skull?error=0");
        }

        $temp_file = tempnam(sys_get_temp_dir(), 'skin');
        file_put_contents($temp_file, file_get_contents($fileURL));

        $tmp = $temp_file;
        $unlink = true;
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


        $properties = get_skull($uuid);

        $command = "/give @p minecraft:skull 1 3 {SkullOwner:{Id:".gen_uuid().",Properties:{textures:[{Signature:". $properties["Signature"]
            .",Value:" . $properties["Value"] ."}]}}}";

        $decode = base64_decode($properties["Value"]);
        $arr = json_decode($decode, true);

        $url = $arr["textures"]["SKIN"]["url"];

        $sql = "INSERT INTO generated(hash,command,duplicate,url) VALUES(?,?,0,?)";
        $stmt = $con->prepare($sql);
        $stmt->bindValue(1, $hash);
        $stmt->bindValue(2, $command);
        $stmt->bindValue(3, $url);
        $stmt->execute();

        $id = $con->lastInsertId();

        if($unlink) {
            unlink($tmp);
        }

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