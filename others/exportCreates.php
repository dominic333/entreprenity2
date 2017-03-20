<?php

header('Content-Type: text/csv; charset=utf-8');
$filename="MetropolitanUsers_" . date("Y-m-d");
header('Content-Disposition: attachment; filename='.$filename.'.csv');

require_once ('../app/api/Query.php'); 


$qry="SELECT clientid, firstname, lastname,email,coname FROM `client_info` where location=46 and status=1";

$res=getData($qry);
$count_res=mysqli_num_rows($res);
if($count_res>0)
{
	$output = fopen('php://output', 'w');
	// output the column headings
	fputcsv($output, array('id', 'Name', 'Email','Company'));
		
	while($row=mysqli_fetch_array($res))
	{
		fputcsv($output, array($row["id"],$row["firstname"].' '.$row["lastname"],$row["email"],$row["coname"]));	
	}
}
else
{
	echo 'no data to export';
} 
?>