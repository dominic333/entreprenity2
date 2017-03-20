<?php

//Function to like a comment made on a timeline post
//June 23,2016
function likeThisComment()
{

}


//Function to unlike a comment made on a timeline post
//June 23,2016
function unlikeThisComment()
{

}

//Function to hide one user's all posts from the timelines
//September 27,2016
function hideAllPostsOfThisUser()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$timelinePostID = $requestData->postID;
		$postID=validate_input($timelinePostID);
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		$clientId=whoIsTheAuthorOfThisPost($postID);
		
		$qry="INSERT INTO entrp_hide_user_feeds(myId,clientId) VALUES(".$my_session_id.",".$clientId.") ";
		if(setData($qry))
		{
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
	}
	return $data;
}


//Function to hide a particular post from a user's timelines
//September 27,2016
function hideThisPost()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$timelinePostID = $requestData->postID;
		$postID=validate_input($timelinePostID);
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		$qry="INSERT INTO entrp_hide_user_post(myId,postID) VALUES(".$my_session_id.",".$postID.") ";
		if(setData($qry))
		{
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
	}
	return $data;
}


//Function to edit a timeline post
//June 20,2016
function editTimelinePost()
{
   $data= array();
   $categories= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		//postID: postId, postContent:postContent, bussOpp:bussOpp, postTags:postTags, timeLine: timelineId, username: ucUsername

		$content	= validate_input($_POST['postContent']['content']);
		$timeLine=validate_input($_POST['timeLine']);
		$postID=validate_input($_POST['postContent']['post_id']);
     
		if(!empty($_POST['postContent']['postTags']))
		{
			$business_op=1;
			$count_category=count($_POST['postContent']['postTags']);
			for($i=0;$i<$count_category;$i++)	
			{
				$categories[$i]	=	$_POST['postContent']['postTags'][$i]['text'];
				$category_json		=  json_encode($categories);
			}
		}
		else
		{     
			$business_op=0;
		}

		$qry="UPDATE entrp_user_timeline SET content='".$content."' WHERE post_id=".$postID."";
		if(setData($qry))
		{
			if($business_op==1)
			{
				$qry2="UPDATE entrp_user_timeline_businessopp_tags SET business_tags='".$category_json."' WHERE postid=".$postID."";
				setData($qry2);  
			}
			
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}


		if($timeLine==1)
		{
			//home page all posts
			$data=getAllPosts();
		}
		else if($timeLine==2)
		{
			//member profile timeline posts
			$username=validate_input($_POST['username']);
			$data		       = refetchMemberNewsFeed($username);
		}
		else if($timeLine==3)
		{
			//home page followed posts
			$data=getFollowedMembersPosts();
		
		}
		else if($timeLine==4)
		{
			//company profile timeline posts
			$username=validate_input($_POST['username']);
			$data				  = refetchCompanyPosts($username);
		}
		else if($timeLine==5)
		{
			//business opportunities page
			$data=getAllBusinessOpportunities();
		}
		else if($timeLine==6)
		{
			//my company profile timeline posts
			$data=getmyCompanyPosts();
		}
		else if($timeLine==7)
		{
			//home page my posts/myprofile timeline
			$data=getMyOwnNewsFeed();
		}
	   else if($timeLine==8)
	   {
	       //home page my posts/myprofile timeline
	       $data=getBusinessOpportunitiesForMe();
	   }
	}
	return $data;
}


//Function to get image path of a timeline post
//July 19,2016
function getImagePathofaTimelinePost($post_id)
{
	$timeLineImage='';
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];
	
	//Let me see to whom this post belongs
	$posted_by= whoIsTheAuthorOfThisPost($post_id);
	
	if($posted_by==$my_session_id)
	{
		//This post is mine. I'm the author
		$qry="SELECT EUT.post_img
				FROM entrp_user_timeline AS EUT
				WHERE EUT.post_id=".$post_id."";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	      {     	
				$timeLineImage			=	$row['post_img'];
	      }	
	   }			
	}
	return $timeLineImage;
}

//Function to delete timeline post likes
//July 19,2016
function deleteTimelinePostLikes($postID)
{
	$qry="DELETE FROM entrp_user_timeline_post_likes WHERE post_id =".$postID."";
	setData($qry);
}

//Function to delete timeline post comments
//July 19,2016
function deleteTimelinePostComments($postID)
{
	$qry="DELETE FROM entrp_user_timeline_post_comments WHERE post_id =".$postID."";
	setData($qry);
}

//Function to delete timeline post tags 
//July 19,2016
function deleteTimelinePostTags($postID)
{
	$qry="DELETE FROM entrp_user_timeline_businessopp_tags WHERE postid =".$postID."";
	setData($qry);
}

//Function to delete timeline post notifications
//July 19,2016
function deleteTimelinePostNotification($postID)
{
	$qry="DELETE FROM entrp_user_notifications WHERE post_id =".$postID."";
	setData($qry);
}


//Function to unlink a timeline post image from server
//July 19,2016
function deleteTimelinePostImageFromServer($timeLineImage)
{
	//assets/img/timeline/timelineimgjordan20160716185651.jpeg
	$timeLineImagePath='../'.$timeLineImage;
	if (file_exists($timeLineImagePath)) 
	{
	   unlink($timeLineImagePath);
	} 
}

//Function to delete a timeline post
//July 19,2016
function deleteTimlinePost()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$timelinePostID = $requestData->postID;
		$postID=validate_input($timelinePostID);
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		$timeLineImage=getImagePathofaTimelinePost($postID);
		
		$qry="DELETE FROM entrp_user_timeline WHERE post_id =".$postID."";
		if(setData($qry))
		{
			deleteTimelinePostLikes($postID);
			deleteTimelinePostComments($postID);
			deleteTimelinePostTags($postID);
			deleteTimelinePostNotification($postID);
			
			if($timeLineImage!='')
			{
				deleteTimelinePostImageFromServer($timeLineImage);
			}
			
			
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
		/*
		if($timeLine==1)
		{
			//home page all posts
			$data=getAllPosts();
		}
		else if($timeLine==2)
		{
			//member profile timeline posts
			$memberUsername = $requestData->username;
			$username		 = validate_input($memberUsername);
			$data		       = refetchMemberNewsFeed($username);
		}
		else if($timeLine==3)
		{
			//home page followed posts
			$data=getFollowedMembersPosts();
		
		}
		else if($timeLine==4)
		{
			//company profile timeline posts
			$companyUsername = $requestData->username;
			$username		  = validate_input($companyUsername);
			$data				  = refetchCompanyPosts($username);
		}
		else if($timeLine==5)
		{
			//business opportunities page
			$data=getAllBusinessOpportunities();
		}
		else if($timeLine==6)
		{
			//my company profile timeline posts
			$data=getmyCompanyPosts();
		}
		else if($timeLine==7)
		{
			//home page my posts/myprofile timeline
			$data=getMyOwnNewsFeed();
		}
      else if($timeLine==8)
		{
			//home page my posts/myprofile timeline
			$data=getBusinessOpportunitiesForMe();
		}
		else
		{
		
		}
		*/
	}
	return $data;
}


