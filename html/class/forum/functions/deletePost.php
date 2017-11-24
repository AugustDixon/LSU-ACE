<?php
/*
	Function Script: class/forum/functions/deletePost.php
	This script replaces the body of a post with "Deleted" and makes the post anonymous.
	
	Inputs:
		'ID'
		'Pid'
		
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

$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
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

$Pid = $_POST['Pid'];

//Check if user is author
$res = $mysqli->query("SELECT * FROM ForumPost WHERE Cid = '$ID' AND Pid = '$Pid' AND Sid = '$username';");
if($res->num_rows == 0){
	echo "0";
	exit();
}

$sql = "UPDATE ForumPost SET Body = 'Deleted', Anonymous = 1 WHERE Pid = '$Pid'";
if($mysqli->query($sql))
	echo "1";
else
	echo "0";

exit();

?>