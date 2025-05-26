<?php
require_once 'bootstrap.php';

if (!isAdminLoggedIn()) {
    if (isUserLoggedIn()) {
        header("location: index.php"); 
    } else {
        header("location: login.php"); 
    }
    exit; 
}

$templateParams["titolo"] = "ChapterOne - Gestione Codici Sconto";
$templateParams["nome"] = "lista-codiciscontopage.php"; 
$templateParams["categorie"] = $dbh->getCategories(); 

$templateParams["codicisconto"] = $dbh->getDiscountCodes(); 


require 'template/base.php';
?>