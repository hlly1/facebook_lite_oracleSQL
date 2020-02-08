<?php
require 'connection.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

connection();

if($connected){

	$user1d = $_POST['user1d'];
	$sname = $_POST['sname'];
	$status = $_POST['status'];
	$location = $_POST['location'];
	$v_lv = (int)$_POST['v_lv'];

	$update = "UPDATE USERS SET S_NAME = :sname, STATU = :status, LOCATION = :location, V_LV = :v_lv WHERE USER_ID = :user1d";

	$stid = oci_parse($conn, $update);

	oci_bind_by_name($stid, ':user1d', $user1d);
	oci_bind_by_name($stid, ':sname', $sname);
	oci_bind_by_name($stid, ':status', $status);
	oci_bind_by_name($stid, ':location', $location);
	oci_bind_by_name($stid, ':v_lv', $v_lv);

	oci_execute($stid);
	header("Location: home.php");


}


?>