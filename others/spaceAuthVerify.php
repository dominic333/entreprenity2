<?php
	session_start();
	require_once ('../api/Query.php'); 
	require_once '../api/constants.php';
	
	$authToken	=	validate_input($_GET['auth']);	

	$qry="SELECT * FROM entrp_external_services_authentication   
			WHERE token='".$authToken."' AND servicetype=2 AND status=1 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id					=	$row['id'];  					
			$cid					=	$row['cid'];  					
			$email				=	$row['email'];  					
			$hashedpass			=	$row['hashedpass'];  					
			$token				=	$row['token'];  					
			$authdatetime		=	$row['authdatetime'];  					
			$servicetype		=	$row['servicetype'];  					
			$status				=	$row['status'];  					
		}
		
		$clientid=getClientIdfromClientInfo($email,$hashedpass);
		if($clientid!="")
		{
			$newToken			= md5($email.date('Y-m-d H:i:s'));
			$respToken= updateClientToken($clientid, $newToken);
			
			if($respToken=='updated')
			{
				$_SESSION["cid"]		=	$clientid;
				$_SESSION['token']	=	$newToken;
				
				setcookie('cid', "", time() + 60 * 60 * 24 * 30, '/spaces/');
				setcookie('token', "", time() + 60 * 60 * 24 * 30, '/spaces/');
				
				header("Location: ../../spaces/index.php"); 
			}
			else
			{
				header("Location: ../../spaces/login.php"); 
			}
		}
		else
		{
			header("Location: ../../spaces/login.php"); 
		}		
	}
	else
	{
		header("Location: ../../spaces/login.php"); 
	} 


	 
   //setcookie('a', $_GET['auth']);
   // 
   
	//Function to update client token in client info table
	//July 01,2016
	function updateClientToken($clientid, $newToken)
	{
		$qry="UPDATE client_info SET token='".$newToken."' WHERE  clientid=".$clientid."";
		if(setData($qry))
		{
		  return 'updated';
		}
		else
		{
			return 'notupdated';
		}
	}   
   
   
   //Function to get client id fron client_info
   //July 01,2016 
   function getClientIdfromClientInfo($email,$hashedpass)
   {
		$qry2="SELECT clientid FROM client_info   
				 WHERE email='".$email."' AND password='".$hashedpass."' ";
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
			return null;
		}  
   }      
    
	//Function to validate inputs
	//July 01,2016 
	function validate_input($input) 
	{	
	  $input = trim($input);
	  //$input = stripslashes($input);
	  $input = addslashes($input);
	  $input = htmlspecialchars($input);
	  return $input;
	}
	
	//Function to enable CORS
	//July 01,2016 
	function enable_cors() 
	{
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, POST');
		header("Access-Control-Allow-Headers: X-Requested-With");	
		date_default_timezone_set('asia/singapore');
	}

?>