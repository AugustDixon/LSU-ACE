<?php
/*
	Page Generate Script:	class/forum/makeThread.php
	This script generates the Make Thread page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/forum/index.php
		"Title" text field - 1 to 40 characters
		"Body" text field - 1 to 400 characters
		"Anonymous" check box
		"Make Thread" button - Runs AJAX class/forum/functions/makeThread.php
		
	AJAX Functions:
		makeThread.php
			Inputs
				'ID'
				'Title'
				'Body'
				'Anon'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to class/forum/index.php
				Case "2" - Idle Timeout
					Redirects to index.php
				Case "3" - Title Constraint Error
					Show constraints
				Case "4" - Body Constraint Error
					Show constraints
					
	Page Connections:
		class/forum/index.php
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



$html = "<html>
 	<head> 
   		 <title>Make Thread</title>
  	</head>
  	<body> 
		<h1>Make Thread</h1>
			<a href=\"index.php?ID=$ID\">Back</a>
			<form name=\"makeThreadForm\">
				Title: <textarea rows=\"1\" cols=\"93\" name=\"Title\" id=\"Title\" maxlength=40></textarea><br>
				<textarea rows=\"4\" cols=\"100\" wrap=\"soft\" name=\"Body\" id=\"Body\" maxlength=400></textarea><br>  
				Anonymous <input type=\"checkbox\" name=\"Anonymous\" id=\"Anonymous\"><br>  
			</form>
			<input type=\"button\" value=\"Make Thread\" onClick=\"loadDoc('functions/makeThread.php', myFunction)\">
	</body>
	<script>
		function loadDoc(url, cFunction) 
		{
			var Title = document.getElementById(\"Title\").value;
			var Body = document.getElementById(\"Body\").value;
			var Anon = document.getElementById(\"Anonymous\").checked;
			var attributes = \"ID=$ID&Title=\" + escape(Title) + \"&Anon=\" + Anon + \"&Body=\" + escape(Body);
	
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
				alert(\"Make Thread Script Failure\");
				break;
			
			case \"1\": 
				window.location = \"index.php?ID=$ID\";
				break;
				
			case \"2\": 
				window.location = \"../../index.php\";
				break;
			
			case \"3\": 
				alert(\"Title must be 40 characters or less\");
				break;
				
			case \"4\": 
				alert(\"Body must be 400 characters or less\");
				break;	
			}
		}
	</script>
</html>";

echo $html;

exit();



?>