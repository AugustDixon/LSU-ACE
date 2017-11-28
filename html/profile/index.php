<?php
/*
	Page generate script: /profile/index.php
	This script generates the profile page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Logout" button - Runs AJAX /profile/functions/logout.php
		Displays from database:
			Username
			LSUID
			First Name
			Last Name
			Phone Number
		"Edit Profile" button - Hyperlinks to /profile/edit.php
		Displays from database for each real class taken by the student:
			Department
			Number
			"Class Page" button - Hyperlinks to /class/index.php
	
	AJAX Functions:
		logout.php
			Inputs
				none
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirect to /index.php
	
	Page Connections:
		/index.php
		/profile/edit.php
		/class/index.php
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

$_SESSION['idle'] = time();

$mysqli = new mysqli("localhost", "SelectOnly", "system", "LSU-ACE");
if($mysqli->connect_errno){
	//Send HTTP error code
}

$username = $_SESSION['username'];
$res = $mysqli->query("SELECT FirstName, LastName, LSUID, Phone FROM Student WHERE Sid = '$username';");
$result = $res->fetch_assoc();
$FirstName = $result["FirstName"];
$LastName = $result["LastName"];
$LSUID = $result["LSUID"];
$PhoneNumber = $result["Phone"];


$html = "<html>
 	<head> 
   		<title>Profile</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
  	</head>
  	<body> 
		<h1>Profile</h1>
   		 	
			<button onclick=\"loadDoc('functions/logout.php', myFunction)\">Logout</button>
			<a href=\"edit.php\">Edit Profile</a>
			<p>
			Student Info:<br>
			Username: $username<br>
			LSUID: $LSUID<br>
			Name: $FirstName $LastName<br>
			Phone: $PhoneNumber<br>
			</p>
			<br>";

$res = $mysqli->query("SELECT Cid, Dept, Num, Confirmed, Sect FROM Class NATURAL JOIN Taking WHERE Sid = '$username';");
$NumRows = $res->num_rows;
$Department = [];
$Number = [];
for($i = 0; $i < $NumRows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Department = $result["Dept"];
	$Number = $result["Num"];
	$Sect= $result['Sect'];
	if($result['Confirmed']){
		$Cid = $result["Cid"];
		$html .= "<p>
				Class Info:
				$Department $Number Section $Sect  
				<a href=\"../class/index.php?ID=$Cid\">Class Page</a>
				</p></br>";
	}
	else
		$html .= "<p>
				Class Info:
				$Department $Number Section $Sect
				</p></br>";
}			
			

$html .= "</body>
	
	<script>
	function loadDoc(url, cFunction) 
	{
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
		xhttp.send();
	}
	
	function myFunction(xhttp) 
	{
		switch(xhttp.responseText)
		{
		case \"0\": 
			alert(\"Logout Script Failure\");
			break;
		
		case \"1\": 
			window.location = \"../index.php\";
			break;
		}
	}
	</script>
</html>";

echo $html;

exit();
?>