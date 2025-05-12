<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn() && !isUserLoggedIn()){
    header("location: login.php");
}else if(isUserLoggedIn()){
    header("location: index.php");
}

$templateParams["titolo"] = "ChapterOne - Gestione Autori";
$templateParams["nome"] = "lista-codiciscontopage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["novità"] = $dbh->getNewBooks(10);
$templateParams["bestsellers"] = $dbh->getbestSellers(10);
$templateParams["autori"] = $dbh->getAuthors();

require 'template/base.php';

?>