<?php


//Member
$member_default_cover			='assets/img/members/member-default.jpg';
$member_default_avatar			='assets/img/members/member-default.jpg';
$member_default					='assets/img/members/member-default.jpg';


//Company
$company_default_cover			='assets/img/companies/company-default.jpg';
$company_default_avatar			='assets/img/companies/company-default.jpg';


//Events
$events_default					='assets/img/events/events-default.jpg';
$event_default_poster			='assets/img/events/events-default.jpg';


$myStaticVars = [
						'member_default_cover'  	=> $member_default_cover,
                	'member_default_avatar' 	=> $member_default_avatar,
                	'events_default'				=> $events_default,
                	'member_default'				=> $member_default,
                	'company_default_cover'		=> $company_default_cover,
                	'company_default_avatar'	=>	$company_default_avatar,
                	'event_default_poster'		=>	$event_default_poster
					 ];
					 

$baristas = array(
					"1" => array("name" => "Sean Lim", "email" => "sean@flexisolutions.com"),
					"46" => array("name" => "Sean Lim", "email" => "sean@flexisolutions.com"),
					"9" => array("name" => "Sean Lim", "email" => "sean@flexisolutions.com"),
					"21" => array("name" => "Sean Lim", "email" => "sean@flexisolutions.com"),
					"2" => array("name" => "Dominic Bird", "email" => "dominic@cliffsupport.com")
				);


if (!defined('TIMELINE_POST_VIDEO')) define('TIMELINE_POST_VIDEO', 'assets/video/');
if (!defined('TIMELINE_POST_VIDEO_UPL')) define('TIMELINE_POST_VIDEO_UPL', '../assets/video/');

if (!defined('TIMELINE_POST_PIC')) define('TIMELINE_POST_PIC', 'assets/img/timeline/');
if (!defined('TIMELINE_POST_PIC_UPL')) define('TIMELINE_POST_PIC_UPL', '../assets/img/timeline/');
 
if (!defined('BUSSOPP_POST_PIC')) define('BUSSOPP_POST_PIC', 'assets/img/businessopp/');
if (!defined('BUSSOPP_POST_PIC_UPL')) define('BUSSOPP_POST_PIC_UPL', '../assets/img/businessopp/');
 
if (!defined('JPEG')) define('JPEG', '.jpeg');
if (!defined('GIF')) define('GIF', '.gif');
if (!defined('PNG')) define('PNG','.png');

if (!defined('PROFILE_PIC')) define('PROFILE_PIC', 'assets/img/members/');
if (!defined('COMPANY_PIC')) define('COMPANY_PIC', 'assets/img/companies/');
if (!defined('EVENT_POSTER')) define('EVENT_POSTER', 'assets/img/events/');

if (!defined('PROFILE_PIC_UPL')) define('PROFILE_PIC_UPL', '../assets/img/members/');
if (!defined('COMPANY_PIC_UPL')) define('COMPANY_PIC_UPL', '../assets/img/companies/');
if (!defined('EVENT_POSTER_UPL')) define('EVENT_POSTER_UPL', '../assets/img/events/');

if (!defined('QRCODE_PATH')) define('QRCODE_PATH', 'assets/img/qrcodes/');
if (!defined('QRCODE_PATH_URL')) define('QRCODE_PATH_URL', '../assets/img/qrcodes/');

//Mail settings (mailgun)
if (!defined('MS_HOSTNAME')) define('MS_HOSTNAME', 'mailgun.securesvr.net');
if (!defined('MS_USERNAME')) define('MS_USERNAME', 'smtp_dominic');
if (!defined('MS_PASSWORD')) define('MS_PASSWORD', 'pIFV2yDX0mbvs');
if (!defined('MS_PORT')) 	  define('MS_PORT', 587);


if (!defined('MS_SENTFROM')) 	  	define('MS_SENTFROM', 'noreply@entreprenity.co');
if (!defined('MS_SENTFROMNAME'))	define('MS_SENTFROMNAME', 'entreprenity.co');

if (!defined('RECIPIENTNAME1')) define('RECIPIENTNAME1','Dominic Gmail');
if (!defined('RECIPIENTEMAIL1')) define('RECIPIENTEMAIL1','dominiccliff88@gmail.com');

if (!defined('RECIPIENTNAME2')) define('RECIPIENTNAME2','Jordan Rains');
if (!defined('RECIPIENTEMAIL2')) define('RECIPIENTEMAIL2','morethanjordan@outlook.com');


if (!defined('ADMINNAME')) define('ADMINNAME','Sean Lim');
if (!defined('ADMINEMAIL')) define('ADMINEMAIL','sean@flexiesolutions.com');


//Email Subjects
if (!defined('NOTIFICATION_CONST')) define('NOTIFICATION_CONST','Notification mail');
if (!defined('EVENTREQ_CONST')) 		define('EVENTREQ_CONST','New Event Request');
if (!defined('FORGOTPASS_CONST')) 	define('FORGOTPASS_CONST','Password Reset');


if (!defined('TIMELINE_POSTS_LIMIT')) 	  define('TIMELINE_POSTS_LIMIT', 4);

/*
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
*/
?>