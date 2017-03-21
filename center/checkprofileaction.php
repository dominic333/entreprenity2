<?php
//action page for saving check in details
//Annie , march 17, 2017
if( !session_id() )
{
    session_start();
}
require_once ('../api/Query.php'); 
require_once ('../api/userDefinedFunctions.php'); 
require_once ('../api/externalLibraries/Mobile_Detect.php'); 
require_once ('centerFunctions.php'); 
require_once ('authenticate.php'); 

$clientid		 = $_POST['clientid'];
$vofClientId	 = $_POST['vofClientId'];
$locId   		 = $_POST['locId'];
$loginDate 		 = $_POST['loginDate'];
$checktype		 = $_POST['checktype'];//in db
$checkintype	 = $_POST['checkintype'];//new value
if(isset($_POST['code']))
{

		$code	 = $_POST['code'];//new value
}
else
{
	
		$code=uniqueCheckinCode();

}
echo $code;

$query= "SELECT * FROM location_info WHERE id =".$locId;
$res = getData($query);
while($row = mysqli_fetch_array($res))
{

$locCode = $row['locCode'];

}
 
/*echo $clientid;
echo $vofClientId;
echo $locId;
echo $loginDate;
echo $checktype;//
echo $checkintype;*/

// 1 checkin 
//2 checkout
if($checkintype == 1)
{
$result = logUserIntoThisCenter($clientid,$vofClientId,$locId,$checkintype,$code);

}

else 
{
	$result = logUserIntoThisCenter($clientid,$vofClientId,$locId,$checkintype,$code);
	
}

if($result)
{

header("Location: dumypage.php?location=".$locCode); /* Redirect browser */
exit();

}
else {

echo "failed";	
}




?>

<html>
<head></head>
<body>

</body>

</html>