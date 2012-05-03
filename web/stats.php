<?php
	
	include_once('../auth/login.php');
	$id = checkLogin();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Magic Time</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    
	<link href="../css/stats.css" rel="stylesheet">

	<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"> </script>
	
    <!-- Le styles -->
    <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="../bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Visualize</a>
          <div class="nav-collapse">
            <ul class="nav">
			  <li><a href="board.php">Life</a></li>
              <li><a href="index.php">Home</a></li>
			  <li class="active"><a href=".">Friends</a></li>
              <li><a href="../auth/logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
		<div class="row">
				<div class="page-header">
					<h1>Welcome to Visualize
					<small>Insights into your friends</small>
					</h1>
			</div>
		</div>
		

	<?php
		//Show the time spent and the users
		include_once('../classes/db.php');
		include_once('../util/time.php');
		$db = new Db($id);
		
		$data = $db->getUsersAndTimes();
		$counter = 0;
		
		$NUM_ENTRIES_PER_ROW = 4;
		//Bootstrap
		$cellSpan = "span".(12/$NUM_ENTRIES_PER_ROW);
		
		$numRows = ceil((float)count($data)/$NUM_ENTRIES_PER_ROW);
		
		//Display the users!!
		//Rows
		for($rowNum = 0; $rowNum < $numRows; $rowNum++)
		{
			//Row
			echo "<div class='row'>\n";
			
			//Columns
			for($columnNumber= 0; $columnNumber < $NUM_ENTRIES_PER_ROW; $columnNumber++)
			{
				$userIndex = $rowNum*$NUM_ENTRIES_PER_ROW+$columnNumber;
				if($userIndex < count($data))
					displayUser($data[$userIndex], $cellSpan);
			}
			
			//End Row
			echo "</div>\n";
		}
	
	
		function displayUser($user, $class)
		{	
			//
			
			$time = $user['time'] == null ? 0 : $user['time'];
				
			echo "<div class='$class'>\n";
				
				echo "<div class='well'>\n";
					
					//Add id as a link
					echo "<div class='name'><a href='index.php?id=".$user['id']."'>".$user['email']."</a></div>\n";
					echo "<h2><div class='time'>".formatTime($time)."</div></h2>\n";
					echo "<div class='attribute'>visualized this week</div>\n";
				echo "</div>\n";
			
			echo "</div>\n";
		}
	?>
	</div> <!-- /container -->
	
	<!-- Javascript - Placed at the end of the document so pages load faster! -->
	<script src="bootstrap/js/bootstrap-button.js"></script>

	</body>
</html>