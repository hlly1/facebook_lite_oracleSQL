<?php

function getFriendsID($uuu, $conn){
	

	$getFriendsID = "SELECT USERA_ID FROM FRIENDSHIP WHERE USERB_ID = :uuu UNION SELECT USERB_ID FROM FRIENDSHIP WHERE USERA_ID = :uuu";

	$stid = oci_parse($conn, $getFriendsID);

	oci_bind_by_name($stid, ':uuu', $uuu);
	oci_execute($stid);

	$listID[] = null;

	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($listID, $item);
		}
	}

	return $listID;
}

function getFriendsInfo($listID, $conn){

	$names[] = null;
	$fcount = sizeof($listID);

	for($x = 1; $x < $fcount; $x++){

		$u1d = $listID[$x];

		$getNames = "SELECT S_NAME FROM USERS WHERE USER_ID = :u1d";
		$stid = oci_parse($conn, $getNames);

		oci_bind_by_name($stid, ':u1d', $u1d);
		oci_execute($stid);
		$row = oci_fetch_array($stid);
		array_push($names, $row[0]);
	}

	return $names;
}

function getFriendsDate($senderID, $receiverID, $conn){
	

	// $getFriendsDate = "SELECT CREATED_AT FROM FRIENDSHIP WHERE USERB_ID = :uuu UNION ALL SELECT CREATED_AT FROM FRIENDSHIP WHERE USERA_ID = :uuu";
	$getFriendsDate = "SELECT CREATED_AT FROM FRIENDSHIP WHERE USERB_ID = :receiverID AND USERA_ID = :senderID 
					UNION 
					SELECT CREATED_AT FROM FRIENDSHIP WHERE USERA_ID = :receiverID AND USERB_ID = :senderID";

	$stid = oci_parse($conn, $getFriendsDate);

	// oci_bind_by_name($stid, ':uuu', $uuu);
	oci_bind_by_name($stid, ':senderID', $senderID);
	oci_bind_by_name($stid, ':receiverID', $receiverID);
	oci_execute($stid);

	$fdate = oci_fetch_array($stid);
	return $fdate[0];
}

function getUserNameByID($u1d, $conn){

	$getUserName = "SELECT S_NAME FROM USERS WHERE USER_ID=:u1d";
    $stid = oci_parse($conn, $getUserName);
    oci_bind_by_name($stid, ':u1d', $u1d);
    oci_execute($stid);
    $name = oci_fetch_array($stid);
    return $name[0];
}

function getRequests($receiver, $conn){

	$getRequests = "SELECT USERA_ID FROM APPLY WHERE USERB_ID = :receiver AND ACCEPTED = 0";

	$receiverID[] = null;

    $stid = oci_parse($conn, $getRequests);
    oci_bind_by_name($stid, ':receiver', $receiver);
    oci_execute($stid);

   	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($receiverID, $item);
		}
	}
    return $receiverID;
}


function getRequestsII($sender, $conn){

	$getRequestsII = "SELECT USERB_ID FROM APPLY WHERE USERA_ID = :sender AND ACCEPTED = 0";

	$requestII[] = null;

    $stid = oci_parse($conn, $getRequestsII);
    oci_bind_by_name($stid, ':sender', $sender);
    oci_execute($stid);

   	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($requestII, $item);
		}
	}
    return $requestII;
}

function applyValidation($sender, $receiver, $conn){

	$getApply = "SELECT USERA_ID FROM APPLY WHERE USERB_ID = :receiver AND ACCEPTED = 0 UNION SELECT USERB_ID FROM APPLY WHERE USERA_ID = :receiver AND ACCEPTED = 0";

	$applyVali[] = null;

    $stid = oci_parse($conn, $getApply);
    oci_bind_by_name($stid, ':receiver', $receiver);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($applyVali, $item);
		}
	}

	$applied = in_array($sender, $applyVali);

	return $applied;
}

function insertFriend($senderID, $receiverID, $conn){

	$insertFriendship = "INSERT INTO FRIENDSHIP VALUES(:senderID, :receiverID, TO_DATE(SYSDATE, 'DD-MON-YYYY'))";

    $stid = oci_parse($conn, $insertFriendship);
    oci_bind_by_name($stid, ':senderID', $senderID);
    oci_bind_by_name($stid, ':receiverID', $receiverID);
    oci_execute($stid);

}

function getRequestsAccepted($receiver, $conn){

	$getRequestsAccepted = "SELECT USERA_ID FROM APPLY WHERE USERB_ID = :receiver AND ACCEPTED = 1 UNION SELECT USERB_ID FROM APPLY WHERE USERA_ID = :receiver AND ACCEPTED = 1";

	$receiverAcceptedID[] = null;

    $stid = oci_parse($conn, $getRequestsAccepted);
    oci_bind_by_name($stid, ':receiver', $receiver);
    oci_execute($stid);

   	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($receiverAcceptedID, $item);
		}
	}
    return $receiverAcceptedID;
}



