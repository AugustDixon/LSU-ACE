<?php
/*
	Page generate script: /profile/index.php
	This script generates the profile page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Logout" button - Runs AJAX /profile/functions/logout.php
		Displays from database:
			LSUID
			First Name
			Last Name
			Nickname
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

$mysqli = new mysqli("localhost", "SelectOnly", "system", "LSU-ACE");

$LSUID = $_SESSION['username'];
$res = $mysqli->query("SELECT FirstName, LastName, Nickname, Phone FROM Student WHERE Sid = '$LSUID';");
$result = $res->fetch_assoc();
$FirstName = $result["FirstName"];
$LastName = $result["LastName"];
$Nickname = $result["Nickname"];
$PhoneNumber = $result["Phone"];

$res = $mysqli->query("SELECT Cid, Dept, Num FROM Class NATURAL JOIN Taking WHERE Sid = '$LSUID';");
$NumRows = $res->num_rows;
$Department = [];
$Number = [];
for($i = 0; $i < $res->num_rows; $i++){
	$res->data_seek($i);
	$result = $res->fetch_assoc();
	$Department = $result["Dept"];
	$Number = $result["Num"];
}

echo "<html>
 	<head> 
   		 <title>Profile</title>
  	</head>
  	<body> 
		<h1>Profile</h1>
   		 	
			<button onclick=\"loadDoc('functions/logout.php', myFunction)\">Logout</button>
			<a href=\"profile/edit.php\">Edit Profile</a>
			<a href=\"class/index.php\">Class Page</a>
			<p>
			Student Info:
			LSUID: $LSUID
			Name: $FirstName $LastName
			Nicknake: $Nickname
			Phone: $PhoneNumber
			</p>
			</br>
			<p>
			Class Info:
			$Department $Number
			<a href=\"class/index.php\">Class Page</a>
			</p>
  	</body>
	
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
		xhttp.send(attributes);
	}
	
	function myFunction(xhttp) 
	{
		switch(xhttp.responseText)
		{
		case \"0\": 
		
		break;
		
		case \"1\": 
		window.location = \"index.php\"
		break;
		
		case \"2\": 
		
		break;
		}
	}
	</script>
</html>";

exit();
?>