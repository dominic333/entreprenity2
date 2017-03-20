<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <meta name="format-detection" content="telephone=no">
      <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
      <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
      <meta name="format-detection" content="telephone=no">
      <meta name="format-detection" content="telephone=no">
      <meta name="format-detection" content="date=no">
      <meta name="format-detection" content="address=no">
      <meta name="format-detection" content="email=no">
      <title>Welcome to Entreprenity</title>
      <style type="text/css">
         body,table.body,h1,h2,h3,h4,h5,h6,p,td {
         font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
         font-size: 14px;
         color: #202020;
         line-height: 1.4; 
         }
         h1, p {
         padding-top: 15px;
         padding-bottom: 15px;
         margin-top: 0px;
         margin-bottom: 0px;
         }
      </style>
      <style type="text/css">
         table {border-collapse:separate;}
         a, a:link, a:visited {text-decoration: none; color: #00788a} 
         a:hover {text-decoration: underline;}
         h2,h2 a,h2 a:visited,h3,h3 a,h3 a:visited,h4,h5,h6,.t_cht {color:#000 !important}
         .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td {line-height: 100%}
         .ExternalClass {width: 100%;}
      </style>
   </head>
   <body style="background-color: #EEEEEE; padding:10px; margin:0">
      <span style="display: none !important;">	
      Welcome to Entreprenity
      </span>
      <table border="0" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0;" width="100%">
         <tr>
            <td align="center" valign="top">
               <table cellpadding="0" cellspacing="0">
                  <tr>
                     <td class="pattern" width="580" style="background-color: #ffffff;">
                        <table cellpadding="0" cellspacing="0">
                           <tr>
                              <td class="header" style="height: 56px; background-color: #222222; width: 580px">
                                 <table cellpadding="0" cellspacing="0" style="width: 100%;">
                                    <tr>
                                       <td align="center" style="width: 100%; padding-left: 10px;  padding-right: 10px; padding-top: 10px; padding-bottom: 10px;">
                                          <img src="http://entreprenity.co/app/assets/img/entreprenity-logo.png" alt="" style="width: 200px; display: block; border: 0;" />
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td align="center" style="padding-top: 10px; padding-left: 15%; padding-right: 15%; text-align: center;">
                                 <table cellpadding="0" cellspacing="0">
                                    <tr>
                                       <td align="center" style="padding-left: 10px;  padding-right: 10px;">
                                          <h1 style="font-size: 18px;">Hi, <strong class="name" style="color: #f29b13;">Admin</strong>!</h1>
                                          <p>A new event request has been received. Here are the details.</p>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td align="center" style="padding-top: 10px; padding-left: 15%; padding-right: 15%; text-align: center;">
                                 <table cellpadding="0" cellspacing="0">
                                    <tr>
                                       <td align="center" style="padding-left: 10px;  padding-right: 10px;">
                                       
                                          <p style="text-align: left; ">Event Name: <?php echo (isset($name) && $name != '' ? $name : ''); ?> </p>
                                          <p style="text-align: left; ">Event Tag: <?php echo (isset($eventTagId) && $eventTagId != '' ? $eventTagId : ''); ?> </p>
                                          <p style="text-align: left; ">Category: <?php echo (isset($category) && $category != '' ? $category : ''); ?></p>
                                          <p style="text-align: left; ">Description: <?php echo (isset($about) && $about != '' ? $about : ''); ?> </p>
                                          <p style="text-align: left; ">Address: <?php echo (isset($address) && $address != '' ? $address : ''); ?> </p>
                                          <p style="text-align: left; ">Location: <?php echo (isset($latitude) && $latitude != '' ? $latitude : ''); ?>,<?php echo (isset($longitude) && $longitude != '' ? $longitude : ''); ?> </p>
                                          <p style="text-align: left; ">City: <?php echo (isset($city) && $city != '' ? $city : ''); ?> </p>
                                          <p style="text-align: left; ">Date: <?php echo (isset($date) && $date != '' ? $date : ''); ?> </p>
                                          <p style="text-align: left; ">Time: <?php echo (isset($startTime) && $startTime != '' ? $startTime : ''); ?> to <?php echo (isset($endTime) && $endTime != '' ? $endTime : ''); ?> </p>
                                          <p style="text-align: left; ">Event Host: <?php echo (isset($firstName) && $firstName != '' ? $firstName : ''); ?>  <?php echo (isset($lastName) && $lastName != '' ? $lastName : ''); ?> </p>
														<p style="text-align: left; ">Company: <?php echo (isset($companyName) && $companyName != '' ? $companyName : ''); ?> </p>                                          
                                          <p style="text-align: left; ">Requested On: <?php echo (isset($added_on) && $added_on != '' ? $added_on : ''); ?> </p>
                                       </td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                           <tr>
                              <td align="center" style="height: 50px; padding-left: 10px;  padding-right: 10px; padding-bottom: 20px;">
                                 <span  style="color: #15D141; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; border-color: #15D141; border-width: 2px; border-style: solid; cursor: pointer;">
                                    <a href="<?php echo $eventAprovURL; ?>" target="_blank" style="color: inherit; text-decoration: none;">Approve this event</a>
                                 </span>
                                 <span   style="color: #F20F0F; padding-top: 10px; padding-bottom: 10px; padding-left: 10px; padding-right: 10px; border-color: #F20F0F; border-width: 2px; border-style: solid; cursor: pointer;">
                                    <a href="<?php echo $eventRejectURL; ?>" target="_blank" style="color: inherit; text-decoration: none;">Reject this event</a>
                                 </span>
                              </td>
                           </tr>
                           <tr>
                              <td class="footer" align="center" valign="center" style="height: 56px; border-top-color: #C9CBCD; border-top-width: 1px; border-top-style: solid; padding-left: 15%; padding-right: 15%;">
                                 <span style="color: #C9CBCD; font-size: 12px; ">If you didn't create an account using this email address, <a href="#">let us know</a></span> 
                              </td>
                           </tr>
                        </table>
                     </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>