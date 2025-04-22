<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - OrderDetails";
$templateParams["nome"] = "orderdetailspage.php";
$templateParams["categorie"] = $dbh->getCategories();
if (isset($_GET["id_order"])) {
    $templateParams["ordine"] = $dbh->getOrderBooks($_GET["id_order"]);
    $templateParams["ordinePrezzo"] = $dbh->getOrderPrice($_GET["id_order"]);
    $templateParams["ordineNumeroLibri"] = $dbh->getOrderBooksNumber($_GET["id_order"]);
    $templateParams["ordineData"] = $dbh->getOrderDate($_GET["id_order"]);
} else {
    header("location: orders.php");
}

require 'template/base.php';

?>