//Function to fetch matching business opportunities
//July 16,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//August 16,2016: Fetching likes, comments and such details
//September 01,2016: Added html decode for wysiwyg editor
//September 08,2016: Fetch data based on country and location filter
//October 07,2016: Added filtering to hide posts I don't like
//October 07,2016: Added filtering to hide all post from people I don't like
function getBusinessOpportunitiesForMe()
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
	
	$limit = TIMELINE_POSTS_LIMIT;
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
	
	//location
	if(isset($_GET['location']))
	{
		$location = validate_input($_GET['location']);		
	}
	else
	{
		$location = 0;
	}
	
	//center code
	if(isset($_GET['country']))
	{
		$country = validate_input($_GET['country']);		
	}
	else
	{
		$country = 0;
	}
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$myCompanyID			=	getCompanyIDfromUserID($my_session_id); //get company id
		//$myCompanyTagsUF		=  fetch_company_categories($myCompanyID); //get company categories json (my company tags)		
		$myCompanyTagsUF		=  get_user_skill_sets($my_session_id); //get company categories json (my company tags)			
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
			
			$hidePosts	= postsIdontLike($my_session_id);
			if(!empty($hidePosts))
			{
				$hidePostsString = implode(",", $hidePosts);
			}
			else
			{
				$hidePostsString ="''";
			}
			
			$hidePeoplePosts	= postsFromUsersIdontLike($my_session_id);
			if(!empty($hidePeoplePosts))
			{
				$hidePeoplePostsString = implode(",", $hidePeoplePosts);
			}
			else
			{
				$hidePeoplePostsString ="''";
			}
			
          $postIdArrayStringUF = array_filter($postIdArrays);
          //$postIdArrayStringUF = $postIdArrays;
		    if (!empty($postIdArrayStringUF)) 
          {
            $postIdArrayString = implode(",", $postIdArrayStringUF);
            
				if($country >0 && $location > 0)
				{
					$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
						FROM entrp_user_timeline AS EUT
						LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
						LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
						LEFT JOIN location_info AS LI ON LI.id=CP.client_location
						WHERE EUT.status=1 AND EUT.business_opp=1 AND EUT.post_id IN (".$postIdArrayString.") AND CP.client_location=".$location." 

						ORDER BY EUT.created_at DESC 
						LIMIT $start, $limit ";
				}
				else if($country ==0 && $location > 0)
				{			
					$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
						FROM entrp_user_timeline AS EUT
						LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
						LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
						LEFT JOIN location_info AS LI ON LI.id=CP.client_location
						WHERE EUT.status=1 AND EUT.business_opp=1 AND EUT.post_id IN (".$postIdArrayString.") AND CP.client_location=".$location." 

						ORDER BY EUT.created_at DESC 
						LIMIT $start, $limit ";
				}		
				else if($country >0 && $location ==0)
				{
					//fetch all locations for this country
					$locationIds		= fetchLocationIDsUnderCountry($country);
					if(!empty($locationIds))
					{
						$locationIdString = implode(",", $locationIds);			
						$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
							FROM entrp_user_timeline AS EUT
							LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
							LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
							LEFT JOIN location_info AS LI ON LI.id=CP.client_location
							WHERE EUT.status=1 AND EUT.business_opp=1 AND EUT.post_id IN (".$postIdArrayString.") AND CP.client_location IN (".$locationIdString.") 

							ORDER BY EUT.created_at DESC 
							LIMIT $start, $limit ";
					}
					else
					{
						$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
							FROM entrp_user_timeline AS EUT
							LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
							LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
							LEFT JOIN location_info AS LI ON LI.id=CP.client_location
							WHERE EUT.status=1 AND EUT.business_opp=1 AND EUT.post_id IN (".$postIdArrayString.") 

							ORDER BY EUT.created_at DESC 
							LIMIT $start, $limit ";
					}
				}
				else
				{
					$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
						FROM entrp_user_timeline AS EUT
						LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
						LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
						LEFT JOIN location_info AS LI ON LI.id=CP.client_location
						WHERE EUT.status=1 AND EUT.business_opp=1 AND EUT.post_id IN (".$postIdArrayString.") 

						ORDER BY EUT.created_at DESC 
						LIMIT $start, $limit ";
				}
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
						$data[$i]['uploadType']									=	$row['uploadType'];
						$data[$i]['created_at']									=	$row['created_at'];
						$data[$i]['bussOpp']										=	$row['business_opp'];
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
						//$data[$i]['post_author']['companyName']			=	$row['company_name'];
						$data[$i]['post_author']['userName']				=	$row['username'];
						$data[$i]['post_author']['location']				=	$row['location_desc'];
						
						$post_by														=	$row['posted_by'];   
						$companyId													=	getCompanyIDfromUserID($post_by);
						$data[$i]['post_author']['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
						
						$data[$i]['isLiked']										= doILikeThisPost($post_id);
						$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
						$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
						$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
						$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
						$data[$i]['comments']									= userCommentsForThisPost($post_id);
			
						$i++;
			      }	
			   }
             
         }
				
		}	
	}
	return $data;
}


//Function to refetch a single timeline post
//July 14,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function refetchThisPost($post_id)
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
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];
	
	//Let me see to whom this post belongs
	$posted_by= whoIsTheAuthorOfThisPost($post_id);
	
	if($posted_by==$my_session_id)
	{
		//This post is mine. I'm the author
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.post_id=".$post_id."";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	      {
	      	//$post_id												=	$row['post_id'];	      	
	      	$data['post_id']									=	$row['post_id'];  
	      	$data['content']									=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
				$data['image']										=	$row['post_img'];
				$data['uploadType']									=	$row['uploadType'];
				$data['created_at']								=	$row['created_at'];
				$data['bussOpp']									=	$row['business_opp'];
				$data['post_author']['id']						=	$row['clientid'];
				$data['post_author']['firstName']			=	$row['firstname'];
				$data['post_author']['lastName']				=	$row['lastname'];
				if($row['avatar']!='')
				{
					$data['post_author']['avatar']			=	$row['avatar'];
				}
				else
				{
					$data['post_author']['avatar']			=	$member_default_avatar;
				}
	   				
				$data['post_author']['position']				=	$row['designation'];
				//$data['post_author']['companyName']			=	$row['company_name'];
				$data['post_author']['userName']				=	$row['username'];
				$data['post_author']['location']				=	$row['location_desc'];
				
				$post_by												=	$row['posted_by'];   
				$companyId											=	getCompanyIDfromUserID($post_by);
				$data['post_author']['companyName'] 		=  getCompanyNameUsingCompUserRelation($companyId);
				
				$data['isLiked']									= doILikeThisPost($post_id);
				$data['likes_count']								= howManyLikesThisPostReceived($post_id);
				$data['likers']									= usersWhoLikesThisPost($post_id);
				$data['comments_count']							= howManyCommentsThisPostReceived($post_id);
				$data['commenters']								= usersWhoCommentedThisPost($post_id);
				$data['comments']									= userCommentsForThisPost($post_id);
				
	      }	
	   }			
	}
	
	return $data;	
}



//Function to refetch a company timeline post based on company username
//July 14,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function refetchCompanyPosts($companyUserName)
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
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];
	
	$hidePosts	= postsIdontLike($my_session_id);
	if(!empty($hidePosts))
	{
		$hidePostsString = implode(",", $hidePosts);
	}
	else
	{
		$hidePostsString ="''";
	}
	
	$hidePeoplePosts	= postsFromUsersIdontLike($my_session_id);
	if(!empty($hidePeoplePosts))
	{
		$hidePeoplePostsString = implode(",", $hidePeoplePosts);
	}
	else
	{
		$hidePeoplePostsString ="''";
	}

	$companyId=getCompanyIdfromCompanyUserName($companyUserName);

	$companyMembers= getAllCompanyMemberIDs($companyId);
	$companyMembersString = implode(",", $companyMembers);
	//$companyMembersString = '1,2,3';
	
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by IN (".$companyMembersString.") AND EUT.status=1 AND EUT.business_opp!=1 
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;
}

//Function to refetch timeline posts of a member's profile
//July 14,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function refetchMemberNewsFeed($username)
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
   
	$my_id	= getUserIdfromUserName($username);
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by 
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by=".$my_id." AND EUT.status=1 AND EUT.business_opp!=1 
			ORDER BY EUT.created_at DESC";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id']; 
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);

			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;	

}

//Function to fetch all business opportunities
//June 15,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
//September 08,2016: Fetch data based on country and location filter
function getAllBusinessOpportunities()
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
	
	$limit = TIMELINE_POSTS_LIMIT;
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
	
	//location
	if(isset($_GET['location']))
	{
		$location = validate_input($_GET['location']);		
	}
	else
	{
		$location = 0;
	}
	
	//center code
	if(isset($_GET['country']))
	{
		$country = validate_input($_GET['country']);		
	}
	else
	{
		$country = 0;
	}
	
	if($country >0 && $location > 0)
	{
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp=1 AND CP.client_location=".$location."
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	}
	else if($country ==0 && $location > 0)
	{			
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp=1 AND CP.client_location=".$location."
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	}		
	else if($country >0 && $location ==0)
	{
		//fetch all locations for this country
		$locationIds		= fetchLocationIDsUnderCountry($country);
		if(!empty($locationIds))
		{
			$locationIdString = implode(",", $locationIds);			
			$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.business_opp=1 AND CP.client_location IN (".$locationIdString.")
				ORDER BY EUT.created_at DESC 
				LIMIT $start, $limit ";
		}
		else
		{
			$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.business_opp=1
				ORDER BY EUT.created_at DESC 
				LIMIT $start, $limit ";
		}
	}
	else
	{
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp=1
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	}	
		
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
         $data[$i]['postTags']									=	getTimelinePostTags($post_id);
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;
}

