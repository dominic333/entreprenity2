<?php
require_once ('../api/Query.php'); 


$qrcode=checkQRCode();	
if(!empty($qrcode))
{
	$countCode=count($qrcode);
	for($i=0;$i<$countCode;$i++)
	{
		$codes	= $qrcode[$i];
		$qrCode		= qrCodeGenerator();
		
		$qry="UPDATE entrp_login SET qrCode='".$qrCode."' WHERE clientid=".$codes." ";
		setData($qry);
	}
}
else
{
	echo 'Location Codes already assigned';
}


function checkQRCode()
{
	$qrcode=array();
	//$qry="SELECT * FROM location_info WHERE locCode IS NULL";
	$qry="SELECT * FROM entrp_login WHERE qrCode IS NULL OR qrCode=''";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$qrcode[]			=	$row['clientid']; //feeded
		}
	}
	return $qrcode;
}


function qrCodeGenerator()
{
	$token = substr(md5(uniqid(rand(), true)),0,6);  // creates a 32 digit token
	//SELECT * FROM entrp_login where qrCode='70f804625753d84827ef993329c3b1b8'
   $qry = "SELECT * FROM entrp_login WHERE qrCode='".$token."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      qrCodeGenerator();
   } 
   else 
   {
      return $token;
   }	
}


?>