<?php
require 'connection.php';
require 'tools.php';
session_start();
connection();

$deleteUserID = $_POST['deleteUserID'];

// echo $deleteUserID;

deleteUserLikeComment($deleteUserID, $conn);

$allUserCommentID = getAllUserComments($deleteUserID, $conn);
$allUserCommentSize = sizeof($allUserCommentID);

for ($y = 1; $y<$allUserCommentSize ; $y++) {
	deleteLikeCommentByID($allUserCommentID[$y], $conn);
}


$getAllUserPostID = getAllUserPosts($deleteUserID, $conn);

$allDPostID = getAllUserComments($deleteUserID, $conn);
$allDPostSize = sizeof($allDPostID);

for($dp = 1; $dp < $allDPostSize; $dp++){

	deleteCommentByPostID($allDPostID[$dp], $conn);
}

deleteCommCommNotNull($deleteUserID, $conn);

$getCommAgain = getAllUserComments($deleteUserID, $conn);

for($a=1; $a<sizeof($getCommAgain); $a++){
	// echo $getCommAgain[$a];
	$commentRelatedLike = getCommCommID($getCommAgain[$a], $conn);
	for($b=1;$b<sizeof($commentRelatedLike);$b++){
		// echo $commentRelatedLike[$b];
		deleteLikeCommentByID($commentRelatedLike[$b], $conn);

	}

}

deleteUserLikePost($deleteUserID, $conn);
cutRelationComment($deleteUserID, $conn);
cutRelationPost($deleteUserID, $conn);

deleteUserApply($deleteUserID, $conn);
deleteUserFriendship($deleteUserID, $conn);

deleteUser($deleteUserID, $conn);

session_destroy();

header("Location: index.php");




?>