//Function to get post id of last inserted timeline post
//June 15,2016 (This can be temporary; Find another way- a better way)
function getLastTimelinePostID($posted_by,$created_at,$content)
{
	$qry="SELECT post_id FROM entrp_user_timeline  
			WHERE content='".$content."' AND created_at='".$created_at."' AND posted_by=".$posted_by." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['post_id'];  					
		}
		return $id;
	}
	else
	{
		return null;
	}  

}

//Function to post a business opportunity
//June 15,2016
//July 14,2016: Timeline id and type added and if else if ladder
//September 15, 2016: post time set to UTC
function postABusinessOpportunity()
{
	$data= array();
	$categories=array();
   $post_img='';
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$content	= validate_input($_POST['postContent']['content']);
		$timeLine=validate_input($_POST['timeLine']);

        $b64String = validate_input($_POST['imgString']);
		$imgString=$b64String;
		
		
		if($imgString!='')
		{
			$post_img=uploadTimelineImage($imgString,7);
		}
		else
		{
			$post_img='';
		}
        
        
		if(!empty($_POST['postContent']['categories']))
		{
			$business_op=1;
			$count_category=count($_POST['postContent']['categories']);
			for($i=0;$i<$count_category;$i++)	
			{
				$categories[$i]	=	$_POST['postContent']['categories'][$i]['text'];
				$category_json		=  json_encode($categories);
			}
            
         if($imgString!='')
         {
             $post_img=uploadTimelineImage($imgString,7);
         }
         else
         {
             $post_img='';
         }
		}
		else
		{
            if($imgString!='')
            {
                $post_img=uploadTimelineImage($imgString,6);
            }
            else
            {
                $post_img='';
            }
            
			$business_op=0;
		}
		date_default_timezone_set('UTC');
		$created_at=date('Y-m-d H:i:s');
		$posted_by=$my_session_id;
		
		
		//$data['postContent']=$content;
		//$data['postCategories']=$categories;
		
		$qry="INSERT INTO entrp_user_timeline(content,post_img,created_at,posted_by,business_opp) VALUES('".$content."','".$post_img."','".$created_at."',".$posted_by.",".$business_op.")";
		if(setData($qry))
		{
			$postID  = getLastTimelinePostID($posted_by,$created_at,$content);
			if($business_op==1)
			{
				$qry2="INSERT INTO entrp_user_timeline_businessopp_tags ( postid, business_tags) VALUES (".$postID.", '".$category_json."')";
				setData($qry2);  
			}
			
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
		
	}
	
	if($timeLine==1)
	{
		//home page all posts
		$data=getAllPosts();
	}
	else if($timeLine==2)
	{
		//member profile timeline posts
		$username=validate_input($_POST['username']);
		$data		       = refetchMemberNewsFeed($username);
	}
	else if($timeLine==3)
	{
		//home page followed posts
		$data=getFollowedMembersPosts();
	
	}
	else if($timeLine==4)
	{
		//company profile timeline posts
		$username=validate_input($_POST['username']);
		$data				  = refetchCompanyPosts($username);
	}
	else if($timeLine==5)
	{
		//business opportunities page
		$data=getAllBusinessOpportunities();
	}
	else if($timeLine==6)
	{
		//my company profile timeline posts
		$data=getmyCompanyPosts();
	}
	else if($timeLine==7)
	{
		//home page my posts/myprofile timeline
		$data=getMyOwnNewsFeed();
	}
    else if($timeLine==8)
    {
        //home page my posts/myprofile timeline
        $data=getBusinessOpportunitiesForMe();
    }
	else
	{
		//single post
		$data=refetchThisPost($postId);
	}
	return $data;

}

//Function to fetch my company timeline post based on company username
//June 13,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function getmyCompanyPosts()
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

	$limit = TIMELINE_POSTS_LIMIT;
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
	//I represent this post
	//$companyUserName=validate_input($_GET['company']);
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];

	$companyId=getCompanyIDfromUserID($my_session_id);

	$companyMembers= getAllCompanyMemberIDs($companyId);
	$companyMembersString = implode(",", $companyMembers);
	//$companyMembersString = '1,2,3';
	
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by IN (".$companyMembersString.") AND EUT.status=1 AND EUT.business_opp!=1
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;
}


//Function to fetch a company timeline post based on company username
//June 13,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function getCompanyPosts()
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

	$limit = TIMELINE_POSTS_LIMIT;
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

	//I represent this post
	$companyUserName=validate_input($_GET['company']);
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];

	$companyId=getCompanyIdfromCompanyUserName($companyUserName);

	$companyMembers= getAllCompanyMemberIDs($companyId);
	$companyMembersString = implode(",", $companyMembers);
	//$companyMembersString = '1,2,3';
	
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by IN (".$companyMembersString.") AND EUT.status=1 AND EUT.business_opp!=1
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;
}

//Function to fetch a single timeline post
//June 08,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//August 12, 2016: Fetch buss opp tags
//September 01,2016: Added html decode for wysiwyg editor
function getThisPost()
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
	
	//I represent this post
	$post_id=validate_input($_GET['post']);
	
	//I am the logged in user.
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];
	
	//Let me see to whom this post belongs
	$posted_by= whoIsTheAuthorOfThisPost($post_id);
	
	if($posted_by==$my_session_id)
	{
		//This post is mine. I'm the author
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.post_id=".$post_id."";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	      {
	      	//$post_id												=	$row['post_id'];	      	
	      	$data['post_id']									=	$row['post_id'];      	
				$data['content']									=	htmlspecialchars_decode($row['content'],ENT_QUOTES);
				$data['image']										=	$row['post_img'];
				$data['uploadType']									=	$row['uploadType'];
				$data['created_at']								=	$row['created_at'];
				$data['bussOpp']									=	$row['business_opp'];
				$data['postTags']									=	getTimelinePostTags($row['post_id']);
				$data['post_author']['id']						=	$row['clientid'];
				$data['post_author']['firstName']			=	$row['firstname'];
				$data['post_author']['lastName']				=	$row['lastname'];
				if($row['avatar']!='')
				{
					$data['post_author']['avatar']			=	$row['avatar'];
				}
				else
				{
					$data['post_author']['avatar']			=	$member_default_avatar;
				}
	   				
				$data['post_author']['position']				=	$row['designation'];
				//$data['post_author']['companyName']			=	$row['company_name'];
				$data['post_author']['userName']				=	$row['username'];
				$data['post_author']['location']				=	$row['location_desc'];
				
				$post_by												=	$row['posted_by'];   
				$companyId											=	getCompanyIDfromUserID($post_by);
				$dat['post_author']['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);
				
				$data['isLiked']									= doILikeThisPost($post_id);
				$data['likes_count']								= howManyLikesThisPostReceived($post_id);
				$data['likers']									= usersWhoLikesThisPost($post_id);
				$data['comments_count']							= howManyCommentsThisPostReceived($post_id);
				$data['commenters']								= usersWhoCommentedThisPost($post_id);
				$data['comments']									= userCommentsForThisPost($post_id);
				
	      }	
	   }			
	}
	
	return $data;	
}



