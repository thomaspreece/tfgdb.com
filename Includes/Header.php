<?php

//Echo Header
Echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
<head>
<meta name=\"Description\" content=\"".$MetaDescription."\" />
<meta name=\"Keywords\" content=\"".$MetaKeywords."\" />
<meta http-equiv=\"content-language\" content=\"en-US\" />
<title>".$MetaTitle."</title>
";


//Load CSS
echo "
<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/MainStyle.css')."\" type=\"text/css\" media=\"screen\" />
<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/jquery-ui-1-10-3.css')."\" type=\"text/css\" media=\"screen\" />
<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/svwp_style_min.css')."\" type=\"text/css\" media=\"screen\" /> 
<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/nivo-slider_min.css')."\" type=\"text/css\" media=\"screen\" />
<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/jquery.nivo.slider.pack_min.css')."\" type=\"text/css\" media=\"screen\" />
";


//Load JS
echo "
<script src=\"".$DOMAIN.auto_version('/scripts/jquery-1.10.2_min.js')."\"></script>
<script src=\"".$DOMAIN.auto_version('/scripts/jquery.slideViewerPro.1.5_min.js')."\" type=\"text/javascript\"></script>
<script src=\"".$DOMAIN.auto_version('/scripts/jquery.timers_min.js')."\" type=\"text/javascript\"></script>
<script src=\"".$DOMAIN.auto_version('/scripts/jquery-ui-1.10.3.modified_min.js')."\"></script>
<script src=\"".$DOMAIN.auto_version('/scripts/jquery.nivo.slider.pack_min.js')."\"></script>
";



//Load Page Specific JS and CSS files
switch ($PageName) {
	Case "GetGame.php":
		echo "
		<script type=\"text/javascript\" src=\"".$DOMAIN.auto_version('/scripts/jRating.jquery_min.js')."\"></script>
		<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/jquery.jRating_min.css')."\" type=\"text/css\"  media=\"screen\" />
		";
		break;
	case "Bundles.php":
		echo "
		<script type=\"text/javascript\" src=\"".$DOMAIN.auto_version('/scripts/jquery.countdown_min.js')."\"></script>
		<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/jquery.countdown_min.css')."\" type=\"text/css\" media=\"screen\" />
		";
		break;
		
	case "EditGame.php":
	case "AddGame.php":
		echo "
		<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/jquery.validation_min.css')."\" type=\"text/css\" media=\"screen\" />
	
		<script src=\"".$DOMAIN.auto_version('/scripts/jquery.validationEngine_min.js')."\"></script>
		<script src=\"".$DOMAIN.auto_version('/scripts/jquery.validationEngine-en_min.js')."\"></script>
		";
		break;
	case "index.php":
		echo "
		<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/jquery.slidorion_min.css')."\" type=\"text/css\" media=\"screen\" />
		<script src=\"".$DOMAIN.auto_version('/scripts/jquery.easing_min.js')."\"></script>
		<script src=\"".$DOMAIN.auto_version('/scripts/jquery.slidorion_min.js')."\"></script>
		
		<link rel=\"stylesheet\" href=\"".$DOMAIN.auto_version('/css/mosaic.1.0.1_min.css')."\" type=\"text/css\" media=\"screen\" />
		<script src=\"".$DOMAIN.auto_version('/scripts/mosaic.1.0.1_min.js')."\"></script>
		";
		break;
}

//Load Global JS Start Code
echo "
<script>
$(function() {
	$( document ).tooltip();
});
</script>
";

//Load Page Specific JS Start Code
switch ($PageName) {
//index.php----------------------------------------
	Case "index.php":
		echo "
		<script type=\"text/javascript\">
		$(document).ready(function () {	
			$('#BannerSlider').nivoSlider({
				effect:\"fold\",
			directionNav: false,
			pauseTime: 6000
			});
		});
		$(document).ready(function(){
			$('#CenterImageSlider').slidorion({
				effect: 'slideUp',
				autoPlay: false,
				speed: 1000
			});
		});
		jQuery(function($){
			$('.fadeMosaic').mosaic();
		});
		
		</script>
		";		
		break;
//Admin.php----------------------------------------
	Case "Admin.php":
		echo "
		<script type='text/javascript'>
		$(function() {
			$( \"#accordion\" ).accordion({
			active: false,
			collapsible: true
			});
		});
		</script>
		";	
		break;
