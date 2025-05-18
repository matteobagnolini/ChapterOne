<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Orders";
$templateParams["nome"] = "orderspage.php";
$templateParams["categorie"] = $dbh->getCategories();

$user = $dbh->getCustomerByUsername($_SESSION["username"]);
$ordini = $dbh->getOrderByCustomerId($user["Id"]);
$templateParams["ordini"] = array_reverse($ordini); 


require 'template/base.php';

?>