//Route to get timeline feeds of users I follow
//May 30,2016
//June 06, 2016: Added location (client centre location)
//July 10,2016: temp fix- user id equal session id.
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//August 11,2016: Added array empty check
//September 01,2016: Added html decode for wysiwyg editor
//September 08,2016: Fetch data based on country and location filter
//October 07,2016: Added filtering to hide posts I don't like
//October 07,2016: Added filtering to hide all post from people I don't like
function getFollowedMembersPosts()
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

	$limit = TIMELINE_POSTS_LIMIT;
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
	
	//location
	if(isset($_GET['location']))
	{
		$location = validate_input($_GET['location']);		
	}
	else
	{
		$location = 0;
	}
	
	//center code
	if(isset($_GET['country']))
	{
		$country = validate_input($_GET['country']);		
	}
	else
	{
		$country = 0;
	}	   
   
   $session_values=get_user_session();
	$my_session_id	= $session_values['id'];
    
   //$username=validate_input($_GET['user']);
	//$myUserId	= getUserIdfromUserName($username);
	$myUserId	= $my_session_id;
	
	$hidePosts	= postsIdontLike($my_session_id);
	if(!empty($hidePosts))
	{
		$hidePostsString = implode(",", $hidePosts);
	}
	else
	{
		$hidePostsString ="''";
	}
	
	$hidePeoplePosts	= postsFromUsersIdontLike($my_session_id);
	if(!empty($hidePeoplePosts))
	{
		$hidePeoplePostsString = implode(",", $hidePeoplePosts);
	}
	else
	{
		$hidePeoplePostsString ="''";
	}	
	
	$usersIFollow= getAllUserIDsIFollow($myUserId);
	
	if (!empty($usersIFollow)) 
	{
		$usersIFollowString = implode(",", $usersIFollow);

		if($country >0 && $location > 0)
		{
			$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.posted_by IN (".$usersIFollowString.") AND CP.client_location=".$location." AND EUT.status=1 AND EUT.business_opp!=1 
				AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
				ORDER BY EUT.created_at DESC 
				LIMIT $start, $limit";
		}
		else if($country ==0 && $location > 0)
		{				
			$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.posted_by IN (".$usersIFollowString.") AND CP.client_location=".$location." AND EUT.status=1 AND EUT.business_opp!=1 
				AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
				ORDER BY EUT.created_at DESC 
				LIMIT $start, $limit";
		}		
		else if($country >0 && $location ==0)
		{
			//fetch all locations for this country
			$locationIds		= fetchLocationIDsUnderCountry($country);
			if(!empty($locationIds))
			{
				$locationIdString = implode(",", $locationIds);							
				$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
					FROM entrp_user_timeline AS EUT
					LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
					LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
					LEFT JOIN location_info AS LI ON LI.id=CP.client_location
					WHERE EUT.posted_by IN (".$usersIFollowString.") AND CP.client_location IN (".$locationIdString.") AND EUT.status=1 AND EUT.business_opp!=1 
					AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
					ORDER BY EUT.created_at DESC 
					LIMIT $start, $limit";
			}
			else
			{
				$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
					FROM entrp_user_timeline AS EUT
					LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
					LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
					LEFT JOIN location_info AS LI ON LI.id=CP.client_location
					WHERE EUT.posted_by IN (".$usersIFollowString.") AND EUT.status=1 AND EUT.business_opp!=1 
					AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
					ORDER BY EUT.created_at DESC 
					LIMIT $start, $limit";
			}			
		}
		else
		{
				$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
					FROM entrp_user_timeline AS EUT
					LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
					LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
					LEFT JOIN location_info AS LI ON LI.id=CP.client_location
					WHERE EUT.posted_by IN (".$usersIFollowString.") AND EUT.status=1 AND EUT.business_opp!=1 
					AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
					ORDER BY EUT.created_at DESC 
					LIMIT $start, $limit";
		}

		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   $i=0; //to initiate count
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	      {
	      	$post_id														=	$row['post_id'];
	      	
	      	$data[$i]['post_id']										=	$row['post_id'];      	
				$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
				$data[$i]['image']										=	$row['post_img'];
				$data[$i]['uploadType']									=	$row['uploadType'];
				$data[$i]['created_at']									=	$row['created_at'];
				$data[$i]['bussOpp']										=	$row['business_opp'];
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
				//$data[$i]['post_author']['companyName']			=	$row['company_name'];
				$data[$i]['post_author']['userName']				=	$row['username'];
				$data[$i]['post_author']['location']				=	$row['location_desc'];
				
				$post_by														=	$row['posted_by'];   
				$companyId													=	getCompanyIDfromUserID($post_by);
				$data[$i]['post_author']['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
				
				$data[$i]['isLiked']										= doILikeThisPost($post_id);
				$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
				$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
				$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
				$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
				$data[$i]['comments']									= userCommentsForThisPost($post_id);				
	
				$i++;
	      }	
	   }		
	}

	return $data;	

	
	/*
	var posts = [
		{
			"post_id": "123456",
			"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
			"image": "jpg01.jpg",
			"created_at": "2015-05-12T14:54:31.566Z",
			"post_author": {
				"id": "1",
				"firstName": "Jordan",
				"lastName": "Rains",
				"avatar": "member-default.jpg",
				"position": "Office Assistant",
				"companyName": "Pet Studio.com",
				"userName": "jordan"
			},
			"isLiked": false,
			"likes_count": 1,
			"likers": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments_count": 1,
			"commenters": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments": [
				{
					"content": "congrats Albert!",
					"created_at": "2015-05-12T15:06:51.457Z",
					"likes_count": 0,
					"likers": [],
					"comment_author": {
						"id": "3",
						"firstName": "John",
						"lastName": "Smith",
						"avatar": "member-default.jpg",
						"position": "Creative Director",
						"companyName": "Wendy Skelton",
						"userName": "John"
					}
				}
			]
		}
	];

	vm.posts = posts;
	*/

}


//Route to get timeline feeds of all users
//May 30,2016
//June 06,2016: Added user location (centre location)
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
//September 08,2016: Fetch data based on country and location filter
//October 07,2016: Added filtering to hide posts I don't like
//October 07,2016: Added filtering to hide all post from people I don't like
function getAllPosts()
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

	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$hidePosts	= postsIdontLike($my_session_id);
	if(!empty($hidePosts))
	{
		$hidePostsString = implode(",", $hidePosts);
	}
	else
	{
		$hidePostsString ="''";
	}
	
	$hidePeoplePosts	= postsFromUsersIdontLike($my_session_id);
	if(!empty($hidePeoplePosts))
	{
		$hidePeoplePostsString = implode(",", $hidePeoplePosts);
	}
	else
	{
		$hidePeoplePostsString ="''";
	}

	
	$data= array();
	
	$limit = TIMELINE_POSTS_LIMIT;
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
	
	//location
	if(isset($_GET['location']))
	{
		$location = validate_input($_GET['location']);		
	}
	else
	{
		$location = 0;
	}
	
	//center code
	if(isset($_GET['country']))
	{
		$country = validate_input($_GET['country']);		
	}
	else
	{
		$country = 0;
	}	
	
	if($country >0 && $location > 0)
	{
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp!=1 AND CP.client_location=".$location." 
			AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	}
	else if($country ==0 && $location > 0)
	{
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp!=1 AND CP.client_location=".$location." 
			AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	}		
	else if($country >0 && $location ==0)
	{
		//fetch all locations for this country
		$locationIds		= fetchLocationIDsUnderCountry($country);
		if(!empty($locationIds))
		{
			$locationIdString = implode(",", $locationIds);		
			$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.business_opp!=1 AND CP.client_location IN (".$locationIdString.") 
				AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
				ORDER BY EUT.created_at DESC 
				LIMIT $start, $limit ";
		}
		else
		{
			$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
				FROM entrp_user_timeline AS EUT
				LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
				LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
				LEFT JOIN location_info AS LI ON LI.id=CP.client_location
				WHERE EUT.status=1 AND EUT.business_opp!=1 
				AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
				ORDER BY EUT.created_at DESC 
				LIMIT $start, $limit ";
		}
		
	}
	else
	{
		$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid 
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.status=1 AND EUT.business_opp!=1 
			AND EUT.post_id NOT IN (".$hidePostsString.") AND EUT.posted_by NOT IN (".$hidePeoplePostsString.")
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit ";
	}
	
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	$data[$i]['postTags']									=	getTimelinePostTags($post_id);
      	$data[$i]['post_id']										=	$row['post_id'];      	
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	

	return $data;	

	
	/*
	var posts = [
		{
			"post_id": "123456",
			"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
			"image": "jpg01.jpg",
			"created_at": "2015-05-12T14:54:31.566Z",
			"post_author": {
				"id": "1",
				"firstName": "Jordan",
				"lastName": "Rains",
				"avatar": "member-default.jpg",
				"position": "Office Assistant",
				"companyName": "Pet Studio.com",
				"userName": "jordan"
			},
			"isLiked": false,
			"likes_count": 1,
			"likers": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments_count": 1,
			"commenters": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments": [
				{
					"content": "congrats Albert!",
					"created_at": "2015-05-12T15:06:51.457Z",
					"likes_count": 0,
					"likers": [],
					"comment_author": {
						"id": "3",
						"firstName": "John",
						"lastName": "Smith",
						"avatar": "member-default.jpg",
						"position": "Creative Director",
						"companyName": "Wendy Skelton",
						"userName": "John"
					}
				}
			]
		}
	];

	vm.posts = posts;
	*/

}


