(function() {

	angular
		.module('entreprenityApp.forgotpassword', [])
		
		.controller('ForgotpasswordController', function($scope, $http, $location) {
			var vm = this;
			
			$scope.post = {};
			$scope.post.login = [];
			$scope.vm = {};
			$scope.index = '';
			
			var baseUrl = 'api/';

			vm.forgotpassword = function(isValid) 
			{
				// check to make sure the form is completely valid
				if (isValid) 
				{
				    $http({
				      method: 'post',
				      url: baseUrl+'forgotpassword',
				      data: $.param($scope.vm),
				      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				    })
				    .success(function(data, status, headers, config) 
				    {
						alert(data.msg);
		    		}).
		    		error(function(data, status, headers, config) 
		    		{
		    			alert(data.msg);
		    		});
				}
			};
			
			
			//Function to validate login form
			//April 15,2016
			$scope.getError = function(error, name)
			{
				if(angular.isDefined(error))
				{
					if(error.email && name == 'username')
					{
						return "Please enter a valid email";
					}
					else if(error.required && name == 'username')
					{
						return "This field is required";
					}
			  }
		  }
		  
			
			
			
		});
	
})();