<?php

/* My Company Profile Services Begins  */



//Function to fetch company members
//August 18,2016
function fetchThisCompanyMembers($companyId)
{	
	$data= array();	
	
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
	
	$allCompanyMemberIds=getAllCompanyMemberIDs($companyId);
	
	if(!empty($allCompanyMemberIds))
	{
		$allCompanyMemberString = implode(",", $allCompanyMemberIds);
	
		$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CI.username,CP.designation,CP.avatar 
		      FROM entrp_login AS CI 
		      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
		      WHERE CI.clientid IN (".$allCompanyMemberString.") AND CI.status=1
		      ORDER BY CI.clientid ASC	      
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
	      		$data[$i]['username']		=	$row['username'];
	      	}
	      	else
	      	{
	      		$data[$i]['username']		=	"";
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
	      		$data[$i]['profilePhoto']			=	$row['avatar'];
	      	}
	      	else
	      	{
	      		$data[$i]['profilePhoto']			=	$member_default;
	      	}
				
				$i++;
	      }	
	   }		
	}
	return $data;
}



//Function to insert company categories
//May 05,2016
function	update_company_categories($company_id,$company_categories)
{
	//INSERT INTO entrp_company_categories (`id`, `companyid`, `category`) VALUES (NULL, '1', 'xcvxc');
	$qry1="SELECT id FROM entrp_company_categories WHERE companyid=".$company_id."";
	$res=getData($qry1);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	$qry2="UPDATE entrp_company_categories SET category='".$company_categories."' WHERE companyid=".$company_id." ";
		setData($qry2); 
   }
   else
   {
   	$qry3="INSERT INTO entrp_company_categories ( companyid, category) VALUES (".$company_id.", '".$company_categories."')";
		setData($qry3);  
   }
}



//Function to update a user's company profile information
//May 03,2016
function updateMyCompanyDetails()
{

	$data= array();
	$company_categories=array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$role			=	getUserDesignationFromCompUserRelation($my_session_id);
	
	$companyName		=validate_input($_POST['companyName']);
	$companyDesc		=validate_input($_POST['companyDesc']);
	$email				=validate_input($_POST['email']);
	$website				=validate_input($_POST['website']);
	$mobile				=validate_input($_POST['mobile']);
	$telephone			=validate_input($_POST['tel']);
	$fax					=validate_input($_POST['fax']);
	
	
	if(!empty($_POST['categories']))
	{
		$count_category=count($_POST['categories']);
		for($i=0;$i<$count_category;$i++)	
		{
			$company_categories[$i]=$_POST['categories'][$i]['text'];
		}
	}	
	$companyID=getCompanyIDFromCompUserRelation($my_session_id);
	$qry="UPDATE company_profiles SET company_name='".$companyName."', description='".$companyDesc."', email='".$email."',website='".$website."',mobile='".$mobile."'
			,telephone='".$telephone."',fax='".$fax."' 
			WHERE id=".$companyID." ";
   if(setData($qry))
   {
   	//updation successful
   	$data=fetch_company_information_from_userid($my_session_id);
   	
   	$company_id=$data['id'];
		$category_json= json_encode($company_categories);
		//delete_company_categories($company_id);
		update_company_categories($company_id,$category_json);   	
   	$data['categories']	= fetch_company_categories($company_id);
   	
   	$data['followers']	= entrp_company_follows($company_id);
   	
		$data['success'] 		= true;
		$data['msg'] 			= 'Company Profile updated.'; 
   }
   else
   {
   	$data['success'] 		= false;
		$data['msg'] 			= 'Something went wrong. Profile not updated.'; 
   }
	return $data;

}


