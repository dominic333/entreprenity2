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
function logUserIntoThisCenter($clientid,$vofClientId,$locId,$clocktype)
{
	date_default_timezone_set('UTC');
	$loginDate=date("Y-m-d");
	$loginDateTime=date("Y-m-d H:i:s");
	$status=1;
	$qry="INSERT INTO entrp_center_login(entrpID,voffID,locID,loginDate,checkIn,checkType,status) VALUES(".$clientid.",".$vofClientId.",".$locId.",'".$loginDate."','".$loginDateTime."',".$clocktype.",".$status.") ";
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
	$query =   "SELECT EL.*,E.* 
					FROM entrp_center_login as EL 
					LEFT JOIN entrp_login as E on EL.voffID = E.vof_clientid 
					WHERE locID = ".$locId." ORDER BY EL.checkIn DESC"; 
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
			$data[$i]['checkIn']	= $row['checkIn'];			      				
			$data[$i]['success'] = 'true';
			$resp =calculateTotalHrs($row['clientid'],$row['loginDate']);

			$i++;
		}
   }
   return $data;


}

//
//
function calculateTotalHrs($clientid,$loginDate)
{

	$data 	=	array();
	$query	=	"SELECT * 
					FROM entrp_center_login
					WHERE clientid ='".$clientid."' AND loginDate = '".$loginDate."' AND checkType =2";
	echo $query;
	$count_res = mysqli_num_rows($query);
	if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($query))
      {
      	$data['checkIn'] =    $row['checkIn']  ;	
			
		}
   }
   			print_r($data);
   return $data;
	


}

// Function to get check in type of clients
//Annie, March 17 , 2017
function fetchUserCheckInType($loginDate,$locId,$clientid)
{
	
	$data 	=		array();
	$query   =		"SELECT * 
						 FROM entrp_center_login 
						 WHERE loginDate ='".$loginDate."'AND locID = '".$locId."'AND entrpID ='".$clientid."' 
						 ORDER BY id DESC LIMIT 1  ";
	$result = getData($query);
	$count_res = mysqli_num_rows($result);
	if($count_res > 0)
   {
     while($row = mysqli_fetch_array($result))
     {
     		$checktype = $row['checkType']; //1 or 2
     }
   } 
   else 
   {
      $checktype = 0;
   }
   return $checktype;


}


?>