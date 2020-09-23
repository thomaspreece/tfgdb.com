<?php 
//Setup PHPBB Forum and User Control
if(ISSET($InForum) == 0){
	define('IN_PHPBB', true);
	$phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/Forum/';
	$phpEx = substr(strrchr(__FILE__, '.'), 1);
	require($phpbb_root_path . 'common.' . $phpEx);
	require($phpbb_root_path . "includes/functions_user.php");
	$user->session_begin();
	$auth->acl($user->data);
	$user->setup();
}else{
	$phpbb_root_path = $_SERVER['DOCUMENT_ROOT'].'/Forum/';
	require_once($phpbb_root_path . "includes/functions_user.php");
}

require($_SERVER['DOCUMENT_ROOT']."/Includes/PointsValues.php");

if($user->data['is_registered']){
	$FDBPrivilages = array(	
	"V18+Game" => 0, //View 18+ Games
	"AGame" => 2, //Add Games
	"EGame" => 2, //Edit Games
	"NGame" => 1, //Rename Games
	"DGame" => 0, //Delete Games
	"MGame" => 0, //Merge Games
	"RGame" => 0, //Restore Games
	
	"ALinks" => 1, //Add Links
	"DLinks" => 0, //Delete Links
	"FLinks" => 2, //Flag Links
	"MLinks" => 0, //Moderate Links
	"RLinks" => 0, //Restore Links
	
	"AWLinks" => 1, //Add Web Links
	"DWLinks" => 0, //Delete Web Links
	"FWLinks" => 2, //Flag Web Links
	"MWLinks" => 0, //Moderate Web Links
	"RWLinks" => 0, //Restore Web Links
	
	"ABundles" => 1, //Add Bundle
	"DBundles" => 0, //Delete Bundle
	"FBundles" => 2, //Flag Bundles
	"MBundles" => 0, //Moderate Bundles
	
	"VAPanel" => 0, //View Admin Panel
	"VMLog" => 0, //View Moderator Log
	"VULog" => 0, //View User Log
	"VCLog" => 0, //View Cron Log
	
	"AResources" => 2, //Add Resources
	"DResources" => 0, //Delete Resources
	"MResources" => 0, //Moderate Resources
	"FResources" => 2, //Flag Resources
	"RResources" => 0, //Restore Resources
	
	"AReviews" => 2, //Add Reviews
	"MReviews" => 0, //Moderate Reviews
	"DReviews" => 0, //Delete Reviews
	"FReviews" => 2, //Flag Reviews
	"RReviews" => 0, //Restore Reviews
	
	"ARating" => 2, //Add Ratings
	"MRating" => 0 //Moderate Ratings			
	);
	$UserMembership = 1;
	
	//Super Moderator
	if(group_memberships(12,$user->data['user_id'],true)){
		$FDBPrivilages = array(
		"V18+Game" => 2, //View 18+ Games
		"AGame" => 2, //Add Games
		"EGame" => 2, //Edit Games
		"NGame" => 2, //Rename Games
		"DGame" => 2, //Delete Games
		"MGame" => 2, //Merge Games
		"RGame" => 2, //Restore Games
		
		"ALinks" => 2, //Add Links
		"DLinks" => 2, //Delete Links
		"FLinks" => 2, //Flag Links
		"MLinks" => 2, //Moderate Links
		"RLinks" => 2, //Restore Links
		
		"AWLinks" => 2, //Add Web Links
		"DWLinks" => 2, //Delete Web Links
		"FWLinks" => 2, //Flag Web Links
		"MWLinks" => 2, //Moderate Web Links
		"RWLinks" => 2, //Restore Web Links
		
		"ABundles" => 2, //Add Bundle
		"DBundles" => 2, //Delete Bundle
		"FBundles" => 2, //Flag Bundles
		"MBundles" => 2, //Moderate Bundles
		
		"VAPanel" => 2, //View Admin Panel
		"VMLog" => 2, //View Moderator Log
		"VULog" => 2, //View User Log
		"VCLog" => 2, //View Cron Log
		
		"AResources" => 2, //Add Resources
		"DResources" => 2, //Delete Resources
		"MResources" => 2, //Moderate Resources
		"FResources" => 2, //Flag Resources
		"RResources" => 2, //Restore Resources
		
		"AReviews" => 2, //Add Reviews
		"MReviews" => 2, //Moderate Reviews
		"DReviews" => 2, //Delete Reviews
		"FReviews" => 2, //Flag Reviews
		"RReviews" => 2, //Restore Reviews
		
		"ARating" => 2, //Add Ratings
		"MRating" => 0 //Moderate Ratings				
		);
		$UserMembership = 25;
	}
	
	//Global Moderator
	if(group_memberships(9,$user->data['user_id'],true)){
		$FDBPrivilages = array(
		"V18+Game" => 2, //View 18+ Games
		"AGame" => 2, //Add Games
		"EGame" => 2, //Edit Games
		"NGame" => 2, //Rename Games
		"DGame" => 2, //Delete Games
		"MGame" => 2, //Merge Games
		"RGame" => 2, //Restore Games
		
		"ALinks" => 2, //Add Links
		"DLinks" => 2, //Delete Links
		"FLinks" => 2, //Flag Links
		"MLinks" => 2, //Moderate Links
		"RLinks" => 2, //Restore Links
		
		"AWLinks" => 2, //Add Web Links
		"DWLinks" => 2, //Delete Web Links
		"FWLinks" => 2, //Flag Web Links
		"MWLinks" => 2, //Moderate Web Links
		"RWLinks" => 2, //Restore Web Links
		
		"ABundles" => 2, //Add Bundle
		"DBundles" => 2, //Delete Bundle
		"FBundles" => 2, //Flag Bundles
		"MBundles" => 2, //Moderate Bundles
		
		"VAPanel" => 2, //View Admin Panel
		"VMLog" => 0, //View Moderator Log
		"VULog" => 2, //View User Log
		"VCLog" => 0, //View Cron Log
		
		"AResources" => 2, //Add Resources
		"DResources" => 2, //Delete Resources
		"MResources" => 2, //Moderate Resources
		"FResources" => 2, //Flag Resources
		"RResources" => 2, //Restore Resources
		
		"AReviews" => 2, //Add Reviews
		"MReviews" => 2, //Moderate Reviews
		"DReviews" => 2, //Delete Reviews
		"FReviews" => 2, //Flag Reviews
		"RReviews" => 2, //Restore Reviews
		
		"ARating" => 2, //Add Ratings
		"MRating" => 2 //Moderate Ratings				
		);
		$UserMembership = 20;
	}
	
	//Link Moderator
	if(group_memberships(13,$user->data['user_id'],true)){
		$FDBPrivilages = array(	
		"V18+Game" => 2, //View 18+ Games
		"AGame" => 2, //Add Games
		"EGame" => 2, //Edit Games
		"NGame" => 1, //Rename Games
		"DGame" => 0, //Delete Games
		"MGame" => 0, //Merge Games
		"RGame" => 0, //Restore Games
		
		"ALinks" => 2, //Add Links
		"DLinks" => 0, //Delete Links
		"FLinks" => 0, //Flag Links
		"MLinks" => 2, //Moderate Links
		"RLinks" => 0, //Restore Links
		
		"AWLinks" => 2, //Add Web Links
		"DWLinks" => 0, //Delete Web Links
		"FWLinks" => 0, //Flag Web Links
		"MWLinks" => 2, //Moderate Web Links
		"RWLinks" => 0, //Restore Web Links
		
		"ABundles" => 2, //Add Bundle
		"DBundles" => 0, //Delete Bundle
		"FBundles" => 0, //Flag Bundles
		"MBundles" => 2, //Moderate Bundles
		
		"VAPanel" => 2, //View Admin Panel
		"VMLog" => 0, //View Moderator Log
		"VULog" => 0, //View User Log
		"VCLog" => 0, //View Cron Log
		
		"AResources" => 2, //Add Resources
		"DResources" => 0, //Delete Resources
		"MResources" => 2, //Moderate Resources
		"FResources" => 0, //Flag Resources
		"RResources" => 0, //Restore Resources
		
		"AReviews" => 2, //Add Reviews
		"MReviews" => 2, //Moderate Reviews
		"DReviews" => 0, //Delete Reviews
		"FReviews" => 0, //Flag Reviews
		"RReviews" => 0, //Restore Reviews
		
		"ARating" => 2, //Add Ratings
		"MRating" => 2 //Moderate Ratings		
		);
		$UserMembership = 15;
	}
}else{
	$FDBPrivilages = array(	
	"V18+Game" => 0, //View 18+ Games
	"AGame" => 0, //Add Games
	"EGame" => 0, //Edit Games
	"NGame" => 0, //Rename Games
	"DGame" => 0, //Delete Games
	"MGame" => 0, //Merge Games
	"RGame" => 0, //Restore Games
	
	"ALinks" => 0, //Add Links
	"DLinks" => 0, //Delete Links
	"FLinks" => 0, //Flag Links
	"MLinks" => 0, //Moderate Links
	"RLinks" => 0, //Restore Links
	
	"AWLinks" => 0, //Add Web Links
	"DWLinks" => 0, //Delete Web Links
	"FWLinks" => 0, //Flag Web Links
	"MWLinks" => 0, //Moderate Web Links
	"RWLinks" => 0, //Restore Web Links
	
	"ABundles" => 0, //Add Bundle
	"DBundles" => 0, //Delete Bundle
	"FBundles" => 0, //Flag Bundles
	"MBundles" => 0, //Moderate Bundles
	
	"VAPanel" => 0, //View Admin Panel
	"VMLog" => 0, //View Moderator Log
	"VULog" => 0, //View User Log
	"VCLog" => 0, //View Cron Log
	
	"AResources" => 0, //Add Resources
	"DResources" => 0, //Delete Resources
	"MResources" => 0, //Moderate Resources
	"FResources" => 0, //Flag Resources
	"RResources" => 0, //Restore Resources
	
	"AReviews" => 0, //Add Reviews
	"MReviews" => 0, //Moderate Reviews
	"DReviews" => 0, //Delete Reviews
	"FReviews" => 0, //Flag Reviews
	"RReviews" => 0, //Restore Reviews
	
	"ARating" => 0, //Add Ratings
	"MRating" => 0 //Moderate Ratings
	);
	$UserMembership = -1;
}

 
/*
Super Moderator able to: =12
All Privilages as Global Moderator
View all activities on site 

Global Moderator able to: =9
Change Game Name
Delete Games
Approve & Delete Links
Approve Website Links
Approve & Delete Bundles

Link Moderator able to: =13
Approve Links
Approve Website Links
Approve Bundles

Game Adder able to: =14
Add Games unmoderated
Add Resources unmoderated

//0 - No
//1 - Moderated
//2 - Not Moderated

		$FDBPrivilages = array[	
		"AGame" => 2, //Add Games
		"EGame" => 2, //Edit Games
		"NGame" => 2, //Rename Games
		"DGame" => 2, //Delete Games
		"ALinks" => 2, //Add Links
		"DLinks" => 2, //Delete Links
		"FLinks" => 0, //Flag Links
		"MLinks" => 2, //Moderate Links
		"AWLinks" => 2, //Add Web Links
		"DWLinks" => 2, //Delete Web Links
		"FWLinks" => 0, //Flag Web Links
		"MWLinks" => 2, //Moderate Web Links
		"ABundles" => 2, //Add Bundle
		"DBundles" => 2, //Delete Bundle
		"FBundles" => 0, //Flag Bundles
		"MBundles" => 2, //Moderate Bundles
		"VMLog" => 0, //View Moderator Log
		"VULog" => 2, //View User Log
		"AResources" => 2, //Add Resources
		"DResources" => 2 //Delete Resources
		];

*/

?>