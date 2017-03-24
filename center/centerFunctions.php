<?php

//Upcoming Event List
function getUpcomingEventsForCenter($location)
{
	//SELECT * FROM entrp_events where clientid IN (SELECT clientid FROM client_profile where client_location=14)
	$data = array();		
	$today = date('Y-m-d'); 
	$qry="SELECT * FROM entrp_events 
			WHERE clientid IN (SELECT clientid FROM client_profile WHERE client_location=".$location.") AND status=1 and event_date>='".$today."' ";
	$res = getData($qry);
   $count_res = mysqli_num_rows($res);
	if($count_res > 0)
   {
   	$i=0;
   	while($row = mysqli_fetch_array($res))
      {
      	$data[$i]['id'] 				= $row['id'];
      	$data[$i]['clientid'] 		= $row['clientid'];
      	$data[$i]['eventName'] 		= $row['eventName'];
      	$data[$i]['eventTagId'] 	= $row['eventTagId'];
      	$data[$i]['category'] 		= $row['category'];
      	$data[$i]['address']			= $row['address'];
			$data[$i]['description']	= $row['description'];			      	
			
			if($row['poster']!='')
			{
				$data[$i]['poster']		=	$row['poster'];
			}
			else
			{
				$data[$i]['poster']		=	'assets/img/events/events-default.jpg';
			}	      	
			
			$data[$i]['city'] 		  	= $row['city'];	
			$data[$i]['eventDate'] 		= $row['event_date'];	
			$data[$i]['eventTime'] 		= $row['event_time'];	
			$data[$i]['eventDateTime'] = $row['event_date_time'];	
			$data[$i]['startTime'] 		= $row['start_time'];	
			$data[$i]['endTime'] 		= $row['end_time'];				

			$eventDateTimestamp = strtotime($row['event_date']);
			$eventDayFormatted = date('l, F d', $eventDateTimestamp);  //Saturday, January 30
			$monthFormatted = date('F', $eventDateTimestamp);  //January
			$eventDFormatted = date('d', $eventDateTimestamp);  //30
			
			$data[$i]['eventDayFormatted'] 		= $eventDayFormatted;	
			$data[$i]['monthFormatted'] 		  	= $monthFormatted;	
			$data[$i]['eventDFormatted'] 		  	= $eventDFormatted;	
			
			$eventStartFormatted			= date('h:i a', strtotime($row['start_time']));
			$eventEndFormatted			= date('h:i a', strtotime($row['end_time']));
			
			$data[$i]['eventStartFormatted'] 	= $eventStartFormatted;	
			$data[$i]['eventEndFormatted'] 		= $eventEndFormatted;	
			$i++;
		}
   }
   return $data;
}


//Function to get user's company name
function getCompanyName($entrpID)
{
	$companyName 	= 'Not Specified';
	//SELECT company_profiles.company_name FROM entrp_company_members LEFT JOIN company_profiles ON company_profiles.id=entrp_company_members.companyid WHERE entrp_company_members.clientid=1
	$qry = "SELECT company_profiles.company_name 
			  FROM entrp_company_members 
			  LEFT JOIN company_profiles ON company_profiles.id=entrp_company_members.companyid 
			  WHERE entrp_company_members.clientid=".$entrpID."";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      while($row = mysqli_fetch_array($res))
      {
      	$companyName 	= $row['company_name'];
		}
   } 
	return $companyName;
}


//Function to login a user into center
function logUserIntoThisCenter($clientid,$vofClientId,$locId,$clocktype,$code)
{
	date_default_timezone_set('UTC');
	$loginDate=date("Y-m-d");
	$loginDateTime=date("Y-m-d H:i:s");
	$status=1;

	$qry="INSERT INTO entrp_center_login(entrpID,voffID,locID,loginDate,checkIn,checkType,checkinCode,status) VALUES(".$clientid.",".$vofClientId.",".$locId.",'".$loginDate."','".$loginDateTime."',".$clocktype.",'".$code."',".$status.") ";
//	echo $qry;
	$result = setData($qry);
	return $result;
}

//Function to fetch user info using qrcode token
function fetchUserInfoUsingQRCode($qrCode)
{
	$data = array();		
	$qry="SELECT L.*,C.avatar
			FROM entrp_login as L
			LEFT JOIN client_profile AS C ON L.clientid=C.clientid
			WHERE L.qrCode='".$qrCode."'";
	$res = getData($qry);
   $count_res = mysqli_num_rows($res);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($res))
      {
      	$data['clientid'] 	= $row['clientid'];
      	$data['username'] 	= $row['username'];
      	$data['email'] 		= $row['email'];
      	$data['firstname'] 	= $row['firstname'];
      	$data['lastname'] 	= $row['lastname'];
      	$data['voffStaff']	= $row['voff_staff'];
			$data['vofClientId']	= $row['vof_clientid'];			      	
			
			if($row['avatar']!='')
			{
				$data['avatar']	=	$row['avatar'];
			}
			else
			{
				$data['avatar']	=	'assets/img/members/member-default.jpg';
			}	      	
			
			$data['success'] = 'true';
		}
   }
   return $data;

}


