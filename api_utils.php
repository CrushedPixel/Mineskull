<?php

function api_done($response) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

function api_error($err) {
    //Return other response code than 200
    http_response_code(400);
    api_done($err);
}

function argreq($name) {
    if (isset($_GET[$name]))
        return $_GET[$name];
    if (isset($_POST[$name]))
        return $_POST[$name];
    api_error(param_missing($name));
}

function argopt($name, $default) {
    if (isset($_GET[$name]))
        return $_GET[$name];
    if (isset($_POST[$name]))
        return $_POST[$name];
    return $default;
}

function generateAuthkey($length = 32) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function check_category($category) {
    global $INVALID_CATEGORY;
    if(is_numeric($category) === False or (int)$category < 0 or (int)$category > 2) {
        api_error($INVALID_CATEGORY);
    }
}

function check_array_length($array, $max, $param) {
    if(count($array) > $max) {
        api_error(too_many_values($param));
    }
}

function array_get($array, $key) {
    global $INVALID_FILE_UPLOAD;
    if(array_key_exists($key, $array)) {
        return $array[$key];
    } else {
        api_error($INVALID_FILE_UPLOAD);
    }
}

function readfile_chunked($filename) {
    $chunksize = 1*(1024*1024); // how many bytes per chunk
    $handle = fopen($filename, 'rb');
    if ($handle === false) {
        return false;
    }
    while (!feof($handle)) {
        $buffer = fread($handle, $chunksize);
        print $buffer;
        ob_flush();
        flush();
    }
    return fclose($handle);
}
?>
