(function() {

angular
	.module('entreprenityApp.imgEventPoster', [])

	.factory('eventPosterService', function($http) {
		var baseUrl = 'api/';
		
		return {
			getBasicUserInfo:function()
			{
				return $http.get(baseUrl + 'getBasicUserInformation');
			},
			getUserSessInfo: function() {
				return $http.get(baseUrl+ 'get_user_session');
			},
			finshEvent: function(eventTag) 
			{
				$http.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';			
				data = {
		            'eventTag' : eventTag
		        };

	        return $http.post(baseUrl+'finishThisEvent', data)
	        .success(function(data, status, headers, config)
	        {
	            console.log(status + ' - ' + data);
	        })
	        .error(function(data, status, headers, config)
	        {
	            console.log('error');
	        });
			}			
		};
	})

	.controller('eventPosterController', function($routeParams, eventPosterService, $scope, $uibModal,$http,$location) {
		var vm = this;
		vm.eventTagID = $routeParams.eventTag;
		$scope.eventTag=$routeParams.eventTag;
		
		vm.open = function () {		
			var modalInstance = $uibModal.open({
				animation: $scope.animationsEnabled,
				templateUrl: 'app/components/modal/imageUpload.html',
				controller: 'ImageUploadController',
				resolve: {
					id: function () 
					{
						return 3;
					}
				}
			});
			
			modalInstance.result.then(function (myCroppedImage) {
				vm.poster = myCroppedImage;
				
			}, function () {
				$log.info('Modal dismissed at: ' + new Date());
			});
		};


		vm.finshEvent = function() {
			eventPosterService.finshEvent(vm.eventTagID).success(function(data) {
				$location.path('/eventPlaced');
			});	
		};

	
	});
})();
