<?php

header('Content-Type: text/csv; charset=utf-8');
$filename="Location_Codes_" . date("Y-m-d");
header('Content-Disposition: attachment; filename='.$filename.'.csv');

require_once ('../api/Query.php'); 


$qry="SELECT location_info.id,location_info.location_desc,location_info.locCode,vo_country_list.country
		FROM location_info
		LEFT JOIN vo_country_list ON vo_country_list.country_code=location_info.country_code";

$res=getData($qry);
$count_res=mysqli_num_rows($res);
if($count_res>0)
{
	$output = fopen('php://output', 'w');
	// output the column headings
	fputcsv($output, array('id', 'Location', 'Location Code','Country'));
		
	while($row=mysqli_fetch_array($res))
	{
		fputcsv($output, array($row["id"],$row["location_desc"],$row["locCode"],$row["country"]));	
	}
}
else
{
	echo 'no data to export';
} 
?>