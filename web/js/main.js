var COMPLETED_DIV_ID = "completed";
var ACTION_URL = "action.php";
var CREATE_ACTIVITY_URL = "createActivity.php";

var CURRENT_EVENT_DIV = "currentEvent";
var CURRENT_EVENT_TITLE_DIV = "title";
var CURRENT_EVENT_TITLE_DIV_SELECTOR = "#"+CURRENT_EVENT_DIV + " #" + CURRENT_EVENT_TITLE_DIV;
var CURRENT_EVENT_TIME_DIV = "stopwatch";
var CURRENT_EVENT_TIME_DIV_SELECTOR = "#" + CURRENT_EVENT_TIME_DIV;
var FINISH_ACTION_BUTTON_DIV = "finishAction";
var FINISH_ACTION_BUTTON_DIV_SELECTOR = "#" + FINISH_ACTION_BUTTON_DIV;
var CREATE_ACTIVITY_INPUT_DIV = "createActivityInput"
var CREATE_ACTIVITY_INPUT_DIV_SELECTOR = "#" + CREATE_ACTIVITY_INPUT_DIV;
var CREATE_ACTIVITY_BUTTON = "createButton";
var CREATE_ACTIVITY_BUTTON_SELECTOR = "#" + CREATE_ACTIVITY_BUTTON;
var ACTIVITY_NAME_INPUT = "activityName";
var ACTIVITY_NAME_INPUT_SELECTOR = "#" + ACTIVITY_NAME_INPUT;
var GOAL_DURATION_INPUT = "goalDuration";
var GOAL_DURATION_INPUT_SELECTOR = "#" + GOAL_DURATION_INPUT;
var CREATE_ACTIVITY_LINK = "createActivityLink";
var CREATE_ACTIVITY_LINK_SELECTOR = "#" + CREATE_ACTIVITY_LINK;

var CREATE_ACTIVITY_RESULT = "createActivityResult";
var CREATE_ACTIVITY_RESULT_SELECTOR = "#"+CREATE_ACTIVITY_RESULT;

var SUCCESS_INDICATOR_BASE = "success_indicator_";
var SUCCESS_INDICATOR_SELECTOR = "#"+SUCCESS_INDICATOR_BASE;
var PROGRESS_INFO_BASE = "progress_info";
var PROGRESS_INFO_SELECTOR = "#" + PROGRESS_INFO_BASE;
var PROGRESS_BAR_BASE = "progress_bar_";
var PROGRESS_BAR_SELECTOR = "#"+PROGRESS_BAR_BASE;

var ACTIVITIES_DIV = "activities";
var ACTIVITIES_DIV_SELECTOR = "#" + ACTIVITIES_DIV;

var PROGRESS_ACTIVE = "active";

var SECONDS_IN_AN_HOUR = 3600;
var MILLISECONDS_IN_SECOND = 1000;

//Amount of time to show on the timer
var millisecondsElapsed = 0;
//Number of milliseconds
var REFRESH_TIME = 100;
var TIMER_REFRESHES_PER_SECOND = MILLISECONDS_IN_SECOND/REFRESH_TIME;

var BAR_COLOR_BAD ="progress-danger";
var BAR_COLOR_OK = "progress-warning";
var BAR_COLOR_SUCCESS = "progress-success";
	
//In seconds
var DISPLAY_UPDATE_TIME = 1;
var timeUpdatedTillNow = 0;

function setCurrentEventTitle(event)
{
	var currentEventTitle = $(CURRENT_EVENT_TITLE_DIV_SELECTOR);
	currentEventTitle.text(event.activityName);
}

function millisecondsToSeconds(milliseconds)
{
	return Math.ceil(milliseconds / MILLISECONDS_IN_SECOND);
}
//Find out if the elapsed time is a multiple of 
function updateInterval(elapsedMilliseconds)
{
	//Mod in terms of refresh rate
	var elapsedTimeConvertedTo = Math.ceil(elapsedMilliseconds/REFRESH_TIME);
	var displayRefreshConvertedTo = DISPLAY_UPDATE_TIME*TIMER_REFRESHES_PER_SECOND;
	
	return elapsedTimeConvertedTo % displayRefreshConvertedTo == 0;
}
function refreshSuccessIndicator(secondsToAdd)
{
	console.log('refreshing');
	var activityId = STATE.currentEvent.activityId;
	
	var newDuration = updateActualTime(STATE.dataForToday, activityId, secondsToAdd);

	var goal = goalToSeconds(getGoal(STATE.dataForToday, activityId));
	
	var percentageComplete = getPPercentageComplete(newDuration, goal);
	var barColorClass = getProgressBarColor(percentageComplete);
	var colorPercentageComplete = getColorPercentageComplete(percentageComplete);
	

	$(PROGRESS_INFO_SELECTOR+activityId).text(getSuccessText(percentageComplete,goal));
	
	if(!$(SUCCESS_INDICATOR_SELECTOR+activityId).hasClass(barColorClass))
	{
		$(SUCCESS_INDICATOR_SELECTOR+activityId).removeClass(BAR_COLOR_BAD +' '+ BAR_COLOR_OK +' '+ BAR_COLOR_SUCCESS);
		$(SUCCESS_INDICATOR_SELECTOR+activityId).addClass(barColorClass);
	}

	$(PROGRESS_BAR_SELECTOR+activityId).attr('style', 'width:'+colorPercentageComplete+'%');
	
}

