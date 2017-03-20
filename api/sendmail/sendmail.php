<?
require_once('class.phpmailer.php');
$mail = new PHPMailer();

$mail->IsSMTP();
$mail->SMTPAuth = true; 		 // turn on SMTP authentication
$mail->Username = MS_USERNAME; // SMTP username
$mail->Password = MS_PASSWORD; // SMTP password
//$mail->SMTPDebug = 2;
$mail->Host     = MS_HOSTNAME;
$mail->Port     = MS_PORT; 

?>
