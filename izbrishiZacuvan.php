<?php

include "najaveniHeader.php";
include "connection.php";
require_once("./include/korisnicka_strana.php");

if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL("login.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
	</head>
	<body>
		<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;">
			<?php
			$user_id =  $fgmembersite->User_id();


			$oglas_id =  mysqli_real_escape_string($conn,$_GET["id"]);

			
			
			$sql = "DELETE FROM zacuvani_oglasi
			WHERE korisnicko_id = '$user_id' AND oglasID = '$oglas_id'";
			
			
			$result = mysqli_query($conn,$sql);
			
			if($result){
				echo"<h2> Вашиот оглас беше успешно избришан од зачувани огласи!</h2>";
			}
				
			header( "refresh:2;url=zacuvaniOglasi.php" );
			
			?>
	
	
		</div>

	</body>
	<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>
	
</footer>
</html>
