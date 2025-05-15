<?php
require_once 'bootstrap.php';

if (isUserLoggedIn() || isAdminLoggedIn()) {
    header("location: index.php");
}

$templateParams["titolo"] = "ChapterOne - Notifiche";
$templateParams["nome"] = "registrazionepage.php";
$templateParams["categorie"] = $dbh->getCategories();


require 'template/base.php';

?>