/*
	life.js
	
	Life Board
*/

/*
	Constants
*/
BOARD_SELECTOR = "#board";

LIFE_DAY_SELECTOR = ".life .value";
LIFE_COLOR_DAY_SELECTOR = ".life .score";
LIFE_GRAPH_SELECTOR = ".life .visualization";

ACTIVITY_DIV_PREFIX = "activity_";
ACTIVITY_SELECTOR = "#"+ACTIVITY_DIV_PREFIX;

ACTIVITY_DAY_SELECTOR = ".day";
ACTIVITY_DAY_VALUE_SELECTOR= ".day .value";
ACTIVITY_DAY_TITLE_SELECTOR = ".day .title";

ACTIVITY_WEEK_SELECTOR = ".week";
ACTIVITY_WEEK_VALUE_SELECTOR = ".week .value";

ACTIVITY_VISUALIZATION_SELECTOR = ".visualization";

CSS_CLASS_GOOD = "good";
CSS_CLASS_MEDIUM = "medium";
CSS_CLASS_BAD = "bad";

DATA_PERCENTAGE_KEY = "percentage";
DATA_ACTIVITY_ID_KEY = "id";

/*
	setUpLife
	
	Sets up the board - main functione
*/
function setUpLife(data)
{
	setUpLifeRow(data);
	setUpTheActivities(data);
}

/*
	setUpLifeRow
	
	Sets up the first rows 
*/
function setUpLifeRow(data)
{
	setUpLifeDayBlock(data.data[data.days.length-1]);//[x
	//setUpLifeMeter(data);
	setUpLifeWeekGraph(data)
}

/*
	setUpLifeDayBlock
	
	Top left block - summary life score for day
*/
function setUpLifeDayBlock(dayInfo)
{
	var cumulativePercentage = percentageForDay(dayInfo);
	
	//Color
	var colorClass = getCSSColorClass(cumulativePercentage);
	
	//
	$(LIFE_DAY_SELECTOR).text(cumulativePercentage);
	$(LIFE_COLOR_DAY_SELECTOR).addClass(colorClass);

}

function setUpLifeWeekGraph(data)
{
	//Data to pass to the graph
	var weekData = [];
	var dayLabels = data.days;
	var div = $(LIFE_GRAPH_SELECTOR)[0];

	var weekActivityData = data.data;

	//For each day
	for(var dayIndex=0; dayIndex < weekActivityData.length; dayIndex++)
		weekData.push(percentageForDay(weekActivityData[dayIndex]));
		
	addGraph(weekData, dayLabels, div, "life");
}


/*
	=================================
			Activities
	=================================
*/
/*
	Set up Activities
*/

function setUpTheActivities(data)
{

	//Get t data
	var activitiesPercentages = data.data;
	var days = data.days;
	
	//Setup each activity
	for(var i=0; i < data.activities.length; i++)
		setUpActivity(data.activities[i], activitiesPercentages, days);
}

/*
	Set up activity

		Creates an activity (an entire row)
*/
function setUpActivity(activity, activitiesPercentages, days)
{
	createActivityDiv(activity);
	setUpActivityDay(activity, activitiesPercentages[activitiesPercentages.length-1]);
	setUpActivityBlockWeek(activity, activitiesPercentages);
	setUpActivityVisualization(activity, activitiesPercentages, days);
}

function createActivityDiv(activity)
{
	var div =
			
			['<div class="activity">',
				'<div class="score block day">',
					'<div class="title">Exercise</div>',
					'<span class="value"></span><span class="percentage">%</span>',
					'<div class="duration">Today</div>',
				'</div>',
				'<div class="score block week">',
					'<span class="value"></span><span class="percentage">%</span>',
					'<div class="duration">Week</div>',
				'</div>',
				'<div class="timeseries block">',
				'<div class="visualization">',
				'</div>',
			'</div>'].join('\n');
		
	//Create div
	var item = $(div).attr('id', ACTIVITY_DIV_PREFIX + activity.id);

	$(BOARD_SELECTOR).append(item);
}

/*
	Set up activity day

	Sets up the activity day block
*/
function setUpActivityDay(activity, dayData)
{
	var percentage = getActivityPercentage(dayData, activity);
	var title = activity.name;
	var colorClass = getCSSColorClass(percentage);
	
	var activitySelector = ACTIVITY_SELECTOR+activity.id + " ";
	$(activitySelector + ACTIVITY_DAY_TITLE_SELECTOR).html(title);
	$(activitySelector + ACTIVITY_DAY_VALUE_SELECTOR).html(percentage);
	//TODO: move the color to a specific div
	$(activitySelector + ACTIVITY_DAY_SELECTOR).addClass(colorClass);
}

/* Set Up Activity Block Week
*/
function setUpActivityBlockWeek(activity, activitiesPercentages)
{
	//Get Data
	var percentage = getActivityWeekPercentage(activitiesPercentages, activity);
	var colorClass = getCSSColorClass(percentage);
	
	//Activity Selector
	var activitySelector = ACTIVITY_SELECTOR+activity.id + " ";
	
	//Set data
	$(activitySelector + ACTIVITY_WEEK_VALUE_SELECTOR).html(percentage);
	$(activitySelector + ACTIVITY_WEEK_SELECTOR).addClass(colorClass);
}

