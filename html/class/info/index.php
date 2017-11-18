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
		For each TA(0 or more):
			Name 
			Email
			"Edit TA Info" button - Hyperlinks to class/info/TAedit.php (Sends Name)
		"Add TA" button - Hyperlinks to class/info/TAedit.php (Send null as Name)
		
	AJAX Functions:
		none
		
	Page Connections:
		class/index.php
		class/info/roster.php
		class/info/edit.php
		class/info/instructorEdit.php
		class/info/TAedit.php
*/




?>