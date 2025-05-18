<?php
require_once '../bootstrap.php'; // Assicurati che il percorso a bootstrap sia corretto

header('Content-Type: application/json');

$response = [
    'unread_notifications_count' => 0,
    'cart_item_count' => 0, // Puoi includerlo se vuoi aggiornare anche questo
    'status' => 'error',
    'message' => 'Utente non loggato o errore nel recupero dati.'
];

if (isUserLoggedIn() || isAdminLoggedIn()) {
    $userId = null;
    $is_admin_view = isAdminLoggedIn();

    if (!$is_admin_view && isset($_SESSION['username'])) {
        $customer = $dbh->getCustomerByUsername($_SESSION['username']);
        if ($customer && isset($customer['Id'])) {
            $userId = $customer['Id'];
        }
    }

    // Conteggio notifiche non lette
    $unreadNotifications = 0;
    if ($is_admin_view) {
        $allAdminNotifications = $dbh->getAllAdminOrderNotifications();
        if (is_array($allAdminNotifications)) {
            foreach ($allAdminNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) {
                    $unreadNotifications++;
                }
            }
        }
    } elseif ($userId) {
        $allUserNotifications = $dbh->getOrderNotificationByCustomerId($userId);
        if (is_array($allUserNotifications)) {
            foreach ($allUserNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) {
                    $unreadNotifications++;
                }
            }
        }
    }
    $response['unread_notifications_count'] = $unreadNotifications;

    // Conteggio articoli carrello (solo per utenti clienti)
    $cartItems = 0;
    if (!$is_admin_view && $userId) {
        $cartDetails = $dbh->getCartByCustomerId($userId);
        if ($cartDetails && isset($cartDetails['Item_count'])) {
            $cartItems = $cartDetails['Item_count'];
        }
    }
    $response['cart_item_count'] = $cartItems;
    
    $response['status'] = 'success';
    $response['message'] = 'Conteggi recuperati con successo.';

} else {
    // L'utente non è loggato, i conteggi rimangono 0, messaggio di errore già impostato
}

echo json_encode($response);
exit;
?>