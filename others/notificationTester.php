<?php

require_once ('../api/Query.php'); 
require_once '../api/constants.php';

//Notify Status Values
//	0: no action taken yet
// 1: email sent successfully
//	2: email sending failed (wrong email id or something) or some sorta error occurred, no further action required
//	3: notification email not needed for this, user did not enabled notification for this


$qry = "SELECT * FROM entrp_user_notifications WHERE status=4";
$res = getData($qry);
$count_res = mysqli_num_rows($res);
if($count_res > 0)
{
	while($row = mysqli_fetch_array($res))
	{
		$data["notify_id"]		=	$row['notify_id'];
		$data["notify_type"]		=	$row['notify_type'];
		$data["notify_to"]		=	$row['notify_to'];
		$data["notify_from"]		=	$row['notify_from'];
		$data["post_id"]			=	$row['post_id'];
		$data["notify_for"]		=	$row['notify_for'];
		$data["status"]			=	$row['status'];
		
		$data['userPreferences']  = getHisOrHerPreferences($data["notify_to"]);

		//if notification not enabled
		if($data['userPreferences']['followers'] == 0 && $data["notify_type"]=='follow')
		{
			$notifyStatus=3;
			updateNotificationAfterEmail($data["notify_id"],$notifyStatus);
		}
		
		//if notification not enabled		
		if($data['userPreferences']['comments'] == 0 && $data["notify_type"]=='comment')
		{
			$notifyStatus=3;
			updateNotificationAfterEmail($data["notify_id"],$notifyStatus);
		}		
		
		//if notification not enabled		
		if($data['userPreferences']['likes'] == 0 && $data["notify_type"]=='like')
		{
			$notifyStatus=3;
			updateNotificationAfterEmail($data["notify_id"],$notifyStatus);
		}
		
		
		if($data['userPreferences']['followers'] == 1 && $data["notify_type"]=='follow')
		{
			$following_user_details = fetch_info_from_entrp_login($data["notify_from"]);
			
			$followed_user_details = fetch_info_from_entrp_login($data["notify_to"]);
			
			$notification_array = array(
											'type' => 'follow',
											'following_user_id'  => $data["notify_from"],
											'following_username' => $following_user_details['username'],
											'followed_email' 		=> $followed_user_details['email'],
											'followed_username'  => $followed_user_details['username']
										);
										
			send_notification_mail($data["notify_id"],$notification_array);
		}
		
		if($data['userPreferences']['comments'] == 1 && $data["notify_type"]=='comment')
		{
			$postAuthorDetails = fetchLoginInfoUsingPostid($data["post_id"]);
			
			$commented_user_details = fetch_info_from_entrp_login($data["notify_from"]);
			
			$notification_array = array(
													'type' => 'comment',
													'postAuthorEmail' 		=> $postAuthorDetails['email'],
													'postAuthorUsername' 	=> $postAuthorDetails['username'],
													'commentAuthorUsername' => $commented_user_details['username']
												 );
												 
			send_notification_mail($data["notify_id"],$notification_array);
		}
		
		if($data['userPreferences']['likes'] == 1 && $data["notify_type"]=='like')
		{
			$postAuthorDetails = fetchLoginInfoUsingPostid($data["post_id"]);
			
			$liked_user_details = fetch_info_from_entrp_login($data["notify_from"]);
			
			$notification_array = array(
													'type' => 'like',
													'postAuthorEmail' 	=> $postAuthorDetails['email'],
													'postAuthorUsername' => $postAuthorDetails['username'],
													'likerUsername' 		=> $liked_user_details['username']
												 );
												 
			send_notification_mail($data["notify_id"],$notification_array);
		}
		

	}		
}

//Function to update notification table after crons
function updateNotificationAfterEmail($notifyId,$notifyStatus)
{
	//UPDATE entrp_user_notifications SET status= '0' WHERE notify_id = 1;
	$qry = "UPDATE entrp_user_notifications SET status=".$notifyStatus." 
			  WHERE notify_id=".$notifyId." ";
	setData($qry);

}

