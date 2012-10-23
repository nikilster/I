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
	
		public static function userIdForAuthToken($authToken, $timezone)
		{
			$fakeUserId = -1;
			$db = new Db($fakeUserId, $timezone);
			
			return $db->userIdForAuthToken($authToken);
		}
		
		
		//This is static so we can call it without being already authenticated
		public static function login($email, $password, $timezone)
		{
			//Invalid user id - just to init the db
			$fakeUserId = -1;
			$db = new Db($fakeUserId, $timezone);
			
			//Try to log in
			$result = $db->authenticate($email, $password);
			
			//Return Result
			return static::getResultOfAuthentication($result, $db);
		}


		/*
			Used for both login and create account (create account logs in and returns the same data as the login :) 
		*/
		private static function getResultOfAuthentication($result, $db)
		{
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
		public static function getInformation($userId, $timezone)
		{
			//Construct a new db
			$db = new Db($userId, $timezone);	
			
			$activities = $db->getActivities();
			$currentEvent = $db->getCurrentRunningEvent();
			//$completed = $db->getCompletedEventsForToday();
			//Percentages for today
			$percentagesForToday = $db->getData(getDateString(0));
			
			return array('activities'=>$activities, 'currentEvent'=>$currentEvent, 'percentages' => $percentagesForToday);
		}
		
		//Return the status of start activity
		public static function startActivity($userId, $activityId, $timezone)
		{
			//Construct a new db
			$db = new Db($userId, $timezone);
			
			//Start the activity and return the json response
			return $db->startEvent($activityId);
		}
		
		//Returns the current running event
		public static function getCurrentRunningEvent($userId, $timezone)
		{
			//Construct a new db
			$db = new Db($userId, $timezone);
			
			//Get the current Running Events
			return $currentEvent = $db->getCurrentRunningEvent();	
		}
		
		//Stop the event
		public static function stopEvent($userId, $eventId, $timezone)
		{
			//Construct a new db
			$db = new Db($userId, $timezone);
			
			return $db->finishCurrentEvent($eventId);
		}

		//Sets the push token (and current date)
		public static function setPushToken($userId, $pushToken, $timezone)
		{
			//Construct a new db
			$db = new Db($userId, $timezone);

			//Set the push token
			return $db->setPushToken($pushToken);
			//*** Negative numbers (-1)  is true in PHP!!!  If(-1) == true

			
		}

		//Create user
		public static function createAccount($firstName, $lastName, $email, $password, $timezone)
		{
			$fakeUserId = -1;
			$db = new Db($fakeUserId, $timezone);

			//Create the user account
			$result = $db->createUser($firstName, $lastName, $email, $password);

			//Format result
			return static::getResultOfAuthentication($result, $db);
		}

		//Create Activity
		public static function createActivity($userId, $activityName, $activityDuration, $timezone)
		{
			//Create db
			$db = new Db($userId, $timezone);

			//Create Activity
			return $db->createActivity($activityName, $activityDuration);
		}
	}
	
	
?>