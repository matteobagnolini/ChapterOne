<?php

require_once 'bootstrap.php';


if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("location: login.php");
    exit; 
}

// A questo punto, l'utente è loggato.
// Assicurati che $_SESSION['admin'] sia un booleano true per gli admin.

$templateParams["titolo"] = "ChapterOne - Notifiche";
$templateParams["nome"] = "notifichepage.php"; 
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["notifiche"] = [];
$orderIds = []; 
$templateParams["ordini"] = [];
$templateParams["isAdminView"] = (isset($_SESSION['admin']) && $_SESSION['admin'] === true); // Imposta il flag qui

if ($templateParams["isAdminView"]) {

    $notificheAdminRaw = $dbh->getOrdersNotificationByStatus('pending');
    $templateParams["notifiche"] = []; // Inizializza come array vuoto
    if (is_array($notificheAdminRaw)) {
        foreach ($notificheAdminRaw as $key => $notification) {
            $currentNotification = $notification; // Lavora su una copia per modificarla
            if (isset($currentNotification["Order_id"])) {
                if (!in_array($currentNotification["Order_id"], $orderIds)) {
                    $orderIds[] = $currentNotification["Order_id"];
                }
                $currentNotification["Preview"] = "Nuovo Ordine";
                $currentNotification["Message"] = "Nuovo Ordine ricevuto. Controlla i dettagli."; // Messaggio base
            }
            $templateParams["notifiche"][$key] = $currentNotification;
        }
    } else {
        error_log("getOrdersNotificationByStatus non ha restituito un array per l'admin.");
    }

} else {
    
    if (isset($_SESSION["username"])) {
        $user = $dbh->getCustomerByUsername($_SESSION["username"]); 
        if ($user && isset($user['Id'])) {
            $templateParams["notifiche"] = $dbh->getOrderNotificationByCustomerId($user['Id']); 
            
            if (is_array($templateParams["notifiche"])) {
                foreach($templateParams["notifiche"] as $key => $notification) {
                    if (isset($notification["Message"]) && !isset($notification["Preview"])) {
                         $templateParams["notifiche"][$key]["Preview"] = substr(htmlspecialchars($notification["Message"]), 0, 50) . "...";
                    }
                    if (isset($notification["Order_id"]) && !in_array($notification["Order_id"], $orderIds)) {
                        $orderIds[] = $notification["Order_id"];
                    }
                }
            } else {
                error_log("getOrderNotificationByCustomerId non ha restituito un array per l'utente: " . $user['Id']);
                $templateParams["notifiche"] = [];
            }
        } else {
            error_log("Utente (non admin) non trovato o ID utente mancante per username: " . $_SESSION["username"]);
        }
    }
}

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