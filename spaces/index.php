<? 	include_once ($_SERVER['DOCUMENT_ROOT']."/spaces/appClass.php");
	include_once ($_SERVER['DOCUMENT_ROOT']."/spaces/checker.php");
	$appClass = new appClass;
	$client_info= $appClass->portal_main_page_getClientCompanyInfo($_SESSION["cid"]);
	
	$client_total=0;
	$client_hrs_q = $appClass->portal_main_page_getClientFacilityInfo($_SESSION["cid"]);  
	while($client_hrs=mysqli_fetch_assoc($client_hrs_q)){
	$client_total = $client_total+$client_hrs['meeting_room_hours_left'];
	}
	$_SESSION['total_hrs']=$client_total;
	
	
	//get visitor ip
	function getUserIP()
		{
			$client  = @$_SERVER['HTTP_CLIENT_IP'];
			$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
			$remote  = $_SERVER['REMOTE_ADDR'];
		
			if(filter_var($client, FILTER_VALIDATE_IP))
			{
				$ip = $client;
			}
			elseif(filter_var($forward, FILTER_VALIDATE_IP))
			{
				$ip = $forward;
			}
			else
			{
				$ip = $remote;
			}
		
			return $ip;
		}
	$ip = getUserIP();

	
	$details = json_decode(file_get_contents("http://ipinfo.io/$ip/json"));
	$client_loc =  $details->country;
	if($client_loc=="MY"){
	$signup = "http://voffice.com.my/meetingroom/";
	$locations = array(4,7,3,39,40, 34,35,51,52,72,89,86,122,148,149, 5,1,2,20,21,25,31,45,46,67,66,73,74,77,78,96,97,131,82,134);}
	else if($client_loc=="PH"){
	$signup = "http://voffice.com.ph/meetingroom/";
	$locations = array(34,35,51,52,72,89,86,122,148,149, 4,7,3,39,40, 5,1,2,20,21,25,31,45,46,67,66,73,74,77,78,96,97,131,82,134);}
	else if($client_loc=="ID"){
	$signup = "http://voffice.co.id/meetingroom/";
	$locations = array(5,1,2,20,21,25,31,45,46,67,66,73,74,77,78,96,97,131,82,134, 34,35,51,52,72,89,86,122,148,149, 4,7,3,39,40);}
	else{
	$signup = "http://voffice.com.my/meetingroom/";
	$locations = array(34,35,51,52,72,89,86,122,148,149, 4,7,3,39,40, 5,1,2,20,21,25,31,45,46,67,66,73,74,77,78,96,97,131,82,134);}
	?>
