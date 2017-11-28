<?php
/*
	Page Generate Script: class/info/TAedit.php
	This script generates the Add/Edit TA page.
	
	HTTP Inputs:
		'ID'
	
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Name" Text field - 0 to 30 characters. 
		"Email" Text field - 0 to 30 characters
		"Submit Change Request" button - Runs AJAX class/info/functions/editTA.php
		
	AJAX Functions:
		editTA.php
			Inputs
				'ID'
				'Name'
				'Email'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1 [Pid]" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Name constraint error
					Show Constraint
				Case "4" - Email Constraint error
					Show Constraint
				Case "5" - Same as current info
					Redirect to class/info/index.php
				Case "6 [Pid]" - Already Exists
					Redirect to class/bulletin/view.php
					
	Page Connections:
		class/info/index.php
		class/bulletin/view.php
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
		<title>Edit TA Info</title>
		<style>
		body{margin:40px
		auto;max-width:650px;line-height:1.6;font-size:18px;color:#444;padding:0
		10px}h1,h2,h3{line-height:1.2}
		</style>
	</head>
	<body>
		<h1>Edit TA Info</h1>
		<a href=\"index.php?ID=$ID\">Back</a><br><br>
		Name: <input type=\"text\" id=\"Name\"><br>
		Email: <input type=\"text\" id=\"Email\"><br>
		<input type=\"button\" value=\"Submit Change Request\" onClick=\"edit()\">
	</body>
	<script>
		function edit(){
			var Name = document.getElementById('Name').value;
			var Email = document.getElementById('Email').value;
			
			var attributes = 'ID=$ID&Name=' + Name + '&Email=' + Email;
			
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					editResponse(this);
				}
			};
			xhttp.open(\"POST\", \"functions/editTA.php\", true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
		
		function editResponse(xhttp){
			
			switch(xhttp.responseText.charAt(0)){
			case \"0\":
				alert(\"Edit TA Script Failure\");
				break;
			
			case \"1\":
				var arr = xhttp.responseText.split(\" \");
				window.location = \"../bulletin/view.php?ID=$ID&Pid=\" + arr[1];
				break;
			
			case \"2\":
				window.location = \"../../index.php\";
				break;
			
			case \"3\":
				alert(\"Name must be less than 30 characters\");
				break;
			
			case \"4\":
				alert(\"Email must be less than 30 characters\");
				break;
			
			case \"5\":
				window.location = \"index.php?ID=$ID\";
				break;
			
			case \"6\":
				var arr = xhttp.responseText.split(\" \");
				window.location = \"../bulletin/view.php?ID=$ID&Pid=\" + arr[1];
				break;
			}
		}
	</script>
</html>";


echo $html;

exit();



?>