<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn()){
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Account Admin";
$templateParams["nome"] = "accountadminpage.php";
$templateParams["categorie"] = $dbh->getCategories();



require 'template/base.php';

?>