//Function to check whether user qrcode is valid or not
function validateUserQRCode($qrCode)
{
	$qry = "SELECT * FROM entrp_login WHERE qrCode='".$qrCode."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      return 1;
   } 
   else 
   {
      return 0;
   }
}

//Function to get users for given location
//Annie, March 17, 2017
function getUsersForLocation($locId)
{
   //	$locId = $_SESSION['locId'];
	$data = array();		
	$checkout1=array();
	$query =   "SELECT EL.*,E.* 
					FROM entrp_center_login as EL 
					LEFT JOIN entrp_login as E on EL.voffID = E.vof_clientid 
					WHERE locID = ".$locId." 
					AND EL.checkType=1
					ORDER BY EL.checkIn DESC
					"; 
					//
	//$query = "SELECT E.* from entrp_login AS E "; 
	//echo $query;
	$result = getData($query);
	$count_res = mysqli_num_rows($result);
   //	echo $count_res;
	$i=0;
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($result))
      {
      	$data[$i]['clientid'] 	= $row['clientid'];
      	$data[$i]['username'] 	= $row['username'];
      	$data[$i]['email'] 		= $row['email'];
      	$data[$i]['firstname'] 	= $row['firstname'];
      	$data[$i]['lastname'] 	= $row['lastname'];
      	$data[$i]['voffStaff']	= $row['voff_staff'];
			$data[$i]['vofClientId']	= $row['vof_clientid'];			      				
			$data[$i]['loginDate']	= $row['loginDate'];			      				
			$data[$i]['checkType']	= $row['checkType'];			      				
			$data[$i]['checkIn']		= $row['checkIn'];			      				
			$data[$i]['checkinCode']	= $row['checkinCode'];			      				
			$data[$i]['success'] 	= 'true';
			$data[$i]['checkout']	=	getCheckOut($row['clientid'],$row['loginDate'],$row['locID'],$row['checkinCode']);
//			print_r($checkout1);
			/*foreach($checkout1 as $row1)
			{
				$data[$i]['checkout']=$row1['checkIn'];
			
			}*/
//			$data[$i]['time']			=	calculateTotalHrs($row['clientid'],$row['loginDate'],$row['locID'],$row['checkinCode']);
			/*while($r = mysqli_fetch_array($resp))
      	{
						$data[$i]['checkout']=$r['checkIn'];
			}*/

			$i++;
		}
   }
//      print_r($data);
   return $data;


}

//
//
function getCheckOut($clientid,$loginDate,$locId,$code)
{
	
	$data 	=	array();
	$i=0;
	$query	=	"SELECT * 
					FROM entrp_center_login
					WHERE entrpID ='".$clientid."' AND loginDate = '".$loginDate."' AND checkType =2 AND locID =".$locId." AND checkinCode='".$code."'
					ORDER BY checkIn DESC"; //DESC LIMIT 1";
	$result = getData($query);
//	echo $query;
	$i=0;
	$count_res = mysqli_num_rows($result);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($result))
      {
      	$checkIn =    $row['checkIn']  ;	
			$i++;
			
		}
   }
   else {
   	$checkIn="";
   }

//  print_r($checkIn);
  return $checkIn;
	


}
//
//

function calculateTotalHrs($clientid,$loginDate,$locId)
{
	$data=array();


	$query = "SELECT * 
				 FROM entrp_center_login
				 WHERE  voffID =".$clientid." AND loginDate = '".$loginDate."' AND locID =".$locId." order by id ";
//				 echo $query;
	$result =getData($query);
	$i=0;
	$checkTime = 0;
	$time = 0;
	$checkin=array();
	$checkout=array();

	$count_res = mysqli_num_rows($result);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($result))
      {
      	
      	//$checkTime =    $checkTime - strtotime($row['checkIn']) ;	
      	$data[$i]['checkinCode'] 	= $row['checkinCode'];
      	$data[$i]['checkIn'] 	= $row['checkIn'];
      	$data[$i]['checkType'] 	= $row['checkType'];
      	if($row['checkType'] == 1)
      	{
      	
				array_push($checkin,$row['checkIn']);      	
      	
      	}
      	else 
      	{
      		array_push($checkout,$row['checkIn'])	;
      	}
      	
      	
      	
      	
      	
			$i++;
		}
	
		for($j = 0; $j < count($checkin); $j++)
		{
			if(!$checkout[$j])
			{
				$checkout[$j] = "0000-00-00 00:00:00";
			}
			$diff = strtotime($checkout[$j]) - strtotime($checkin[$j]);
//			echo $diff;
			$checkTime = $checkTime+$diff/3600;
		
		
		}
		
	
	//	return $checkTime;
		
   }
   else 
   {
   	return 0;
   }
//		return $checkTime;


}

