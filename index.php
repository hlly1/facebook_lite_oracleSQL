<?php
$loginFail = "";
if ($_SERVER["REQUEST_METHOD"] == "POST")
{


session_start();
global $conn;
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
    $email = $password = "";
	$email = $_POST['email'];
	$password = md5($_POST['password']);
    

	$query = "SELECT EMAIL, PASSWORD FROM USERS WHERE EMAIL=:email AND PASSWORD=:password";

    $stid = oci_parse($conn, $query);

    oci_bind_by_name($stid, ':email', $email);
    oci_bind_by_name($stid, ':password', $password);

    oci_execute($stid);

    $row = oci_fetch_array($stid, OCI_ASSOC);

    if($row){
        $_SESSION['email']=$email;
        header("location: home.php");
    }else{
        $loginFail = "<p style = 'color:red;'>*Invalid Email or Password</p>";
    }
}
oci_close($conn);
}
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
    <h1>Login</h1><br><?php echo $loginFail; ?><br>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <label> Email: </label><br><input type="email" name="email" class="form-control" required/><br>
    <label> Password: </label><br><input type="password" name="password" class="form-control" required/><br><br>

    <input type="submit" class="btn btn-primary" value="Login">
        <a href="signup.html">Not registered?</a>    
    </form>
    </div>
    </div>





</div>


</body>
</html>

