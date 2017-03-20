<? session_start();
include_once ($_SERVER['DOCUMENT_ROOT']."/spaces/appClass.php");
$appClass = new appClass;
$landing = "index.html";
if(isset($_COOKIE['cid']) && ($_COOKIE['token'])){
$_SESSION["cid"] = $appClass->getClientToken($_COOKIE['cid'],$_COOKIE['token']);
if(isset($_SESSION["cid"]))
$land = "<script>window.location.href = '$landing';exit();</script>";
}
if (($_SERVER['REQUEST_METHOD'] == 'POST') && ($_POST['username']!="") && ($_POST['password']!="")){
setcookie('cid', "", time() - 60 * 60 * 24 * 30, '/spaces/');
setcookie('token', "", time() - 60 * 60 * 24 * 30, '/spaces/');
$_SESSION["cid"] = $appClass->checkUserLogin($_POST['username'],$_POST['password']);
if($_SESSION["cid"]==""){
$message = "Invalid username or password";
}else{
$_SESSION['token'] = md5($_POST['username'].date('Y-m-d H:i:s'));
$appClass->updateClientToken($_SESSION['cid'], $_SESSION['token']);	
if(isset($_POST['remember'])){
setcookie('cid', $_SESSION["cid"], time() +  60 * 60 * 24 * 30, '/spaces/');
setcookie('token', $_SESSION['token'], time() +  60 * 60 * 24 * 30, '/spaces/');
}
$land = "<script>window.location.href = '$landing';exit();</script>";
}
}
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<? if(isset($land))
	echo $land;?>
    
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Meeting Room App &mdash; Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/flexslider.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/lightbox.min.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/ytplayer.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/theme.css" rel="stylesheet" type="text/css" media="all" />
        <link href="css/custom.css" rel="stylesheet" type="text/css" media="all" />
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400%7CRaleway:100,400,300,500,600,700%7COpen+Sans:400,500,600' rel='stylesheet' type='text/css'>
        <link href="http://fonts.googleapis.com/css?family=Montserrat:100,300,400,600,700" rel="stylesheet" type="text/css">
    </head>
    <body>
	
		<div class="main-container">
		<section class="image-bg overlay pt112 pb40 pt-xs-64 pb-xs-24">
		        <div class="background-image-holder">
		            <img alt="image" class="background-image" src="img/bg-login.jpg">
		        </div>
		        <div class="container">
		            <div class="row">
		                <div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1">
		                    <div class="feature text-center mb64">
		                        <h1 class="large thin uppercase">Spaces</h1>
                              <h5 class="mb64">#togetherweachievemore #unity #collaboration #partnership</h5>
<form name="loginform" id="loginform" method="post" action="" class="form-email">
					<input name="username" type="text" id="username" class="validate-required"placeholder="username">
							<input name="password" type="password" id="password" class="validate-required mb8" placeholder="password">
                            <!--div class="text-left">
                            
                            <input type="checkbox" id="remember" name="remember" value="remember">
                            <label for="remember" class="color-white">Remember me</label>
                            </div-->
                            
                            <div class="remember text-left">
                            <input type="checkbox" id="remember" name="remember"  value="remember" />
                            <label for="remember">Remember me</label>
                            </div>
                            

							<input type="submit" name="user_login" class="btn btn-primary mb16" value="SIGN IN NOW"> 
                            <p class="mb40"><a href="/spaces/application/views/forgot-password.html">Forgot password</a></p>
                            
                            
						</form>
                        
						<div class="color-red"><?=$message?></div>
                        
                        
		                    </div>
                            
                            <div class="text-center">
                            <p>
                             Dont have account yet? <a href="http://voffice.com.ph/meetingroom/signup.php">Create One</a> 
                            </p>
                            </div>
		                </div>
		            </div> <!-- end of row -->
                  
		        </div>
		        
		    </section>
            
            <section class="bg-primary">
		        <div class="container">
		            <div class="row">
		                <div class="col-sm-12 text-center">
		                    <h6 class="bold mb23 uppercase">A joint effort by</h6>
                            <!--h5>vOffice Malaysia, vOffice Indonesia, vOffice Philippines, InstantOffice@SG, Acceler8.ph, Uppercase.asia, Tao Hub Thailand</h5-->
                            <a href="http://vOffice.com.my" target="_blank"><img src="img/voffice.png" alt="vOffice"></a> 
                            <a href="http://acceler8.com.ph" target="_blank"><img src="img/acceler8.png" alt="Acceler8 PH"></a>
                            <a href="http://instantoffice.com.sg" target="_blank"><img src="img/instantofficesg.png" alt="Instant Office @ SG"></a>
                            <a href="http://uppercase.asia/" target="_blank"><img src="img/uppercase.png" alt="Uppercase Asia"></a>
                            <a href="http://vOffice.com.my" target="_blank"><img src="img/taohub.png" alt="Tao Hub Thailand"></a>
		                </div>
		            </div>
		            
		        </div>
		        
		    </section>
            
            </div>
		
				
		<script src="js/plugins/modernizr-2.8.3-respond-1.4.2.min.js"></script>
		<!--script src="js/jquery.min.js"></script-->
        <script src="js/bootstrap.min.js"></script>
        <!--script src="js/flexslider.min.js"></script>
        <script src="js/lightbox.min.js"></script>
        <script src="js/masonry.min.js"></script>
        <script src="js/twitterfetcher.min.js"></script>
        <script src="js/spectragram.min.js"></script>
        <script src="js/ytplayer.min.js"></script>
        <script src="js/countdown.min.js"></script-->
        <script src="js/smooth-scroll.min.js"></script>
        <script src="js/parallax.js"></script>
        <script src="js/scripts.js"></script>
        
        <!-- Scripts -->
		<script>
		window.jQuery || document.write('<script src="js/plugins/jquery-1.11.2.min.js"><\/script>')
        </script>
		<!--script src="js/plugins/bootstrap.min.js"></script-->
        
    </body>
</html>
				