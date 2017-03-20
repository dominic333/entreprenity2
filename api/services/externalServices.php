<?php


//Function to convert currencies 
//February 04,2017
function converCurrency($from,$to,$amount)
{
	$url = "http://www.google.com/finance/converter?a=$amount&from=$from&to=$to"; 
	$request = curl_init(); 
	$timeOut = 0; 
	curl_setopt ($request, CURLOPT_URL, $url); 
	curl_setopt ($request, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($request, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)"); 
	curl_setopt ($request, CURLOPT_CONNECTTIMEOUT, $timeOut); 
	$response = curl_exec($request); 
	curl_close($request); 
	return $response;
} 

//Function to purchase an item from honesty bar
//January 17,2017
function purchaseThisItem()
{
	$data = array();	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$userid=$my_session_id;
	if($userid)
	{
		$tag		=	validate_input($_POST['tag']);
		$id		=	validate_input($_POST['slno']);
		
		//$checkItem= checkItemTagValidity($tag);
		if($tag)
		{
		  $geolocation	=	'';
		  
		  $itemPriceId		=	getItemPriceIDFromItemtag($tag);
		  $dateOfPurchase = date('Y-m-d H:i:s');
		  
		  if($itemPriceId!=0)
		  {
		  	   $voffClientID = getUserVOfffromENId($userid);
		  	   if($voffClientID>0)
		  	   {
		  	   	$itemDetails 	= fetchItemDetailsFromItemTag($tag);
		  	   	$itemPrice 	 	= $itemDetails["price"];
		  	   	$itemCurrency 	= $itemDetails["currency"];
		  	   	
		  	   	$userCreditsAvailable = fetchAvailableUserCredits($voffClientID);
		  	   	$userCurrency  = fetchUserCurrency($userid,$voffClientID);
		  	   	
		  	   	if($userCreditsAvailable >0)
		  	   	{
		  	   		$orderTag = honestyBarOrderTag();
		  	   		if($userCurrency === $itemCurrency)
			  	   	{
			  	   		if($userCreditsAvailable>=$itemPrice)
			  	   		{
			  	   			$remainingCredits = $userCreditsAvailable-$itemPrice;
			  	   			$data['remainingCredits'] 		 = $remainingCredits;
			  	   			$data['price'] 		 = $itemPrice;
			  	   			$data['response'] = processThePurchase($itemPriceId,$id,$dateOfPurchase,$geolocation,$userid,$voffClientID,$remainingCredits,$orderTag,$userCurrency,$itemPrice);
			  	   		}
			  	   		else 
			  	   		{
			  	   			$data['response'] ='insufficientcredits';
			  	   		}	
			  	   	}
			  	   	else
			  	   	{
			  	   		 $from_currency  = $itemCurrency;
						    $to_currency    = $userCurrency;
						    $amount         = $itemPrice;
						    $results = converCurrency($from_currency,$to_currency,$amount);
						    $regularExpression     = '#\<span class=bld\>(.+?)\<\/span\>#s';
						    preg_match($regularExpression, $results, $finalData);
						    $res =strip_tags($finalData[0]);
						    $val =explode(" ",$res);
						    $convertedItemPrice = $val[0];
						    
						    if(is_numeric($convertedItemPrice))
							 {
							 	if($userCreditsAvailable>=$convertedItemPrice)
							 	{
							 		$remainingCredits = $userCreditsAvailable-$convertedItemPrice;
							 		$data['remainingCredits'] 		 = $remainingCredits;
							 		$data['price'] 		 = $convertedItemPrice;
			  	   				$data['response'] = processThePurchase($itemPriceId,$id,$dateOfPurchase,$geolocation,$userid,$voffClientID,$remainingCredits,$orderTag,$userCurrency,$convertedItemPrice);
							 	}
							 	else 
				  	   		{
				  	   			$data['response'] ='insufficientcredits';
				  	   		} 
							 }
							 else
							 {
							 	$data['response'] ='unabletoprocess';
							 }
			  	   	}
		  	   	}
		  	   	else
		  	   	{
		  	   		$data['response'] ='insufficientcredits';
		  	   	}
		  	   }
		  	   else
		  	   {
		  	   	$data['response'] ='failed';
		  	   }	  
		  }
		  else
		  {
		  	  $data['response'] ='invalid';
		  }
		}
		else
		{
		  $data['response'] ='invalid';
		}
		
		if(isset($orderTag))
		{
			$data['purchase'] 	 = $orderTag;
		}
		else
		{
			$data['purchase'] 	 = '';
		}
		
		if(isset($id))
		{
			$data['redirect'] 	 = $id;
		}
		else
		{
			$data['redirect'] 	 = '';
		}
		
		if(isset($userCurrency))
		{
			$data['userCurrency'] = $userCurrency;
		}
		else
		{
			$data['userCurrency'] = '';
		}
		
		if(isset($itemPrice))
		{
			$data['itemPrice'] 	 = $itemPrice;
		}
		else
		{
			$data['itemPrice'] 	 = '';
		}
		
		if(isset($itemCurrency))
		{
			$data['itemCurrency'] = $itemCurrency;
		}
		else
		{
			$data['itemCurrency'] = '';
		}
		
		
		
		
		
	}
	return $data;
}

//Function to process a purchase and update credits
//February 04,2017
function processThePurchase($itemPriceId,$id,$dateOfPurchase,$geolocation,$userid,$voffClientID,$remainingCredits,$orderTag,$userCurrency,$userPaid)
{
   $qry="INSERT INTO entrp_products_purchases(itemPriceId,itemId,dateOfPurchase,geolocation,entrpId,voffId,orderTag) 
	VALUES(".$itemPriceId.",".$id.",'".$dateOfPurchase."','".$geolocation."',".$userid.",".$voffClientID.",'".$orderTag."')";
	
	$qry1="UPDATE entrp_credit_core SET perm_core_credit=".$remainingCredits." WHERE client_id=".$voffClientID."";
	
	if(setData($qry) && setData($qry1))
	{
		$data='success';	
		send_order_purchase_mail($orderTag,$remainingCredits,$userCurrency,$userPaid);
		if($id==2)
		{
			send_order_detailsto_barista($orderTag);
		}
	}
	else
	{
		$data='failed';
	}	
	return $data;
}

//Function to fetch order info based on order tag
//Feb 13,2017
function fetchPurchaseInfoBasedonOrderTAG($orderTag)
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	$data= array();	
	/*
	SELECT entrp_products_purchases.orderTag,entrp_products_purchases.dateOfPurchase,entrp_products.name AS productName, entrp_products.description,entrp_products.image, 
	entrp_products_pricing.price,entrp_products_pricing.currency,entrp_products_pricing.name AS subProductName, entrp_products_pricing.image AS subProductImage,
	location_info.location_desc,entrp_login.firstname,entrp_login.lastname,entrp_login.email
	FROM entrp_products_purchases
	LEFT JOIN entrp_products ON entrp_products.id=entrp_products_purchases.itemId
	LEFT JOIN entrp_products_pricing ON entrp_products_purchases.itemPriceId=entrp_products_pricing.id
	LEFT JOIN location_info ON location_info.id=entrp_products_pricing.location
	LEFT JOIN entrp_login ON entrp_login.clientid=entrp_products_purchases.entrpId
	WHERE entrp_products_purchases.orderTag='2bde40'
	*/
	$qry="SELECT entrp_products_purchases.orderTag,entrp_products_purchases.dateOfPurchase,entrp_products.name AS productName, entrp_products.description,entrp_products.image, 
			entrp_products_pricing.price,entrp_products_pricing.currency,entrp_products_pricing.name AS subProductName, entrp_products_pricing.image AS subProductImage,entrp_products_pricing.location,
			location_info.location_desc,entrp_login.firstname,entrp_login.lastname,entrp_login.email
			FROM entrp_products_purchases
			LEFT JOIN entrp_products ON entrp_products.id=entrp_products_purchases.itemId
			LEFT JOIN entrp_products_pricing ON entrp_products_purchases.itemPriceId=entrp_products_pricing.id
			LEFT JOIN location_info ON location_info.id=entrp_products_pricing.location
			LEFT JOIN entrp_login ON entrp_login.clientid=entrp_products_purchases.entrpId
			WHERE entrp_products_purchases.orderTag='".$orderTag."'
			";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
   	{
   		$data['orderTag']				=	$row['orderTag'];
   		$data['dateOfPurchase']		=	$row['dateOfPurchase'];
   		$data['productName']			=	$row['productName'];
   		$data['description']			=	$row['description'];
   		$data['image']					=	$row['image'];
   		$data['price']					=	$row['price'];
   		$data['currency']				=	$row['currency'];
   		$data['subProductName']		=	$row['subProductName'];
   		$data['subProductImage']	=	$row['subProductImage'];
   		$data['location']				=	$row['location_desc'];
   		$data['locationId']			=	$row['location'];
   		$data['username']				=	$row['firstname'].' '.$row['lastname'];
   		$data['email']					=	$row['email'];

   	}
		
	}
	return $data;

}