//AddGame.php----------------------------------------
	Case "AddGame.php":
		echo "
		<script type='text/javascript'>
		$(document).ready(function(){
			$(\"#GameDataForm\").validationEngine('attach',{scrollOffset: 80, showOneMessage: true});	
		});
		</script>
		";	
		break;
//EditGame.php----------------------------------------	
	Case "EditGame.php":
		echo "
		<script type='text/javascript'>
		$(function() {
			$( \"#accordion\" ).accordion({
			active: false,
			collapsible: true
			});
		});
		$(document).ready(function(){
			$(\"#GameDataForm\").validationEngine('attach',{scrollOffset: 80, showOneMessage: true});
			$(\"#WebLinkForm\").validationEngine('attach',{scrollOffset: 80, showOneMessage: true});
			$(\"#LinkForm\").validationEngine('attach',{scrollOffset: 80, showOneMessage: true});
			$(\"#ReviewForm\").validationEngine('attach',{scrollOffset: 80, showOneMessage: true});
			
		});
		</script>
		";
		break;
//GetGame.php--------------------------------------
	Case "GetGame.php":
echo "
<script type=\"text/javascript\">
	var Pageid = ".$_REQUEST['ID'].
";
	$(window).load(function() {

		$(\"div#screenshotsslide\").slideViewerPro({
		thumbs: 3,
		autoslide: false,
		asTimer: 3500,
		typo: false,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 60,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 27,
		shuffle: true
		});
	
		$(\"div#fanartslide\").slideViewerPro({
		thumbs: 3,
		autoslide: false,
		asTimer: 3500,
		typo: false,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 60,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 27,
		shuffle: true
		});	
	
		$(\"div#bannerslide\").slideViewerPro({
		thumbs: 3,
		autoslide: false,
		asTimer: 3500,
		typo: false,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 60,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 35,
		shuffle: true
		});	
	
		$(\"div#frontbackslide\").slideViewerPro({
		thumbs: 2,
		autoslide: false,
		asTimer: 3500,
		typo: false,
		galBorderWidth: 0,
		thumbsBorderOpacity: 0,
		thumbsActiveBorderColor: \"grey\",
		buttonsWidth: 60,
		thumbsActiveBorderOpacity: 0.8,
		thumbsPercentReduction: 35,
		shuffle: true
		});	
	 

		$( \"#accordion3\" ).tabs();
        
    	});

	$(window).ready(function() {

		$(\".RatingDiv\").jRating({
			 step:false,
			 length : 5,
			 phpPath : \"".$DOMAIN."/GameScript.php\",";
		if (true || $FDBPrivilages['ARating'] > 0){

		}else{
				echo "
			isDisabled : true,";
		}
			echo "		 
			 onSuccess : function(){
				alert('Rating saved');
			 },
			 onError : function(){
				alert('There was an error saving your rating');
			 },
			 rateMax: 10,
			 canRateAgain : true,
			 nbRates : 1000
		});
	

		$( \"#accordion\" ).accordion({
			active: false,
			collapsible: true
			});
    
		$( \"#accordion2\" ).accordion({
			active: false,
			collapsible: true
		});
    
        	

	});



	
    </script>";

	break;
//---------------------------------------------



}	

//Continue Head
Echo "</head>

<body style='margin:0px;border:0px'>
<div style='position:relative; min-height:100%;'>
";

//Load Top Menu
require_once("./Includes/TopMenu.php");
/*
<table class='MainTable' style='width:100%;' height='100%'>
<colgroup>
	<col width='*'>
	<col width='992px'>
	<col width='*'>
</colgroup>

<tr>
	<td></td>
	<td>
*/
/*
		<table width='100%' class='WhiteMiddle'>
			<colgroup width='10'></colgroup>
			<colgroup width='*'></colgroup>
			<colgroup width='10'></colgroup>
			<tr>
				<td class='AlignTop' > 
				</td>
				<td class='AlignTop' >
*/


echo "

	<div style='min-height:100%; width:992px;margin-left:auto;margin-right:auto;'> 
		<div class='WhiteMiddle' style='min-height:100%; padding-left:5px;padding-right:5px'>
	

				<div style='height:45px;'>
				</div>
";

if(ISSET($_REQUEST['Error'])){
echo "<div class='news' style='font-size:14px;text-align:center;'>";
	switch($_REQUEST['Error']){
		case "Failed":
			echo "Last Operation Failed";
			break;
		default:
			echo "Last Operation Completed Successfully";
			break;
	}
echo "</div>
";
}

if(!($user->data['is_registered']) || $user->data['user_points'] < 10){
	if($PageName == "Games.php"){
	echo "
	<div class='news' style='width:970px;height:90px;margin-left:auto;margin-right:auto;margin-top:20px;margin-bottom:0px'>
	<script type=\"text/javascript\"><!--
	google_ad_client = \"ca-pub-9094624708667650\";
	/* tfgdb.com */
	google_ad_slot = \"6777651075\";
	google_ad_width = 970;
	google_ad_height = 90;
	//-->
	</script>
	<script type=\"text/javascript\"
	src=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">
	</script>
	</div>
	<div class='news' style='padding-top:5px;margin-right:1px;margin-bottom:5px;margin-top:0px;float:right'>
		<div style='height:5px;'></div>
		<a href='".$DOMAIN."/HideAdverts.php'>Hide Adverts</a>
	</div>
	";
	}
}
?>