function getAllPostsID($conn){

	$getAllPosts = "SELECT POST_ID FROM POST ORDER BY T_TAMP DESC";

	$allPostID[] = null;

    $stid = oci_parse($conn, $getAllPosts);

    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($allPostID, (int)$item);
		}
	}

	return $allPostID;
	// return postid list.....
}

function getPostUser($post1d, $conn){
	$getPostUser = "SELECT USER_ID FROM POST WHERE POST_ID = :post1d";

    $stid = oci_parse($conn, $getPostUser);
	oci_bind_by_name($stid, ':post1d', $post1d);
    oci_execute($stid);
    $row = oci_fetch_array($stid);
    return (int)$row[0];
	// return userid....
}

function getUserLvByID($user1d, $conn){

	$getLv = "SELECT V_LV FROM USERS WHERE USER_ID = :user1d";
    $stid = oci_parse($conn, $getLv);
	oci_bind_by_name($stid, ':user1d', $user1d);
    oci_execute($stid);

    $row = $row = oci_fetch_array($stid);
    return (int)$row[0];
	// return 0, 1, 2...

}

function checkFriendship($postUser, $currentUser, $conn){

	$checkFriend = "SELECT USERA_ID FROM FRIENDSHIP WHERE USERB_ID = :currentUser UNION SELECT USERB_ID FROM FRIENDSHIP WHERE USERA_ID = :currentUser ";

	$stid = oci_parse($conn, $checkFriend);

	oci_bind_by_name($stid, ':currentUser', $currentUser);
	oci_execute($stid);

	$friendList[] = null;

	while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($friendList, $item);
		}
	}

	$isFriend = in_array($postUser, $friendList);


	return $isFriend;

}

function getPostContentByID($postID, $conn){

	$getContent = "SELECT CONTENT FROM POST WHERE POST_ID = :postID";

	$stid = oci_parse($conn, $getContent);
	oci_bind_by_name($stid, ':postID', $postID);
	oci_execute($stid);

	$row = oci_fetch_array($stid);

	return $row[0];
}

function getPostIdByCommentId($commentID, $conn){

	$getPostID = "SELECT POST_ID FROM COMMENTS WHERE COMMENT_ID = :commentID";

	$stid = oci_parse($conn, $getPostID);
	oci_bind_by_name($stid, ':commentID', $commentID);
	oci_execute($stid);

	$row = oci_fetch_array($stid);

	return (int)$row[0];

}

function getUserIdByCommentId($commentID, $conn){

	$getUserID = "SELECT USER_ID FROM COMMENTS WHERE COMMENT_ID = :commentID";

	$stid = oci_parse($conn, $getUserID);
	oci_bind_by_name($stid, ':commentID', $commentID);
	oci_execute($stid);

	$row = oci_fetch_array($stid);

	return (int)$row[0];

}

function getAllCommApost($postid, $conn){

	$getAllCommApost = "SELECT COMMENT_ID FROM COMMENTS WHERE POST_ID = :postid ORDER BY T_TAMP ASC";

	$allCommID[] = null;

    $stid = oci_parse($conn, $getAllCommApost);
	oci_bind_by_name($stid, ':postid', $postid);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($allCommID, (int)$item);
		}
	}

	return $allCommID;

}

function getCommContentByID($commID, $conn){

	$getContent = "SELECT CONTENT FROM COMMENTS WHERE COMMENT_ID = :commID";

	$stid = oci_parse($conn, $getContent);
	oci_bind_by_name($stid, ':commID', $commID);
	oci_execute($stid);

	$row = oci_fetch_array($stid);

	return $row[0];
}

function commCommExist($commentid, $conn){

	$check = "SELECT COMMENT_COMMENT_ID FROM COMMENTS WHERE COMMENT_ID = :commentid";
	$stid = oci_parse($conn, $check);
	oci_bind_by_name($stid, ':commentid', $commentid);
	oci_execute($stid);
	$row = oci_fetch_array($stid);
	return $row[0];
}


function getLikePost($postID, $conn){

	$getLikePost = "SELECT LIKEP_ID FROM LIKEPOST WHERE POST_ID = :postID";
	$allLikePost[] = null;

    $stid = oci_parse($conn, $getLikePost);
	oci_bind_by_name($stid, ':postID', $postID);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($allLikePost, (int)$item);
		}
	}

	return $allLikePost;
}

function votedPost($userid, $postid, $conn){

	$votedCheck = "SELECT POST_ID, USER_ID FROM LIKEPOST WHERE POST_ID=:postid AND USER_ID=:userid";

    $stid = oci_parse($conn, $votedCheck);

    oci_bind_by_name($stid, ':postid', $postid);
    oci_bind_by_name($stid, ':userid', $userid);

    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC);

    if($row){
    	return true;
    }else{
        return false;
    }


}


