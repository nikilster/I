<!DOCTYPE html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
<link type="text/css" rel="stylesheet" href="../css/board.css"/>
<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
<link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
		
</head>
<body>

	<script type="text/javascript" src="../js/activity.js" ></script>
	<script type="text/javascript" src="../js/life.js" ></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"> </script>
	<script src="../Highcharts-2.2.0/js/highcharts.js" type="text/javascript"></script>

	<script>
			<?php 
			
			//Auth / Setup 
			include_once('main.php');
			
			//From main
			//$activities is set			

			echo "var DATA = {};";

			//Ignore the current event (for now) - could later have: event running
			/*echo "DATA.currentEvent = " . json_encode($currentEvent). ";";
			echo "\n";
			echo "if(DATA.currentEvent) DATA.currentEvent = new Event(DATA.currentEvent);";
			//For readability
			echo "\n";*///.
			
			echo "DATA.activities = createActivitiesArray(".json_encode($activities).");";
			/*echo "\n";
			echo "DATA.completed = createEventsArray(".json_encode($completed).");";
			*/
			
			//Data Format
			//Array (('id'=>'1', 'name'=>'exercise', 'goal'=>3600 duration'=>600), ...)
			echo "DATA.data = [];";
			echo "DATA.days = [];";
			//Get the graph data
			for($dayOffset = -6; $dayOffset < 1; $dayOffset++)
			{
				echo "DATA.data.push(" . json_encode($db->getData(getDateString($dayOffset))) . "); \n";
				echo "DATA.days.push('" . getDayInitialFormattedString($dayOffset) . "'); \n";
			}		
			?>
			console.log(DATA);
			
			//Document Ready
			$(document).ready(function(){
			
				//Setup
				setUpLife(DATA);
			});
	</script>
	
	<div id="board">
		
		<div class="life">
			<div class="score block">
				<div class="title">LifeScore</div>
				<span class="value"></span><span class="percentage">%</span>
				<div class="duration">Today</div>
			</div>
			<div class="meter block">
				<div class="meterBar">
					<div class="meterFill"></div>
				</div>
				<div class="scores">
					<div class="header">Week Goals</div>
					<div class="good">
						6 <span class="summary">Accomplished</span>
					</div>
				
					<div class="medium">
						5 <span class="summary">Sort Of</span>
					</div>
					<div class="bad">
						2 <span class="summary">Missed</span>
					</div>
				</div>
			</div>
			<div class="timeseries block">
			<div class="visualization"></div>
			</div>
		</div>
		
		<!-- Activities -->
		
	</div>
	
</body>
</html>