//Function to generate order tag
//Feb 13,2017
function honestyBarOrderTag()
{
	$token = substr(md5(uniqid(rand(), true)),0,6);  // creates a 32 digit token
	//SELECT * FROM entrp_login where qrCode='70f804625753d84827ef993329c3b1b8'
   $qry = "SELECT * FROM entrp_products_purchases WHERE orderTag='".$token."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      honestyBarOrderTag();
   } 
   else 
   {
      return $token;
   }	
}

//Function to fetch item details for processing using itemTag
//February 04,2017
function fetchItemDetailsFromItemTag($tag)
{
	//SELECT entrp_products.id,entrp_products.name,entrp_products.shortcode,entrp_products.description,entrp_products.image,entrp_products_pricing.itemTag,entrp_products_pricing.price,entrp_products_pricing.currency,location_info.location_desc
	//FROM entrp_products
	//LEFT JOIN entrp_products_pricing ON entrp_products_pricing.itemId=entrp_products.id
	//LEFT JOIN location_info ON location_info.id=entrp_products_pricing.location
	//WHERE entrp_products_pricing.itemTag='COKE_CCE8_ABC123' AND entrp_products_pricing.status=1 AND entrp_products.status=1
	
	$data = array();	
	$qry="SELECT entrp_products.id,entrp_products.name,entrp_products.shortcode,entrp_products.description,entrp_products.image,
					 entrp_products_pricing.itemTag,entrp_products_pricing.price,entrp_products_pricing.currency,
					 location_info.location_desc
			FROM entrp_products
			LEFT JOIN entrp_products_pricing ON entrp_products_pricing.itemId=entrp_products.id
			LEFT JOIN location_info ON location_info.id=entrp_products_pricing.location
			WHERE entrp_products_pricing.itemTag='".$tag."' AND entrp_products_pricing.status=1 AND entrp_products.status=1
   ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
   	{   				
   		$data['id']				=	$row['id'];
   		$data['itemTag']		=	$row['itemTag'];
   		$data['name']			=	$row['name'];
   		$data['image']			=	$row['image'];
   		$data['price']			=	$row['price'];
   		$data['location']		=	$row['location_desc'];
   		$data['description']	=	htmlspecialchars_decode($row['description'],ENT_QUOTES);
   		$data['currency']		=	$row['currency'];
   	}		   	   
   }
	return $data;
}

