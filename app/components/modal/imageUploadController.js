(function() {

	angular
		.module('entreprenityApp.imageUpload', [])

		.factory('imageUploadService', function($http) {
			var baseUrl = 'api/';
			
			return {		
				uploadMemberAvatar: function(id,userImg) 
				{
					$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';			
					data = {
			            'uploadType' : id,
			            'uploadImg'  : userImg
			        };

		        return $http.post(baseUrl+'update_member_avatar', data)
		        .success(function(data, status, headers, config)
		        {
		            console.log(status + ' - ' + data);
		        })
		        .error(function(data, status, headers, config)
		        {
		            console.log('error');
		        });
				},
				getBasicUserInfo:function()
				{
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				getMemberPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getMembersPost?user='+username);
				},	
				getCompanyPosts: function(username) 
				{
					return $http.get(baseUrl+ 'getCompanyPosts?company='+username);
				}
			};
		})

		.controller('ImageUploadController', function($routeParams,imageUploadService,$scope, $uibModalInstance, id , $timeout) {
			$scope.id = id;
			$scope.myImage='';
			$scope.myCroppedImage='';
			var vm = this;
			
			var myPost = {
					"post_id": "",
					"content": "",
					"image": "",
					"created_at": "",
					"post_author": {
						"id": "",
						"firstName": "",
						"lastName": "",
						"avatar": "",
						"position": "",
						"companyName": "",
						"userName": ""
					},
					"isLiked": false,
					"likes_count": 0,
					"likers": [],
					"comments_count": 0,
					"commenters": [],
					"comments": []
				};

			vm.currentPost = myPost;

			$scope.progressValue = 0;
			$scope.progressMax = 100;
			$scope.showImage = false;

			$scope.clickBrowseImage = function(){
				angular.element('#fileInput').trigger('click');
			};
			

			$scope.handleFileSelect = function(evt){
				
				var file = evt.files[0];
				var reader = new FileReader();
				var maxSize = 5e+6;

				console.log(file);
				if(evt.files.length > 1) {
					alert("You can select only 2 images");
				}else {
					if(file.size>maxSize){
						//alert('file size is more than ' + maxSize + ' bytes');
						return false;
					}else{
						//alert('file size is correct - ' + file.size + ' bytes');
						reader.onload = function (evt) {
							$scope.$apply(function($scope){
								$scope.myImage = evt.target.result;
							});
						};
						reader.readAsDataURL(file);
						$scope.progressValue = 100;
						$timeout(function(){
							$scope.showImage = true;
						}, 1000);
					}
				}
			}
			
			//To upload a profile picture
			$scope.ok = function () {
				//create a service to update the profile photo using $scope.id
				//when user click save, will post data to update in backend
				if($scope.myCroppedImage)
				{
					imageUploadService.uploadMemberAvatar($scope.id,$scope.myCroppedImage).success(function(data) {
						
						$scope.myImage= data;


					});	
				}

				//alert($scope.myCroppedImage);
				console.log($scope.myImage);
				console.log($scope.myCroppedImage);
				$uibModalInstance.close($scope.myCroppedImage);
			};

			$scope.cancel = function () {
				$uibModalInstance.dismiss('cancel');
			};

			$scope.okPost = function () {
				//create a service to update the profile photo using $scope.id
				//when user click save, will post data to update in backend
				if($scope.myImage) //alert('image uploaded');
				$uibModalInstance.close($scope.myImage);
			};
			
		});
})();