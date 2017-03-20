<?php

require_once ('../api/Query.php'); 
require_once '../api/constants.php';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset password & send welcome email again</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  </head>
  <body>
  
	
	  <div class="main_bg">
	  

             <div class="container change_password">
                    <h2>Reset password & send welcome email again</h2>
                    <div class="col-md-12">
                    <div class="row">
                        <form id="resentWelcomeEmailForm" name="resentWelcomeEmailForm" method="POST" action="changeuserpassword.php">
										
                              <div class="form-group">
                                  <label for="email">Enter User's Email Id</label>
                                  <input type="text" id="email" name="email" placeholder="Enter Email Id Here.." class="form-control">
                              </div>	
                             		
									  <div class=" form-group">
                         	  		<button type="submit" class="btn btn-md  btn-primary">Submit</button>	
                         	  </div>	
                        	  					
                        </form> 
                        </div>
                    </div> 
             </div>

        </div>
    
   <script src="javascripts/jquery.validate.min.js"></script>
	<script type="text/javascript">
	
	// JavaScript Document
	$(document).ready(function()
	{		
			//Function to validate form
			$('#resentWelcomeEmailForm').validate(
			 { 	
			  rules: 
			  {
			    email: 
			    {
				     required: true,
				     email: true
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