// Function to get check in type of clients
//Annie, March 17 , 2017
function fetchUserCheckInType($loginDate,$locId,$clientid)
{
	
	$data 	=		array();
	$query   =		"SELECT * 
						 FROM entrp_center_login 
						 WHERE locID = '".$locId."'AND entrpID ='".$clientid."' 
						 ORDER BY id DESC LIMIT 1  ";//loginDate ='".$loginDate."'AND
	$result = getData($query);
	$count_res = mysqli_num_rows($result);
	if($count_res > 0)
   {
     while($row = mysqli_fetch_array($result))
     {
     		$data['checktype'] 	= $row['checkType']; //1 or 2
     		$data['code'] 		= $row['checkinCode']; //1 or 2
     }
   } 
   else 
   {
      $data['checktype']="";
      $data['code'] 	="";
   }
//   print_r($data);
   return $data;


}

//Function to generate unique code
//Annie,March 20, 2017
function uniqueCheckinCode()
{
	//echo"codeeeeeeeeeeeee";
	$token = substr(md5(uniqid(rand(), true)),0,10);  // creates a 10 digit token
	//SELECT * FROM `entrp_user_timeline` where post_img like '%timelineimgdominic.ronquillo20160816080631.jpeg%'
   $qry = "SELECT * FROM entrp_center_login where checkinCode like '%$token%'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueCheckinCode();
   } 
   else 
   {
      return $token;
   }	
}
//function to get credit left for the client
//Annie, march 21,2017
function getCreditLeft($vofClientId) 
{
	
/*	$query	=	"SELECT * FROM entrp_credit_core WHERE client_id=".$vofClientId;
//	echo $query;
	$result	=	getData($query);
	$count_result = mysqli_num_rows($result);
	if($count_result > 0)
	{
	
	  while($row = mysqli_fetch_array($result))
     {
     	/*	$data['monthly_credit'] 	= $row['monthly_credit']; //1 or 2
     		$data['monthly_core_credit'] 		= $row['monthly_core_credit']; //1 or 2
   		$data['total_hours']	=0.3*$data['monthly_core_credit'];
     
     //1 co-hour 	= 	1 credit = 60 co-minutes
     //1 co-min	=	1/60 credit	=0.017 credits
     //say 18 co-min,0.017*18 =0.306 credits
     
     		$data['creditsUsed'] = 0.017*$time;
     		$data['creditLeft']=$data['monthly_core_credit']-$data['creditsUsed'];*/
     		
		//}
	/*	$update = "UPDATE entrp_core_credit SET monthly_credit =".$data['creditLeft']."WHERE entrpID =".$vofClientId;
		$update_res =getData($update);
	
	}
	else
	{
		return 0;
	}
	return $data;*/
	
	$clientfacilitiesquery	= "SELECT * 
											FROM client_facilities_core
											WHERE client_id =".$vofClientId;
											
		$resultfacilities	=	getData($clientfacilitiesquery);									
		$count_facilities = mysqli_num_rows($resultfacilities);								
		if($count_facilities >0)
		{
			while($row = mysqli_fetch_array($resultfacilities))
      	{
      			$facilities['co_work_hours_limit']		=		$row['co_work_hours_limit'];
      			$facilities['co_work_hours_left']		=		$row['co_work_hours_left'];
      	}
		
		
		}	
		else 
		{
			return 0;
		
		}
		return $facilities;
	
	
	
	
}

function updateTime($vofClientId,$code)
{

	$facilities=array();
	$query_1 = "SELECT * 
				 FROM entrp_center_login
				 WHERE  voffID =".$vofClientId." AND checkCode=' ".$code."'";
	$result_1 =getData($query_1);
	$count_res = mysqli_num_rows($result_1);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($result_1))
      {
      	$data['checkinCode'] 	= $row['checkinCode'];
      	$data['checkIn'] 	= $row['checkIn'];
      	$data['checkType'] 	= $row['checkType'];
      	if($row['checkType'] == 1)
      	{
      	
				//array_push($checkin,$row['checkIn']);   
				$checkIn=$row['checkIn'];   	
      	
      	}
      	else 
      	{
//      		array_push($checkout,$row['checkIn'])	;
				$checkOut=$row['checkIn'];   	
      	}
      	
      	$diff = strtotime($checkOut) - strtotime($checkIn);
//			echo $diff;
			$checkTime = $checkTime+$diff/60;
			echo $checkTime;
      }

		//update query for updation of cowork hours
		$clientfacilitiesquery	= "SELECT * 
											FROM client_facilities_core
											WHERE client_id =".$vofClientId;

		$resultfacilities	=	getData($clientfacilitiesquery);									
		$count_facilities = mysqli_num_rows($resultfacilities);								
		if($count_facilities >0)
		{
			while($row = mysqli_fetch_array($resultfacilities))
      	{
      			$facilities['co_work_hours_limit']		=		$row['co_work_hours_limit'];
      			$facilities['co_work_hours_left']		=		$row['co_work_hours_left'];
      	}
      		
			$co_work_left			=		$facilities['co_work_hours_limit']-	$checkTime;
			
			$updatefacilities		=		"UPDATE client_facilities_core SET co_work_hours_left =".$co_work_left."WHERE client_id =".$vofClientId;
				
			echo $updatefacilities;								
			$re 						=		setData($updatefacilities);								
		
		
		}	
		else { return 0;}
   }
   return $facilities;



}



?>