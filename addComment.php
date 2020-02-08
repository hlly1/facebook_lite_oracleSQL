<?php

require 'connection.php';
connection();
$replyer = $_POST['u1dForCommPost'];
$content = $_POST['newComment'];
$postid = $_POST['passedID'];

$insertCommentPost = "INSERT INTO COMMENTS VALUES(COMMENTS_SEQ.NEXTVAL, :replyer, :postid, :content, SYSTIMESTAMP, NULL)";

$stid = oci_parse($conn, $insertCommentPost);

oci_bind_by_name($stid, ':replyer', $replyer);
oci_bind_by_name($stid, ':content', $content);
oci_bind_by_name($stid, ':postid', $postid);
oci_execute($stid);
header("Location: home.php");

?>