<?php

require_once ('../api/Query.php'); 
require_once '../api/constants.php';

//Function to validate inputs
function validate_input($input) 
{	
  $input = trim($input);
  //$input = stripslashes($input);
  $input = addslashes($input);
  $input = htmlspecialchars($input);
  return $input;
}


$token	= validate_input(urldecode($_GET['token']));
$currentTime=date('Y-m-d H:i:s');
if($token!='')
{
	$qry="SELECT * FROM entrp_forgot_password_tokens WHERE forgotToken='".$token."' AND status=0";
	$res=getData($qry);
	$count_res=mysqli_num_rows($res); 
	if($count_res==1)
	{
		while($row=mysqli_fetch_array($res))
		{
			$clientid		=	$row['clientid'];
			$expireTime		=	$row['expireTime'];     
		}
		
		if($currentTime > $expireTime)
		{
			
			$tokenStatus='expired';
		}
		else
		{
			$tokenStatus='valid';
		}
	}
	else
	{
		$tokenStatus='notavailable';
	}
}
else
{
	$tokenStatus='notavailable';
}
 

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  </head>
  <body>
  
	
	  <div class="main_bg">
	  
	  			 <?php if($tokenStatus=='valid') { ?>
             <div class="container change_password">
                    <h2>Change Password Form</h2>
                    <div class="col-md-12">
                    <div class="row">
                        <form id="passwordChangeForm" name="passwordChangeForm" method="POST" action="changepasswordaction.php">
										
										<input type="hidden" id="tempc" name="tempc" value="<?php echo (isset($clientid))?$clientid:'';?>" >
										<input type="hidden" id="forgotToken" name="forgotToken" value="<?php echo (isset($token))?$token:'';?>" >
                              <div class="form-group">
                                  <label for="password">New Password</label>
                                  <input type="password" id="password" name="password" placeholder="Enter Password Here.." class="form-control">
                              </div>

                             <div class="form-group">
                                 <label for="cpassword">Confirm Password</label>
                                 <input type="password" id="cpassword" name="cpassword" placeholder="Re-enter Password Here.." class="form-control"> 
                             </div>		
                             		
									  <div class=" form-group">
                         	  		<button type="submit" class="btn btn-md  btn-primary">Submit</button>	
                         	  </div>	
                        	  					
                        </form> 
                        </div>
                    </div> 
             </div>
             <?php } ?>
             
             <?php if($tokenStatus=='expired') {  ?>
             <div class="container link_expired">
              Sorry! This token already expired. Please send a new request.
             </div>
             <?php } ?>
             
             <?php if($tokenStatus=='notavailable') { ?>
             <div class="container link_expired">
              Sorry! This token is invalid. Please send a new request.
             </div>
             <?php } ?>
        </div>
    
   <script src="javascripts/jquery.validate.min.js"></script>
	<script type="text/javascript">
	
	// JavaScript Document
	$(document).ready(function()
	{		
			//Function to validate form
			$('#passwordChangeForm').validate(
			 { 	
			  rules: 
			  {
			    password: 
			    {
				     required: true,
				     minlength: 8
				 }, 
			    cpassword:
			    {
			     	 required:true,
			     	 minlength: 8,
			     	 equalTo: "#password"
			    }			    
			  },
			  highlight: function(element) 
			  {
				  $(element).closest('.form-group').removeClass('success').addClass('error');
			  },
			  success: function(element) 
			  {
				  element
				 .text('').addClass('valid')
				 .closest('.form-group').removeClass('error').addClass('success');
			  }
			}); 
	    
	}); // End Ready Function
	
	</script>   
   
  </body>
</html>