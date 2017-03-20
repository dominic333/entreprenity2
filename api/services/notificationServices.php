<?php



//Function to mark all notifications as read for a user
//October 14,2016
function markThisNotificationAsRead()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id!='')
	{
		date_default_timezone_set('UTC');
		$readAt=date('Y-m-d H:i:s');
	
		//UPDATE entrp_user_notifications SET read_unread=1, read_at='2016-11-31 12:12:12' WHERE notify_to=3 AND read_unread=0
		$qry2="UPDATE entrp_user_notifications SET read_unread=1, read_at='".$readAt."' WHERE notify_to=".$my_session_id." AND read_unread=0 ";
		if(setData($qry2))
		{
			$data='success';
		}
		else
		{
			$data='failure';
		}
	}	
	return $data;
}

//Function to mark all notifications as read for a user
//October 14,2016
function markAllNotificationsAsRead()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	if($my_session_id!='')
	{
		date_default_timezone_set('UTC');
		$readAt=date('Y-m-d H:i:s');
	
		//UPDATE entrp_user_notifications SET read_unread=1, read_at='2016-11-31 12:12:12' WHERE notify_to=3 AND read_unread=0
		$qry2="UPDATE entrp_user_notifications SET read_unread=1, read_at='".$readAt."' WHERE notify_to=".$my_session_id." AND read_unread=0 ";
		if(setData($qry2))
		{
			$data='success';
		}
		else
		{
			$data='failure';
		}
	}	
	return $data;
}


//Function to read total unread notifications
//August 18,2016 
function getAllUnreadNotifications()
{
	$data= array();
	//$data['totalUnread']	=	0;	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	//if($my_session_id)
	//{
		//SELECT COUNT(notify_id) AS totalUnread FROM entrp_user_notifications WHERE notify_to=1 AND read_unread=0
		$qry="SELECT COUNT(notify_id) AS totalUnread FROM entrp_user_notifications WHERE notify_to=".$my_session_id." AND read_unread=0";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	   	{
	   		$data['totalUnread']	=	$row['totalUnread'];
	   		//$data['totalUnread']	=	$qry;
	   	}	   
	   }
	   else
	   {
			$data['totalUnread']	=	0;	   
	   }
	//}	
	return $data;
}



//Function to delete a notification from notification table
//June 07,2016
function deleteANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for)
{
	$qry="DELETE FROM entrp_user_notifications 
			WHERE notify_type='".$notify_type."' AND notify_to=".$notify_to." AND notify_from=".$notify_from." AND post_id=".$post_id." AND notify_for='".$notify_for."' ";
	setData($qry);
}

//Function to add a notification to notification table
//June 07,2016
//September 15, 2016: Notification time set to UTC
function addANotificationForThis($notify_type,$notify_to,$notify_from,$post_id,$notify_for)
{
	date_default_timezone_set('UTC');
	$created_at=date('Y-m-d H:i:s');
		
	$qry="INSERT INTO entrp_user_notifications (notify_type,notify_to,notify_from,post_id,created_at,notify_for) 
			VALUES('".$notify_type."',".$notify_to.",".$notify_from.",".$post_id.",'".$created_at."','".$notify_for."')";
	setData($qry);

}

