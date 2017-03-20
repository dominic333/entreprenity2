<?php

require_once ('Query.php'); 
require_once ('userDefinedFunctions.php'); 
require_once 'constants.php';
require 'flight/Flight.php';
//require_once 'externalLibraries/qrcode/qrlib.php';
require_once 'externalLibraries/qrcode.php';
require_once ('externalLibraries/Mobile_Detect.php'); 

//01 Route to events directory
// April 13,2016
Flight::route('/getEvents', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getEvents();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//02 Route to members directory
// April 13,2016
Flight::route('/getMembers', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMembers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//03 Route to companies directory
// April 13,2016
Flight::route('/getCompanies', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getCompanies();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//04 Route to login and redirect
// April 15,2016
Flight::route('/login', function()
{
   enable_cors();	
   services_included();	
	$returnarray=login();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//05 Function for forgot password feature
//April 15, 2016
Flight::route('/forgotpassword', function()
{
   enable_cors();	
   services_included();	
	$returnarray=forgot_password();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//06 Function to get user session
//April 18,2016
Flight::route('/get_user_session', function()
{
   enable_cors();	
   services_included();	
	$returnarray=get_user_session();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//07 Function to get user session
//April 19,2016
Flight::route('/getLocations', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getLocations_dropdown();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//08 Route to fetch new members
// April 21,2016 (sectionServices.php)
Flight::route('/getNewMembers', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getNewMembers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//09 Route to fetch new members
// April 21,2016
Flight::route('/view_user_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=viewUserProfile();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//10 Route to fetch new members
// April 25,2016
Flight::route('/view_company_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=viewCompanyProfile();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//11 Route to fetch new members
// April 27,2016 (index.php)
Flight::route('/view_event_detail', function()
{
   enable_cors();	
   services_included();	
	$returnarray=viewEventDetail();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});



//12 Route to get a user's own details
//April 28,2016
Flight::route('/get_my_details', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//13 Route to update user profile details
//May 03,2016
Flight::route('/update_my_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=updateMyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//14 Route to get a user's own company details
//May 03,2016
Flight::route('/get_my_company_profile', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyCompanyProfileDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//15 Route to update user's company profile details
//May 03,2016
Flight::route('/update_my_company_profile', function()
{
   enable_cors();
   services_included();	
	$returnarray=updateMyCompanyDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//16 Route to update user's profile avatar
//May 06,2016
Flight::route('/update_member_avatar', function()
{
   enable_cors();
   services_included();	
	$returnarray=uploadTheImage();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//17 Route to fetch latest events
//May 09,2016 (sectionServices.php)
Flight::route('/getLatestEvents', function()
{
   enable_cors();
   services_included();	
	$returnarray=getLatestEvents();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//18 Route to fetch basic user information
//May 09,2016
Flight::route('/getBasicUserInformation', function()
{
   enable_cors();
   services_included();	
	$returnarray=getBasicUserInformation();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//19 Route to follow a user from his profile
//May 11,2016
Flight::route('/followThisUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=followThisUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//20 Route to un-follow a user from his profile
//May 11,2016
Flight::route('/unfollowThisUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowThisUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//21 Route to follow a user from member directory
//May 11,2016 (followUnfollowservices.php)
Flight::route('/followUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=followUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//22 Route to un-follow a user from member directory
//May 11,2016 (followUnfollowservices.php)
Flight::route('/unfollowUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//23 Route to follow a company from company directory
//May 12,2016
Flight::route('/followCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=followCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//24 Route to un-follow a company from company directory
//May 12,2016
Flight::route('/unfollowCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//25 Route to follow a company from company profile
//May 12,2016
Flight::route('/followThisCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=followThisCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//26 Route to un-follow a company from company profile
//May 12,2016
Flight::route('/unfollowThisCompany', function()
{
   enable_cors();
   services_included();	
	$returnarray=unfollowThisCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//27 Route to mark going for an event (from event details)
//May 12,2016
Flight::route('/goingForEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=goingForEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//28 Route to mark not going for an event (from event details)
//May 12,2016
Flight::route('/notGoingForEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=notGoingForEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//29 Route to mark going for an event (from event directory)
//May 13,2016
Flight::route('/goingToEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=goingToEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//30 Route to mark not going for an event (from event directory)
//May 13,2016
Flight::route('/notGoingToEvent', function()
{
   enable_cors();
   services_included();	
	$returnarray=notGoingToEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//31 Route to fetch a member's following list
//May 13,2016
Flight::route('/getMemberFollowing', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMemberFollowing();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//32 Route to fetch a member's follower list
//May 13,2016
Flight::route('/getMemberFollowers', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMemberFollowers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//33 Route to fetch a company's follower list
//May 13,2016
Flight::route('/getCompanyFollowers', function()
{
   enable_cors();
   services_included();
	$returnarray=getCompanyFollowers();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//34 Route to check validity of a user token
//May 17,2016 (userLoginServices.php)
Flight::route('/validateUserToken', function()
{
   enable_cors();
   services_included();	
	$returnarray=validateUserToken();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//35 Route to check validity of a user token
//May 17,2016 (userLoginServices.php)
Flight::route('/destroyUserToken', function()
{
   enable_cors();
   services_included();	
	$returnarray=destroyUserToken();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//36 Route to post new Feed to timeline
//May 18,2016
Flight::route('/postCurrentPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=postCurrentPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//37 Route to get my Feed to timeline
//May 18,2016
Flight::route('/getMembersPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMyNewsFeed();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//38 Route to get timeline feeds of users I follow
//May 30,2016  (timelineServices.php)
Flight::route('/getFollowedMembersPosts', function()
{
   enable_cors();
   services_included();	
	$returnarray=getFollowedMembersPosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//39 Route to get timeline feeds of all users
//May 30,2016 (timelineServices.php)
Flight::route('/getAllPosts', function()
{
   enable_cors();
   services_included();	
	$returnarray=getAllPosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//40 Route to post a new comment for a timeline post
//May 20,2016 (timelineServices.php)
Flight::route('/postThisComment', function()
{
   enable_cors();
   services_included();	
	$returnarray=postThisComment();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

// Route to delete a comment for a timeline post
//May 20,2016 (timelineServices.php)
Flight::route('/deleteThisComment', function()
{
   enable_cors();
   services_included();	
	$returnarray=deleteThisComment();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//41 Route to like a timeline post 
//May 20,2016 (timelineServices.php)
Flight::route('/likeThisPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=likeThisPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//42 Route to unlike a timeline post
//May 20,2016 (timelineServices.php)
Flight::route('/unlikeThisPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=unlikeThisPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//43 Route to profile settings
// May 24,2016 (myProfileServices.php)
//Arshad
Flight::route('/getMyPreferences', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyPreferences();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//44 Route to update preferences
// May 25,2016 (myProfileServices.php)
//Arshad
Flight::route('/updateMyPreferences', function()
{
   enable_cors();	
   services_included();	
	$returnarray=updateMyPreferences();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//45 Route to fetch top contributors
//June 02,2016 (sectionServices.php)
Flight::route('/getTopContributors', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getTopContributorsBeta();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//46 Route to fetch user notifications
//June 02,2016
Flight::route('/getMyNotifications', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getMyNotifications();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//47 Route to fetch a single post from user notification
//June 08,2016
Flight::route('/getThisPost', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getThisPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//48 Route to fetch timeline posts of a company
//June 13,2016 (timelineServices.php)
Flight::route('/getCompanyPosts', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getCompanyPosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//49 Route to fetch all company categories
//June 13,2016
Flight::route('/getTagCategories', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getAllCompanyCategories();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//50 Route to post a business opportunity
//June 15,2016 (timelineServices.php)
Flight::route('/postABusinessOpportunity', function()
{
   enable_cors();	
   services_included();	
	$returnarray=postABusinessOpportunity();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//51 Route to fetch all business opportunities (readOnlyServices.php)
//June 15,2016 (timelineServices.php)
Flight::route('/getAllBusinessOpportunities', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getAllBusinessOpportunities();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//52 Route to fetch all event categories (readOnlyServices.php)
//June 15,2016
Flight::route('/getAllEventCatgories', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getAllEventCatgories();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//53 Route to add a new event's details (directoryServices.php)
//June 15,2016
Flight::route('/addNewEvent', function()
{
   enable_cors();	
   services_included();	
	$returnarray=addNewEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//54 Route to like a comment made for a timeline post
//June 23,2016
Flight::route('/likeThisComment', function()
{
   enable_cors();	   
   services_included();	
	$returnarray=likeThisComment();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//55 Route to unlike a comment made for a timeline post
//June 23,2016
Flight::route('/unlikeThisComment', function()
{
   enable_cors();	
   services_included();	
	$returnarray=unlikeThisComment();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//56 Route to finish an add event process
//June 23,2016
Flight::route('/finishThisEvent', function()
{
   enable_cors();	
   services_included();	
	$returnarray=finishThisEvent();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//57 Route to fetch events hosted by a company
//June 28,2016
Flight::route('/getEventsHostedByCompany', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getEventsHostedByCompany();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//58 Route to fetch business opportunities (as a section) to show in home page
//June 29,2016 (sectionServices.php)
Flight::route('/recommendedBusinessOpportunities', function()
{
   enable_cors();	
   services_included();	
	$returnarray=recommendedBusinessOpportunities();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//59 Route to invoke call answering service
//June 30,2016 (externalServices.php)
Flight::route('/invokeCallAnswering', function()
{
   enable_cors();	
   services_included();	
	$returnarray=invokeCallAnswering();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//60 Route to invoke spaces (meeting room) service
//July 05,2016 (externalServices.php)
Flight::route('/invokeSpaces', function()
{
   enable_cors();	
   services_included();	
	$returnarray=invokeSpaces();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});


//61 Route to fetch timeline posts of a company
//July 10,2016 (timelineServices.php)
Flight::route('/getmyCompanyPosts', function()
{
   enable_cors();	
   services_included();	
	$returnarray=getmyCompanyPosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);

});

//62 Route to get my Feed to timeline
//July 10,2016 (timelineServices.php)
Flight::route('/getmyTimeLinePost', function()
{
   enable_cors();
   services_included();	
	$returnarray=getMyOwnNewsFeed();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//63 Route to get recommended business opportunities (business opportunity timeline)
//July 16,2016 (timelineServices.php)
Flight::route('/getBusinessOpportunitiesForMe', function()
{
   enable_cors();
   services_included();	
	$returnarray=getBusinessOpportunitiesForMe();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//63 Route to delete a timeline post 
//July 19,2016 (timelineServices.php)
Flight::route('/deleteTimlinePost', function()
{
   enable_cors();
   services_included();	
	$returnarray=deleteTimlinePost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//63 Route to edit a timeline post 
//July 19,2016 (timelineServices.php)
Flight::route('/editTimelinePost', function()
{
   enable_cors();
   services_included();	
	$returnarray=editTimelinePost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//63 Route to change user password
//August 03,2016 (userLoginServices.php)
Flight::route('/resetPassword', function()
{
   enable_cors();
   services_included();	
	$returnarray=resetPassword();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//64 Route to read total unread notifications
//August 18,2016 (notificationServices.php)
Flight::route('/getAllUnreadNotifications', function()
{
   enable_cors();
   services_included();	
	$returnarray=getAllUnreadNotifications();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//65 Route to save facebook connect data
//August 24,2016 (externalServices.php)
Flight::route('/saveFacebookAuthData', function()
{
   enable_cors();
   services_included();	
	$returnarray=saveFacebookAuthData();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//66 Route to unlink a facebook account
//August 24,2016 (externalServices.php)
Flight::route('/unlinkFacebookAccount', function()
{
   enable_cors();
   services_included();	
	$returnarray=unlinkFacebookAccount();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//67 Route to check facebook conected or not
//August 24,2016 (externalServices.php)
Flight::route('/checkFBConnectedorNot', function()
{
   enable_cors();
   services_included();	
	$returnarray=checkFBConnectedorNot();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//68 Route to allow login with facebook
//August 24,2016 (userLoginServices.php)
Flight::route('/loginWithFaceBook', function()
{
   enable_cors();
   services_included();	
	$returnarray=loginWithFaceBook();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//69 Route to save facebook Id and authorize facebok login
//August 24,2016 (externalServices.php)
Flight::route('/saveFacebookIdandAuthorize', function()
{
   enable_cors();
   services_included();	
	$returnarray=saveFacebookIdandAuthorize();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//70 Route to check whether entreprenity token expired or not
//August 24,2016 (userLoginServices.php)
Flight::route('/checkEntrpTokenExpiration', function()
{
   enable_cors();
   services_included();	
	$returnarray=checkEntrpTokenExpiration();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//71 Route to fetch country locations (selected countries)
//September 07,2016 (readOnlyServices.php)
Flight::route('/getCountries', function()
{
   enable_cors();
   services_included();	
	$returnarray=getSelectedCountries();
	//$returnarray=getAllCountries();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//72 Route to fetch locations based on countries
//September 07,2016 (readOnlyServices.php)
Flight::route('/getLocationsInCountry', function()
{
   enable_cors();
   services_included();	
	$returnarray=getLocationsUnderCountry();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//73 Route to fetch a user's QR code
//September 17,2016 (readOnlyServices.php)
Flight::route('/getMyQRCode', function()
{
   enable_cors();
   services_included();	
	$returnarray=getUserQRCode();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//74 Route to change a user's QR code
//September 27,2016 
Flight::route('/changeQRCode', function()
{
   enable_cors();
   services_included();	
	$returnarray=changeQRCode();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//75 Route to fetch last updated time of user's QR code
//September 27,2016 
Flight::route('/fetchQRCodeUpdatedTime', function()
{
   enable_cors();
   services_included();	
	$returnarray=fetchQRCodeUpdatedTime();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//76 Route to hide a particular post from a user's timelines
//October 07,2016 (timelineServices.php)
Flight::route('/hideThisPost', function()
{
   enable_cors();
   services_included();	
	$returnarray=hideThisPost();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//77 Route to hide one user's all posts from the timelines
//October 07,2016 (timelineServices.php)
Flight::route('/hideAllPostsOfThisUser', function()
{
   enable_cors();
   services_included();	
	$returnarray=hideAllPostsOfThisUser();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//78 Route to mark all notifications as read
//October 14,2016 (notificationServices.php)
Flight::route('/markAllNotificationsAsRead', function()
{
   enable_cors();
   services_included();	
	$returnarray=markAllNotificationsAsRead();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//79 Route to mark all notifications as read
//October 27,2016 (searchServices.php)
Flight::route('/searchFor', function()
{
   enable_cors();
   services_included();	
	$returnarray=searchResultsFor();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//80 Route to view an item's info
//January 17,2017 (externalServices.php)
Flight::route('/viewThisItem', function()
{
   enable_cors();
   services_included();	
	$returnarray=viewThisItem();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});


//81 Route to fetch an item's info
//January 17,2017 (externalServices.php)
Flight::route('/getItemDetails', function()
{
   enable_cors();
   services_included();	
	$returnarray=getItemDetails();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//82 Route to purchase an item from Honesty Bar
//January 17,2017 (externalServices.php)
Flight::route('/purchaseThisItem', function()
{
   enable_cors();
   services_included();	
	$returnarray=purchaseThisItem();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//83 Route to fetch a user's country for timeline purposes
//January 17,2017 (userIDBasedServices.php)
Flight::route('/getUserCountry', function()
{
   enable_cors();
   services_included();	
	$returnarray=getUserCountryFromLocation();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//84 Route to fetch a user's country for timeline purposes
//January 17,2017 (userIDBasedServices.php)
Flight::route('/uploadVideoServices', function()
{
   enable_cors();
   services_included();	
	$returnarray=uploadVideo();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});

//Route to test timeline posts
//November 31,2016
/*
Flight::route('/testTimelinePosts', function()
{
   enable_cors();
   services_included();	
	$returnarray=testTimelinePosts();
	header('Content-type:application/json;charset=utf-8');
	echo json_encode($returnarray);
});
*/




Flight::start();


function services_included()
{
	
	require_once 'services/searchServices.php'; 
	require_once 'services/myCompanyProfileServices.php'; 
	require_once 'services/readOnlyServices.php'; 
	require_once 'services/userLoginServices.php'; 
	require_once 'services/userIDBasedServices.php'; 
	require_once 'services/myProfileServices.php'; 
	require_once 'services/directoryServices.php'; 
	require_once 'services/imageUploadServices.php'; 
	require_once 'services/followUnfollowServices.php'; 
	require_once 'services/timelineServices.php'; 
	require_once 'services/emailServices.php'; 
	require_once 'services/sectionServices.php'; 
	require_once 'services/notificationServices.php'; 
	require_once 'services/externalServices.php'; 
	require_once 'services/uploadVideoServices.php'; 
	
	
}

//Function to log action to text file
//October 18,2016
function logThisToTxtFile($logENID,$action)
{
	$detect = new Mobile_Detect();
	
	$user_browser=user_browser();
	$user_os=user_os();
	$user_ip=getRealIpAddr();
				
	if ($detect->isMobile())
	{
		// mobile content
		$device='Mobile';
	}   				
	else
	{
		// other content for desktops
		$device='Desktop';
	}
	date_default_timezone_set('UTC');	
	$logDateTime= date('Y-m-d H:i:s');		
	$log_this = 'EN client '.$logENID.': '.$action.' on UTC '.$logDateTime.' from '.$user_ip.' using '.$device.' through browser '.$user_browser.' ,OS: '.$user_os;
	$filename= 'ENLog_'.date('MY').'.txt';
 	$myfile = file_put_contents($filename, $log_this.PHP_EOL , FILE_APPEND);	

}

//Function to fetch last updated time of QR Code
//September 27,2016
function fetchQRCodeUpdatedTime()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id>0)
	{
	   $data=getLastUpdatedTimeQRCode($my_session_id);
	}
	return $data;
}

//Function to fetch last updated time for QR Codes
//September 27,2016
function getLastUpdatedTimeQRCode($clientid)
{
	$data= array();
	$qry="SELECT qrCodeUpdatedDateTime FROM entrp_login WHERE clientid=".$clientid." ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
   	{
   		$data['lastUpdatedAt']	=	$row['qrCodeUpdatedDateTime'];
   	}	   
   }
	return $data;
}

//Function to change a user's QR Code
//September 27,2016
function changeQRCode()
{
	$data= array();
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	if($my_session_id>0)
	{
		$qrCodeToken	=	uniqueQRCodeToken();
	   saveQRCOdeforUser($my_session_id,$qrCodeToken);
	   $data=getLastUpdatedTimeQRCode($my_session_id);
	}
	return $data;
}

//Function to fetch qrcode from database, if already generated
//September 22,2016
function fetchUserQRCodeFromDB($clientid)
{
	$qrCode= '';
 	$qry="SELECT qrCode
			FROM entrp_login 
			WHERE clientid=".$clientid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$qrCode	=	$row['qrCode'];
		}   	   	
   }
   return $qrCode;
}

//Function to generate qrcode identifier
//September 22,2016
function uniqueQRCodeToken()
{
	$token = substr(md5(uniqid(rand(), true)),0,32);  // creates a 32 digit token
	//SELECT * FROM entrp_login where qrCode='70f804625753d84827ef993329c3b1b8'
   $qry = "SELECT * FROM entrp_login WHERE qrCode='".$token."'";
   $res=getData($qry);
   $count_res=mysqli_num_rows($res);
   if($count_res > 0)
   {
      uniqueQRCodeToken();
   } 
   else 
   {
      return $token;
   }	
}


//Function to save QR code of the user in database
//September 22,2016
function saveQRCOdeforUser($clientid,$qrCodeToken)
{
	date_default_timezone_set('UTC');
	$updatedAt=date('Y-m-d H:i:s');
	$qry="UPDATE entrp_login SET qrCode='".$qrCodeToken."',qrCodeUpdatedDateTime='".$updatedAt."' WHERE clientid=".$clientid." ";
	setData($qry);
}

//Function to generate qrcode for a user
//September 17,2016
function getUserQRCode()
{
	 $qr = new qrcode();
	 //the defaults starts
	 global $myStaticVars;
	 extract($myStaticVars);  // make static vars local
	 $member_default_avatar 	= $member_default_avatar;
	 $member_default_cover		= $member_default_cover;
	 $member_default				= $member_default;
	 $company_default_cover		= $company_default_cover;
	 $company_default_avatar	= $company_default_avatar;
	 $events_default				= $events_default;
	 $event_default_poster		= $event_default_poster;
	 //the defaults ends	
	
	 $session_values=get_user_session();
	 $my_session_id	= $session_values['id'];
	 if($my_session_id>0)
	 {
	    // how to build raw content - QRCode with Business Card (VCard) + photo 	     
	    //$tempDir = QRCODE_PATH; 
	     
	    $data			=	fetch_info_from_entrp_login($my_session_id);
	    
	    $clientid 		= $data['clientid'];
   	 $username 		= $data['username'];
   	 $email 			= $data['email'];
   	 $firstname 	= $data['firstname'];
   	 $lastname 		= $data['lastname'];
   	 $voffStaff		= $data['voffStaff'];
		 $vofClientId	= $data['vofClientId'];	
		 
		 //Fetch qr-code from db if already generated
		 $qrCode= fetchUserQRCodeFromDB($clientid);
	    
	    if($qrCode=='')
	    {
	    	$qrCodeToken	=	uniqueQRCodeToken();
	    	saveQRCOdeforUser($clientid,$qrCodeToken);
	    }
	    else
	    {
	    	$qrCodeToken	= $qrCode;
	    }
	    
	    $qr->text($qrCodeToken);
	    $data['qr_link'] 			= $qr->get_link();
	    $data['client_id'] 			= $clientid;
	    $data['vofClientId'] 		= $vofClientId;
	    $data['vofStaffStatus'] 	= $voffStaff;
	    //return $qr->get_link();  
	    return $data;  
	 }	 
}



//Function to compress an image
function compress($source, $destination, $quality) 
{
	$info = getimagesize($source);

	if ($info['mime'] == 'image/jpeg') 
		$image = imagecreatefromjpeg($source);

	elseif ($info['mime'] == 'image/gif') 
		$image = imagecreatefromgif($source);

	elseif ($info['mime'] == 'image/png') 
		$image = imagecreatefrompng($source);

	imagejpeg($image, $destination, $quality);

	return $destination;
}


//Function to cache an image
function cacheThisImage($img)
{
	require_once 'externalLibraries/ImageCache.php';
	$baseurl=base_url();
	$imagecache = new ImageCache();
	//$imagecache->cached_image_directory = $baseurl. 'entreprenity/api/cachedImages/';
	$imagecache->cached_image_directory = dirname(__FILE__) . '/cachedImages';

	$cached_src_one = $imagecache->cache($img);
	//return $imagecache->cached_image_directory;
	return $cached_src_one;

}

//Arshad
//For base_url()
function base_url(){
	$base_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
	$base_url .= "://".$_SERVER['HTTP_HOST'].'/'; //will give : http(s)://somewebsite.com/
	
	/*----- Want to change something then change it here ------*/
	//$base_url .= 'projects/entreprenity/#/'; 
	
	return $base_url;
}


//Function to fetch events hosted by a company using companyid
//June 28,2016
//August 16,2016: HTML character encoding support
function fetchCompanyEvents($companyid)
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends	
	
	$data= array();
	
	$qry="SELECT * FROM entrp_events WHERE companyid=".$companyid." AND status=1";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
   $i=0; //to initiate count
   if($count_res>0)
   {
   	while($row=mysqli_fetch_array($res))
      {
      	$data[$i]['id']				=	$row['id'];
			$data[$i]['eventName']		=	$row['eventName'];
			$data[$i]['eventTagId']		=	$row['eventTagId'];
			$data[$i]['description']	=	htmlspecialchars_decode($row['description'],ENT_QUOTES);
			if($row['poster']!='')
			{
				$data[$i]['poster']			=	$row['poster'];
			}
			else
			{
				$data[$i]['poster']			=	$event_default_poster;
			}
			
			$data[$i]['city']				=	$row['city'];
			$data[$i]['date']				=	$row['event_date'];
			$data[$i]['time']				=	$row['event_time'];
			$data[$i]['joining']			=	goingForThisEventorNot($data[$i]['id']);
			$i++;
      }	
   }
   else
   {
   	$data[$i]['id']				=	"";
		$data[$i]['eventName']		=	"";
		$data[$i]['eventTagId']		=	"";
		$data[$i]['description']	=	"";
		$data[$i]['poster']			=	"";
		$data[$i]['city']				=	"";
		$data[$i]['date']				=	"";
		$data[$i]['time']				=	"";
   }
	
	return $data;

}


//Function to fetch events hosted by a company using company user name 
//(Not in use as of now. This is fetched from mother function internally)
//June 28,2016
function getEventsHostedByCompany()
{
	$companyUserName=validate_input($_GET['companyUserName']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
}



//Function to fetch a company's follower list
//May 13,2016
function getCompanyFollowers()
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
		
	$companyUserName=validate_input($_GET['company']);
	$companyid=getCompanyIdfromCompanyUserName($companyUserName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	$data=fetch_company_information_from_companyid($companyid);
	
	if($my_session_id)
	{
		$data['followed']= doIFollowThisCompany($my_session_id,$companyid);
	}
	
	$data['followersObjects']		= getThisCompanyfollowerObjects($companyid);
	
	return $data;
	/*	
	data = {
			"id": 1,
			"companyUserName": "nbbit",
			"profilePhoto": "company01.jpg",
			"coverPhoto": "memberCover01.jpg",
			"name": "Pet Studio.com",
			"location": "Fort Legend Tower",
			"followed": true,
			"followers": "1",
			"following": "1",
			"followersObjects": [
				{
					"id": 1,
					"username": "jordan",
					"avatar": "member01.jpg",
					"coverPhoto": "memberCover01.jpg",
					"firstName": "Jordan",
					"lastName": "Rains",
					"position": "Office Assistant",
					"company": [{
						"companyName": "Pet Studio.com",
						"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
					}]
				}
			]
		};	
	*/

}

//Function to fetch a member's follower list
//May 13,2016
function getMemberFollowers()
{
	
   //the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	$userName=validate_input($_GET['user']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	$data									= fetch_user_information_from_id($clientid);
	if($my_session_id)
	{
		$data['followed']				= doIFollowThisUser($my_session_id,$clientid);
	}
	
	$data['followersObjects']		= getThisUserfollowerObjects($clientid);
	
	return $data;

}


//Function to fetch a member's following list
//May 13, 2016
function getMemberFollowing()
{
	
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	$userName=validate_input($_GET['user']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	$data									= fetch_user_information_from_id($clientid);
	if($my_session_id)
	{
		$data['followed']				= doIFollowThisUser($my_session_id,$clientid);
	}
	
	$data['followingObjects']		= getThisUserfollowingObjects($clientid);
	
	return $data;		

		/*
		data = {
			"id": 1,
			"userName": "jordan",
			"avatar": "member01.jpg",
			"coverPhoto": "memberCover01.jpg",
			"firstName": "Jordan",
			"lastName": "Rains",
			"position": "Office Assistant",
			"company": [
				{
					"companyName": "Pet Studio.com",
					"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
				}
			],
			"followed": true,
			"followers": "1",
			"following": "1",
			"followingObjects": [
				{
					"id": 1,
					"username": "jordan",
					"avatar": "member01.jpg",
					"coverPhoto": "memberCover01.jpg",
					"firstName": "Jordan",
					"lastName": "Rains",
					"position": "Office Assistant",
					"company": [{
						"companyName": "Pet Studio.com",
						"companyDesc": "Lorem ipsum dolor sit amet, consectetur adipiscing elit."
					}]
				}
			]
		};
	 */

}



//Function to fetch basic user information
//May 09,2016
//August 10, 2016: Changes after implementing company-user relation
function getBasicUserInformation()
{
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
	
	$data= array();
  
	//$userid=validate_input($_GET['id']);
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	$userid=$my_session_id;
	if($userid)
	{
			/*
			$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
					 		 client_profile.avatar,client_profile.designation,client_profile.company_name,client_profile.cover_pic,
					 		 company_profiles.company_username
					FROM entrp_login
					LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
					//LEFT JOIN company_profiles ON entrp_login.clientid=company_profiles.clientid
					WHERE entrp_login.clientid=".$userid."
			      ";
			 */
			$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,
					 		 client_profile.avatar,client_profile.designation,client_profile.company_name,client_profile.cover_pic 
					FROM entrp_login
					LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
					WHERE entrp_login.clientid=".$userid."
			      ";
			$res=getData($qry);
		   $count_res=mysqli_num_rows($res);
		   if($count_res>0)
		   {
		   	while($row=mysqli_fetch_array($res))
		   	{
		   		$data['id']			=	$row['clientid'];
		   		
		   		if($row['avatar']!='')
   				{
   					$data['avatar']				=	$row['avatar'];
   				}
   				else
   				{
   					$data['avatar']				=	$member_default_avatar;
   				}
   				if($row['cover_pic']!='')
   				{
   					$data['coverPhoto']				=	$row['cover_pic'];
   				}
   				else
   				{
   					$data['coverPhoto']				=	'';
   				}
   				
   				if($row['firstname']!='')
   				{
   					$data['firstName']			=	$row['firstname'];
   				}
   				else
   				{
   					$data['firstName']			=	'';
   				}
   				
   				if($row['lastname']!='')
   				{
   					$data['lastName']				=	$row['lastname'];
   				}
   				else
   				{
   					$data['lastName']				=	'';
   				}
   				
   				if($row['username']!='')
   				{
   					$data['userName']				=	$row['username'];
   				}
   				else
   				{
   					$data['userName']				=	'';
   				}
   				
   				if($row['designation']!='')
   				{
   					$data['position']				=	$row['designation'];
   				}
   				else
   				{
   					$data['position']				=	'';
   				}
   				/*
   				if($row['company_name']!='')
   				{
   					$data['myOffice']				=	$row['company_name'];
   				}
   				else
   				{
   					$data['myOffice']				=	'';
   				}
   				
   				if($row['company_username']!='')
   				{
   					$data['companyUserName']				=	$row['company_username'];
   				}
   				else
   				{
   					$data['companyUserName']				=	'';
   				}
   				*/
		   	}	
		   	
		   	$companyID	=	getCompanyIDFromCompUserRelation($userid);
		   	$data['companyUserName']	=	getCompanyUserNameUsingCompUserRelation($companyID);	
		   	$data['myOffice']				=  getCompanyNameUsingCompUserRelation($companyID);	   
		   }
		   else
		   {
					$data['id']					=	'';
					$data['avatar']			=	'';
					$data['firstName']		=	'';
					$data['lastName']			=	'';
					$data['userName']			=	'';
					$data['position']			=	'';
					$data['myOffice']			=	'';		   
		   }	
	}
	return $data;

}



//Function to get an event's details
//April 25, 2016
//August 16,2016: HTML character encoding support
function viewEventDetail()
{
		/*
		{
	  "name": "Master The Art Of Selling",
	  "address": "10-f, Fort Legend Tower, 3rd Ave, Taguig, Metro Manila",
	  "gmapLong": 121.04692,
	  "gmapLat": 14.55408,
	  "date": "4-25-2016",
	  "startTime": "10:00",
	  "endTime": "19:00",
	  "eventPhoto": "event.jpg",
	  "about": "We will teach you on how to master selling and generate more sales for your brand or company",
	  "attendees": [
	    {
	      "id": "1",
	      "firstName": "Ken",
	      "lastName": "Sia",
	      "profilePhoto": "emp1.jpg"
	    },
	    {
	      "id": "2",
	      "firstName": "Jaye",
	      "lastName": "Atienza",
	      "profilePhoto": "emp2.jpg"
	    }
	  ]
	  }
	  */
	  
	//the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends
	
	
	$data= array();		
	
	$eventTagid=validate_input($_GET['id']);
	$eventid=getEventIdfromEventTag($eventTagid);
	if($eventid!='')
	{
		$qry="SELECT entrp_events.*,entrp_event_categories.category_name 
				FROM entrp_events 
				LEFT JOIN entrp_event_categories ON entrp_events.category=entrp_event_categories.id
			   WHERE entrp_events.id=".$eventid." AND entrp_events.status!=0
				";
		$res=getData($qry);
		$count_res=mysqli_num_rows($res);
		if($count_res>0)
		{
			while($row=mysqli_fetch_array($res))
	   	{
	   		$data['id']				=	$row['id'];
	   		$data['eventTagId']	=	$row['eventTagId'];
	   		$data['city']			=	$row['city'];
	   		$data['share_url']	=	$row['share_url'];
	   		$data['name']			=	$row['eventName'];
	   		$data['address']		=	$row['address'];
	
	   		$data['date_time_formatted']			=	date('Y/m/d H:i:s', strtotime($row['event_date_time']));
	   		$data['date']			=	$row['event_date'];
	   		$data['startTime']	=	$row['start_time'];
	   		$data['endTime']		=	$row['end_time'];
	   		$data['eventPhoto']	=	$row['clientid'];
	   		if($row['poster']!='')
	   		{
	   			$data['poster']	=	$row['poster'];
	   		}
	   		else
	   		{
	   			$data['poster']	=	$events_default;
	   		}
	   		
	   		$data['about']			=	htmlspecialchars_decode($row['description'],ENT_QUOTES);
	   		$data['category']		=	$row['category_name'];
	   		$data['map']['center']['latitude']		=	$row['location_lat'];
				$data['map']['center']['longitude']		=	$row['location_long'];
				$data['map']['zoom']	=	8;
	   	}
	   	
			$data['joining']			=	goingForThisEventorNot($eventid);
			$data['attendees']		=	getEventAttendeesFromEventID($eventid);
		}
		else
		{
			$data['id']										=	'';
			$data['name']									=	'';
			$data['address']								=	'';
			$data['map']['center']['latitude']		=	'';
			$data['map']['center']['longitude']		=	'';
			$data['map']['zoom']							=	8;
			$data['date']									=	'';
			$data['startTime']							=	'';
			$data['endTime']								=	'';
			$data['eventPhoto']							=	'';
			$data['poster']								=	'';
			$data['about']									=	'';
	   	$data['category']								=	'';		
		}	
	}
	return $data;
}




//Function to fetch a user profile
//April 22,2016
//Updated on May 03, 2016: to fetch secondary mobile and designation
//Updated May 11, 2016: To check if I follow this user or not
//August 10, 2016: Changes after implementing company-user relation
//August 16,2016: HTML character encoding support
//August 17,2016: Check if I am me
function viewUserProfile()
{
	//$clientid=validate_input($_GET['id']);
	
	$userName=validate_input($_GET['id']);
	$clientid=getUserIdfromUserName($userName);	
	
	$session_values=get_user_session();
	$my_session_id	= $session_values['id'];
	
	$data= array();	
	
	if($my_session_id)
	{
		$data['followed']= doIFollowThisUser($my_session_id,$clientid);
	}
	
  //the defaults starts
	global $myStaticVars;
	extract($myStaticVars);  // make static vars local
	$member_default_avatar 		= $member_default_avatar;
	$member_default_cover		= $member_default_cover;
	$member_default				= $member_default;
	$company_default_cover		= $company_default_cover;
	$company_default_avatar		= $company_default_avatar;
	$events_default				= $events_default;
	$event_default_poster		= $event_default_poster;
	//the defaults ends

	/*
   $qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,client_profile.city,client_profile.country,client_profile.contact_email as email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 client_profile.secondary_mobile,
			 		 location_info.location_desc,
			 		 company_profiles.company_name,company_profiles.description
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			LEFT JOIN company_profiles ON company_profiles.clientid=entrp_login.clientid
			WHERE entrp_login.clientid=".$clientid."
	      ";
	*/
	
	$qry="SELECT entrp_login.clientid,entrp_login.firstname,entrp_login.lastname,entrp_login.username,client_profile.city,client_profile.country,client_profile.contact_email as email,
			 		 client_profile.avatar,client_profile.cover_pic,client_profile.designation,client_profile.mobile,client_profile.website,client_profile.about_me,
			 		 client_profile.secondary_mobile,
			 		 location_info.location_desc
			FROM entrp_login
			LEFT JOIN client_profile ON entrp_login.clientid=client_profile.clientid
			LEFT JOIN location_info ON location_info.id=client_profile.client_location
			WHERE entrp_login.clientid=".$clientid."
	      ";
	$res=getData($qry);
   $count_res=mysqli_num_rows($res);
	if($count_res>0)
   {

   	while($row=mysqli_fetch_array($res))
      {
      	$data['id']				=	$row['clientid'];
      	
      	
   		if($row['avatar']!='')
   		{
   			$data['avatar']	=	$row['avatar'];
   		}
   		else
   		{
   			$data['avatar']	=	$member_default_avatar;
   		}  
   		   		
   		if($row['cover_pic']!='')
   		{
   			$data['coverPhoto']	=	$row['cover_pic'];
   		}
   		else
   		{
   			$data['coverPhoto']	=	'';
   		}  

			$data['firstName'] 	= 	$row['firstname'];
			$data['lastName'] 	= 	$row['lastname'];
			$data['userName'] 	= 	$row['username'];
			$data['position'] 	= 	$row['designation'];
			
			$data['city'] 			= 	$row['city'];
			
			$data['aboutMe'] 		=  htmlspecialchars_decode($row['about_me'],ENT_QUOTES);
			$data['email'] 		=  $row['email'];
			$data['website'] 		=  $row['website'];
			$data['mobile'] 		=  $row['mobile'];
			$data['tel'] 			=  $row['secondary_mobile'];			

			$data['success'] = true;
			$data['msg'] = 'Profile fetched';
		}
		
		$companyID	=	getCompanyIDFromCompUserRelation($clientid);
		   	
		$data['company']['companyName'] 		= getCompanyNameUsingCompUserRelation($companyID);	
		$data['company']['companyDesc'] 		= getCompanyDescriptionUsingCompUserRelation($companyID);	
		   	
   	$data['skills'] 		= get_user_skill_sets($clientid);
   	$data['interests'] 	= get_user_interest_sets($clientid);
   	
   	//Function to get total followers of a user
		$data['followers'] 	= user_followers($clientid);
		
		//Function to get total followings of a user
		$data['following'] 	= user_following($clientid);
		
		//if i am me
		if($data['id']==$my_session_id)
		{
			$data['myProfile'] 	= 1;
		}
		else
		{
			$data['myProfile'] 	= 0;
		}
   	   	
   }
   else
   {
   	$data['success'] = false;
		$data['msg'] = 'Please check your credentials once again';
   }
   return $data;
}




//Function to get session values
//April 18,2016
function get_user_session()
{
	if (!isset($_SESSION))
	{    
	    session_start();
	}    
	
	$sessions = array();
			
	$sessions['login_token'] 	= $_SESSION['login_token'];
	$sessions['firstname'] 		= $_SESSION['firstname'];
	$sessions['lastname'] 		= $_SESSION['lastname'];
	$sessions['id'] 				= $_SESSION['id'];
	$sessions['username'] 		= $_SESSION['username'];
	
	return $sessions;
}



?>
