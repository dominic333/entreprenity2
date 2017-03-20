<?php

//Function to fetch tags of a timeline business opportunity post
//July 08,2016
function getTimelinePostTags($post_id)
{
	$data= array();
	$qry="SELECT BOT.business_tags 
			FROM entrp_user_timeline_businessopp_tags AS BOT
			WHERE BOT.postid=".$post_id."
		  ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {    	
			$data			=	json_decode($row['business_tags']);
      }	
   }
	return $data;
}


//Function to fetch all business opportunity tags
//July 07,2016 (This is redundant, already a function for fetching company tags in readonly services)
//August 16: Change tags to lowercase
function getAllBusinessOpportunityTags()
{
	$data= array();
	$qry="SELECT BOT.* 
			FROM entrp_user_timeline_businessopp_tags AS BOT
		  ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	
      	$data[$i]['post_id']			=	$row['id'];      	
      	$data[$i]['postid']			=	$row['postid'];      	
			$data[$i]['tags']				=	json_decode(strtolower($row['business_tags']));

			$i++;
      }	
   }
	return $data;
}


//Function to fetch business opportunities (as a section) to show in home page
//June 29,2016
//August 12, 2016: Changes after implementing company-user relation
function recommendedBusinessOpportunities()
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
	$allTags= array();
	$myCompCategoriesDecoded= array();
	$array = array();
	$postIdArrays = array();
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$myCompanyID			=	getCompanyIDfromUserID($my_session_id); //get company id
		//$myCompanyTagsUF		=  fetch_company_categories($myCompanyID); //get company categories json (my company tags)	
		$myCompanyTagsUF		=  get_user_skill_sets($my_session_id); 		
		$allTags 				=  getAllBusinessOpportunityTags(); 	//get All business opportunity tags
	
		$myCompanyTags = array_filter($myCompanyTagsUF);
		if (!empty($myCompanyTags)) 
		{
			$myUniqueCompanyTags = array_unique($myCompanyTags);
			
			foreach($allTags as $tag)  // iterate through multi-dimensional array not single one
			{
				// a for loop to iterate through single-dimensional completely
				for($i=0;$i<count($myUniqueCompanyTags);$i++)
				{ 
					 // if single dimensional array value exist in multi-dimensional tags array
				    if (in_array($myUniqueCompanyTags[$i],$tag['tags']))  
				    {
				        $newdata =  array (
				            'postId' => $tag['postid'],
				            'tag' => $myUniqueCompanyTags[$i]
				         );
				        array_push($array, $newdata); // push the data
				        array_push($postIdArrays, $tag['postid']); // push the data
				    }
				}
			}
			
          $postIdArrayStringUF = array_filter($postIdArrays);
		    if (!empty($postIdArrayStringUF)) 
          {
	          $postIdArrayString = implode(",", $postIdArrayStringUF);
	          $qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.created_at,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc 
					FROM entrp_user_timeline AS EUT
					LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
					LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
					LEFT JOIN location_info AS LI ON LI.id=CP.client_location
					WHERE EUT.status=1 AND EUT.business_opp=1 AND EUT.post_id IN (".$postIdArrayString.")
					ORDER BY EUT.created_at DESC LIMIT 3
	                 ";
				$res=getData($qry);
			   $count_res=mysqli_num_rows($res);
			   $i=0; //to initiate count
			   if($count_res>0)
			   {
			   	while($row=mysqli_fetch_array($res))
			      {
			      	$post_id														=	$row['post_id'];
			      	
			      	$data[$i]['post_id']										=	$row['post_id'];      	
			      	$data[$i]['postTags']									=	getTimelinePostTags($post_id);
						$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
						$data[$i]['image']										=	$row['post_img'];
						$data[$i]['created_at']									=	$row['created_at'];
						
						$data[$i]['post_author']['id']						=	$row['clientid'];
						$data[$i]['post_author']['firstName']				=	$row['firstname'];
						$data[$i]['post_author']['lastName']				=	$row['lastname'];
						if($row['avatar']!='')
						{
							$data[$i]['post_author']['avatar']				=	$row['avatar'];
						}
						else
						{
							$data[$i]['post_author']['avatar']				=	$member_default_avatar;
						}
			   				
						$data[$i]['post_author']['position']				=	$row['designation'];
						
						$data[$i]['post_author']['userName']				=	$row['username'];
						$data[$i]['post_author']['location']				=	$row['location_desc'];
						
						$post_by														=	$row['clientid'];  
						$companyId													=	getCompanyIDfromUserID($post_by);
						$data[$i]['post_author']['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
			
						$i++;
			      }	
			   }
                
         }
				
		}	
	}
	return $data;
}

