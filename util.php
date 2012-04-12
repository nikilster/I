<?php

	function getDateString($dayOffset)
	{
		//Mysqql format
		$dateFormatString = 'Y-m-d H:i:s';
		return formattedDate($dayOffset, $dateFormatString);
		
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