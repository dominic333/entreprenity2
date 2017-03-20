(function() {
	angular
		.module('entreprenityApp.newsFeed', [])

		.factory('newsFeedService', function($http) {
			var baseUrl = 'api/';
			
			return {
				
				getAllPosts: function(pageNumber,countryCode,centerCode) 
				{ 
					return $http.get(baseUrl+ 'getAllPosts?page='+pageNumber+'&country='+countryCode+'&location='+centerCode);
				},
				getMemberPosts: function(pageNumber,username) 
				{
					return $http.get(baseUrl+ 'getMembersPost?user='+username+'&page='+pageNumber);
				},
            getmyMemberPosts: function(pageNumber,username) 
				{
					return $http.get(baseUrl+ 'getmyTimeLinePost?page='+pageNumber);
				},
				getFollowedMembersPosts: function(pageNumber,username,countryCode,centerCode) 
				{
					return $http.get(baseUrl+ 'getFollowedMembersPosts?page='+pageNumber+'&country='+countryCode+'&location='+centerCode);
				},
				getCompanyPosts: function(pageNumber,username) 
				{
					return $http.get(baseUrl+ 'getCompanyPosts?company='+username+'&page='+pageNumber);
				},
            getMyCompanyPosts: function(pageNumber,username) 
				{
					return $http.get(baseUrl+ 'getmyCompanyPosts?page='+pageNumber);
				},				
				getAllBusinessOpportunities:function(pageNumber,countryCode,centerCode) 
				{
					return $http.get(baseUrl + 'getAllBusinessOpportunities?page='+pageNumber+'&country='+countryCode+'&location='+centerCode);
				},
				getBusinessOpportunitiesForMe:function(pageNumber,countryCode,centerCode) 
				{
					return $http.get(baseUrl + 'getBusinessOpportunitiesForMe?page='+pageNumber+'&country='+countryCode+'&location='+centerCode);
				},
				
				getBasicUserInfo:function()
				{
					return $http.get(baseUrl + 'getBasicUserInformation');
				},
				getTagCategories:function() 
				{
					return $http.get(baseUrl + 'getTagCategories');
				},
				postCurrentPost: function(newPost,imgString,timelineId,ucUsername) 
				{
					var dataPost = {newPost: newPost,imgString: imgString,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'postCurrentPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
            postBusoppPost: function(content,imgString,timelineId,ucUsername) 
				{
					var dataPost = {postContent: content,imgString: imgString,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'postABusinessOpportunity',
										data: $.param(dataPost),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				postComment: function(commentedPost,newComment,timelineId,ucUsername) 
				{
					var dataPost = {postId: commentedPost.post_id,postComment:newComment,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'postThisComment',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},				
				postLike: function(likedPost,timelineId,ucUsername) 
				{
					var dataPost = {likedPostId: likedPost.post_id,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'likeThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				postUnLike: function(unLikedPost,timelineId,ucUsername) 
				{
					var dataPost = {unlikedPostId: unLikedPost.post_id,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'unlikeThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				deleteTimlinePost: function(postID,timelineId,ucUsername) 
				{
					var dataPost = {postID: postID,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'deleteTimlinePost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				editTimelinePost: function(content,timelineId,ucUsername) 
				{
					var dataPost = {postContent: content,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'editTimelinePost',
										data: $.param(dataPost),
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				getUserCountry:function() 
				{
					return $http.get(baseUrl + 'getUserCountry');
				},
				getCountries:function() 
				{
					return $http.get(baseUrl + 'getCountries');
				},
				getCentersInCountry:function(country) 
				{
					return $http.get(baseUrl+ 'getLocationsInCountry?country='+country);
				},
				getLocations:function() {
					return $http.get(baseUrl + 'getLocations');
				},
				hideThisPost: function(postID,timelineId,ucUsername) 
				{
					var dataPost = {postID: postID,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'hideThisPost',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				hideAllPostsOfThisUser: function(postID,timelineId,ucUsername) 
				{
					var dataPost = {postID: postID,timeLine: timelineId,username: ucUsername};														
					return $http({ method: 'post',
										url: baseUrl+'hideAllPostsOfThisUser',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				},
				deleteComment: function (commentId) {
					var dataPost = {commentId: commentId};														
					return $http({ method: 'post',
										url: baseUrl+'deleteThisComment',
										data: dataPost,
										headers: {'Content-Type': 'application/x-www-form-urlencoded'}
									});
				}
				
			};
		})
        .factory('focus', function($timeout, $window) {
            return function(id) {
            // timeout makes sure that is invoked after any other event has been triggered.
            // e.g. click events that need to run before the focus or
            // inputs elements that are in a disabled state but are enabled when those events
            // are triggered.
            $timeout(function() {
                var element = $window.document.getElementById(id);
                if(element)
                element.focus();
                });
            };
        })
        .directive('eventFocus', function(focus) {
            return function(scope, elem, attr) {
                elem.on(attr.eventFocus, function() {
                    focus(attr.eventFocusId);
                });
      
            // Removes bound events in the element itself
            // when the scope is destroyed
            scope.$on('$destroy', function() {
                element.off(attr.eventFocus);
            });
            };
        })
		.directive('newsFeed', function() {
			var controller = function($routeParams, newsFeedService, $scope, focus, $uibModal) {
				var vm = this;
				var userObject;
				//console.log(vm);

				vm.busoppPost = false; // initial state is false, set to true if a business opportunity post
				vm.isAnImagePost = false; // initial state is false, set to true if image upload is clicked
				//vm.isAVideoPost = false; // initial state is false, set to true if image upload is clicked
				vm.editState = false; // initial state is false, set to true if edit post is clicked
				vm.triggernextpage = false; // initial state is false, set to true if infinite scroll is triggered
				vm.reloadnewsfeed = false; // initial state is false, set to true if reload newsfeed is triggered
				vm.pageNumber = 1;
				vm.posts = [];
				vm.busy = false;

				$scope.$watch('vm.triggernextpage', function() {
					if (vm.triggernextpage) {
						//console.log('Loading');
						vm.getPosts();
						vm.triggernextpage = false;
					}
				});

				$scope.$watch('vm.reloadnewsfeed', function() {
					if (vm.reloadnewsfeed) {
						//alert('vm.reloadnewsfeed');
						vm.pageNumber = 1;
						vm.getPosts();
						vm.reloadnewsfeed = false;
					}
				});
				
				$scope.loadTags = function(query) { //load tag Categories
				    /*
					 var categories = [];
					 newsFeedService.getTagCategories().success(function(data) {
					 	vm.categories = data;						
											 	
					 });
					 return vm.categories;
					*/
				};
				
				/*
				vm.keypressEventListener = function($event, post){
					var keyCode = $event.which || $event.keyCode;
					if (keyCode === 13) {
						//alert('Enter pressed');
						//console.log(post);
						vm.editPost(post);
					}
				};
				*/
				

				
				//Service to get country list
				newsFeedService.getCountries().success(function(data) {
					vm.countries = {};					
					vm.countries = data;
					
					//Service to get user's country
					newsFeedService.getUserCountry().success(function(myCountry) {
						$scope.clientCountry = myCountry;
						vm.centers = {};
						newsFeedService.getCentersInCountry(myCountry).success(function(data) {
							vm.centers = data;
						});
					});
				});
					
				//Service to get locations
				newsFeedService.getLocations().success(function(data) {
							vm.centers = data;
				});
				
				//Service to fetch centers based on country
				vm.fetchCenters = function (selectedCountry) 
				{

					if(selectedCountry)
					{
						newsFeedService.getCentersInCountry(selectedCountry).success(function(data) {
							vm.centers = data;
						});
					}
					else
					{
						vm.centers = {};
						newsFeedService.getLocations().success(function(data) {
							vm.centers = data;
						});
						
						selectedCenter = vm.centers[0];
					}
					
					vm.pageNumber = 1;
					vm.posts = [];				
					vm.getPosts();
					vm.reloadnewsfeed = false;
					
			
				};
				
				//Service to Fetch feeds for center (on center change)
				vm.fetchFeedsforCenter = function (selectedCenter) {	
					vm.pageNumber = 1;
					vm.posts = [];				
					vm.getPosts();	
					vm.reloadnewsfeed = false;		
				};
				
				vm.getPosts = function () {
					var postsType = vm.poststype;
					var username = vm.username;

					var selectedCountry	= $scope.selectedCountry;
					var selectedCenter	= $scope.selectedCenter;
					
					if(selectedCountry)
					{
						var countryCode	=	selectedCountry;
					}
					else
					{
						var countryCode	=	0;
					}
					
					if(selectedCenter)
					{
						var centerCode		=	selectedCenter;
					}
					else
					{
						var centerCode		=	0;
					}
					
					
					switch(postsType)
					{
						//home page all posts
						case '1':
						
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getAllPosts(vm.pageNumber,countryCode,centerCode).success(function(data) {
								var itemData = data;
								console.log(data);
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
							});
						break;

						//member profile timeline posts
						case '2':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getMemberPosts(this.pageNumber,username).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
							});
						break;

						//home page followed posts
						case '3':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getFollowedMembersPosts(this.pageNumber,username,countryCode,centerCode).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
							});
						break;

						//company profile timeline posts
						case '4':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getCompanyPosts(this.pageNumber,username).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
							});
						break;

						//business all opportunities page
						case '5':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getAllBusinessOpportunities(this.pageNumber,countryCode,centerCode).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
								vm.busoppPost = true;
							});
						break;
						
						//my company profile timeline posts
						case '6':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getMyCompanyPosts(this.pageNumber,username).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
							});
						break;
						
						//home page my posts timeline
						case '7':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getmyMemberPosts(this.pageNumber,username).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
							});
						break;
						
						//matched business opportunities
						case '8':
							
							if (vm.busy) return;
							vm.busy = true;
							newsFeedService.getBusinessOpportunitiesForMe(this.pageNumber,countryCode,centerCode).success(function(data) {
								var itemData = data;
								if (vm.pageNumber == 1) {
									vm.posts = itemData;
								} else {
									for (var i = 0; i < itemData.length; i++) {
										vm.posts.push(itemData[i]);
									}
								}
								vm.pageNumber++;
								vm.busy = false;
								vm.busoppPost = true;
							});
						break;
					}

				};
				
				
				vm.getPosts();

				var myPost = {
					"post_id": "",
					"content": "",
					"image": "",
					"created_at": "",
					"post_author": {
						"id": "",
						"firstName": "",
						"lastName": "",
						"avatar": "",
						"position": "",
						"companyName": "",
						"userName": ""
					},
					"isLiked": false,
					"likes_count": 0,
					"likers": [],
					"comments_count": 0,
					"commenters": [],
					"comments": []
				};

				vm.currentPost = myPost;

				//to get basic user information
				vm.basicInfo = function () {
					newsFeedService.getBasicUserInfo().success(function(data) {
						vm.currentPost.post_author.id 	= data.id;
						vm.currentPost.post_author.firstName 	= data.firstName;
						vm.currentPost.post_author.lastName 	= data.lastName;
						vm.currentPost.post_author.position 	= data.position;
						vm.currentPost.post_author.companyName 	= data.companyName;
						vm.currentPost.post_author.avatar 		= data.avatar;
						vm.currentPost.post_author.userName 	= data.userName;
						vm.currentPost.post_author.companyUserName 	= data.companyUserName;
						userObject = data;
					});	
				};
				vm.basicInfo();


				// Add a time-line post
				vm.addPost = function (newPost,newImg) 
				{
					
					var currentPost = vm.currentPost;
					currentPost.created_at = new Date();
					//vm.posts.unshift(currentPost);
					currentPost.content = ""; //clear post textarea
					//currentPost.image = ""; //clear post image
					if(newImg)
					{
					 	var base64ImgString= newImg;
					}
					else
					{
						var base64ImgString= '';
					}
					
					if(newPost)
					{
						newsFeedService.postCurrentPost(newPost,base64ImgString,vm.poststype,vm.username).success(function(data) {
							vm.posts = data;
							/*if(data.response=='success')
							{
								vm.posts.unshift(currentPost);
							}*/
							
							vm.currentPost.image = ""; //clear post image
                     vm.isAnImagePost = false; //hide image div
						});
					}	
				};

				// Add a time-line Business Opportunity post
				vm.addBusoppPost = function (content,newImg) 
				{
               var currentPost = vm.currentPost;
					if(content.content && content.categories)
					{
                  if(newImg)
                  {
                      var base64ImgString= newImg;
                  }
                  else
                  {
                      var base64ImgString= '';
                  }
						newsFeedService.postBusoppPost(content,base64ImgString,vm.poststype,vm.username).success(function(data) 
						{
							vm.posts = data;
                     currentPost.image = ""; //clear post image
                     currentPost.content = ""; //clear post textarea
                     currentPost.categories = ""; //clear tags
                     vm.currentPost.image = ""; //clear post image
                     vm.isAnImagePost = false; //hide image div
						});
					}								
				};

				//Like a time-line post
				vm.likePost = function(post) 
				{
					var likedPost = post;
					//vm.basicInfo();
					likedPost.likers.push(userObject);
					newsFeedService.postLike(likedPost,vm.poststype,vm.username).success(function(data) {
						if(data.response=='success')
						{
							likedPost.isLiked = true;
							likedPost.likes_count++;						
						}
						//vm.posts = data;
												
					});	
				};

				//unlike a time-line post
				vm.unLikePost = function(post) 
				{
					var unLikedPost = post;
					//vm.basicInfo();
					newsFeedService.postUnLike(unLikedPost,vm.poststype,vm.username).success(function(data) {
						if(data.response=='success')
						{
							unLikedPost.isLiked = false;
							unLikedPost.likes_count--;
							unLikedPost.likers.pop();
						}
						//vm.posts = data;							
					});	
				};
				

				//Add a comment to time-line post
				vm.addComment = function(post,newComment) {
					var commentedPost = post;
					var currentComment = {};
					
					if(newComment)
					{
						//currentComment.content = vm.currentComment.content;
						currentComment.content = newComment;
						currentComment.created_at = new Date();
						currentComment.comment_author = userObject;
	
						newsFeedService.postComment(commentedPost,newComment,vm.poststype,vm.username).success(function(data) {
							//vm.posts = data;
							if(data.response=='success')
							{
								commentedPost.comments_count++;
								commentedPost.comments.push(currentComment);
							}								
							post.comment.content = ""; //clear comment textarea
							//vm.currentComment.content = ""; //clear comment textarea					
						});
					}	
				};
                
				 //This is to set focus on the comment box upon clicking the comment link
				 vm.focusCommentBox = function(newCommentBox) {
					 // do something awesome
					 focus(newCommentBox);
				 };

				//Image upload function
				vm.addImageToPost = function () 
				{
					//alert('addImageToPost');
					
					vm.isAnImagePost = true;
					vm.currentPost.image = "";
					
					var modalInstance = $uibModal.open({
						animation: $scope.animationsEnabled,
						templateUrl: 'app/components/modal/imageUploadPostsView.html',
						controller: 'ImageUploadController',
						resolve: {
							id: function ()
							{
								return 1;
							}
						}
					});

					modalInstance.result.then(function (imageToPost) {
						vm.currentPost.image = imageToPost;

					}, function () {
						$log.info('Modal dismissed at: ' + new Date());
					});

				}
				
				//Video upload
				
				vm.addVideoPost = function () 
				{
					vm.isAnImagePost = true;
					var modalInstance = $uibModal.open({
						animation: $scope.animationsEnabled,
						templateUrl: 'app/components/modal/videoUpload.html',
						controller: 'videoUploadController',
						resolve: {
							id: function ()
							{
								return 1;
							}
						}
					});

					modalInstance.result.then(function (videoToPost) {
						vm.currentPost.image = videoToPost;
						vm.getPosts();

					}, function () {
						$log.info('Modal dismissed at: ' + new Date());
					});

				}
				
				// Delete a time-line post
				vm.deletePost = function (postsArray, postIndex,postID) 
				{	
					var deletedPost = postsArray[postIndex];				
					if(postID)
					{
						newsFeedService.deleteTimlinePost(postID,vm.poststype,vm.username).success(function(data) 
						{
							if(data.response=='success')
							{
								postsArray.splice(postIndex, 1);
							}							
							//vm.posts = data;
						});
					}	
				};
				
				//Hide a time-line post
				vm.hideThisPost = function (postsArray, postIndex,postID) 
				{				
					if(postID)
					{
						newsFeedService.hideThisPost(postID,vm.poststype,vm.username).success(function(data) 
						{
							if(data.response=='success')
							{
								postsArray.splice(postIndex, 1);
							}							
							//vm.posts = data;
						});
					}	
				};
				
				//Hide All posts of this user
				vm.hideAllPostsOfThisUser = function (postsArray, postIndex,postID) 
				{				
					if(postID)
					{
						newsFeedService.hideAllPostsOfThisUser(postID,vm.poststype,vm.username).success(function(data) 
						{
							if(data.response=='success')
							{
								postsArray.splice(postIndex, 1);
							}							
							//vm.posts = data;
						});
					}	
				};
				
				/*				
				//Ken's delete Post
				vm.deletePost = function(postsArray, postIndex) {
					var deletedPost = postsArray[postIndex];

					//service to send deleted post to backend
					 newsFeedService.deleteTimlinePost(postID,vm.poststype,vm.username).success(function(data)
					 {
						//vm.posts = data;
						 console.log(deletedPost);
						 postsArray.splice(postIndex, 1);
					 });
				};
				*/


				//Edit a time-line Post content
				vm.editPost = function(post) 
				{
					var editedPost = post;
					var postContent 	= editedPost.content;
					
					if(postContent)
					{
						//service to send edited post
						newsFeedService.editTimelinePost(editedPost,vm.poststype,vm.username).success(function(data) {
							vm.posts = data;
						});
						vm.indexOfEditedPost = null;
						vm.editState = false;
					}
				};

				vm.openPostToEdit = function(index) {
					vm.indexOfEditedPost = index;
					vm.editState = true;
				};

				vm.nextPage = function() {
					//console.log('trigger nextpage');
				};
				
				//Delete a comment
				//Jerry
				vm.deleteComment = function(commentArray, commentIndex, commentId)
				{
					console.log(commentArray, commentIndex, commentId);
					//var commentId = comment;

					if(commentId)
					{
						//service to delete a post
						newsFeedService.deleteComment(commentId).then(function(response) {
							if(response.data.response == 'success'){
								//Splice the comments array here
								commentArray.splice(commentIndex, 1);
							}else{
								//Show error message here
							}
						});
					}

				};
			};
		
			var template = '<button>{{vm.poststype}}</button>';

			return {
				restrict: 'E',
				scope: {
					poststype: '@',
					istextareahidden: '=',
					username: '=',
					triggernextpage: '=',
					reloadnewsfeed: '='
				},
				controller: controller,
				controllerAs: 'vm',
				bindToController: true, //required in 1.3+ with controllerAs
				templateUrl: 'app/components/newsFeed/newsFeed.html'
				//template: template
			};
		});		
})();
