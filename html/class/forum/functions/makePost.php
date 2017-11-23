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

$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

//check idle time
if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$id = $_POST['ID'];
$tid = $_POST['Tid'];
$body = $_POST['Body'];
$anon = $_POST['Anon'];
$date = date("Y/m/d");

if(strlen($body) > 400 || strlen($body) <1){
	echo "3";
	exit();
}

//need to figure out Cid, Pid, Sid
if(isset($id) && isset($tid) && isset($body) && isset($anon)){
	$sql = "INSERT ForumPost (Cid, Tid, Pid, Sid, Body, Date, Anonymous)
	VALUES ('', '$tid', '', '', '$body', '$date', '$anon')";
	if($mysqli->query($sql))
		echo "1";
	else
		echo "0";
}
$mysqli->close();

exit();

	
?>