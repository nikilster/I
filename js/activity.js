function createActivitiesArray(jsonData)
{
	var activities = [];
	
	for(var i=0; i < jsonData.length; i++)
		activities.push(new Activity(jsonData[i]));

	return activities;
}

function Activity(jsonData)
{
	this.id = jsonData['id'];
	this.name = jsonData['name'];
	this.goal = jsonData['goal'];
}