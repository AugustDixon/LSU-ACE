<?php
/*
	Function Script: class/functions/load.php
	This script loads the users notes from the database. If the user does not have any notes, create an empty relation and
	send an empty string back.
	
	Script Inputs:
		'ID' - Cid
	
	Outputs:
		0 = Script Failure
		ELSE = Success
*/

session_start();

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "InsertOnly", "system", "LSU-ACE");
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

$res = $mysqli->query("SELECT Sesid FROM Session WHERE Cid = '$ID' AND InSession = 1;");

if($res->num_rows == 0){
	echo "0";
	exit();
}

$result = $res->fetch_assoc();
$Sesid = $result['Sesid'];

$res = $mysqli->query("SELECT Notes FROM Notes WHERE Sesid = '$Sesid' AND Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	if($mysqli->query("INSERT INTO Notes (Sesid, Cid, Sid, Notes) VALUES ('$Sesid', '$ID', '$username', '');"))
		echo "";
	else
		echo "0";
}
else {
	$result = $res->fetch_assoc();
	echo $result['Notes'];
}

exit();

?>