<?php

include "nenajaveniHeader.php";
require_once("./include/korisnicka_strana.php");


$fgmembersite->LogOut();
$fgmembersite->RedirectToURL("login.php");
    exit;

?>