function getLikeComment($commID, $conn){

	$getLikeComment = "SELECT LIKE_ID FROM LIKECOMMENT WHERE COMMENT_ID = :commID";
	$allLikeComment[] = null;

    $stid = oci_parse($conn, $getLikeComment);
	oci_bind_by_name($stid, ':commID', $commID);
    oci_execute($stid);

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($allLikeComment, (int)$item);
		}
	}

	return $allLikeComment;
}

function votedComment($userid, $commentid, $conn){

	$votedCheck = "SELECT COMMENT_ID, USER_ID FROM LIKECOMMENT WHERE COMMENT_ID=:commentid AND USER_ID=:userid";

    $stid = oci_parse($conn, $votedCheck);

    oci_bind_by_name($stid, ':commentid', $commentid);
    oci_bind_by_name($stid, ':userid', $userid);

    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC);

    if($row){
    	return true;
    }else{
        return false;
    }

}

function deleteUserLikePost($userid, $conn){

	$deleteUserLikePost = "DELETE FROM LIKEPOST WHERE USER_ID = :userid";
	$stid = oci_parse($conn, $deleteUserLikePost);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);

}

function deleteUserLikeComment($userid, $conn){

	$deleteUserLikeComment = "DELETE FROM LIKECOMMENT WHERE USER_ID = :userid";
	$stid = oci_parse($conn, $deleteUserLikeComment);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);
	
}

function deleteUserApply($userid, $conn){

	$deleteUserApply = "DELETE FROM APPLY WHERE USERA_ID = :userid OR USERB_ID = :userid";
	$stid = oci_parse($conn, $deleteUserApply);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);
	
}

function deleteUserFriendship($userid, $conn){

	$deleteUserFriendship = "DELETE FROM FRIENDSHIP WHERE USERA_ID = :userid OR USERB_ID = :userid";
	$stid = oci_parse($conn, $deleteUserFriendship);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);
	
}

function deleteUser($userid, $conn){

	$deleteUser = "DELETE FROM USERS WHERE USER_ID = :userid";
	$stid = oci_parse($conn, $deleteUser);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);

}

function getAllUserComments($userid, $conn){

	$getAllUserComments = "SELECT COMMENT_ID FROM COMMENTS WHERE USER_ID = :userid";

	$stid = oci_parse($conn, $getAllUserComments);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);

	$allUserComments[] = null;

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($allUserComments, (int)$item);
		}
	}

	return $allUserComments;
}

function getCommCommID($comm, $conn){

	$getCommCommID = "SELECT COMMENT_ID FROM COMMENTS WHERE COMMENT_COMMENT_ID = :comm";

	$stid = oci_parse($conn, $getCommCommID);
	oci_bind_by_name($stid, ':comm', $comm);
	oci_execute($stid);

	$CommCommID[] = null;

    $row = oci_fetch_array($stid);
	
	return (int)$row[0];
}


function deleteLikeCommentByID($commentid, $conn){

	$deleteLikeComm = "DELETE FROM LIKECOMMENT WHERE COMMENT_ID = :commentid";
	$stid = oci_parse($conn, $deleteLikeComm);
	oci_bind_by_name($stid, ':commentid', $commentid);
	oci_execute($stid);

}

function getAllUserPosts($userid, $conn){

	$getAllUserPosts = "SELECT POST_ID FROM POST WHERE USER_ID = :userid";

	$stid = oci_parse($conn, $getAllUserPosts);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);
	

	$getAllUserPost[] = null;

    while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
		foreach ($row as $item) {
			array_push($getAllUserPost, (int)$item);
		}
	}

	return $getAllUserPost;
}

function deleteCommentByPostID($postid, $conn){
	$deleteCommentByPostID = "DELETE FROM COMMENTS WHERE POST_ID = :postid";
	$stid = oci_parse($conn, $deleteCommentByPostID);
	oci_bind_by_name($stid, ':postid', $postid);
	oci_execute($stid);
}

function deleteCommCommNotNull($userid, $conn){
	$deleteCommCommNotNull = "DELETE FROM COMMENTS WHERE USER_ID = :userid AND COMMENT_COMMENT_ID IS NOT NULL";
	$stid = oci_parse($conn, $deleteCommCommNotNull);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);
}


function cutRelationComment($userid, $conn){

	$cutRelationComment = "UPDATE COMMENTS SET USER_ID = NULL WHERE USER_ID = :userid";
	$stid = oci_parse($conn, $cutRelationComment);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);

}

function cutRelationPost($userid, $conn){

	$cutRelationPost = "UPDATE POST SET USER_ID = NULL WHERE USER_ID = :userid";
	$stid = oci_parse($conn, $cutRelationPost);
	oci_bind_by_name($stid, ':userid', $userid);
	oci_execute($stid);

}















































