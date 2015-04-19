<?php

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
        mt_rand( 0, 0xffff ),
        mt_rand( 0, 0x0fff ) | 0x3000,
        mt_rand( 0, 0x3fff ) | 0x8000,
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function get_skull($uuid) {
    $url = "https://sessionserver.mojang.com/session/minecraft/profile/".$uuid."?unsigned=false";
    $result = json_decode(file_get_contents($url), true);

    return array("Signature" => $result["properties"][0]["signature"], "Value" => $result["properties"][0]["value"]);
}


?>