function updateDisplay(elapsedMilliseconds)
{
	var secondsToAdd = millisecondsToSeconds(elapsedMilliseconds) - timeUpdatedTillNow;
	
	//The success
	refreshSuccessIndicator(secondsToAdd);
	//Mark
	timeUpdatedTillNow += secondsToAdd;
}

var timerStartTime;
function startTimer(startTime)
{
	timerStartTime = startTime;
	var now = new Date();
	//Time + adding deltas
	timeUpdatedTillNow = now.getTime() - timerStartTime.getTime();
	timeUpdatedTillNow = millisecondsToSeconds(timeUpdatedTillNow);
	
	STATE.timeIntervalId = setInterval("updateTimer()", REFRESH_TIME);
}

function updateTimer() {
    var now = new Date();
    var elapsedMilliseconds = now.getTime() - timerStartTime.getTime()
	
	if(updateInterval(elapsedMilliseconds))
		updateDisplay(elapsedMilliseconds);
	
	var div = document.getElementById(CURRENT_EVENT_TIME_DIV);
	div.innerHTML = formatTime(elapsedMilliseconds);
	
	
}

/*
//Initializes and Starts the timer
function startTimer(currentEvent)
{
	millisecondsElapsed = currentEvent.millisecondsSinceStart();
	STATE.timeIntervalId = setInterval("updateTimer()", REFRESH_TIME);
}

//Function to call on every update of the timer
function updateTimer()
{
	millisecondsElapsed += REFRESH_TIME;
	var div = document.getElementById(CURRENT_EVENT_TIME_DIV);
	div.innerHTML = formatTime(millisecondsElapsed);
}
*/

function getActionForId(data, id)
{
	for(var i=0; i < data.length; i++)
	{
		if(data[i]['id'] == id)
			return data[i];
	}
	
	//Sanity Check
	alert('didn\'t find the id in get actual!');
}
function updateActualTime(data, id, timeToAdd)
{
	
	var action = getActionForId(data, id);
	
	action['duration'] += timeToAdd;
	return action['duration'];
}
//Find the activity in the data which corresponds to the id given and return the time
function getActualTime(data, id)
{
	return getActionForId(data, id)['duration']
}

function getGoal(data, id)
{
	return getActionForId(data, id)['goal'];
}

function getPPercentageComplete(current, target)
{
	var percentageComplete = 100 * current / target ;
	percentageComplete = percentageComplete.toFixed(0);
		
	//Only for old activities without a goal
	if(target == 0)
		percentageComplete = 100;
	
	return percentageComplete;
}

function getColorPercentageComplete(percentageComplete)
{	
	var colorPercentageComplete = percentageComplete;
	
	//So they see at least a red
	if(percentageComplete == 0)
		colorPercentageComplete = 1;

	return colorPercentageComplete;
}

function getProgressBarColor(percentageComplete)
{
	//Red 
	if(percentageComplete < 30)
		return BAR_COLOR_BAD;
	else if(percentageComplete < 70)
		return BAR_COLOR_OK ;
	else 
		return BAR_COLOR_SUCCESS;
}
function getSuccessText(percentageComplete, goal)
{
	return percentageComplete+"% of "+goal/SECONDS_IN_AN_HOUR+" hour goal";
}

