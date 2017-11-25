<?php
/*
	Function Script:	class/group/functions/joinGroup.php
	This script generates an InGroup relation.
	
	Inputs:
		'ID'
		'Sid'
		
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

$Sid = $_POST['Sid'];


$res = $mysqli->query("SELECT Max, Open FROM SGroup WHERE Cid = '$ID' AND Sid = '$Sid';");
$result = $res->fetch_assoc();
$Max = $result['Max'];
$Open = $result['Open'];

$res = $mysqli->query("SELECT * FROM InGroup WHERE Cid = '$ID' AND Gid = '$Sid' AND Sid = '$username';");
$InGroup = ($res->num_rows > 0);

$res = $mysqli->query("SELECT * FROM InGroup WHERE Cid = '$ID' AND Gid = '$Sid';");
if(($res->num_rows == $Max) || !($Open) || $InGroup){
	echo "0";
	exit();
}

if($mysqli->query("INSERT INTO InGroup (Cid, Gid, Sid) VALUES ('$ID', '$Sid', '$username');"))
	echo "1";
else
	echo "0";

exit();

?>