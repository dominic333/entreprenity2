<?php

//Function to fetch a user's currency
function fetchUserCurrency($userid,$voffClientID)
{
	//SELECT location_info.id,location_info.location_desc,location_info.currency 
	//FROM entrp_login
	//LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
	//LEFT JOIN location_info ON location_info.id=client_profile.client_location
	//WHERE entrp_login.clientid=1 AND entrp_login.status=1
 	$qry="SELECT location_info.id,location_info.location_desc,location_info.currency 
 			FROM entrp_login 
 			LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid 
 			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			WHERE entrp_login.clientid=".$userid." AND entrp_login.status=1 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$currency	=	$row['currency'];  					
		}
		return $currency;
	}
	else
	{
		return '';
	} 

}

//Function to fetch available user credits
//February 04,2017
function fetchAvailableUserCredits($voffClientID)
{
	//SELECT perm_core_credit FROM entrp_credit_core where client_id=12
 	$qry="SELECT perm_core_credit FROM entrp_credit_core  
			WHERE client_id=".$voffClientID." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$credits	=	$row['perm_core_credit'];  					
		}
		return $credits;
	}
	else
	{
		return 0;
	} 
}

//Function to fetch user voff id from clientid
//February 04,2017
function getUserVOfffromENId($userID)
{
	
	$qry="SELECT vof_clientid FROM entrp_login  
			WHERE clientid=".$userID." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['vof_clientid'];  					
		}
		return $id;
	}
	else
	{
		return 0;
	}   
	
}

//Function to get a user's country
//February 01,2017
function getUserCountryFromLocation()
{
	//SELECT location_info.country_code
	//FROM entrp_login
	//LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
	//LEFT JOIN location_info ON location_info.id=client_profile.client_location
	//WHERE entrp_login.status=1 AND entrp_login.clientid=1
	
	$country= '';
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id)
	{
	 	$qry="SELECT location_info.country_code
		FROM entrp_login
		LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
		LEFT JOIN location_info ON location_info.id=client_profile.client_location
		WHERE entrp_login.status=1 AND entrp_login.clientid=".$my_session_id."
      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
		if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	      {
	      	$country	=	$row['country_code'];
			}   	   	
	   }
	}
   return $country;
}

//Function to get item price id from item tag
//January 17,2017
function getItemPriceIDFromItemtag($itemTag)
{
	$id= 0;
 	$qry="SELECT id
			FROM entrp_products_pricing 
			WHERE itemTag='".$itemTag."'
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$id	=	$row['id'];
		}   	   	
   }
   return $id;
}


//Function to get list of user whose posts i don't wanna see
//October 07,2016
function postsFromUsersIdontLike($userID)
{
	$data= array();	
	$qry="SELECT clientId FROM entrp_hide_user_feeds WHERE myId=".$userID."";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[]			=	$row['clientId'];
		}		
	}
	return $data;
}


//Function to get list of posts I don't wanna see
//October 7,2016
function postsIdontLike($userID)
{
	$data= array();	
	$qry="SELECT postID FROM entrp_hide_user_post WHERE myId=".$userID."";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[]			=	$row['postID'];
		}		
	}
	return $data;
}


//Function to remove company profile pic path from table
//September 21,2016
function removeCompanyProfilePicPathFromDB($userID)
{
	$companyID	=	getCompanyIDFromCompUserRelation($userID);
	$qry="UPDATE company_profiles SET avatar='' WHERE id=".$companyID." ";
	setData($qry);
}

//Function to remove user image path from table
//September 21,2016
function removeUserProfilePicPathFromDB($userID)
{
	$qry="UPDATE client_profile SET avatar='' WHERE clientid=".$userID." ";
	setData($qry);
}

//Fetch location ids for all centers under a country
//September 08,2016
function fetchLocationIDsUnderCountry($ccode)
{
	$data= array();
	
	$qry="SELECT id FROM location_info WHERE country_code=".$ccode."";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[]			=	$row['id'];
		}		
	}
	return $data;
}


/* Functions and services based on userid begins */

