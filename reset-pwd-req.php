<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 08-Jan-18
 * Time: 4:52 PM
 */
include "nenajaveniHeader.php";
require_once("./include/korisnicka_strana.php");

$emailsent = false;
if(isset($_POST['submitted']))
{
	if($fgmembersite->EmailResetPasswordLink())
	{
		$fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
		exit;
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
		<title>Барање за ресетирање на лозинка</title>
		<link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css" />
		<script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
	</head>
	<body>
		<!-- Form Code Start -->
		<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;">
			<div id='fg_membersite'>
				<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
					<fieldset >
						<legend>Ресетирање на лозинка</legend>

						<input type='hidden' name='submitted' id='submitted' value='1'/>

						<div class='short_explanation'>* задолжителни полиња</div>

						<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
						<div class='container'>
							<label for='username' >Email*:</label><br/>
							<input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
							<span id='resetreq_email_errorloc' class='error'></span>
						</div>
						<div class='short_explanation'>На вашата email адреса ќе биде испратен линк за ресетирање на лозинката.</div>
						<div class='container'>
							<input type='submit' class="btn btn-success navbar-btn" name='Submit' value='Испрати' />
						</div>

					</fieldset>
				</form>
				<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

				<script type='text/javascript'>
					// <![CDATA[

					var frmvalidator  = new Validator("resetreq");
					frmvalidator.EnableOnPageErrorDisplay();
					frmvalidator.EnableMsgsTogether();

					frmvalidator.addValidation("email","req","Внесете ја email адресата што ја искористивте за да се најавите.");
					frmvalidator.addValidation("email","email","Внесете ја email адресата што ја искористивте за да се најавите.");

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