<?php

require_once ('../api/Query.php'); 
require_once ('../api/constants.php'); 

$qry="SELECT * from entrp_login where welcomeEmail=0 AND user_type=2";
$res=getData($qry);
$count_res=mysqli_num_rows($res);
if($count_res>0)
{
	$i=0;
	while($row=mysqli_fetch_array($res))
   {
   	$data[$i]["clientid"] 		= $row['clientid']; 
		$data[$i]["firstname"] 		= $row['firstname']; 
		$data[$i]["lastname"] 		= $row['lastname']; 
		$data[$i]["email"] 			= $row['email']; 
		$data[$i]["vof_clientid"] 	= $row['vof_clientid']; 
		
		$i++; 
	}
	
	if(!empty($data))
	{
		echo '<table>';
		echo'
		<tr>
		   <td>#</td>
			<td>clientid</td>
			<td>Name</td>
			<td>email</td>
			<td>voffice ID</td>
		</tr>';
		for($i=0;$i<count($data);$i++)
		{
			
			echo'
			<tr>
			   <td>'.$i.'</td>
				<td>'.$data[$i]["clientid"].'</td>
				<td>'.$data[$i]["firstname"].' '.$data[$i]["lastname"].'</td>
				<td >'.$data[$i]["email"].'</td>
				<td>'.$data[$i]["vof_clientid"].'</td>
			</tr>';
		}
		echo '</table>';	
		
		
		for($i=0;$i<count($data);$i++)
		//for($i=0;$i<3;$i++)
		{
			//add user to entrp_login
			//add client profile
			//add company profile
			//assign members to company entrp_comp_members
			//add user notification preference
			//create password and update table
			//send welcome mail
			//mark welcome mail send
		
			$clientid	=	$data[$i]["clientid"]; 
   		$firstname	=	$data[$i]["firstname"];
   		$lastname	=	$data[$i]["lastname"];
   		$email		=	$data[$i]["email"];
   		$vof_clientid	=	$data[$i]["vof_clientid"];
   		
			$userPassw = substr(md5(uniqid(rand(), true)),0,10);  // creates a 10 digit token						
			$pass=md5($userPassw);

			$result = updateENpassword($clientid,$pass,$vof_clientid);		
							
			if($result==1)
			{
				$fullName	=$firstname.' '.$lastname;
				$userEmail	=$email;
 				$userPass	=$userPassw;
 				
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
	    			$log_this = 'Success: Welcome mail from entreprenity to '.$clientid.' email '.$to.' on '.$mail_date;
	 				$myfile = file_put_contents('welcomeMailLog.txt', $log_this.PHP_EOL , FILE_APPEND);	
				}
				else
				{
					$mail_date= date('Y-m-d H:i:s');
	    			$log_this = 'Failed: Welcome mail from entreprenity to '.$clientid.' email '.$to.' on '.$mail_date;
	 				$myfile = file_put_contents('welcomeMailLog.txt', $log_this.PHP_EOL , FILE_APPEND);	
				}
			}
   		sleep(5);
		}		
		
	}
	
}