//To fetch user data from entrp_login using postId
//Arshad
function fetchLoginInfoUsingPostid($postId){
	$data = array();
	
	$qry = "
				SELECT L.* 
				FROM entrp_login as L 
				JOIN entrp_user_timeline as UT ON UT.posted_by = L.clientid
				WHERE UT.post_id = ".$postId."
			 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data['clientid'] = $row['clientid'];
      	$data['username'] = $row['username'];
      	$data['email'] = $row['email'];					
		}
	}
	return $data;
}

//Function to unlike a timeline post\
//May 20,2016
//July 14,2016: Timeline id and type added and if else if ladder
function unlikeThisPost()
{
	$data= array();
	$data1= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$unlikedPostId = $requestData->unlikedPostId;
		$postId=validate_input($unlikedPostId);
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		$likedUsersID=fetchUsersIDWhoLikesThisPost($postId);
		if (!empty($likedUsersID)) 
		{
			//if user not liked it
			if(in_array($my_session_id,$likedUsersID))
			{
				if (($key = array_search($my_session_id, $likedUsersID)) !== false) 
				{
				    unset($likedUsersID[$key]);
				}
				
				if(!empty($likedUsersID))
				{	
					//$likedUsersIDArr[]		=	$likedUsersID;		
					//$likedUsersIDJSON		= json_encode($likedUsersID);
					$likedUsersIDJSON 		= json_encode(array_values($likedUsersID));
					/*
					$qry0="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='' WHERE  post_id=".$postId."";
					setData($qry0);
					*/
					$qry="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='".$likedUsersIDJSON."' WHERE  post_id=".$postId."";
					if(setData($qry))
					{
						$data['response']='success';
						
						//Let me see to whom this post belongs
						$Host=whoIsTheAuthorOfThisPost($postId);
						$notify_type="like";
						$notify_to=$Host;
						$notify_from=$my_session_id;
						$post_id=$postId;
						$notify_for="user";
						deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
					}
					else
					{
						$data['response']='failed';
					}
				}
				else
				{
					$qry2="DELETE FROM entrp_user_timeline_post_likes WHERE  post_id=".$postId."";
					if(setData($qry2))
					{
						$data['response']='success';
						
						//Let me see to whom this post belongs
						$Host=whoIsTheAuthorOfThisPost($postId);
						$notify_type="like";
						$notify_to=$Host;
						$notify_from=$my_session_id;
						$post_id=$postId;
						$notify_for="user";
						deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
					}
					else
					{
						$data['response']='failed';
					}
				}
				
			}
		}
		
		/*
		if($timeLine==1)
		{
			//home page all posts
			$data=getAllPosts();
		}
		else if($timeLine==2)
		{
			//member profile timeline posts
			$memberUsername = $requestData->username;
			$username		 = validate_input($memberUsername);
			$data		       = refetchMemberNewsFeed($username);
		}
		else if($timeLine==3)
		{
			//home page followed posts
			$data=getFollowedMembersPosts();
		
		}
		else if($timeLine==4)
		{
			//company profile timeline posts
			$companyUsername = $requestData->username;
			$username		  = validate_input($companyUsername);
			$data				  = refetchCompanyPosts($username);
		}
		else if($timeLine==5)
		{
			//business opportunities page
			$data=getAllBusinessOpportunities();
		}
		else if($timeLine==6)
		{
			//my company profile timeline posts
			$data=getmyCompanyPosts();
		}
		else if($timeLine==7)
		{
			//home page my posts/myprofile timeline
			$data=getMyOwnNewsFeed();
		}
      else if($timeLine==8)
      {
          //home page my posts/myprofile timeline
          $data=getBusinessOpportunitiesForMe();
      }
		else
		{
			//single post
			$data=refetchThisPost($postId);
		}
		*/
	}
	return $data;
}


//Function to fetch userIDs who liked this timeline post
//May 20,2016
function fetchUsersIDWhoLikesThisPost($post_id)
{
	$data= array();

	$qry="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id."";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['liked_user_ids']);  					
		}
	}
	return $data;
}

//Function to like a timelinePost
//May 20,2016
//July 14,2016: Timeline id and type added and if else if ladder
function likeThisPost()
{
	$data= array();
	$data1= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$my_session_username	= $session_values['username'];
	
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$likedPostId = $requestData->likedPostId;
		$postId=validate_input($likedPostId);
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		$likedUsersID=fetchUsersIDWhoLikesThisPost($postId);
		//$likedUsersIDArray = array_filter($likedUsersID);
		//if array is not empty
		if (!empty($likedUsersID)) 
		{
			//if user not liked it
			if(! in_array($my_session_id,$likedUsersID))
			{
				array_push($likedUsersID, $my_session_id);
				$likedUsersIDJSON		= json_encode($likedUsersID);
				
				$qry="UPDATE entrp_user_timeline_post_likes SET liked_user_ids='".$likedUsersIDJSON."' WHERE  post_id=".$postId."";
				if(setData($qry))
				{
					
					$postAuthorDetails = fetchLoginInfoUsingPostid($postId);
			
					if($my_session_id !== $postAuthorDetails['clientid'])
					{	
						/*			
						$myPreferences = getMyPreferences();
					
						if($myPreferences['likes'] == 'true')
						{
							$notification_array = array(
															'type' => 'like',
															'postAuthorEmail' => $postAuthorDetails['email'],
															'postAuthorUsername' => $postAuthorDetails['username'],
															'likerUsername' => $my_session_username
														 );
							$data['mail_send'] = send_notification_mail($notification_array);
						}
						*/
						
						$Host=$postAuthorDetails['clientid'];
						$notify_type="like";
						$notify_to=$Host;
						$notify_from=$my_session_id;
						$post_id=$postId;
						$notify_for="user";
						addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
					}
			
					$data['response']='success';
				}
				else
				{
					$data['response']='failed';
				}
			}
		}
		else
		{
			array_push($data1, $my_session_id);
			$likedUsersIDJSON		= json_encode($data1);
			
			$qry2="INSERT INTO entrp_user_timeline_post_likes(post_id,liked_user_ids) VALUES(".$postId.",'".$likedUsersIDJSON."')";
			if(setData($qry2))
			{
				
				$postAuthorDetails = fetchLoginInfoUsingPostid($postId);
			
				if($my_session_id !== $postAuthorDetails['clientid'])
				{
					/*				
					$myPreferences = getMyPreferences();
				
					if($myPreferences['likes'] == 'true')
					{
						$notification_array = array(
														'type' => 'like',
														'postAuthorEmail' => $postAuthorDetails['email'],
														'postAuthorUsername' => $postAuthorDetails['username'],
														'likerUsername' => $my_session_username
													 );
						$data['mail_send'] = send_notification_mail($notification_array);
					}
					*/
					$Host=$postAuthorDetails['clientid'];
					$notify_type="like";
					$notify_to=$Host;
					$notify_from=$my_session_id;
					$post_id=$postId;
					$notify_for="user";
					addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
				}
					
				$data['response']='success';
			}
			else
			{
				$data['response']='failed';
			}		
		}
		
		/*
		if($timeLine==1)
		{
			//home page all posts
			$data=getAllPosts();
		}
		else if($timeLine==2)
		{
			//member profile timeline posts
			$memberUsername = $requestData->username;
			$username		 = validate_input($memberUsername);
			$data		       = refetchMemberNewsFeed($username);
		}
		else if($timeLine==3)
		{
			//home page followed posts
			$data=getFollowedMembersPosts();
		
		}
		else if($timeLine==4)
		{
			//company profile timeline posts
			$companyUsername = $requestData->username;
			$username		  = validate_input($companyUsername);
			$data				  = refetchCompanyPosts($username);
		}
		else if($timeLine==5)
		{
			//business opportunities page
			$data=getAllBusinessOpportunities();
		}
		else if($timeLine==6)
		{
			//my company profile timeline posts
			$data=getmyCompanyPosts();
		}
		else if($timeLine==7)
		{
			//home page my posts/myprofile timeline
			$data=getMyOwnNewsFeed();
		}
      else if($timeLine==8)
      {
         //home page my posts/myprofile timeline
         $data=getBusinessOpportunitiesForMe();
      }
		else
		{
			//single post
			$data=refetchThisPost($postId);
		}
		*/
	
	}
	return $data;
}


