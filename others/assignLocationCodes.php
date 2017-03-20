<?php
require_once ('../api/Query.php'); 


$locations=getLocations();	
if(!empty($locations))
{
	$countLocations=count($locations);
	for($i=0;$i<$countLocations;$i++)
	{
		$location	= $locations[$i];
		$locCode		= locCodeGenerator();
		
		$qry="UPDATE location_info SET locCode='".$locCode."' WHERE id=".$location." ";
		setData($qry);
	}
}
else
{
	echo 'Location Codes already assigned';
}


function getLocations()
{
	$locations=array();
	//$qry="SELECT * FROM location_info WHERE locCode IS NULL";
	$qry="SELECT * FROM location_info WHERE locCode IS NULL OR locCode=''";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$locations[]			=	$row['id']; //feeded
		}
	}
	return $locations;
}


function locCodeGenerator()
{
	$token = substr(md5(uniqid(rand(), true)),0,4);  // creates a 32 digit token
	//SELECT * FROM entrp_login where qrCode='70f804625753d84827ef993329c3b1b8'
   $qry = "SELECT * FROM location_info WHERE locCode='".$token."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      locCodeGenerator();
   } 
   else 
   {
      return $token;
   }	
}


?>