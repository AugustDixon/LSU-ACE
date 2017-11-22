<?php
/*
	Page Generate Script: class/options.php
	This script generates the student options page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/index.php
		"Hide LSUID" check box
		"Hide Name" check box
		"Hide Phone Number" check box
		"Edit Options" button - Runs AJAX class/functions/editOptions.php
		
	AJAX Functions:
		editOptions.php
			Inputs
				'ID'
				'HideID'
				'HideName'
				'HidePhone'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Success
					Redirect to class/index.php
				Case "2" - Idle Timeout
					Redirect to index.php
					
	Page Connections:
		class/index.php
*/

session_start();

if(isset($_SESSION['username'])){
	if(($_SESSION['idle'] + 600) < time()){
		unset($_SESSION['username']);
		unset($_SESSION['idle']);
		header("Location: ../index.php", true, 303);
		exit();
	}
}
else{
	header("Location: ../index.php");
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
	header("Location: ../profile/index.php", true, 303);
	exit();
}
$ID = $_GET['ID'];


$res = $mysqli->query("SELECT * FROM Taking WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	header("Location: ../profile/index.php", true, 303);
	exit();
}

echo "<html>
	<head>
		<title>Edit Student Options</title>
	<head>
	<body>
		<h1>Edit Student Options</h1>
		<a href=\"index.php\">Back</a><br><br>
		Hide Name: <input type=\"checkbox\" id=\"HName\"><br>
		Hide Phone: <input type=\"checkbox\" id=\"HPhone\"><br>
		Hide LSUID: <input type=\"checkbox\" id=\"HLSUID\"><br>
		<input type=\"button\" value=\"Edit Options\" onClick=\"loadDoc('functions/editOptions.php', myFunction)\">
	</body>
	<script>
		function loadDoc(url, cFunction){
		var HName = document.getElementById('HName').checked;
		var HPhone = document.getElementById('HPhone').checked;
		var HLSUID = document.getElementById('HLSUID').checked;
		
		var attributes = 'ID=$ID&HideName=' + HName + '&HidePhone=' + HPhone + '&HideID=' + HLSUID;
	
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
			alert(\"Edit Options Script Failure\");
			break;
		
		case \"1\": 
			window.location = \"index.php\";
			break;
		
		case \"2\": 
			window.location = \"../index.php\";
			break;
		}
	}
	</script>
</html>";

exit();


?>