//Function to fetch a user designation from company member relation table
//August 19,2016
function getUserDesignationFromCompUserRelation($userID)
{
	$designation= '';
 	$qry="SELECT designation
			FROM entrp_company_members 
			WHERE clientid=".$userID."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$designation	=	$row['designation'];
		}   	   	
   }
   return $designation;
}


//Function to fetch event id from event tag
//August 17,2016
function getEventTagfromEventId($eventId)
{
	
	$qry="SELECT eventTagId FROM entrp_events  
			WHERE id=".$eventId." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$eventTagId		=	$row['eventTagId'];  					
		}
		return $eventTagId;
	}
	else
	{
		return null;
	}   
	
}


//Function to get company description using company-user relationship
//August 10,2016
function getCompanyDescriptionUsingCompUserRelation($companyID)
{
	$companyDesc= '';
 	$qry="SELECT description
			FROM company_profiles 
			WHERE id=".$companyID."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$companyDesc			=	$row['description'];
		}
   	   	
   }
   return $companyDesc;
}

//Function to get company name using company-user relationship
//August 10,2016
function getCompanyNameUsingCompUserRelation($companyID)
{
	$companyN= '';
 	$qry="SELECT company_name
			FROM company_profiles 
			WHERE id=".$companyID."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$companyN			=	$row['company_name'];
		}   	   	
   }
   return $companyN;

}

//Function to get company username using company-user relationship
//August 10,2016
function getCompanyUserNameUsingCompUserRelation($companyID)
{
	$companyUN= '';
 	$qry="SELECT company_username
			FROM company_profiles 
			WHERE id=".$companyID."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$companyUN			=	$row['company_username'];
		}
   	   	
   }
   return $companyUN;

}

//Function to get company id of a user from company-user relationship
//August 10,2016
function getCompanyIDFromCompUserRelation($userID)
{
	$companyid= '';
 	$qry="SELECT companyid
			FROM entrp_company_members 
			WHERE clientid=".$userID."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$companyid			=	$row['companyid'];
		}
   	   	
   }
   return $companyid;
}


//Function to fetch user image path based on user id
//July 22,2016
//August 10, 2016: Changes after implementing company-user relation
function getCompanyProfilePicFromUserID($clientid)
{
	
	$avatar= '';	
	$companyID	=	getCompanyIDFromCompUserRelation($clientid);
	//Earlier this query fetch using clientid
 	$qry="SELECT avatar
			FROM company_profiles
			WHERE id=".$companyID."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$avatar			=	$row['avatar'];
		}
   	   	
   }
   return $avatar;

}


//Function to fetch user image path based on user id
//July 21,2016
function getUserProfilePicFromUserID($clientid)
{

	$avatar= '';
 	$qry="SELECT client_profile.avatar
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid=".$clientid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$avatar			=	$row['avatar'];
		}
   	   	
   }
   return $avatar;

}

//Function entreprenity login password using email id
//June 30,2016
function fetchUserLoginPassword($loginEmail,$userId)
{
	$qry="SELECT password FROM entrp_login  
			WHERE email='".$loginEmail."' AND clientid=".$userId." AND status=1 AND user_type=2 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$password		=	$row['password'];  					
		}
		return $password;
	}
	else
	{
		return null;
	}
}

//Function to fetch event id from event tag
//June 29,2016
function getEventIdfromEventTag($eventTagid)
{
	
	$qry="SELECT id FROM entrp_events  
			WHERE eventTagId='".$eventTagid."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['id'];  					
		}
		return $id;
	}
	else
	{
		return null;
	}   
	
}


