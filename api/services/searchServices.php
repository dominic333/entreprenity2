<?php



//Function for universal search
//October 25,2016
function searchResultsFor()
{

	//Search against 
	//Client Profiles - first name, last name, email, clientid, username, location
	//Company Profiles- company name, company username, location
	//Upcoming Events- Event Name, location, category
	//Timeline Posts- posts
	//BO Posts- posts, tags
	$data= array();
	$query = validate_input($_POST['query']);
	if(strlen($query)>=3)
	{
		$data["users"]			=	searchUserInfo($query);
		$data["companies"]	=	searchCompanyInfo($query);
		$data["events"]		=	searchEvents($query);
		$data["bussOpp"]		=	searchBusinessOpportunities($query);
	}
	return $data;
}

//Function to do a search on business opportunities
//October 27,2016
function searchBusinessOpportunities($query)
{
	//SELECT entrp_user_timeline.*
	//FROM entrp_user_timeline
	//LEFT JOIN entrp_user_timeline_businessopp_tags ON entrp_user_timeline.post_id=entrp_user_timeline_businessopp_tags.postid
	//WHERE status=1 AND entrp_user_timeline.business_opp=1 
	
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
	$statusQuery='entrp_user_timeline.status=1 AND entrp_user_timeline.business_opp=1';
	$qry="SELECT entrp_user_timeline.*,entrp_login.firstname,entrp_login.lastname,entrp_login.username,client_profile.avatar
			FROM entrp_user_timeline
			LEFT JOIN entrp_user_timeline_businessopp_tags ON entrp_user_timeline.post_id=entrp_user_timeline_businessopp_tags.postid
			LEFT JOIN entrp_login ON entrp_login.clientid=entrp_user_timeline.posted_by
			LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
			WHERE (".$statusQuery.") 
			AND (entrp_user_timeline_businessopp_tags.business_tags like '%$query%' OR entrp_user_timeline.content like '%$query%' ) ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']			=	$row['post_id'];
      	$data[$i]['content']		=	$row['content'];
      	
			$data[$i]['user']['firstName']		=	$row['firstname'];
			$data[$i]['user']['lastName']			=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['user']['avatar']		=	$row['avatar'];
			}
			else
			{
				$data[$i]['user']['avatar']		=	$member_default_avatar;
			}
			$data[$i]['user']['userName']			=	$row['username'];
      	
      	$i++;
      }
   }
   return $data;
}

//Function to do a search on events
//October 27,2016
function searchEvents($query)
{
	//SELECT entrp_events.*,entrp_event_categories.category_name,location_info.location_desc
	//FROM entrp_events
	//LEFT JOIN entrp_event_categories ON entrp_event_categories.id=entrp_events.category
	//LEFT JOIN entrp_login ON entrp_login.clientid=entrp_events.clientid
	//LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
	//LEFT JOIN location_info ON location_info.id=client_profile.client_location
	//WHERE entrp_events.status IN (1,2) AND location_info.location_desc like '%global%'
	
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
	$statusQuery='entrp_events.status IN (1,2)';
	$qry="SELECT entrp_events.*,entrp_event_categories.category_name,location_info.location_desc  
			FROM entrp_events 
			LEFT JOIN entrp_event_categories ON entrp_event_categories.id=entrp_events.category
			LEFT JOIN entrp_login ON entrp_login.clientid=entrp_events.clientid
			LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			WHERE (".$statusQuery.") 
			AND (location_info.location_desc like '%$query%' OR entrp_events.eventName like '%$query%' 
			     OR entrp_events.city like '%$query%' OR entrp_events.eventTagId like '%$query%' OR entrp_event_categories.category_name like '%$query%' ) ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']			=	$row['id'];
      	$data[$i]['eventName']	=	$row['eventName'];
      	$data[$i]['eventTagId']	=	$row['eventTagId'];
      	$data[$i]['eventDate']	=	$row['event_date'];
      	$data[$i]['location']	=	$row['location_desc'];
      	if($row['poster']!='')
			{
				$data[$i]['poster']			=	$row['poster'];
			}
			else
			{
				$data[$i]['poster']			=	$event_default_poster;
			}
			$data[$i]['eventUrl']			=	'events/'.$row['eventTagId'];
      	
      	$i++;
      }
   }
   return $data;
}


