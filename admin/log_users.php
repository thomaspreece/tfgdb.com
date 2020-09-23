<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/Includes/HeaderFunctions.php");
	if($FDBPrivilages['VULog']==2){	
		$stmt = $Gamedb->prepare("SELECT * FROM `userlog` WHERE 1 ORDER BY `Date` DESC");
		$stmt->execute();
		$TotalRows = $stmt->rowCount();
		$RowsPerPage = 30;
		$Pages = ceil($TotalRows/$RowsPerPage);
		echo "<div class='center'>
		<a class='log_previous' href='javascript:void(0)' onclick='previousPage();'>Previous</a>&nbsp;&nbsp;";
		for ($i = 1; $i <= $Pages; $i++) {
			echo "<a class='log_".$i."' href='javascript:void(0)' onclick='getPage(".$i.");'>".$i."</a>&nbsp;&nbsp;";
		}
		echo "<a class='log_next' href='javascript:void(0)' onclick='nextPage();'>Next</a></div>";		
		echo "<hr/>
		<div id='userPageContent'>
		
		</div>		
		<hr/>";
		echo "<div class='center'>
		<a class='log_previous' href='javascript:void(0)' onclick='previousPage();'>Previous</a>&nbsp;&nbsp;";
		for ($i = 1; $i <= $Pages; $i++) {
			echo "<a class='log_".$i."' href='javascript:void(0)' onclick='getPage(".$i.");'>".$i."</a>&nbsp;&nbsp;";
		}
		echo "<a class='log_next' href='javascript:void(0)' onclick='nextPage();'>Next</a></div>";
	}
?>	

<script>
var page = 1;
var pageMax = <?php echo $Pages;?>;
var pageURL = 'admin/log_users_content.php'

function loadPage()	//the function that loads pages via AJAX
{
    
	if(page==pageMax){
		$('.log_next').css({'display': 'none'})
	}else{
		$('.log_next').css({'display': 'inline'})
	}
	if(page==1){
		$('.log_previous').css({'display': 'none'})
	}else{
		$('.log_previous').css({'display': 'inline'})
	}
	
	
	$('#userPageContent').html("<div class='center'>Loading...</div>");
	for (i=1; i <= pageMax; i++){
		$('.log_'+i).css({'text-decoration': 'underline','color': 'black','font-weight': 'normal'})
	}
	$('.log_'+page).css({'text-decoration': 'none','color': 'black','font-weight': 'bold'})
	
    $.ajax({	//create an ajax request to load_page.php
        type: "GET",
        url: pageURL,
        data: 'Page='+page,	//with the page number as a parameter
        dataType: "html",	//expect html to be returned
        success: function(msg){

            if(parseInt(msg)!=0)	//if no errors
            {
                $('#userPageContent').html(msg);	//load the returned html into pageContet
                //$('#loading').css('visibility','hidden');	//and hide the rotating gif
            }else{
				$('#userPageContent').html("error");
			}
        }

    });

}

$(document).ready(function(){
	loadPage();
});

function nextPage()
{
	page = page + 1;
	if(page>pageMax){
		page = pageMax;
	}
	loadPage();
}

function previousPage()
{
	page = page - 1;	
	if(page<1){
		page = 1;
	}
	loadPage();
}

function getPage(number)
{
	page = number;
	loadPage();
}

</script>