//In hours
function goalToSeconds(goal)
{
	return  goal * SECONDS_IN_AN_HOUR;
}
function createActivityButtons(activities, data)
{
	var activitiesDiv = $(ACTIVITIES_DIV_SELECTOR);
	for(var i =0; i < activities.length; i++)
	{
		
		var id = activities[i].id;
		var name = activities[i].name;
		//Both in seconds
		var goal = goalToSeconds(activities[i].goal);
		var actualDurationInSeconds = getActualTime(data, id);
		
		//Container div that has both holds the progress bar / button
		var activityContainer = jQuery(	'<div></div>', {
										id:"activity_" + id,
									   });
		
		
		
		var percentageComplete = getPPercentageComplete(actualDurationInSeconds, goal);
		var barColorCategory = getProgressBarColor(percentageComplete);
		var colorPercentageComplete = getColorPercentageComplete(percentageComplete);
		
		//Success Indicator
		var successIndicator = jQuery( '<div></div>', {
										id:SUCCESS_INDICATOR_BASE+id,
										class:'progress progress-striped ' + barColorCategory,
										}
									 );
		//Text
		successIndicator.append(jQuery("<span></span>", {
										id: PROGRESS_INFO_BASE+id,
										class:'progressText',
										text:getSuccessText(percentageComplete, goal)
										})
								);							 
		
		//Bar
		successIndicator.append(jQuery("<div></div>", {
										id: PROGRESS_BAR_BASE + id,
										class:'bar',
										style:'width: '+colorPercentageComplete+ '%;'										
										})
								);
		
		//Button
		//Create Div
		//New with twitter bootstrap
		var activityButton = jQuery(//'<div></div>', {
									'<button></button>', {
									id:"activityButton_" + id,
									html:name,
									//class:"button redButton"
									class:"activity-button btn btn-large btn-primary"
								});
		activityButton.click([id],activityClicked);
		
		//Add to the div and attach the click fhandler
		activityContainer.append(successIndicator);
		activityContainer.append(activityButton);
		
		activitiesDiv.append(activityContainer);
		
		

	}					
}

/*
	Progress bar functions
*/

//Set the progress bar to active
function updateProgressBar(id)
{
	
	//Make this one active
	progressBarActivate(id);
	
	//Make all of the other ones inactive
	for(var i=0; i < STATE.activities.length; i++)
	{	
		var currId = STATE.activities[i].id;
		if(currId != parseInt(id)) 
		{
			finishProgressActive(currId);
		}
	}
}

function progressBarActivate(id)
{
	$(SUCCESS_INDICATOR_SELECTOR + id).addClass(PROGRESS_ACTIVE);

}
function finishProgressActive(id)
{
	$(SUCCESS_INDICATOR_SELECTOR+id).removeClass(PROGRESS_ACTIVE);
}

//Function that is called when the activity is clicked
function activityClicked(arg1, arg2)
{
	var id = arg1.data[0];
	
	//Save / Start 
	$.ajax({
		url:ACTION_URL,
		data: {type:"start", id:id},
		success: function(data, textStatus, jqXHR) {
			handleAjaxResponse(data);
			
			//Progress Bar Activity
			updateProgressBar(id);
			
		}
	});
}

//Handles the return value from the action click
//Result Form: ["result" => 1 (or 0), "completed" => event /action history...];
function handleAjaxResponse(result)
{
	console.log(result);
	result = jQuery.parseJSON(result);
	
	if(!result)
		console.log("Error with making response");
	else if( !("result" in result))
		console.log("Error with save");
	else if("completed" in result)
	{	
		if(result["result"] == 0)
			console.log("Error saving data");
		
		if("currentEvent" in result && result["currentEvent"])
		{	
			STATE.currentEvent = new Event(result["currentEvent"]);
			//Ovverride the start time to be now so that any time small (not time zone) differences between the server and client smoothed
			STATE.currentEvent.startTime = new Date();
			startEventTimer(STATE.currentEvent);
		}
		else
		{
			//Clear Current Event
			STATE.currentEvent = null;
			initTimer();
		}
		refreshPastEvents(createEventsArray(result["completed"]));	
	}
}
//Takes the timer back to the default setting
function initTimer()
{
	//Set title
	$(CURRENT_EVENT_TITLE_DIV_SELECTOR).text("Right now I am:");
	
	//Stop timer
	clearInterval(STATE.timeIntervalId);
	//Set time
	$(CURRENT_EVENT_TIME_DIV_SELECTOR).text("0:00");
}
function startEventTimer(event)
{
	setCurrentEventTitle(event);
	clearInterval(STATE.timeIntervalId);
	//startTimer(event);
	startTimer(event.startTime);
}
function formatCompletedEvent(completedEvent)
{
	//Be Smart
	//Swimming @5:40 for 2 hours 20 min
	return completedEvent.activityName + " @" + completedEvent.prettyTime() + " " + completedEvent.prettyDuration();
}
function setupActivityClickHandlers()
{
	
}

