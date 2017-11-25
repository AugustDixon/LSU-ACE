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


$Name = $_POST['Name'];
$Max = $_POST['Max'];
$Looking = $_POST['Looking'];
$Open = $_POST['Open'];

if(strlen($Name) > 30 || strlen($Name) < 1){
	echo "3";
	exit();
}

$res = $mysqli->query("SELECT * FROM SGroup WHERE Cid = '$ID' AND Sid = '$username';");
if($res->num_rows > 0){
	echo "4";
	exit();
}

if(!($mysqli->query("INSERT INTO SGroup (Cid, Sid, Max, Open, Looking, Name) VALUES ('$ID', '$username', $Max, $Open, $Looking, '$Name');"))){
	echo "0";
	exit();
}

if($mysqli->query("INSERT INTO InGroup (Cid, Gid, Sid) VALUES ('$ID', '$username', '$username');"))
	echo "1";
else
	echo "0";

exit();

?>