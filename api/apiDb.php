<?php
	
	include_once('../db.php');
	
	//Wrapper methods for the db for the web api
	class APIDb
	{
	
		public static function userIdForAuthToken($authToken)
		{
			$fakeUserId = -1;
			$db = new Db($fakeUserId);
			
			return $db->userIdForAuthToken($authToken);
		}
		
		
		//This is static so we can call it without being already authenticated
		public static function login($email, $password)
		{
			//Invalid user id - just to init the db
			$fakeUserId = -1;
			$db = new Db($fakeUserId);
			
			//Try to log in
			$result = $db->authenticate($email, $password);
			
			//TODO: switch this to ===
			//API:: if we have a response == 1,  there ALWAYSwill always be a "data"
			if($result["result"] == 1)
			{
				$id = $result["id"];
				$authToken = $db->getAuthToken($id);
				
				//Return the response
				$return = array("result"=>1, 'authToken'=>$authToken);
				return $return;
			}
			else
			{
				$return = array('result'=>0, 'message'=>"Invalid email or password");
				return $return;
			}
		}

		//Return the main user components of the page
		public static function getInformation($userId)
		{
			//Construct a new db
			$db = new Db($userId);	
			
			$activities = $db->getActivities();
			$currentEvent = $db->getCurrentRunningEvent();
			$completed = $db->getCompletedEventsForToday();
			
			return array('activities'=>$activities, 'currentEvent'=>$currentEvent, 'completedEvents'=>$completed);
		}
		
		//Return the status of start activity
		public static function startActivity($userId, $activityId)
		{
			//Construct a new db
			$db = new Db($userId);
			
			//Start the activity and return the json response
			return $db->startEvent($activityId);
		}
		
		//Stop the event
		public static function stopEvent($userId, $eventId)
		{
			//Construct a new db
			$db = new Db($userId);
			
			return $db->finishCurrentEvent($eventId);
		}
	}
	
	
?>