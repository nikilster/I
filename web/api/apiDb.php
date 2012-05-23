<?php
	
	//This is inside web/api/apiDb.php
	//http://stackoverflow.com/questions/7378814/are-php-include-paths-relative-to-the-file-or-the-calling-code
	//http://stackoverflow.com/questions/2184810/difference-between-getcwd-and-dirname-file-which-should-i-use
	include_once(dirname(__FILE__).'/../../classes/db.php');
	//for the date format
	include_once(dirname(__FILE__).'/../../util/util.php');
	
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
			//$completed = $db->getCompletedEventsForToday();
			//Percentages for today
			$percentagesForToday = $db->getData(getDateString(0));
			
			return array('activities'=>$activities, 'currentEvent'=>$currentEvent, 'percentages' => $percentagesForToday);
		}
		
		//Return the status of start activity
		public static function startActivity($userId, $activityId)
		{
			//Construct a new db
			$db = new Db($userId);
			
			//Start the activity and return the json response
			return $db->startEvent($activityId);
		}
		
		//Returns the current running event
		public static function getCurrentRunningEvent($userId)
		{
			//Construct a new db
			$db = new Db($userId);
			
			//Get the current Running Events
			return $currentEvent = $db->getCurrentRunningEvent();	
		}
		
		//Stop the event
		public static function stopEvent($userId, $eventId)
		{
			//Construct a new db
			$db = new Db($userId);
			
			return $db->finishCurrentEvent($eventId);
		}

		//Sets the push token (and current date)
		public static function setPushToken($userId, $pushToken)
		{
			//Construct a new db
			$db = new Db($userId);

			//Set the push token
			return $db->setPushToken($pushToken);
			//*** Negative numbers (-1)  is true in PHP!!!  If(-1) == true

			
		}
	}
	
	
?>