//Function to send SMTP mails
function send_notification_mail($notifyId,$notification_array)
{
	
	$type = $notification_array['type'];	
	//To send follow notification email	
	if($type === 'follow')
	{		
		$following_username = $notification_array['following_username'];
		$followed_username = $notification_array['followed_username'];
		$to_email = $followed_email = $notification_array['followed_email'];	
	}
	//To send comment notification email	
	elseif($type === 'comment')
	{		
		$commentAuthorUsername = $notification_array['commentAuthorUsername'];
		$postAuthorUsername = $notification_array['postAuthorUsername'];
		$to_email = $postAuthorEmail = $notification_array['postAuthorEmail'];
	}
	//To send like notification email	
	elseif($type === 'like')
	{		
		$likerUsername = $notification_array['likerUsername'];
		$postAuthorUsername = $notification_array['postAuthorUsername'];
		$to_email = $postAuthorEmail = $notification_array['postAuthorEmail'];
	}
	
	ob_start();
	include('../api/email_templates/notification.php');
	$notification_template = ob_get_contents();			
	ob_end_clean();
	
	$to = 'dominic@cliffsupport.com'; 
	//$to = $to_email; 
	$strSubject = NOTIFICATION_CONST;
	$message	= $notification_template;
	include('../api/sendmail/sendmail.php');	
	$mail->SetFrom(MS_SENTFROM, MS_SENTFROMNAME);
	$mail->Subject = ($strSubject);
	$mail->MsgHTML($message);
	$mail->AddAddress($to);
	//$mail->AddAddress(RECIPIENTEMAIL1, RECIPIENTNAME1);
	//$mail->AddAddress(RECIPIENTEMAIL2, RECIPIENTNAME2);
	if($mail->Send()) 
	{
 		//return "Mail send successfully";
 		$notifyStatus=5;
		updateNotificationAfterEmail($notifyId,$notifyStatus);
 		echo "true";
	} 
	else 
	{
		$notifyStatus=6;
		updateNotificationAfterEmail($notifyId,$notifyStatus);	
  		echo "false";
	}

	
}

//Function to get user information based on id
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
      	$data['clientid'] = $row['clientid'];
      	$data['username'] = $row['username'];
      	$data['email'] = $row['email'];     	
			$data['success'] = 'true';
		}
   }
   else
   {
   	$data['success'] = 'false';
	}
   return $data;
}


//To fetch user data from entrp_login using postId
function fetchLoginInfoUsingPostid($postId)
{
	$data = array();	
	$qry = " SELECT L.* 
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

//Function to get a user's preference
//Used to check whether it's necessary to send a notification mail
function getHisOrHerPreferences($userid)
{	
	$data = array();
	$qry = "SELECT *
			  FROM entrp_user_notification_preferences as UP
			  WHERE UP.clientid = ".$userid."
	       ";
	$res = getData($qry);
   $count_res = mysqli_num_rows($res);
   if($count_res > 0)
   {
   	while($row = mysqli_fetch_array($res))
   	{  		
   		$data['followers'] 				 = ($row['follow'] == 1 ? 1 : 0);
   		$data['comments'] 				 = ($row['comment'] == 1 ? 1 : 0);
   		$data['likes'] 					 = ($row['likes'] == 1 ? 1 : 0);
   		$data['mentions'] 				 = ($row['mention'] == 1 ? 1 : 0);
   		$data['businessOpportunities'] = ($row['business_opportunity'] == 1 ? 1 : 0);
   	}	   	
   }
   else
   {  		
		$data['followers'] = '';
		$data['comments'] = '';
		$data['likes'] = '';
		$data['mentions'] = '';
		$data['businessOpportunities'] = '';	   
   }	
	return $data;
}


?>