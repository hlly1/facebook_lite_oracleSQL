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
	$user = $_SESSION['email'];
	$query = "SELECT * FROM USERS WHERE EMAIL=:email";

	$stid = oci_parse($conn, $query);

	oci_bind_by_name($stid, ':email', $user);
	oci_execute($stid);

	$array = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS);
	$userid = $array['USER_ID'];

	$getPosts = "SELECT POST_ID, CONTENT FROM POST WHERE USER_ID=:userid ORDER BY POST_ID DESC";
	

	$stid = oci_parse($conn, $getPosts);

	oci_bind_by_name($stid, ':userid', $userid);

	oci_execute($stid);	

}

?>

<html>
<header class="navbar navbar-dark navColor">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<title>Facebook-Lite | Assignment 1</title>
<link href="css/custom.css" rel="stylesheet">

      <div class="container">
        <a class="navTitle" href="home.php">facebook</a>
        <a href="logout.php" class="text-right btn btn-outline-warning">Logout</a>
      </div>


			<!-- Nav Bar -->
<style>
.emoji {
  font-family: Segoe UI Emoji, Segoe UI Symbol, Quivira, Symbola;
}
</style>

</header>

<body>



<div class="jumbotron text-center">
	<div class="row">

		<div class="col-sm-3">
		<div class="card shadow">
			<div class="card-header text-left"><h5>Personal Details: </h5> <input type="submit" value="edit" form="userEdit" class="btn btn-outline-primary"> <br>

					<form action="deleteUser.php" method="POST" id = "userDelete" onsubmit = "return confirmDelete()" >
						<input type="hidden" name = "deleteUserID" value="<?php echo $userid?>" form="userDelete">
						<input type="submit" class="btn btn-outline-danger" value="Delete my Account" form="userDelete">
					</form>

			</div>
				<div class="card-body">
					<ul class="list-group list-group-flush">
			<?php 
				
				echo "<div class = 'text-left'><label>Email: </label><br><li class='list-group-item'>".$array['EMAIL']."</li>";
				echo "<label>Full Name: </label><br><li class='list-group-item'>".$array['F_NAME']."</li>";
				echo "<label>Screen Name: </label><br><li class='list-group-item'>".$array['S_NAME']."</li>";
				echo "<label>Gender: </label><br><li class='list-group-item'>".$array['GENDER']."</li>";
				echo "<label>Date of Brith: </label><br><li class='list-group-item'>".$array['DOB']."</li>";
				echo "<label>Status: </label><br><li class='list-group-item'>".$array['STATU']."</li>";
				echo "<label>Location: </label><br><li class='list-group-item'>".$array['LOCATION']."</li>";
				if($array["V_LV"] == 0){
					echo "<label>Who can see my posts?: </label><br><li class='list-group-item'>Everyone can watch your posts.</li>";
				}elseif ($array["V_LV"] == 1) {
					echo "<label>Who can see my posts?: </label><br><li class='list-group-item'>Only your friends can watch your posts.</li>";
				}elseif ($array["V_LV"] == 2) {
					echo "<label>Who can see my posts?: </label><br><li class='list-group-item'>No one but you can watch your posts.</li>";
				}
			?>
				</ul>
				</div>
			</div>

		</div>

		<div class="col-sm-6">
			<h2>Add your post here...</h2><br>
			<form action="addPosts.php" method="POST" id="postForm">
				<textarea class="form-control shadow" rows="5" name="post" form = "postForm" required></textarea>
				<input type="submit" class="btn btn-primary" form = "postForm">
			</form>

			<br>
			<h5>All Posts: </h5><br>


				<?php 
					
					$allPostID = getAllPostsID($conn);
					$allPostSize = sizeof($allPostID);
					for($p = 1; $p < $allPostSize; $p++){

						$postUserID = getPostUser($allPostID[$p], $conn);
						$userVlv = getUserLvByID($postUserID, $conn);

						$condition1 = ($userVlv == 0);
						$condition2 = checkFriendship($postUserID, $userid, $conn);
						$condition3 = ($postUserID == $userid && $userVlv == 2);
						$condition4 = ($userVlv == 1 && $userid == $postUserID);
						$condition5 = ($condition1 || ($condition2 && $userVlv == 1) || $condition3 || $condition4) && getUserNameByID($postUserID, $conn) !=null;
						if($condition5){
							$contentPost = getPostContentByID($allPostID[$p], $conn);
							echo "
								<div class='card shadow'>
									<div class = 'card-header text-left'><h5>[".getUserNameByID($postUserID, $conn)."] posted:</h5></div>
									<div class='card-body text-left'>".$contentPost."&nbsp&nbsp
									<input type = 'hidden' name = 'postIDforComment".$allPostID[$p]."' value = '".$allPostID[$p]."'>
										<button type = 'button' data-toggle='modal' data-target='#commentModal' class = 'btn-primary btnRadius' onClick = 'passID(this.value)' value = '".$allPostID[$p]."'>
											reply
										</button>";

										$allLikePost = getLikePost($allPostID[$p], $conn);
										$likePostNum = sizeof($allLikePost) - 1;
										$currentUserVoted = votedPost($userid, $allPostID[$p], $conn);
										if(!$currentUserVoted){
											echo "<button type = 'button' class = 'btn-primary btnRadius float-right' onClick = 'likePost(".$allPostID[$p].", ".$userid.")'>
														<span class=emoji>&#x1F44D Like  ".$likePostNum."</span>
													</button>";
										}else{
											echo "<button type = 'button' class = 'btn-danger btnRadius float-right' onClick = 'votedLike()'>
														<span class=emoji>&#x1F44D Liked  ".$likePostNum."</span>
													</button>";
										}

									echo "</div>";

									$allCommID = getAllCommApost($allPostID[$p], $conn);
									$allCommSize = sizeof($allCommID);

									for($c = 1; $c < $allCommSize; $c++){

										$replyerID = getUserIdByCommentId($allCommID[$c], $conn);
										$replyerLv = getUserLvByID($replyerID, $conn);
										$comment = getCommContentByID($allCommID[$c], $conn);



										$commCondition1 = ($replyerLv == 0);
										$commCondition2 = (checkFriendship($replyerID, $userid, $conn) && $replyerLv == 1) || ($replyerLv == 1 && $replyerID == $userid);
										$commCondition3 = ($replyerID == $userid && $replyerLv == 2);
										
										$parentCommentUserID = getUserIdByCommentId((int)commCommExist($allCommID[$c], $conn),$conn);
										
										$commCondition5I = ($replyerID == $userid && $replyerLv == 2) || ($replyerLv == 2 && $postUserID == $userid);

										$commCondition5II = ($replyerLv == 2 && $parentCommentUserID == $userid) || ($replyerID == $userid && $replyerLv == 2) || ($replyerLv == 2 && $parentCommentUserID == $userid);
										
										$combination1 = ($commCondition1 || $commCondition2 || $commCondition3 || $commCondition5I);
										$combination2 = ($commCondition1 || $commCondition2 || $commCondition3 || $commCondition5II);

										if(!commCommExist($allCommID[$c], $conn) && $combination1  && getUserNameByID($replyerID, $conn) !=null){
											echo "<li class='list-group-item text-left'>
											>>--[".getUserNameByID($replyerID, $conn)."] reply: ".$comment."&nbsp&nbsp<button type = 'button' data-toggle='modal' data-target='#commCommModal' class = 'btn-primary btnRadius' onClick = 'passCommID(this.value);getParentPostID(".$allPostID[$p].");' value = '".$allCommID[$c]."'>
												reply
											</button>";

											$allLikeComment = getLikeComment($allCommID[$c], $conn);
											$likeCommentNum = sizeof($allLikeComment) - 1;

											$currentUserVotedComm = votedComment($userid, $allCommID[$c], $conn);

											if(!$currentUserVotedComm){
												echo "<button type = 'button' class = 'btn-primary btnRadius float-right' onClick = 'likeComment(".$allCommID[$c].",".$userid.");'><span class=emoji>&#x1F44D Like  ".$likeCommentNum."</span></button>";

											}else{
												echo "<button type = 'button' class = 'btn-danger btnRadius float-right' onClick = 'votedLike();'><span class=emoji>&#x1F44D Liked  ".$likeCommentNum."</span></button>";
											}
											echo "</li>";
										}elseif($combination2 && getUserNameByID($replyerID, $conn) !=null){

											$subCommentUserName = getUserNameByID(getUserIdByCommentId($allCommID[$c], $conn), $conn);
											$parentCommentUserName = getUserNameByID(getUserIdByCommentId((int)commCommExist($allCommID[$c], $conn),$conn),$conn);
											if($parentCommentUserName){
												echo "<li class='list-group-item text-left'>
												>>>>---[".$subCommentUserName."] reply [".$parentCommentUserName."]: ".$comment."&nbsp&nbsp<button type = 'button' data-toggle='modal' data-target='#commCommModal' class = 'btn-primary btnRadius' onClick = 'passCommID(this.value);getParentPostID(".$allPostID[$p].");' value = '".$allCommID[$c]."'>
													reply
												</button>";

												$allLikeComment1 = getLikeComment($allCommID[$c], $conn);
												$likeCommentNum1 = sizeof($allLikeComment1) - 1;

												$currentUserVotedComm1 = votedComment($userid, $allCommID[$c], $conn);

												if(!$currentUserVotedComm1){
													echo "<button type = 'button' class = 'btn-primary btnRadius float-right' onClick = 'likeComment(".$allCommID[$c].",".$userid.");'><span class=emoji>&#x1F44D Like  ".$likeCommentNum1."</span></button>";

												}else{
													echo "<button type = 'button' class = 'btn-danger btnRadius float-right' onClick = 'votedLike();'><span class=emoji>&#x1F44D Liked  ".$likeCommentNum1."</span></button>";
												}
												echo "</li>";
											}

										}


							}
								echo "</div><br>";

						}

					}
					
				?>
			
		</div>

		<div class="col-sm-3">
			
			<div class="card shadow">
				<div class="card-header">
			    	<h5>Friendship</h5>
			  	</div>
			  	<div class="card-body">
					<?php
						//var_dump($flist);
					$listID = getFriendsID($userid, $conn);
					$names = getFriendsInfo($listID, $conn);
					
					if($connected){
						// var_dump($listID);
						$fcount = sizeof($names);
						for($x = 1; $x < $fcount; $x++){
							$senderFriendshipID = (int)$listID[$x];
							echo "<li class='list-group-item'>".$names[$x]."---(started at)---".getFriendsDate($senderFriendshipID, $userid, $conn)."</li>";
						}

					}
					else{
						echo "connection closed.";
					}
					?>
				</div>
			</div><br>


			<div class="card shadow">
				<div class="card-header">
			    	<h5>Add Friends by Email</h5>
			  	</div>

			  	<div class="card-body">
				  	<form action="friendApply.php" method="POST" id = "applyForm">
						<input type = "hidden" value = "<?php echo $userid ?>" name = "usera" form = "applyForm">
						<input type="email" name = "userbEmail" form = "applyForm" required>
						<input type="submit" value="Send Request" form = "applyForm" class="btn btn-primary" >
					</form>
				</div>
				<div class="card-header">
			    	<h5>Requests(to me):</h5>
			  	</div>

			  	<div class="card-body text-left">
				  	
						
						<?php

							$senderID = getRequests($userid, $conn);
							$senderAcceptID = getRequestsAccepted($userid, $conn);
							if($connected){
								
								$requestCount = sizeof($senderID);
								$requestCountII = sizeof($senderAcceptID);

								for($y = 1; $y < $requestCount; $y++){
										echo "<li class='list-group-item'><form action='accept.php' method='POST' id = 'accept".$senderID[$y]."'>
										<input type = 'hidden' value = '".$userid."' name = 'receiverID' form = 'accept".$senderID[$y]."'>
										[ ".getUserNameByID($senderID[$y], $conn)." ] wants to add you as a friend.
										<input type = 'hidden' value = '".$senderID[$y]."' name = 'senderID' form = 'accept".$senderID[$y]."'>
										<br><input type = 'submit' class = 'btn btn-primary' value = 'accpet' form = 'accept".$senderID[$y]."'>
										</form>

										<form action='reject.php' method='POST' id = 'reject".$senderID[$y]."'>
										<input type = 'hidden' value = '".$userid."' name = 'rejectID' form = 'reject".$senderID[$y]."'>
										<input type = 'hidden' value = '".$senderID[$y]."' name = 'rejectSenderID' form = 'reject".$senderID[$y]."'>
										<input type = 'submit' class = 'btn btn-danger' value = 'reject' form = 'reject".$senderID[$y]."'>
										</form>

										</li>";
								}

								for($q = 1; $q < $requestCountII; $q++){
										echo "<li class='list-group-item'>[ ".getUserNameByID($senderAcceptID[$q], $conn)." ] 
										is already your friend. <input class='btn btn-lg btn-success' value = 'accepted' disabled></li>";
								}


							}
						?>
						
					
				</div>

				<div class="card-header">
			    	<h5>Requests(from me):</h5>
			  	</div>

			  	<div class="card-body text-left">
				 
						<input type = "hidden" value = "<?php echo $userid ?>" name = "usera" form = "accept">
						<?php

							$requestII = getRequestsII($userid, $conn);

							if($connected){
								
								$requestCountII = sizeof($requestII);
								for($z = 1; $z < $requestCountII; $z++){
									echo "<li class='list-group-item'>Waiting [ ".getUserNameByID($requestII[$z], $conn)." ] for accepting your request.</li>";
								}
							}
						?>
						
				</div>


		  	</div>

		</div>

	</div>
