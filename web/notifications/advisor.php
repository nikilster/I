<?php

/*
	Advisor

	Suggests that you start an activity that you havne't started so far!
*/

//Include the db
include_once('../../classes/db.php');
include_once('pushNotification.php');

//Get list of people
$users = DB::getUsersWithMobileDevices();

//For each person
foreach($users as $user)
{
	//User object has: id, firstName, lastName, pushToken

	//Get the list of activities that they haven't started
	//Chose one at random and suggest they start it
	$activity = DB::getRandomUnstartedActivityForUser($user->id);

	//If the user has not finished all of their activities for today
	if($activity)
	{
		//TODO: add more messages!
		//Suggest they start it
		$message = "Hey! Want to " . $activity[name] . " today?";
		motivateUser($message, $user->pushToken);
	}		
		 
}

function motivateUser($message, $pushToken)
{
	sendPush($message, $pushToken);
	echo "sent message: $message to $pushToken";
}
?>