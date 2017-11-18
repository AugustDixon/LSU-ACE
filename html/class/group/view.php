<?php
/*
	Page Generate Script:	class/group/view.php
	This script generates the View Group page.
	
	HTTP Inputs:
		'ID'
		'Sid' - LSUID of group leader
		
	Page Features:
		"Back" button - Hyperlinks to class/group/index.php
		"Edit Group" button - Hyperlinks to class/group/edit.php. Only shows if the user is the group leader.
		"Join Group" button - Runs AJAX class/group/functions/joinGroup.php. Only shows if group is open
				and Number of Members is less than Maximum Members.
		The following info is available to anyone:
			Group Name
			Maximum Members
			Number of Members
			Whether or not the group is looking for members
		The following info is only available to group members: For Each Member:
			LSUID
			First Name
			Last Name
			Nickname
			Phone Number
		
	AJAX Functions:
		joinGroup.php
			Inputs
				'ID'
				'Sid' - LSUID of group leader
			Outputs/Actions
				Case "0" - Script Failure
					TBD
				Case "1" - Success
					Reloads class/group/view.php
				Case "2" - Idle Timeout
					Redirects to index.php
					
	Page Connections:
		class/group/edit.php
		class/group/index.php
*/





?>