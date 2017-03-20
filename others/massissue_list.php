<?php

require_once ('../api/Query.php'); 

//Step 1: Feed client ids from client_info to the script
//Step 2: Fetch data from client_info wrt clientid
//Step 3: Check whether users have already been added to EN
//Step 4: Add users to EN entrp_login
//Step 5: Create user profile in client_profile
//Step 6: Add Connect plan
//Step 7: Add entrp_credit_core
//Step 8: Send welcome email


//SELECT coname, COUNT( * ) c FROM client_info GROUP BY coname HAVING c > 1 ORDER BY c DESC

//Declarations
$clientIDArray=array(10000,10001,10002,10003,10004,10005,10006,10007,10008);
$data=array();


if(!empty($clientIDArray))
{
	
	
	//To add users based on their clientid, uncomment next two lines
	//$clientIDString = implode(",", $clientIDArray);
	//$qry="SELECT clientid FROM client_info WHERE clientid IN(".$clientIDString.") AND status=1";
	//SELECT clientid FROM client_info WHERE clientid IN(10000,10001,2) (sample query)

	//To add users based on location, uncomment the next two lines
	$locationString=getLocations();	
	$qry="SELECT clientid 
			FROM client_info 
			WHERE location IN(".$locationString.") 
			AND status=1 
			AND email!='' AND email!='n/a n/a' 
			AND coname!='' AND coname NOT IN ('Myvoffice DEMO account','Voffice Demo','na','none yettttttttttttttt') 
			GROUP BY email";
	echo $qry;
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$clientIDs[]			=	$row['clientid']; //feeded
		}
		
		//Select all voffice clients from entreprenity
		$qry2="SELECT vof_clientid FROM entrp_login";		
		$res2=getData($qry2);
   	$count_res2=mysqli_num_rows($res2);
   	if($count_res2>0)
   	{
   		while($row2=mysqli_fetch_array($res2))
      	{
      		$clientENVOF[]	=	$row2['vof_clientid']; //fetched
			}
   	}	
   	
   	if( !empty($clientIDs) && !empty($clientENVOF) )
   	{
   		$result=array_filter(array_diff($clientIDs,$clientENVOF)); //Step 3: Check whether users have already been added to EN
   			   		
   		foreach($result as $key)
			{
			 $addToEN[]= $key;
			}
			
			if(!empty($addToEN))
			{
				$addToENString = implode(",", $addToEN);
				//echo $addToENString;
				
				//Step 2 begins				
				$i=0;
				//equivalent to SELECT clientid, firstname,lastname,coname,address,postcode,city,state,country,email,website,location,dob FROM client_info
				$qry3="SELECT * FROM client_info WHERE clientid IN(".$addToENString.") AND status=1 ORDER BY coname ";
				echo $qry3;
				$res3=getData($qry3);
   			$count_res3=mysqli_num_rows($res3);
				if($count_res3>0)
				{
					while($row3=mysqli_fetch_array($res3))
		      	{
		      		$data[$i]["clientid"] 		= $row3['clientid']; 
		      		$data[$i]["firstname"] 		= $row3['firstname']; 
		      		$data[$i]["lastname"] 		= $row3['lastname']; 
		      		$data[$i]["coname"] 			= strip_tags($row3['coname']); 
		      		$data[$i]["address"] 		= $row3['address']; 
		      		$data[$i]["postcode"] 		= $row3['postcode']; 
		      		$data[$i]["city"] 			= $row3['city']; 
		      		$data[$i]["state"] 			= $row3['state']; 
		      		$data[$i]["country"] 		= $row3['country']; 
		      		$data[$i]["email"] 			= $row3['email']; 
		      		$data[$i]["website"] 		= $row3['website']; 
		      		$data[$i]["location"] 		= $row3['location']; 
		      		$data[$i]["dob"] 				= $row3['dob'];
		      		
		      		$i++; 
					}	
						
				}
				
				$repeatedEmailIds=repeatedEmailIds();
				$repeatedCompanies=repeatedCompanies();
				if(!empty($data))
				{
					echo '<table>';
					echo'
					<tr>
					   <td>#</td>
						<td>clientid</td>
						<td>Name</td>
						<td>coname</td>
						<td>email</td>
						<td>location</td>
					</tr>';
					for($i=0;$i<count($data);$i++)
					{
						if (in_array($data[$i]["email"], $repeatedEmailIds)) 
						{ 
							$color='red'; 
						} 
						else
						{
							$color='black';
						}
						
						if (in_array($data[$i]["coname"], $repeatedCompanies)) 
						{ 
							$compcolor='blue'; 
						} 
						else
						{
							$compcolor='black';
						}
						
						echo'
						<tr>
						   <td>'.$i.'</td>
							<td>'.$data[$i]["clientid"].'</td>
							<td>'.$data[$i]["firstname"].' '.$data[$i]["lastname"].'</td>
							<td><font color="'.$compcolor.'">'.$data[$i]["coname"].'</font></td>
							<td ><font color="'.$color.'">'.$data[$i]["email"].'</font></td>
							<td>'.$data[$i]["location"].'</td>
						</tr>';
					}
					echo '</table>';
					
					/*
					//Adding Users to Entreprenity starts
					for($i=0;$i<count($data);$i++)
					//for($i=0;$i<10;$i++)
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
		      		$coname		=	strip_tags($data[$i]["coname"]);
		      		$address		=	strip_tags($data[$i]["address"]);
		      		$postcode	=	$data[$i]["postcode"];
		      		$city			=	$data[$i]["city"];
		      		$state		=	$data[$i]["state"];
		      		$country		=	$data[$i]["country"];
		      		$email		=	$data[$i]["email"];
		      		$website		=	$data[$i]["website"];
		      		$location	=	$data[$i]["location"];
		      		$dob			=	$data[$i]["dob"];
		      		
		      		addENLogin($clientid,$firstname,$lastname,$email);		//add user to entrp_login
		      		addENClientProfile($clientid,$location,$coname,$dob); //add client profile
		      		
		      		$companyUserName=preg_replace('/[^a-zA-Z0-9-]/', '', $coname);
		      		$companyExist= checkCompanyAddedOrNot($coname);		      		
		      		//Not added
		      		if($companyExist==0)
		      		{
		      			addENCompanyProfile($clientid,$companyUserName,$coname); //add company profile
		      			
							$designation=1;
							$compID= getCompanyIDFromCompanyName($coname);
		      			assignENUserstoCompany($compID,$designation,$clientid); //assign company admin to company
		      		}
		      		else
		      		{
							//get company id from company name
							//assign company member to company		
							$designation=2; 
							$compID= getCompanyIDFromCompanyName($coname);  
							assignENUserstoCompany($compID,$designation,$clientid); //assign company members to company   		
		      		}
		      		
		      		addENUserPreferences($clientid); //add user notification preference
		      		
						$userPassw = substr(md5(uniqid(rand(), true)),0,10);  // creates a 10 digit token						
						$pass=md5($userPassw);
						
						$enID= getEntreprenityID($clientid);
						if($enID!='')
						{
							$result = updateENpassword($enID,$pass,$clientid);		
						}
										
						if($result==1)
						{
							$fullName	=$firstname.' '.$lastname;
							$userEmail	=$email;
 							$userPass	=$userPassw;
							ob_start();
							include('../api/email_templates/welcome_email.php');
							$welcomeTemplate = ob_get_contents();			
							ob_end_clean();			
							
							$to='dominic@cliffsupport.com'; 
							//$to=$userEmail; 
							$strSubject="Welcome to Entreprenity";
							$message =  $welcomeTemplate;              
							$headers = 'MIME-Version: 1.0'."\r\n";
							$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
							$headers .= "From: eprty@test.com"; 
							
							$mail_to_enduser=mail($to, $strSubject, $message, $headers);						
							if($mail_to_enduser)
							{
								markWelcomeMailSending($clientid);
							}
						}
		      		
					}
					*/			
					
					//Adding users to Entreprenity ends 					
					
					
					
										
					
					
				}
				else
				{
					echo 'No users to add 2';
				}
				
			}
			else
			{
				echo 'all users added';			
			}
   	}
   	//style='color:red'
   	  					 	
   }
   
}
else
{
	echo 'No users to add';
}


//Function to mark welcome mail sending
function markWelcomeMailSending($clientid)
{
	$enID= getEntreprenityID($clientid);
	$qry2="UPDATE entrp_login SET welcomeEmail=1 WHERE clientid=".$enID." ";
	setData($qry2);
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