//Function to get most timeline commenter s (not comments received, but comments posted)
//June 02,2016
function getMostTimeLineCommenters()
{
	//SELECT count(post_comments_id) AS comment_count,commented_by FROM entrp_user_timeline_post_comments WHERE status=1 GROUP BY commented_by ORDER BY commented_by ASC
	$commentPoints=2;
	$data= array();
	$i=0;
	$qry="SELECT count(post_comments_id) AS comment_count,commented_by 
			FROM entrp_user_timeline_post_comments 
			WHERE status=1 
			GROUP BY commented_by 
			ORDER BY commented_by ASC
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[$i]['user']		=	$row['commented_by'];
			$data[$i]['points']	=	$row['comment_count'];
			$i++;			
		}	
		
		foreach ($data as $key => $row) 
		{
    		// replace 0 with the field's index/key
    		$commentSort[$key]  = $commentPoints*$row['points'];
		}

		array_multisort($commentSort, SORT_DESC, $data);		
	}
	return $data;
}

//Function to get most timeline like receivers
//June 02,2016
function getMostTimelineLikeReceivers()
{
	//SELECT EUTPL.post_id,EUTPL.liked_user_ids,EUT.posted_by FROM entrp_user_timeline_post_likes AS EUTPL LEFT JOIN entrp_user_timeline AS EUT ON EUT.post_id=EUTPL.post_id WHERE EUT.status=1
	$likePoints=1;
	$data= array();
	$i=0;
	$qry="SELECT EUTPL.post_id,EUTPL.liked_user_ids,EUT.posted_by 
			FROM entrp_user_timeline_post_likes AS EUTPL 
			LEFT JOIN entrp_user_timeline AS EUT ON EUT.post_id=EUTPL.post_id 
			WHERE EUT.status=1
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			/*
			$data[$i]['post_id']				=	$row['post_id'];
			$data[$i]['posted_by']			=	$row['posted_by'];
			$like_array							=	json_decode($row['liked_user_ids']);
			$data[$i]['total_likes']		=	sizeof($like_array);
			*/
			
			$data['post_id'][$i]				=	$row['post_id'];
			$data['user'][$i]			=	$row['posted_by'];
			$like_array							=	json_decode($row['liked_user_ids'],TRUE);
			$data['total_likes'][$i]		=	sizeof($like_array);			
			
			$i++;			
		}	
		
		$users = array_unique($data['user']);
		$listUserLike = [];
		
		foreach($users as $user) 
		{
		    $indexes = array_keys($data['user'], $user);
		    $likes = array_intersect_key($data['total_likes'], array_flip($indexes));
		    $listUserLike[] = ['user' => $user, 'points' => array_sum($likes)];
		}
		
		foreach ($listUserLike as $key => $row) 
		{
    		// replace 0 with the field's index/key
    		$likeSort[$key]  = $likePoints*$row['points'];
		}

		array_multisort($likeSort, SORT_DESC, $listUserLike);	


	}
	return $listUserLike;
}


//Function to fetch most timeline post publishers
//June 02,2016 (incomplete)
function getMostTimelinePostPublishers()
{
	//SELECT COUNT(post_id) AS total_posts, posted_by FROM entrp_user_timeline WHERE status=1  GROUP BY posted_by ORDER BY posted_by ASC
	$postPoints=3;
	$data= array();
	$i=0;
	$qry="SELECT COUNT(post_id) AS total_posts, posted_by 
			FROM entrp_user_timeline 
			WHERE status=1  
			GROUP BY posted_by 
			ORDER BY posted_by ASC
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[$i]['user']		=	$row['posted_by'];
			$data[$i]['points']	=	$row['total_posts'];
			$i++;			
		}	
		
		foreach ($data as $key => $row) 
		{
    		// replace 0 with the field's index/key
    		$postSort[$key]  = $postPoints*$row['points'];
		}

		array_multisort($postSort, SORT_DESC, $data);		
	}
	return $data;
}


//Function to fetch top 10 post publishers
//September 02,2016
function getTopThreePostPublishers()
{
	//SELECT posted_by, count(post_id) AS totalPosts FROM `entrp_user_timeline` WHERE status=1 GROUP BY posted_by ORDER BY totalPosts DESC LIMIT 10
	$data= array();
	$qry="SELECT posted_by, count(post_id) AS totalPosts 
			FROM entrp_user_timeline 
			WHERE status=1 AND posted_by NOT IN (1)
			GROUP BY posted_by 
			ORDER BY totalPosts DESC 
			LIMIT 3
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data[]	=	$row['posted_by'];	
		}			
	}
	return $data;
}

