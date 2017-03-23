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

    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <link rel="stylesheet" href="bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">

    <!--    <style>-->
    <!--        table {-->
    <!--            width: 80%-->
    <!--        }-->
    <!---->
    <!--        th, td {-->
    <!--            padding: 5px;-->
    <!--            text-align: left;-->
    <!--        }-->
    <!--    </style>-->

</head>
<body>

<div class="">
    <div class="container">

        <div class="modal-body" id="welcomeMsgDiv" tabindex="-1" role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <button type="button" class="close"
                                data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">
                            Welcome, <span
                                id="checkedInUser"><?php echo $result['firstname'] . ' ' . $result['lastname'] ?> </span>!
                        </h4>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        <p>
                        <center><img id="checkedInUserImage" class="img-circle img-responsive"
                                     src="../<?php echo $result['avatar'] ?>" alt="Avatar"></center>
                        </p>

                        <form name="checkin" method="post" action="checkprofileaction.php" class="form-horizontal"
                              role="form">

                            <table border='0' class="table">
                                <tr>
                                    <td width='30%'><b>CLIENT ID :</b>
                                    <td>
                                    <td><?php echo $result['clientid'] ?>
                                    <td>
                                </tr>
                                <tr>
                                    <td><b>VOF CLIENT ID :</b>
                                    <td>
                                    <td><?php echo $result['vofClientId'] ?>
                                    <td>
                                </tr>
                                <tr>
                                    <td><b>USERNAME :</b>
                                    <td>
                                    <td><?php echo $result['username'] ?>
                                    <td>
                                </tr>
                                <tr>
                                    <td><b>EMAIL :</b>
                                    <td>
                                    <td><?php echo $result['email'] ?>
                                    <td>
                                </tr>
                                <tr>
                                    <td><b>CHECK TYPE :</b>
                                    <td>
                                    <td><select name="checkintype">

                                            <?php if ($result1['checktype'] == 0 || $result1['checktype'] == 2) { ?>
                                                <option value="1" label="checktype">Check In</option>
                                            <?php } else if ($result1['checktype'] == 1) { ?>
                                                <option value="2" label="checktype">Check out</option>
                                            <?php } else {
                                            } ?>
                                        </select>
                                    <td>
                                </tr>
                            </table>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-danger"
                                data-dismiss="modal">
                            Cancel
                        </button>
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
        </div>


    </div>
</div>

<script src="jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="main.js"></script>
<script type="text/javascript" src="llqrcode.js"></script>

<script type="text/javascript" src="bootstrap/bootstrap.min.js"></script>
<!--<script type="text/javascript">
var data = localStorage.getItem('myMainKey');
console.log(data);
</script>-->
<!--<style>-->
<!--    div #welcomeMsgDiv {-->
<!--        background-color: white;-->
<!---->
<!--    }-->
<!--</style>-->
</body>
</html> 
 