<?php
	
	//Check the login
	include('login.php');
	list($id, $timezone) = checkLogin();
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Magic Time</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

	<!-- My Stuff -->
	<script type="text/javascript" src="d3/d3.js"></script>
	<script type="text/javascript" src="d3/d3.layout.js"></script>
	<link href="css/see.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="js/event.js" ></script>
	<script type="text/javascript" src="js/graph.js" ></script>

    <!-- Le styles -->
    <link href="libraries/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="libraries/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

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
              <li><a href="index.php">Home</a></li>
              <li class="active"><a href="see.php">Insights</a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">

      <h1>What you have done so far</h1>
      <p>Here are your insights for today<br> All you get is this message and a barebones HTML document.</p>
	  <div id="info"></div>

	<!-- Graphing js -->
	<script type="text/javascript">

	<?php
		//Include the database file
		include('db.php');
		
		//Create the database with the id
		$db = new Db($id, $timezone);
		
		//Get the data
		echo "var data = " . json_encode($db->getData()) . ";";
	?>
	/*var data = [{"label":"one", "duration":20}, 
            {"label":"two", "duration":50}, 
            {"label":"three", "duration":30}];
		*/
		drawGraph();
	</script>
	
	</div> <!-- /container -->
  </body>
</html>