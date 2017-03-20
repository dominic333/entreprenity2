(function() {

	angular
		.module('entreprenityApp.resetpassword', ["ngMessages"])
	
		.directive('compareTo', function() {

			    return {
			      require: "ngModel",
			      scope: {
			        otherModelValue: "=compareTo"
			      },
			      link: function(scope, element, attributes, ngModel) {
			
			        ngModel.$validators.compareTo = function(modelValue) {
			          return modelValue == scope.otherModelValue;
			        };
			
			        scope.$watch("otherModelValue", function() {
			          ngModel.$validate();
			        });
			      }
			    };			
		})
		.controller('ResetpasswordController', function($scope, $http, $location) {
			
		 	 var vm = this;
		    vm.message = "";
		
		    vm.user = {
		      currentpassword: "",
		      password: "",
		      confirmPassword: ""
		    };
			 var baseUrl = 'api/';
			 
		    vm.submit = function(isValid) 
		    {
		      if (isValid) 
		      {
		      	var dataPost = {currentPassword: vm.user.currentpassword,newPassword: vm.user.confirmPassword};			        
		        	$http({
				      method: 'post',
				      url: baseUrl+'resetPassword',
				      data: $.param(dataPost),
				      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				    })
				    .success(function(data, status, headers, config) 
				    {
				    	vm.message = data.msg;
		    		}).
		    		error(function(data, status, headers, config) 
		    		{
						vm.message = data.msg;
		    		});
		      } 
		      else
		      {
		        vm.message = "There are still invalid fields below";
		      }
		    };				
			
		});
	
})();