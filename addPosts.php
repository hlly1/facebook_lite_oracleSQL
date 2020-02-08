<?php
require 'connection.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

connection();
if($connected){
	$useremail = $_SESSION['email'];
	$content = $_POST['post'];
	

	$getUserID = "SELECT USER_ID FROM USERS WHERE EMAIL=:useremail";
	$stid = oci_parse($conn, $getUserID);

	oci_bind_by_name($stid, ':useremail', $useremail);
	oci_execute($stid);

	$array = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
	$uid = $array['USER_ID'];
	echo "------------".$uid."------------------";


	$postInsert = "INSERT INTO POST VALUES(POST_SEQ.NEXTVAL, :ud, :cot, SYSTIMESTAMP)";
	$stid = oci_parse($conn, $postInsert);

	oci_bind_by_name($stid, ':ud', $uid);
	oci_bind_by_name($stid, ':cot', $content);
	oci_execute($stid);

	header("Location: home.php");

}

?>