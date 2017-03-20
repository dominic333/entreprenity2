<?php

//Function to finish an add event process
//June 24,2016
function finishThisEvent()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$eTag = $requestData->eventTag;
		$eventTag=validate_input($eTag);
		
		$qry="DELETE FROM entrp_events_users_tags WHERE eventTag='".$eventTag."'";
		if(setData($qry))
		{	
			//send mail to admin
		   send_new_event_notification_to_admin($eventTag);
		   $data['msg'] = 'success';
		}
		else
		{
			 $data['msg'] = 'failed';
		} 
		
	}
	return $data;
}


//Function to generate unique event tag
//June 15,2016
function generateUniqueEventTag()
{
	$token = substr(md5(uniqid(rand(), true)),0,6);  // creates a 6 digit token
   $qry = "SELECT id FROM entrp_events WHERE eventTagId = '".$token."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      generateUniqueEventTag();
   } 
   else 
   {
      return $token;
   }	
}

//Function to add a new event's details
//June 15,2016
//June 22,2016: added string to time conversion for event date
function addNewEvent()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$name = $requestData->eventName;
		$eventName=validate_input($name);
		
		$category = $requestData->eventCategory;
		$eventCategory=validate_input($category);
		
		$description = $requestData->eventDescription;
		$eventDescription=validate_input($description);
		
		$date = $requestData->eventDate;
		$eventDate1=validate_input($date);
		$eventDate=date('Y-m-d',strtotime($eventDate1));
		
		$startTime = $requestData->eventStartTime;
		$eventStartTime=validate_input($startTime);
		
		$endTime = $requestData->eventEndTime;
		$eventEndTime=validate_input($endTime);
		
		$city = $requestData->eventCity;
		$eventCity=validate_input($city);
		
		$location = $requestData->eventLocation;
		$eventLocation=validate_input($location);
		
		$locLat = $requestData->eventLocLat;
		$eventLocLat=validate_input($locLat);
		
		$locLong = $requestData->eventLocLong;
		$eventLocLong=validate_input($locLong);
		
		$poster = $requestData->poster;
		$poster = str_replace('data:image/png;base64,', '', $poster);
		//$poster = str_replace(' ', '+', $poster);
		$poster = base64_decode($poster);
		$extension = ".png";
		$eventPosterName = uniqueEventPostersName();
		$fileName 	 = EVENT_POSTER.$eventPosterName.$extension;
		$filePath 	 = EVENT_POSTER_UPL.$eventPosterName.$extension;
		$success = file_put_contents($filePath, $poster);
		if($success == false){
			$fileName = '';
		}
		//$eventLocation='';
		//$eventLocLat='';
		//$eventLocLong='';
		$eventDateTime=$eventDate.' '.$eventStartTime;
		$eventTag=generateUniqueEventTag();
		$companyID=getCompanyIDfromUserID($my_session_id);
		$addedON=date('Y-m-d H:i:s');
		$status=0;
		
		$qry="INSERT INTO entrp_events 
			   (clientid,companyid,eventName,eventTagId,category,address,description,
			    city,event_date,event_time,event_date_time,
			    start_time,end_time,location_lat,location_long,
			    added_by,added_on,status,poster)
			   VALUES 
			   (".$my_session_id.", ".$companyID.",'".$eventName."','".$eventTag."',".$eventCategory.",'".$eventLocation."','".$eventDescription."',
			    '".$eventCity."','".$eventDate."','".$eventStartTime."','".$eventDateTime."',
			    '".$eventStartTime."','".$eventEndTime."','".$eventLocLat."','".$eventLocLong."',
			    ".$my_session_id.",'".$addedON."',".$status.",'".$fileName."')";//$fileName
		if(setData($qry))
		{
			
			$qry2="INSERT INTO entrp_events_users_tags (userID,eventTag) 
			      VALUES (".$my_session_id.",'".$eventTag."')";
			setData($qry2);
			
		   $data['eventToken'] = $eventTag;
		} 
		
	}
	return $data;
}

