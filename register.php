<?php

$username = 's3694521';
$password = 'tclh13795';
$servername = 'talsprddb01.int.its.rmit.edu.au';
$servicename = 'CSAMPR1.ITS.RMIT.EDU.AU';
$connection = $servername."/".$servicename;

$conn = oci_connect($username, $password, $connection);
if(!$conn)
{
    $e = oci_error();
    header("Location: signup.html");
    echo "<script>alter('Request Refused: Connection Error!')</script>";
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    exit;
}
else
{
	$email = $_POST['email'];
	$password = md5($_POST['password']);
	$fname = $_POST['fname'];
	$sname = $_POST['sname'];
	$gender = $_POST['gender'];
	$location = $_POST['address'];
	$dob = $_POST['dob'];


	$query1 = "SELECT EMAIL, PASSWORD FROM USERS WHERE EMAIL=:email";

    $stid = oci_parse($conn, $query1);

    oci_bind_by_name($stid, ':email', $email);
    

    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC);

    if($row){
        header("location: signup.html");
    }else{
 	// echo $email."\n";
	// echo $password."\n";
	// echo $fname."\n";
	// echo $sname."\n";
	// echo $gender."\n";
	// echo $location."\n";
	// echo "----------------".$dob."----------------\n";


    // $query =  'INSERT INTO USERS(USER_ID, EMAIL, PASSWORD, F_NAME, S_NAME, DOB, GENDER, STATU, LOCATION, V_LV) '.
    //    'VALUES(USERS_SEQ.NEXTVAL, '.$email.','.$password.','.$fname.','.$sname.','.$dob.','.$gender.',"default",'.$location.',0);';

    // $query =  "INSERT INTO USERS VALUES(USERS_SEQ.NEXTVAL,:email,:password,:fname,:sname, TO_DATE(:dob, 'YYYY-MM-DD','nls_date_language=american'),:gender,'default',:location,0)";

 	$query =  "INSERT INTO USERS VALUES(USERS_SEQ.NEXTVAL,:email,:password,:fname,:sname, TO_DATE(:dob, 'YYYY-MM-DD'),:gender,'default',:location,0)";

 	// $query =  "INSERT INTO USERS VALUES(USERS_SEQ.NEXTVAL,:email,:password,:fname,:sname, :dob,:gender,'default',:location,0)";

    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':email', $email);
	oci_bind_by_name($stid, ':password', $password);
	oci_bind_by_name($stid, ':fname', $fname);
	oci_bind_by_name($stid, ':sname', $sname);
	oci_bind_by_name($stid, ':dob', $dob);
	oci_bind_by_name($stid, ':gender', $gender);
	oci_bind_by_name($stid, ':location', $location);

    oci_execute($stid);
    }

}
oci_close($conn);
?>
<html>
<header class="navbar navbar-dark navColor">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<title>Facebook-Lite | Assignment 1</title>
<link href="css/custom.css" rel="stylesheet">

      <div class="container">
        <a class="navTitle" href="index.php">facebook</a>
      </div>

			<!-- Nav Bar -->

</header>

<body>

<div class="container jumbotron text-center">
	<div class="row">
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
	<h1>Congraduations!</h1><br><br>
	<h6>You are now one of our member!</h6>
	<br>
	<br>
	<form action="index.php">
    	<input type="submit" value="Click here to Login!" class="btn btn-primary" />
	</form>

	</div>
	</div>
</div>


</body>
</html>