//Function to fetch event details based on event tag
//June 21,2016
function fetchEventDetailsBasedonEventTAG($eventTag)
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

	$data= array();	
	
	$qry="SELECT entrp_events.*,entrp_event_categories.category_name 
			FROM entrp_events 
			LEFT JOIN entrp_event_categories ON entrp_events.category=entrp_event_categories.id
		   WHERE entrp_events.eventTagId='".$eventTag."'
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data['id']				=	$row['id'];
   		$eventid					= 	$data['id']	;
   		$data['name']			=	$row['eventName'];
   		$data['address']		=	$row['address'];
   		
   		$data['date']			=	$row['event_date'];
   		$data['startTime']	=	$row['start_time'];
   		$data['endTime']		=	$row['end_time'];
   		$data['clientid']	=	$row['clientid'];
   		if($row['poster']!='')
   		{
   			$data['poster']	=	$row['poster'];
   		}
   		else
   		{
   			$data['poster']	=	$events_default;
   		}
   		$data['about']			=	$row['description'];
   		$data['category']		=	$row['category_name'];
   		$data['map']['center']['latitude']		=	$row['location_lat'];
			$data['map']['center']['longitude']		=	$row['location_long'];
			$data['map']['zoom']	=	8;
			
			// newly fetched info starts
			$data['city']				=	$row['city'];
			$data['added_by']			=	$row['added_by'];
			$data['added_on']			=	$row['added_on'];
			$data['status']			=	$row['status'];
			$data['read_only']		=	$row['read_only'];
			$data['eventTagId']		=	$row['eventTagId'];
			// newly fetched info ends
   	}
		
		$data['attendees']=getEventAttendeesFromEventID($eventid);
	}
	else
	{
		$data['id']										=	'';
		$data['name']									=	'';
		$data['address']								=	'';
		$data['map']['center']['latitude']		=	'';
		$data['map']['center']['longitude']		=	'';
		$data['map']['zoom']							=	8;
		$data['date']									=	'';
		$data['startTime']							=	'';
		$data['endTime']								=	'';
		$data['eventPhoto']							=	'';
		$data['poster']								=	'';
		$data['about']									=	'';
   	$data['category']								=	'';
   	
   	$data['description']		=	'';
		$data['city']				=	'';
		$data['added_by']			=	'';
		$data['added_on']			=	'';
		$data['status']			=	'';
		$data['read_only']		=	'';
		$data['eventTagId']		=	'';
	}
	return $data;
} 


//Function to get company id from user id
//June 15,2016
//August 10, 2016: Changes after implementing company-user relation
function getCompanyIDfromUserID($userID)
{
	//SELECT id FROM company_profiles WHERE clientid=1;
	$companyID	=	getCompanyIDFromCompUserRelation($userID);
	
	$qry="SELECT id FROM company_profiles  
			WHERE id='".$companyID."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['id'];  					
		}
		return $id;
	}
	else
	{
		return null;
	} 
}

//Function to fetch members of a company (fetch id only)
//June 13,2016
function getAllCompanyMemberIDs($companyId)
{
	$data= array();
	
	$qry="SELECT clientid FROM entrp_company_members WHERE companyid=".$companyId."";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[]			=	$row['clientid'];
		}		
	}
	return $data;
}


//Function to get the author id of this timeline post
//June 08,2016
function whoIsTheAuthorOfThisPost($postID)
{
	//SELECT posted_by FROM entrp_user_timeline WHERE post_id=1 AND status=1
	$qry="SELECT posted_by FROM entrp_user_timeline   
			WHERE post_id='".$postID."' AND status=1 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$posted_by		=	$row['posted_by'];  					
		}
		return $posted_by;
	}
	else
	{
		return null;
	}

}


//Function to get company owner from company id
//June 07,2016
function getCompanyOwnerFromCOMPANYID($companyid)
{
	//SELECT clientid FROM company_profiles WHERE id=1;
	$qry="SELECT clientid FROM company_profiles  
			WHERE id='".$companyid."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['clientid'];  					
		}
		return $id;
	}
	else
	{
		return null;
	} 
}


//Function to get event host id from event id
//June 07.2016
function getEventHostFromEVENTID($eventId)
{
	//SELECT clientid FROM entrp_events WHERE id=1
	$qry="SELECT clientid FROM entrp_events  
			WHERE id='".$eventId."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['clientid'];  					
		}
		return $id;
	}
	else
	{
		return null;
	} 
}


//Function to get users (list of user id) I follow
//May 31,2016
function getAllUserIDsIFollow($myUserId)
{
	//SELECT follows FROM `entrp_user_follows` where clientid=1
	$data= array();
	
	$qry="SELECT follows FROM entrp_user_follows WHERE clientid=".$myUserId."";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[]			=	$row['follows'];
		}		
	}
	return $data;


}

