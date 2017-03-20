(function() {

angular
.module('entreprenityApp.AuthenticationService', [])

.factory('AuthService', ["$http", "$location", function($http, $location){
    var vm = this;
    var baseUrl = 'api/';
    vm.checkToken = function(token)
    {   	 
        var data = {token: token};
        
        $http.post(baseUrl + 'validateUserToken', data).success(function(response)
        {
            if (response.msg === "unauthorized")
            {
                //console.log("Logged out");
                $location.path('/login');
            } 
            else 
            {
                //console.log("Logged In");
                return response.msg;
            }
        }).error(function(error)
        {
            $location.path('/login');
        })
        
    }
    
    vm.logOut = function(token)
    {   	 
        var data = {token: token};
        
        $http.post(baseUrl + 'destroyUserToken', data).success(function(response)
        {
            if (response.msg === "Logged out")
            {
            	 localStorage.clear();
                //console.log("Logged out");
                $location.path('/login');
            } 
        }).error(function(error)
        {
        		localStorage.clear();
            $location.path('/login');
        })
        
    }
   return vm;
}]);

})();