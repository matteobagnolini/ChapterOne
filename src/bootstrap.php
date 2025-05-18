<?php
session_start();
define("UPLOAD_DIR", "resources/");
require_once("utils/functions.php");
require_once("db/database.php");
$dbh = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);


$templateParams['unread_notifications_count'] = 0;
$templateParams['cart_item_count'] = 0;

// Verifica se l'utente è loggato (admin o cliente)
if (isUserLoggedIn() || isAdminLoggedIn()) {
    $userId = null;
    $is_admin_view = isAdminLoggedIn(); // Utilizza la tua funzione helper

    // Recupera l'ID dell'utente se è un cliente
    if (!$is_admin_view && isset($_SESSION['username'])) {
        $customer = $dbh->getCustomerByUsername($_SESSION['username']);
        if ($customer && isset($customer['Id'])) {
            $userId = $customer['Id'];
        }
    }

    // Calcola il conteggio delle notifiche non lette
    if ($is_admin_view) {
        // Per Admin: conta le notifiche admin non lette
        $allAdminNotifications = $dbh->getAdminOrderNotifications(); // Funzione che dovresti avere
        $unreadCount = 0;
        if (is_array($allAdminNotifications)) {
            foreach ($allAdminNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) { // o $notification['Seen'] === false
                    $unreadCount++;
                }
            }
        }
        $templateParams['unread_notifications_count'] = $unreadCount;
        // Idealmente, avresti una funzione: $templateParams['unread_notifications_count'] = $dbh->getUnreadAdminNotificationsCount();
    } elseif ($userId) {
        // Per Utente: conta le notifiche utente non lette
        $allUserNotifications = $dbh->getOrderNotificationByCustomerId($userId); // Funzione che dovresti avere
        $unreadCount = 0;
        if (is_array($allUserNotifications)) {
            foreach ($allUserNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) { // o $notification['Seen'] === false
                    $unreadCount++;
                }
            }
        }
        $templateParams['unread_notifications_count'] = $unreadCount;
        // Idealmente, avresti una funzione: $templateParams['unread_notifications_count'] = $dbh->getUnreadUserNotificationsCount($userId);
    }

    // Calcola il conteggio degli articoli nel carrello (solo per utenti clienti)
    if (!$is_admin_view && $userId) {
        $cartDetails = $dbh->getCartByCustomerId($userId); // Funzione che dovresti avere
        if ($cartDetails && isset($cartDetails['Item_count'])) {
            $templateParams['cart_item_count'] = $cartDetails['Item_count'];
        }
    }
}


?>
