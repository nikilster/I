<?php

/*
	Advisor

	Suggests that you start an activity that you havne't started so far!
*/

//Include the db
include_once(dirname(__FILE__).'/../../classes/db.php');
include_once(dirname(__FILE__).'/pushNotification.php');

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
		//Suggest they start it
		motivateUser($user, $activity);
 
}

function motivateUser($user, $activity)
{

	//TODO: add more messages!
	$message = getMessage($user, $activity);	

	//Send Push
	sendPush($message, $user->pushToken);

	//Log
	logMotivation($user, $activity, $message);
}

function getMessage($user, $activity)
{
	$name = $user->firstName;
	$activity = $activity['name'];

	$m1 = "Hey $name! Want to $activity today?";
	$m2 = "Hey $name!  Is it time to $activity!?";
	$m3 = "Yo $name!  Maybe some $activity now?";
	$m4 = "Hey buddy!  Let's get some $activity on!";

	$messages = array($m1, $m2, $m3, $m4);

	//Return random message
	$randomMessage = $messages[rand(0,count($messages)-1)];

	return $randomMessage;
}

function logMotivation($user, $activity, $message)
{
	//User id, Time, Activity Id, First Name, Last Name
	//Duration, Goal, Percentage, Activity Name, Message
	$now = date("Y-m-d H:i:s");
	DB::logMotivation($user->id, $now, $activity['id'],
						$user->firstName, $user->lastName,
						$activity['duration'], $activity['goal'], $activity['percentage'],
						$activity['name'], $message);

}

?>