//Function to do a basic search on user information
//October 26,2016
function searchUserInfo($query)
{	
	//SELECT entrp_login.username, entrp_login.firstname,entrp_login.lastname 
	//FROM entrp_login 
	//WHERE entrp_login.firstname like '%dominic%' OR entrp_login.lastname like '%dominic%' OR CONCAT(entrp_login.firstname, ' ',entrp_login.lastname) like '%dominic%' 
	//AND entrp_login.status=1
	
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
	$statusQuery='entrp_login.status=1';
	$qry="SELECT entrp_login.clientid,entrp_login.username, entrp_login.firstname,entrp_login.lastname,client_profile.avatar, location_info.location_desc AS userLocation
			FROM entrp_login 
			LEFT JOIN entrp_user_skills ON entrp_user_skills.user_id=entrp_login.clientid 
			LEFT JOIN entrp_user_interests ON entrp_user_interests.user_id=entrp_login.clientid 
			LEFT JOIN client_profile ON client_profile.clientid=entrp_login.clientid
	      LEFT JOIN location_info ON location_info.id=client_profile.client_location
			WHERE (".$statusQuery.") 
			AND (entrp_login.firstname like '%$query%' OR entrp_login.lastname like '%$query%' OR CONCAT(entrp_login.firstname, ' ',entrp_login.lastname) like '%$query%'
			     OR entrp_user_interests.interests like '%$query%' OR entrp_user_skills.skills like '%$query%' ) ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['clientid']	=	$row['clientid'];
      	$data[$i]['username']	=	$row['username'];
      	$data[$i]['firstname']	=	$row['firstname'];
      	$data[$i]['lastname']	=	$row['lastname'];
      	
      	if(!empty($row['avatar']))
      	{
      		$data[$i]['avatar']			=	$row['avatar'];
      	}
      	else
      	{
      		$data[$i]['avatar']			=	$member_default;
      	}
      	
      	$companyId							=	getCompanyIDFromCompUserRelation($data[$i]['clientid']);
			$data[$i]['companyName'] 		= getCompanyNameUsingCompUserRelation($companyId);
      	
      	$i++;
      }
   }
   return $data;
}


//Function to fetch basic company info
//October 27, 2016
function searchCompanyInfo($query)
{
	//SELECT company_profiles.* 
	//FROM company_profiles 
	//LEFT JOIN entrp_company_categories ON entrp_company_categories.companyid=company_profiles.id
	//WHERE company_profiles.company_username like '%dominic%' OR company_profiles.company_name like '%dominic%' OR entrp_company_categories.category like '%first%'
	
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
	$qry="SELECT company_profiles.*,location_info.location_desc  
			FROM company_profiles 
			LEFT JOIN entrp_company_categories ON entrp_company_categories.companyid=company_profiles.id 
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.company_username like '%$query%' OR company_profiles.company_name like '%$query%' 
			      OR entrp_company_categories.category like '%$query%' OR location_info.location_desc like '%$query%' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0;
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['compId']		=	$row['id'];
      	$data[$i]['compUName']	=	$row['company_username'];
      	$data[$i]['compName']	=	$row['company_name'];
      	$data[$i]['city']			=	$row['city'];
      	$data[$i]['location']	=	$row['location_desc'];
      	
      	if($row['avatar']!='')
			{
				$data[$i]['avatar']			=	$row['avatar'];
			}
			else
			{
				$data[$i]['avatar']			=	$company_default_avatar;
			}
      	
      	$i++;
      }
   }
   return $data;
}

?>