/* Bar chart using High Charts */
var ACTIVITY_NAME_KEY = 'name';
var ACTIVITY_LENGTH_KEY = 'duration';
var GOAL_KEY = 'goal';

function calculateTimeTillGoal(goalTimeInHours, elapsedTime)
{	
	var MINUTES_IN_AN_HOUR = 60;
	
	//elapsed is in seconds
	var elapsedMinutes = elapsedTime / MINUTES_IN_AN_HOUR;
	
	//goal is in hours
	var goalMinutes = goalTimeInHours * MINUTES_IN_AN_HOUR;
	
	var timeTillGoal = goalMinutes - elapsedMinutes;

	if(timeTillGoal > 0) return timeTillGoal;
	else return 0;
} 
function createGraphHC(data, divId, isWeekView, title)
{
		
		var fontFamily = '"Helvetica Neue", Helvetica, Arial, sans-serif';
		
		var COLOR_BLACK = "#FF0000";
		var colors = ["#0882c5", "#f1f1f1"];
		var colorSchemer = ["#3366FF", "#33FF66", "#CC33FF", "#FFCC33", "#FF3366", "#FF33CC"];
		var colorBootstrapButton = ["#f5f5f5", "#007acd", "#55bad8", "#000000",  "#5ebd5f", "#faad41"];
		var colorSchemeDesigner = ["#FF4900", "#0B7AC2", "#62EB00", "#511700", "#00253D", "#1F4A00"];
		var colorSchemer2 = ["#0000FF", "#8000FF", "#FF0080", "#FF8000", "#FFFF00", "#00FF00"];
		
		//Color from "well"
		var yAxisLines = "#f5f5f5";
		var titleColor = "#999";
		
		//Activities
		var activities = [];
		for(var i=0; i < data.length; i++)
			activities.push(data[i][ACTIVITY_NAME_KEY]);
			
		//Durations
		var durations = [];
		for(var i=0; i < data.length; i++)
			durations.push({
					//Same as xAxis category
					name: data[i][ACTIVITY_NAME_KEY],
					color: colorSchemer2[i],
					y: convertToMinutes(data[i][ACTIVITY_LENGTH_KEY])
					});
		
		//Goals
		var goals = [];
		for(var i=0; i < data.length; i++)
			goals.push({
				//"Goal for " xAxis category
				name: "Goal",
				color: COLOR_BLACK, 
				y: calculateTimeTillGoal(data[i][GOAL_KEY], data[i][ACTIVITY_LENGTH_KEY])
				});

		//Options for the Chart
		var options = {
		
		chart: {
			renderTo:divId,
			type: 'column',
			style: {
				fontFamily: fontFamily,
			}
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
				}
			}
		},
		
		series: [{
					//data: goals
				},
				{
					data: durations
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
	

	
	//Day Specific
	if(!isWeekView)
	{
		console.log(options);
		options.xAxis.categories = activities;
		options.tooltip.pointFormat = '<span style="color:{point.color}; font-weight:bold;">{point.name}:</span> <span style="font-weight:bold;">{point.y}</span> minutes today';
		options.yAxis.title.text = "Minutes Spent";
	}
	else
	{
		//No Ticks
		options.xAxis.tickInterval = 0;
		//TD: detailed check vs display none
		options.yAxis.title.text= "";
		options.tooltip.pointFormat = '<span style="color:{point.color}; font-weight:bold;">{point.name}</span>: <span style="font-weight:bold;">{point.y}</span>minutes';

	}
	var chart = new Highcharts.Chart(options);		
	
	
}