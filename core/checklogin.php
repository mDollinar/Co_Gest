<?php
require_once "gest.php";
$gest = new Gest();

$host="localhost"; // Host name
$username=""; // Mysql username
$password=""; // Mysql password
$db_name=""; // Database name
$tbl_name="users"; // Table name

// Connect to server and select databse.
$con = mysqli_connect($host, $username, $password, $db_name)or die("cannot connect");

// username and password sent from form 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword'];

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysqli_real_escape_string($con, $myusername);
$mypassword = mysqli_real_escape_string($con, $mypassword);

$sql="SELECT * FROM $tbl_name WHERE usr='$myusername' and pwd='$mypassword' and abilis = 1";
$result=mysqli_query($con, $sql);
$id;
$adm;
while($rs = mysqli_fetch_array($result)){
    $id = $rs['id'];
    $adm = $rs['adm'];
    $master = $rs['master'];
}

// Mysql_num_row is counting table row
$count=mysqli_num_rows($result);
// If result matched $myusername and $mypassword, table row must be 1 row

if($count==1){
	// Register $myusername, $mypassword and redirect to file "login_success.php"
	session_start();
	$_SESSION["myusername"] = "k";
	$_SESSION["mypassword"] = "k";
	$_SESSION['user_id'] = $id;
	$_SESSION['user'] = $myusername;
    $_SESSION['adm'] = $adm;
    $_SESSION['master'] = $master;
    $gest->addLog("eseguito l'accesso", true);
	header("location:../home.php");
}
else {
echo "Utente o password sbagliati, o utente non abilitato";
}
?>