</div>

<!-- ====================================================================================================================================================== -->

<div class="modal fade" id="commentModal" tabindex="-1" role="dialog" aria-labelledby="commentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Comment:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="addComment.php" method = "POST" id = "addComment">
          <div class="form-group">
            <label for="message-text" class="col-form-label">Comment:</label>
            <textarea class="form-control" id="message-text" form="addComment" name="newComment" required></textarea>
            <input type="hidden" value="" id="passedID" name="passedID" form="addComment">
            <input type="hidden" value="<?php echo $userid; ?>" id="u1dForCommPost" name="u1dForCommPost" form="addComment">

          </div>
        </form>


      </div>
      <div class="modal-footer">
        <input type="submit" form="addComment" class="btn btn-primary" value="Add a New Comment">

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="commCommModal" tabindex="-1" role="dialog" aria-labelledby="commCommModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New Comment:</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form action="addCommentComment.php" method = "POST" id = "addCommentComment">
          <div class="form-group">
            <label for="message-text" class="col-form-label">Comment:</label>
            <textarea class="form-control" id="message-text" form="addCommentComment" name="newCommentComment" required></textarea>
            <input type="hidden" id="commCommID" name="commCommID" form="addCommentComment">
            <input type="hidden" value="" id="parentPost" name="parentPost" form="addCommentComment">
            <input type="hidden" value="<?php echo $userid; ?>" id="u1dForCommComm" name="u1dForCommComm" form="addCommentComment">
          </div>
        </form>

      </div>
      <div class="modal-footer">
        <input type="submit" form="addCommentComment" class="btn btn-primary" value="Add a New Comment">

      </div>
    </div>
  </div>