//Function to fetch and show a selected item's info
//January 17,2017
//March 7, 2017: Itemcode fetched instead of item tag 
function getItemDetails()
{
	//SELECT entrp_products.id,entrp_products.name,entrp_products.shortcode,entrp_products.description,entrp_products.image,entrp_products_pricing.itemTag,entrp_products_pricing.price,entrp_products_pricing.currency,location_info.location_desc
	//FROM entrp_products
	//LEFT JOIN entrp_products_pricing ON entrp_products_pricing.itemId=entrp_products.id
	//LEFT JOIN location_info ON location_info.id=entrp_products_pricing.location
	//WHERE entrp_products_pricing.itemTag='COKE_CCE8_ABC123' AND entrp_products_pricing.status=1 AND entrp_products.status=1
	
	$data = array();	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$userid=$my_session_id;
	if($userid)
	{
		$item		=	validate_input($_POST['item']);
		$qry="SELECT entrp_products.id,entrp_products.name,entrp_products.shortcode,entrp_products.description,entrp_products.image,
						 entrp_products_pricing.itemTag,entrp_products_pricing.price,entrp_products_pricing.currency,
						 entrp_products_pricing.name AS subProductName, entrp_products_pricing.image AS subProductImage,
						 location_info.location_desc
				FROM entrp_products
				LEFT JOIN entrp_products_pricing ON entrp_products_pricing.itemId=entrp_products.id
				LEFT JOIN location_info ON location_info.id=entrp_products_pricing.location
				WHERE entrp_products_pricing.itemCode='".$item."' AND entrp_products_pricing.status=1 AND entrp_products.status=1
      ";
		$res=getData($qry);
	   $count_res=mysqli_num_rows($res);
	   if($count_res>0)
	   {
	   	while($row=mysqli_fetch_array($res))
	   	{   				
	   		$data['id']				=	$row['id'];
	   		$data['itemTag']		=	$row['itemTag'];
	   		$data['name']			=	$row['name'].'-'.$row['subProductName'];
				if($row['subProductImage']=='')
				{
					$data['image']			=	$row['image'];
				}
				else
				{
					$data['image']			=	$row['subProductImage'];
				}
	   		
	   		$data['price']			=	$row['price'];
	   		$data['location']		=	$row['location_desc'];
	   		$data['description']	=	htmlspecialchars_decode($row['description'],ENT_QUOTES);
	   		$data['currency']		=	$row['currency'];
	   	}		   	   
	   }
	}
	return $data;
}

