<?php
/*
	Page Generate Script: class/info/index.php
	This script generates the class info page.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Class Roster" button - Hyperlinks to class/info/roster.php
		The following info for the class:
			Department
			Number
			Section
			Class Title
			Classroom
			Days A
			Start to End Time A
			Days B
			Start to End Time B
			"Edit Class Info" button - Hyperlinks to class/info/edit.php
		The following info for the Instructor:
			Name
			Email
			Office
			Office Hours
			"Edit Instructor Info" button - Hyperlinks to class/info/instructorEdit.php
		The following info for the TA:
			Name 
			Email
			"Edit TA Info" button - Hyperlinks to class/info/TAedit.php
		
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/info/roster.php
		class/info/edit.php
		class/info/instructorEdit.php
		class/info/TAedit.php
*/

session_start();

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);
		header("Location: ../../index.php", true, 303);
		exit();
	}
}
else{
	header("Location: ../../index.php");
	exit();
}

$username = $_SESSION['username'];
$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "SelectOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	//Send HTTP error code
	exit();
}


if(!isset($_GET['ID'])){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}
$ID = $_GET['ID'];

$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}




$res = $mysqli->query("SELECT Dept, Num, Sect, Title, Classroom, DayA, STimeA, ETimeA, DayB, STimeB, ETimeB FROM Class WHERE Cid = '$ID';");

$result = $res->fetch_assoc();
$Dept = $result['Dept'];
$Num = $result['Num'];
$Sect = $result['Sect'];
$Title = $result['Title'];
$Classroom = $result['Classroom'];
$DayA = $result['DayA'];
$STimeA = $result['STimeA'];
$ETimeA = $result['ETimeA'];
$DayB = $result['DayB'];
$STimeB = $result['STimeB'];
$ETimeB = $result['ETimeB'];


$res = $mysqli->query("SELECT Name, Email, Office, Hours FROM Instructor WHERE Cid = '$ID';");

$result = $res->fetch_assoc();
$InstrName = $result['Name'];
$InstrEmail = $result['Email'];
$Office = $result['Office'];
$Hours = $result['Hours'];

$res = $mysqli->query("SELECT Name, Email FROM TA WHERE Cid = '$ID';");

$result = $res->fetch_assoc();
$TAName = $result['Name'];
$TAEmail = $result['Email'];


$html = "<html>
	<head>
		<title>Class Information</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
	</head>
	<body>
		<h1>Class Information</h1>
		<a href=\"../index.php?ID=$ID\">Back</a><br>
		<a href=\"roster.php?ID=$ID\">Class Roster</a>
		<p>
			Department: $Dept<br>
			Number: $Num<br>
			Section: $Sect<br>
			Class Title: $Title<br>
			Classroom: $Classroom<br>
			$STimeA - $ETimeA $DayA<br>
			$STimeB - $ETimeB $DayB<br>
			<a href=\"edit.php?ID=$ID\">Edit Class Info</a>
		</p>
		<p>
			Instructor Name: $InstrName<br>
			Instructor Email: $InstrEmail<br>
			Office: $Office<br>
			Office Hours: $Hours<br>
			<a href=\"instructorEdit.php?ID=$ID\">Edit Instructor Info</a>
		</p>
		<p>
			TA Name: $TAName<br>
			TA Email: $TAEmail<br>
			<a href=\"TAedit.php?ID=$ID\">Edit TA Info</a>
		</p>
	</body>
</html>";


echo $html;

exit();

?>