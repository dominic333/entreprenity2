(function() {
	angular
		.module('entreprenityApp.search', [])

		.factory('searchService', function($http) {
			var baseUrl = 'api/';
			
			return {
				getSearchResults: function(searchQuery)
				{ 
					return $http({
						method: 'post',
						url: baseUrl+'searchFor',
						data: $.param(searchQuery),
						headers: {'Content-Type': 'application/x-www-form-urlencoded'}
					})
				}
			};
		})
		.directive('search', function() {
			var controller = function($routeParams, searchService, $scope) {
				var vm = this;
				vm.isBusy =  false;
				vm.showResultsDiv = false;

				vm.searchOnKeyup = function() {
					var searchQuery = {query: vm.searchQuery};

					vm.isBusy = true;

					if(vm.searchQuery.length > 2) {
						//console.log(searchQuery);
						//console.log(vm.searchQuery.length);
						//Service to get search results
						searchService.getSearchResults(searchQuery).success(function(data) {
							vm.searchResults = data;
							//console.log(vm);
							vm.isBusy =  false;
							vm.showResultsDiv = true;
						});
					} else {
						vm.isBusy =  false;
						vm.showResultsDiv = false;
					}
				}

				vm.convertToHTML =  function(content) {
					var origContent = content;
					var htmlContent;

					htmlContent = replaceAll(content, '&lt;', '<');
					htmlContent = replaceAll(htmlContent, '&gt;', '>');

					return htmlContent;
				}

				var replaceAll = function(string, search, replacement) {
					var target = string;
					return target.replace(new RegExp(search, 'g'), replacement);
				};


				
			};
		
			var template = '<button>{{vm.poststype}}</button>';

			return {
				restrict: 'E',
				scope: {

				},
				controller: controller,
				controllerAs: 'vm',
				bindToController: true, //required in 1.3+ with controllerAs
				templateUrl: 'app/components/search/search.html'
				//template: template
			};
		});		
})();
