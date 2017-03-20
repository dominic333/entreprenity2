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
		<title>Notification alert</title>	
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
			a{
				color: inherit;
				text-decoration: none;
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
			Notification alert
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
														<img src="https://entreprenity.co/app/assets/img/entreprenity-logo.png" alt="" style="width: 200px; display: block; border: 0;" />
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<?php if(isset($type) && $type === 'follow'){ ?>
									<tr>
										<td align="center" style="padding-top: 10px; padding-left: 15%; padding-right: 15%; text-align: center;">
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td align="left" style="padding-left: 10px;  padding-right: 10px;">
														<h1 style="font-size: 18px;">Hi <strong class="name" style="color: #f29b13;">@<?php echo (isset($followed_username) && $followed_username != '' ? $followed_username : ''); ?></strong>!</h1>
														<p><a href="#">@<?php echo (isset($following_username) && $following_username != '' ? $following_username : ''); ?></a> started following you!</p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<?php } ?>
									<?php if(isset($type) && $type === 'like'){ ?>
									<tr>
										<td align="center" style="padding-top: 10px; padding-left: 15%; padding-right: 15%; text-align: center;">
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td align="left" style="padding-left: 10px;  padding-right: 10px;">
														<h1 style="font-size: 18px;">Hi <strong class="name" style="color: #f29b13;">@<?php echo (isset($postAuthorUsername) && $postAuthorUsername != '' ? $postAuthorUsername : ''); ?></strong>!</h1>
														<p><a href="#">@<?php echo (isset($likerUsername) && $likerUsername != '' ? $likerUsername : ''); ?></a> liked your <a href="#">post</a></p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<?php } ?>
									<?php if(isset($type) && $type === 'comment'){ ?>
									<tr>
										<td align="center" style="padding-top: 10px; padding-left: 15%; padding-right: 15%; text-align: center;">
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td align="left" style="padding-left: 10px;  padding-right: 10px;">
														<h1 style="font-size: 18px;">Hi <strong class="name" style="color: #f29b13;">@<?php echo (isset($postAuthorUsername) && $postAuthorUsername != '' ? $postAuthorUsername : ''); ?></strong>!</h1>
														<p><a href="#">@<?php echo (isset($commentAuthorUsername) && $commentAuthorUsername != '' ? $commentAuthorUsername : ''); ?></a> commented on your <a href="#">post</a></p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<?php } ?>
									<?php if(isset($type) && $type === 'mention'){ ?>
									<tr>
										<td align="center" style="padding-top: 10px; padding-left: 15%; padding-right: 15%; text-align: center;">
											<table cellpadding="0" cellspacing="0">
												<tr>
													<td align="left" style="padding-left: 10px;  padding-right: 10px;">
														<h1 style="font-size: 18px;">Hi <strong class="name" style="color: #f29b13;">@will</strong>!</h1>
														<p><a href="#">@jason</a> mentioned you in a <a href="#">post</a></p>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									<?php } ?>																					
									<tr>
										<td class="footer" align="center" valign="center" style="height: 56px; border-top-color: #C9CBCD; border-top-width: 1px; border-top-style: solid; padding-left: 15%; padding-right: 15%;">
											 <span style="color:#C9CBCD;font-size:12px;" >Entreprenity is currently in BETA phase. So please, do let us know what issues you are facing while using the app. Do feed back to us at <a href="mailto:feedback@entreprenity.co" style="text-decoration:none;color:#00788a;" >feedback@entreprenity.co</a>
                                 when you found one.</span>
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