<?
function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function post($ch, $fields) {
    //url-ify the data for the POST
    $fields_string = "";
    foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
    rtrim($fields_string, '&');

    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

}

function set_skin($username, $password, $skin) {
    $login_url = "https://minecraft.net/login";
    $skin_url = "https://minecraft.net/profile/skin";

    //first, login to Minecraft.net
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $login_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "/dev/null");
    curl_setopt($ch,CURLOPT_HEADER,true);

    $fields = array(
        "username" => urlencode($username),
        "password" => urlencode($password)
    );

    post($ch, $fields);
    curl_exec($ch);

    //Then, load the profile page to retrieve authenticityToken
    curl_setopt($ch,CURLOPT_URL, $skin_url);
    $result = curl_exec($ch);

    $token = get_string_between($result, 'name="authenticityToken" value="', '">');

    //finally, post the skin update
    $headers = array("Content-Type:multipart/form-data"); // cURL headers for file uploading
    $fields = array("model" => "steve", "skin" => "@$skin", "authenticityToken" => $token);

    $options = array(
        CURLOPT_URL => $skin_url,
        CURLOPT_HEADER => true,
        CURLOPT_POST => 1,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_POSTFIELDS => $fields,
        CURLOPT_RETURNTRANSFER => true
    ); // cURL options

    curl_setopt_array($ch, $options);

    $result = urldecode(curl_exec($ch));

    curl_close($ch);

    if(strlen(strstr($result, "success=Your skin has been changed! It will take a couple of seconds for it to update.")) > 0) {
        return true;
    } else {
        return false;
    }
}


?>