//Function to get a company's follower list
//May 13,.2016
//August 12, 2016: Changes after implementing company-user relation
function getThisCompanyfollowerObjects($companyid)
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
	
	$i=0;
	
	$data= array();
	$qry="SELECT CP.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,CP.cover_pic 
			FROM entrp_company_follows AS ECF
			LEFT JOIN entrp_login AS EL ON EL.clientid=ECF.clientid
			LEFT JOIN client_profile AS CP ON CP.clientid=ECF.clientid
			WHERE ECF.companyid=".$companyid."";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[$i]['id']				=	$row['clientid'];
			
			$data[$i]['username']		=	$row['username'];
			
			if($row['avatar']!='')
			{
				$data[$i]['avatar']	=	$row['avatar'];
			}
			else
			{
				$data[$i]['avatar']	=	$member_default;
			} 
			
			if($row['cover_pic']!='')
			{
				$data[$i]['coverPhoto']	=	$row['cover_pic'];
			}
			else
			{
				$data[$i]['coverPhoto']	=	$member_default;
			} 
			
			$data[$i]['firstName']						=	$row['firstname'];
			$data[$i]['lastName']						=	$row['lastname'];
			 
			
			$data[$i]['position']						=	$row['designation'];
			//$data[$i]['company']['companyName']		=	$row['company_name'];
			
			$post_by											=	$row['clientid'];   
			$companyId										=	getCompanyIDfromUserID($post_by);
			$data[$i]['company']['companyName'] 	=  getCompanyNameUsingCompUserRelation($companyId);
			 			
			$i++;
		}		
	}
	return $data;
}



//Function to get a user's follower objects
//May 13,2016
//August 12, 2016: Changes after implementing company-user relation
function getThisUserfollowerObjects($clientid)
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
	
	$i=0;
	$data= array();
	$qry="SELECT CP.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,CP.cover_pic 
			FROM entrp_user_follows AS EUF
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUF.clientid
			LEFT JOIN client_profile AS CP ON CP.clientid=EUF.clientid
			WHERE EUF.follows=".$clientid."";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[$i]['id']				=	$row['clientid'];
			
			$data[$i]['username']		=	$row['username'];
			
			if($row['avatar']!='')
			{
				$data[$i]['avatar']	=	$row['avatar'];
			}
			else
			{
				$data[$i]['avatar']	=	$member_default;
			} 
			
			if($row['cover_pic']!='')
			{
				$data[$i]['coverPhoto']	=	$row['cover_pic'];
			}
			else
			{
				$data[$i]['coverPhoto']	=	$member_default;
			} 
			
			$data[$i]['firstName']						=	$row['firstname'];
			$data[$i]['lastName']						=	$row['lastname'];
			 
			
			$data[$i]['position']						=	$row['designation'];
			//$data[$i]['company']['companyName']		=	$row['company_name'];
			
			$post_by											=	$row['clientid'];   
			$companyId										=	getCompanyIDfromUserID($post_by);
			$data[$i]['company']['companyName']		=  getCompanyNameUsingCompUserRelation($companyId);
			 			
			$i++;
		}		
	}
	return $data;

}

//Function to get a user's following objects
//May 13,2016
function getThisUserfollowingObjects($clientid)
{
	/*
	"followingObjects": [
		{
			"id": 1,
			"username": "jordan",
			"avatar": "member01.jpg",
			"coverPhoto": "memberCover01.jpg",
			"firstName": "Jordan",
			"lastName": "Rains",
			"position": "Office Assistant",
			"company": [{
				"companyName": "Pet Studio.com",
				"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
			}]
		}
	]
	*/
	
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
	
	$i=0;
	$data= array();
	$qry="SELECT CP.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,CP.cover_pic 
			FROM entrp_user_follows AS EUF
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUF.follows
			LEFT JOIN client_profile AS CP ON CP.clientid=EUF.follows
			WHERE EUF.clientid=".$clientid."";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[$i]['id']				=	$row['clientid'];
			
			$data[$i]['username']		=	$row['username'];
			
			if($row['avatar']!='')
			{
				$data[$i]['avatar']	=	$row['avatar'];
			}
			else
			{
				$data[$i]['avatar']	=	$member_default_avatar;
			} 
			
			if($row['cover_pic']!='')
			{
				$data[$i]['coverPhoto']	=	$row['cover_pic'];
			}
			else
			{
				$data[$i]['coverPhoto']	=	$member_default_cover;
			} 
			
			$data[$i]['firstName']						=	$row['firstname'];
			$data[$i]['lastName']						=	$row['lastname'];
			 
			
			$data[$i]['position']						=	$row['designation'];
			$data[$i]['company']['companyName']		=	$row['company_name'];
			 			
			$i++;
		}		
	}
	return $data;
}


