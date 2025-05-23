<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn() && !isUserLoggedIn()){
    header("location: login.php");
}else if(isUserLoggedIn()){
    header("location: index.php");
}

$templateParams["titolo"] = "ChapterOne - Gestione Autori";
$templateParams["nome"] = "lista-clientipage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["utenti"] = $dbh->getCustomers();

require 'template/base.php';

?>