//Function to post a comment for a timeline post
//May 20,2016
//July 14,2016: Timeline id and type added and if else if ladder
//September 20,2016: Comments are in UTC time
function postThisComment()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$my_session_username	= $session_values['username'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$timelinePostId = $requestData->postId;
		$postId=validate_input($timelinePostId);
		
		$newComment = $requestData->postComment;
		$comment=validate_input($newComment);
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		$post_img='';
		date_default_timezone_set('UTC');
		$created_at=date('Y-m-d H:i:s');
		$posted_by=$my_session_id;

		$qry="INSERT INTO entrp_user_timeline_post_comments(post_id,comment,commented_by,commented_at) VALUES(".$postId.",'".$comment."',".$posted_by.",'".$created_at."')";
		if(setData($qry))
		{
			$postAuthorDetails = fetchLoginInfoUsingPostid($postId);
			
			if($my_session_id !== $postAuthorDetails['clientid'])
			{
				/*
				$myPreferences = getMyPreferences();
			
				if($myPreferences['comments'] == 'true'){
					$notification_array = array(
													'type' => 'comment',
													'postAuthorEmail' => $postAuthorDetails['email'],
													'postAuthorUsername' => $postAuthorDetails['username'],
													'commentAuthorUsername' => $my_session_username
												 );
					$data['mail_send'] = send_notification_mail($notification_array);
				}
				*/
				
				$Host=$postAuthorDetails['clientid'];
				$notify_type="comment";
				$notify_to=$Host;
				$notify_from=$my_session_id;
				$post_id=$postId;
				$notify_for="user";
				addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for);
			}
			
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
	
	}
	
	/*
	if($timeLine==1)
	{
		//home page all posts
		$data=getAllPosts();
	}
	else if($timeLine==2)
	{
		//member profile timeline posts
		$memberUsername = $requestData->username;
		$username		 = validate_input($memberUsername);
		$data		       = refetchMemberNewsFeed($username);
	}
	else if($timeLine==3)
	{
		//home page followed posts
		$data=getFollowedMembersPosts();
	
	}
	else if($timeLine==4)
	{
		//company profile timeline posts
		$companyUsername = $requestData->username;
		$username		  = validate_input($companyUsername);
		$data				  = refetchCompanyPosts($username);
	}
	else if($timeLine==5)
	{
		//business opportunities page
		$data=getAllBusinessOpportunities();
	}
	else if($timeLine==6)
	{
		//my company profile timeline posts
		$data=getmyCompanyPosts();
	}
	else if($timeLine==7)
	{
		//home page my posts/myprofile timeline
		$data=getMyOwnNewsFeed();
	}
    else if($timeLine==8)
    {
        //home page my posts/myprofile timeline
        $data=getBusinessOpportunitiesForMe();
    }
	else
	{
		//single post
		$data=refetchThisPost($postId);
	}
	*/
	return $data;
}

//Function to delete a comment for a timeline post
//October 31, 2016
//Jerry
function deleteThisComment()
{
	$data = array();
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$commentId = $requestData->commentId;
		//Check the comment owner is user itself
		$qry = "SELECT commented_by FROM entrp_user_timeline_post_comments  
			     WHERE post_comments_id=".$commentId." and commented_by=".$my_session_id."  ";
		$res = getData($qry);
	   $count_res = mysqli_num_rows($res);
		if($count_res > 0)
		{
			
				$qry = "DELETE FROM entrp_user_timeline_post_comments  
					     WHERE post_comments_id=".$commentId."";
				$res = setData($qry);
			   //$count_res = mysqli_affected_rows($res);
				if($res)
				{
					$data['response'] = 'success';
				}else{
					$data['response'] = 'failed';
				}
		}else{
				$data['response'] = 'failed';
		}
	
	}
	
	
	return $data;
}


//Function to fetch users who liked this comment
//May 19,2016
//August 11, 2016: Changes after implementing company-user relation
function usersWhoLikedThisComment($commentId)
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

	$data0= array();
	$data= array();
	$likes_count= 0;
	
	$qry0="SELECT liked_user_ids FROM entrp_user_timeline_comments_likes  
			WHERE comment_id=".$commentId." ";
	$res0=getData($qry0);
   $count_res0=mysqli_num_rows($res0);
	if($count_res0>0)
	{
		while($row0=mysqli_fetch_array($res0))
		{
			$data0		=	json_decode($row0['liked_user_ids']);  					
		}
		
		$likers_array = implode(",", $data0);
		
		$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
			 		 client_profile.avatar,client_profile.designation,client_profile.company_name 
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid IN(".$likers_array.")
	      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	$i=0;
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data[$i]['id']			=	$row['clientid'];
	   		
	   		if($row['avatar']!='')
				{
					$data[$i]['avatar']				=	$row['avatar'];
				}
				else
				{
					$data[$i]['avatar']				=	$member_default_avatar;
				}
				
				if($row['firstname']!='')
				{
					$data[$i]['firstName']			=	$row['firstname'];
				}
				else
				{
					$data[$i]['firstName']			=	'';
				}
				
				if($row['lastname']!='')
				{
					$data[$i]['lastName']			=	$row['lastname'];
				}
				else
				{
					$data[$i]['lastName']			=	'';
				}
				
				if($row['username']!='')
				{
					$data[$i]['userName']			=	$row['username'];
				}
				else
				{
					$data[$i]['userName']			=	'';
				}
				
				if($row['designation']!='')
				{
					$data[$i]['position']			=	htmlspecialchars_decode($row['designation'],ENT_QUOTES);
				}
				else
				{
					$data[$i]['position']			=	'';
				}
				
				$companyId								=	getCompanyIDfromUserID($data[$i]['id']);
				$data[$i]['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);
				/*
				if($row['company_name']!='')
				{
					$data[$i]['companyName']		=	$row['company_name'];
				}
				else
				{
					$data[$i]['companyName']		=	'';
				}
				*/
				$i++;
	   	}		   	   
	   }
	   else
	   {
				$data['id']					=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['avatar']			=	'';
				$data['position']			=	'';
				$data['companyName']		=	'';
				$data['userName']			=	'';		   
	   }		
	}
	return $data;

}


//Function to get count of how many users liked this comment
//May 19,2016
function howManyLikesThisCommentReceived($commentId)
{

	$data= array();
	$likes_count= 0;
	
	$qry="SELECT liked_user_ids FROM entrp_user_timeline_comments_likes  
			WHERE comment_id=".$commentId." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['liked_user_ids']);  					
		}
		
		$likes_count=sizeof($data);
	}   
	return $likes_count;	

}

//Function to count how many comments this timeline post received
//May 19,2016
function howManyCommentsThisPostReceived($post_id)
{
	//SELECT COUNT(post_comments_id) AS comments_count FROM entrp_user_timeline_post_comments WHERE post_id=1
	$data= array();
	$comments_count= 0;
	
	$qry="SELECT COUNT(post_comments_id) AS comments_count FROM entrp_user_timeline_post_comments  
			WHERE post_id=".$post_id." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$comments_count		=	$row['comments_count'];  					
		}
	}   
	return $comments_count;
}