//Function to check going for this event or not
//May 13,2016
function goingForThisEventorNot($eventid)
{
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$qry="SELECT id FROM entrp_event_attendees WHERE clientid=".$my_session_id." AND eventid=".$eventid."";
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

//Function to get event attendees from eventid
//May 12,2016
//June 29,2016: Fetch username
function getEventAttendeesFromEventID($eventid)
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
	
   	$i=0;
   	$data2= array();
   	$qry2="SELECT entrp_event_attendees.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,client_profile.avatar,client_profile.designation,client_profile.city,client_profile.cover_pic 
				 FROM entrp_event_attendees 
				 LEFT JOIN entrp_login ON entrp_login.clientid=entrp_event_attendees.clientid 
				 LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
				 WHERE entrp_event_attendees.eventid=".$eventid."
				";
	   $res2=getData($qry2);
		$count_res2=mysqli_num_rows($res2);
		if($count_res2>0)
		{
			while($row2=mysqli_fetch_array($res2))
   		{
   			$data2[$i]['id']				=	$row2['clientid'];
   			$data2[$i]['firstName']		=	$row2['firstname'];
   			$data2[$i]['lastName']		=	$row2['lastname'];
   			$data2[$i]['userName']		=	$row2['username'];
   			if($row2['avatar']!='')
   			{
   				$data2[$i]['avatar']	=	$row2['avatar'];
   			}
   			else
   			{
   				$data2[$i]['avatar']	=	$member_default;
   			} 
   			
   			if($row2['cover_pic']!='')
   			{
   				$data2[$i]['coverPhoto']	=	$row2['cover_pic'];
   			}
   			else
   			{
   				$data2[$i]['coverPhoto']	=	$member_default;
   			}  
   			
   			$data2[$i]['position']	=	$row2['designation'];
   			$data2[$i]['city']		=	$row2['city'];
   			 			
   			$i++;
   		}		
		}
		return $data2;

}

//Function to get event from event id
//May 12,2016
//June 21,2016: New data fetched (newly fetched info)
function getEventFromEventID($eventid)
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

	$data= array();	
	
	$qry="SELECT entrp_events.*,entrp_event_categories.category_name 
			FROM entrp_events 
			LEFT JOIN entrp_event_categories ON entrp_events.category=entrp_event_categories.id
		   WHERE entrp_events.id=".$eventid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data['id']				=	$row['id'];
   		$data['name']			=	$row['eventName'];
   		$data['address']		=	$row['address'];
   		

   		$data['date']			=	$row['event_date'];
   		$data['startTime']	=	$row['start_time'];
   		$data['endTime']		=	$row['end_time'];
   		$data['clientid']		=	$row['clientid'];
   		if($row['poster']!='')
   		{
   			$data['poster']	=	$row['poster'];
   		}
   		else
   		{
   			$data['poster']	=	$events_default;
   		}
   		$data['about']			=	$row['description'];
   		$data['category']		=	$row['category_name'];
   		$data['map']['center']['latitude']		=	$row['location_lat'];
			$data['map']['center']['longitude']		=	$row['location_long'];
			$data['map']['zoom']	=	8;
			
			// newly fetched info starts
			$data['city']				=	$row['city'];
			$data['added_by']			=	$row['added_by'];
			$data['added_on']			=	$row['added_on'];
			$data['status']			=	$row['status'];
			$data['read_only']		=	$row['read_only'];
			$data['eventTagId']		=	$row['eventTagId'];
			// newly fetched info ends
   	}
		
		$data['attendees']=getEventAttendeesFromEventID($eventid);
	}
	else
	{
		$data['id']										=	'';
		$data['name']									=	'';
		$data['address']								=	'';
		$data['map']['center']['latitude']		=	'';
		$data['map']['center']['longitude']		=	'';
		$data['map']['zoom']							=	8;
		$data['date']									=	'';
		$data['startTime']							=	'';
		$data['endTime']								=	'';
		$data['eventPhoto']							=	'';
		$data['poster']								=	'';
		$data['about']									=	'';
   	$data['category']								=	'';
   	
   	$data['description']		=	'';
		$data['city']				=	'';
		$data['added_by']			=	'';
		$data['added_on']			=	'';
		$data['status']			=	'';
		$data['read_only']		=	'';
		$data['eventTagId']		=	'';
	}
	return $data;

		

}