//Function to fetch top contributors from the community
//June 02,2016
//August 12, 2016: Changes after implementing company-user relation

function getTopContributors()
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
	
	$posts_array= array();
	$likes_array= array();
	$comment_array= array();
	$data4= array();
	$data= array();
	$uniqueUsers = array();
	
	$points = 0;
	$votes = 0;
	$users = 0;
	
	//timeline post
	$posts_array= 	getMostTimelinePostPublishers();

	//timeline likes
	$likes_array= 	getMostTimelineLikeReceivers();	
	
	//timeline comments (not comments received, but comments posted)
	$comment_array= 	getMostTimeLineCommenters();
	
	//timeline comment likes
	//$data4=	getMostTimelineCommentLikeReceivers();
	
	/*
	//THIS METHOD IS ALSO CORRECT BUT ARRAY COLUMN FUNCTION
	//IS NOT AVAILABLE IN EVERY PHP VERSION
	$comments = array_column($comment_array, 'points', 'user');
	$likes = array_column($like_array, 'points', 'user');
	$posts = array_column($post_array, 'points', 'user');
	
	$list = [$comments, $likes, $posts];
	$result = [];
	
	array_walk_recursive($list, function($v, $k) use(&$result)
	{
	    if (key_exists($k, $result))
	    {
	        $result[$k]['points'] += $v;
	    } 
	    else 
	    {
	        $result[$k] = ['user' => $k, 'points' => $v];
	    }
	});
	
	$result = array_values($result);
	
	foreach ($result as $key => $row) 
	{
    	// replace 0 with the field's index/key
    	$pointsSort[$key]  = $row['points'];
	}

	array_multisort($pointsSort, SORT_DESC, $result);
		
	//print_r($result);
	
	for($i=0; $i< sizeof($result) ;$i++)
	{
		$topContributorsUserIDArray[] =  $result[$i]['user'];
	}
	
	$topContributorsUserIDString = implode(",", array_slice($topContributorsUserIDArray, 0, 3));
	*/
	
	//Comments
	if (!empty($comment_array)) 
	{
	    foreach ($comment_array as $item) 
	    {
	        if (!in_array($item['user'], $uniqueUsers)) 
	        {
	            array_push($uniqueUsers, $item['user']);
	            $result[$item['user']] = 0;
	        }
	        $votes ++;
	        $result[$item['user']] += $item['points'];
	    }
	}
	
	// Likes
	if (!empty($like_array)) 
	{
	    foreach ($like_array as $item) 
	    {
	
	        if (!in_array($item['user'], $uniqueUsers)) 
	        {
	            array_push($uniqueUsers, $item['user']);
	            $result[$item['user']] = 0;
	        }
	        $votes ++;
	        $result[$item['user']] += $item['points'];
	    }
	}
	
	
	// Posts
	if (!empty($post_array)) 
	{
	    foreach ($post_array as $item) 
	    {
	
	        if (!in_array($item['user'], $uniqueUsers)) 
	        {
	            array_push($uniqueUsers, $item['user']);
	            $result[$item['user']] = 0;
	        }
	        $votes ++;
	        $result[$item['user']] += $item['points'];
	    }
	}
	
	$i=0;
	foreach ($result as $idUser=>$points) 
	{
		$resultArray[$i]['user']=  $idUser;
		$resultArray[$i]['points']=  $points;
		$i++;
	}
	
	foreach ($resultArray as $key => $row) 
	{
	    $pointsSort[$key]  = $row['points'];
	}
	
	array_multisort($pointsSort, SORT_DESC, $resultArray);
		
	for($i=0; $i< sizeof($resultArray) ;$i++)
	{
		$topContributorsUserIDArray[] =  $resultArray[$i]['user'];
	}
		
	$topContributorsUserIDString = implode(",", array_slice($topContributorsUserIDArray, 0, 7));
	
	//echo'<pre>';
	//print_r($resultArray);
	//echo'</pre>';
	
	//echo $topContributorsUserIDString;
		
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CI.username,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM entrp_login AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
	      WHERE CI.clientid IN (".$topContributorsUserIDString.") AND CI.clientid NOT IN (1) 
	      LIMIT 4 
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
			
			if(!empty($row['lastname']))
      	{
      		$data[$i]['lastName']		=	$row['lastname'];
      	}
      	else
      	{
      		$data[$i]['lastName']		=	"";
      	}
      	
      	if(!empty($row['username']))
      	{
      		$data[$i]['userName']		=	$row['username'];
      	}
      	else
      	{
      		$data[$i]['userName']		=	"";
      	}
			
			if(!empty($row['avatar']))
      	{
      		$data[$i]['avatar']			=	$row['avatar'];
      	}
      	else
      	{
      		$data[$i]['avatar']			=	$member_default_avatar;
      	}
			
			if(!empty($row['designation']))
      	{
      		$data[$i]['position']		=	$row['designation'];
      	}
      	else
      	{
      		$data[$i]['position']		=	"";
      	}
			
			/*
			if(!empty($row['company_name']))
      	{
      		$data[$i]['company']	=	$row['company_name'];
      	}
      	else
      	{
      		$data[$i]['company']	=	"";
      	}
      	*/
      	
      	$post_by							=	$row['clientid'];   
			$companyId						=	getCompanyIDfromUserID($post_by);
			$data[$i]['company']			=  getCompanyNameUsingCompUserRelation($companyId);
			
			if(!empty($row['city']))
      	{
      		$data[$i]['city']				=	$row['city'];
      	}
      	else
      	{
      		$data[$i]['city']				=	"";
      	}
      	
			$i++;
      }	
   }
   else
   {
   	/*
   	$data[$i]['id']				=	"";
		$data[$i]['firstName']		=	"";
		$data[$i]['lastName']		=	"";
		$data[$i]['avatar']			=	"";
		$data[$i]['position']		=	"";
		$data[$i]['company']			=	"";
		$data[$i]['city']				=	"";
		$data[$i]['userName']		=	"";
		*/
		
		//$data['message']="No Top Contributors.";
   }
	return $data;		

}


