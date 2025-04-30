<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - OrderDetails";
$templateParams["nome"] = "orderdetailspage.php";
$templateParams["categorie"] = $dbh->getCategories();
if (isset($_GET["id_order"])) {

    $templateParams["dettagliordine"] = = $dbh->getOrderDetailsByOrderId($_GET["id_order"]);
    
} else {
    header("location: orders.php");
}

require 'template/base.php';

?>