<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
	<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
			<title>vOffice | Meeting Rooms</title>
			<meta name="description" content="">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<link rel="apple-touch-icon" href="apple-touch-icon.png">
			<link rel="shortcut icon" type="image/png" href="favicon.ico"/>
			<!-- Font Awesome Style -->
			<link rel="stylesheet" href="stylesheets/font-awesome.min.css">
			<!-- Main Stylesheet -->
			<link rel="stylesheet" href="stylesheets/styles.css">
            
			<style>
				body {
					padding-top: 50px;
				}

				/* clear fix */
				.grid:after {
				content: '';
				display: block;
				clear: both;
				}
			</style>
			<script src="js/plugins/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	</head>
	<body style="height: 100%;">
		<!-- Header -->
		<!-- Navigation Bar -->
    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavBar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button> 
          <a class="navbar-brand" href="/spaces/"><img src="img/spaces.png" alt="Meeting Room App" height="auto"></a>
        </div>
				<div class="collapse navbar-collapse" id="myNavBar">
					<ul class="nav navbar-nav navbar-right">
						<li class="hoursLeft"><a href="#"><? echo "You have <strong>".$_SESSION['total_hrs']."</strong> hours of meeting room usage left.";?></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="username"><img src="img/icon-user.png" alt="" height="auto" width="36">Welcome <?=$client_info['firstname']?></span><span class="caret"></span></a>
							<ul class="dropdown-menu">
								<li><a href="/spaces/application/views/bookings.html">My Bookings</a></li>
								<li role="separator" class="divider"></li>
                                <li><a href="/spaces/application/views/invoices.html">My Invoices</a></li>
                                 <li role="separator" class="divider"></li>
                <li><a href="<?=$signup?>">Buy More Hours</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="logout.html">Logout</a></li>
							</ul>
						</li>
					</ul>
				</div>
      </div>
    </nav>

		<div id="hero-container" class="no-padding">	
			<div id="hero">
				<img src="img/cover1.jpg" alt="Meeting Room" height="auto" width="100%">
				<!-- Search and Filter Div -->
				<div class="form-group">
				 <div class="input-group">
							<input type="text" class="form-control quicksearch" placeholder="Search -- Jakarta, Manila, Kuala Lumpur, Surabaya, Bali" name="srch-term" id="srch-term">
							<div class="input-group-btn">
									<button class="btn btn-default" type="submit" ><i class="fa fa-search"></i></button>
							</div>
					</div>
				</div>
			</div>
		</div>
			
		<div id="home-content-container" class="container" style="background-color: #efefef;">
			<div class="row">
				<div class="col-md-8">
					<!-- <div id="search-results">
						<p>Search Results: 6</p>
					</div>
					Meeting Rooms Cards Preview -->
					<div class="row" style="height: 520px; overflow-y: auto;">

						<div class="iso-grid"><!-- grid start //got css issue -->
							<? foreach ($locations as $key => $location) {
									include 'config.php';
							?>
							<div class='iso-element-item' data-category='transition'><!-- element start-->
								<div class="">  
									<div class="cards thumbnail">
										<img src="<?=$photo?>" alt="..." style="width: 100%; height: auto">
										<div class="caption">
											<ul>
												<li>
													<h4  class="card-name"><?=$name?></h4>
												</li>
												<li class="card-address-container">
													<span><i class="fa fa-map-marker fa-lg"></i></span>
													<p class="card-address"><?=$address?></p>
												</li>
												<hr>
												<li>
													<div class="card-capacity-container">
														<div class="float-left">
															<p class="card-capacity"><span><i class="fa fa-users fa-lg"></i></span><?=$mincap."-".$maxcap?> Guests</p>
														</div>
														<div class="float-right">
															<span>Operating Hours</span>
															<p class="card-hours"><?=$starthr."-".$endhr?></p>
														</div>
													</div>
												</li>
												<hr>
												<li>
													<div class="card-features">
														<? foreach ($facil as $skey => $sfacil) {?>
														<div class="meetingicon-container">
															<span class="meetingicon <?=$icons[$skey]?>"></span>
															<span class="icon-label"><?=$sfacil?></span>
														</div>
													 <? }?>
													</div>
												</li>
												<hr>
												<li class="card-btn-container">
													<a href="application/views/booking.php?lid=<?=$_SESSION['lid']?>" class="btn btn-primary" role="button">Book Now</a>
												</li>
											</ul>
										</div>
									</div>
								</div> 
							</div> <!-- element end-->
						 <? } ?>         
					 </div>  <!-- .grid --> 
				 </div> <!-- .row --> 
                 
                 
				</div> <!-- .col-md-8 -->
				
				<!-- Map -->
				<div class="col-md-4">
					<div class="map-container">
						<div id="map"></div>
					</div>
				</div> <!-- .col-md-4 -->
				
			</div> <!-- .row -->
		</div> <!-- .container -->

		<!-- footer -->	
		<footer>
			<div class="container">
				<p class="small" style="font-weight:300;">If you encounter any problem while using this site or with the facility, please contact your Account Manager. This system is built and maintained by vOffice.</p>
				<p class="small" style="font-weight:300;">Copyright &copy; 2016 vOffice Asia</p>
			</div>
			
			<ul class="nav pull-right scroll-top">
				<li><a href="#" title="Scroll to top"><i class="fa fa-chevron-up fa-lg"></i></a></li>
			</ul>
		</footer>
			
		<!-- Scripts -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="js/plugins/jquery-1.11.2.min.js"><\/script>')</script>
		<script src="js/plugins/bootstrap.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js"></script> <!-- Google Maps JS API -->
		<script src="js/plugins/gmaps.js"></script> <!-- GMaps Library -->
		<script src="js/main.js"></script>
		<script src="js/scrollToTop.js"></script>
        <script src="js/isotope.pkgd.js"></script>
        
		<script  type="text/javascript">
			$(document).ready( function() {
				<? if($client_loc=="MY"){?>
				var dummyData = '[{"BuildingName": "Wisma Genting","MapLat": 3.1506385,"MapLng": 101.7110145},' +
													'{"BuildingName": "Empire Tower","MapLat": 3.0820123,"MapLng": 101.5827012},' +
													'{"BuildingName": "Plaza Mont Kiara","MapLat": 3.16658,"MapLng": 101.65151}' + 
												']';
												
				<? }else if($client_loc=="PH"){ ?>
				var dummyData = '[{"BuildingName": "Fort Legend Tower","MapLat": 14.55408,"MapLng": 121.04692},' +
													'{"BuildingName": "One Global Place","MapLat": 14.54831,"MapLng": 121.04705},' +
													'{"BuildingName": "Mavenue Building","MapLat": 14.565339,"MapLng": 121.029959},' + 
													'{"BuildingName": "Finman Building","MapLat": 14.565339,"MapLng": 121.029959},' + 
													'{"BuildingName": "Rufino Pacific Tower","MapLat": 14.55804,"MapLng": 121.01822}' + 
												']';
												
				<? }else if($client_loc=="ID"){ ?>
				var dummyData = '[{"BuildingName": "Menara Rajawali","MapLat": -6.2271771,"MapLng": 106.8264121},' +
													'{"BuildingName": "The City Tower","MapLat": -6.1991132,"MapLng": 106.8236692},' +
													'{"BuildingName": "Kebon Jeruk","MapLat": -6.1980239,"MapLng": 106.7620677},' +
													'{"BuildingName": "Grand Slipi","MapLat": -6.200765,"MapLng": 106.7985219},' +
													'{"BuildingName": "CEO Building","MapLat": -6.2920438,"MapLng": 106.7968952},' +
													'{"BuildingName": "Kirana II Tower","MapLat": -6.151794,"MapLng": 106.8951},' +
													'{"BuildingName": "Graha Surveyor","MapLat": -6.2392756,"MapLng": 106.8323006},' +
													'{"BuildingName": "FX Plaza Office Tower","MapLat": -6.2246529,"MapLng": 106.8038571},' +
													//'{"BuildingName": "Berry Biz Hotel","MapLat": -8.6979611,"MapLng": 115.1777275},' +
													//'{"BuildingName": "Ibis Styles","MapLat": -8.6791566,"MapLng": 115.2051796},' +
													'{"BuildingName": "Office 8","MapLat": -6.2294797,"MapLng": 106.8057355}' + 
												']';
				<? }else { ?>
				var dummyData = '[{"BuildingName": "Fort Legend Tower","MapLat": 14.55408,"MapLng": 121.04692},' +
													'{"BuildingName": "One Global Place","MapLat": 14.54831,"MapLng": 121.04705},' +
													'{"BuildingName": "Finman Building","MapLat": 14.56098,"MapLng": 121.02423},' + 
													'{"BuildingName": "Rufino Pacific Tower","MapLat": 14.55804,"MapLng": 121.01822}' + 
												']';
				<? } ?>
										/*
														'{"BuildingName": "One Global Place", "RoomName": "Meeting Room 1", "Address": "Level 10-1 One Global Place,25th Street & 5th Avenue,Bonifacio Global City, Taguig 1632 Philippines", "HoursStart": "8:00am", "HoursEnd": "5:00pm","CapacityMin": 1,"CapacityMax": 8,"MapLat": 14.54831,"MapLng": 121.04705}]}	
														*/
				console.log(dummyData);

				MeetingRoomArray = JSON.parse(dummyData);
				/*
					// Meeting Room Object
					var meetingRoomsObject = {};
					var meetingRoomsObjectArray = [];
					var MeetingRoom = function(e) {
						this.BuildingName = e.BuildingName;
						this.RoomName = e.RoomName;
						this.Address = e.Address;
						this.CapacityMin = e.CapacityMin;
						this.CapacityMax = e.CapacityMax;
						this.HoursStart = e.HoursStart;
						this.HoursEnd = e.HoursEnd;
						this.MapLat = e.MapLat;
						this.MapLng = e.MapLng;

						this.push(meetingRoomsObjectArray);
					}
					*/

					// Create Map
					var map = new GMaps({
							el: '#map',
							lat: '<?php echo $map_area_lat; ?>',
							lng: '<?php echo $map_area_lng; ?>',
							scrollwheel: false
					});

					// Map Bound
					var bounds = [];

					generateMap(MeetingRoomArray);

					function generateMap(mapData) {
						console.log(mapData);
						console.log(mapData.length);

						for (i = 0; i < mapData.length; i++) {

							// Set Bound Marker
							var latlng = new google.maps.LatLng(mapData[i].MapLat, mapData[i].MapLng);
							bounds.push(latlng);

							// Add Marker
							map.addMarker({
									lat: mapData[i].MapLat,
									lng: mapData[i].MapLng,
									title: mapData[i].BuildingNAme,
									infoWindow: {
											content: '<p>' + mapData[i].BuildingName + '</p>'
									}
							});
						}
					}

					// Fit All Marker to map 
					map.fitLatLngBounds(bounds);

			});
			
			
	$( function() {
  // quick search regex
  var qsRegex;
  
  // init Isotope
  var $grid = $('.iso-grid').isotope({
    itemSelector: '.iso-element-item',
		layoutMode: 'fitRows',
    filter: function() {
      return qsRegex ? $(this).text().match( qsRegex ) : true;
    }
  });

  // use value of search field to filter
  var $quicksearch = $('.quicksearch').keyup( debounce( function() {
    qsRegex = new RegExp( $quicksearch.val(), 'gi' );
    $grid.isotope();
  }, 200 ) );
  
});

// debounce so filtering doesn't happen every millisecond
function debounce( fn, threshold ) {
  var timeout;
  return function debounced() {
    if ( timeout ) {
      clearTimeout( timeout );
    }
    function delayed() {
      fn();
      timeout = null;
    }
    timeout = setTimeout( delayed, threshold || 100 );
  }
}



		</script>			
	</body>
</html>
