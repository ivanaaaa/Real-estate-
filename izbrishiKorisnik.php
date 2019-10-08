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
			


			$korisnik_id =  mysqli_real_escape_string($conn,$_GET["id"]);

			//echo $korisnik_id;
			
			$sql = "DELETE FROM korisnici
			WHERE id = '$korisnik_id'";
			
			
			$result = mysqli_query($conn,$sql);
			
			header( "refresh:1;url=lista_moderator.php" );
				
			?>

		</div>

	</body>
</html>
