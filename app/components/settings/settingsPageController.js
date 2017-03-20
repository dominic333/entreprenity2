(function() {

	angular
		.module('entreprenityApp.settings', [])

		.factory('settingsService', function($http) {
			var baseUrl = 'api/';

			return {	
				getSettings: function(userName) 
				{
					return $http.get(baseUrl+ 'getMyPreferences');
				},
				postSettings: function(userSettings) 
				{
					var dataContent = userSettings;
			        
					return $http({ 
										method: 'post',
										url: baseUrl+'updateMyPreferences',
										data: $.param(dataContent),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							 });
				},
				linkFacebookAccount: function(response,response2) 
				{
					var dataContent = {
			            'fid' 			: response.id,
			            'first_name' 	: response.first_name,
			            'last_name' 	: response.last_name,
			            'gender' 		: response.gender,
			            'email' 			: response.email,
			            'fbImage' 		: response2.data.url
			        };
			        
					return $http({ 
										method: 'post',
										url: baseUrl+'saveFacebookAuthData',
										data: $.param(dataContent),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							 });
				},
				unlinkFacebookAccount: function() 
				{
					return $http.get(baseUrl+ 'unlinkFacebookAccount');
				},
				checkFBConnectedorNot: function() 
				{
					return $http.get(baseUrl+ 'checkFBConnectedorNot');
				},
				saveFacebookIdandAuthorize: function(fbId) 
				{
					var dataContent = {
			            'fid' 			: fbId
			        };			        
					return $http({ 
										method: 'post',
										url: baseUrl+'saveFacebookIdandAuthorize',
										data: $.param(dataContent),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							 });
				},
				changeQRCode: function() 
				{
					return $http.get(baseUrl+ 'changeQRCode');
				},
				fetchQRCodeUpdatedTime: function() 
				{
					return $http.get(baseUrl+ 'fetchQRCodeUpdatedTime');
				}
			};
		})

		.controller('SettingsPageController', function($routeParams, settingsService,$scope) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
		
			//dummy data (sample json)
			/*data = {
				"languages": [
					{"id": "0", "text": "EN", "image": "en.jpg"},
					{"id": "1", "text": "ID", "image": "id.jpg"},
					{"id": "2", "text": "MS", "image": "ms.jpg"},
					{"id": "3", "text": "ZH", "image": "zh.jpg"}
				],
				"selectedLanguage": {"id": "0", "text": "EN", "image": "en.jpg"},
				"timezones": [
					{"id": "0", "text": "SGT"},
					{"id": "1", "text": "IDT"},
					{"id": "2", "text": "EST"},
					{"id": "3", "text": "CET"}
				],
				"selectedTimezone": {"id": "0", "text": "SGT"},
				"followers": true,	
				"comments": true,
				"likes": true,
				"mentions": false,
				"businessOpportunities": false
			}
			vm.settings = data;*/
		
			//sample get service, this will replace the dummy data once the settings data is available in the db already
			
			settingsService.getSettings(vm.memberUserName).success(function(data) {
				vm.settings = data;
			});	
			
		
			//when user clicks SAVE SETTINGS, vm.settings object will be passed in a service to update settings in the db
			vm.submitSettings = function() {
				console.log(vm.settings);
				//sample post service
				
				settingsService.postSettings(vm.settings).success(function(data) {
					vm.settings = data;
				});	
				
			};

			
			//Function to check facebook linked or not
			settingsService.checkFBConnectedorNot().success(function(data) {
				vm.FBConnect = data;
			});
			
			
			//Function to fetch last Updated Time of QR Code
			settingsService.fetchQRCodeUpdatedTime().success(function(data) {
				vm.lastUpdated = data.lastUpdatedAt;
			});
			
			//Function to change user QR Code	      
	      $scope.changeQRCode = function() {
				settingsService.changeQRCode().success(function(data) {
					vm.lastUpdated = data.lastUpdatedAt;
				});	
			};
			
			//Function to unlink a facebook profile	      
	      $scope.FBUnlink = function() {
				settingsService.unlinkFacebookAccount().success(function(data) {
					vm.FBConnect = data;
				});	
			};
			
			//Link a facebook profile				
			$scope.FBConnect= function()
			{
				//new code here
				FB.getLoginStatus(function(response) 
				{
				  //console.log(response);
				
				  if (response.status === 'connected') 
				  {
				    // the user is logged in and has authenticated your
				    // app, and response.authResponse supplies
				    // the user's ID, a valid access token, a signed
				    // request, and the time the access token 
				    // and signed request each expire
				    var uid = response.authResponse.userID;
				    var accessToken = response.authResponse.accessToken;
				    
				    settingsService.saveFacebookIdandAuthorize(uid).success(function(data) {
						 vm.FBConnect = data;
					 });
				  } 
				  else if (response.status === 'not_authorized') 
				  {
				      // the user is logged in to Facebook, 
				      // but has not authenticated your app
				      FB.login(function(response) 
						{
						    if (response.authResponse) 
						    {
						     FB.api('/me?fields=email,first_name,last_name,gender', function(response) {
						       
						       if(response.id)
						       {
						       	var accessToken=FB.getAuthResponse();
						       	//console.log(accessToken);				       	
						       	FB.api("/"+response.id+"/picture?type=large",function (response2) {
									      if (response2 && !response2.error) 
									      {
									        var imageUrl=response2.data.url;
									        //console.log(imageUrl);
									      }
									      settingsService.linkFacebookAccount(response,response2).success(function(data) {
												vm.FBConnect = data;
											});
									 });							
						       }	
						       });	
						     }		       				       
						});
				  } 
				  else 
				  {
				    // the user isn't logged in to Facebook.
				    
				     FB.login(function(response) 
					  {
						    if (response.authResponse) 
						    {
						     FB.api('/me?fields=email,first_name,last_name,gender', function(response) {
						       
						       if(response.id)
						       {
						       	var accessToken=FB.getAuthResponse();
						       	//console.log(accessToken);				       	
						       	FB.api("/"+response.id+"/picture?type=large",function (response2) {
									      if (response2 && !response2.error) 
									      {
									        var imageUrl=response2.data.url;
									        //console.log(imageUrl);
									      }
									      settingsService.linkFacebookAccount(response,response2).success(function(data) {
												vm.FBConnect = data;
											});
									    });							
						        }  				       				       
						      });
				           }
				  
				      });				
				
			       }
			
				});
							
			};
			
		});
})();
