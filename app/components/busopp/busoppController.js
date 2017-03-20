(function() {

	angular
		.module('entreprenityApp.bussOpp', [])
		
		.factory('businessOpportunityService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				getLatestEvents:function() {
					return $http.get(baseUrl + 'getLatestEvents');
				},
				getBasicUserInfo:function() {
					return $http.get(baseUrl + 'getBasicUserInformation');
				}
			};
		})
		
		.controller('BusinessOpportunityController', function($scope, $routeParams, businessOpportunityService, AuthService) {
				var vm = this;
				vm.isReloadMatched = false;
				vm.isReloadAll = false;
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
					businessOpportunityService.getUserSessInfo().success(function(data) {
						vm.id 			= data.id;
						//vm.userName 	= data.username;
					});
					
					//to get latest events
					businessOpportunityService.getLatestEvents().success(function(data) {
						vm.latestEvents = data;
					});
					
					//to get basic user information
					businessOpportunityService.getBasicUserInfo().success(function(data) {
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

			$scope.toggleMenu = function() {
				$scope.isActive = !$scope.isActive;
				$scope.isSlide = !$scope.isSlide;
			}
			
			$scope.triggerNextPage = function () 
			{
				if (vm.activeTab == 0) 
				{					
					vm.isTriggerNextPageMatchedBO = true;					
				} 
				else if (vm.activeTab == 1) 
				{					
					vm.isTriggerNextPageOtherBO = true;					
				} 
			}

			$scope.$watch('vm.activeTab', function() {
				//alert('active tab changed' + vm.activeTab);
				if (vm.activeTab == 0)
				{
					vm.isReloadMatched = true;
				}
				else if (vm.activeTab == 1)
				{
					vm.isReloadAll = true;
				}
			});

		});
})();
