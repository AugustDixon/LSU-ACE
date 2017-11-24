<?php
/*
	Function Script:	class/group/functions/makeGroup.php
	This script generates an InGroup and SGroup relation. The Student making the group cannot already lead another group.
	
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
		4 = User Already Leads Group
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
$name = $_POST['Name'];
$max = $_POST['Max'];
$looking = $_POST['Looking'];
$open = $_POST['Open'];

if(strlen($name) > 30 || strlen($name) < 1){
	echo "3";
	exit();
}
?>