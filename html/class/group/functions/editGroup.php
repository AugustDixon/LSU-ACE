<?php
/*
	Function Script:	class/group/functions/editGroup.php
	This script updates a SGroup relation's information
	
	Inputs:
		'ID'
		'Name' - 1 to 30 characters
		'Max'
		'Looking'
		'Open'
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = Group Name Constraint Error
*/

//start/load session
session_start();

if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$ID = $_POST['ID'];

$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$Name = $_POST['Name'];
$Max = $_POST['Max'];
$Looking = $_POST['Looking'];
$Open = $_POST['Open'];

if(strlen($Name) > 30 || strlen($Name) < 1){
	echo "3";
	exit();
}


$sql = "UPDATE SGroup SET Max = '$Max', Open = '$Open', Looking = '$Looking', Name = '$Name' WHERE Sid = '$username' AND Cid = '$ID';";
if($mysqli->query($sql))
	echo "1";
else
	echo "0";

exit();



?>