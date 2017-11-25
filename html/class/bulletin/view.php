<?php
/*
	Page Generate Script: class/bulletin/view.php
	This script generates a page which views bulletin posts. Query posts will have two buttons that allow you to
	respond yes or no to the query.
	
	HTTP Inputs:
		'ID' - Cid of the class
		'Pid' - Post ID
		
	Page Features:
		"Back" button - Hyperlinks to class/bulletin/index.php
		Title
		Body
		"Yes" button - Runs AJAX class/bulletin/functions/altRespond.php. Appears only if the post is a query and the student has not yet responded.
		"No" button - Runs AJAX class/bulletin/functions/altRespond.php. Appears only if the post is a query and the student has not yet responded.
		"Delete Post" button - Runs AJAX class/bulletin/functions/deletePost.php. Appears only if the user is the author of the post.
		
	AJAX Functions:
		altRespond.php
			Inputs:
				'Answer' - boolean
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/bulletin/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
		deletePost.php
			Inputs:
				'ID'
				'Pid'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/bulletin/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
					
	Page Connections
		class/bulletin/index.php
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







$Pid = $_GET['Pid'];

$res = $mysqli->query("SELECT Sid, Title, Body, Date, Query, Aid FROM Bulletin WHERE Pid = '$Pid';");

$result = $res->fetch_assoc();


$Sid = $result['Sid'];
$Title = $result['Title'];
$Body = $result['Body'];
$Date = $result['Date'];
$Query = $result['Query'];
$Author = $username == $result['Sid'];


if($Query){
	$Aid = $result['Aid'];
	$res = $mysqli->query("SELECT * FROM AlteredResp WHERE Cid = '$ID' AND Aid = '$Aid' AND Sid = '$username';");
	$Responded = ($res->num_rows == 0);
	$res = $mysqli->query("SELECT EditClass, Title, Classroom, EditInstructor, InstrName, InstrEmail, Office, Hours, EditTA, TAName, TAEmail FROM Altered WHERE Cid = '$ID' AND Aid = '$Aid';");
	$result = $res->fetch_assoc();
	$EditInstructor = $result['EditInstructor'];
	$EditTA = $result['EditTA'];
	$EditClass = $result['EditClass'];
	
	if($EditClass){
		$NewTitle = $result['Title'];
		$NewClassroom = $result['Classroom'];
		$res = $mysqli->query("SELECT Title, Classroom FROM Class WHERE Cid = '$ID';");
		$result = $res->fetch_assoc();
		$CurrentTitle = $result['Title'];
		$CurrentClassroom = $result['Classroom'];
		
		$Body = "Current Information:<br>
		Title: $CurrentTitle<br>
		Classroom: $CurrentClassroom<br><br>
		
		New Information:<br>
		Title: $NewTitle<br>
		Classroom: $NewClassroom";
		if($Responded)
			$Body .= "<br><br>
		
		Do you agree with these changes?";
	}
	else if($EditInstructor){
		$NewName = $result['InstrName'];
		$NewEmail = $result['InstrEmail'];
		$NewOffice = $result['Office'];
		$NewHours = $result['Hours'];
		$res = $mysqli->query("SELECT Name, Email, Office, Hours FROM Instructor WHERE Cid = '$ID';");
		$result = $res->fetch_assoc();
		$CurrentName = $result['Name'];
		$CurrentEmail = $result['Email'];
		$CurrentOffice = $result['Office'];
		$CurrentHours = $result['Hours'];
		
		$Body = "Current Information:<br>
		Name: $CurrentName<br>
		Email: $CurrentEmail<br>
		Office: $CurrentOffice<br>
		Hours: $CurrentHours<br><br>
		
		New Information:<br>
		Name: $NewName<br>
		Email: $NewEmail<br>
		Office: $NewOffice<br>
		Hours: $NewHours";
		if($Responded)
			$Body .= "<br><br>
		
		Do you agree with these changes?";
	}
	else if($EditTA){
		$NewName = $result['TAName'];
		$NewEmail = $result['TAEmail'];
		$res = $mysqli->query("SELECT Name, Email FROM TA WHERE Cid = '$ID';");
		$result = $res->fetch_assoc();
		$CurrentName = $result['Name'];
		$CurrentEmail = $result['Email'];
		
		$Body = "Current Information:<br>
		Name: $CurrentName<br>
		Email: $CurrentEmail<br><br>
		
		New Information:<br>
		Name: $NewName<br>
		Email: $NewEmail";
		if($Responded)
			$Body .= "<br><br>
		
		Do you agree with these changes?";
	}
}




$html = "<html>
 	<head> 
   		 <title>View Post</title>
  	</head>
  	<body> 
		<h1>View Post</h1>
            <a href= \"index.php?ID=$ID\">Back</a><br>
   		 	<form name=\"postClass\">
				<h2>$Title</h2>
				$Body<br>";

if($Query && $Responded)
	$html .= "
				<input type=\"button\" value=\"Yes\" onClick=\"altRespond(1)\"> 
                <input type=\"button\" value=\"No\" onClick=\"altRespond(0)\">";

if($Author)
	$html .= "
                <br><br><input type=\"button\" value=\"Delete Post\" onClick=\"loadDoc('functions/deletePost.php', myFunction)\">";

$html .= "
			</form>	
  	</body>
	<script>
		function altRespond(Answer) {
			var attributes = 'ID=$ID&Pid=$Pid&Answer=' + Answer;
	
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					altFinished(this);
				}
			};
			xhttp.open(\"POST\", \"functions/altRespond.php\", true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
		
        function altFinished(xhttp) {
			switch(xhttp.responseText)
			{
				case \"0\": 
					alert(\"Response Script Failure\");
					break;
		
				case \"1\": 
					window.location = \"index.php?ID=$ID\";
					break;
		
				case \"2\": 
					window.location = \"../../index.php\";
					break;
			}
		}
		
        function loadDoc(url, cFunction) {
			var attributes = 'ID=$ID&Pid=$Pid';
	
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					cFunction(this);
				}
			};
			xhttp.open(\"POST\", url, true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
		
        function myFunction(xhttp) 
		{
			switch(xhttp.responseText)
			{
				case \"0\": 
					alert(\"Delete Post Script Failure\");
					break;
		
				case \"1\": 
					window.location = \"index.php?ID=$ID\";
					break;
		
				case \"2\": 
					window.location = \"../../index.php\";
					break;
			}
		}
	</script>
</html>";

echo $html;

exit();



?>