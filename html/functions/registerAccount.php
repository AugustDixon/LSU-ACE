<?php
/*
	Function script: /functions/registerAccount.php
	This script creates a new account. This script should only be called using HTTPS.
	
	Script Inputs:
	'username' - 1 to 20 characters. Must be unique
	'nickname' - 0 to 20 characters
	'firstname' - 0 to 20 characters
	'lastname' - 0 to 20 characters
	'phone' - 0 to 20 characters
	'password' - 5 to 20 characters
	'cpass' - Must match password
	
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
if(strlen($username) > 20 || strlen($username) == 0){
   echo "2";
   exit();
}

$res = $mysqli->query("SELECT Sid FROM Student WHERE Sid = '$username';");
if($res->num_rows == 1){
    echo "9";
	exit();
}   

//Check if password and cpass match if not than send constrain failure notification.
if($password != $cpass){
   echo "8";
   exit();
}
//check if password is atleast 5 characters long or over 20 characters long and send constraint failure notification.
if(strlen($password) < 5 || strlen($password) > 20){
   echo "7";
   exit();
}

if(strlen($firstname) > 20){
	echo "4";
	exit();
}

if(strlen($lastname) > 20){
	echo "5";
	exit();
}

if(strlen($nickname) > 20){
	echo "3";
	exit();
}

if(strlen($phone) > 20){
	echo "6";
	exit();
}


//add account to LSU-ACE database
$sql = "INSERT Student (Sid, Nickname, FirstName, LastName, Phone) VALUES ('$username', '$nickname', '$firstname', '$lastname', '$phone')";

if ($mysqli->query($sql) === FALSE){
    echo "0";
    exit();
}
$mysqli->close();

//add account to Logins database
$sql = "INSERT Logins (Sid, Password) VALUES ('$username', '$password')";

if ($logins->query($sql) === TRUE) {
    echo "1";
} else {
    echo "0";
}
$logins->close();

exit();

?>