//Function to find top contributors based on total posts made
//September 02,2016
function getTopContributorsBeta()
{
	$data= array();	
	$topThree=getTopThreePostPublishers();
	$topContributorsUserIDString = implode(",", $topThree);
		
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CI.username,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM entrp_login AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
	      WHERE CI.clientid IN (".$topContributorsUserIDString.") AND CI.clientid NOT IN (1) 
	      LIMIT 3 
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
			
			if(!empty($row['lastname']))
      	{
      		$data[$i]['lastName']		=	$row['lastname'];
      	}
      	else
      	{
      		$data[$i]['lastName']		=	"";
      	}
      	
      	if(!empty($row['username']))
      	{
      		$data[$i]['userName']		=	$row['username'];
      	}
      	else
      	{
      		$data[$i]['userName']		=	"";
      	}
			
			if(!empty($row['avatar']))
      	{
      		$data[$i]['avatar']			=	$row['avatar'];
      	}
      	else
      	{
      		$data[$i]['avatar']			=	$member_default_avatar;
      	}
			
			if(!empty($row['designation']))
      	{
      		$data[$i]['position']		=	$row['designation'];
      	}
      	else
      	{
      		$data[$i]['position']		=	"";
      	}
      	
      	$post_by							=	$row['clientid'];   
			$companyId						=	getCompanyIDfromUserID($post_by);
			$data[$i]['company']			=  getCompanyNameUsingCompUserRelation($companyId);
			
			if(!empty($row['city']))
      	{
      		$data[$i]['city']				=	$row['city'];
      	}
      	else
      	{
      		$data[$i]['city']				=	"";
      	}
      	
			$i++;
      }	
   }
	return $data;
}



