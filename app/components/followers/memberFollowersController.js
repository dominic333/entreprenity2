(function() {
	angular
		.module('entreprenityApp.memberFollowers', [])

		.factory('memberFollowersService', function($http) {
			var baseUrl = 'api/';
			return {
				getMemberFollowers: function(userName) 
				{
					return $http.get(baseUrl+ 'getMemberFollowers?user='+userName);
				}			
			};
		})

		.controller('MemberFollowersController', function($routeParams, memberFollowersService) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
		
		
			memberFollowersService.getMemberFollowers(vm.memberUserName).success(function(data) {
				vm.member = data;
			});
		});			
})();

