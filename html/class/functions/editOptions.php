<?php
/*
	Function Script: class/functions/editOptions.php
	This script changes a student's class options.
	
	Inputs:
		'ID'
		'HideID'
		'HideName'
		'HidePhone'
		
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
*/

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

$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';";

if($res->num_rows == 0){
	echo "0";
	exit();
}

$HideID = $_POST['HideID'];
$HideName = $_POST['HideName'];
$HidePhone = $_POST['HidePhone'];

$sql = "UPDATE Taking SET HideID = '$HideID', HideName = '$HideName', HidePhone = '$HidePhone' WHERE Cid = '$ID' AND Sid = '$username';";

if($mysqli->query($sql))
	echo "1";
else
	echo "0";

exit();


?>