//Function to fetch latest events
//May 09, 2016
function getLatestEvents()
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
	
	$today=date('Y-m-d H:i:s');
	$to_day = new DateTime($today);
	$to_day->modify('+14 day');
	$tothatday= $to_day->format('Y-m-d H:i:s');
	
	$data= array();	
	$qry="SELECT entrp_events.*,entrp_event_categories.category_name 
			FROM entrp_events 
			LEFT JOIN entrp_event_categories ON entrp_events.category=entrp_event_categories.id
	      WHERE  entrp_events.status=1 AND entrp_events.event_date_time >= '".$today."' AND entrp_events.event_date_time <= '".$tothatday."'
	      ORDER BY entrp_events.event_date_time 
	      LIMIT 3
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	if(!empty($row['id']))
      	{
      		$data[$i]['id']					=	$row['id'];
      	}
      	else
      	{
      		$data[$i]['id']					=	"";
      	}
        
        if(!empty($row['eventTagId']))
      	{
      		$data[$i]['eventTagId']					=	$row['eventTagId'];
      	}
      	else
      	{
      		$data[$i]['eventTagId']					=	"";
      	}
      	
      	if(!empty($row['eventName']))
      	{
      		$data[$i]['name']					=	$row['eventName'];
      	}
      	else
      	{
      		$data[$i]['name']					=	"";
      	}
			
			if(!empty($row['poster']))
      	{
      		$data[$i]['poster']				=	$row['poster'];
      	}
      	else
      	{
      		$data[$i]['poster']				=	$event_default_poster;
      	}
      	
      	if(!empty($row['event_date']))
      	{
      		$data[$i]['date']					=	$row['event_date'];
      	}
      	else
      	{
      		$data[$i]['date']					=	"";
      	}
      	
			$i++;
      }	
   }
   else
   {
   	/*
   	$data[$i]['id']		=	"";
		$data[$i]['name']		=	"";
		$data[$i]['date']		=	"";
		$data[$i]['poster']	=	"";
		*/	
		//$data['message']="No Upcoming Members.";	
   }
	return $data;	
	
	
	
	 /*
	 vm.latestEvents = data = {
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



//Function to fetch newly registered members list
//April 21,2016
//August 12, 2016: Changes after implementing company-user relation
function getNewMembers()
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
  
	$today=date('Y-m-d H:i:s');
	$to_day = new DateTime($today);
	$to_day->modify('-14 day');
	$fromday= $to_day->format('Y-m-d H:i:s');
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];	
	
	
	$data= array();	
	$qry="SELECT CI.clientid,CI.firstname,CI.lastname,CI.username,CP.designation,CP.company_name,CP.avatar,LI.location_desc AS city 
	      FROM entrp_login AS CI 
	      LEFT JOIN client_profile AS CP ON CP.clientid=CI.clientid
	      LEFT JOIN location_info as LI ON LI.id=CP.client_location
	      WHERE CP.join_date >= '".$fromday."' AND CP.join_date <= '".$today."' 
	      AND CI.clientid!=".$my_session_id."
	      ORDER BY CI.clientid DESC 
	      LIMIT 3 
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
			
			if(!empty($row['lastname']))
      	{
      		$data[$i]['lastName']		=	$row['lastname'];
      	}
      	else
      	{
      		$data[$i]['lastName']		=	"";
      	}
      	
      	if(!empty($row['username']))
      	{
      		$data[$i]['userName']		=	$row['username'];
      	}
      	else
      	{
      		$data[$i]['userName']		=	"";
      	}
			
			if(!empty($row['avatar']))
      	{
      		$data[$i]['avatar']			=	$row['avatar'];
      	}
      	else
      	{
      		$data[$i]['avatar']			=	$member_default_avatar;
      	}
			
			if(!empty($row['designation']))
      	{
      		$data[$i]['position']		=	$row['designation'];
      	}
      	else
      	{
      		$data[$i]['position']		=	"";
      	}
			/*
			if(!empty($row['company_name']))
      	{
      		$data[$i]['company']	=	$row['company_name'];
      	}
      	else
      	{
      		$data[$i]['company']	=	"";
      	}
      	*/
      	$post_by							=	$row['clientid'];   
			$companyId						=	getCompanyIDfromUserID($post_by);
			$data[$i]['company']			=  getCompanyNameUsingCompUserRelation($companyId);
			
			if(!empty($row['city']))
      	{
      		$data[$i]['city']				=	$row['city'];
      	}
      	else
      	{
      		$data[$i]['city']				=	"";
      	}
      	
			$i++;
      }	
   }
   else
   {
   	/*
   	$data[$i]['id']				=	"";
		$data[$i]['firstName']		=	"";
		$data[$i]['lastName']		=	"";
		$data[$i]['avatar']			=	"";
		$data[$i]['position']		=	"";
		$data[$i]['company']			=	"";
		$data[$i]['city']				=	"";
		$data[$i]['userName']		=	"";
		*/
		//$data['message']="No New Members.";
   }
	return $data;


	/*
	vm.newMembers = data = [
		{
			"id": "1",
			"avatar": "member01.jpg",
			"firstName": "Kurt",
			"lastName": "Megan",
			"position": "Office Assistant",
			"company": "Pet Studio.com",
		},
		{
			"id": "2",
			"avatar": "member02.jpg",
			"firstName": "Will",
			"lastName": "Ferrel",
			"position": "CEO",
			"company": "Clever Sheep",
		},
		{
			"id": "3",
			"avatar": "member03.jpg",
			"firstName": "Will",
			"lastName": "Ferrel",
			"position": "CEO",
			"company": "Clever Sheep",
		},
	];
	*/
}

?>