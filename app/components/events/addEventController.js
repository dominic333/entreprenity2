(function() {

    angular
        .module('entreprenityApp.addEvent', [])
        
        .factory('addEventService', function($http) {
            var baseUrl = 'api/';

            return {
            //services
            getEventCatgories:function() {
					return $http.get(baseUrl + 'getAllEventCatgories');
				},
            };
        })

        .controller('addEventController', function($routeParams, addEventService, eventPosterService, $http, $scope,$location) {

			  var vm = this;
			  var map;
			  var marker;
			  var latLngC;
			  
			  initialize();
				
			  function initialize() 
			  {
			    latLngC = new google.maps.LatLng(14.5800, 121.0000);
			    var mapOptions = {
			      center: latLngC,
			      zoom: 12,
			      mapTypeId: google.maps.MapTypeId.ROADMAP,
			    };
			
			    map = new google.maps.Map(document.getElementById('source_map'),
			      mapOptions);
			
			    var marker = new google.maps.Marker({
			      position: latLngC,
			      map: map,
			      draggable: true
			    });
			
			
				google.maps.event.addListener(marker, 'dragend', function (x) 
        		{
            	//document.getElementById('src_lat').value = x.latLng.lat();
            	//document.getElementById('src_long').value = x.latLng.lng();
            	$scope.$apply(function() 
					{
			        $scope.vm.src_lat = x.latLng.lat();
			        $scope.vm.src_long = x.latLng.lng();
			      });
            	document.getElementById('pickup_location').innerHTML = x.latLng.lat()+' , '+x.latLng.lng();
			  		var geocoder = new google.maps.Geocoder;
  					var infowindow = new google.maps.InfoWindow;	            
            	geocodeLatLng(geocoder, map, infowindow,x.latLng.lat(),x.latLng.lng(),'source_point');
        		}); 
        		
        		//Get coordinates,address Upon clicking a location in map (Source Map)
        		google.maps.event.addListener(map, 'click', function(x) 
        		{
            	//document.getElementById('src_lat').value = x.latLng.lat();
            	//document.getElementById('src_long').value = x.latLng.lng();
            	$scope.$apply(function() 
					{
			        $scope.vm.src_lat = x.latLng.lat();
			        $scope.vm.src_long = x.latLng.lng();
			      });
            	document.getElementById('pickup_location').innerHTML = x.latLng.lat()+' , '+x.latLng.lng();
			  		var geocoder = new google.maps.Geocoder;
  					var infowindow = new google.maps.InfoWindow;	            
            	geocodeLatLng(geocoder, map, infowindow,x.latLng.lat(),x.latLng.lng(),'source_point');
        		});			
			
			    //Add marker upon clicking on map
			    //google.maps.event.addDomListener(map, 'click', addMarker);
			    google.maps.event.addDomListener(map, 'click', function() {
			      addMarker(map);
			    });
			
			    var places1 = new google.maps.places.Autocomplete(document.getElementById('source_point'));
			    google.maps.event.addListener(places1, 'place_changed', function() {
			      var place1 = places1.getPlace();
			
			      var src_addr = place1.formatted_address;
			      var src_lat = place1.geometry.location.lat();
			      var src_long = place1.geometry.location.lng();
			
					$scope.$apply(function() 
					{
			        $scope.vm.src_lat = src_lat;
			        $scope.vm.src_long = src_long;
			        $scope.vm.source_point = src_addr; 
			      });
			      //document.getElementById('src_lat').value = src_lat;
			      //document.getElementById('src_long').value = src_long;
			      document.getElementById('pickup_location').innerHTML = src_lat + ' , ' + src_long;
			    });
			    //Add marker upon place change          
			    google.maps.event.addDomListener(places1, 'place_changed', function() {
			      addMarker(map);
			    });
			
			  }
			  
			  google.maps.event.addDomListener(window, 'resize', initialize);
			  google.maps.event.addDomListener(window, 'load', initialize);
			
				//Function to add a marker on the map
			   function addMarker(map) 
	     		{
	         	//var lat = document.getElementById('src_lat').value;
	         	var lat = $scope.vm.src_lat;
	         	//var loong = document.getElementById('src_long').value;
	         	var loong = $scope.vm.src_long;
	         	
	     			if(!lat || !loong) return;
	
	     			var coordinate = new google.maps.LatLng(lat, loong);
	 
	 				if(marker) 
	 				{
	     				//if marker already was created change positon
	    				marker.setPosition(coordinate);
	    				map.setCenter(coordinate);
	  					map.setZoom(18);
	  						 
	     				google.maps.event.addListener(marker, 'dragend', function (x) 
	     				{
	         			//document.getElementById('src_lat').value = x.latLng.lat();
	         			//document.getElementById('src_long').value = x.latLng.lng();
	         			$scope.$apply(function() 
							{
			       			 $scope.vm.src_lat = x.latLng.lat();
			       			 $scope.vm.src_long = x.latLng.lng();
			      		});
	         			document.getElementById('pickup_location').innerHTML = x.latLng.lat()+' , '+x.latLng.lng();
			  				var geocoder = new google.maps.Geocoder;
	  							var infowindow = new google.maps.InfoWindow;	            
	         			geocodeLatLng(geocoder, map, infowindow,x.latLng.lat(),x.latLng.lng(),'source_point');
	     				});   						 
	 				} 
	 				else 
	 				{
	     				//create a marker
	     				marker = new google.maps.Marker({          
	         			position: coordinate,
	         			map: map,
	         			draggable: true
	     				});
	     				map.setCenter(coordinate);
	  					map.setZoom(18);
	  						
	     				google.maps.event.addListener(marker, 'dragend', function (x) 
	     				{
	         			//document.getElementById('src_lat').value = x.latLng.lat();
	         			//document.getElementById('src_long').value = x.latLng.lng();
	         			$scope.$apply(function() 
							{
			       			 $scope.vm.src_lat = x.latLng.lat();
			       			 $scope.vm.src_long = x.latLng.lng();
			      		});
	         			document.getElementById('pickup_location').innerHTML = x.latLng.lat()+' , '+x.latLng.lng();
			  				var geocoder = new google.maps.Geocoder;
	  							var infowindow = new google.maps.InfoWindow;	            
	         			geocodeLatLng(geocoder, map, infowindow,x.latLng.lat(),x.latLng.lng(),'source_point');
	     				});   						
	 				} 
	 			}


			 //To Calculate address from coordinates
			 function geocodeLatLng(geocoder, map, infowindow,latt,longg,addr_div) 
			 {
  					var latlng = {lat: parseFloat(latt), lng: parseFloat(longg)};
  					geocoder.geocode({'location': latlng}, function(results, status) 
  					{
    					if (status === google.maps.GeocoderStatus.OK) 
    					{
      					if (results[1]) 
      					{
      						$scope.$apply(function() 
								{
			       			 $scope.vm.source_point = results[1].formatted_address; 
			      			});
      						//document.getElementById(addr_div).value= results[1].formatted_address;     						
        						//infowindow.setContent(results[1].formatted_address);
        						//infowindow.open(map, marker);
      					} 
      					else 
      					{
        						window.alert('No results found');
     						}
    					} 
    					else 
    					{
      					window.alert('Geocoder failed due to: ' + status);
    					}
  				  });
			 }

				
				addEventService.getEventCatgories().success(function(data) {
					vm.categories = data;
				});
			
				//Function to validate add event form
				//April 15,2016
				$scope.getError = function(error, name)
				{
					if(angular.isDefined(error))
					{
						if(error.required && name == 'name')
						{
							return "Please enter an event name";
						}
						else if(error.required && name == 'eventCategory')
						{
							return "Please select an event category";
						}
						else if(error.required && name == 'description')
						{
							return "Please enter a description";
						}
						else if(error.required && name == 'eventDate')
						{
							return "Select a date";
						}
						else if(error.required && name == 'eventStartTime')
						{
							return "Select a start time";
						}
						else if(error.required && name == 'eventEndTime')
						{
							return "Select an end time";
						}
						else if(error.required && name == 'src_lat')
						{
							return "Latitude required";
						}
						else if(error.required && name == 'src_long')
						{
							return "Longitude required";
						}
						else if(error.required && name == 'source_point')
						{
							return "Enter event address";
						}
						else if(error.required && name == 'eventCity')
						{
							return "Enter City";
						}
				  }
			  }
			
				$scope.post = {};
				$scope.post.addEvent = [];
				$scope.index = '';
				var baseUrl = 'api/';
				
            // function to submit the form after all validation has occurred
            vm.addEvent = function(isValid)
            {
            	 //alert(vm.name);
            	 //alert(vm.eventCategory);
            	 //alert(vm.description);
            	 //alert(vm.eventDate);
            	 //alert(vm.eventStartTime);
            	 //alert(vm.eventEndTime);
            	 //alert(vm.source_point);
            	 //alert(vm.src_lat);
            	 //alert(vm.src_long);
            	 //alert(vm.eventCity);
                // check to make sure the form is completely valid
                if (isValid)
                {
                	 //alert('i am valid');
                	 //&& vm.eventStartTime && vm.eventEndTime
                	if(vm.name && vm.eventCategory && vm.description && vm.eventDate  && vm.source_point && vm.src_lat && vm.src_long && vm.eventCity)
                	{
                		var startTime = new Date(vm.eventStartTime);
							var eventstartTime = startTime.getHours()+':'+startTime.getMinutes()+':'+startTime.getSeconds();
							
							var endTime = new Date(vm.eventEndTime);
							var eventendTime = endTime.getHours()+':'+endTime.getMinutes()+':'+endTime.getSeconds();
							
							var event_poster = $('#event_poster_ars').val();
							
	                	var dataPost = 
	                			{
	                				eventName			: vm.name,
	                				eventCategory		: vm.eventCategory,
	                				eventDescription	: vm.description,
	                				eventDate			: vm.eventDate,
	                				eventStartTime		: eventstartTime,
	                				eventEndTime		: eventendTime,
	                				eventLocation		: vm.source_point,
	                				eventLocLat			: vm.src_lat,
	                				eventLocLong		: vm.src_long,
	                				eventCity			: vm.eventCity,
	                				poster				: event_poster
	                			
	                			};														
							$http({ method: 'post',
										url: baseUrl+'addNewEvent',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									})
									.success(function(data, status, headers, config) 
								   {console.log(data);
								    	if(data.eventToken)
								    	{
								    		var eT=data.eventToken;
								    		
								    		//$location.path('/addEventPoster/'+eT);
								    		eventPosterService.finshEvent(eT).success(function(data) {
												$location.path('/eventPlaced');
											});
								    	}
								    	else
								    	{
								    		alert('Something went wrong. Please try again');
								    	}
						    	 }).
						    	 error(function(data, status, headers, config) 
						    	 {
						    			alert('Something went wrong. Please try again');
						    	 });                		                	
                	}
                	else
                	{
                		alert('Please set event start and end time');
                	}
                }
            };

            // Date Picker Objects and Functions (angular ui bootstrap datepicker)
			$scope.today = function() {
				vm.eventDate = new Date();
			};
			$scope.today();

			$scope.clear = function() {
				vm.eventDate = null;
			};

			// Disable weekend selection
			$scope.disabled = function(date, mode) {
				return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
			};

			$scope.toggleMin = function() {
				$scope.minDate = $scope.minDate ? null : new Date();
			};

			$scope.toggleMin();
			$scope.maxDate = new Date(2020, 5, 22);
			// popup open function
            $scope.open1 = function() {
                $scope.popup1.opened = true;
            };
			// popup object
            $scope.popup1 = {
                opened: false
            };
			$scope.dateDisplayFormat = 'dd-MMMM-yyyy';
			
			/*
			// Time Range Objects and Functions (angular dateTimeRangePicker)
			// show only time slider
			// config for timerangepicker
			$scope.timeRangePicker = {
				time: {
					from: 480, // default low value
					to: 1020, // default high value
					step: 15, // step width
					minRange: 15, // min range
					hours24: true // true for 24hrs based time | false for PM and AM
				}
			};
			// update vm start and end time on timerange change
			$scope.whenTimeChange = function (data) {
				console.log('schedule changes', data);
				//vm.eventStartTime = $scope.timeRangePicker.time.from;
				vm.eventStartTime = data.from;
				//vm.eventEndTime = $scope.timeRangePicker.time.to;
				vm.eventEndTime = data.to;
			};
			*/

			// Time Picker Objects and Functions (angular ui bootstrap datepicker)
			var now = new Date();
			var oneHourLater = new Date(now.getTime() + (1*1000*60*60));

			vm.eventStartTime = now;
			vm.eventEndTime = oneHourLater;

			$scope.options = {
				hstep: [1, 2, 3],
				mstep: [1, 5, 10, 15, 25, 30]
			};

			$scope.ismeridian = true;
			$scope.toggleMode = function() {
				$scope.ismeridian = ! $scope.ismeridian;
			};

			$scope.update = function() {
				var d = new Date();
				d.setHours( 14 );
				d.setMinutes( 0 );
				$scope.mytime = d;
			};

			$scope.changed = function () {
				//$log.log('Time changed to: ' + $scope.mytime);
			};

			$scope.clear = function() {
				vm.eventStartTime = null;
				vm.eventEndTime = null;
			};
			
			$scope.myImage='';
		    $scope.myCroppedImage='';
		
		    var handleFileSelect=function(evt) {
		      var file=evt.currentTarget.files[0];
		      var reader = new FileReader();
		      reader.onload = function (evt) {
		        $scope.$apply(function($scope){
		          $scope.myImage=evt.target.result;
		        });
		      };
		      reader.readAsDataURL(file);
		    };
		    angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
    
    
        });
})();