//Function to view info of an item from Honesty bar
//January 17,2017
function viewThisItem()
{
 	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$item		=	validate_input($_POST['item']);
		if($item!='')
		{
			$checkItem= checkItemTagValidity($item);
			
			if($checkItem)
			{
			  $data='valid';
			}
			else
			{
			  $data='';
			}		
		}
		else
		{
			$data='';
		}
	}		
	return $data;
}

//Function to check item tag valid or not
//Modified March 7, 2017: Item tag validation changed to item code validation
function checkItemTagValidity($item)
{
 	$qry="SELECT entrp_products.id,entrp_products.name,entrp_products_pricing.itemTag
			FROM entrp_products
			LEFT JOIN entrp_products_pricing ON entrp_products_pricing.itemId=entrp_products.id
			WHERE entrp_products_pricing.itemCode='".$item."' AND entrp_products_pricing.status=1 AND entrp_products.status=1 ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}

//Function to save facebook id and authorize facebook login
//August 24,2016
function saveFacebookIdandAuthorize()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$fid		=	validate_input($_POST['fid']);
		if($fid!='')
		{
			$qry="UPDATE entrp_login 
					SET facebookID='".$fid."' 
					WHERE clientid=".$my_session_id." ";
			if(setData($qry))
			{
				$data='connected';
			}
			else
			{
				$data='notconnected';
			}		
		}
		else
		{
			$data='notconnected';
		}
	}		
	return $data;
}


//Function to check facebook connected or not
//August 24,2016
function checkFBConnectedorNot()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$qry="SELECT facebookID FROM entrp_login  
				WHERE clientid=".$my_session_id." ";
		$res=getData($qry);
		while($row=mysqli_fetch_array($res))
		{
			$facebookID		=	$row['facebookID'];  					
		}
		
		if($facebookID=='')
		{
			$data='notconnected';
		}
		else if($facebookID==0)
		{
			$data='notconnected';
		}
		else
		{
			$data='connected';
		}
	}	
	return $data;
}

//Function to revoke facebook connect
//August 24,2016
function unlinkFacebookAccount()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$qry="UPDATE entrp_login 
				SET facebookID='',facebookEmail='' 
				WHERE clientid=".$my_session_id." ";
		if(setData($qry))
		{
			$data='notconnected';
		}
	}	
	return $data;
}