//Function to fetch users who commented on this timeline post
//May 19,2016
//August 11, 2016: Changes after implementing company-user relation
function usersWhoCommentedThisPost($post_id)
{
	/*
	"commenters": [
			{
				"id": "3",
				"firstName": "John",
				"lastName": "Smith",
				"avatar": "member-default.jpg",
				"position": "Creative Director",
				"companyName": "Wendy Skelton",
				"userName": "John"
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
		
	$data0= array();
	$data= array();
	
	//SELECT commented_by FROM entrp_user_timeline_post_comments WHERE post_id=1
	$qry0="SELECT DISTINCT commented_by FROM entrp_user_timeline_post_comments  
			WHERE post_id=".$post_id." ";
	$res0=getData($qry0);
   $count_res0=mysqli_num_rows($res0);
	if($count_res0>0)
	{
		while($row0=mysqli_fetch_array($res0))
		{
			$data0[]		=	$row0['commented_by'];  
							
		}
		$commenters_array = implode(",", $data0);
		
		$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
			 		 client_profile.avatar,client_profile.designation,client_profile.company_name 
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid IN(".$commenters_array.")
	      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	$i=0;
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data[$i]['id']			=	$row['clientid'];
	   		
	   		if($row['avatar']!='')
				{
					$data[$i]['avatar']				=	$row['avatar'];
				}
				else
				{
					$data[$i]['avatar']				=	$member_default_avatar;
				}
				
				if($row['firstname']!='')
				{
					$data[$i]['firstName']			=	$row['firstname'];
				}
				else
				{
					$data[$i]['firstName']			=	'';
				}
				
				if($row['lastname']!='')
				{
					$data[$i]['lastName']			=	$row['lastname'];
				}
				else
				{
					$data[$i]['lastName']			=	'';
				}
				
				if($row['username']!='')
				{
					$data[$i]['userName']			=	$row['username'];
				}
				else
				{
					$data[$i]['userName']			=	'';
				}
				
				if($row['designation']!='')
				{
					$data[$i]['position']			=	$row['designation'];
				}
				else
				{
					$data[$i]['position']			=	'';
				}
				
				$companyId								=	getCompanyIDfromUserID($data[$i]['id']);
				$data[$i]['companyName'] 			= getCompanyNameUsingCompUserRelation($companyId);
				/*
				if($row['company_name']!='')
				{
					$data[$i]['companyName']		=	$row['company_name'];
				}
				else
				{
					$data[$i]['companyName']		=	'';
				}
				*/
				$i++;
	   	}		   	   
	   }
	   else
	   {
				$data['id']					=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['avatar']			=	'';
				$data['position']			=	'';
				$data['companyName']		=	'';
				$data['userName']			=	'';		   
	   }		
	}
	return $data;
}


//Function to fetch comments made on this timeline post
//May 19,2016
//June 06,2016: Added user location (centre location)
//June 14,2016: Sorted comments based on datetime posted
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
function userCommentsForThisPost($post_id)
{
	
	//SELECT ETC.post_comments_id,ETC.post_id,ETC.comment,ETC.commented_by,ETC.commented_at,
	//EP.firstname,EP.lastname,EP.username,
	//CP.avatar,CP.designation,CP.company_name
	//FROM entrp_user_timeline_post_comments AS ETC
	//LEFT JOIN entrp_login AS EP ON EP.clientid=ETC.commented_by 
	//LEFT JOIN client_profile AS CP ON EP.clientid=CP.clientid
	//WHERE ETC.post_id=1 AND ETC.status=1

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
	$qry="SELECT ETC.post_comments_id,ETC.post_id,ETC.comment,ETC.commented_by,ETC.commented_at, 
			EP.firstname,EP.lastname,EP.username,
			CP.avatar,CP.designation,CP.company_name,LI.location_desc
			FROM entrp_user_timeline_post_comments AS ETC
			LEFT JOIN entrp_login AS EP ON EP.clientid=ETC.commented_by 
			LEFT JOIN client_profile AS CP ON EP.clientid=CP.clientid
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE ETC.post_id=".$post_id." AND ETC.status=1 
			ORDER BY ETC.commented_at ASC
			";
   $res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$commentId												=  $row['post_comments_id'];
			$data[$i]['commentId']								=	$row['post_comments_id'];
			$data[$i]['content']									=	htmlspecialchars_decode($row['comment'],ENT_QUOTES);
			$data[$i]['created_at']								=	$row['commented_at'];
			$data[$i]['likes_count']							=	howManyLikesThisCommentReceived($commentId);
			$data[$i]['likers']									=	usersWhoLikedThisComment($commentId);			
			
			$data[$i]['comment_author']['id']				=	$row['commented_by'];
			$data[$i]['comment_author']['firstName']		=	$row['firstname'];
			$data[$i]['comment_author']['lastName']		=	$row['lastname'];
			if($row['avatar']!='')
			{
				$data[$i]['comment_author']['avatar']		=	$row['avatar'];
			}
			else
			{
				$data[$i]['comment_author']['avatar']		=	$member_default_avatar;
			} 
			
			$data[$i]['comment_author']['location']		=	$row['location_desc'];
			$data[$i]['comment_author']['position']		=	$row['designation'];
			//$data[$i]['comment_author']['companyName']	=	$row['company_name'];
			$data[$i]['comment_author']['userName']		=	$row['username'];
			
			$commented_by											=	$row['commented_by']; 
			$companyId												=	getCompanyIDfromUserID($commented_by);
			$data[$i]['comment_author']['companyName']	=  getCompanyNameUsingCompUserRelation($companyId);
			 			
			$i++;
		}		
	}
	return $data;	
	
	
	/*
	"comments": [
		{
			"content": "congrats Albert!",
			"created_at": "2015-05-12T15:06:51.457Z",
			"likes_count": 0,
			"likers": [],
			"comment_author": {
				"id": "3",
				"firstName": "John",
				"lastName": "Smith",
				"avatar": "member-default.jpg",
				"position": "Creative Director",
				"companyName": "Wendy Skelton",
				"userName": "John"
			}
		}
	]
	*/

}

//Function to check whether the current user like this timeline post or not
//May 19,2016
function doILikeThisPost($postid)
{
	$data= array();
	$post_id=(int)$postid;
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id)
	{
		$qry="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id." ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
		if($count_res>0)
		{
			while($row=mysqli_fetch_array($res))
			{
				$data		=	json_decode($row['liked_user_ids']);  					
			}
			
			if(in_array($my_session_id,$data))
			{
			    return true;
			}
			else
			{
				return false;
			}
		}
		{
			return false;
		}
	}
	else 
	{
		return false;
	}
}

//Function to fetch users who like this timeline post
//May 19,2016
//August 11, 2016: Changes after implementing company-user relation
function usersWhoLikesThisPost($post_id)
{
	//SELECT liked_user_ids FROM entrp_user_timeline_post_likes WHERE post_id=1
	
	
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
	
	$data0= array();
	$data= array();
	$likes_count= 0;
	
	$qry0="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id." ";
	$res0=getData($qry0);
   $count_res0=mysqli_num_rows($res0);
	if($count_res0>0)
	{
		while($row0=mysqli_fetch_array($res0))
		{
			$data0		=	json_decode($row0['liked_user_ids']);  					
		}
		
		$likers_array = implode(",", $data0);
		
		$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
			 		 client_profile.avatar,client_profile.designation,client_profile.company_name 
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			WHERE entrp_login.clientid IN(".$likers_array.")
	      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	$i=0;
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data[$i]['id']			=	$row['clientid'];
	   		
	   		if($row['avatar']!='')
				{
					$data[$i]['avatar']				=	$row['avatar'];
				}
				else
				{
					$data[$i]['avatar']				=	$member_default_avatar;
				}
				
				if($row['firstname']!='')
				{
					$data[$i]['firstName']			=	$row['firstname'];
				}
				else
				{
					$data[$i]['firstName']			=	'';
				}
				
				if($row['lastname']!='')
				{
					$data[$i]['lastName']			=	$row['lastname'];
				}
				else
				{
					$data[$i]['lastName']			=	'';
				}
				
				if($row['username']!='')
				{
					$data[$i]['userName']			=	$row['username'];
				}
				else
				{
					$data[$i]['userName']			=	'';
				}
				
				if($row['designation']!='')
				{
					$data[$i]['position']			=	$row['designation'];
				}
				else
				{
					$data[$i]['position']			=	'';
				}
				
				$companyId								=	getCompanyIDfromUserID($data[$i]['id']);
				$data[$i]['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
				/*
				if($row['company_name']!='')
				{
					$data[$i]['companyName']		=	$row['company_name'];
				}
				else
				{
					$data[$i]['companyName']		=	'';
				}
				*/
				$i++;
	   	}		   	   
	   }
	   else
	   {
				$data['id']					=	'';
				$data['firstName']		=	'';
				$data['lastName']			=	'';
				$data['avatar']			=	'';
				$data['position']			=	'';
				$data['companyName']		=	'';
				$data['userName']			=	'';		   
	   }		
	}
	return $data;

}

