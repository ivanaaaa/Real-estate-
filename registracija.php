<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 06-Jan-18
 * Time: 9:06 PM
 */
include "nenajaveniHeader.php";
require_once("./include/korisnicka_strana.php");
if(isset($_POST['submitted']))
{
    if($fgmembersite->RegisterUser())
    {
        $fgmembersite->RedirectToURL("thank-you.php");
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Регистрација</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>
</head>
<body>


<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;">

<!-- Form Code Start -->
<div id='fg_membersite'>
    <form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
        <fieldset >
            <legend>Регистрација</legend>

            <input type='hidden' name='submitted' id='submitted' value='1'/>

            <div class='short_explanation'>* задолжителни полиња</div>
            <input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

            <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
            <div class='container'>
                <label for='name' >Вашето целосно име*: </label><br/>
                <input type='text' name='name' id='name' value='<?php echo $fgmembersite->SafeDisplay('name') ?>' maxlength="50" /><br/>
                <span id='register_name_errorloc' class='error'></span>
            </div>
            <div class='container'>
                <label for='email' >Email*:</label><br/>
                <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
                <span id='register_email_errorloc' class='error'></span>
            </div>
            <div class='container'>
                <label for='username' >Корисничко име*:</label><br/>
                <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
                <span id='register_username_errorloc' class='error'></span>
            </div>
            <div class='container' style='height:80px;'>
                <label for='password' >Лозинка*:</label><br/>
                <div class='pwdwidgetdiv' id='thepwddiv' ></div>
                <noscript>
                    <input type='password' name='password' id='password' maxlength="50" />
                </noscript>
                <div id='register_password_errorloc' class='error' style='clear:both'></div>
            </div>

            <div class='container'>
                <input type='submit' class="btn btn-success navbar-btn" name='Submit' value='Испрати' />
            </div>

        </fieldset>
    </form>
	</div></div>
    <!-- client-side Form Validations:
    Uses the excellent form validation script from JavaScript-coder.com-->

    <script type='text/javascript'>
        // <![CDATA[
        var pwdwidget = new PasswordWidget('thepwddiv','password');
        pwdwidget.MakePWDWidget();

        var frmvalidator  = new Validator("register");
        frmvalidator.EnableOnPageErrorDisplay();
        frmvalidator.EnableMsgsTogether();
        frmvalidator.addValidation("name","req","Внесете го вашето име");

        frmvalidator.addValidation("email","req","Внесете ја вашата email адреса");

        frmvalidator.addValidation("email","email","Внесете валидна email адреса");

        frmvalidator.addValidation("username","req","Внесете го вашето корисничко име");

        frmvalidator.addValidation("password","req","Внесете ја лозинката");

        // ]]>
    </script>


</body>
<footer class="panel-footer">
	<center>		
		<h4>COPYRIGHT 	&copy; SMESTI-SE.МК 2018</h4>
		<a href="pravila.php">ПРАВИЛА И УСЛОВИ</a>
	</center>
	
</footer>
</html>