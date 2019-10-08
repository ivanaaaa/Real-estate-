<?php
/*
 try{
    $pdo = new PDO('mysql:host=localhost; dbname=izdavanje','root',"",array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
    }catch(PDOException $pe){
        echo $pe->getMessage();
    }    */
if (!($conn = mysqli_connect("localhost", "root", "")))	
		die("Error 1!" .mysqli_connect_error());
	if (!(mysqli_select_db($conn,"izdavanje"))) 
		die("Error 2!" .mysqli_connect_error());
	
mysqli_set_charset($conn,"utf8");
?>