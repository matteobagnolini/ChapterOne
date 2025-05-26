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

$templateParams["titolo"] = "ChapterOne - Gestione Autori";
$templateParams["nome"] = "lista-clientipage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["utenti"] = $dbh->getCustomers();

require 'template/base.php';

?>