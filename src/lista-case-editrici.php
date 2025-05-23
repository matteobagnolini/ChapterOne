<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn() && !isUserLoggedIn()){
    header("location: login.php");
}else if(isUserLoggedIn()){
    header("location: index.php");
}

$templateParams["titolo"] = "ChapterOne - Gestione Autori";
$templateParams["nome"] = "lista-case-editricipage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["caseeditrici"] = $dbh->getPublishers();

require 'template/base.php';

?>