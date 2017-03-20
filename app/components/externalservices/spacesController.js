(function() {

	angular
		.module('entreprenityApp.spaces', [])
		
		.factory('spacesService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				invokeSpaces: function(token) 
				{
					var dataContent = {
			            'token' : token
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'invokeSpaces',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				}
			};
		})
		.controller('SpacesController', function($scope, $http, $location,spacesService) {
			var vm = this;
			
			var token=localStorage['entrp_token'];

			spacesService.invokeSpaces(token).success(function(data) {
				//vm.locations = data;
				//console.log(data);
				//vm.data='http://entreprenity.co/app/others/spaceAuthVerify.php?auth='+data;
				vm.data='http://myvoffice.me/spaces/spaceAuthVerify.php?auth='+data;
				if(data !='failed')
				{	
					//http://entreprenity.co/spaces/login.html				
					window.open('http://myvoffice.me/spaces/spaceAuthVerify.php?auth='+data, '_blank');	
					//window.open('http://entreprenity.co/app/others/spaceAuthVerify.php?auth='+data, '_blank');	
				}
			});
						
		});
	
})();