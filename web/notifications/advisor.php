<?php

/*
	Advisor

	Suggests that you start an activity that you havne't started so far!
*/

//Include the db
include_once('../../classes/db.php');

//Get list of people
$users = DB::getUsersWithMobileDevices();

print_r($users);
//For each person

	//Get the list of activities that they haven't started
	
		//Chose one at random and suggest they start it 
	

?>