//Function to fetch members directory
//April 13,2015
//August 11, 2016: Changes after implementing company-user relation
function getMembers()
{	
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends	
	
	$limit = 10;
	if(isset($_GET['page']))
	{
		$page = validate_input($_GET['page']);
		if($page)
		{
			$start = ($page - 1) * $limit;
		}
		else
		{
			$start = 0;
		}		
	}
	else
	{
		$start = 0;
	}	
	
   $sorterMin=1;
   $sorterMax=5;
   $sorter= rand($sorterMin,$sorterMax);
   
   $sortCondition="A.clientid";
   switch ($sorter) 
   {
    case 1:
        $sortCondition="A.clientid";
        break;
    case 2:
        $sortCondition="A.firstname";
        break;
    case 3:
        $sortCondition="A.username";
        break;
    case 4:
        $sortCondition="A.company_name";
        break;
    case 5:
        $sortCondition="A.lastname";
        break;
    default:
        $sortCondition="A.clientid";
        break;
	}
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$data= array();	
	//ORDER BY CI.clientid ASC 
	$qry="SELECT A.* FROM (SELECT CI.clientid,CI.firstname,CI.lastname,CI.username,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM entrp_login AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
	      WHERE CI.clientid!=".$my_session_id."
	      ORDER BY if(CP.avatar = '' or CP.avatar is null,1,0), CI.clientid
	      LIMIT  $start, $limit) AS A ORDER BY ".$sortCondition."	      
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	if(!empty($row['clientid']))
      	{
      		$data[$i]['id']				=	$row['clientid'];
      	}
      	else
      	{
      		$data[$i]['id']				=	"";
      	}
      	
      	if(!empty($row['firstname']))
      	{
      		$data[$i]['firstName']		=	$row['firstname'];
      	}
      	else
      	{
      		$data[$i]['firstName']		=	"";
      	}
      	
      	if(!empty($row['username']))
      	{
      		$data[$i]['userName']		=	$row['username'];
      	}
      	else
      	{
      		$data[$i]['userName']		=	"";
      	}
			
			if(!empty($row['lastname']))
      	{
      		$data[$i]['lastName']		=	$row['lastname'];
      	}
      	else
      	{
      		$data[$i]['lastName']		=	"";
      	}
			
			if(!empty($row['avatar']))
      	{
      		//$img= '../'.$row['avatar'];
      		//$data[$i]['avatar']			=	cacheThisImage($img);
      		$data[$i]['avatar']			=	$row['avatar'];
      	}
      	else
      	{
      		$data[$i]['avatar']			=	$member_default;
      	}
			
			if(!empty($row['designation']))
      	{
      		$data[$i]['position']		=	$row['designation'];
      	}
      	else
      	{
      		$data[$i]['position']		=	"";
      	}
			
			$companyId							=	getCompanyIDfromUserID($data[$i]['id']);
			$data[$i]['companyName'] 		= getCompanyNameUsingCompUserRelation($companyId);
			/*
			if(!empty($row['company_name']))
      	{
      		$data[$i]['companyName']	=	$row['company_name'];
      	}
      	else
      	{
      		$data[$i]['companyName']	=	"";
      	}
			*/
			
			if(!empty($row['city']))
      	{
      		$data[$i]['city']				=	$row['city'];
      	}
      	else
      	{
      		$data[$i]['city']				=	"";
      	}
      	
      	$data[$i]['followed']=doIFollowThisUser($my_session_id,$data[$i]['id']);
      	
      	
			$i++;
      }	
   }
   //$data2= $data;
	//$data= shuffle($data);
	return $data;
}



//Function to fetch company directory
// April 13,2015
function getCompanies()
{	

	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	$limit = 10;
	if(isset($_GET['page']))
	{
		$page = validate_input($_GET['page']);
		if($page)
		{
			$start = ($page - 1) * $limit;
		}
		else
		{
			$start = 0;
		}		
	}
	else
	{
		$start = 0;
	}
	
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
			
	$qry="SELECT  CP.id,CP.clientid,CP.company_name,CP.description,CP.avatar,CP.company_username,LI.location_desc AS city 
			FROM company_profiles AS CP
			LEFT JOIN location_info as LI ON LI.id=CP.client_location 
			ORDER BY if(CP.avatar = '' or CP.avatar is null,1,0), CP.company_username
			LIMIT $start, $limit";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['companyName']	=	$row['company_name'];
			$data[$i]['description']	=	$row['description'];

			if($row['avatar']!='')
			{
				$data[$i]['avatar']			=	$row['avatar'];
			}
			else
			{
				$data[$i]['avatar']			=	$company_default_avatar;
			}
			$data[$i]['city']				=	$row['city'];
			$data[$i]['userName']		=	$row['company_username'];
			
			$data[$i]['followed']=doIFollowThisCompany($my_session_id,$data[$i]['id']);
			$i++;
      }	
   }

	return $data;	
}



//Function to fetch events directory
// April 13,2015
//June 22,2016: added WHERE clause
function getEvents()
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends	
	
	$limit = 10;
	if(isset($_GET['page']))
	{
		$page = validate_input($_GET['page']);
		if($page)
		{
			$start = ($page - 1) * $limit;
		}
		else
		{
			$start = 0;
		}		
	}
	else
	{
		$start = 0;
	}	
	
	$data= array();
	
	$qry="SELECT * FROM entrp_events 
			WHERE status IN (1,2) 
			ORDER BY event_date_time DESC 
			LIMIT $start, $limit";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['eventName']		=	$row['eventName'];
			$data[$i]['eventTagId']		=	$row['eventTagId'];
			$data[$i]['description']	=	$row['description'];
			if($row['poster']!='')
			{
				$data[$i]['poster']			=	$row['poster'];
			}
			else
			{
				$data[$i]['poster']			=	$event_default_poster;
			}
			
			$data[$i]['city']				=	$row['city'];
			$data[$i]['date_time_formatted']				=	date('Y/m/d H:i:s', strtotime($row['event_date_time']));
			$data[$i]['date']				=	$row['event_date'];
			$data[$i]['time']				=	$row['event_time'];
			$data[$i]['joining']			=	goingForThisEventorNot($data[$i]['id']);
			$i++;
      }	
   }
	return $data;
}



?>