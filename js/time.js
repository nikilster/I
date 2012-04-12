//constants
var MILLISECONDS_IN_A_DECISECOND = 100;
var MILLISECONDS_IN_A_SECOND = 1000;
var MILLISECONDS_IN_A_MINUTE = 60000;
var MILLISECONDS_IN_AN_HOUR = 3600000;
	
//A number of milliseconds into hh:mm:ss.m
function formatTime(milliseconds)
{

	
	//Calculate
	var numHours = Math.floor(milliseconds / MILLISECONDS_IN_AN_HOUR);
	milliseconds -= numHours * MILLISECONDS_IN_AN_HOUR;
	
	var numMinutes = Math.floor(milliseconds / MILLISECONDS_IN_A_MINUTE);
	milliseconds -= numMinutes * MILLISECONDS_IN_A_MINUTE;
	
	var numSeconds = Math.floor(milliseconds / MILLISECONDS_IN_A_SECOND);
	milliseconds -= numSeconds * MILLISECONDS_IN_A_SECOND;
	
	var numDeciseconds = Math.floor(milliseconds / MILLISECONDS_IN_A_DECISECOND);
	milliseconds -= numDeciseconds * MILLISECONDS_IN_A_DECISECOND;
	
	var output = String(numSeconds) + "." + String(numDeciseconds) + " sec";
	
	//Add the minutes
	if(numMinutes > 0)
	{
		//If necessary -> add "0"
		//if(numSeconds < 10) output = "0"+output;
		output = String(numMinutes) + " min  &nbsp&nbsp " + output;
	}
	
	//Add the hours
	if(numHours > 0)
	{
		//If necssary -> add "0"
		//if(numMinutes < 10) output = "0"+output;
		output = String(numHours) + " hrs  &nbsp&nbsp " + output;
	}
	
	return output;
}

//TODO: Better time formatting
//Formats an event time to seconds
//todo: 42h 20m 3s
function formatEventTimeSeconds(milliseconds)
{
	//4:42:20.2
	var timeWithDecimal = formatTime(milliseconds);
	//4:42:20
	//var timeAsInteger = Math.floor(timeWithDecimal);
	
	//4.2 seconds
	if(milliseconds < MILLISECONDS_IN_A_MINUTE)
		return  timeWithDecimal + "s";
	else if (milliseconds < MILLISECONDS_IN_AN_HOUR)
		//return timeAsInteger + "m";
		return timeWithDecimal + "m";
	else
		//return timeAsInteger;
		return timeWithDecimal;
}
