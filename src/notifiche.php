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

$templateParams["notifiche_non_lette"] = [];
$templateParams["notifiche_lette"] = [];
$orderIds = []; 
$templateParams["isAdminView"] = isAdminLoggedIn(); // Utilizza la funzione helper se disponibile, altrimenti (isset($_SESSION['admin']) && $_SESSION['admin'] === true)

if ($templateParams["isAdminView"]) {

    $allAdminNotifications = $dbh->getAdminOrderNotifications();

    if (is_array($allAdminNotifications)) {
        foreach ($allAdminNotifications as $notification) {
            $currentNotification = $notification; 
            
            if (isset($currentNotification["Order_id"]) && !in_array($currentNotification["Order_id"], $orderIds)) {
                $orderIds[] = $currentNotification["Order_id"];
            }
            
            // Assicurati che Preview e Message siano presenti o gestisci la loro assenza
            if (!isset($currentNotification["Preview"]) && isset($currentNotification["Order_id"])) {
                $currentNotification["Preview"] = "Notifica per Ordine ID: " . $currentNotification["Order_id"];
            }
            if (!isset($currentNotification["Message"]) && isset($currentNotification["Status"])) {
                $currentNotification["Message"] = "Stato ordine: " . htmlspecialchars($currentNotification["Status"]) . ". Controlla i dettagli.";
            }


            if (isset($currentNotification["Seen"]) && $currentNotification["Seen"] == 1) {
                $templateParams["notifiche_lette"][] = $currentNotification;
            } else {
                // Se Seen non è settato o è 0 (o false), considerala non letta
                $templateParams["notifiche_non_lette"][] = $currentNotification;
            }
        }
    } else {
        error_log("getAllOrderNotificationsForAdmin non ha restituito un array.");
    }

} else { // Vista Utente
    // Assumendo che l'ID utente sia memorizzato in $_SESSION['user_id'] o $_SESSION['customer_id']
    $userId = null;
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
    } elseif (isset($_SESSION['customer_id'])) {
        $userId = $_SESSION['customer_id'];
    } elseif (isset($_SESSION["username"])) { // Fallback per recuperare l'ID se si usa username
        $user = $dbh->getCustomerByUsername($_SESSION["username"]);
        if ($user && isset($user['Id'])) {
            $userId = $user['Id'];
        }
    }

    if ($userId) {
        // La funzione getOrderNotificationByCustomerId dovrebbe restituire tutte le notifiche per l'utente
        // includendo il campo 'Seen'.
        $allUserNotifications = $dbh->getOrderNotificationByCustomerId($userId); 
            
        if (is_array($allUserNotifications)) {
            foreach($allUserNotifications as $notification) {
                $currentNotification = $notification; 
                
                if (isset($currentNotification["Order_id"]) && !in_array($currentNotification["Order_id"], $orderIds)) {
                    $orderIds[] = $currentNotification["Order_id"];
                }

                // Logica per Preview se non esiste già (come nell'originale)
                if (isset($currentNotification["Message"]) && empty($currentNotification["Preview"])) {
                     $currentNotification["Preview"] = substr(htmlspecialchars($currentNotification["Message"]), 0, 50) . "...";
                }
                
                if (isset($currentNotification["Seen"]) && $currentNotification["Seen"] == 1) {
                    $templateParams["notifiche_lette"][] = $currentNotification;
                } else {
                    $templateParams["notifiche_non_lette"][] = $currentNotification;
                }
            }
        } else {
            error_log("getOrderNotificationByCustomerId non ha restituito un array per l'utente: " . $userId);
        }
    } else {
        error_log("ID utente non trovato in sessione o non recuperabile per la visualizzazione delle notifiche utente.");
    }
}

// Popola i dettagli degli ordini associati alle notifiche
if (!empty($orderIds)) {
    $templateParams["ordini"] = []; // Inizializza qui per sicurezza
    foreach ($orderIds as $orderId) {
        $orderInfo = $dbh->getOrderById($orderId); 
        if ($orderInfo) {
            $templateParams["ordini"][$orderId] = $orderInfo; 
        }
    }
}

require 'template/base.php';

?>