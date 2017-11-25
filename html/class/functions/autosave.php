<?php
/*
	Function Script: class/functions/autosave.php
	This script saves the student's notes.
	
	Script Inputs:
		'ID' - Cid
		'text'
		
	Outputs:
		0 = Script Failure
		1 = Success
*/

date_default_timezone_set("America/Chicago");
session_start();

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$ID = $_POST['ID'];
$text = $_POST['text'];


$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$res = $mysqli->query("SELECT Sesid FROM Session WHERE Cid = '$ID' AND InSession = 1;");
if($res->num_rows == 0){
	echo "0";
	exit();
}
$result = $res->fetch_assoc();
$Sesid = $result['Sesid'];

if($mysqli->query("UPDATE Notes SET Notes = '$text' WHERE Cid = '$ID' AND Sesid = '$Sesid' AND Sid = '$username';"))
	echo "1";
else
	echo "0";


exit();

?>