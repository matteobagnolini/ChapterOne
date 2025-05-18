<?php
require_once '../bootstrap.php'; // Assicurati che il percorso sia corretto

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id']) && (isUserLoggedIn() || isAdminLoggedIn())) {
        $notificationId = $_POST['notification_id'];
        $currentUserId = null; // ID dell'utente loggato, se non è admin
        $isAdmin = isAdminLoggedIn();

        if (!$isAdmin && isUserLoggedIn()) {
            $customer = $dbh->getCustomerByUsername($_SESSION['username']);
            if ($customer && isset($customer['Id'])) {
                $currentUserId = $customer['Id'];
            }
        }

        $deleted = false;
        if (isUserLoggedIn()){
            $deleted = $dbh->deleteOrderNotification($notificationId);
        } else if (isAdminLoggedIn()) {
            $deleted = $dbh->deleteAdminNotification($notificationId);
        }
      
    } else {
        $_SESSION['error'] = "Richiesta non valida o utente non autorizzato.";
    }
} else {
    $_SESSION['error'] = "Metodo di richiesta non supportato.";
}

header("Location: ../notifiche.php");
exit;
?>