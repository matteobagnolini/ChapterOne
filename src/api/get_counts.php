<?php
<?php
session_start();
require_once("../utils/functions.php");
require_once("../db/database.php");

// Imposta l'header per indicare che si tratta di una risposta JSON
header('Content-Type: application/json');

// Inizializza l'array della risposta
$response = [
    'unread_notifications_count' => 0,
    'cart_item_count' => 0
];

// Crea istanza del database
$dbh = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

// Verifica se l'utente è loggato
if (isUserLoggedIn() || isAdminLoggedIn()) {
    $userId = null;
    $is_admin_view = isAdminLoggedIn();

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
        $allAdminNotifications = $dbh->getAdminOrderNotifications();
        $unreadCount = 0;
        if (is_array($allAdminNotifications)) {
            foreach ($allAdminNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) {
                    $unreadCount++;
                }
            }
        }
        $response['unread_notifications_count'] = $unreadCount;
    } elseif ($userId) {
        // Per Utente: conta le notifiche utente non lette
        $allUserNotifications = $dbh->getOrderNotificationByCustomerId($userId);
        $unreadCount = 0;
        if (is_array($allUserNotifications)) {
            foreach ($allUserNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) {
                    $unreadCount++;
                }
            }
        }
        $response['unread_notifications_count'] = $unreadCount;
    }

    // Calcola il conteggio degli articoli nel carrello (solo per utenti clienti)
    if (!$is_admin_view && $userId) {
        $cartDetails = $dbh->getCartByCustomerId($userId);
        if ($cartDetails && isset($cartDetails['Item_count'])) {
            $response['cart_item_count'] = $cartDetails['Item_count'];
        }
    }
}

// Ritorna la risposta in formato JSON
echo json_encode($response);
?>