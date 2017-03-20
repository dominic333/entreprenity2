<?php


//Function to update user skills
//May 06,2016
function update_user_skills($my_session_id,$skills_json)
{
	$qry1="SELECT id FROM entrp_user_skills WHERE user_id=".$my_session_id."";
	$res=getData($qry1);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	$qry2="UPDATE entrp_user_skills SET skills='".$skills_json."' WHERE user_id=".$my_session_id." ";
		setData($qry2); 
   }
   else
   {
   	$qry3="INSERT INTO entrp_user_skills ( user_id, skills) VALUES (".$my_session_id.", '".$skills_json."')";
		setData($qry3);  
   }
}

//Function to update user interests
//May 06,2016
function update_user_interests($my_session_id,$interests_json)
{
	$qry1="SELECT id FROM entrp_user_interests WHERE user_id=".$my_session_id."";
	$res=getData($qry1);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	$qry2="UPDATE entrp_user_interests SET interests='".$interests_json."' WHERE user_id=".$my_session_id." ";
		setData($qry2); 
   }
   else
   {
   	$qry3="INSERT INTO entrp_user_interests ( user_id, interests) VALUES (".$my_session_id.", '".$interests_json."')";
		setData($qry3);  
   }
}


//Function to update a user's profile information
//May 03,2016
function updateMyProfileDetails()
{

	$data= array();
	$user_interests=array();
	$user_skills=array();
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$firstName		=validate_input($_POST['firstName']);
	$lastName		=validate_input($_POST['lastName']);
	$position		=validate_input($_POST['position']);
	$aboutMe			=validate_input($_POST['aboutMe']);
	$website			=validate_input($_POST['website']);
	$mobile			=validate_input($_POST['mobile']);
	$tel				=validate_input($_POST['tel']);
	$contact_email	=validate_input($_POST['email']);
	
	
	if(!empty($_POST['skills']))
	{
		$count_skills=count($_POST['skills']);
		for($i=0;$i<$count_skills;$i++)	
		{
			$user_skills[$i]=$_POST['skills'][$i]['text'];
		}
	}	
	
	if(!empty($_POST['interests']))
	{
		$count_interests=count($_POST['interests']);
		for($i=0;$i<$count_interests;$i++)	
		{
			$user_interests[$i]=$_POST['interests'][$i]['text'];
		}
	}	

	$skills_json		= json_encode($user_skills);
	update_user_skills($my_session_id,$skills_json); 

	$interests_json	= json_encode($user_interests);
	update_user_interests($my_session_id,$interests_json);
	
	$qry="UPDATE client_profile SET designation='".$position."', mobile='".$mobile."',contact_email='".$contact_email."',secondary_mobile='".$tel."',website='".$website."',about_me='".$aboutMe."' WHERE clientid=".$my_session_id." ";
	$qry2="UPDATE entrp_login SET firstname='".$firstName."', lastname='".$lastName."' WHERE clientid=".$my_session_id." ";
   if(setData($qry) && setData($qry2))
   {
   	//updation successful
   	
   	$data=fetch_user_information_from_id($my_session_id);
		$data['success'] 		= true;
		$data['msg'] 			= 'User Profile updated.'; 
   }
   else
   {
   	$data['success'] 		= false;
		$data['msg'] 			= 'Something went wrong. Profile not updated.'; 
   }
   
	return $data;

}



//Function to fetch a user's own details
//April 28,2016
//August 16,2016: HTML character encoding support
function getMyProfileDetails()
{
	
/*
{
	"avatar": "member01.jpg",
	"coverPhoto": "memberCover01.jpg",
	"firstName": "Ken",
	"lastName": "Sia",
	"position": "Front-end Web Developer",
	"Location": "Fort Legend Tower",
	"aboutMe": "Front-end Web Developer who loves listening to music, surfing, and traveling",
	"email": "ken.voffice@gmail.com",
	"website": "ken.com.ph",
	"mobile": "09175296299",
	"tel": "0229131533"
	"skills": [
		"Programming",
		"Public Speaking"
	],
	"interests": [
		"Design",
		"Surf",
		"Basketball"
	]
}

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

	$data= array();
   
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$userid=$my_session_id;
	if($userid)
	{
			/*
			$qry="SELECT entrp_login.clientid,entrp_login.username,entrp_login.firstname,entrp_login.lastname,client_profile.city,client_profile.country,client_profile.contact_email,
					 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.secondary_mobile,client_profile.website,client_profile.about_me,
					 		 location_info.location_desc,
					 		 company_profiles.company_name,company_profiles.description
					FROM entrp_login
					LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
					LEFT JOIN location_info ON location_info.id=client_profile.client_location
					LEFT JOIN company_profiles ON company_profiles.clientid=entrp_login.clientid
					WHERE entrp_login.clientid=".$userid."
			      ";
			*/
			$qry="SELECT entrp_login.clientid,entrp_login.username,entrp_login.firstname,entrp_login.lastname,client_profile.city,client_profile.country,client_profile.contact_email,
					 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.secondary_mobile,client_profile.website,client_profile.about_me,
					 		 location_info.location_desc 
					FROM entrp_login
					LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
					LEFT JOIN location_info ON location_info.id=client_profile.client_location
					WHERE entrp_login.clientid=".$userid."
			      ";
			$res=getData($qry);
		   $count_res=mysqli_num_rows($res);
		   if($count_res>0)
		   {
		   	while($row=mysqli_fetch_array($res))
		   	{
		   		if($row['avatar']!='')
   				{
   					$data['avatar']				=	$row['avatar'];
   				}
   				else
   				{
   					$data['avatar']				=	$member_default_avatar;
   				}
   				
		   		$data['coverPhoto']		=	$row['cover_pic'];
		   		$data['firstName']		=	$row['firstname'];
		   		$data['lastName']			=	$row['lastname'];
		   		$data['position']			=	$row['designation'];
		   		$data['Location']			=	$row['location_desc'];
		   		$data['aboutMe']			=	htmlspecialchars_decode($row['about_me'],ENT_QUOTES);
		   		$data['email']				=	$row['contact_email'];
		   		$data['website']			=	$row['website'];
		   		$data['mobile']			=	$row['mobile'];
		   		$data['userName']			=	$row['username'];
		   		$data['tel']				=	$row['secondary_mobile'];
		   	}
		   	
		   	 //fetch user skills
		   	 $data['skills']				= get_user_skill_sets($userid);
		   	 
		   	 //fetch user interests
		   	 $data['interests']			= get_user_interest_sets($userid);
		   	 
		   	 //Function to get total followers of a user
				 $data['followers'] 			= user_followers($userid);
		
				 //Function to get total followings of a user
				 $data['following'] 			= user_following($userid);
		   	   
		   }
		   else
		   {
				$data['avatar']			=	'';
				$data['coverPhoto']		=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['position']			=	'';
				$data['Location']			=	'';
				$data['aboutMe']			=	'';
				$data['email']				=	'';
				$data['website']			=	'';
				$data['mobile']			=	'';
				$data['tel']				=	'';
				$data['userName']				=	'';
		   
		   }
		   //fetch all skills
		   //$data['allSkills'] 		= get_all_skill_sets();
		   	
		   //fetch all interests
		   //$data['allInterests'] 	= get_all_interest_sets();	
	
	}
	return $data;
}

