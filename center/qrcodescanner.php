<div class="scanner-qr-code-container">
	<div class="scanner-qr-code container">
		<div>
			<h1>Welcome to <?php echo $_SESSION['locName']; ?></h1>
		</div>
		<div class="row">
            <div class="col-md-6">
                <div class="qr-container">
                    <div style="display:none" id="result"></div>
                    <div class="selector" id="webcamimg" onclick="setwebcam()" align="left" ></div>
                    <div class="selector" id="qrimg" onclick="setimg()" align="right" ></div>
                    <center id="mainbody"><div id="outdiv"></div></center>
                    <canvas id="qr-canvas" width="800" height="600"></canvas>
                    <div class="corner-border tl"></div>
                    <div class="corner-border tr"></div>
                    <div class="corner-border bl"></div>
                    <div class="corner-border br"></div>
                </div>
                <div>
                    <h3>Align the QR code to scan</h3>
                    <img class="en-logo" src="../assets/img/entreprenity-logo.png" alt="">
                </div>
            </div>
            <div class="col-md-6">
                <div style="max-width: 400px; margin: 0 auto; margin-top: 150px">
                    <h2>Position your phone below the camera and align the QR code in the viewport on the left to scan</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="events-container">
                    <h3 style="border-bottom: 1px solid #f29b13; max-width: 200px; margin: 10px auto; padding: 5px;">Upcoming Events</h3>
                    <div>
                        <ul class="list-group">
                            <?php
                            if(!empty($upcomingEvents))
                            {
                             for($i=0;$i<count($upcomingEvents);$i++)
                             {
                            ?>
                            <li class="list-group-item">
                                <div class="content-left">
                                    <div class="date">
                                        <h1><?php echo $upcomingEvents[$i]['eventDFormatted']; ?></h1>
                                        <p><?php echo $upcomingEvents[$i]['monthFormatted']; ?></p>
                                    </div>
                                </div>
                                <div class="content-right">
                                    <h1><strong><?php echo $upcomingEvents[$i]['eventName']; ?></strong></h1>
                                    <h3><strong><?php echo $upcomingEvents[$i]['eventDayFormatted']; ?>, <?php echo $upcomingEvents[$i]['eventStartFormatted']; ?> - <?php echo $upcomingEvents[$i]['eventEndFormatted']; ?></strong></h3>
                                    <p><strong><?php echo $upcomingEvents[$i]['description']; ?></strong></p>
                                </div>
                            </li>
                                    <?php
                                      }
                                    }
                                    else
                                    {
                                        echo 'No Upcoming Events';
                                    }
                                    ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>


		
        <div id="successModal" class="modal fade in" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <img id="checkedInUserImage" class="img-circle" src="" alt="Avatar">
                    </div>
                    <div id="welcomeMsgDiv" class="modal-body" style="color:black;">
                        <div class="arrow-down"></div>
                        <p>Welcome, <span id="checkedInUser"></span>!</p>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#successModal">CONTINUE</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="failureModal" class="modal fade in" >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                         <img id="errorIcon" class="img-circle" src="../assets/img/qr-code-scanner-fail.png" alt="Avatar" width="80">
                         <h1>Code Scanning Failed!</h1>
                    </div>
                    <div id="errorMsgDiv" class="modal-body" style="color:black;">
                        <div class="arrow-down"></div>
                        <p>Please try again</p>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#failureModal">TRY AGAIN</button>
                    </div>
                </div>
            </div>
        </div>
		
		
	</div>
</div>



