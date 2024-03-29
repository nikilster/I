<?php
 
 //Settings file
 //http://stackoverflow.com/questions/7378814/are-php-include-paths-relative-to-the-file-or-the-calling-code
 include_once(dirname(__FILE__)."/../config/configuration.php");
 include_once(dirname(__FILE__)."/event.php");
 include_once(dirname(__FILE__)."/error.php");
 include_once(dirname(__FILE__)."/activity.php");
 include_once(dirname(__FILE__)."/../util/util.php");
 include_once(dirname(__FILE__)."/user.php");

 //Set default timezone
 date_default_timezone_set('America/Los_Angeles');
 
 class Db
 {
 
	//Warning: if () 
	function __construct($id, $timezone = "")
	{		
		//Set up the database
		//Global configuration of the database settings
		global $dbHostname, $dbUsername, $dbPassword, $dbName;
		
		//Else try to connect to the database
		$this->db = mysql_connect($dbHostname, $dbUsername, $dbPassword);
		
		//Select the database to use
		mysql_select_db($dbName, $this->db);
		
		//Set userId
		$this->userId = intval($id);
		
		//Set the Timezone
		$this->setTimezone($timezone);
	}
	
	//Checks to see if this is a valid user id
	public static function validUserId($userId)
	{
		$anonmymousDB = new self(0);
		
		$id = $anonmymousDB->cleanForDb($userId);
		$query = "SELECT count(*) AS count FROM users WHERE id = $id";
		$result = $anonmymousDB->query($query);
		
		$row = mysql_fetch_assoc($result);
		$count = $row['count'];
		
		//more than 1 user!? error
		if($count > 1) 
		{
			$this->error('More than 1 user with the same id!');
			return false;
		}
		
		//1
		if($count == 1) return true;
		else return false;
	}
	
	/*
		Cleans and sets timezone!
	*/
	private function setTimezone($timezone)
	{
		//If the user didn't pass in a timezone to the create function
		if($timezone == "") return;		
		$timezone = $this->cleanForDb($timezone);
		$this->query("SET time_zone='$timezone'");
	}
	
	private function query($query)
	{
		$result = mysql_query($query);
		
		//Check error
		$this->checkMysqlError();
		
		return $result;
	}
	
	//Error handling
	private function error($name)
	{
		echo $name;
	}
	
	private function checkMysqlError()
	{
		if(mysql_error()) echo mysql_error();
	}
	
	//Gets the event that is currently running
	public function getCurrentRunningEvent()
	{
		//Get the event
		$userId = $this->userId; //so we can put it in the query string
		$currentEventQuery = "SELECT events.id, activity_id, start_time, activities.name AS activity_name FROM events JOIN activities ON events.activity_id = activities.id WHERE events.user_id = $userId AND end_time IS NULL";
		$result = $this->query($currentEventQuery);
		
		//Check to see that there is only one event
		if(mysql_num_rows($result) > 1)
			$this->error("Uh oh, there is more than one open event!");
		
		//No open events
		//return null
		if(mysql_num_rows($result) == 0)
			return null;
			
		//Otherwise return the event
		return new Event(mysql_fetch_array($result));
	}
	
	//End the current running event
	public function finishCurrentEvent($eventId)
	{		
		//Query
		$eventId = intval($eventId);
		
		$endTimeOfEventIsNullQuery = "SELECT id, end_time FROM events WHERE id = $eventId AND end_time IS NULL;";
		$result = $this->query($endTimeOfEventIsNullQuery);
		if(mysql_num_rows($result) != 1)
			return 0;
			
		$finishCurrentQuery = "UPDATE events SET end_time = NOW() WHERE id = $eventId;";
		return $this->query($finishCurrentQuery);	
	}
	
	//Starts a given event
	public function startEvent($activityId)
	{
		//Checks to see if a event is already in progress
		//If so, ends
		if($event = $this->getCurrentRunningEvent())
			$this->finishCurrentEvent($event->id);
		
		//TODO: only start the activity if that activity id belongs to the current user
	
		//Add event
		$activityIdInt = intval($activityId);
		$userId = $this->userId;
		$startEventQuery = "INSERT INTO events (activity_id, user_id, start_time) VALUES ($activityIdInt, $userId, NOW());";
		
		return $this->query($startEventQuery);
	
	}
	
	public function createActivity($name, $goal)
	{
		$name = mysql_real_escape_string($name);
		$userId = $this->userId;
		$getSameNameActivityQuery = "SELECT id, name FROM activities WHERE user_id = $userId AND name = '$name';";
		$result = $this->query($getSameNameActivityQuery);

		//Already more than 1 activity with the same name!  Bad!
		if(mysql_num_rows($result) >1)
		{
			echo "uh oh there is already more than one activity with the same name and we are trying to add another!";
			return 0;
		}
		else if(mysql_num_rows($result) == 1)
			return 0;
	
	
		$insertActivityQuery = "INSERT INTO activities (user_id, name, goal) VALUES ('$userId', '$name', '$goal');";
		return $this->query($insertActivityQuery);
	}
	
	//Ordered by the activity id (essentially date created)
	public function getActivities()
	{
		
		$userId = $this->userId;
		$activitiesQuery = "SELECT id, name, goal FROM activities WHERE user_id = $userId ORDER BY id";
		$result = $this->query($activitiesQuery);
		
		$activities = array();
				
		while($row = mysql_fetch_assoc($result))
			array_push($activities,new Activity($row));
			
		return $activities;
	}
	
	private function createEventsFromDB($dbResult)
	{
		//Build
		$events = array();
		while($row = mysql_fetch_assoc($dbResult))
			array_push($events, new Event($row));
		
		return $events;
	}
	
	//Get the events which are finished
	public function getCompletedEventsForToday()
	{
		//Today
		$today = "NOW()";
		
		//Query
		$userId = $this->userId;
		$completedEventsQuery = "SELECT events.id, activity_id, start_time, end_time, activities.name AS activity_name ";
		$completedEventsQuery .= " FROM events JOIN activities ON events.activity_id = activities.id ";
		$completedEventsQuery .= " WHERE events.user_id = $userId AND end_time IS NOT NULL ";
		//Today Filter
		$completedEventsQuery .= " AND (DATE(start_time) = DATE($today) OR DATE(end_time) = DATE($today)) ";
		$completedEventsQuery .= " ORDER BY events.end_time DESC"; 
		$result = $this->query($completedEventsQuery);
		
		//return an array of Event objects
		return $this->createEventsFromDB($result);		
	}
	
		
	/* ============== Authentication =============== */
	/*
	*
	*
	*
	*
	*/
	
	//Entry point for create
	public function createUser($firstName, $lastName, $email, $password)
	{
		$result = $this->authenticate($email, $password);
	
		//Create user found a user with this einfo
		if($result["result"] == 1)
			return $result;
		
		//There was a user with the email
		else if($result["result"] == -1) return $result;
		
		//Else create user
		else
			//Passing the original values so we can clean inside of sign up
			return $this->signUp($firstName, $lastName, $email, $password);
	}
	
	//Called on log in
	public function authenticate($email, $password)
	{
		$email = $this->cleanForDb($email);
		$password = $this->cleanAndHashPasswordForDb($password);
		
		//Get email
		$emailQuery = "SELECT email FROM users WHERE email = '$email';";
		$result = $this->query($emailQuery);
		
		if(mysql_num_rows($result) == 0)
			return array("result"=>0, "error"=> "no user found");
			
		//If email exists
		else if(mysql_num_rows($result) == 1)
			return $this->getLoginResult($email, $password);
			
		//More than 1 email address!?
		else
		{
			echo "uh oh more than 1 email!";
			return array("result"=>0, "error"=> "more than 1 email!");
		}
	}
	
	private function cleanForDb($field)
	{
		return mysql_real_escape_string($field);
	}
	
	private function cleanAndHashPasswordForDb($password)
	{
		return md5($this->cleanForDb($password));
	}
	
	//Checks the login
	private function getLoginResult($email, $password)
	{
		$checkCredentialsQuery = "SELECT id, first_name, last_name, email, password FROM users WHERE email = '$email' AND password = '$password'";
		$result = $this->query($checkCredentialsQuery);
		
		//Good login
		if(mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_assoc($result);
			$id = $row["id"];
			return array("result"=>1, "id"=> $id);
		}
		else
			return array("result"=>-1, "error"=> "Invalid email or password.");
	}
	
	private function signUp($firstName, $lastName, $email, $password)
	{
	
		//Clean Values
		$firstName = $this->cleanForDb($firstName);
		$lastName = $this->cleanForDb($lastName);
		$email = $this->cleanForDb($email);
		$password = $this->cleanAndHashPasswordForDb($password);
		//Generate a random one
		$authToken = md5($email . date( 'Y-m-d H:i:s'));
		
		$createUserQuery = "INSERT INTO users (first_name, last_name, email, password, auth_token) VALUES ('$firstName', '$lastName', '$email', '$password', '$authToken');";
		$result = $this->query($createUserQuery);
		
		$result = $this->getLoginResult($email, $password);
		
		if($result["result"] == 0) return $result;
		
		$id = $result["id"];
		$this->userId = $id;
		
		/*
		$defaultActivity = array(array("name"=>"Study", "goal"=>4),
								 array("name"=>"Play", "goal"=>1),
								 array("name"=>"Exercise", "goal"=>1.5),
								 array("name"=>"Other", "goal"=>1)
								 );
		foreach ($defaultActivity as $activity)
			$this->createActivity($activity['name'], $activity['goal']);
		*/
		return $result;
	}
 
 
	/**
		getData()
		--------
		Gets the sum of the times of the activities for the user for the current day
		We use this data to show graphse 
	**/
	public function getData($dateTime)
	{
		//Get the user id so we can drop it / use it in the query
		$userId = $this->userId;
		
		$day= " DATE('$dateTime') ";
		
		//Cases
		//Activity Starts before day
							//Ends on day
							//Ends after day
		//Activity Starts on day
							//Ends on day
							//Ends after day
		//
		//Solution startTime <= day and endTime >= day
		//TODO: Handle the case when we have a event without an end time
		//OR (startime <= day and endtime IS null) (still running)(te
		//**NULL date does not count as DATE(end_time) <= DATE(NOW()) 
		$getActivitiesForDayQuery = "SELECT events.id, activity_id, start_time, end_time, activities.name AS activity_name ";
		$getActivitiesForDayQuery .= " FROM events JOIN activities ON events.activity_id = activities.id "; 
		$getActivitiesForDayQuery .= " WHERE events.user_id = $userId AND ";
		$getActivitiesForDayQuery .= " ((DATE(start_time) <= $day AND DATE(end_time) >= $day) ";
		$getActivitiesForDayQuery .= " OR (DATE(start_time) <= $day AND end_time IS NULL )); ";
	
		//Query
		$result = $this->query($getActivitiesForDayQuery);
		
		//Create
		$events = $this->createEventsFromDB($result);
		
		
		
		//Sum the total time for each activity
		$activityTimes = array();
		foreach($events as $e)
		{
			//Activity id (should be string)
			$activityId = $e->activityId;
			
			//If we have seen this activity
			if(array_key_exists($activityId , $activityTimes))
				$activityTimes[$activityId] += $e->duration();
			
			//We Have not seen this activity
			//Add ('id'=> '1', 'name'=>'exercise', 'duration'=>24) (duration is in seconds)
			else
				$activityTimes[$activityId] = $e->duration(); 
		}
		
		//Get the activities
		$activityList = $this->getActivities();
		
		//Compile a list of all of the activites with the id, name, time of goal, durations
		$compiledActivities = array();
		for($i = 0; $i < count($activityList); $i++)
		{
			//Set the basic info (activity)
			$currActivity = $activityList[$i];
			$activityInfo = array('id'=>$currActivity->id, 'name'=>$currActivity->name, 'goal'=> $currActivity->goal, 'duration'=>0);

			//If we have a duration - set it
			if(array_key_exists($currActivity->id, $activityTimes))
				$activityInfo['duration'] = $activityTimes[$currActivity->id];
			
			//Add Percentage
			//***Duration is in seconds.  Goal is in hours.
			//TODO: dont let the goal be 0
			$SECONDS_IN_AN_HOUR = 3600;
			$activityInfo['percentage'] = 100 * ((double)$activityInfo['duration'] / ($SECONDS_IN_AN_HOUR*$activityInfo['goal']));
			
			//Add to array
			array_push($compiledActivities, $activityInfo);
		}
		
		//Return the values (the array (('id'=>'1',, 'name'=>'exercise', 'goal'=>3600 'duration'=>600), ...))
		return $compiledActivities;
		
	}
 
	/*Returns the users and the amount of time each person has spent on the site in the past week
		  Also returns how much time the user has spent in total 
	  Also retursn how many categories the user hasa 
	  
	  Output: array(array('name'=>"nikil", 'id'=>1, 'timeThiswWeek'=>1241234, tiemTotal=>123123123), ...);
	  'i'=-'
	*/  
	function getUsersAndTimes()
	{
		//Get the data
		//Do later? user?
		//Show somthing special
		//By user id
		//for the user
		
		//Query
		$getAllDataQuery = 'SELECT users.id AS id, users.email AS email, users.first_name AS firstName, users.last_name AS lastName, sum(TIME_TO_SEC(TIMEDIFF(events.end_time, events.start_time))) AS time';
		$getAllDataQuery .= ' FROM users LEFT JOIN events ON users.id = events.user_id ';
		$getAllDataQuery .= ' GROUP BY users.id ';
		
		$result = $this->query($getAllDataQuery);
		
		//Get results
		$data = array();
		while($row = mysql_fetch_assoc($result))
			array_push($data, array("id" => $row['id'], 'email'=>$row['email'], 'firstName' => $row['firstName'], 'lastName' => $row['lastName'], 'time'=>$row['time']));
		
		return $data;
	}
	
	/*
		Get Authtoken
		
		Returns the current user's auth token and if necessary (based on the rules of multiple I decide on) generate /sets new
	*/
	
	public function getAuthToken($id)
	{
		$id = intval($id);
		$getAuthTokenQuery = "SELECT auth_token FROM users WHERE id = $id"; 
		
		$result = $this->query($getAuthTokenQuery);
		
		if(mysql_num_rows($result) != 1)
			error("getAuthToken: Invalid user id");
		
		$row = mysql_fetch_assoc($result);
		return $row["auth_token"];
	}
	
	
	public function userIdForAuthToken($authToken)
	{
	
		//TODO: sanitize
		$authToken = $this->cleanForDb($authToken);
		$getUserIdQuery = "SELECT id FROM users WHERE auth_token = '$authToken'";
		
		$result = $this->query($getUserIdQuery);
		
			
		if(mysql_num_rows($result) == 0)
			return 0;
			
		else if(mysql_num_rows($result) == 1)
		{
			$row = mysql_fetch_assoc($result);
			return $row["id"];
		}
		else //(mysql_num_rows($result) > 1)
			error("More than 1 user has the same auth token! Auth token collision!");
		
		return -1;
	}

	/*
		Set Push Token

		Sets the iphone push token and the current date
	*/

	public function setPushToken($pushToken)
	{
		//Get the user id
		$userId = $this->userId;

		//Clean
		$pushToken = $this->cleanForDb($pushToken);

		//Make sure this is a valid push token
		$PUSH_TOKEN_LENGTH = 64;
		if(strlen($pushToken) < $PUSH_TOKEN_LENGTH)
			return false;

		//Make Queries
		$addPushTokenQuery = "UPDATE users SET push_token = '$pushToken' WHERE id = $userId;";
		$updatePushTokenDateQuery = "UPDATE users SET push_token_date = NOW() where id = $userId;";

		//Run Queries
		$addPushTokenResult = $this->query($addPushTokenQuery);
		$updatePushTokenDateResult = $this->query($updatePushTokenDateQuery);

		//Return pushTokenResult
		return $addPushTokenResult;

	}


	/*
		Choose a user and randomly generate test data for that user for the past week
	*/
	public function generateTestData()
	{
		//Get the user id
		$userId = $this->userId;
		
		//Get the activity ids and goals
		$activities = $this->getActivities();
		$NUM_DAYS = 7;

		//For today and the past NUM_DAYS-1 days
		for($dayOffset = 0; $dayOffset<$NUM_DAYS; $dayOffset++)
		{	
			echo $dayOffset . "<br/>";
			$dayTimestamp = strtotime(-$dayOffset . " days");
			$day = date('Y-m-d 0:0:0', $dayTimestamp);
			
			//For each activity
			foreach($activities as $activity)
			{
				//Add a random duration
				//From 0 - 1.5Goal hours
				$id = $activity->id;
				$goal = $activity->goal;
				
				$percentage = rand(1,11) / 10.0;
				$minutes = $percentage * $goal * 60;
				
				//Add time
				//Starttime is just midnight
				$startTime = $day;
				$endTime = date('Y-m-d H:i:s', strtotime($day . ' +'.$minutes." minutes"));
				/*$endDate = new DateTime($startTime);
				$endDate->add(new DateInterval('P'.$minutes.'M'));
				$endTime = $endDate->format('Y-m-d H:i:s');*/
				
				
				$addEventQuery = "INSERT INTO events (activity_id, user_id, start_time, end_time) VALUES ($id, $userId, '$startTime', '$endTime');";
				
				echo $addEventQuery . "<br/>";
				$result = $this->query($addEventQuery);
				echo "Result of adding: " . print_r($result) . "<br/><br/>";
				
				
			}
			
		}
	}

	/*
		For the Notifications!
	*/
	public static function getUsersWithMobileDevices()
	{
		//Query
		$selectUsersWithMobileQuery = "SELECT id, first_name, last_name, push_token FROM users WHERE push_token IS NOT NULL";
		$anonmymousDB = new self(0);
		$result = $anonmymousDB->query($selectUsersWithMobileQuery);

		//Create Array
		$users = array();
		while($row = mysql_fetch_assoc($result))
			array_push($users, new User($row));

		//Return
		return $users;

	}

	/*
		For Notifications
	*/
		public static function getRandomUnstartedActivityForUser($userId)
		{

			//We know this is clean
			$db = new self($userId);

			//Current date (Now in Mysql datetime format)
			$now = date("Y-m-d H:i:s");
			$activities = $db->getData($now);

			//Get the activities that are 0
			$unstartedActivities = array();
			foreach($activities as $a)
				if($a['duration'] == 0) 
					array_push($unstartedActivities, $a);


			//TODO: if all of the activities are started - suggest important
			//Choose an activity at random
			$numActivities = count($unstartedActivities);
			if($numActivities > 0)
				return $unstartedActivities[rand(0,$numActivities-1)];
			else
				return null;
		}

	/*
		Log Notification
	*/
		//User id, Time, Activity Id, First Name, Last Name
		//Duration, Goal, Percentage, Activity Name, Message
		public static function logMotivation($userId, $time, $activityId,
												$firstName, $lastName, $duration,
												$goal, $percentage, $activityName, $message)
		{
			//Anonymous db
			$db = new self(0);

			$message = $db->cleanForDb($message);
			$logQuery = 'INSERT INTO motivation ';
			$logQuery .= ' (user_id, time, activity_id, first_name, last_name, duration, goal, percentage, activity_name, message) ';
			$logQuery .= ' VALUES ';
			$logQuery .= " ($userId, '$time', $activityId, '$firstName', '$lastName', $duration, $goal, $percentage, '$activityName', '$message');";

			return $db->query($logQuery);

		}
 }		
 
?>