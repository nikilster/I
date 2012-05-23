<?php

	function getDateString($dayOffset)
	{
		//Mysqql format
		$dateFormatString = 'Y-m-d H:i:s';
		return formattedDate($dayOffset, $dateFormatString);
		
	}

	function getDayInitialFormattedString($dayOffset)
	{
	
		if($dayOffset == 0)
			return getDayFormattedString($dayOffset);
		else
		//Return first character
		return substr(getDayFormattedString($dayOffset), 0,3);
	}
	
	function getDayFormattedString($dayOffset)
	{
		//Just the day: "Sunday"
		$dateFormatString = "l";
		if($dayOffset == 0)
			return "Today";
		else 
			return formattedDate($dayOffset, $dateFormatString);// w
	}
	
	//f
	function formattedDate($dayOffset, $dateFormatString)
	{
		if($dayOffset == 0) 
			return date($dateFormatString);
		else if($dayOffset >0)
			return date($dateFormatString, strtotime("+".strval($dayOffset). " days"));
		else
			return date($dateFormatString, strtotime(strval($dayOffset) . " days"));
	}

?>