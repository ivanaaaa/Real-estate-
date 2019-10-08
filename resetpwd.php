<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 08-Jan-18
 * Time: 4:52 PM
 */
require_once("./include/korisnicka_strana.php");

$success = false;
if($fgmembersite->ResetPassword())
{
    $success=true;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>Ресетирање на лозинка</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_korisnicka_strana.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<div id='fg_membersite_content'>
    <?php
    if($success){
        ?>
        <h2>Лозинката е ресетирана успешно</h2>
        Вашата нова лозинка е пратена на вашата email адреса.
        <?php
    }else{
        ?>
        <h2>Грешка</h2>
        <span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span>
        <?php
    }
    ?>
</div>

</body>
</html>