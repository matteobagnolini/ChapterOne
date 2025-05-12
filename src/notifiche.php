<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Notifiche";
$templateParams["nome"] = "notifichepage.php";
$templateParams["categorie"] = $dbh->getCategories();


$user = $dbh->getCustomerByUsername($_SESSION["username"]);
$templateParams["notifiche"] = $dbh->getOrderNotificationByCustomerId($user['Id']);

$user = $dbh->getCustomerByUsername($_SESSION["username"]);
$templateParams["notifiche"] = $dbh->getOrderNotificationByCustomerId($user['Id']);

$orderIds = []; // Array per memorizzare gli ID degli ordini

foreach($templateParams["notifiche"] as $key => $notification) {
    $templateParams["notifiche"][$key]["Preview"] = substr($notification["Message"], 0, 50) . "...";
    
    if (!in_array($notification["Order_id"], $orderIds)) {
        $orderIds[] = $notification["Order_id"];
    }
}

$templateParams["ordini"] = [];
if (!empty($orderIds)) {
    foreach ($orderIds as $orderId) {
        $orderInfo = $dbh->getOrderById($orderId); 
        if ($orderInfo) {
            $templateParams["ordini"][$orderId] = $orderInfo; 
        }
    }
}
require 'template/base.php';

?>