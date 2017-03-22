<?php
if (!session_id()) {
    session_start();
}
require_once('../api/Query.php');
require_once('../api/userDefinedFunctions.php');
require_once('../api/externalLibraries/Mobile_Detect.php');
require_once('centerFunctions.php');
require_once('authenticate.php');
$code = $_POST['qrcode'];
//echo $code;
$result = fetchUserInfoUsingQRCode($code);
//print_r($result);
date_default_timezone_set('UTC');
$loginDate = date("Y-m-d");
$locId = $_SESSION['locId'];
$clientid = $result['clientid'];

$result1 = fetchUserCheckInType($loginDate, $locId, $clientid);

//echo $result1;

?>


<html>
<head>
    <title> WELCOME !!! </title>
    <link rel="icon" type="image/png" href="../assets/img/favicon-32x32.png" sizes="32x32"/>

    <link rel="apple-touch-icon" sizes="57x57" href="../assets/img/favicon-57x57.png"/>
    <link rel="apple-touch-icon" sizes="72x72" href="../assets/img/favicon-72x72.png"/>
    <link rel="apple-touch-icon" sizes="114x114" href="../assets/img/favicon-114x114.png"/>
    <link rel="apple-touch-icon" sizes="144x144" href="../assets/img/favicon-144x144.png"/>

    <link rel="stylesheet" href="../assets/fonts/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/stylesheets/bootstrap-glyphicons.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <script type="text/javascript" src="main.js"></script>
    <script type="text/javascript" src="llqrcode.js"></script>
</head>
<body>
<div class="">
    <div class="container">

        <div id="welcomeMsgDiv" class="modal-body" style="color:black;">
            <div class="modal-header">
                <img id="checkedInUserImage" class="img-circle" src="../<?php echo $result['avatar'] ?>" alt="Avatar">
                <h4 style="text-align: center;">Welcome, <span
                        id="checkedInUser"><?php echo $result['firstname'] . ' ' . $result['lastname'] ?> </span>!</h4>
            </div>
            <form name="checkin" method="post" action="checkprofileaction.php">
                <div class="arrow-down" id="dev_id">

                    <h5> CLIENT ID :<?php echo $result['clientid'] ?></h5>
                    <h5>VOF CLIENT ID :<?php echo $result['vofClientId'] ?></h5>
                    <h5>USERNAME :<?php echo $result['username'] ?></h5>
                    <h5>EMAIL :<?php echo $result['email'] ?></h5>
                    <p>Check Type

                        <select name="checkintype">

                            <?php if ($result1['checktype'] == 0 || $result1['checktype'] == 2) { ?>
                                <option value="1" label="checktype">Check In</option>
                            <?php } else if ($result1['checktype'] == 1) { ?>
                                <option value="2" label="checktype">Check out</option>
                            <?php } else {
                            } ?>
                        </select>
                    </p>
                </div>
                <button type="submit" name="submit" value="submit" class="btn btn-primary">CONTINUE</button>
                <input type="hidden" name="clientid" value="<?php echo $result['clientid'] ?>">
                <input type="hidden" name="vofClientId" value="<?php echo $result['vofClientId'] ?>">
                <input type="hidden" name="locId" value="<?php echo $locId ?>">
                <input type="hidden" name="loginDate" value="<?php echo $loginDate ?>">
                <input type="hidden" name="checktype" value="<?php echo $result1['checktype'] ?>">
                <input type="hidden" name="code" value="<?php echo $result1['code'] ?>">
            </form>

        </div>
    </div>
</div>
<script src="jquery-1.11.2.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
        crossorigin="anonymous"></script>
<!--<script type="text/javascript">
var data = localStorage.getItem('myMainKey');
console.log(data);
</script>-->
<style>
    div #welcomeMsgDiv {
        background-color: white;

    }
</style>
</body>
</html> 
 