<?php
/*
	Function script: /functions/registerAccount.php
	This script creates a new account. This script should only be called using HTTPS.
	
	Script Inputs:
	'username'
		Max length 20 char
	'nickname'
		Max length 20 char
	'firstname'
		Max length 20 char
	'lastname'
		Max length 20 char
	'phone'
		Max length 20 char
	'password'
		Max length 20 char
	'cpass'
		Max length 20 char
	
	Script Output Codes:
		0 = Script Failure
		1 = Successful Account Creation
		2 = Username Constraint Error
		3 = Nickname Constraint Error
		4 = First Name Constraint Error
		5 = Last Name Constraint Error
		6 = Phone Number Constraint Error
		7 = Password Constraint Error
		8 = Passwords Do Not Match
		9 = Username Already Exists
*/

//Start/Load session
session_start();

$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
$logins = new mysqli("localhost", "Scheduler", "system", "Logins");
if($mysqli->connect_errno || $logins->connect_error){
	echo "0";
	exit();
}
$nickname = $_POST['nickname'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$phone = $_POST['phone'];
$username = $_POST['username'];
$password = $_POST['password'];
$cpass = $_POST['cpass'];

//Check if username is valid
if(strlen($username) == 0){
   echo "2";
   exit();
}
if(strlen($username) > 20){
   echo "2";
   exit();
}
else {
   $res = $mysqli->query("SELECT Sid FROM Student WHERE Sid = '$username';");

	if($res->num_rows == 1){
        echo "9";
		exit();
    }   
}

//Check if password and cpass match if not than send constrain failure notification.
if($password != $cpass){
   echo "8";
   exit();
}
//check if password is atleast 5 characters long and send constraint failure notification.
if(strlen($password) < 5){
   echo "7";
   exit();
}
//check if password is over 20 characters long and send constraint failure notification.
if(strlen($password) > 20){
   echo "7";
   exit();
}

//add account to LSU-ACE database
$sql = "INSERT Student (Sid, FirstName, LastName, Phone)
VALUES ('$username', '$firstname', '$lastname', '$phone')";

if ($mysqli->query($sql) === FALSE){
    echo "0";
    exit();
}
$mysqli->close();

//add account to Logins database
$sql = "INSERT Logins (Sid, Password)
VALUES ('$username', '$password')";

if ($logins->query($sql) === TRUE) {
    echo "1";
} else {
    echo "0";
}
$logins->close();

exit();

?>