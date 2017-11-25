<?php
/*
	Function Script: class/bulletin/functions/makePost.php
	Makes a new bulletin board post.
	
	Inputs:
		'ID' - Cid
		'Title' - 1 to 50 characters
		'Body' - 1 to 200 characters
		
	Output Codes:
		0 = Script failure
		1 = Success
		2 = Idle Timeout
		3 = Title constraint error
		4 = Body constraint error
*/

date_default_timezone_set("America/Chicago");
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


$Title = $_POST['Title'];
$Body = $_POST['Body'];

if(strlen($Title) > 50){
	echo "3";
	exit();
}

if(strlen($Body) > 200){
	echo "4";
	exit();
}

$Date = date("m/d/y");

if($mysqli->query("INSERT INTO Bulletin (Cid, Sid, Title, Body, Date, Query) VALUES ('$ID', '$username', '$Title', '$Body', '$Date', 0);")){
	$Pid = $mysqli->insert_id;
	echo "1" . " $Pid";
}
else
	echo "0";

exit();

?>