//Function to count how many likes a timeline post received
//May 19,2016
function howManyLikesThisPostReceived($post_id)
{
	//SELECT liked_user_ids FROM entrp_user_timeline_post_likes WHERE post_id=1
	
	$data= array();
	$likes_count= 0;
	
	$qry="SELECT liked_user_ids FROM entrp_user_timeline_post_likes  
			WHERE post_id=".$post_id." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$data		=	json_decode($row['liked_user_ids']);  					
		}
		
		$likes_count=sizeof($data);
	}   
	return $likes_count;	
}


//Function to fetch myprofile/my timeline posts to show in home and my profile
//July 09,2016
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function getMyOwnNewsFeed()
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
	
	$limit = TIMELINE_POSTS_LIMIT;
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
   //$username=validate_input($_GET['user']);
	//$my_id	= getUserIdfromUserName($username);
	$my_id	=$my_session_id;
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by=".$my_id." AND EUT.status=1 AND EUT.business_opp!=1
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id'];  
        
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['created_at']									=	$row['created_at'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by										=	$row['posted_by'];   
			$companyId									=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] = getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;	


}


//Function to get member news feed to timeline
//May 18,2016
//June 06, 2016: Added location (client centre location)
//July 19, 2016: fetch buss opp flag
//August 11, 2016: Fetch post_by of timeline post
//August 11, 2016: Changes after implementing company-user relation
//September 01,2016: Added html decode for wysiwyg editor
function getMyNewsFeed()
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

	$limit = TIMELINE_POSTS_LIMIT;
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
   
   $username=validate_input($_GET['user']);
	$my_id	= getUserIdfromUserName($username);
		
	$qry="SELECT EUT.post_id,EUT.content,EUT.post_img,EUT.uploadType,EUT.created_at,EUT.business_opp,EL.clientid,EL.firstname,EL.lastname,EL.username,CP.company_name,CP.designation,CP.avatar,LI.location_desc,EUT.posted_by  
			FROM entrp_user_timeline AS EUT
			LEFT JOIN entrp_login AS EL ON EL.clientid=EUT.posted_by
			LEFT JOIN client_profile AS CP ON CP.clientid=EL.clientid
			LEFT JOIN location_info AS LI ON LI.id=CP.client_location
			WHERE EUT.posted_by=".$my_id." AND EUT.status=1 AND EUT.business_opp!=1 
			ORDER BY EUT.created_at DESC 
			LIMIT $start, $limit";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$post_id														=	$row['post_id'];
      	
      	$data[$i]['post_id']										=	$row['post_id']; 
			$data[$i]['content']										=	html_entity_decode(htmlspecialchars_decode($row['content'],ENT_QUOTES));
			$data[$i]['image']										=	$row['post_img'];
			$data[$i]['uploadType']									=	$row['uploadType'];
			$data[$i]['bussOpp']										=	$row['business_opp'];
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
			//$data[$i]['post_author']['companyName']			=	$row['company_name'];
			$data[$i]['post_author']['userName']				=	$row['username'];
			$data[$i]['post_author']['location']				=	$row['location_desc'];
			
			$post_by														=	$row['posted_by'];   
			$companyId													=	getCompanyIDfromUserID($post_by);
			$data[$i]['post_author']['companyName'] 			=  getCompanyNameUsingCompUserRelation($companyId);
			
			$data[$i]['isLiked']										= doILikeThisPost($post_id);
			$data[$i]['likes_count']								= howManyLikesThisPostReceived($post_id);
			$data[$i]['likers']										= usersWhoLikesThisPost($post_id);
			$data[$i]['comments_count']							= howManyCommentsThisPostReceived($post_id);
			$data[$i]['commenters']									= usersWhoCommentedThisPost($post_id);
			$data[$i]['comments']									= userCommentsForThisPost($post_id);
			

			$i++;
      }	
   }
	return $data;	

	
	/*
	var posts = [
		{
			"post_id": "123456",
			"content": "Hi, we recently noticed an increased sign up for our eVoiceMail.net service particularly from users from US. Anyone know why and is interested to help us to market our service to even more peeps?",
			"image": "jpg01.jpg",
			"created_at": "2015-05-12T14:54:31.566Z",
			"post_author": {
				"id": "1",
				"firstName": "Jordan",
				"lastName": "Rains",
				"avatar": "member-default.jpg",
				"position": "Office Assistant",
				"companyName": "Pet Studio.com",
				"userName": "jordan"
			},
			"isLiked": false,
			"likes_count": 1,
			"likers": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments_count": 1,
			"commenters": [
				{
					"id": "3",
					"firstName": "John",
					"lastName": "Smith",
					"avatar": "member-default.jpg",
					"position": "Creative Director",
					"companyName": "Wendy Skelton",
					"userName": "John"
				}
			],
			"comments": [
				{
					"content": "congrats Albert!",
					"created_at": "2015-05-12T15:06:51.457Z",
					"likes_count": 0,
					"likers": [],
					"comment_author": {
						"id": "3",
						"firstName": "John",
						"lastName": "Smith",
						"avatar": "member-default.jpg",
						"position": "Creative Director",
						"companyName": "Wendy Skelton",
						"userName": "John"
					}
				}
			]
		}
	];

	vm.posts = posts;
	*/

}

//Function to add new feed to timeline
//May 18,2016
//July 14,2016: Timeline id and type added and if else if ladder
//September 15, 2016: post time set to UTC
function postCurrentPost()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id)
	{
		$requestData = json_decode(file_get_contents("php://input"));
		
		$newPost = $requestData->newPost;
		$content=validate_input($newPost);
		
		$b64String = $requestData->imgString;
		$imgString=$b64String;
		
		$timelineId = $requestData->timeLine;
		$timeLine=validate_input($timelineId);
		
		if($imgString!='')
		{
			$post_img=uploadTimelineImage($imgString,6);
		}
		else
		{
			$post_img='';
		}
		date_default_timezone_set('UTC');
		$created_at=date('Y-m-d H:i:s');
		$posted_by=$my_session_id;
		
		$qry="INSERT INTO entrp_user_timeline(content,post_img,created_at,posted_by) VALUES('".$content."','".$post_img."','".$created_at."',".$posted_by.")";
		if(setData($qry))
		{
			$data['response']='success';
		}
		else
		{
			$data['response']='failed';
		}
		
		if($timeLine==1)
		{
			//home page all posts
			$data=getAllPosts();
		}
		else if($timeLine==2)
		{
			//member profile timeline posts
			$memberUsername = $requestData->username;
			$username		 = validate_input($memberUsername);
			$data		       = refetchMemberNewsFeed($username);
		}
		else if($timeLine==3)
		{
			//home page followed posts
			$data=getFollowedMembersPosts();
		
		}
		else if($timeLine==4)
		{
			//company profile timeline posts
			$companyUsername = $requestData->username;
			$username		  = validate_input($companyUsername);
			$data				  = refetchCompanyPosts($username);
		}
		else if($timeLine==5)
		{
			//business opportunities page
			$data=getAllBusinessOpportunities();
		}
		else if($timeLine==6)
		{
			//my company profile timeline posts
			$data=getmyCompanyPosts();
		}
		else if($timeLine==7)
		{
			//home page my posts/myprofile timeline
			$data=getMyOwnNewsFeed();
		}
        else if($timeLine==8)
		{
			//home page my posts/myprofile timeline
			$data=getBusinessOpportunitiesForMe();
		}
		else
		{
			//single post
			$data=refetchThisPost($postId);
		}
		
	}
	return $data;

}

//Function to test time-line module
//November 31,2016
function testTimelinePosts()
{

	$resp= array();
	$data = json_decode(file_get_contents("php://input"));
	$email = $data->name;
	$pass = $data->date;
	$qry="INSERT INTO users(email,password) VALUES('".$email."','".$pass."')";
	if( setData($qry))
	{
		$resp['msg']				=	"saved";    
	} 
	else 
	{
		$resp['msg']				=	"notsaved";   
	}
	return $resp;

}

?>