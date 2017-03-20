<?php 
if( !session_id() )
{
    session_start();
}
require_once ('../api/Query.php'); 
require_once ('../api/userDefinedFunctions.php'); 
require_once ('centerFunctions.php'); 

If(isset($_GET["location"]))
{
	$locationCode=validate_input($_GET["location"]);
	if(!empty($locationCode))
	{
		//Check validity of location code received.
		//If valid, create a session and set values for this location.
		//If not, destroy the session.
		//Then initiate scanning of qrcodes
		
		//Step 1
		$qry = "SELECT * FROM location_info WHERE locCode='".$locationCode."'";
	   $res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res > 0)
	   {
	      while($row=mysqli_fetch_array($res))
	      {
	      	$id				=	$row['id']; 
	      	$locationDesc	=	$row['location_desc']; 
	      	
	      	$_SESSION['locId'] 			= $id;
				$_SESSION['locName'] 		= $locationDesc;
			}
			$show=1;
			
			//fetch upcoming events 
			$upcomingEvents = array();
			$upcomingEvents= getUpcomingEventsForCenter($id);
			
			//print_r($upcomingEvents);
	   } 
	   else 
	   {
	   	//If not, destroy the session.
	      $show=0;
	   }			
	}
	else
	{
		$show=0;
	}	
}
else
{
	$show=0;	
}


?>


<!DOCTYPE html>
<html >
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="business network, business connections, business opportunities matching, entreprenuers, business network singapore, 
business network malaysia, business network indonesia, business network philippines, business network jakarta, self improvement workshop, 
business networking events, vOffice Global Network, Acceler8.ph philippines, Cre8.id indonesia, Uppercase.asia, meeting room booking, work space booking,
coworking spaces, coworking events 
	">
	<title>Entreprenity - Community of Entrepreneurs | Engineering meaningful connections between people and enriching individuals</title>
	<link rel="icon" type="image/png" href="../assets/img/favicon-32x32.png" sizes="32x32" />

	<link rel="apple-touch-icon" sizes="57x57" href="../assets/img/favicon-57x57.png" />
   <link rel="apple-touch-icon" sizes="72x72" href="../assets/img/favicon-72x72.png" />
   <link rel="apple-touch-icon" sizes="114x114" href="../assets/img/favicon-114x114.png" />
   <link rel="apple-touch-icon" sizes="144x144" href="../assets/img/favicon-144x144.png" />

    <link rel="stylesheet" href="../assets/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/stylesheets/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <script type="text/javascript" src="main.js"></script>
	<script type="text/javascript" src="llqrcode.js"></script>

	</head>
	<body>
	<div class="header">

    </div>

	<?php
		if($show==0) 
		{
			//No location selected
			include 'nolocationselected.php';
		}
		else 
		{
			//show qr code scanner area
			include 'qrcodescanner.php';
		}
	?>

	<script type="text/javascript">load();</script>
	<script src="jquery-1.11.2.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


	</body>
</html>
