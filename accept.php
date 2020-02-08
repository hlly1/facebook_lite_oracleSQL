<?php
require 'connection.php';
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

connection();

if($connected){
	//receiver to accept.
	$receiverID = $_POST['receiverID'];
	$senderID = $_POST['senderID'];

	insertFriend($senderID, $receiverID, $conn);

	$acceptRequest = "UPDATE APPLY SET ACCEPTED = 1 WHERE USERA_ID = :senderID AND USERB_ID = :receiverID";
	$stid = oci_parse($conn, $acceptRequest);
	oci_bind_by_name($stid, ':senderID', $senderID);
	oci_bind_by_name($stid, ':receiverID', $receiverID);
	oci_execute($stid);
	header("Location: home.php");

}



?>