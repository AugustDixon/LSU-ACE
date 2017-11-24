<?php
/*
	Function Script: profile/functions/editProfile.php
	This script updates a students profile information.
	
	Input:
		'FirstName' - 0 to 20 characters
		'LastName' - 0 to 20 characters
		'LSUID' - 0 to 20 characters
		'Phone' - 0 to 20 characters
		
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = First Name Constraint Error
		4 = Last Name Constraint Error
		5 = LSUID Constraint Error
		6 = Phone Number Constraint Error
*/
session_start();

$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
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

$_SESSION['idle'] = time();

$first = $_POST['FirstName'];
$last = $_POST['LastName'];
$LSUID = $_POST['LSUID'];
$phone = $_POST['Phone'];
$user = $_SESSION['username'];

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

//check LSUID constraints
if(strlen($LSUID) > 20){
   echo "5";
   exit();
}

//check phone number constraints
if(strlen($phone) > 20){
   echo "6";
   exit();
}


$sql = "UPDATE Student SET FirstName = '$first', LastName = '$last', LSUID = '$LSUID', Phone = '$phone' WHERE Sid = '$user'";

if($mysqli->query($sql))
	echo "1";
else
	echo "0";

$mysqli->close();

exit();

?>