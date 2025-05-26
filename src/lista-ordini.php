<?php
require_once 'bootstrap.php';


if (!isAdminLoggedIn()) {
    if (isUserLoggedIn()) {
        header("location: index.php"); 
    } else {
        header("location: login.php"); 
    }
    exit; 
}

$templateParams["titolo"] = "ChapterOne - Gestione Ordini";
$templateParams["nome"] = "lista-ordinipage.php";
$templateParams["categorie"] = $dbh->getCategories();


$templateParams["ordini"] = $dbh->getOrders(); 


if (!empty($templateParams["ordini"])) {
    foreach ($templateParams["ordini"] as $key => $ordine) {
        $customer = $dbh->getCustomerById($ordine["Customer_id"]);
        $templateParams["ordini"][$key]["Customer_Name"] = $customer ? ($customer["First_name"] . " " . $customer["Last_name"]) : "Sconosciuto";
        $templateParams["ordini"][$key]["Customer_Email"] = $customer ? $customer["Email"] : "N/D";
    }
}

require 'template/base.php';
?>