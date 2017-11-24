<?php
/*
	Page Generate Script:	class/group/edit.php
	This script generates the Edit Group page.
	
	HTTP Inputs:
		'ID'
		
	Page Features:
		"Back" button - Hyperlinks to class/group/view.php
		"Group Name" text box - 1 to 30 characters
		"Max Group Members" slider/whatever
		"Looking for Members" check box
		"Open Group" check box
		"Edit Group" button - Runs AJAX class/group/functions/editGroup.php
		
	AJAX Functions:
		editGroup.php
			Inputs
				'ID'
				'Name'
				'Max'
				'Looking'
				'Open'
			Outputs/Actions
				Case "0" - Script failure
					TBD
				Case "1" - Success
					Redirect to class/group/view.php
				Case "2" - Idle Timeout
					Redirect to index.php
				Case "3" - Group Name Constraint Error
					Show constraintts
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

$res = $mysqli->query("SELECT * FROM SGroup WHERE Cid = '$ID' AND Sid = '$username';");

if($res->num_rows == 0){
	header("Location: ../../profile/index.php", true, 303);
	exit();
}



$html = "<html>
 	<head> 
   		 <title>Edit Group</title>
  	</head>
  	<body> 
		<h1>Edit Group</h1>
            <a href= \"view.php?ID=$ID&Sid=$username\">Back</a>
            <form name=\"editGroup\">
				Group Name: <input type=\"text\" id=\"groupName\"><br>
                Max Group Members:<input type=\"text\" id=\"max\" maxlength=\"36\"><br>
                Looking for Members:<input id=\"Looking\" type=\"checkbox\" name=\"Looking\"><br>
                Open Group:<input id=\"Open\" type=\"checkbox\" name=\"Open\"><br>
                </div>
				
				<input type=\"button\" value=\"Edit Group\" onClick=\"loadDoc('functions/editGroup.php', myFunction)\">
			</form>	
    </body>
	
	<script>
		function loadDoc(url, cFunction) 
		{
			var name = document.getElementById('groupName').value;
			var max = document.getElementById('max').value;
			max = parseInt(max);
			var looking = document.getElementById('Looking').value;
			var open = document.getElementById('Open').value;
			var attributes = 'ID=$ID&Name=' + groupName + '&Max=' + max + '&Looking=' + looking + '&Open=' + open;
	
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
				alert(\"Edit Group Script Failure\");
				break;
		
			case \"1\": 
				window.location = \"view.php?ID=$ID&Sid=$username\";
				break;
		
			case \"2\": 
				window.location = \"../../index.php\";
				break;
		
			case \"3\": 
				alert(\"Group Name must be 1 to 30 characters long\");
				break;
		
		    }
		}
	</script>
</html>";

echo $html;

exit();

?>