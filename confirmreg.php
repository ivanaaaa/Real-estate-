<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 08-Jan-18
 * Time: 4:46 PM
 */
require_once("./include/korisnicka_strana.php");

if(isset($_GET['code']))
{
    if($fgmembersite->ConfirmUser())
    {
        $fgmembersite->RedirectToURL("thank-you-regd.php");
    }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Потврди регистрација</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<h2>Потврдете ја регистрацијата</h2>
<p>
    Внесете го кодот за потврда во полето подолу
</p>

<!-- Form Code Start -->
<div id='fg_membersite'>
    <form id='confirm' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='get' accept-charset='UTF-8'>
        <div class='short_explanation'>* задолжителни полиња</div>
        <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
        <div class='container'>
            <label for='code' >Код за потврда:* </label><br/>
            <input type='text' name='code' id='code' maxlength="50" /><br/>
            <span id='register_code_errorloc' class='error'></span>
        </div>
        <div class='container'>
            <input type='submit' name='Submit' value='Submit' />
        </div>

    </form>
    <!-- client-side Form Validations:
    Uses the excellent form validation script from JavaScript-coder.com-->

    <script type='text/javascript'>
        // <![CDATA[

        var frmvalidator  = new Validator("confirm");
        frmvalidator.EnableOnPageErrorDisplay();
        frmvalidator.EnableMsgsTogether();
        frmvalidator.addValidation("code","req","Please enter the confirmation code");

        // ]]>
    </script>
</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>