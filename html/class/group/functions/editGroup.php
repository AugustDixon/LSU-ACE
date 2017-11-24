<?php
/*
	Function Script:	class/group/functions/editGroup.php
	This script updates a SGroup relation's information
	
	Inputs:
		'ID'
		'Name' - 1 to 30 characters
		'Max'
		'Looking'
		'Open'
	Output Codes:
		0 = Script Failure
		1 = Success
		2 = Idle Timeout
		3 = Group Name Constraint Error
*/Connect

//start/load session
session_start();

//Connect to the MySQL server
$mysqli = new mysqli("localhost", "UpdateOnly", "system", "LSU-ACE");
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
$_SESSION['idle'] = time();

$id = $_POST['ID'];
$name = $_POST['Name'];
$max = $_POST['Max'];
$looking = $_POST['Looking'];
$open = $_POST['Open'];

if(strlen($name) > 30 || strlen($name) < 1){
	echo "3";
	exit();
}
if(isset($id)){
	$sql = "UPDATE SGroup SET Max = '$max', Open = '$open', Looking = '$looking', Name = '$name' WHERE Sid = '$id'";
	if($mysqli->query($sql))
		echo "1";
	else
		echo "0";
}
$mysqli->close();
exit();


?>