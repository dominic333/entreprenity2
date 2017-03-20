//inject angular file upload directives and services.
angular.module('entreprenityApp.fileUpload', ['ngFileUpload'])

	.factory('uploadVideoServices', function($http) {
			var baseUrl = 'api/';
			
			return {		
				
				uploadVideo:function ()
				{
											console.log(data);
				}
			};
		})
	.controller('videoUploadController', ['$scope','$route', 'Upload', '$timeout','$uibModalInstance', function ($scope,$route, Upload, $timeout,$uibModalInstance) {
			
		 var baseUrl = 'api/';
   	 $scope.uploadPic = function(file) 
   	 {

	   	 file.upload = Upload.upload({
	     			 url: baseUrl+'uploadVideoServices',
	     			 data: {post: $scope.post,file: file},
				
	   	 });
		
		    file.upload.then(function (response) 
		    {		    	
		      $timeout(function () {
		        file.result = response.data;
		        //Upload Successful
		      });
		      if(file.progress==100)
		      {
		    	  $uibModalInstance.dismiss('cancel');
		    	  $route.reload();
		      }
		    }, function (response) 
		    {
		      if (response.status > 0)
		        $scope.errorMsg = response.status + ': ' + response.data;
		    }, function (evt) 
		    {
		      // Math.min is to fix IE which reports 200% sometimes
		      file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
		      //console.log(file.progress);
		    });
		    
		    
		    
		 }
		    
		     $scope.cancel = function () {
				$uibModalInstance.dismiss('cancel');
			};
	
		}]);
/*.controller('videoUploadController', ['$scope', 'Upload', '$timeout','$uibModalInstance', function ($scope, Upload, $timeout,$uibModalInstance) {

	 var baseUrl = 'api/';
    $scope.$watch('files', function () {
       // $scope.upload($scope.files);
        $scope.videoFile=$scope.files;
        //$scope.upload($scope.files);
    });
    $scope.$watch('file', function () {
        if ($scope.file != null) {
            $scope.files = [$scope.file]; 
        }
    });
    $scope.log = '';

    /*
    $scope.upload = function (files) {
       	
				 if (files && files.length) {
            for (var i = 0; i < files.length; i++) {
              var file = files[i];
              if (!file.$error) {
                Upload.upload({
                    url: baseUrl+'uploadVideoServices',
                    data: {
                      file: file  
                    }
                }).then(function (resp) {
                    $timeout(function() {
                        $scope.log = 'file: ' +
                        resp.config.data.file.name +
                        ', Response: ' + JSON.stringify(resp) +
                        '\n' + $scope.log;
                    });
                }, null, function (evt) {
                    var progressPercentage = parseInt(100.0 *
                    		evt.loaded / evt.total);
                    $scope.log = 'progress: ' + progressPercentage + 
                    	'% ' + evt.config.data.file.name + '\n' + 
                      $scope.log;
                }
                );
              }
            }
        }
    }; */
    	
		/*	$scope.okVideo = function () 
			{
				 var files= $scope.videoFile;
				 //var fileSize = files.size;
				 //console.log(fileSize);
				 if (files && files.length) {
		            for (var i = 0; i < files.length; i++) {
		              var file = files[i];
		              if (!file.$error) 
		              {
		              	
		                Upload.upload({
		                    url: baseUrl+'uploadVideoServices',
		                    data: {
		                      file: file  
		                    }
		                }).then(function (resp) {
		                    $timeout(function() {
		                        $scope.log = JSON.stringify(resp);
		                        //$uibModalInstance.close(data);
		                        $uibModalInstance.dismiss('cancel');
		                       //	$uibModalInstance.dismiss();
		                    });
		                });
		                
		              }
		            }
		        }	
			};
    $scope.cancel = function () {
				$uibModalInstance.dismiss('cancel');
			};
	
	
	$scope.okPost = function () {
				//create a service to update the profile photo using $scope.id
				//when user click save, will post data to update in backend
				//if($scope.myImage) //alert('image uploaded');
				$uibModalInstance.close();
			};

}]);*/
