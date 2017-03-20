(function() {
	angular
		.module('entreprenityApp.memberFollowing', [])

		.factory('memberFollowingService', function($http) {
		var baseUrl = 'api/';
		return {
			getMemberFollowing: function(userName) 
			{
				return $http.get(baseUrl+ 'getMemberFollowing?user='+userName);
			}			
		};
	})

	.controller('MemberFollowingController', function($routeParams, memberFollowingService) {
		var vm = this;
		vm.memberUserName = $routeParams.memberUserName;

		memberFollowingService.getMemberFollowing(vm.memberUserName).success(function(data) {
			vm.member = data;
		});
		
	});			
})();