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
		<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;padding-left: 40px;
    padding-right: 40px;">
			<?php
			$user_id =  $fgmembersite->User_id();


			$oglas_id = $_SESSION['oglasID'];

			
			//echo $oglas_id;
			
			$sql = "INSERT INTO zacuvani_oglasi ( korisnicko_id,oglasID)
			VALUES('$user_id','$oglas_id')";
			
			
			$result = mysqli_query($conn,$sql);
			
			if($result){
				echo"<h2>Вашиот оглас беше успешно зачуван!</h2>";
			}

			header( "refresh:2;url=zacuvaniOglasi.php" );
			
			?>

		</div>

	</body>
</html>
