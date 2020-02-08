<?php

function connection(){

global $conn;
global $connected;
$username = 's********';
$password = '*********';
$servername = '********';
$servicename = '**********';
$connection = $servername."/".$servicename;

$conn = oci_connect($username, $password, $connection);
$connected = true;
if(!$conn)
{
	$connected = flase;
    $e = oci_error();
    header("Location: signup.html");
    echo "<script>alter('Request Refused: Connection Error!')</script>";
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    exit;
}

}



?>