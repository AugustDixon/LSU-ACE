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

//check idle time
if(($_SESSION['idle'] + 600) < time()){
	unset($_SESSION['username']);
	unset($_SESSION['idle']);
	echo "2";
	exit();
}
$_SESSION['idle'] = time();

$id = $_POST['ID'];
$sid = $_POST['Sid'];


?>