<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Account Admin";
$templateParams["nome"] = "accountadminpage.php";
$templateParams["categorie"] = $dbh->getCategories();

if(!isAdminLoggedIn()){
    header("location: login.php");
}

require 'template/base.php';

?>