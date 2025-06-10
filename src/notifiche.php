<?php

require_once 'bootstrap.php';


if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("location: login.php");
    exit; 
}


$templateParams["titolo"] = "ChapterOne - Notifiche";
$templateParams["nome"] = "notifichepage.php"; 
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["notifiche_non_lette"] = [];
$templateParams["notifiche_lette"] = [];
$orderIds = []; 
$templateParams["isAdminView"] = isAdminLoggedIn();

if ($templateParams["isAdminView"]) {

    $allAdminNotifications = $dbh->getAdminOrderNotifications();

    if (is_array($allAdminNotifications)) {
        foreach ($allAdminNotifications as $notification) {
            $currentNotification = $notification; 
            
            if (isset($currentNotification["Order_id"]) && !in_array($currentNotification["Order_id"], $orderIds)) {
                $orderIds[] = $currentNotification["Order_id"];
            }
            
            if (!isset($currentNotification["Preview"]) && isset($currentNotification["Order_id"])) {
                $currentNotification["Preview"] = "Notifica per Ordine ID: " . $currentNotification["Order_id"];
            }
            if (!isset($currentNotification["Message"]) && isset($currentNotification["Status"])) {
                $currentNotification["Message"] = "Stato ordine: " . htmlspecialchars($currentNotification["Status"]) . ". Controlla i dettagli.";
            }


            if (isset($currentNotification["Seen"]) && $currentNotification["Seen"] == 1) {
                $templateParams["notifiche_lette"][] = $currentNotification;
            } else {
                $templateParams["notifiche_non_lette"][] = $currentNotification;
            }
        }
    }

} else { 
    $userId = null;
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } elseif (isset($_SESSION['customer_id'])) {
        $userId = $_SESSION['customer_id'];
    } elseif (isset($_SESSION["username"])) {
        $user = $dbh->getCustomerByUsername($_SESSION["username"]);
        if ($user && isset($user['Id'])) {
            $userId = $user['Id'];
        }
    }

    if ($userId) {

        $allUserNotifications = $dbh->getOrderNotificationByCustomerId($userId); 
            
        if (is_array($allUserNotifications)) {
            foreach($allUserNotifications as $notification) {
                $currentNotification = $notification; 
                
                if (isset($currentNotification["Order_id"]) && !in_array($currentNotification["Order_id"], $orderIds)) {
                    $orderIds[] = $currentNotification["Order_id"];
                }

                if (isset($currentNotification["Message"]) && empty($currentNotification["Preview"])) {
                     $currentNotification["Preview"] = substr(htmlspecialchars($currentNotification["Message"]), 0, 50) . "...";
                }
                
                if (isset($currentNotification["Seen"]) && $currentNotification["Seen"] == 1) {
                    $templateParams["notifiche_lette"][] = $currentNotification;
                } else {
                    $templateParams["notifiche_non_lette"][] = $currentNotification;
                }
            }
        }
    }
}


if (!empty($orderIds)) {
    $templateParams["ordini"] = []; 
    foreach ($orderIds as $orderId) {
        $orderInfo = $dbh->getOrderById($orderId); 
        if ($orderInfo) {
            $templateParams["ordini"][$orderId] = $orderInfo; 
        }
    }
}

require 'template/base.php';

?>