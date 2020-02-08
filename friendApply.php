<?php
require 'connection.php';
require 'tools.php';
session_start();
if (empty($_SESSION['email']) || !isset($_SESSION['email'])){
	header("Location: index.php");
}

connection();

if($connected)
{


	$sender = $_POST['usera'];
	$receiveEmail = $_POST['userbEmail'];

	$existUser = "SELECT EMAIL FROM USERS WHERE EMAIL=:email";
    $stid = oci_parse($conn, $existUser);
    oci_bind_by_name($stid, ':email', $receiveEmail);
    oci_execute($stid);
    $row1 = oci_fetch_array($stid, OCI_ASSOC);


	$existFriend = "SELECT USER_ID FROM USERS WHERE EMAIL=:email";
    $stid = oci_parse($conn, $existFriend);
    oci_bind_by_name($stid, ':email', $receiveEmail);
    oci_execute($stid);
    $listID = getFriendsID($sender, $conn);

    $row2 = oci_fetch_array($stid);

    $friendshipExist = in_array($row2[0], $listID);
    $receiver = (int)$row2[0];

    $applied = applyValidation($sender, $receiver, $conn);


    if(!$row1){
    	header("Location: applyFail.php");
    }elseif ($friendshipExist) {
    	header("Location: applyFail.php");
    }elseif($applied){
    	header("Location: applyFail.php");
    }elseif ($sender == $receiver){
        header("Location: applyFail.php");
    }else{

		$insertRequest = "INSERT INTO APPLY VALUES(APPLY_SEQ.NEXTVAL, :user1, :user2, 0)";
		$stid = oci_parse($conn, $insertRequest);

		oci_bind_by_name($stid, ':user1', $sender);
		oci_bind_by_name($stid, ':user2', $receiver);
    	oci_execute($stid);
    	header("Location: home.php");
    }






}

?>