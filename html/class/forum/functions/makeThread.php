<?php
/*
	Function Script: class/forum/functions/makeThread.php
	This script creates and thread and post relation.
	
	Inputs:
		'ID'
		'Title' - 1 to 40 characters
		'Body' - 1 to 400 characters
		'Anon'
		
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = Title Constraint Error
		4 = Body Constraint Error
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

$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
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
$Anon = $_POST['Anon'];

//check title constraints
if(strlen($Title) > 40 || strlen($Title) < 1){
	echo "3";
	exit();
}

//check body constraints
if(strlen($Body) > 400 || strlen($Body) <1){
	echo "4";
	exit();
}



if(!($mysqli->query("INSERT INTO Thread (Cid, Title) VALUES ('$ID', '$Title');"))){
	echo "0";
	exit();
}
$Tid = $mysqli->insert_id;

$Date = date("m/d/y");

if(!($mysqli->query("INSERT INTO ForumPost (Cid, Tid, Sid, Body, Date, Anonymous) VALUES ('$ID', '$Tid', '$username', '$Body', '$Date', $Anon);"))){
	echo "0";
	exit();
}
$Pid = $mysqli->insert_id;

if($mysqli->query("UPDATE Thread SET Pid = '$Pid' WHERE Tid = '$Tid';"))
	echo "1";
else
	echo "0";

exit();


?>