//Function to get a user's own company details
//May 03,2016
//August 10, 2016: Changes after implementing company-user relation
//August 16,2016: HTML character encoding support	
function getMyCompanyProfileDetails()
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
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$companyID	=	getCompanyIDFromCompUserRelation($my_session_id);
	$role			=	getUserDesignationFromCompUserRelation($my_session_id);
	/*
	$qry="SELECT company_profiles.id,company_profiles.company_name,company_profiles.company_username,company_profiles.description,company_profiles.avatar,company_profiles.city,company_profiles.cover_photo,
			 		 company_profiles.website,company_profiles.email,company_profiles.mobile,company_profiles.telephone,company_profiles.fax,
			 		 location_info.location_desc
			FROM company_profiles
			LEFT JOIN location_info ON location_info.id=company_profiles.client_location
			WHERE company_profiles.clientid=".$my_session_id."
	      ";
	 */     
	$qry="SELECT company_profiles.id,company_profiles.company_name,company_profiles.company_username,company_profiles.description,company_profiles.avatar,company_profiles.city,company_profiles.cover_photo,
			 		 company_profiles.website,company_profiles.email,company_profiles.mobile,company_profiles.telephone,company_profiles.fax,
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
			$data['companyUserName']=	$row['company_username'];								
			$data['companyName']		=	$row['company_name'];  			
			$data['location']			=	$row['location_desc'];  			
			$data['companyDesc']		=	htmlspecialchars_decode($row['description'],ENT_QUOTES);	
			$data['email']				=	$row['email'];  			
			$data['website']			=	$row['website'];  			
			$data['mobile']			=	$row['mobile'];  			
			$data['tel']				=	$row['telephone'];  			
			$data['fax']				=	$row['fax'];  	
			
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
			
					
		}
		$company_id=$data['id'];
		$data['categories']	= fetch_company_categories($company_id);	
		$data['followers']	= entrp_company_follows($company_id);
		$data['companyEvents']	=  fetchCompanyEvents($company_id);	
		$data['companyMembers']	=  fetchThisCompanyMembers($company_id);	
		$data['role']	=  $role;	
	}
	else 
	{
		$data['id']					=	'';  			
		$data['profilePhoto']	=	''; 		
		$data['coverPhoto']		=	''; 		
		$data['companyName']		=	''; 
		$data['location']			=	'';   			
		$data['companyDesc']		=	''; 		
		$data['email']				=	''; 			
		$data['website']			=	'';  			
		$data['mobile']			=	''; 	
		$data['tel']				=	'';  			
		$data['fax']				=	''; 
		$data['companyUserName']='';
	}
	
	return $data;
	/*
	{
	"id": 1,
	"profilePhoto": "member01.jpg",
	"coverPhoto": "memberCover01.jpg",
	"companyName": "vOffice",
	"location": "Fort Legend Tower",
	"companyDesc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
	"email": "info@voffice.com",
	"website": "voffice.com.ph",
	"mobile": "6322242000",
	"category": [
		"Virtual Office",
		"Serviced Office",
		"Coworking Space"
	],
	"allCategory" : []
	};
	*/
}



//Function to fetch a company profile
//April 25,2016
//August 16,2016: HTML character encoding support
function viewCompanyProfile()
{
		/*
		{
		  "name": "vOffice",
		  "location": "Fort Legend Tower",
		  "coverPhoto": "cover.jpg",
		  "profilePhoto": "profile.jpg",
		  "website": "voffice.com.ph",
		  "email": "sales@voffice.com",
		  "mobile": "639175296299",
		  "tel": "6322931533",
		  "fax": "6329165745",
		  "desc": "We provide businesses superior reach and access to South East Asia markets like Jakarta, Manila, Kuala Lumpur and Singapore.",
		  "natureOfBusinessTags": [
		    "virtual office",
		    "serviced office",
		    "co-working spaces"
		  ],
		  "employees": [
		    {
		      "id": "1",
		      "firstName": "Ken",
		      "lastName": "Sia",
		      "profilePhoto": "emp1.jpg"
		    },
		    {
		      "id": "2",
		      "firstName": "Jaye",
		      "lastName": "Atienza",
		      "profilePhoto": "emp2.jpg"
		    }
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
	
	$companyUserName=validate_input($_GET['id']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	if($my_session_id)
	{
		$data['followed']= doIFollowThisCompany($my_session_id,$companyid);
	}
	
	$qry="SELECT  CP.*,LI.location_desc AS city 
			FROM company_profiles AS CP
			LEFT JOIN location_info as LI ON LI.id=CP.client_location
			WHERE CP.id=".$companyid." 
		  ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data['id']					=	$row['id'];
   		$data['name']				=	$row['company_name'];
   		$data['companyUserName']=	$row['company_username'];
   		$data['location']			=	$row['located_at'];
   		
   		if($row['cover_photo']!='')
   		{
   			$data['coverPhoto']		=	$row['cover_photo'];
   		}
   		else
   		{
   			$data['coverPhoto']		=	$company_default_cover;
   		}
   		
   		if($row['avatar']!='')
   		{
   			$data['profilePhoto']	=	$row['avatar'];
   		}
   		else
   		{
   			$data['profilePhoto']	=	$company_default_avatar;
   		}     				
   		$data['website']			=	$row['website'];
   		$data['email']				=	$row['email'];
   		$data['mobile']			=	$row['mobile'];
   		$data['tel']				=	$row['telephone'];
   		$data['fax']				=	$row['fax'];
   		$data['desc']				=	htmlspecialchars_decode($row['description'],ENT_QUOTES);
   		
   		$data['followers']		=	entrp_company_follows($companyid);
   		$data['categories']		=  fetch_company_categories($companyid);
   		$data['companyEvents']	=  fetchCompanyEvents($companyid);
   		$data['companyMembers']	=  fetchThisCompanyMembers($companyid);	

   	}
   	
	}
	else
	{
		$data['id']				=	'';
		$data['name']			=	'';
		$data['companyUserName']='';
		$data['location']		=	'';
		$data['coverPhoto']		=	'';
		$data['profilePhoto']		=	'';
		$data['website']			=	'';
		$data['mobile']	=	'';
		$data['tel']		=	'';
		$data['fax']	=	'';
		$data['desc']		=	'';
	}
	return $data;

}

/* My Company Profile Services Ends */


?>