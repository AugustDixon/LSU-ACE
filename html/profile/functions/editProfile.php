<?php
/*
	Function Script: profile/functions/editProfile.php
	This script updates a students profile information.
	
	Input:
		'FirstName' - 0 to 20 characters
		'LastName' - 0 to 20 characters
		'Nickname' - 0 to 20 characters
		'Phone' - 0 to 20 characters
		
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = First Name Constraint Error
		4 = Last Name Constraint Error
		5 = Nickname Constraint Error
		6 = Phone Number Constraint Error
*/
session_start();


$mysqli = new mysqli("localhost", "LoginSelect", "system", "Logins");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

//check idle time
if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$first = $_POST['FirstName'];
$last = $_POST['LastName'];
$nick = $_POST['Nickname'];
$phone = $_POST['Phone'];
$user = $_GET['username'];

//check first name constraints
if(strlen($first) > 20){
   echo "3";
   exit();
}

//check last name constraints
if(strlen($last) > 20){
   echo "4";
   exit();
}

//check nickname constraints
if(strlen($nick) > 20){
   echo "5";
   exit();
}

//check phone number constraints
if(strlen($phone) > 20){
   echo "6";
   exit();
}

//update firstname
if(isset($first)){
   $res = "UPDATE Student SET FirstName = '$first' WHERE Sid = '$user'";
}

//update lastname.
if(isser($last)){
   $res2 = "UPDATE Student SET LastName = '$last' WHERE Sid = '$user'";
}

//update nickname.
if(isset($nick)){
   $res3 = "UPDATE Student SET Nickname = '$nick' WHERE Sid = '$user'";
}

//update phone number.
if(isset($phone)){
   $res3 = "UPDATE Student SET Phone = '$phone' WHERE Sid = '$user'";
}
$mysqli->close();
exit();
?>