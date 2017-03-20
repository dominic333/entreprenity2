(function() {

	angular
		.module('entreprenityApp.scanner', ['webcam','bcQrReader'])
		
		.factory('scannerService', function($http) {
			var baseUrl = 'api/';
			
			return {	
				viewThisItem: function(item) 
				{
					var dataContent = {
			            'item' : item
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'viewThisItem',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				}
			};			
		})
		
		.controller('qrCrtl', function($scope, $http, $location,scannerService) {
			
		 	  var vm = this;	
		 	 
		 	  $scope.cameraRequested = true;
			  $scope.processURLfromQR = function (item) {
			    scannerService.viewThisItem(item).success(function(data) {
									if(data=='valid')
									{
										$location.path('/honestyBarPurchases/'+item);
										$scope.cameraRequested = false;
										$scope.cameraRequested = true;
									}
									else
									{
										alert('Invalid Product Tag!!!');
									}
							  });
			    $scope.cameraRequested = true;
			  }
  			/*
			 $scope.onSuccess = function(item) {
			     scannerService.viewThisItem(item).success(function(data) {
						if(data=='valid')
						{
							$location.path('/honestyBarPurchases/'+item);
						}
						else
						{
							alert('Invalid Product Tag!!!');
						}
				  });
			 };
			 
			 $scope.onError = function(error) {
			     //console.log(error);
			 };
			 
			 $scope.onVideoError = function(error) {
			     //console.log(error);
			 };
			 */
		});
	
})();