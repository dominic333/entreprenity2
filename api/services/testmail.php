<?php
$pathToFile				= "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
echo $pathToFile	;
	
	
	$to = 'dominic@cliffsupport.com'; 
	$strSubject = "Notification mail";
	$message =  'this is a test message';              
	$headers = 'MIME-Version: 1.0'."\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
	$headers .= "From: eprty@test.com"; 
	/*
	if(mail($to, $strSubject, $message, $headers)){
		echo 'Mail send successfully';
	}else{
		echo 'Could not send email';
	} 
	*/

?>