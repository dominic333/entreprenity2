<?php

require_once ('../api/Query.php'); 
require_once ('../api/constants.php'); 


$userEmail=validate_input($_REQUEST["email"]);

if($userEmail!='')
{
	//SELECT * FROM entrp_login where email='dominic@cliffsupport.com'
	$qry="SELECT * FROM entrp_login where email='".$userEmail."' LIMIT 1";
	//echo $qry;
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
	   {
	   	$clientid		= $row['clientid']; 
			$firstname 		= $row['firstname']; 
			$lastname 		= $row['lastname']; 
			$email			= $row['email']; 
			$vof_clientid 	= $row['vof_clientid']; 
			$voff_staff 	= $row['voff_staff']; 
		}
		
		$userPassw = substr(md5(uniqid(rand(), true)),0,10);  // creates a 10 digit token						
		$pass=md5($userPassw);
	
		$result = updateENpassword($clientid,$pass);
		if($voff_staff==1)
		{
			updateVOFFpassword($vof_clientid,$pass);
		}		
			
						
		if($result==1)
		{
			$fullName	=$firstname.' '.$lastname;
			$userEmail	=$email;
	 		$userPass	=$userPassw;	 		
	 		//echo $userEmail;
	 			
			ob_start();
			include('../api/email_templates/welcome_email.php');
			$welcomeTemplate = ob_get_contents();			
			ob_end_clean();			
			
			//$to='sean@flexiesolutions.com'; 
			//$to='sajeev@cliffsupport.com'; 
			$to=$userEmail; 
			$strSubject="Welcome to Entreprenity";
			$message =  $welcomeTemplate;              
			$headers = 'MIME-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
			$headers .= "From: ".MS_SENTFROM; 
			
			$mail_to_enduser=mail($to, $strSubject, $message, $headers);						
			if($mail_to_enduser)
			{
				markWelcomeMailSending($clientid);
				
				$mail_date= date('Y-m-d H:i:s');
	 			$log_this = 'Success: Password changed mail sent for '.$clientid.' email '.$to.' on '.$mail_date;
	 			$myfile = file_put_contents('passwordChangeLog.txt', $log_this.PHP_EOL , FILE_APPEND);	
	 			
				echo 'Mail Sent';
				echo'</br>';echo'</br>';
				echo '<a href="resentWelcomeEmail.php">Click here to go to form view</a>';
			}
			else
			{
				$mail_date= date('Y-m-d H:i:s');
	 			$log_this = 'Failed: Password changed mail not sent for '.$clientid.' email '.$to.' on '.$mail_date;
	 			$myfile = file_put_contents('passwordChangeLog.txt', $log_this.PHP_EOL , FILE_APPEND);	
	 			
				echo 'Unable to sent email';
				echo'</br>';echo'</br>';
				echo '<a href="resentWelcomeEmail.php">Click here to go to form view</a>';
			}
		}		
	}
	else
	{
		echo 'User does not exist';
		echo'</br>';echo'</br>';
		echo '<a href="resentWelcomeEmail.php">Click here to go to form view</a>';
	}
}
else
{
	echo 'Please provide an email id';
	echo'</br>';echo'</br>';
	echo '<a href="resentWelcomeEmail.php">Click here to go to form view</a>';
}




//Function to update entreprenity password
function updateENpassword($enID,$pass)
{
	$qry2="UPDATE entrp_login SET password='".$pass."' WHERE clientid=".$enID." ";
	if(setData($qry2))
	{
		return 1;	
	}
	else
	{
		return 0;
	}
}


//Function to update voffice password
function updateVOFFpassword($clientid,$pass)
{	
	$qry3="UPDATE client_info SET password='".$pass."' WHERE clientid=".$clientid." ";
	if(setData($qry3))
	{
		return 1;	
	}
	else
	{
		return 0;
	}
}

//Function to mark welcome mail sending
function markWelcomeMailSending($clientid)
{
	$qry2="UPDATE entrp_login SET welcomeEmail=1 WHERE clientid=".$clientid." ";
	setData($qry2);
}


//Function to validate inputs
function validate_input($input) 
{	
  $input = trim($input);
  //$input = stripslashes($input);
  $input = addslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}


/*----------------------------------------------------------------------------------*/


//Function to fetch company id from company name
function getCompanyIDFromCompanyName($coname)
{
	$qry="SELECT id FROM company_profiles where company_name='".$coname."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$counted		=	$row['id'];  					
		}
		return $counted;
	}
	else
	{
		return '';
	}
}



//Function to get entreprenity id from voffice id
function getEntreprenityID($clientid)
{
	$qry="SELECT clientid FROM entrp_login  
			WHERE vof_clientid=".$clientid." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$id		=	$row['clientid'];  					
		}
		return $id;
	}
	else
	{
		return null;
	} 
}


function getLocations()
{
	$locationString='';
	//SELECT id FROM location_info WHERE currency='PHP'
	$qry="SELECT id FROM location_info WHERE currency='PHP'";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$locations[]			=	$row['id']; //feeded
		}
		$locationString = implode(",", $locations);
	}
	return $locationString;

}



?>