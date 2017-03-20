(function() {

	angular
		.module('entreprenityApp.memberProfile', [])

		.factory('memberProfileService', function($http) {
		
			var baseUrl = 'api/';
			return {
				getMemberProfile: function(id) 
				{
					return $http.get(baseUrl+ 'view_user_profile?id='+id);
				},
				postMemberFollow: function(memberUserName) 
				{
					var dataContent = {
			            'user' : memberUserName
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'followThisUser',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				postMemberUnFollow: function(memberUserName) 
				{
					var dataContent = {
			            'user' : memberUserName
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'unfollowThisUser',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				}				
				
			};
		})

		.controller('MemberProfileController', function($routeParams, memberProfileService,$scope) {
			var vm = this;
			vm.memberUserName = $routeParams.memberUserName;
			
			
			memberProfileService.getMemberProfile(vm.memberUserName).success(function(data) {
				vm.member = data;
			});	
		
			//when user click follow, will post data to follow member and update in backend
			vm.follow_this_member = function(memberUserName) {
				memberProfileService.postMemberFollow(memberUserName).success(function(data) {
					//vm.member = data; //return user_info, with updated followers and followed status
					vm.member.followed= data.followed;
				});	
			};

			//when user click unfollow, will post data to unfollow member and update in backend
			vm.unFollow_this_member = function(memberUserName) {
				memberProfileService.postMemberUnFollow(memberUserName).success(function(data) {
					//vm.member = data; //return user_info, with updated followers and followed status
					vm.member.followed= data.followed;
				});	
			};
			
			$scope.triggerNextPage = function () 
			{
				vm.isTriggerNextUserProfilePosts = true;
			}
		
		});			
})();