/*
	Set up Visualization
*/
function setUpActivityVisualization(activity, activitiesPercentages, days)
{
	var data = [];
	var labels = days;
	var div = $(ACTIVITY_SELECTOR + activity.id + " " + ACTIVITY_VISUALIZATION_SELECTOR)[0];
	
	//add for each day
	for(var i=0; i < activitiesPercentages.length; i++)
		data.push(getActivityPercentage(activitiesPercentages[i], activity));
		
	//Graph
	addGraph(data, labels, div, activity.name);
}







/*
	Add Graph
	
	Adds a graph to the specified div
	
*/	
function addGraph(data, labels, div, activityName)
{

	var title = "";
	/* Column chart using High Charts */
	var fontFamily = '"Helvetica Neue", Helvetica, Arial, sans-serif';
	
	var colors = ["#0882c5", "#f1f1f1"];
	var colorSchemer = ["#3366FF", "#33FF66", "#CC33FF", "#FFCC33", "#FF3366", "#FF33CC"];
	var colorBootstrapButton = ["#f5f5f5", "#007acd", "#55bad8", "#000000",  "#5ebd5f", "#faad41"];
	var colorSchemeDesigner = ["#FF4900", "#0B7AC2", "#62EB00", "#511700", "#00253D", "#1F4A00"];
	var colorSchemer2 = ["#0000FF", "#8000FF", "#FF00FF", "#FF0080", "#FF8000", "#FFFF00", "#00FF00"];
		
	//Color from "well"
	var yAxisLines = "#f5f5f5";
	var titleColor = "#999";
		
	//Durations
	var percentages = [];
	
	for(var i=0; i < data.length; i++)
		percentages.push({
				//Same as xAxis category
				name: labels[i],
				color: colorSchemer2[i],
				y: data[i]
				});
	
	//Calculate the max
	//Max of 100 and the maximum Y value
	var maxYValue = Math.max(100, Math.max.apply(Math, data));
	
	//Options for the Chart
	var options = {
	
	chart: {
		renderTo:div,
		type: 'column',
		style: {
			fontFamily: fontFamily,
		},
		backgroundColor:"#f5f5f5"
	},
	
	title: {
		text: title,
		style: {
			fontFamily: fontFamily,
			color: titleColor
		}
	},
	
	xAxis:{
		labels: {
			style: {
				fontFamily: fontFamily,
				color: titleColor
			}
		}
	},
	
	yAxis: {
		title: {			
			style: {
				fontFamily: fontFamily,
				color: titleColor					
			}
		},
		gridLineColor:yAxisLines,
		labels: {
			style: {
				fontFamily: fontFamily,
				color: titleColor		
			},
			formatter: function(){return this.value+"%";}
		},
		max: maxYValue
	},
	
	series: [{
				data: percentages
			}],
	
	//No Legend
	legend: {
		enabled: false
		},
	
	//No Header
	tooltip: {
		headerFormat:'',
	},
	
	plotOptions: {
		column: {
			stacking: 'normal'
		}
	}
}


	options.xAxis.categories = labels;
	options.tooltip.pointFormat = '<span style="color:{point.color}; font-weight:bold;">{point.name}:</span> <span style="font-weight:bold;">{point.y}</span>% of '+activityName+' goals achieved';
	options.yAxis.title.text = "";

	//No Ticks
	//options.xAxis.tickInterval = 0;
		
	var chart = new Highcharts.Chart(options);		

}

/*
	Get activity week percentage
	
	As number
*/
function getActivityWeekPercentage(weekData, activity)
{
	var totalPercentage = 0.0;
	
	//for each day
	for(var i=0; i < weekData.length; i++)
		totalPercentage += Math.min(100, getActivityPercentage(weekData[i], activity));
		
	var percentage = Math.round(totalPercentage/weekData.length);

	return percentage;
}
/*

	Get Activity Percentage
	
	For this activity
*/
function getActivityPercentage(dayData, activity)
{
	for(var i=0; i<dayData.length; i++)
		if(dayData[i][DATA_ACTIVITY_ID_KEY] == activity.id)
			return Math.round(dayData[i][DATA_PERCENTAGE_KEY]);
			
	return -1;
}


/* 
	
	Percentage For Day
	
	Calculates the Average percnatege of all of the activities for a given day
	
*/	
function percentageForDay(dayActivities)
{
	var cumulativePercentage = 0.0;
	
	for(var i =0; i < dayActivities.length; i++)
	{
		//cap at 100
		//must do your goals everyday!
		var currentPercentage = Math.min(dayActivities[i]['percentage'], 100);
		cumulativePercentage += currentPercentage;
	}
	
	//Average
	cumulativePercentage /= dayActivities.length;
	
	//Round
	cumulativePercentage = Math.round(cumulativePercentage);
	
	return cumulativePercentage;
}

/* 
	Helper Functions
*/

/*
	get CSS color class

	gets the CSS class (good, medium , bad) for the percentage
	
	for now:  -/20/70/+
*/
function getCSSColorClass(percentage)
{
	if(percentage < 20)
		return CSS_CLASS_BAD;
	
	else if(percentage < 70)
		return CSS_CLASS_MEDIUM;
	
	else
		return CSS_CLASS_GOOD;
}