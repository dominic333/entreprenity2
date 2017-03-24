<?php
//action page for saving check in details
//Annie , march 17, 2017
if( !session_id() )
{
    session_start();
}
require_once ('../api/Query.php'); 
require_once ('../api/userDefinedFunctions.php'); 
require_once ('../api/externalLibraries/Mobile_Detect.php'); 
require_once ('centerFunctions.php'); 
require_once ('authenticate.php'); 

$clientid		 = $_POST['clientid'];
$vofClientId	 = $_POST['vofClientId'];
$locId   		 = $_POST['locId'];
$loginDate 		 = $_POST['loginDate'];
$checktype		 = $_POST['checktype'];//in db
$checkintype	 = $_POST['checkintype'];//new value

$t= $_POST['code'];
if($t !=""){

$code	=$_POST['code'];
}
else {
	$code = uniqueCheckinCode();
	}
	//echo $code;
//$code = uniqueCheckinCode();

$query= "SELECT * FROM location_info WHERE id =".$locId;
$res = getData($query);
while($row = mysqli_fetch_array($res))
{

$locCode = $row['locCode'];

}
 
/*echo $clientid;
echo $vofClientId;
echo $locId;
echo $loginDate;
echo $checktype;//
echo $checkintype;
*/
// 1 checkin 
//2 checkout
if($checkintype == 1)
{
$result = logUserIntoThisCenter($clientid,$vofClientId,$locId,1,$code);
//echo "hellooo";
}

else 
{
	$result 	= logUserIntoThisCenter($clientid,$vofClientId,$locId,2,$code);
	$query_1 = "SELECT * 
				 FROM entrp_center_login
				 WHERE  voffID =".$vofClientId." AND checkinCode='".$code."'";
	$result_1 =getData($query_1);
	$count_res1 = mysqli_num_rows($result_1);

	$checkIn=0;
	$r=0;
	$t=0;
	$checkOut=0;
	if($count_res1 > 0)
   {
   	while($row = mysqli_fetch_array($result_1))
      {
      	$data['checkinCode'] 	= $row['checkinCode'];
      	$data['checkIn'] 	= $row['checkIn'];
      	$data['checkType'] 	= $row['checkType'];
      	if($row['checkType'] == 1)
      	{
      	
				$r=$row['checkIn'];
				$checkIn=strtotime($row['checkIn']);   	
      	
      	}
      	else 
      	{
      		$t=$row['checkIn'];

				$checkOut=strtotime($row['checkIn']);   	
      	}
      	
      	
			
      }
    	  /*echo "this   ".$t."-".$r."</br>";
      	echo $checkOut." - ".$checkIn;*/
      	
      	$diff = abs($checkOut - $checkIn);

			$minutes   = round($diff / 60,2);

//			echo "Diffrence".$minutes."minutes";
//			echo date('H:i:s',$diff);

		//update query for updation of cowork hours
		$clientfacilitiesquery	= "SELECT * 
											FROM client_facilities_core
											WHERE client_id =".$vofClientId;

		$resultfacilities	=	getData($clientfacilitiesquery);
//		echo $clientfacilitiesquery;									
		$count_facilities = mysqli_num_rows($resultfacilities);								
		if($count_facilities >0)
		{
			while($row = mysqli_fetch_array($resultfacilities))
      	{
      			$facilities['co_work_hours_limit']		=		$row['co_work_hours_limit'];
      			$facilities['co_work_hours_left']		=		$row['co_work_hours_left'];
      	}
      		
			$co_work_left			=		round(($facilities['co_work_hours_limit']-$diff),2);

//			echo "Time".$co_work_left;
//			echo "else".$co_work_left;

			
			$updatefacilities		=		"UPDATE client_facilities_core SET co_work_hours_left=".$co_work_left." WHERE client_id=".$vofClientId;
				
//			echo $updatefacilities;								
			$re 						=		setData($updatefacilities);								
		
		
		}	
		}
		

	

}

if($result)
{

header("Location: dumypage.php?location=".$locCode); 
exit();

}
else {

echo "failed";	
}




?>
<html>

<head></head>
<body>
<h3>test</h3>

</body>
</html>