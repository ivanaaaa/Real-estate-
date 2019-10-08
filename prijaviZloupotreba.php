<?php
require_once("./include/korisnicka_strana.php");


if (!$fgmembersite->CheckLogin()) {
	include "nenajaveniHeader.php";
}
else
	include "najaveniHeader.php";

require_once("include/class.phpmailer.php");

$mail_naslov = $_POST['mail_naslov'];
$mail_poraka = $_POST['mail_poraka'];
$id = $_POST['oglas_id'];


$mailer = new PHPMailer();

$mailer->CharSet = 'utf-8';


$mailer->AddAddress("gasoline898@outlook.com");

$mailer->Subject = $mail_naslov;

$mailer->Body = $mail_poraka . "\r\n" . "Оглас ИД: ".$id;


if(!$mailer->Send())
{
	$isprateno =0;
}
else
	$isprateno =1;

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
	</head>
	<body>
		<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;padding-left: 40px;
									  padding-right: 40px;">
			<?php

			
			if($isprateno){
				echo"<h2>Вашата пријава беше успешно испратена!</h2>";
			}

			//header( "refresh:2;url=zacuvaniOglasi.php" );

			else
				echo"<h2>Се случи грешка при испраќањето на пријавата.</h2>";
			?>

		</div>

	</body>
</html>