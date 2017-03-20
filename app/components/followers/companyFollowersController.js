(function() {
	angular
		.module('entreprenityApp.companyFollowers', [])

		.factory('companyFollowersService', function($http) {
		var baseUrl = 'api/';
		return {
			getCompanyFollowers: function(companyUserName) 
			{
				return $http.get(baseUrl+ 'getCompanyFollowers?company='+companyUserName);
			}			
		};
	})

		.controller('CompanyFollowersController', function($routeParams, companyFollowersService) {
		var vm = this;
		vm.companyUserName = $routeParams.companyUserName;

		companyFollowersService.getCompanyFollowers(vm.companyUserName).success(function(data) {
			vm.company = data;
		});
	});			
})();


