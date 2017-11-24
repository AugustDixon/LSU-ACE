<?php
/*
	Page Generate Script: class/info/edit.php
	This script generates the Edit Class page.
	
	HTTP Inputs:
		'ID' - Cid
		
	Page Features:
		"Back" button - Hyperlinks to class/info/index.php
		"Class Title" text field - 0 to 30 characters
		"Classroom" text field - 0 to 20 characters
		"Submit Change Request" button - Runs AJAX class/info/functions/editClassInfo.php
		
	AJAX Functions:
		editClassInfo.php
			Inputs:
				'ID'
				'Title'
				'Classroom'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1 [Pid]" - Success
					Redirect to class/bulletin/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Title Constraint Error
					Show Constraints
				Case "4" - Classroom Constraint Error
					Show Constraints
				Case "5" - "Total" Success
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
		<title>Edit Class Info</title>
	</head>
	<body>
		<h1>Edit Class Info</h1>
		<a href=\"index.php?ID=$ID\">Back</a><br><br>
		Class Title: <input type=\"text\" id=\"Title\"><br>
		Classroom: <input type=\"text\" id=\"Classroom\"><br>
		<input type=\"button\" value=\"Submit Change Request\" onClick=\"edit()\">
	</body>
	<script>
		function edit(){
			var Title = document.getElementById('Title').value;
			var Classroom = document.getElementById('Classroom').value;
			
			var attributes = 'ID=$ID&Title=' + Title + '&Classroom=' + Classroom;
			
			var xhttp;
			xhttp=new XMLHttpRequest();
			xhttp.onreadystatechange = function() 
			{
				if (this.readyState == 4 && this.status == 200) 
				{
					editResponse(this);
				}
			};
			xhttp.open(\"POST\", \"functions/editClassInfo.php\", true);
			xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
			xhttp.send(attributes);
		}
		
		function editResponse(xhttp){
			
			switch(xhttp.responseText.charAt(0)){
			case \"0\":
				alert(\"Edit Class Info Script Failure\");
				break;
			
			case \"1\":
				var arr = xhttp.responseText.split(\" \");
				window.location = \"../bulletin/view.php?ID=$ID&Pid=\" + arr[1];
				break;
			
			case \"2\":
				window.location = \"../../index.php\";
				break;
			
			case \"3\":
				alert(\"Class Title must be less than 30 characters\");
				break;
			
			case \"4\":
				alert(\"Classroom must be less than 20 characters\");
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