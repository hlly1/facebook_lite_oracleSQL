<?php
require 'connection.php';
require 'tools.php';

connection();


$userBID = $_POST['rejectID'];
$userAID = $_POST['rejectSenderID'];

echo "currentUser: ".$userBID."       senderID: ".$userAID;

$deleteApply = "DELETE FROM APPLY WHERE USERA_ID = :userAID AND USERB_ID = :userBID";

$stid = oci_parse($conn, $deleteApply);

oci_bind_by_name($stid, ':userAID', $userAID);
oci_bind_by_name($stid, ':userBID', $userBID);

oci_execute($stid);
header("Location: home.php");

?>