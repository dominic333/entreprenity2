(function() {

	angular
		.module('entreprenityApp.callanswering', [])
		
		.factory('callAnswerService', function($http) {
			var baseUrl = 'api/';
			return {
				getUserSessInfo: function() {
					return $http.get(baseUrl+ 'get_user_session');
				},
				invokeCallAnswering: function(token) 
				{
					var dataContent = {
			            'token' : token
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'invokeCallAnswering',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				}
			};
		})
		.controller('CallAnswerController', function($scope, $http, $location,callAnswerService) {
			var vm = this;
			
			var token=localStorage['entrp_token'];

			callAnswerService.invokeCallAnswering(token).success(function(data) {
				//vm.locations = data;
				//console.log(data);
				vm.data='http://callanswering.me/app/authVerify.php?auth='+data;
				if(data !='failed')
				{		
					window.open('http://callanswering.me/app/authVerify.php?auth='+data, '_blank');	
				}
			});
			
			
			
		});
	
})();