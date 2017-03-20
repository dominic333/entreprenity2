(function() {

	angular
		.module('entreprenityApp.logout', [])
		
		.controller('LogoutController', function($scope,$routeParams,AuthService) {
				var vm = this;		

			    //If user is not logged in
				var token;
				if (localStorage['entrp_token'])
				{
			    	token = JSON.parse(localStorage['entrp_token']);
				} 
				else 
				{
					token = "something stupid";
				}
				AuthService.logOut(token);

		});
	
		

})();
