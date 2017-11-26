<?php
/*
	Page generate script: /profile/add.php
	This script generates the Add Class page.
	
	HTTP Inputs:
		No inputs besides session data.
	
	Page Features:
		"Back" button - Hyperlinks to /profile/edit.php
		"Department" text Field - 1 to 5 characters
		"Number" text field - 1 to 5 characters
		"Section" text field - 1 to 3 characters
		"Monday", "Tuesday", etc. check boxes
		"Start Hour A" and "Start Minute A" text fields - 1 to 2 characters each.
		"End Hour A" and "End Minute A" text fields - 1 to 2 characters each.
		"Monday", "Tuesday", etc. check boxes
		"Start Hour B" and "Start Minute B" text fields - 1 to 2 characters, 2 characters
		"End Hour B" and "End Minute B" text fields - 1 to 2 characters, 2 characters
		"Hide Name", "Hide Phone", and "Hide LSUID" check boxes
		"Add Class" button - runs AJAX /profile/functions/addClass.php
	
	AJAX Functions:
		addClass.php
			Inputs
				'Dept'
				'Num'
				'Sect'
				'DayA' - Must be built
				'STimeA' - Must be built
				'ETimeA' - Must be built
				'DayB' - Must be built
				'STimeB' - Must be built
				'ETimeB' - Must be built
				'HName'
				'HPhone'
				'HLSUID'
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Redirects to /profile/index.php
				Case "2" - Idle timer expiration
					Redirects to /index.php
				Case "3" - Department Constraint Error
					Show message and constraints
				Case "4" - Number Constraint Error
					Show message and constraints
				Case "5" - Section Constraint Error
					Show message and constraints
				Case "6" - DayA Constraint Error
					Show message and constraints
				Case "7" - Start Time A Constraint Error
					Show message and constraints
				Case "8" - End Time A Constraint Error
					Show message and constraints
				Case "9" - DayB Constraint Error
					Show message and constraints
				Case "10" - Start Time B Constraint Error
					Show message and constraints
				Case "11" - End Time B Constraint Error
					Show message and constraints
				Case "12" - Student Already In Class
					Show message
	
	Page Connections:
		/profile/edit.php
		/profile/index.php
		
	Extra Information:
		Building DayA and DayB:
			DayA and DayB are 5 character strings built from the five weekday checkboxes for each. Each day corresponds to a character in
			the string; Monday is character 0, Tuesday is 2, etc. A checked box will fill that place with the first letter of the name
			of the day uppercase and an unchecked box will be a space. E.g. "M W F", " T T ", "M W  ".
			
		Building STimeA, STimeB, ETimeA, and ETimeB:
			The first part of these strings will be the value of the Hour text field. A colon will be concatenated on to that. Then the Minute
			value will be concatenated onto that. Finally, lowercase "am" will be concatenated if the Hour field is within 7-11 and "pm" will
			be concatenated otherwise. E.g. "1:15pm", "7:30am", "12:00pm".
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


echo "<html>
 	<head> 
		<title>Add Class</title>
		<style>
		textarea {
			resize: none;
		}
		</style>
  	</head>
  	<body> 
		<h1>Add Class</h1>
			<a href= \"edit.php\">Back</a>
			</br>
			</br>
   		 	<form name=\"addClass\">
				Enter the following information exactly as it appears on your Personal Schedule:<br><br>
				Department: <textarea rows=\"1\" cols=\"5\" maxlength=\"5\" id=\"Dept\"></textarea></br>
				Number: <textarea rows=\"1\" cols=\"5\" maxlength=\"5\" id=\"Num\"></textarea></br>
				Section: <textarea rows=\"1\" cols=\"3\" maxlength=\"3\" id=\"Sect\"></textarea></br><br>
				M<input type=\"checkbox\" id=\"MondayA\" value='M'>
				  T<input type=\"checkbox\" id=\"TuesdayA\" value='T'>
				  W<input type=\"checkbox\" id=\"WednesdayA\" value='W'>
				  Th<input type=\"checkbox\" id=\"ThursdayA\" value='T'>
				  F<input type=\"checkbox\" id=\"FridayA\" value='F'></br>
				<textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"SHourA\"></textarea>:<textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"SMinuteA\"></textarea>
				- <textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"EHourA\"></textarea>:<textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"EMinuteA\"></textarea></br><br>
				M<input type=\"checkbox\" id=\"MondayB\" value='M'>
				  T<input type=\"checkbox\" id=\"TuesdayB\" value='T'>
				  W<input type=\"checkbox\" id=\"WednesdayB\" value='W'>
				  Th<input type=\"checkbox\" id=\"ThursdayB\" value='T'>
				  F<input type=\"checkbox\" id=\"FridayB\" value='F'></br>
				<textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"SHourB\"></textarea>:<textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"SMinuteB\"></textarea>
				- <textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"EHourB\"></textarea>:<textarea rows=\"1\" cols=\"2\" maxlength=\"2\" id=\"EMinuteB\"></textarea></br><br>
				Hide Name: <input type=\"checkbox\" id=\"HName\" value=\"true\">
				 Hide Phone: <input type=\"checkbox\" id=\"HPhone\" value=\"true\">
				 Hide LSUID: <input type=\"checkbox\" id=\"HLSUID\" value=\"true\"></br><br>
				<input type=\"button\" value=\"Add Class\" onClick=\"loadDoc('functions/addClass.php', myFunction)\">
			</form>	
  	</body>
	
	</body>
	
	<script>
	
	function day(day){
		if(day.checked)
			return day.value;
		else
			return ' ';
	}
	
	function buildTime(Hour, Minute){
		var intHour = parseInt(Hour);
		if(Hour.length == 1)
			Hour = '0' + Hour;
		var intMinute = parseInt(Minute);
		var ampm = '';
		if(intHour == 12 || (intHour < 7 && intHour > 0))
			ampm = 'pm';
		else if(intHour > 6 || intHour < 12)
			ampm = 'am';
		else
			ampm = '0000000000';
		if(intMinute < 0 || intMinute > 59)
			Minute = '000000000';
			
		return Hour + ':' + Minute + ampm;
	}
	
	function loadDoc(url, cFunction) 
	{
		var Dept = document.getElementById('Dept').value;
		var Num = document.getElementById('Num').value;
		var Sect = document.getElementById('Sect').value;
		var MondayA = document.getElementById('MondayA');
		var TuesdayA = document.getElementById('TuesdayA');
		var WednesdayA = document.getElementById('WednesdayA');
		var ThursdayA = document.getElementById('ThursdayA');
		var FridayA = document.getElementById('FridayA');
		var DayA = day(MondayA) + day(TuesdayA) + day(WednesdayA) + day(ThursdayA) + day(FridayA);
		var SHourA = document.getElementById('SHourA').value;
		var SMinuteA = document.getElementById('SMinuteA').value;
		var STimeA = buildTime(SHourA, SMinuteA);
		var EHourA = document.getElementById('EHourA').value;
		var EMinuteA = document.getElementById('EMinuteA').value;
		var ETimeA = buildTime(EHourA, EMinuteA);
		var MondayA = document.getElementById('MondayB');
		var TuesdayA = document.getElementById('TuesdayB');
		var WednesdayA = document.getElementById('WednesdayB');
		var ThursdayA = document.getElementById('ThursdayB');
		var FridayA = document.getElementById('FridayB');
		var DayB = day(MondayB) + day(TuesdayB) + day(WednesdayB) + day(ThursdayB) + day(FridayB);
		var SHourB = document.getElementById('SHourB').value;
		var SMinuteB = document.getElementById('SMinuteB').value;
		var STimeB = buildTime(SHourB, SMinuteB);
		var EHourB = document.getElementById('EHourB').value;
		var EMinuteB = document.getElementById('EMinuteB').value;
		var ETimeB = buildTime(EHourB, EMinuteB);
		var HName = document.getElementById('HName').checked;
		var HPhone = document.getElementById('HPhone').checked;
		var HLSUID = document.getElementById('HLSUID').checked;
		if(DayB == '     '){
			STimeB = '      ';
			ETimeB = '      ';
		}
		
		var attributes = 'Dept=' + Dept + '&Num=' + Num + '&Sect=' + Sect + '&DayA=' + DayA + '&STimeA=' + STimeA + '&ETimeA=' + ETimeA + '&DayB=' + DayB + '&STimeB=' + STimeB + '&ETimeB=' + ETimeB + '&HName=' + HName + '&HPhone=' + HPhone + '&HLSUID=' + HLSUID;
	
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
			alert(\"Add Class Script Failure\");
			break;
		
		case \"1\": 
			window.location = \"index.php\";
			break;
		
		case \"2\": 
			window.location = \"../index.php\";
			break;
		
		case \"3\": 
			alert(\"Department must be 1 to 5 characters\");
			break;
		
		case \"4\": 
			alert(\"Number must be 1 to 5 characters\");
			break;
		
		case \"5\": 
			alert(\"Section must be 1 to 3 characters\");
			break;
		
		case \"6\": 
			
			break;
		
		case \"7\": 
			alert(\"Start Time A not valid.\");
			break;
		
		case \"8\": 
			alert(\"End Time A not valid.\");
			break;
		
		case \"9\": 
			
			break;
		
		case \"10\": 
			alert(\"Start Time B not valid\");
			break;
		
		case \"11\": 
			alert(\"End Time B not valid\");
			break;
		
		case \"12\":
			alert(\"Student already taking class\");
			break;
		}
	}
	</script>
</html>";

exit();

?>