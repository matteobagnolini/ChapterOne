<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn() && !isUserLoggedIn()){
    header("location: login.php");
}else if(isUserLoggedIn()){
    header("location: index.php");
}

$templateParams["titolo"] = "ChapterOne - Gestione Codici Sconto";
$templateParams["nome"] = "lista-codiciscontopage.php"; 
$templateParams["categorie"] = $dbh->getCategories(); 

$templateParams["codicisconto"] = $dbh->getDiscountCodes(); 


require 'template/base.php';
?>