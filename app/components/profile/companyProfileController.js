(function() {

	angular
		.module('entreprenityApp.companyProfile', [])

		.factory('companyProfileService', function($http) {

		var baseUrl = 'api/';
		return {
			getCompanyProfile: function(id) {
				return $http.get(baseUrl+ 'view_company_profile?id='+id);
			},
			getCompanyEvents: function(companyUserName) {
				return $http.get(baseUrl+ 'getEventsHostedByCompany?id='+companyUserName);
			},
			postCompanyFollow: function(companyUserName) 
			{
				var dataContent = {
		            'company' : companyUserName
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'followThisCompany',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			},
			postCompanyUnFollow: function(companyUserName) 
			{
				var dataContent = {
		            'company' : companyUserName
		        };
		        
				return $http({ method: 'post',
								url: baseUrl+'unfollowThisCompany',
								data: $.param(dataContent),
								headers: {'Content-Type': 'application/x-www-form-urlencoded'}
							});
			}
			//postCompanyFollow,
			//postCompanyUnFollow
		};
	})

		.controller('CompanyProfileController', function($routeParams, companyProfileService,$scope) {
		var vm = this;
		vm.companyUserName = $routeParams.companyUserName;
		
		companyProfileService.getCompanyProfile(vm.companyUserName).success(function(data) {
			vm.company = data;
		});
		
		//when user click follow, will post data to follow member and update in backend
		vm.follow_this_company = function(companyUserName) {
			companyProfileService.postCompanyFollow(companyUserName).success(function(data) {
				vm.company = data; //return company info, with updated followers and followed status
			});	
		};

		//when user click unfollow, will post data to unfollow member and update in backend
		vm.unFollow_this_company = function(companyUserName) {
			companyProfileService.postCompanyUnFollow(companyUserName).success(function(data) {
				vm.company = data; //return company info, with updated followers and followed status
			});	
		};
		
		$scope.triggerNextPage = function () 
		{
			vm.isTriggerNextUserCompanyPosts = true;
		}
		
		//Fetch events hosted by this company
		/*
		companyProfileService.getCompanyEvents(vm.companyUserName).success(function(data) {
			vm.company = data;
		});
		*/
	});			
})();