//Input php json array of completed events
//TODO: swap events
function refreshPastEvents(events)
{
	clearEvents();
	addEvents(events);
}
function createCompletedEvents()
{
	addEvents(STATE.completed);
}

function clearEvents()
{
	$("#"+COMPLETED_DIV_ID).empty();
}
function addEvents(events)
{
	var completedDiv = $("#"+COMPLETED_DIV_ID);
	for(var i=0; i < events.length; i++)
	{
		var text = formatCompletedEvent(events[i]);
		var event = jQuery('<div></div>', {
								id:"completed_" + events[i].id,
								text:text,
								class:"event"
							});
		
		completedDiv.append(event);			
	}
}

//Add the Finish button click handler
function addFinishButtonClickHandler()
{
	$(FINISH_ACTION_BUTTON_DIV_SELECTOR).click(finishClicked);
}

//finish clickedb
function finishClicked()
{
	if(!STATE.currentEvent) return;
	
	var event = STATE.currentEvent;
	
	//TODO: pass current event
	$.ajax({
		url:ACTION_URL,
		data: {type:"finish", id:event.id},
		success: function(data, textStatus, jqXHR) {
			handleAjaxResponse(data);
			
			finishProgressActive(event.activityId);
		}
	});
}

//Successfully finished an action
function handleFinishSucces()
{
	//Reset timer
}

//Create Aactivity
function setupCreateActivity()
{
	$(CREATE_ACTIVITY_INPUT_DIV_SELECTOR).hide();

	//Link Clicked
	$(CREATE_ACTIVITY_LINK_SELECTOR).click(function(e) {
		
		//Cancel action
		e.preventDefault();
		
		//Hide link and show activity box
		$(CREATE_ACTIVITY_LINK_SELECTOR).hide(600);
		$(CREATE_ACTIVITY_INPUT_DIV_SELECTOR).show(600);
	});
	
	
	//Create activity clicked
	$(CREATE_ACTIVITY_BUTTON_SELECTOR).click(function() {
	
		//Create the activity if it has (a name) any text int he box
		if($(ACTIVITY_NAME_INPUT_SELECTOR).val() != "")
			createActivity($(ACTIVITY_NAME_INPUT_SELECTOR).val(), $(GOAL_DURATION_INPUT_SELECTOR).val());	
	});
}

function createActivity(activityName, goalNumber)
{
	$.ajax({
		url:CREATE_ACTIVITY_URL,
		type:"POST",
		data: {type:"add", name:activityName, goal:goalNumber},
		success: function(data, textStatus, jqXHR) {
			var result = handleCreateActivity(data);		
			
			//If it was successful
			if(result == 1)
			{
				setCreateActivityResult("Success!");
				$(CREATE_ACTIVITY_INPUT_DIV_SELECTOR).hide(100);
				$(CREATE_ACTIVITY_LINK_SELECTOR).show(100);
			}
			else
				setCreateActivityResult(result);
		}
	});
}

function handleCreateActivity(result)
{
	console.log(result);
	result = jQuery.parseJSON(result);
	
	//Make sure we got a result
	if(!result) console.log("Error with making create activity ajax call");
	
	//Make sure the result has the result status in it
	else if(!("result" in result)) console.log("Need a result status");
	
	//If we have the activites, check the result and go aheaad and update them (good)
	else if("activities" in result)
	{
		if(result["result"] == 0)
		{
			console.log("Error creating activity");
			return "Error: probably duplicate activity!";
		}
		
		//Good! Create
		else
		{
			
			//Update State
			STATE.activities = createActivitiesArray(result["activities"]);
			//TODO: State.dataForToday is more recent than the current day in State.data
			STATE.dataForToday = result["dataForToday"];
			
			$(ACTIVITIES_DIV_SELECTOR).empty();
			
			createActivityButtons(STATE.activities, STATE.dataForToday);
			
			return 1;
		}	
	
	}
	
	//missing "activities" /other error
	else console.log("create activity broken");
	
	return "Error in creating this activity";
}

function setCreateActivityResult(result)
{
	$(CREATE_ACTIVITY_RESULT_SELECTOR).text(result);
}

//Start the display for the current event
//Title, Timer, B
function startCurrentEvent(event)
{
	startEventTimer(event);
	progressBarActivate(event.activityId);
}
function setupPage(STATE)
{
	addFinishButtonClickHandler();
	createActivityButtons(STATE.activities, STATE.dataForToday);
	setupActivityClickHandlers();
	createCompletedEvents();
	setupCreateActivity();
	
	//Start the timer
	if(STATE.currentEvent)
		startCurrentEvent(STATE.currentEvent);
	else
		initTimer();
		
}