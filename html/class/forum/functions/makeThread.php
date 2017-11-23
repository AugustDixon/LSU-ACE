<?php
/*
	Function Script: class/forum/functions/makeThread.php
	This script creates and thread and post relation.
	
	Inputs:
		'ID'
		'Title' - 1 to 40 characters
		'Body' - 1 to 400 characters
		'Anon
		
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = Title Constraint Error
		4 = Body Constraint Error
*/
session_start();

$mysqli = new mysqli("localhost", "Scheduler", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$id = $_POST['ID'];
$title = $_POST['Title'];
$body = $_POST['Body'];
$anon = $_POST['Anon'];

//check title constraints
if(strlen($title) > 40 || strlen($title) <1){
	echo "3";
	exit();
}

//check body constraints
if(strlen($body) > 400 || strlen($body) <1){
	echo "4";
	exit();
}

//need clarification on Thread table
if(isset($id) && isset($title) && isset($body) && isset($anon)){
	$sql = "INSERT ForumPost (Cid, Tid, Pid, Title)
	VALUES ('', '$id', '', '$title')";
	if($mysqli->query($sql))
		echo "1";
	else
		echo "0";
}
$mysqli->close();

exit();
?>