//Function to fetch company profile from company id
//May 12,2016
function fetch_company_information_from_companyid($companyid)
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
	
	$data= array();

	$qry="SELECT company_profiles.*,
			 		 location_info.location_desc
			FROM company_profiles
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.id=".$companyid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['id']					=	$row['id']; 
			$data['name']		=	$row['company_name'];  	
			$data['companyUserName']=	$row['company_username'];		
			$data['location']			=	$row['located_at'];  			
			
			if($row['avatar']!='')
   		{
   			$data['profilePhoto']				=	$row['avatar'];
   		}
			else
			{
				$data['profilePhoto']				=	$company_default_avatar;
			} 
			
			if($row['cover_photo']!='')
   		{
   			$data['coverPhoto']				=	$row['cover_photo'];
   		}
			else
			{
				$data['coverPhoto']				=	$company_default_cover;
			}			 			
			 						 			 			
			$data['website']			=	$row['website'];
			$data['email']				=	$row['email'];   			
			$data['mobile']			=	$row['mobile'];  			
			$data['tel']				=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];
			$data['desc']				=	$row['description'];   
			$data['location']				=	$row['located_at'];   
			
			$data['followers']		=	entrp_company_follows($companyid);
			$data['categories']		= fetch_company_categories($companyid);			
		}
	}
	else 
	{
		$data['id']					=	'';  			
		$data['profilePhoto']	=	''; 		
		$data['coverPhoto']		=	''; 		
		$data['companyName']		=	''; 
		$data['companyUserName']='';
		$data['location']			=	'';   			
		$data['companyDesc']		=	''; 		
		$data['email']				=	''; 			
		$data['website']			=	'';  			
		$data['mobile']			=	''; 	
		$data['tel']				=	'';  			
		$data['fax']				=	''; 
	}
	
	return $data;

}



//Function to fetch total (count) users following a company
//May 05,2016
function entrp_company_follows($company_id)
{
	//To fetch user followers
	//SELECT COUNT(entrp_user_follows.clientid) AS followers FROM entrp_user_follows WHERE entrp_user_follows.follows=1
	$qry="SELECT COUNT(entrp_company_follows.clientid) AS followers 
			 FROM entrp_company_follows 
			 WHERE entrp_company_follows.companyid=".$company_id."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$count_followers 		= $row['followers'];
   	}
	}
	else
	{
		$count_followers 			= 0;
	}
	return $count_followers;
}



//Function to delete company categories
//May 05,2016 (Not in use now)
function delete_company_categories($company_id)
{
	$qry="DELETE FROM entrp_company_categories  
			WHERE companyid=".$company_id." ";
   setData($qry);

}


//Function to fetch user id from username
//May 10,2016
function getUserIdfromUserName($userName)
{
	
	$qry="SELECT clientid FROM entrp_login  
			WHERE username='".$userName."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['clientid'];  					
		}
		return $id;
	}
	else
	{
		return null;
	}   
	
}




//Function to fetch company user id from company username
//May 10,2016
function getCompanyIdfromCompanyUserName($companyUserName)
{
	
	$qry="SELECT id FROM company_profiles  
			WHERE company_username='".$companyUserName."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['id'];  					
		}
	}   
	return $id;
}


