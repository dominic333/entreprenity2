(function() {

	angular
		.module('entreprenityApp.honestyBar', [])
		
		.factory('honestyService', function($http) {
			var baseUrl = 'api/';
			
			return {	
				getItemDetails: function(itemId) 
				{
					var dataContent = {
			            'item' : itemId
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'getItemDetails',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				},
				purchaseThisItem: function(itemId,id) 
				{
					var dataContent = {
			            'tag' : itemId,
			            'slno' : id
			        };
			        
					return $http({ method: 'post',
									url: baseUrl+'purchaseThisItem',
									data: $.param(dataContent),
									headers: {'Content-Type': 'application/x-www-form-urlencoded'}
								});
				}
			};			
		})
		.controller('honestyBarController', function($routeParams,$scope, $http, $location,honestyService,$rootScope) {
			
		 	 var vm = this;
		 	 vm.itemCode = $routeParams.itemTag;
		 	 
		 	 //Function to show product info
		 	 honestyService.getItemDetails(vm.itemCode).success(function(data) 
		 	 {
				//vm.product = data;
				if(data.image)
				{
					vm.image = data.image;
				}
				else
				{
					vm.image = 'assets/img/entreprenity-icon.png';
				}
				
				vm.name  = data.name;
				vm.price = data.price;
				vm.currency = data.currency;
				vm.location = data.location;
				vm.itemTag = data.itemTag;
				vm.id = data.id;
			 });	
			
			//Function to purchase a product
			vm.purchaseThisItem = function(tag,id) {
				honestyService.purchaseThisItem(tag,id).success(function(data) {
					if(data.response =='success')
					{
						//alert('Purchased!');
						$rootScope.purchase = data.purchase;
						$rootScope.price 	  = data.price; //user paid
						$rootScope.userCurrency 	 = data.userCurrency; //user currency
						$rootScope.remainingCredits = data.remainingCredits; //remaining credit
						$rootScope.itemPrice 	  	 = data.itemPrice; //item
						$rootScope.itemCurrency 	 = data.itemCurrency; //item currency
						
						$location.path('/purchaseSuccess');
					}
					else
					{
						//alert('Something went wrong. Please try again!');
						$location.path('/purchaseFailed');
					}
					
				});	
		   };
		   
		   //Function to cancel purchase of a product
			vm.cancelThisItemPurchase = function() {
				$location.path('/honestyBar');
		   };
		   
		});
	
})();