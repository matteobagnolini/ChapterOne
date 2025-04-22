<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Orders";
$templateParams["nome"] = "orderspage.php";
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["ordini"] = $dbh->getOrders($_SESSION["username"]);

require 'template/base.php';

?>