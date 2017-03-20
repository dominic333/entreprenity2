<?php



//***** EVENT GOING/NOT GOING From EVENT DIRECTORY PAGE *****

//Function to mark going for an event (from event directory)
//May 13,2016
function goingToEvent()
{
	$data= array();  
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_event_attendees(eventid,clientid,date_time) VALUES(".$eventId.",".$my_session_id.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$Host=getEventHostFromEVENTID($eventId);
		$notify_type="attend";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=$eventId;
		$notify_for="events";
		addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
		
		$data['joining']=true;
	}
	else
	{
		$data['joining']=false;
	}
	return $data;

}

//Function to mark going for an event (from event directory)
//May 13,2016
function notGoingToEvent()
{
	$data= array();  
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="DELETE FROM entrp_event_attendees WHERE eventid=".$eventId." AND clientid=".$my_session_id."
	      ";
	if(setData($qry))
	{
		
		$Host=getEventHostFromEVENTID($eventId);
		$notify_type="attend";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=$eventId;
		$notify_for="events";
		deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
		
		$data['joining']=false;
	}
	else
	{
		$data['joining']=true;
	}
	return $data;
}


//***** EVENT GOING/NOT GOING From EVENT DETAILS PAGE *****


//Function to mark going for an event
//May 12,2016
function goingForEvent()
{
	$data= array();
	   
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_event_attendees(eventid,clientid,date_time) VALUES(".$eventId.",".$my_session_id.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=true;
		
		$Host=getEventHostFromEVENTID($eventId);
		$notify_type="attend";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=$eventId;
		$notify_for="events";
		addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=false;
	}
	return $data;

}

//Function to mark not going for an event
//May 12,2016
function notGoingForEvent()
{
	$data= array();
	   
   $eventId=validate_input($_POST['event']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="DELETE FROM entrp_event_attendees WHERE eventid=".$eventId." AND clientid=".$my_session_id."
	      ";
	if(setData($qry))
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=false;
		
		$Host=getEventHostFromEVENTID($eventId);
		$notify_type="attend";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=$eventId;
		$notify_for="events";
		deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data=getEventFromEventID($eventId);
		$data['joining']=true;
	}
	return $data;

}


//***** CHECK USER/COMPANY FOLLOW*****

//Function to check whether the current user follows this company or not
//May 12,2016
function doIFollowThisCompany($my_session_id,$companyid)
{
	$qry="SELECT id FROM entrp_company_follows WHERE clientid=".$my_session_id." AND companyid=".$companyid."";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	return true;
   }
   else
	{
		return false;
	}
}

//Function to check whether the current user follows the other user or not
//May 11,2016
function doIFollowThisUser($my_session_id,$clientid)
{
  	$qry="SELECT id FROM entrp_user_follows WHERE clientid=".$my_session_id." AND follows=".$clientid."";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	return true;
   }
   else
	{
		return false;
	}
}


//***** COMPANY FROM PROFILE *****

