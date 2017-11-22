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

$res = $mysqli->query("SELECT * FROM Session WHERE Cid = '$ID' AND InSession = 1;");

if($res->num_rows == 0){
	echo "0";
	exit();
}

if($mysqli->query("UPDATE Notes SET Notes = '$text';"))
	echo "1";
else
	echo "0";


exit();

?>