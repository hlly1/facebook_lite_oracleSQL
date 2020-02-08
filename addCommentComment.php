<?php
require 'connection.php';
connection();
$content = $_POST['newCommentComment'];
$replyer = $_POST['u1dForCommComm'];
$parentPostID = $_POST['parentPost'];
$parentCommentID = $_POST['commCommID'];


// echo "content: ".$content."-----------replyerID: ".$replyer."-----------parentPostID: ".$parentPostID."-----------------parentCommentID: ".$parentCommentID;

$insertComment = "INSERT INTO COMMENTS VALUES(COMMENTS_SEQ.NEXTVAL, :replyer, :parentPostID, :content, SYSTIMESTAMP, :parentCommentID)";

$stid = oci_parse($conn, $insertComment);

oci_bind_by_name($stid, ':replyer', $replyer);
oci_bind_by_name($stid, ':parentPostID', $parentPostID);
oci_bind_by_name($stid, ':content', $content);
oci_bind_by_name($stid, ':parentCommentID', $parentCommentID);
oci_execute($stid);
header("Location: home.php");










?>