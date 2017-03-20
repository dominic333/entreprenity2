<?php

require_once ('../api/Query.php'); 


//This script is run by a cron job which checks for events that got expired and sets the status as 2 for those events

//SELECT * FROM entrp_events WHERE event_date_time<CURRENT_TIMESTAMP AND status IN(0,1)

$qry="SELECT id FROM entrp_events WHERE event_date_time<CURRENT_TIMESTAMP AND status IN(0,1)";
$res=getData($qry);
$count_res=mysqli_num_rows($res);
if($count_res>0)
{
	while($row=mysqli_fetch_array($res))
   {
   	$eventIds[]			=	$row['id']; 
	}
	
	$eventIdString = implode(",", $eventIds);
	
	//echo $eventIdString;
	
	$qry2="UPDATE entrp_events SET status=2 WHERE id IN(".$eventIdString.") ";
	setData($qry2);
}

?>