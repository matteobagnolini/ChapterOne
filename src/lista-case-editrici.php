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
$templateParams["nome"] = "lista-case-editricipage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["caseeditrici"] = $dbh->getPublishers();

require 'template/base.php';

?>