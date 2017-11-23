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

//check idle time
if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}

$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	echo "0";
	exit();
}

$id = $_POST['ID'];
$pid = $_POST['Pid'];

if(isset($Pid) && isset($ID)){
	$sql = "UPDATE ForumPost SET Body = 'Deleted', Anonymous = 'true' WHERE Pid = '$pid'";
	if($mysqli->query($sql))
		echo "1";
	else
		echo "0";
}
$mysqli->close();

exit();

?>