//Function to save facebook connect data
//August 24,2016
function saveFacebookAuthData()
{
	$data = array();	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id) 
	{
		$fid				=	validate_input($_POST['fid']);
		$firstnameFB	=	validate_input($_POST['first_name']);
		$lastnameFB		=	validate_input($_POST['last_name']);
		$gender			=	validate_input($_POST['gender']);
		$email			=	validate_input($_POST['email']);
		$fbImage			=	validate_input($_POST['fbImage']);

		if($fbImage!='' && $fid!='')
		{
			$imgSRC= "//graph.facebook.com/".$fid."/picture?type=large";
			$qry0="UPDATE client_profile 
					SET avatar='".$imgSRC."'
					WHERE clientid=".$my_session_id." ";
			setData($qry0); 
		}		
		
		$qry="UPDATE entrp_login 
				SET facebookID='".$fid."',facebookEmail='".$email."',firstname='".$firstnameFB."',lastname='".$lastnameFB."',gender='".$gender."' 
				WHERE clientid=".$my_session_id." ";
		if(setData($qry))
		{
			$data='connected';
		}		
	}
	return $data;
}


//Function to invoke spaces service
//July 05,2016
function invokeSpaces()
{
	$data = array();	   
	$myData = array();
	$landing = "index.html";
		   
   $token=validate_input($_POST['token']);
   
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];	
	$userName	= $session_values['username'];	
	
	$myData = fetch_info_from_entrp_login($my_session_id);
		
	$loginEmail = $myData['email'];
	
	$loginPassword = fetchUserLoginPassword($loginEmail,$my_session_id);

	$cid= checkUserLoginClientInfo($loginEmail,$loginPassword);
	
	if($cid!="")
	{
		$authtoken			= md5($loginEmail.date('Y-m-d H:i:s'));
		$_SESSION['token']= $authtoken; 
		$_SESSION["cid"]	 = $cid;
		//setcookie('cid', $_SESSION["cid"], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		//setcookie('token', $_SESSION['token'], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		
    	$serviceType=2; //represents spaces
    	$logResp=logThisServiceAuthRequest($cid,$loginEmail,$loginPassword,$authtoken,$serviceType);
    	if($logResp!='')
    	{
    		$data=$logResp;
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
	return $data;

}


//Function to invoke call answering service
//June 30,2016
function invokeCallAnswering()
{
	$data = array();	   
	$myData = array();
	$landing = "index.html";
		   
   $token=validate_input($_POST['token']);
	
	$session_values = get_user_session();
	$my_session_id	= $session_values['id'];	
	$userName	= $session_values['username'];	
	
	$myData = fetch_info_from_entrp_login($my_session_id);
		
	$loginEmail = $myData['email'];
	
	$loginPassword = fetchUserLoginPassword($loginEmail,$my_session_id);

	$cid= checkUserLoginClientInfo($loginEmail,$loginPassword);
	
	if($cid!="")
	{
		$authtoken			= md5($loginEmail.date('Y-m-d H:i:s'));
		$_SESSION['token']= $authtoken; 
		$_SESSION["cid"]	 = $cid;
		//setcookie('cid', $_SESSION["cid"], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		//setcookie('token', $_SESSION['token'], time() +  60 * 60 * 24 * 30, 'http://callanswering.me/app/');
		
    	$serviceType=1; //represents call answer
    	$logResp=logThisServiceAuthRequest($cid,$loginEmail,$loginPassword,$authtoken,$serviceType);
    	if($logResp!='')
    	{
    		$data=$logResp;
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
	return $data;
}


//Function to log an external service authentication request
//July 01,2016
function logThisServiceAuthRequest($cid,$loginEmail,$loginPassword,$authtoken,$serviceType)
{
	$createdAt=date('Y-m-d H:i:s');
	$status=1;
	$qry="INSERT INTO entrp_external_services_authentication(cid,email,hashedpass,token,authdatetime,servicetype,status) 
			VALUES(".$cid.",'".$loginEmail."','".$loginPassword."','".$authtoken."','".$createdAt."',".$serviceType.",".$status.")";
	if(setData($qry))
	{
		return $authtoken;	
	}
	else
	{
		return null;
	}
} 

//Function to check user's client_info information (validation for external service)
//June 30,2016
function checkUserLoginClientInfo($loginEmail,$loginPassword)
{
	$qry="SELECT clientid FROM entrp_login  
			WHERE email='".$loginEmail."' AND password='".$loginPassword."' ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
	{
		while($row=mysqli_fetch_array($res))
		{
			$clientid		=	$row['clientid'];  					
		}
		return $clientid;
	}
	else
	{
		return null;
	}
}


?>