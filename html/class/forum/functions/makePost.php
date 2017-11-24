<?php
/*
	Function Script: class/forum/functions/makePost.php
	This script makes a Post relation.
	
	Inputs:
		'ID'
		'Tid'
		'Body' - 1 to 400 characters
		'Anon'
	
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = Body Constraint Error
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


$Tid = $_POST['Tid'];
$Body = $_POST['Body'];
$Anon = $_POST['Anon'];
$Date = date("m/d/y");

if(strlen($Body) > 400 || strlen($Body) < 1){
	echo "3";
	exit();
}


$sql = "INSERT INTO ForumPost (Cid, Tid, Sid, Body, Date, Anonymous) VALUES ('$ID', '$Tid', '$username', '$Body', '$Date', $Anon)";
if($mysqli->query($sql))
	echo "1";
else
	echo "0";

exit();
	
?>