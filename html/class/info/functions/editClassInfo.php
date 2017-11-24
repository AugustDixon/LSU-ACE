<?php
/*
	Function Script: class/info/functions/editClassInfo.php
	This script generates an Altered, AlteredResp, and Bulletin relation to make an info edit query.
	
	Inputs:
		'ID'
		'Title'
		'Classroom'
	
	Outputs:
		0 = Script Failure
		1 [Pid] = Success
		2 = Idle Timeout
		3 = Title constraint error
		4 = Classroom constraint error
		5 = Total Success
		6 [Pid] = Already Exists
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
$Classroom = $_POST['Classroom'];


//Check Constraints
if(strlen($Title) > 30){
	echo "3";
	exit();
}

if(strlen($Classroom) > 20){
	echo "4";
	exit();
}

//Check if same as current info
$res = $mysqli->query("SELECT * FROM Class WHERE Cid = '$ID' AND Title = '$Title' AND Classroom = '$Classroom';");

if($res->num_rows > 0){
	echo "5";
	exit();
}

//Check if only class member
$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID';");

if($res->num_rows == 1){
	if($mysqli->query("UPDATE Class SET Title = '$Title', Classroom = '$Classroom' WHERE Cid = '$ID';"))
		echo "5";
	else
		echo "0";
	exit();
}

//Check if query already exists
$res = $mysqli->query("SELECT Aid FROM Altered WHERE Cid = '$ID' AND EditClass = 1 AND Title = '$Title' AND Classroom = '$Classroom';");

if($res->num_rows > 0){
	$result = $res->fetch_assoc();
	$Aid = $result['Aid'];
	$res = $mysqli->query("SELECT Pid FROM Bulletin WHERE Cid = '$ID' AND Aid = '$Aid';");
	$result = $res-> fetch_assoc();
	$Pid = $result['Pid'];
	$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Aid = '$Aid' AND Sid = '$username';");
	if($res->num_rows > 0){
		echo "6" . " $Pid";
	}
	else{
		if($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', 1);")){
			echo "6" . " $Pid";
		}
		else
			echo "0";
	}
	exit();
}

//Begin operations to execute
//Create Altered relation
if(!($mysqli->query("INSERT INTO Altered (Cid, EditClass, Title, Classroom) VALUES ('$ID', 1, '$Title', '$Classroom');"))){
	echo "0";
	exit();
}
$Aid = $mysqli->insert_id;

//Create AlteredResp relation
if(!($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', 1);"))){
	echo "0";
	exit();
	
}

//Create Bulletin relation
$PostTitle = "Class Information Change Request";
$Date = date("m/d/y");

if(!($mysqli->query("INSERT INTO Bulletin (Cid, Sid, Title, Body, Date, Query, Aid) VALUES ('$ID', '$username', '$PostTitle', '', '$Date', 1, '$Aid');"))){
	echo "0";
	exit();
}
$Pid = $mysqli->insert_id;

echo "1 $Pid";

exit();

?>