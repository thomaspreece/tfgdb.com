<?php
$MetaDescription = "Login to change and add games to the database";
$MetaKeywords = "Free,games,database,login,mac,windows,linux,genres,3D,2D";

$PageName = "Login.php";

include("./Includes/HeaderFunctions.php");
$MetaTitle = "Member Login - ".$DOMAINTITLE;
include("./Includes/Header.php");
?>

<?php

if(isset($_GET['Redirect'])){
	$Red = $_GET['Redirect'];
	$Red = preg_replace('/[^a-zA-Z0-9\.\?=&\/]/',"",$Red); //remove everything except a-z, A-Z, 0-9, '.', '?', '=', '&' and '/'

}else{
	$Red = "index.php";
}
echo "
<div style='height:25px;'></div>
<div class='news' >
	<div style=\"margin:5px;\" >
	<h1>Login</h1>
";

if($user->data['is_registered'])
{
	 echo "You are already logged in!<br/>
	<a href='".$Red."'>Click here </a>if you are not redirected within 3 seconds
	
	<script type=\"text/JavaScript\">

		setTimeout(\"location.href = '".$Red."';\",3000);

	</script>
	 
	 
	 ";
}else{
	
	echo "
	<form action=\"../Forum/ucp.php?mode=login\" method=\"post\" enctype=\"multipart/form-data\">
	<table width='100%'>
		<colgroup width='410'></colgroup>
		<colgroup width='410'></colgroup>
	<tr>
		<td style='vertical-align:top;'><div class='center'>
In order to login you must be registered. Registering takes only a few moments but gives you increased capabilities. The board administrator may also grant additional permissions to registered users. Before you register please ensure you are familiar with our terms of use and related policies. Please ensure you read any forum rules as you navigate around the board.<br/>
<a href='".$DOMAIN."/Forum/ucp.php?mode=terms'>Terms of use</a> | <a href='".$DOMAIN."/Forum/ucp.php?mode=privacy'>Privacy policy</a>
</div>
		</td>		
		<td>


		<input type=\"hidden\" name=\"redirect\" value=\"".$Red."\" />
		<table width='100%'>
			<colgroup width='*'></colgroup>
			<colgroup width='100px'></colgroup>
			<colgroup width='200px'></colgroup>
			<colgroup width='*'></colgroup>
		<tr>
			<td></td>
			<td><b>Username: </b></td>
			<td>
				<input style='width:200px;' type=\"text\" name=\"username\" size=\"10\" /><br/>
				
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td>		
				<div><a href='".$DOMAIN."/Forum/ucp.php?mode=register'>Register</a></div>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td><b>Password: </b></td>
			<td>		
				<input style='width:200px;' type=\"password\" id=\"password\" name=\"password\" size=\"10\" class=\"inputbox autowidth\" /><br/>
				
			</td>
			<td></td>
		</tr>	
		<tr>
			<td></td>
			<td></td>
			<td>		
				<div><a href='".$DOMAIN."/Forum/ucp.php?mode=sendpassword'>I forgot my password</a><br/>
				<a href='".$DOMAIN."/Forum/ucp.php?mode=resend_act'>Resend activation e-mail</a><br/>
				Remember Me?:<input type=\"checkbox\" name=\"autologin\"></div>
				
			</td>
			<td></td>
		</tr>


		
		
		</table>



		</td>
		
	</tr>
	</table>

	<div class='center' style='width:100%;'><input style='width:250px;' type=\"submit\"  value=\"Login\" name=\"login\" /></div>
	</form>

	";

}
echo "

	</div>
</div>
";

include("./Includes/Footer.php");
include("./Includes/FooterFunctions.php");
?>