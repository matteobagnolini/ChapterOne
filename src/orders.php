<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Orders";
$templateParams["nome"] = "orderspage.php";
$templateParams["categorie"] = $dbh->getCategories();

$user = $dbh->getCustomerByUsername($_SESSION["username"]);
$templateParams["ordini"] = $dbh->getOrderByCustomerId($user["Id"]);

require 'template/base.php';

?>