</div>

<!-- ====================================================================================================================================================== -->


<form action="userEdit.php", method="POST" id = "userEdit">
	<input type = "hidden" value="<?php echo $userid ?>" form = "userEdit" name = "edit1d">
</form>

<form action="addLikePost.php" method="POST" id = "likePost">
	<input type="hidden" name = "postLikeUserID" id = "postLikeUserID" value="" form="likePost">
	<input type="hidden" name = "postIDlike" id = "postIDlike" value="" form = "likePost">
</form>

<form action="addLikeComment.php" method="POST" id = "likeComment">
	<input type="hidden" name = "commLikeUserID" id = "commLikeUserID" value="" form="likeComment">
	<input type="hidden" name = "commIDlike" id = "commIDlike" value="" form="likeComment">
</form>

<!-- ====================================================================================================================================================== -->
<script language="javascript">
function passID(ID){
	console.log(ID);
	document.getElementById("passedID").value = ID;
}

function passCommID(commID){
	console.log(commID);
	document.getElementById("commCommID").value = commID;

}

function getParentPostID(salt){
	console.log(salt);
	document.getElementById("parentPost").value = salt;
}

function likePost(postid, userid){
	// alert("Postid: "+postid+"_____Userid: "+userid);
	document.getElementById("postLikeUserID").value = userid;
	document.getElementById("postIDlike").value = postid;
	document.getElementById("likePost").submit();

}

function likeComment(commentid, userid){
	// alert("commentid: "+commentid+"_____Userid: "+userid);
	document.getElementById("commLikeUserID").value = userid;
	document.getElementById("commIDlike").value = commentid;
	document.getElementById("likeComment").submit();

}


function votedLike(){

	alert("You can only vote it once!");

}

function confirmDelete(){
	var confirmDelete = confirm("WARNING: ARE YOU SURE TO DELETE YOUR ACCOUNT?");
	if (confirmDelete == true){

		return true;

	}else{

		return false;
	}
}

</script>




</body>
</html>
<?php oci_close($conn); ?>