<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 08-Jan-18
 * Time: 4:47 PM
 */

include "nenajaveniHeader.php";
require_once("./include/korisnicka_strana.php");
if(isset($_POST['submitted']))
{
    if($fgmembersite->Login())
    {
        $fgmembersite->RedirectToURL("login-home.php");
    }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Најава</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<div class="container" style="margin:30 auto;background-color:whitesmoke;border-radius:4px;">
<!-- Form Code Start -->
<div id='fg_membersite'>
    <form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
        <fieldset >
            <legend>Најава</legend>

            <input type='hidden' name='submitted' id='submitted' value='1'/>

            <div class='short_explanation'>* задолжителни полиња</div>

            <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
            <div class='container'>
                <label for='username' >Корисничко име*:</label><br/>
                <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
                <span id='login_username_errorloc' class='error'></span>
            </div>
            <div class='container'>
                <label for='password' >Лозинка*:</label><br/>
                <input type='password' name='password' id='password' maxlength="50" /><br/>
                <span id='login_password_errorloc' class='error'></span>
            </div>

            <div class='container'>
                <input type='submit' name='Submit' class="btn btn-success navbar-btn" value='Испрати' />
            </div>
            <div class='short_explanation'><a href='reset-pwd-req.php'>Ја заборавивте лозинката?</a></div>
        </fieldset>
    </form>
    <!-- client-side Form Validations:
    Uses the excellent form validation script from JavaScript-coder.com-->

    <script type='text/javascript'>
        // <![CDATA[

        var frmvalidator  = new Validator("login");
        frmvalidator.EnableOnPageErrorDisplay();
        frmvalidator.EnableMsgsTogether();

        frmvalidator.addValidation("username","req","Внесете го вашето корисничко име");

        frmvalidator.addValidation("password","req","Внесете ја вашата лозинка");

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