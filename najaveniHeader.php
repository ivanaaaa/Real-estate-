<?php

include "connection.php";
require_once("./include/korisnicka_strana.php");
if (!$fgmembersite->CheckLogin()) {
    //echo "prave problem tuka";
    $fgmembersite->RedirectToURL("login.php");
    exit;
}


?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Продавај - Изнајми - Смести се</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>

</head>
<body style="background-color:#E0E0E0;">


<div class="container-fluid">

    <div style="width:100%;height:80px;">


        <form action="login-home.php" method="post" class="navbar-form" style="float:right; ">
            <div class="form-group">
                <input type="text" name="klucenZbor" class="form-control" placeholder="клучен збор">
            </div>

            <input type="submit" name="baraj_po_klucenZbor" class="btn btn-primary navbar-btn" value="Пребарај">

            <?php
            if ( ($fgmembersite->User_type() != 'админ') && ($fgmembersite->User_type() != 'модератор') && ($fgmembersite->User_type() != 'корисник'))
            { ?>
            <a href="login.php"  class="btn btn-primary  navbar-btn">Најави се</a>

            <?php } else { ?>
            <a href="logout.php" class="btn btn-default  navbar-btn" style="color: white !important ;background-color: #840303 !important;">Одјави се</a>
            <?php }?>

        </form>
    </div>
    <div style="width:100%;height:180px; background-color:whitesmoke; border-radius: 5px; margin-bottom:30px;">

        <img src="assets/logo.png" style="float:left; margin:10px;">

        <div class="nav-left">
            <?php if( ($fgmembersite->User_type() == 'админ' ) || ($fgmembersite->User_type() == 'модератор') || ($fgmembersite->User_type() == 'корисник')) {?>
            <a href="login-home.php" class="btn btn-default  navbar-btn" style="display: inline-block;
																			   ">Сите огласи</a>
            <?php }?>
            <?php if ($fgmembersite->User_type() == 'корисник') { ?>
                <a href="vnesiOglas.php" class="btn btn-default  navbar-btn" style="
																						display: inline-block;
																						">Внеси оглас</a>
            <?php }?>
            <?php if($fgmembersite->User_type() == 'корисник') {?>
                <a href="moiOglasi.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Мои огласи</a>
            <?php }?>
            <?php if($fgmembersite->User_type() == 'корисник') {?>
                <a href="zacuvaniOglasi.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Зачувани огласи</a>
            <?php }?>


            <?php if( ($fgmembersite->User_type() == 'админ') || ($fgmembersite->User_type() == 'модератор') ) {?>
                <a href="oglasi_za_odobruvanje.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Одобри</a>
            <?php }?>

            <?php if(($fgmembersite->User_type() == 'админ') || ($fgmembersite->User_type() == 'модератор') ) {?>
                <a href="moiOdobreniOglasi.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Мои одобрени</a>
            <?php }?>

            <?php if( ($fgmembersite->User_type() == 'админ') && ($fgmembersite->User_type() != 'модератор') && ($fgmembersite->User_type() != 'корисник') ) {?>
                <a href="vnesiModerator.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Внеси модератор</a>
            <?php }?>

            <?php if ((($fgmembersite->User_type() == 'админ') || ($fgmembersite->User_type() == 'модератор')) && ($fgmembersite->User_type() != 'корисник')) { ?>
               <?php if($fgmembersite->User_type() == 'админ') {?>
                <a href="lista_admin.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Корисници</a>
                <?php }?>
                <?php if ($fgmembersite->User_type() == 'модератор') {?>
                    <a href="lista_moderator.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Корисници</a>
                <?php }?>
            <?php }?>
            
            <?php if($fgmembersite->User_type() == 'админ'){?>
                <a href="oglasi.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Огласи</a>
            <?php }?>
            
            
            
            <?php if( ($fgmembersite->User_type() == 'админ') || ($fgmembersite->User_type() == 'модератор') || ($fgmembersite->User_type() == 'корисник')) {?>
                <a href="urediProfil.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Уреди мој профил</a>
            <?php }?>

            <?php  if ( ($fgmembersite->User_type() != 'админ') && ($fgmembersite->User_type() != 'модератор') && ($fgmembersite->User_type() != 'корисник')) { ?>

            <a href="registracija.php" class="btn btn-default  navbar-btn" style="display: inline-block;">Регистрирај се</a>
            <?php }?>
            <?php if( ($fgmembersite->User_type() != 'админ') && ($fgmembersite->User_type() != 'модератор') && ($fgmembersite->User_type() != 'корисник') ) {?>
                <a href="#" class="btn btn-default  navbar-btn" style="
																		   display: inline-block;
																		   ">Помош</a>
            <?php }?>
            <?php if( ($fgmembersite->User_type() != 'админ') && ($fgmembersite->User_type() != 'модератор') && ($fgmembersite->User_type() != 'корисник') ) {?>
                <a href="#" class="btn btn-default  navbar-btn" style="
																		   display: inline-block;
																		   ">За нас</a>
            <?php }?>

        </div>
    </div>


</div><!-- /.container-fluid -->