//Function to get a user notification
//June 06,2016
//October 18,2016: Removed read_unread condition from where clause
function getMyNotifications()
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
	
	$userName=validate_input($_GET['user']);
	$clientid_z=getUserIdfromUserName($userName);	
	$clientid=(int)$clientid_z;
	$session_values=get_user_session();
	$my_session_id	= (int)$session_values['id'];
	
	$data= array();	

	if($clientid==$my_session_id)
	{
		/*
		SELECT EUN.*,EL.username,EL.firstname,EL.lastname,CP.avatar,CP.company_name,CP.designation,LI.location_desc  
		FROM entrp_user_notifications AS EUN
		LEFT JOIN entrp_login AS EL ON EL.clientid=EUN.notify_from 
		LEFT JOIN client_profile AS CP ON EL.clientid=CP.clientid
		LEFT JOIN location_info AS LI ON LI.id=CP.client_location
		WHERE EUN.notify_to=1 AND EUN.read_unread=0
      ORDER BY EUN.created_at DESC
      */
      
      $i = 0;
		$qry = "SELECT EUN.*,EL.username,EL.firstname,EL.lastname,CP.avatar,CP.company_name,CP.designation,LI.location_desc  
				  FROM entrp_user_notifications AS EUN 
				  LEFT JOIN entrp_login AS EL ON EL.clientid=EUN.notify_from 
				  LEFT JOIN client_profile AS CP ON EL.clientid=CP.clientid 
				  LEFT JOIN location_info AS LI ON LI.id=CP.client_location 
				  WHERE EUN.notify_to=".$clientid."  				  
				  ORDER BY EUN.created_at DESC
				 ";
		//$data[$i]['qry']		=	$qry;
	   $res = getData($qry);
		$count_res = mysqli_num_rows($res);
		if($count_res > 0)
		{
			while($row = mysqli_fetch_array($res))
			{
				$data[$i]['notif_type']							=	$row['notify_type'];
				$data[$i]['notify_for']							=	$row['notify_for'];
				$data[$i]['notif_author']["id"]				=	$row['notify_from'];
				$data[$i]['notif_author']["firstName"]		=	$row['firstname'];
				$data[$i]['notif_author']["lastName"]		=	$row['lastname'];
				if($row['avatar']!='')
				{
					$data[$i]['notif_author']["avatar"]			=	$row['avatar'];
				}
				else
				{
					$data[$i]['notif_author']["avatar"]			=	$member_default_avatar;				
				}	
							
				$data[$i]['notif_author']["position"]		=	$row['designation'];
				$data[$i]['notif_author']["companyName"]	=	$row['company_name'];
				$data[$i]['notif_author']["userName"]		=	$row['username'];
				$data[$i]['notif_author']["location"]		=	$row['location_desc'];
				$data[$i]['post_id']								=	$row['post_id'];
				$data[$i]['created_at']							=	$row['created_at'];
				$data[$i]['isRead']								=	$row['read_unread'];
				
				if($data[$i]['notif_type']=='attend')
				{
					$data[$i]['event_tag'] =	getEventTagfromEventId($data[$i]['post_id']);
				}
				else
				{
					$data[$i]['event_tag']='';
				}
				
						
				$i++;
			}		
		}
	}
	
	return $data;

	/*
	//dummy data (sample json)
	data = [
		{
			"notif_type": "follow",
			"notif_author": {						
				"id": "2",
				"firstName": "Hola",
				"lastName": "Ferrel",
				"avatar": "member-default.jpg",
				"position": "CEO",
				"companyName": "Clever Sheep",
				"userName": "will"
			},
			"post_id": "",
			"created_at": "2016-05-26 14:29:00"
		},
		{
			"notif_type": "comment",
			"notif_author": {						
				"id": "2",
				"firstName": "Will",
				"lastName": "Ferrel",
				"avatar": "member-default.jpg",
				"position": "CEO",
				"companyName": "Clever Sheep",
				"userName": "will"
			},
			"post_id": "1234",
			"created_at": "2016-05-26 14:29:00"
		},
		{
			"notif_type": "like",
			"notif_author": {						
				"id": "2",
				"firstName": "Will",
				"lastName": "Ferrel",
				"avatar": "member-default.jpg",
				"position": "CEO",
				"companyName": "Clever Sheep",
				"userName": "will"
			},
			"post_id": "1234",
			"created_at": "2016-05-26 14:29:00"
		},
	]
	vm.notifications = data;
	*/	

}



/*
Post = {
					"post_id": "",
					"content": "",
					"image": "",
					"created_at": "",
					"post_author": {
						"id": "2",
						"firstName": "Will",
						"lastName": "Ferrel",
						"avatar": "member-default.jpg",
						"position": "CEO",
						"companyName": "Clever Sheep",
						"userName": "will"
					},
					"isLiked": false,
					"likes_count": 0,
					"likers": [],
					"comments_count": 0,
					"commenters": [],
					"comments": []
				};

*/

?>