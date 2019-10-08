<?php
/**
 * Created by PhpStorm.
 * User: Ivana
 * Date: 06-Jan-18
 * Time: 9:09 PM
 */
require_once("./include/fg_korisnicka_strana.php");

$fgmembersite = new FGMembersite();

$fgmembersite->SetWebsiteName('Smesti-se.com');
//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('ivanacebova@gmail.com');

$fgmembersite->InitDB(/*hostname*/'localhost',
    /*username*/'root',
    /*password*/'',
    /*database name*/'izdavanje',
    /*table name*/'korisnici');

$fgmembersite->SetRandomKey('4TtxBYsTYVvGwu0');

?>