//Function to follow a company from company profile
//May 12,2016
function followThisCompany()
{
	$data= array();
	   
   $companyUserName=validate_input($_POST['company']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_company_follows(clientid,companyid,date_time) VALUES(".$my_session_id.",".$companyid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=true;
		
		$Host=getCompanyOwnerFromCOMPANYID($companyid);
		$notify_type="company";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="company";
		addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=false;
	}
	return $data;
}

//Function to un-follow a company from company profile
//May 12,2016
function unfollowThisCompany()
{
	$data= array();
   
   $companyUserName=validate_input($_POST['company']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_company_follows WHERE clientid=".$my_session_id." AND companyid=".$companyid."";
	      
	if(setData($qry))
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=false;
		
		$Host=getCompanyOwnerFromCOMPANYID($companyid);
		$notify_type="company";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="company";
		deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data=fetch_company_information_from_companyid($companyid);
		$data['followed']=true;
	}
	return $data;
}


//***** COMPANY FROM DIRECTORY *****

//Function to follow a company from company directory
//May 12,2016
function followCompany()
{
	$data= array();  
   $companyid=validate_input($_POST['company']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_company_follows(clientid,companyid,date_time) VALUES(".$my_session_id.",".$companyid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data['followed']=true;
		
		$Host=getCompanyOwnerFromCOMPANYID($companyid);
		$notify_type="company";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="company";
		addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data['followed']=false;
	}
	return $data;

}

//Function to un-follow a company from company directory
//May 12,2016
function unfollowCompany()
{
	$data= array();
   $companyid=validate_input($_POST['company']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_company_follows WHERE clientid=".$my_session_id." AND companyid=".$companyid."";
	      
	if(setData($qry))
	{
		$data['followed']=false;
		
		$Host=getCompanyOwnerFromCOMPANYID($companyid);
		$notify_type="company";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="company";
		deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data['followed']=true;
	}
	return $data;

}

//***** MEMBER FROM DIRECTORY *****

//Function to follow a user from member directory
//May 11,2016

//Edited on May 25, 2016
//Arshad
//To add email notification functionality
function followUser()
{
	$data = array();	   
   $clientid=validate_input($_POST['user']);
   
   $followed_user_details = fetch_info_from_entrp_login($clientid);
	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];	
	$userName	= $session_values['username'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_user_follows(clientid,follows,follow_date_time) VALUES(".$my_session_id.",".$clientid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		$data['followed']=true;
		
		$Host=$clientid;
		$notify_type="follow";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="user";
		addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
		/*
		$data['myPreferences'] = getMyPreferences();
		
		if($data['myPreferences']['followers'] == 'true'){
			$notification_array = array(
											'type' => 'follow',
											'following_user_id' => $my_session_id,
											'following_username' => $userName,
											'followed_email' => $followed_user_details['email'],
											'followed_username' => $followed_user_details['username']
										);
			$data['mail_send'] = send_notification_mail($notification_array);
		}
		*/
	}
	else
	{
		$data['followed']=false;
	}
	return $data;
}

//Function to unfollow a user from member directory
//May 11,2016
function unfollowUser()
{
 	$data= array();
   $clientid=validate_input($_POST['user']);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_user_follows WHERE clientid=".$my_session_id." AND follows=".$clientid."";
	      
	if(setData($qry))
	{
		$data['followed']=false;
		
		$Host=$clientid;
		$notify_type="follow";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="user";
		deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		$data['followed']=true;
	}
	return $data;
}

//***** MEMBER FROM PROFILE *****

//Function to follow a user from his/her profile view
//May 11,2016
//August 18,2016: No more fetching whole user info
function followThisUser()
{
	$data= array();
	   
   $userName=validate_input($_POST['user']);
	$clientid=getUserIdfromUserName($userName);
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	$timeofaction=date('Y-m-d H:i:s');
	
	$qry="INSERT INTO entrp_user_follows(clientid,follows,follow_date_time) VALUES(".$my_session_id.",".$clientid.",'".$timeofaction."')
	      ";
	if(setData($qry))
	{
		//$data=fetch_user_information_from_id($clientid);
		$data['followed']=true;
		
		$Host=$clientid;
		$notify_type="follow";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="user";
		addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
		/*
		$data['myPreferences'] = getMyPreferences();
		
		if($data['myPreferences']['followers'] == 'true'){
			$notification_array = array(
											'type' => 'follow',
											'following_user_id' => $my_session_id,
											'following_username' => $userName,
											'followed_email' => $followed_user_details['email'],
											'followed_username' => $followed_user_details['username']
										);
			$data['mail_send'] = send_notification_mail($notification_array);
		}
		*/
	}
	else
	{
		//$data=fetch_user_information_from_id($clientid);
		$data['followed']=false;
	}
	return $data;
}


//Function to un-follow a user from his/her profile view
//May 11,2016
//August 18,2016: No more fetching whole user info
function unfollowThisUser()
{
	$data= array();
   
   $userName=validate_input($_POST['user']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="DELETE FROM entrp_user_follows WHERE clientid=".$my_session_id." AND follows=".$clientid."";
	      
	if(setData($qry))
	{
		//$data=fetch_user_information_from_id($clientid);
		$data['followed']=false;
		
		$Host=$clientid;
		$notify_type="follow";
		$notify_to=$Host;
		$notify_from=$my_session_id;
		$post_id=0;
		$notify_for="user";
		deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
	}
	else
	{
		//$data=fetch_user_information_from_id($clientid);
		$data['followed']=true;
	}
	return $data;
}





?>