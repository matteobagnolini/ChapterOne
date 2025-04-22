<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Notifiche";
$templateParams["nome"] = "notifichepage.php";
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["notifiche"] = $dbh->getNotificationsById($_SESSION["username"]);

require 'template/base.php';

?>