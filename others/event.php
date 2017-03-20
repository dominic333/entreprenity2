<?php

require_once ('../api/Query.php'); 
require_once '../api/constants.php';

$eventTag	= validate_input(urldecode($_GET['tagged']));
$action		= validate_input(urldecode($_GET['action']));

if($eventTag!='' && $action!='') 
{
	$qry="SELECT id 
			FROM entrp_events
			WHERE eventTagId='".$eventTag."' AND read_only=0 AND status=0 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$id		=	$row['id'];
      }
      
      if($action=='accept')
		{
			$qry2="UPDATE entrp_events SET read_only=1,status=1  WHERE id=".$id." ";
			if(setData($qry2))
			{
				echo 'Event with id '.$eventTag.' has been marked as accepted.';
			}
		}
		else if($action=='reject')
		{
			$qry2="UPDATE entrp_events SET read_only=1,status=3  WHERE id=".$id." ";
			if(setData($qry2))
			{
				echo 'Event with id '.$eventTag.' has been marked as rejected.';
			}
		}
		else
		{
			echo 'Sorry! No such request exists.';
		}	
   }
   else
   {
		echo 'Sorry! No such request exists.';
   }
}
else
{
	echo 'Sorry! No such request exists.';
}

//Function to validate inputs
function validate_input($input) 
{	
  $input = trim($input);
  //$input = stripslashes($input);
  $input = addslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}


?>