//Function to update entreprenity and voffice password
function updateENpassword($enID,$pass,$clientid)
{
	$qry2="UPDATE entrp_login SET password='".$pass."' WHERE clientid=".$enID." ";
	
	$qry3="UPDATE client_info SET password='".$pass."' WHERE clientid=".$clientid." ";
	
	if(setData($qry2) && setData($qry3))
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


/*----------------------------------------------------------------------------------*/

//Add user notification preferences
function addENUserPreferences($clientid)
{
	//$data_q = "INSERT INTO entrp_user_notification_preferences (clientid) VALUES ('$id')";
	$enID= getEntreprenityID($clientid);
	
	$qry="INSERT INTO entrp_user_notification_preferences (clientid) 
			VALUES(".$enID.") ";
	setData($qry);
}

//Assign entreprenity users to companies
function assignENUserstoCompany($compID,$designation,$clientid)
{
	//$data_q = "INSERT INTO entrp_company_members (companyid, clientid,designation) VALUES ('$cid', '$id', '$owner')";
	$enID= getEntreprenityID($clientid);
	
	$qry="INSERT INTO entrp_company_members (companyid, clientid,designation) 
			VALUES(".$compID.",".$enID.",".$designation.") ";
	setData($qry);

}

//Create entreprenity company profile
function addENCompanyProfile($clientid,$companyUserName,$coname)
{
	//$data_q = "INSERT INTO company_profiles (clientid, company_username, company_name) 
	// VALUES ('$clientid', '$company_username', '$company_name')";
	$enID= getEntreprenityID($clientid);
	$qry="INSERT INTO company_profiles (clientid, company_username, company_name) 
			VALUES(".$enID.",'".$companyUserName."','".$coname."') ";
	setData($qry);
}

//create entreprenity user profile
function addENClientProfile($clientid,$location,$coname,$dob)
{
	$joinDate=date('Y-m-d H:i:s');
	//$data_q = "INSERT INTO client_profile (clientid, client_location, company_name, date_of_birth, mobile,join_date) 
	//VALUES ('$clientid', '$client_location', '$company_name', '$date_of_birth', '$mobile','$joinDate')";
	$enID= getEntreprenityID($clientid);
	
	$qry="INSERT INTO client_profile (clientid, client_location, company_name, date_of_birth,join_date) 
			VALUES(".$enID.",".$location.",'".$coname."','".$dob."','".$joinDate."') ";
	setData($qry);

}

//add user to entrp_login
function addENLogin($clientid,$firstname,$lastname,$email)
{
	//$data_q = "INSERT INTO entrp_login (username, email, password, firstname, lastname, voff_staff, vof_clientid, status, user_type) 
	//	VALUES ('$username', '$email', '$password', '$firstname', '$lastname', '$voff_staff', '$vof_clientid', '$status', '$user_type')";
	
	$time=date("Y-m-d H:i:s");
	$voffStaff=1;
	$status=1;
	$userType=2;	
	$firstnames=preg_replace('/[^a-zA-Z0-9-]/', '', $firstname);
	$username=strtolower($firstnames).$clientid;
			
	$pass=md5($username);
	
	$qry="INSERT INTO entrp_login (username,email,password,firstname,lastname,voff_staff, vof_clientid, status, user_type) 
			VALUES('".$username."','".$email."','".$pass."','".$firstname."','".$lastname."',".$voffStaff.",".$clientid.",".$status.",".$userType.") ";
	setData($qry);
	
}

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

//Function to check company already added to En or not
function checkCompanyAddedOrNot($coname)
{
	$qry="SELECT count(id) as counted FROM company_profiles where company_name='".$coname."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$counted		=	$row['counted'];  					
		}
		return $counted;
	}
	else
	{
		return 0;
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

function repeatedCompanies()
{
	$locationString=getLocations();
	$qry="SELECT coname, COUNT( * ) c FROM client_info WHERE status=1 AND location IN(".$locationString.") AND coname!='' GROUP BY coname HAVING c > 1 ORDER BY c DESC ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$repeatedCompanies[]			=	$row['coname']; //feeded
		}
	}
	return $repeatedCompanies;

}


function repeatedEmailIds()
{
	//SELECT email, COUNT( * ) c FROM client_info WHERE status=1 AND location IN(14,33,21) AND email!='' GROUP BY email HAVING c > 1 ORDER BY c DESC
	$locationString=getLocations();
	$qry="SELECT email, COUNT( * ) c FROM client_info WHERE status=1 AND location IN(".$locationString.") AND email!='' GROUP BY email HAVING c > 1 ORDER BY c DESC ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$repeatedEmailIds[]			=	$row['email']; //feeded
		}
	}
	return $repeatedEmailIds;

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


function duplicatedusersSameCompany()
{
	//SELECT clientid,CONCAT(firstname,' ',lastname) AS name,coname, COUNT( * ) c FROM client_info WHERE status=1 AND location IN(14,21,26,33) AND coname!='' GROUP BY name HAVING c > 1 ORDER BY name DESC

}


?>