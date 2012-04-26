<?php

class Event
{
	public $id;
	public $activityId;
	public $activityName;
	public $startTime;
	public $endTime;
	
	
	//Construct from the db data -= mysqlfetcharray_ no
	function __construct($dbData)
	{
		$this->id = $dbData['id'];
		$this->activityId = $dbData['activity_id'];
		$this->startTime = $dbData['start_time'];
		
		//If we have an end time add it otherwise use null
		//array key exists (key , array)
		$this->endTime = array_key_exists('end_time',$dbData) ? $dbData['end_time'] : null;
	
		//If we have 
		$this->activityName = array_key_exists('activity_name', $dbData) ? $dbData['activity_name'] : null;
	}
	
	//Returns the total time of the events
	function duration()
	{
		//Difference
		$startTime = strtotime($this->startTime);
		
		if(is_null($this->endTime))
			$endTime = time();
		else 
			$endTime = strtotime($this->endTime);
		
		//Return the number of seconds
		return $endTime - $startTime;
	}
}
?>