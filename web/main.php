<?php
	
	include_once('auth/login.php');
	$id = checkLogin();

	
	include_once('../util/users.php');
	include_once('../classes/db.php');

	//Check which user we are trying to see
	$userToShow = getUsersPage($id);
	
	
	//Include
	include_once('../classes/event.php');
	include_once('../util/util.php');
	//timer
	//Stop
	//Activity 1
	//Activity 2
	//Activity 3
	
	//History
	//Swim @4:35pm 4min
	//Eat @3:32 12 min
	//Get the current running event
	$db = new Db($userToShow);	
	$activities = $db->getActivities();
	
	//If the user needs to create activities - take them to the create activities page
	if(noActivity($activities))
		redirectToCreateActvitiesPage();
		
	$currentEvent = $db->getCurrentRunningEvent();
	$completed = $db->getCompletedEventsForToday();
	
	function noActivity($activities)
	{
		return count($activities) == 0;
	}
	
	function redirectToCreateActvitiesPage()
	{
		header("Location: createActivities.php");
		exit();
	}
	
?>