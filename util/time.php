<?php

	//Returns times in the format:
	//12h
	//42min
	//
	function formatTime($timeInSeconds)
	{
		$SECONDS_IN_MINUTE = 60;
		$SECONDS_IN_HOUR = $SECONDS_IN_MINUTE*60;
		
		//Give them extra!
		if($timeInSeconds >= $SECONDS_IN_HOUR)
		{
			$value = ceil((float)$timeInSeconds/$SECONDS_IN_HOUR);
			$scale = $value > 1 ? " hours" : " hour";
			return $value . $scale;
		}
		else if($timeInSeconds >= $SECONDS_IN_MINUTE)
		{
			$value = ceil((float)$timeInSeconds / $SECONDS_IN_MINUTE);
			$scale = $value > 1 ? " minutes" : " minute";
			return $value . $scale;
		}
		else
		{
			$value = $timeInSeconds;
			$scale = $value == 1 ? " second" : " seconds";
			return $value . $scale;
		}
	}
?>