//Function to fetch a user's preferences
//May 24,2016
//Arshad
//Updated By Dominic: Added business opportunities (June 08,2016)
function getMyPreferences()
{
	
	$data = array();
	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	$userid = $my_session_id;
	if($userid)
	{
			$qry = "SELECT *
					  FROM entrp_user_notification_preferences as UP
					  LEFT JOIN entrp_languages as L ON L.lang_id = UP.language
					  LEFT JOIN entrp_timezones as T ON T.timezone_id = UP.timezone
					  WHERE UP.clientid = ".$userid."
			       ";
			$res = getData($qry);
		   $count_res = mysqli_num_rows($res);
		   if($count_res > 0)
		   {
		   	while($row = mysqli_fetch_array($res))
		   	{
		   		$data['languages'] = get_all_languages();
		   		$data['timezones'] = get_all_timezones();
		   		
		   		$data['selectedLanguage'] = array('id' => $row['lang_id'], 'text' => $row['lang_name'], 'image' => $row['lang_image']);
		   		$data['selectedTimezone'] = array('id' => $row['timezone_id'], 'text' => $row['timezone_name']);
		   		
		   		
		   		$data['followers'] = ($row['follow'] == 1 ? true : false);
		   		$data['comments'] = ($row['comment'] == 1 ? true : false);
		   		$data['likes'] = ($row['likes'] == 1 ? true : false);
		   		$data['mentions'] = ($row['mention'] == 1 ? true : false);
		   		$data['businessOpportunities'] = ($row['business_opportunity'] == 1 ? true : false);
		   	}
		   	
		   }
		   else
		   {
				$data['languages'] = '';
	   		$data['timezones'] = '';
	   		
	   		$data['selectedLanguage'] = '';
	   		$data['selectedTimezone'] = '';
	   		
	   		
	   		$data['followers'] = '';
	   		$data['comments'] = '';
	   		$data['likes'] = '';
	   		$data['mentions'] = '';
		   
		   }
	}
	return $data;
}

//Function to fetch a user's preferences
//May 24,2016
//Arshad
//Updated By Dominic: Added business opportunities (June 08,2016)
function updateMyPreferences()
{
	
	$data = array();
	
	$language = validate_input($_POST['selectedLanguage']['id']);
	$timezone = validate_input($_POST['selectedTimezone']['id']);
	$follow = ($_POST['followers'] == 'true'  ? 1 : 0);
	$comment = ($_POST['comments'] == 'true'  ? 1 : 0 );
	$likes = ($_POST['likes'] == 'true'  ? 1 : 0);
	$mention = ($_POST['mentions'] == 'true' ? 1 : 0);
	$businessOpportunities = ($_POST['businessOpportunities'] == 'true' ? 1 : 0);
	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	$userid = $my_session_id;
	
	if($userid)
	{
		$qry = "UPDATE entrp_user_notification_preferences SET language='".$language."', timezone='".$timezone."', follow='".$follow."', mention='".$mention."', comment='".$comment."', likes='".$likes."',business_opportunity='".$businessOpportunities."'  
				  WHERE clientid=".$my_session_id." ";
		if(setData($qry)){
			$data = getMyPreferences();
			$data['success'] 		= true;
			$data['msg'] 			= 'Prefences has been updated.'; 
		}else{
			$data['success'] 		= false;
			$data['msg'] 			= 'Something went wrong. Could not update preferences. Please try again'; 
		} 
	}else{
		
	}
	return $data;
}


?>