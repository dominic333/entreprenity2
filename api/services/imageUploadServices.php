<?php

//Function to generate unique timeline image name
//September 21,2016
function uniqueTimelineImageName()
{
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 10 digit token
	//SELECT * FROM `entrp_user_timeline` where post_img like '%timelineimgdominic.ronquillo20160816080631.jpeg%'
   $qry = "SELECT * FROM entrp_user_timeline where post_img like '%$token%'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueTimelineImageName();
   } 
   else 
   {
      return $token;
   }	
}

//Function to generate unique user profile pic name
//September 21,2016
function uniqueUserProfilePicName()
{
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 10 digit token
   $qry = "SELECT * FROM client_profile where avatar like '%$token%'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueUserProfilePicName();
   } 
   else 
   {
      return $token;
   }	
}

//Function to generate unique company profile pic name
//September 21,2016
function uniqueCompanyProfilePicName()
{
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 10 digit token
   $qry = "SELECT * FROM company_profiles where avatar like '%$token%'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueCompanyProfilePicName();
   } 
   else 
   {
      return $token;
   }	
}

//Function to generate unique event poster name
//September 21,2016
function uniqueEventPostersName()
{	
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 10 digit token
   $qry = "SELECT * FROM entrp_events where poster like '%$token%'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueEventPostersName();
   } 
   else 
   {
      return $token;
   }	
}


//Function to upload timeline post image
//July 16,2016
//September 21,2016: Unique filenames for posts published
function uploadTimelineImage($uploadImg,$uploadType)
{
	$data= array();
	$data='not uploaded';
    
	$session_values=get_user_session();
	$my_session_id				= $session_values['id'];
	$my_session_firstname 	= $session_values['firstname'];
	$my_session_lastname		= $session_values['lastname'];
	$my_session_username		= $session_values['username'];
	
	$upAt=date('YmdHis');
	
	// $uploadType values
	// 6- timeline post pic
	// 7- buss opp post pic

	
	if (strpos($uploadImg, 'data:image/png;base64') !== false) 
	{
   	$img = str_replace('data:image/png;base64,', '', $uploadImg);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);	
		$extension= PNG;
	}
	
	if (strpos($uploadImg, 'data:image/gif;base64') !== false) 
	{
   	$img = str_replace('data:image/gif;base64,', '', $uploadImg);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);	
		$extension= GIF; 
	}
	
	if (strpos($uploadImg, 'data:image/jpeg;base64') !== false) 
	{
   	$img = str_replace('data:image/jpeg;base64,', '', $uploadImg);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);	
		$extension= JPEG; 
	}
	
	if($uploadType==6)
	{
		$timeLineImageName=uniqueTimelineImageName();
		$fileName 	 = TIMELINE_POST_PIC.$timeLineImageName.$extension;
		$filePath 	 = TIMELINE_POST_PIC_UPL.$timeLineImageName.$extension;
		//$fileName 	 = TIMELINE_POST_PIC.'timelineimg'.$my_session_username.$upAt.$extension;
		//$filePath 	 = TIMELINE_POST_PIC_UPL.'timelineimg'.$my_session_username.$upAt.$extension;
	}
	
	if($uploadType==7)
	{
		$timeLineImageName=uniqueTimelineImageName();
		$fileName 	 = TIMELINE_POST_PIC.$timeLineImageName.$extension;
		$filePath 	 = TIMELINE_POST_PIC_UPL.$timeLineImageName.$extension;
		//$fileName     = BUSSOPP_POST_PIC.'bussoppimg'.$my_session_username.$upAt.$extension;
		//$filePath 	  = BUSSOPP_POST_PIC_UPL.'bussoppimg'.$my_session_username.$upAt.$extension;
	}
	
	
	$success = file_put_contents($filePath, $data);
	//$compressThisImg= 'http://myvoffice.me/entreprenity/'.$fileName;
	//$d = compress($compressThisImg, $fileName, 80);
	$result  = $success ? 1 : 0;
	
	
	if($result==1)
	{
		return $fileName;
	}
	else
	{
		return null;
	}

}

/*
function uploadTimelineImage($base64Img,$h,$w)
{
    $im = imagecreatefromstring($base64Img);
    if ($im !== false) 
    {
        $width = imagesx($im);
        $height = imagesy($im);
        $r = $width / $height; // ratio of image

        // calculating new size for maintain ratio of image
        if ($w/$h > $r) 
        {
            $newwidth = $h*$r; 
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagedestroy($im);

        $fileName  = 'img'.date('Ymd').'.jpeg';
        $filepath =  'folder/'.$fileName  ;
        imagejpeg($dst,$filepath);

        imagedestroy($dst);
        return $fileName;
    }
    else
    {
        return "";
    }
}
*/