//Function to fetch company categories based on company id
//May 05,2016
function fetch_company_categories($company_id)
{
	$data= array();
	
	$qry="SELECT * FROM entrp_company_categories  
			WHERE companyid=".$company_id." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['category']);  					
		}
	}   
	return $data;
}


//Function to get a user's own company details based on user id
//May 03,2016
//August 10, 2016: Changes after implementing company-user relation
function fetch_company_information_from_userid($clientid)
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
	
	$data= array();
	/*
	$qry="SELECT company_profiles.*,
			 		 location_info.location_desc
			FROM company_profiles
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.clientid=".$clientid."
	      ";
	*/
	$role			=	getUserDesignationFromCompUserRelation($clientid);
	$companyID	=	getCompanyIDFromCompUserRelation($clientid);
	$qry="SELECT company_profiles.*,
			 		 location_info.location_desc
			FROM company_profiles
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.id=".$companyID."
	      ";
	      
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['id']					=	$row['id']; 
			$data['companyName']		=	$row['company_name'];  	
			$data['companyUserName']=	$row['company_username'];		
			$data['location']			=	$row['located_at'];  			
			
			if($row['avatar']!='')
   		{
   			$data['profilePhoto']				=	$row['avatar'];
   		}
			else
			{
				$data['profilePhoto']				=	$company_default_avatar;
			} 
			
			if($row['cover_photo']!='')
   		{
   			$data['coverPhoto']				=	$row['cover_photo'];
   		}
			else
			{
				$data['coverPhoto']				=	$company_default_cover;
			}			 			
			 						 			 			
			$data['website']			=	$row['website'];
			$data['email']				=	$row['email'];   			
			$data['mobile']			=	$row['mobile'];  			
			$data['tel']				=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];
			$data['companyDesc']		=	$row['description'];   
			
			$data['followers']		=	entrp_company_follows($data['id']);
			$data['categories']		= fetch_company_categories($data['id']);
			$data['role']	=  $role;				
		}
	}
	else 
	{
		$data['id']					=	'';  			
		$data['profilePhoto']	=	''; 		
		$data['coverPhoto']		=	''; 		
		$data['companyName']		=	''; 
		$data['companyUserName']='';
		$data['location']			=	'';   			
		$data['companyDesc']		=	''; 		
		$data['email']				=	''; 			
		$data['website']			=	'';  			
		$data['mobile']			=	''; 	
		$data['tel']				=	'';  			
		$data['fax']				=	''; 
	}
	
	return $data;

}


//Function to fetch a user's skill set
//May 02, 2016
//August 16: Change tags to lowercase
function get_user_skill_sets($userid)
{
	
	$data= array();
	$qry="SELECT * FROM entrp_user_skills  
			WHERE user_id=".$userid." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode(strtolower($row['skills']));  					
		}
	}   
	return $data;	
	
	
}


//Function to fetch a user's interest set
//May 02, 2016
function get_user_interest_sets($userid)
{	
	$data= array();
	$qry="SELECT * FROM entrp_user_interests  
			WHERE user_id=".$userid." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['interests']);  					
		}
	}   
	return $data;		
}

//Function to get total (count) followings of a user
//April 25,2016
function user_following($clientid)
{
	//To fetch user following
   //SELECT COUNT(entrp_user_follows.follows) AS following FROM entrp_user_follows WHERE entrp_user_follows.clientid=1
	$qry="SELECT COUNT(entrp_user_follows.follows) AS following 
			 FROM entrp_user_follows 
			 WHERE entrp_user_follows.clientid=".$clientid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$count_following 		= $row['following'];
   	}
	}
	else
	{
		$count_following 			= 0;
	}
	return $count_following;
}



//Function to get total (count) followers of a user
//April 25, 2016
function user_followers($clientid)
{
	//To fetch user followers
	//SELECT COUNT(entrp_user_follows.clientid) AS followers FROM entrp_user_follows WHERE entrp_user_follows.follows=1
	$qry="SELECT COUNT(entrp_user_follows.clientid) AS followers 
			 FROM entrp_user_follows 
			 WHERE entrp_user_follows.follows=".$clientid."
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$count_followers 		= $row['followers'];
   	}
	}
	else
	{
		$count_followers 			= 0;
	}
	return $count_followers;
}



