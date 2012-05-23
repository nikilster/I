<?php

	
	//make sure we have a type
	//either start / finish
	if(!array_key_exists("type",$_GET)) return;
	
	$type = $_GET["type"];
	if(!($type == "start" || $type == "finish")) return;
	if(!array_key_exists("id", $_GET))return;

	include_once('auth/login.php');
	$userId = checkLogin();
	
	include_once('../classes/db.php');
	$db = new Db($userId);
	$eventId = intval($_GET["id"]);

	if($type == "start")	
		//Start Event
		$result = $db->startEvent($eventId);

	else if ($type == "finish")
		$result = $db->finishCurrentEvent($eventId);
	
	//Get the current Running Events
	$currentEvent = $db->getCurrentRunningEvent();	
	
	//Return the new event in history
	$completed = $db->getCompletedEventsForToday();
		
	echo json_encode(array("result"=> $result, "currentEvent"=>$currentEvent, "completed"=>$completed));
	
?>