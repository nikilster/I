function drawDayViewGraph(data, divId, length, useBarChart)
{
	//For Charts
	var IS_WEEK_VIEW = false;
	
	if(useBarChart)
		createGraphHC(data, divId, IS_WEEK_VIEW, "My day");
	else
		createGraphd3(data, divId, length); //data, div, size
}

//Adds the label to the div graph 
function addGraphDayLabel(dayName, divId)
{
	$("#"+divId).html("<span class='graphName'>" + dayName + "</span>");
}


function drawWeekViewGraphs(data, days, divIdPrefix, length, useBarChart)
{
	//TODO: Check that the max length is 6
	var MAX_GRAPHS = 6;
	var numGraphs = Math.min(MAX_GRAPHS, data.length);
	
	//For Charting
	var IS_WEEK_VIEW = true;
	
	//Create Graphs
	for(var i=0; i<numGraphs; i++)
	{
		if(useBarChart)
			createGraphHC(data[i], divIdPrefix+i, IS_WEEK_VIEW, days[i]);
		else
		{
			addGraphDayLabel(days[i], divIdPrefix+i);
			createGraphd3(data[i], divIdPrefix+i, length);
		}
	}	
}

function convertToMinutes(seconds)
{
	return Math.ceil(seconds/60);
}
