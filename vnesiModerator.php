<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 18-Jan-18
 * Time: 10:23 PM
 */
include "connection.php";
include "najaveniHeader.php";
require_once("./include/korisnicka_strana.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
if(isset($_POST['vnesiModerator'])) {

    $ime = mysqli_real_escape_string($conn, $_POST['ime']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefon = mysqli_real_escape_string($conn, $_POST['telefon']);
    $confirmcode = 'y';
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $tip_korisnik = 'модератор';


    $korisnik = $fgmembersite->User_id();

    $proverka = mysqli_query($conn, "SELECT tip_korisnik,username FROM korisnici");
// proverka dali veke postoe toa korisnicko ime za moderator
    if ($proverka->num_rows > 0)
        while ($row = $proverka->fetch_assoc()) {
            if ($username == $row['username'])
            {
                echo '<script type="text/javascript">alert("Ова име на модератор веќе постои!");</script>';
            }
            else
            {
                //Vnesi vo bazata na korisnici pdatocite za moderator
                $sql = "INSERT INTO korisnici ( ime, email, telefon, confirmcode, username, password,tip_korisnik)
	VALUES('$ime','$email','$telefon','$confirmcode','$username',md5('$password'),'$tip_korisnik')";

            }
        }
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo '<script type="text/javascript">alert("Модераторот е успешно внесен!");</script>';
        }
    }


?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Внеси модератор</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>

</head>
<body style="background-color:#E0E0E0;">

<div class="container">

	<div class = "left">
		<table>
			<form method="post" action="vnesiModerator.php" enctype='multipart/form-data'>


				<tr>
                    <td  style="font-size:15px;margin:5px;">Име:</td>
					<td><input required type='text' name="ime" class="form-control"></td>
				</tr>


                <tr>
                    <td  style="font-size:15px;margin:5px;">Email:</td>
                    <td><input required type='email' name="email" class="form-control"></td>
                </tr>


                <tr>
                    <td  style="font-size:15px;margin:5px;">Телефонски број:</td>
                    <td><input required type='text' name="telefon" class="form-control"></td>
                </tr>


                <tr>
                    <td  style="font-size:15px;margin:5px;">Корисничко име:</td>
                    <td><input required type='text' name="username" class="form-control"></td>
                </tr>


                <tr>
                    <td  style="font-size:15px;margin:5px;">Лозинка:</td>
                    <td><input required type='text' name="password" class="form-control"></td>
                </tr>



				<tr >
					<td>

						<input type="submit" class="btn btn-default btn-success" name="vnesiModerator" value="Внеси го модераторот">

					</td>
				</tr>
			</form>
		</table>

	</div>
</div>
</body>
<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>
	
</footer>
</html>
