<?php

require_once ('../api/Query.php'); 
require_once '../api/constants.php';

$password		= validate_input($_POST['password']);
$client			= validate_input($_POST['tempc']);
$forgotToken	= validate_input($_POST['forgotToken']);

if($password!='' && $client!='') 
{

	$qry="SELECT firstname,lastname,email FROM entrp_login where clientid=".$client." AND status=1 ";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);   
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$firstname		=	$row['firstname'];
			$lastname		=	$row['lastname'];     
			$email			=	$row['email']; 
			$fullname		=  $firstname.' '.$lastname;     
		}
		
		ob_start();
		include('../api/email_templates/password_changed.php');
		$password_changed_template = ob_get_contents();			
		ob_end_clean();			
	
		$to=$email; 
		$strSubject="Password Changed";
		$message =  $password_changed_template;              
		$headers = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		$headers .= "From: ".MS_SENTFROM; 
	
		$qry2="UPDATE entrp_login SET password='".md5($password)."' where email='".$email."' AND clientid=".$client." ";
		if(setData($qry2))
		{
			$qry3="UPDATE entrp_forgot_password_tokens SET status=1 where forgotToken='".$forgotToken."' ";
		   if(setData($qry3))
		   {
		   	$mail_to_enduser=mail($to, $strSubject, $message, $headers);
		   	if($mail_to_enduser)
				{
					$data['success'] 		= true;
					header("Location: ../index.html"); 
				}
				else
				{
					$data['success'] 		= false;
					header("Location: ../index.html"); 
				}
		   }
		   else
		   {
		   	header("Location: ../index.html"); 
		   }
		}
		else
		{
			$data['success'] 		= false;
			header("Location: ../index.html"); 
		}						
	}
}
else
{
	header("Location: ../index.html"); 
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


?>