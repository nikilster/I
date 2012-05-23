<?php

	
	if(!array_key_exists("type", $_POST)) return;
	if(!array_key_exists("name", $_POST)) return;
	if(!array_key_exists("goal", $_POST)) return;

	
	$type = $_POST["type"];
	if($type != "add") return;
	
	//TODO: sanitize
	$activityName = $_POST["name"];
	$goalDuration = $_POST["goal"];
	
	include_once('auth/login.php');
	$userId = checkLogin();
	
	include_once('../classes/db.php');
	$db = new Db($userId);

	$result = $db->createActivity($activityName, $goalDuration);
	$activities = $db->getActivities();

	include_once('../util/util.php');
	//Data for today
	$dataForToday = $db->getData(getDateString(0));
	echo json_encode(array("result"=>$result, "activities"=>$activities, "dataForToday"=>$dataForToday));

?>