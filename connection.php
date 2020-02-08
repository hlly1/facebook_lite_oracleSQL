<?php

function connection(){

global $conn;
global $connected;
$username = 's3694521';
$password = 'tclh13795';
$servername = 'talsprddb01.int.its.rmit.edu.au';
$servicename = 'CSAMPR1.ITS.RMIT.EDU.AU';
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