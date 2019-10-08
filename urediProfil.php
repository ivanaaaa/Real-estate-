<?php

include "najaveniHeader.php";
include "connection.php";
require_once("./include/korisnicka_strana.php");

if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL("login.php");
	exit;
}

if(isset($_POST['submitted']))
{
    if($fgmembersite->ChangePassword())
    {
      echo '<script type="text/javascript">alert("Вашата лозинка е успешно променета!");</script>';
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Change password</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>
</head>
	<body>
		<div class="container urediProfil " style="margin:30 auto;background-color:whitesmoke;border-radius:4px;">
			<?php
			$user_id =  $fgmembersite->User_id();

			

			$sql = mysqli_query($conn,"SELECT *
				FROM korisnici
				WHERE korisnici.id = '$user_id'
				") or die("Error");

			$row = mysqli_fetch_array($sql);
			
			
			if(isset($_POST['userUpdate'])){
				
				$ime = mysqli_real_escape_string($conn,$_POST['ime']);
				$email = mysqli_real_escape_string($conn,$_POST['email']);
				$telefon = mysqli_real_escape_string($conn,$_POST['telefon']);
				
				$sql = "UPDATE korisnici 
						SET ";
				
				
				if(!empty($ime))
					$sql .= " ime = '$ime' ";
				
				
				
				if (!empty($ime) && !empty($email))
					$sql .= " , email = '$email'";
				else if (empty($ime) && !empty($email))
					$sql .= "email = '$email'";
				
				
				
				
				if(!empty($ime) && !empty($email) && !empty($telefon))
					$sql .= " , telefon = '$telefon'";
				
				else if (empty($ime) && !empty($email) && !empty($telefon))
					$sql .= " , telefon = '$telefon'";
				
				else if (!empty($ime) && empty($email) && !empty($telefon))
					$sql .= " , telefon = '$telefon'";			
				
				else if(!empty($telefon))
					$sql .= "  telefon = '$telefon'";
				
				
				
				$sql .= " WHERE id = '$user_id'";
				
				$sendSQL =mysqli_query($conn,$sql);
				if($sendSQL)
					echo '<script type="text/javascript">alert("Вашите податоци е успешно променети!");</script>';
				
				header( "refresh:1;" );
			}

			?>
			<form action="urediProfil.php" method="post">
				<table>
				<tr>
					<td>
						<p style="font-size:15px;margin:5px;">Име:</p>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="ime" placeholder="<?=$row['ime']; ?>" class="form-control">
					</td>
				</tr>
				<tr>
					<td>
						<p style="font-size:15px;margin:5px;">E-mail:</p>
					</td>
					<td>
						<p style="font-size:15px;margin:5px;">Телефон:</p>
					</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="email" placeholder="<?=$row['email']; ?>" class="form-control">
					</td>
					<td>
						<input type="text" name="telefon" placeholder="<?php
																	   
																	   if($row['telefon'] == "")
																	   echo "немате внесено телефон";
																	   
																	   else
																		   echo $row['telefon'];
																	   
																	   ?>" class="form-control">
					</td>
				</tr>
				
				<tr>
					<td>
						<input type="submit" class="btn btn-default btn-success" name="userUpdate" value="Зачувај промени">

					</td>
				</tr>
			</table>
			</form>
			

		



<!-- Form Code Start -->
<div id='fg_membersite'>
    <form id='changepwd' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
        <fieldset >
            <legend>Промена на вашата лозинка</legend>

            <input type='hidden' name='submitted' id='submitted' value='1'/>

            <div class='short_explanation'>* задолжителни полиња</div>

            <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
            <div class='container'>
                <label for='oldpwd' >Стара лозинка*:</label><br/>
                <div class='pwdwidgetdiv' id='oldpwddiv' ></div><br/>
                <noscript>
                    <input type='password' name='oldpwd' id='oldpwd' maxlength="50" />
                </noscript>
                <span id='changepwd_oldpwd_errorloc' class='error'></span>
            </div>

            <div class='container'>
                <label for='newpwd' >Нова лозинка*:</label><br/>
                <div class='pwdwidgetdiv' id='newpwddiv' ></div>
                <noscript>
                    <input type='password' name='newpwd' id='newpwd' maxlength="50" /><br/>
                </noscript>
                <span id='changepwd_newpwd_errorloc' class='error'></span>
            </div>

            <br/><br/><br/>
            <div class='container'>
                <input type='submit' class="btn btn-default btn-success" name='Submit' value='Испрати' />
            </div>

        </fieldset>
    </form>
    <!-- client-side Form Validations:
    Uses the excellent form validation script from JavaScript-coder.com-->

    <script type='text/javascript'>
        // <![CDATA[
        var pwdwidget = new PasswordWidget('oldpwddiv','oldpwd');
        pwdwidget.enableGenerate = false;
        pwdwidget.enableShowStrength=false;
        pwdwidget.enableShowStrengthStr =false;
        pwdwidget.MakePWDWidget();

        var pwdwidget = new PasswordWidget('newpwddiv','newpwd');
        pwdwidget.MakePWDWidget();


        var frmvalidator  = new Validator("changepwd");
        frmvalidator.EnableOnPageErrorDisplay();
        frmvalidator.EnableMsgsTogether();

        frmvalidator.addValidation("oldpwd","req","Внесете ја вашата стара лозинка");

        frmvalidator.addValidation("newpwd","req","Внесете ја вашата нова лозинка");

        // ]]>
    </script>


</div>
</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>
	
</footer>
</html>