function dateObjectFromTime(timeString)
{
		//TODO: Convert to 
		var arr = timeString.split(/[- :]/);
		return new Date(arr[0], arr[1]-1, arr[2], arr[3], arr[4], arr[5]);
}

function Event(jsonData)
{
	this.id = jsonData['id'];
	this.activityId = jsonData['activityId'];
	this.startTime = dateObjectFromTime(jsonData['startTime']);
	this.endTime = jsonData['endTime'] ? dateObjectFromTime(jsonData['endTime']) : jsonData['endTime'];
	this.activityName = "activityName" in jsonData ? jsonData['activityName'] : "";
	// this.duration = "endTime" in jsonData ? 
	
	//Computes the number of seconds since the start
	this.millisecondsSinceStart = function() {
		
		//Convert times
		var nowInSeconds = new Date().getTime();
		var startInSeconds = new Date(this.startTime).getTime();
		
		//Compute difference
		return nowInSeconds - startInSeconds;
	}
	
	this.prettyTime = function() {
	
		var start = new Date(this.startTime);
		var today = new Date();
		
		var day = "";
		var days = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
		if(start.getDate() != today.getDate())
		{
			day = days[start.getDay()] + " ";
		
			//TODO
			if(Math.abs(start.getDate() - today.getDate()) > 6)
				day += " " + (parseInt(start.getMonth()) + 1) + "/" + start.getDate() + " ";
		}
		//am/pm
		var hours = start.getHours();

		var ampm = "am";
		if(start.getHours() >= 12) 
		{
			ampm = "pm";
			hours = hours%12;
		}
		
		//12am
		if(hours == 0)
			hours = 12;
			
		//5:42pm
		var prettyString = day + hours + ":" + start.getMinutes() + ampm;
		
		return prettyString;
	}
	
	this.prettyDuration = function() {
		var startInSeconds = new Date(this.startTime).getTime();
		var endInSeconds = new Date(this.endTime).getTime();
		return formatEventTimeSeconds(endInSeconds - startInSeconds);
	}
	
}

//Create an array of events
function createEventsArray(jsonData)
{
	var events = [];
	
	for(var i=0; i < jsonData.length; i++)
		events.push(new Event(jsonData[i]));
	
	return events;
}