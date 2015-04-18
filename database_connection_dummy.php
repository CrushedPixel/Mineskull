<?php
$_db_host = "x";
$_db_username = "x";
$_db_password = "x";
$_db_database = "x";

function handle_errors ($error, $message, $filename, $line) {
    global $_print_errors;
    echo "<b>$message</b> in line $line of <i>$filename</i></body></html>";
    exit;
}
set_error_handler('handle_errors');

$con = new PDO("mysql:host=$_db_host;dbname=$_db_database" ,$_db_username,$_db_password);
?>
