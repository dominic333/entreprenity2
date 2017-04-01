<?php 

if( !session_id() )
{
    session_start();
}
require_once ('../api/Query.php'); 
require_once ('../api/userDefinedFunctions.php'); 
require_once ('../api/externalLibraries/Mobile_Detect.php'); 
require_once ('centerFunctions.php'); 


$fullUrl=fullURL();
$sub='center';
$basePath = substr($fullUrl, 0, strpos($fullUrl, $sub));

$detect = new Mobile_Detect();

if(isset($_POST['send']))
{

	$arr= array();

	$userQRCode=validate_input($_POST['credential']);

	$qrCodeValidity=validateUserQRCode($userQRCode);
	
	if($qrCodeValidity==1)
	{
		$coworkValidity = validateCoworkHour($userQRCode);		
		if($coworkValidity == 1)
		{
			$checkInDateTime= date('Y-m-d H:i:s');
			$data= fetchUserInfoUsingQRCode($userQRCode);
			
			$_SESSION['logged_in'] 	= true;
			$arr['username'] 			= $data['username'];
			$arr['firstname'] 		= $data['firstname'];
			$arr['lastname'] 			= $data['lastname'];
			$arr['clientid'] 			= $data['clientid'];
			$arr['vofClientId'] 		= $data['vofClientId'];
			
			if (strpos($data['avatar'], 'facebook') !== false) 
			{
			    $arr['avatar'] 			= $data['avatar'];
			}
			else
			{
				$arr['avatar'] 			= $basePath.$data['avatar'];
			}
			
			$arr['checkInDateTime'] = $checkInDateTime;
			$arr['company'] 			= getCompanyName($data['clientid']);
			
			
			$locId = $_SESSION['locId'];
	//		$locId = 3;
		//	logUserIntoThisCenter($data['clientid'],$data['vofClientId'],$locId);
	
			
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
	   				
			$log_this = 'EN client '.$data['clientid'].' having vOffice ID '.$data['vofClientId'].' checked in location '.$locId.' on UTC '.$checkInDateTime.' from '.$user_ip.' using '.$device.' through browser '.$user_browser.' ,OS: '.$user_os;
	 		$myfile = file_put_contents('centerCheckIN.txt', $log_this.PHP_EOL , FILE_APPEND);	
			
			$arr['success'] 			= true;
		}
		else 
		{
			$arr['success'] = false;
		}		
	}
	else
	{
		$arr['success'] = false;
	}
	/*
	if($_POST['credential'] == 'momo')
	{
		$_SESSION['logged_in'] = true;
		$arr['success'] = true;
	} 
	else 
	{
		$arr['success'] = false;
	}
	*/

	echo json_encode($arr);
}




?>