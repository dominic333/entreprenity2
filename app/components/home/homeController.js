(function() {

	angular
		.module('entreprenityApp.home', [])
		
		.factory('myHomeService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				getNewMembers:function() {
					return $http.get(baseUrl + 'getNewMembers');
				},
				getLatestEvents:function() {
					return $http.get(baseUrl + 'getLatestEvents');
				},
				getBasicUserInfo:function() {
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				getTopContributors:function() {
					return $http.get(baseUrl + 'getTopContributors');
				},
				recommendedBusinessOpportunities:function() {
					return $http.get(baseUrl + 'recommendedBusinessOpportunities');
				}
			};
		})
		
		.controller('HomeController', function($scope, $routeParams, myHomeService, AuthService, settingsService) {
			var vm = this;
			//If user is not logged in
			var token;
			if (localStorage['entrp_token'])
			{
				token = JSON.parse(localStorage['entrp_token']);
			}
			else
			{
				token = "";
			}

			if(token)
			{
				AuthService.checkToken(token);

				//To get user session value
				myHomeService.getUserSessInfo().success(function(data) {
					vm.id 			= data.id;
					//vm.userName 	= data.username;
				});

				//To get new members
				myHomeService.getNewMembers().success(function(data) {
					vm.newMembers = data;
				});

				//to get latest events
				myHomeService.getLatestEvents().success(function(data) {
					vm.latestEvents = data;
				});

				//to get top contributors
				myHomeService.getTopContributors().success(function(data) {
					vm.topContributors = data;
				});

				//to get recommended business opportunities
				myHomeService.recommendedBusinessOpportunities().success(function(data) {
					vm.businessOpportunities = data;
				});

				//to get basic user information
				myHomeService.getBasicUserInfo().success(function(data) {
					vm.firstName 	= data.firstName;
					vm.lastName 	= data.lastName;
					vm.position 	= data.position;
					vm.myOffice 	= data.myOffice;
					vm.avatar 		= data.avatar;
					vm.userName 	= data.userName;
					vm.companyUserName 	= data.companyUserName;

					$scope.userName= data.userName;
					//$scope.$apply()
				});
			}



			$scope.logout = function(){
				var data = {
					token: token
				}
				AuthService.logOut(token);
			}

			$scope.toggleMenu = function() 
			{
				$scope.isActive = !$scope.isActive;
				$scope.isSlide = !$scope.isSlide;
			}

			$scope.triggerNextPage = function () {
				if (vm.activeTab == 0) 
				{
					vm.isTriggerNextPageAll = true;					
				} 
				else if (vm.activeTab == 1) 
				{
					vm.isTriggerNextPageFollowed = true;					
				} 
				else if (vm.activeTab == 2) 
				{
					vm.isTriggerNextPageMy = true;					
				}
			}
			
			//Function to check facebook linked or not
			settingsService.checkFBConnectedorNot().success(function(response) {
				vm.FBConnected = (response == 'connected' ? true : false);
			});

			$scope.$watch('vm.activeTab', function() {
				//alert('active tab changed' + vm.activeTab);
				if (vm.activeTab == 0)
				{
					vm.isReloadAll = true;
				}
				else if (vm.activeTab == 1)
				{
					vm.isReloadFollowed = true;
				}
				else if (vm.activeTab == 2)
				{
					vm.isReloadMy = true;
				}
			});
		});
})();