//Function to update user avatar, company avatar, event poster
//May 06,2016
//June 16,2016: convert base64 to img, save path to db
//September 21,2016: Unique filenames for user profile pic, company profile pic, event poster
function uploadTheImage()
{
	$data= array();
	$data='not uploaded';
	
	$session_values=get_user_session();
	$my_session_id				= $session_values['id'];
	$my_session_firstname 	= $session_values['firstname'];
	$my_session_lastname		= $session_values['lastname'];
	$my_session_username		= $session_values['username'];
	
	$upAt=date('YmdHis');
	
	$postdata 		= file_get_contents("php://input");
	$request 		= json_decode($postdata);
	
	$uploadType 	= (int)$request->uploadType;
	$uploadImg 		= $request->uploadImg;
	
	// $uploadType values
	// 1- member profile pic
   // 2- company profile pic
	// 3- events poster
	// 4- client profile cover photo
	// 5- client company profile photo
	
	if (strpos($uploadImg, 'data:image/png;base64') !== false) 
	{
   	$img = str_replace('data:image/png;base64,', '', $uploadImg);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);	
		//$extension= PNG;
	}
	
	if (strpos($uploadImg, 'data:image/gif;base64') !== false) 
	{
   	$img = str_replace('data:image/gif;base64,', '', $uploadImg);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);	
		$extension= GIF; 
	}
	
	if (strpos($uploadImg, 'data:image/jpeg;base64') !== false) 
	{
   	$img = str_replace('data:image/jpeg;base64,', '', $uploadImg);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);	
		$extension= JPEG; 
	}	
		
	$extension= PNG;
	
   // 1- member profile pic
	if($uploadType==1)
	{
		$userImg=getUserProfilePicFromUserID($my_session_id);
		if($userImg!='')
		{
			deleteTimelinePostImageFromServer($userImg);
			removeUserProfilePicPathFromDB($my_session_id);
		}
		$userFileName=uniqueUserProfilePicName();
		$fileName 	 = PROFILE_PIC.$userFileName.$extension;
		$filePath 	 = PROFILE_PIC_UPL.$userFileName.$extension;
		//$fileName 	 = PROFILE_PIC.$my_session_username.$upAt.$extension;
		//$filePath 	 = PROFILE_PIC_UPL.$my_session_username.$upAt.$extension;		
	}
	
   // 2- company profile pic
	if($uploadType==2)
	{
		$userImg=getCompanyProfilePicFromUserID($my_session_id);
		if($userImg!='')
		{
			deleteTimelinePostImageFromServer($userImg);
			removeCompanyProfilePicPathFromDB($my_session_id);
		}
		$companyProfilePicName=uniqueCompanyProfilePicName();
		$fileName     = COMPANY_PIC.$companyProfilePicName.$extension;
		$filePath 	  = COMPANY_PIC_UPL.$companyProfilePicName.$extension;
	}
	
   // 3- events poster
	if($uploadType==3)
	{
		$eventPosterName=uniqueEventPostersName();
		$fileName 	 = EVENT_POSTER.$eventPosterName.$extension;
		$filePath 	 = EVENT_POSTER_UPL.$eventPosterName.$extension;
	}
	
		
	$success = file_put_contents($filePath, $data);
	$result  = $success ? 1 : 0;	
	
	//client profile pic
	if($uploadType==1)
	{
		$qry1="UPDATE client_profile SET avatar='".$fileName."' WHERE clientid=".$my_session_id." ";
		if(setData($qry1))
		{
			$data='success';
		}
	}
	
	//client company profile pic
	if($uploadType==2)
	{
		$companyID	=	getCompanyIDFromCompUserRelation($my_session_id);
		$qry2="UPDATE company_profiles SET avatar='".$fileName."' WHERE id=".$companyID." ";
		if(setData($qry2))
		{
			$data='success';
		}
	}
	
	//event poster
	if($uploadType==3)
	{			
		$qry4="SELECT eventTag FROM entrp_events_users_tags WHERE userID=".$my_session_id."";
		$res4=getData($qry4);
	   $count_res4=mysqli_num_rows($res4);
	   if($count_res4>0)
	   {
	   	while($row4=mysqli_fetch_array($res4))
	      {
	      	$eventTag				=	$row4['eventTag'];
	      }
	      
	      $qry3="UPDATE entrp_events SET poster='".$fileName."' WHERE eventTagId='".$eventTag."' ";
			if(setData($qry3))
			{
				$data='success';
			}
			else
			{
				$data='failed';
			}	
	   }
	   else
	   {
	   	$data='failed';
	   }		
	}
	
	return $data;
	
}


?>