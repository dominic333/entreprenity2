<?php
	session_start();
	require_once ('../api/Query.php'); 
	require_once ('../api/constants.php');
	require_once ('../api/userDefinedFunctions.php'); 
	require_once ('../api/services/userLoginServices.php'); 
	require_once ('../api/services/userIDBasedServices.php'); 
	require_once ('../api/externalLibraries/Mobile_Detect.php'); 
	
	$authToken	=	validate_input($_GET['auth']);	
	//$authToken	=	'z1eb853b889a1a1c0712de8721624550';	

	$detect = new Mobile_Detect();
	//$fullUrl='http://192.168.11.13/projects/entreprenity/others/voffRequestVerifier.php';
	$fullUrl=fullURL();
	$sub='others';
	$basePath = substr($fullUrl, 0, strpos($fullUrl, $sub));
	//Step 1
	//Fetch voffice login request
	//Service type 3 equals auto login from voffice after redirecting the user to EN.
	$qry="SELECT * FROM entrp_external_services_authentication   
			WHERE token='".$authToken."' AND servicetype=3 AND status=1 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id					=	$row['id'];  					
			$vofficeId			=	$row['cid'];  	//voffice client ID				
			$email				=	$row['email'];  //voffice email id					
			$hashedpass			=	$row['hashedpass'];  					
			$token				=	$row['token'];  					
			$authdatetime		=	$row['authdatetime'];  					
			$servicetype		=	$row['servicetype'];  					
			$status				=	$row['status'];  					
		}
		
		//Step 2
		//Validate user based on email and voffice clientid
		//Check if the user has a valid EN account
		$entreprenityId=getClientENId($email,$vofficeId);
		if($entreprenityId!=0)
		{
			//Step 3
			//Make user login to EN by setting login token
			
			//And redirect user to login controller
			//No need to set session variables here
			$newToken	= generate_login_token();
			
			set_client_session_token($newToken,$entreprenityId);
			
			$data= fetch_info_from_entrp_login($entreprenityId);
			
			if($data['success'] == 'false')
			{
				header("Location: $basePath");
			}
			else
			{
				session_start();
				$_SESSION['id'] 				= $data['clientid'];
				$_SESSION['firstname'] 		= $data['firstname'];
				$_SESSION['lastname'] 		= $data['lastname'];
				$_SESSION['login_token'] 	= $newToken;
				$_SESSION['username'] 	   = $data['username']; //added by arshad
				
				//Log the action
				$checkInDateTime= date('Y-m-d H:i:s');
				$user_browser=user_browser();
				$user_os=user_os();
				$user_ip=getRealIpAddr();
							
				if ($detect->isMobile())
				{
					// mobile content
					$device='Mobile';
				}   				
				else
				{
					// other content for desktops
					$device='Desktop';
				}
		   				
				$log_this = 'User with EN ID '.$entreprenityId.' and voffice ID '.$vofficeId.' redirected to EN on '.$checkInDateTime.' from '.$user_ip.' using '.$device.' through browser '.$user_browser.' ,OS: '.$user_os;
		 		$myfile = file_put_contents('usersRedirectedLogins.txt', $log_this.PHP_EOL , FILE_APPEND);	
				
				
				//Set local storage
				//$loginTokenJSON= json_encode($newToken);
				echo '<script>';
				echo'localStorage.setItem("entrp_token", JSON.stringify("'.$newToken.'"));';
				echo 'localStorage.isLogged = "true";';
				echo '</script>';
				echo("<script>window.location = '".$basePath."';</script>");
				//header("Location: $basePath"); 			
			}

			
		}
		else
		{
			header("Location: $basePath"); 
		}		
	}
	else
	{
		header("Location: $basePath"); 
	} 
   
   
   //Function to get client id fron client_info
   //September 30,2016 
   function getClientENId($email,$vofficeId)
   {
   	//SELECT clientid FROM entrp_login WHERE vof_clientid=10000 AND email='dominic@cliffsupport.com' AND voff_staff=1 AND status=1
		$qry2="SELECT clientid 
				 FROM entrp_login   
				 WHERE email='".$email."' AND vof_clientid=".$vofficeId." AND voff_staff=1 AND status=1 ";
		$res2=getData($qry2);
	   $count_res2=mysqli_num_rows($res2);
	   if($count_res2>0)
		{
			while($row2=mysqli_fetch_array($res2))
			{
				$clientid	=	$row2['clientid'];  
			}
			return $clientid;	
		} 
		else
		{
			return 0;
		}  
   }      

    

?>