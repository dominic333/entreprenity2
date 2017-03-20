(function() {

angular
	.module('entreprenityApp.myProfile', [])

	.factory('myProfileService', function($http) {
		var baseUrl = 'api/';
		
		return {
			getMemberProfile: function(id) {
				return $http.get(baseUrl+ 'get_my_details');
			},			
			postMemberProfile: function(userdata) 
			{
				return $http({ method: 'post',
									url: baseUrl+'update_my_profile',
									data: $.param(userdata),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
			},
			getBasicUserInfo:function()
			{
				return $http.get(baseUrl + 'getBasicUserInformation');
			},
			getUserSessInfo: function() {
				return $http.get(baseUrl+ 'get_user_session');
			},
			getMemberPosts: function(username) 
			{
				return $http.get(baseUrl+ 'getmyMembersPost');
			},
            getBasicUserInfo:function() 
            {
					return $http.get(baseUrl + 'getBasicUserInformation');
            }
			
		};
	})

	.controller('MyProfileController', function($routeParams, myProfileService, $scope, $uibModal, $http) {
		var vm = this;
	
		vm.open = function () {		
			var modalInstance = $uibModal.open({
				animation: $scope.animationsEnabled,
				templateUrl: 'app/components/modal/imageUpload.html',
				controller: 'ImageUploadController',
				resolve: {
					id: function () 
					{
						return 1;
					}
				}
			});
			
			//$scope.numOfNotifications = notificationCount;			
			
			modalInstance.result.then(function (myCroppedImage) {
				vm.member.avatar = myCroppedImage;

				myProfileService.getBasicUserInfo().success(function(data) {
					/*
					vm.currentPost.post_author.id 	= data.id;
					vm.currentPost.post_author.firstName 	= data.firstName;
					vm.currentPost.post_author.lastName 	= data.lastName;
					vm.currentPost.post_author.position 	= data.position;
					vm.currentPost.post_author.companyName 	= data.companyName;
					vm.currentPost.post_author.avatar 		= data.avatar;
					vm.currentPost.post_author.userName 	= data.userName;
					vm.currentPost.post_author.companyUserName 	= data.companyUserName;
					*/
					//vm.getPosts(1,data.userName);
				});


			}, function () {
				$log.info('Modal dismissed at: ' + new Date());
			});
		};
	
		//vm.memberUserName = "";
		//vm.memberUserName = $routeParams.memberUserName;
		vm.editState = false;
		
		//get initial data
		myProfileService.getMemberProfile(vm.memberUserName).success(function(data) {
			vm.member = data;
			console.log(vm.member);
		});	

	
		//when user click save, will post data to update in backend
		vm.updateData = function(userdata) {
			myProfileService.postMemberProfile(userdata).success(function(data) {
				vm.member = data;
				vm.editState = false;
				vm.isReloadAll = true;
			});	
		};
		
		//when user click cancel, will reload data and cancel all changes to the model
		vm.reloadData = function() {
			myProfileService.getMemberProfile(vm.memberUserName).success(function(data) {
				vm.member = data;
				vm.editState = false;
			});	
		};
		
		$scope.triggerNextPage = function () 
		{
			vm.isTriggerNextmyProfilePosts = true;
		}



	});
})();
