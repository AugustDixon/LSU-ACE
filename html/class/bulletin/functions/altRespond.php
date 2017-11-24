<?php
/*
	Function Script: class/bulletin/functions/altRespond.php
	Responds to an information alteration query and performs operations based on that response. First, the response is
	recorded in AlteredResp. Then, the script checks whether or not the vote is a majority in either favor against
	the amount of people in the class. Then the script checks if it is a completely tied vote with everyone in the class
	voting. If it ends up in a yes vote majority, the changes from the corresponding Altered relation are applied. If any
	of these conditions are true. The corresponding Bulletin, Altered, and AlteredResp relations are deleted along with
	any temporary tables.
	
	Inputs:
		'Answer' - boolean
		'ID'
		'Pid'
	
	Output Codes:
		0 = Script failure
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

$Answer = $_POST['Answer'];
$Pid = $_POST['Pid'];

$res = $mysqli->query("SELECT Aid FROM Bulletin WHERE Pid = '$Pid';");
$result = $res->fetch_assoc();
$Aid = $result['Aid'];


//Check if student already answered
$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Sid = '$username' AND Aid = '$Aid';");
if($res->num_rows > 0){
	echo "0";
	exit();
}


//Add response
if(!($mysqli->query("INSERT INTO AlteredResp (Cid, Aid, Sid, Response) VALUES ('$ID', '$Aid', '$username', '$Answer');"))){
	echo "0";
	exit();
}

//Find Total Students, number of Yes's and number of No's
$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID';");
$Num = $res->num_rows;
$Majority = ($Num / 2) + 1;

$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Aid = '$Aid' AND Response = 1;");
$NumYes = $res->num_rows;

$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Aid = '$Aid' AND Response = 0;");
$NumNo = $res->num_rows;
$Total = $NumYes + $NumNo;


$Succeed = $NumYes >= $Majority;
$Kill = ($NumNo >= $Majority) || ($Total == $Num);

if($Succeed){
	$res = $mysqli->query("SELECT EditClass, Title, Classroom, EditInstructor, InstrName, InstrEmail, Office, Hours, EditTA, TAName, TAEmail FROM Altered WHERE Cid = '$ID' AND Aid = '$Aid';");
	$result = $res->fetch_assoc();
	$EditInstructor = $result['EditInstructor'];
	$EditTA = $result['EditTA'];
	$EditClass = $result['EditClass'];
	
	if($EditClass){
		$Title = $result['Title'];
		$Classroom = $result['Classroom'];
		if(!($mysqli->query("UPDATE Class SET Title = '$Title', Classroom = '$Classroom' WHERE Cid = '$ID';"))){
			echo "0";
			exit();
		}
	}
	else if($EditInstructor){
		$Name = $result['InstrName'];
		$Email = $result['InstrEmail'];
		$Office = $result['Office'];
		$Hours = $result['Hours'];
		if(!($mysqli->query("UPDATE Instructor SET Name = '$Name', Email = '$Email', Office = '$Office', Hours = '$Hours' WHERE Cid = '$ID';"))){
			echo "0";
			exit();
		}
	}
	else if($EditTA){
		$Name = $result['InstrName'];
		$Email = $result['InstrEmail'];
		if(!($mysqli->query("UPDATE Instructor SET Name = '$Name', Email = '$Email' WHERE Cid = '$ID';"))){
			echo "0";
			exit();
		}
	}
}
if($Kill || $Succeed){
	if(!($mysqli->query("DELETE FROM AlteredResp WHERE Aid = '$Aid';"))){
		echo "0";
		exit();
	}
	if(!($mysqli->query("DELETE FROM Altered WHERE Aid = '$Aid';"))){
		echo "0";
		exit();
	}
	if($mysqli->query("DELETE FROM Bulletin WHERE Pid = '$Pid';"))
		echo "1";
	else
		echo "0";
	exit();
}


echo "1";

exit();


?>