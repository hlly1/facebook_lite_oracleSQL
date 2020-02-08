<?php
require 'connection.php';
connection();
$userid = $_POST['postLikeUserID'];
$postid = $_POST['postIDlike'];

// echo "USERID: ".$userid."    postid: ".$postid;

$insertLikePost = "INSERT INTO LIKEPOST VALUES(LIKEPOST_SEQ.NEXTVAL, :postid, :userid, TO_DATE(SYSDATE, 'DD-MON-YYYY'))";

$stid = oci_parse($conn, $insertLikePost);
oci_bind_by_name($stid, ':postid', $postid);
oci_bind_by_name($stid, ':userid', $userid);
oci_execute($stid);

header("Location: home.php");

?>