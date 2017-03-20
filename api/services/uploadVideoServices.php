<?php


//function to upload video to server
//annie, march 15,2017

function uploadVideo()
{
  	  	 
  	  	 $filename 		=	$_FILES["file"]["name"];
	  	 $data['type'] = $_FILES["file"]["type"];
	  	 $ext = pathinfo($filename, PATHINFO_EXTENSION);
	  	 
	  	 $name = uniqueVideoName();
	  	 $data['name'] = $name.'.'.$ext;
	  	 $data['size'] = ($_FILES["file"]["size"] / 1024). " Kb";
	  	 $data['tempfile'] = $_FILES["file"]["tmp_name"];
	  	 if(isset($_POST['post'])){
	  		 $post 			= $_POST['post'];
	  	 
	  	 }
	  	 else{
	  	 	$post 			=	"";
	  	 
	  	 }
	  	 
	  	 if(move_uploaded_file($_FILES["file"]["tmp_name"], TIMELINE_POST_VIDEO_UPL.$data['name']))
	  	 {
	  	   $data['storedPath'] = TIMELINE_POST_VIDEO. $data['name'];
	  	   $data['response'] = saveVideoToDB($data['storedPath'],$post);
	  	 }
	  	 else
	  	 {
	  	 	$data['storedPath'] = 'unable to save the file';
	  	 }
	/*  	 
  $allowedExts = array("mp4");
  //$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
  if (($_FILES["file"]["type"] == "video/mp4") && ($_FILES["file"]["size"] < 20000))
  {
	  if ($_FILES["file"]["error"] > 0)
	  {
	    $data['return_code'] = $_FILES["file"]["error"];
	  }
	  else
	  {

	

	  }
  }
  else
  {
    $data['name'] = "Invalid file";
  } 
  */
  return $data;
 

}

//function to save video details to db
//annie, march 15,2017

function saveVideoToDB($video,$post)
{
	
	//insert into entrp_user_timeline(post_img,uploadType)values($data,$uploadType)
	
		$uploadType			= 	2;
		$session_values	=	get_user_session();
		$user					= 	$session_values['id'];
		date_default_timezone_set('UTC');
		$date					=	date('Y-m-d H:i:s');	
		$data['post_image'] = $video;
		$query	=	"INSERT  into entrp_user_timeline(content,post_img,uploadType,created_at,posted_by)values('".$post."','".$video."',".$uploadType.",'".$date."',".$user.")";
		if(setData($query))
		{
			$data ='success';
		}
		else
		{
			$data ='failed';
		}
		return $data;


}

//Function to generate unique timeline video name
//Annie,March 17, 2017
function uniqueVideoName()
{
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 10 digit token
	//SELECT * FROM `entrp_user_timeline` where post_img like '%timelineimgdominic.ronquillo20160816080631.jpeg%'
   $qry = "SELECT * FROM entrp_user_timeline where post_img like '%$token%'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueVideoName();
   } 
   else 
   {
      return $token;
   }	
}



?>