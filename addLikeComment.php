<?php
require 'connection.php';
connection();

$commentid = $_POST['commIDlike'];
$userid = $_POST['commLikeUserID'];
// echo "USERID: ".$userid."    postid: ".$commentid;
$insertCommLike = "INSERT INTO LIKECOMMENT VALUES(LIKECOMMENT_SEQ.NEXTVAL, :commentid, :userid, TO_DATE(SYSDATE, 'DD-MON-YYYY'))";

$stid = oci_parse($conn, $insertCommLike);
oci_bind_by_name($stid, ':commentid', $commentid);
oci_bind_by_name($stid, ':userid', $userid);
oci_execute($stid);

header("Location: home.php");



?>