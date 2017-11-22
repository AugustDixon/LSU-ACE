<?php
/*
	Function Script: sendMessage.php
	This script receives a message and updates the chatlog.
	
	Script Inputs:
		'ID' - Cid
		'message'
		
	Output Codes:
		0 = Script Failure
		1 = Success
*/

session_start();

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$ID = $_POST['ID'];
$message = $_POST['message'];


$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$res = $mysqli->query("SELECT * FROM Session WHERE Cid = '$ID' AND InSession = 1;");

if($res->num_rows == 0){
	echo "0";
	exit();
}


$time = date("h:m:s");


if($mysqli->query("INSERT INTO Chatlog (Cid, Body, Time) VALUES ('$ID', '$message', '$time');"))
	echo "1";
else
	echo "0";

exit();


?>