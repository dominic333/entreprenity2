<?php

require_once ('../api/Query.php'); 
require_once ('../api/constants.php'); 
require_once ('../api/externalLibraries/qrcode.php'); 

$qr = new qrcode();

if (isset($_REQUEST["staff_id"]))
{
	$staff_id	=	$_REQUEST["staff_id"];

if($staff_id != '')
{
		$query 		= "select qrCode from entrp_login where vof_clientid='".$staff_id."'";
		$result		=	getData($query);
		$count_res	=	mysqli_num_rows($result);
		if($count_res > 0) 
		{
			while($row		=	mysqli_fetch_array($result)) {	
				$qrCode	=	$row['qrCode'];
				//echo $qrCode;	
			}
					
			$var	= $qr->text($qrCode);
			$link	=	$qr->get_link();
			
			$output = '<img src="';
			$output.= $link ;
			$output.='" />';
			echo $output;
			//$file = $qr->get_image();
			//$qr->download_image($file);
			
			$imageName = $staff_id.'.png';
			$imagePath = __DIR__ .'/qrcodes/'.$imageName;
			$downloadPath = 'qrcodes/'.$imageName;
			$content = file_get_contents($link);
			$fp = fopen($imagePath, "w");
			fwrite($fp, $content);
			fclose($fp);
						
			$output1='<a id="download" href="';
			$output1.=$downloadPath;
			$output1.='" download="'.$downloadPath.'">Download</a><br/>';
			echo $output1;


		}
		else
		{
			
			echo "Client id / qrCode does not exists";
		
		}
}		
}
echo '<a href="downloadQRCode.php">Click to go back</a>';
?>