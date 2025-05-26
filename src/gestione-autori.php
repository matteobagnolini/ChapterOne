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
$templateParams["nome"] = "lista-autoripage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["novità"] = $dbh->getNewBooks(10);
$templateParams["bestsellers"] = $dbh->getbestSellers(10);
$templateParams["autori"] = $dbh->getAuthors();

require 'template/base.php';

?>