//Function to get user information based on id
//May 03, 2016
//August 10, 2016: Changes after implementing company-user relation
//Sibling to viewUserProfile
function fetch_user_information_from_id($clientid)
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
	
	$data= array();		
	/*
	SELECT client_info.clientid,client_info.firstname,client_info.lastname,client_info.city,client_info.country,client_info.email,
			 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 location_info.location_desc,company_profiles.company_name,company_profiles.description
	FROM client_info
	LEFT JOIN client_profile ON client_info.clientid=client_profile.clientid
	LEFT JOIN location_info ON location_info.id=client_profile.client_location
	LEFT JOIN company_profiles ON company_profiles.clientid=client_info.clientid
	*/

   /*
  $qry="SELECT entrp_login.clientid,entrp_login.username,entrp_login.firstname,entrp_login.lastname,client_profile.city,client_profile.country,client_profile.contact_email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 client_profile.secondary_mobile,
			 		 location_info.location_desc,
			 		 company_profiles.company_name,company_profiles.description
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			LEFT JOIN company_profiles ON company_profiles.clientid=entrp_login.clientid
			WHERE entrp_login.clientid=".$clientid."
	      ";
	 */
	$qry="SELECT entrp_login.clientid,entrp_login.username,entrp_login.firstname,entrp_login.lastname,client_profile.city,client_profile.country,client_profile.contact_email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 client_profile.secondary_mobile,
			 		 location_info.location_desc
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			WHERE entrp_login.clientid=".$clientid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {

   	while($row=mysqli_fetch_array($res))
      {
      	$data['id']				=	$row['clientid'];
      	
			if(!empty($row['avatar']))
      	{
      		$data['avatar']				=	$row['avatar'];
      	}
      	else
      	{
      		$data['avatar']				=	$member_default_avatar;
      	}
      	
			if(!empty($row['cover_pic']))
      	{
      		$data['coverPhoto']			=	$row['cover_pic'];
      	}
      	else
      	{
      		$data['coverPhoto']			=	$member_default_cover;
      	}
      	
			$data['firstName'] 	= 	$row['firstname'];
			$data['lastName'] 	= 	$row['lastname'];
			$data['position'] 	= 	$row['designation'];
			$data['city'] 			= 	$row['city'];
			
			$data['aboutMe'] 		=  $row['about_me'];
			$data['email'] 		=  $row['contact_email'];
			$data['website'] 		=  $row['website'];
			$data['mobile'] 		=  $row['mobile'];
			$data['tel'] 			=  $row['secondary_mobile'];
			$data['userName']		=	$row['username'];

			$data['success'] = true;
			$data['msg'] = 'Profile fetched';
		}

		$companyID	=	getCompanyIDFromCompUserRelation($clientid);
		$data['company']['companyName'] 		= getCompanyNameUsingCompUserRelation($companyID);	
		$data['company']['companyDesc'] 		= getCompanyDescriptionUsingCompUserRelation($companyID);	
		
   	$data['skills'] 		= get_user_skill_sets($clientid);
   	$data['interests'] 	= get_user_interest_sets($clientid);
   	
   	//Function to get total followers of a user
		$data['followers'] 	= user_followers($clientid);
		
		//Function to get total followings of a user
		$data['following'] 	= user_following($clientid);
   	   	
   }
   else
   {
   	$data['success'] = false;
		$data['msg'] = 'User Not Found';
   }
   return $data;
}

//Function to get user information based on id
//May 25, 2016
//Arshad
//September 05,2016: Fetch firstname and lastname
//September 17,2016: Fetch voffice clientid
function fetch_info_from_entrp_login($clientid)
{
	$data = array();		
	$qry="SELECT *
			FROM entrp_login as L
			WHERE L.clientid=".$clientid."
	      ";
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
			
			$data['success'] = 'true';
		}
   }
   else
   {
   	$data['success'] = 'false';
	}
   return $data;
}



/* Functions and services based on userid ends */



?>