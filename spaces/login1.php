<?php
include 'getlocation.php';
$ipcountry = ip_info("Visitor", "Country"); 



if( isset($_POST['user_login']) )
{
  define( 'LOGGING_IN', true );
  // include the 'session functions' file
  include_once( 'loginfunc.php' );
  process_login();
   
}
else
{
?>

<!DOCTYPE html>

<html lang="en">

<head>

<title>vOffice&reg; &#8212; Customer Portal Home</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=Edge">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="keywords" content="">

<meta name="description" content="">

<!-- 

Impulse Template

http://www.templatemo.com/free-website-templates/469-impulse

-->

<!-- STYLESHEET CSS FILES -->

<link rel="stylesheet" href="login/css/bootstrap.min.css">
<link rel="stylesheet" href="login/css/animate.min.css">
<link rel="stylesheet" href="login/css/font-awesome.min.css">
<link rel="stylesheet" href="login/css/nivo-lightbox.css">
<link rel="stylesheet" href="login/css/nivo_themes/default/default.css">
<link rel="stylesheet" href="login/css/templatemo-style.css">

</head>

<body data-spy="scroll" data-offset="50">

<!-- preloader section -->

<div class="preloader">
  <div class="sk-spinner sk-spinner-rotating-plane"></div>
</div>

<!-- home section -->

<section id="home">

  <!--div class="container"-->

    <div class="row">
 <? if($ipcountry=="Philippines" || $ipcountry=="Malaysia" || $ipcountry=="Indonesia"){?>
      <div class="col-md-12 col-sm-12 title" style="background:#f6f6f6;color:#333;height: 100vh;">
      <? }else{ ?>
      <div class="title" style="background:#f6f6f6;color:#333;height: 100vh;">
      <? } ?>
        <img src="login/images/myvOffice.png" style="margin-top:50px">
        <div class="row">
        <div class=" col-md-8 col-sm-8 col-md-offset-2 col-sm-offset-2">
		<div class="formsection">
        <form name="loginform" id="loginform" method="post" action="<?php echo $_SERVER['login/PHP_SELF']; ?>">
		  <?php
              if ($_GET["login"]=="bl")
			  {
				  echo "<div><h5>Logging Error!</h5></div>";
			  }
			  if ($_GET["login"]=="location")
			 {
				 echo "</form>";
				 echo "Please Select Your Account Location";
				include_once('location.php');
			 ?>
                <?
			 }
			 
			 
			 else{
				
				  ?>
          <input name="user" type="text" id="user" class="form-control" placeholder="username">

          <input name="password" type="password" id="password" class="form-control" placeholder="password">
          <input type="submit" name="user_login" class="form-control" value="SIGN IN NOW"> 
		  <? } ?>
        </form>
        <div>&nbsp;</div>
        <hr>
        <p><small>If you have lost your password or did not have one yet, you can request for a new password by entering your primary email address with us <a href="forget_password.php">HERE</a> — (it's the email address you receive your welcome mail or invoice).</small></p>
        
        </div> <!-- end of formsection -->
        </div> 
        </div> 
        
        
        
        <? if($ipcountry=="Malaysia"){?>

        <div id="vrush" >
        <div class="formsection" style="background:#fff;padding:40px 20px;border:4px solid #c32327;border-left:0;border-right:0">
        
        <h3>Start your own company today</h3>
        <p>Let us Incorporate your Company for you</p>
        <ul>
        <li>Straight forward & hassle freet</li>
        <li>Only requires 7 working days to complete (could be faster)</li>
        <li>It's affordable</li>
        </ul>
		<p class="text-center">
        <a href="http://voffice.com.my/malaysia-company-registration.html" target="_blank" class="btn">Read More</a></p>
        </div>
        </div><!-- end of companyreg -->
        <? } ?>
        
        <? if($ipcountry=="Indonesia"){?>

        <div id="vrush" >
        <div class="formsection" style="background:#fff;padding:40px 20px;border:4px solid #c32327;border-left:0;border-right:0">
        <img src="login/simpanbarang1.jpg">
        <p class="text-center">
        <a href="http://simpanbarang.com/" target="_blank" class="btn">Read More</a></p>-->
        </div>
        </div><!-- end of companyreg -->
        <? } ?>
        
        <div class="formsection">
        <hr>

        <p><small>©2010–2015 All Rights Reserved.<br>
Powered by <a href="http://businesscentresolution.com/" target="_blank">BCMS</a></small></p>
        </div> <!-- end of form section for footer part -->
        
     
      </div> <!-- end of col -->
      

<? if($ipcountry=="Malaysia"){?>
      <div class="col-md-4 col-sm-4 title text-left" id="rightbar">
        <h3>Start your own company today</h3>
        <p>Let us Incorporate your Company for you</p>
        <ul>
        <li>Straight forward & hassle freet</li>
        <li>Only requires 7 working days to complete (could be faster)</li>
        <li>It's affordable</li>
        </ul>
		<p class="text-center">
        <a href="http://voffice.com.my/malaysia-company-registration.html" target="_blank" class="btn">Read More</a></p>
      </div>
<? } ?>

<? if($ipcountry=="Indonesia"){?>
<style>
#content {
    position: relative;
}
#content img {
    position: absolute;
    top: 0px;
    right: 0px;
}
</style>
<div id="content" class="col-md-4 col-sm-4 title text-left" id="rightbar">
    <img src="images/unspecified.png" class="ribbon" width="545"/>
</div>
		<!--
       <div class="col-md-4 col-sm-4 title text-left" id="rightbar">
        <img src="login/simpanbarang-logo.png">  
        <h3>Gratis 3 bulan pertama!</h3>
        <p>Juga dapatkan diskon potongan untuk ruang penyimpanan.</p>
		<p class="text-center">
        <a href="http://voffice.com.my/index.php?dis=company_registration" target="_blank" class="btn">Read More</a></p>
      </div>-->
<? } ?>

    </div> <!-- end of row -->

  <!--/div-->

</section>



<!-- JAVASCRIPT JS FILES --> 

<script src="login/js/jquery.js"></script> 

<script src="login/js/bootstrap.min.js"></script> 

<script src="login/js/nivo-lightbox.min.js"></script> 

<script src="login/js/smoothscroll.js"></script> 

<script src="login/js/jquery.sticky.js"></script> 

<script src="login/js/jquery.parallax.js"></script> 

<script src="login/js/wow.min.js"></script> 

<script src="login/js/custom.js"></script>

</body>

</html>
<?php
}
?>