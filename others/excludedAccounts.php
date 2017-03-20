<?php

header('Content-Type: text/csv; charset=utf-8');
$filename="excluded_users_php_" . date("Y-m-d");
header('Content-Disposition: attachment; filename='.$filename.'.csv');

require_once ('../api/Query.php'); 

//Philippines locations
//location IN(14,21,26,31,33,42,39,51)

/*
SELECT clientid 
FROM client_info 
WHERE location IN(14,21,26,31,33,42,39,51) AND status=1 
AND email!='' AND email!='n/a n/a' 
AND coname!='' AND coname NOT IN ('Myvoffice DEMO account','Voffice Demo','na','none yettttttttttttttt') 
GROUP BY email
*/

//SELECT * FROM client_info WHERE clientid IN(44603) AND status=1 ORDER BY coname 


function getLocations()
{
	$locationString='';
	//SELECT id FROM location_info WHERE currency='PHP'
	$qry="SELECT id FROM location_info WHERE currency='PHP'";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$locations[]			=	$row['id']; //feeded
		}
		$locationString = implode(",", $locations);
	}
	return $locationString;

}


$locationString=getLocations();	

//SELECT clientid,firstname,lastname,email,coname,location FROM `client_info` WHERE status=1 AND location IN(14,21,26,31,33,42,39,51) AND clientid NOT IN (select vof_clientid from entrp_login)
$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CI.email,CI.coname,LI.location_desc,CI.location 
		FROM client_info AS CI
		LEFT JOIN location_info AS LI ON LI.id=CI.location
		WHERE CI.status=1 
		AND CI.location IN(".$locationString.") 
		AND CI.clientid NOT IN (SELECT vof_clientid FROM entrp_login)";
		//echo $qry;
$res=getData($qry);
$count_res=mysqli_num_rows($res);
if($count_res>0)
{
	$output = fopen('php://output', 'w');
	// output the column headings
	fputcsv($output, array('clientid', 'Name', 'Email','Company','Location','Location Id'));
		
	while($row=mysqli_fetch_array($res))
	{
		fputcsv($output, array($row["clientid"],$row["firstname"].' '.$row["lastname"],$row["email"],$row["coname"],$row["location_desc"],$row["location"]));	
	}
}
else
{
	echo 'no excluded user accounts';
} 


?>