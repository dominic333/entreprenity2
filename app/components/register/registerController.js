(function() {

    angular
        .module('entreprenityApp.register', [])
        
        .controller('RegisterController', function($scope,textAngularManager) {
            var vm = this;

            //$scope.version = textAngularManager.getVersion();
            //$scope.versionNumber = $scope.version.substring(1);
            vm.orightml = '';
            vm.htmlcontent = vm.orightml;
            vm.disabled = false;

            console.log(vm.htmlcontent);
        });
})();
