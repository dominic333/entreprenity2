(function() {

	angular
		.module('entreprenityApp.common', ['ngRoute'])
		
		.factory('myCommonService', function($http) {
			var baseUrl = 'api/';
			return {
				getBasicUserInfo:function() {
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				getAllUnreadNotifications:function() {
					return $http.get(baseUrl + 'getAllUnreadNotifications');
				}
			};
		})
		
		.controller('CommonController', function($scope, $routeParams, myCommonService, AuthService,AuthService3,$rootScope) {
				var vm = this;			

			    var update = function() 
			    {
			        myCommonService.getAllUnreadNotifications().success(function(data) {
					
						$scope.numOfNotifications = data.totalUnread;// need a service that will store the number of unred notif to this variable
						});
			        //$scope.commonId=10;
			        //console.log($scope.commonId);
			    };
			    
			    if (localStorage['entrp_token'])
				 {
			     update();
			    }
			
				 $scope.$on('$routeChangeSuccess', update);	
			   

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
					//to get basic user information
					myCommonService.getBasicUserInfo().success(function(data) {
						vm.id 			= data.id;
						vm.firstName 	= data.firstName;
						vm.lastName 	= data.lastName;
						vm.position 	= data.position;
						vm.myOffice 	= data.myOffice;
						vm.avatar 		= data.avatar;
						vm.userName 	= data.userName;
						vm.companyUserName 	= data.companyUserName;
						
						$scope.userName 			= data.userName;
						$scope.companyUserName 	= data.companyUserName;
					});

				}
				
				
				$scope.logout = function(){
					var data = {
						token: token
					}
					AuthService.logOut(token);
				}
				/*
				$rootScope.$on("$routeChangeStart", function (event, next, current) 
				{
               if (next && next.$$route && next.$$route.loggedIn) {
               */ 
               /*	
				$rootScope.$on('$routeChangeSuccess', function (event, current, previous) 
				{
					if ($route.current.$$route.loggedIn) 
					{
						AuthService3.fetchUnreadNotifications().then(function (res)
				      {
				      	//console.log(res.data.totalUnread);
				      	$scope.numOfNotifications =res.data.totalUnread;
				      });
					}
				  }
				);
				*/
				
				/*
				if(localStorage['notifications'])
				{
					$scope.numOfNotifications = localStorage['notifications'];
				}
				else
				{
					$scope.numOfNotifications = 0;
				}
				*/
				//$scope.numOfNotifications = notificationCount;
				/*
				myCommonService.getAllUnreadNotifications().success(function(data) {
					
						$scope.numOfNotifications = data.totalUnread;// need a service that will store the number of unred notif to this variable
				});
				*/
				
				
		});
	
		

})();
