<?php
require_once '../bootstrap.php'; // Assicurati che il percorso a bootstrap sia corretto

// Verifica che l'utente sia loggato (admin o customer)
if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id'])) {
        $notificationId = filter_input(INPUT_POST, 'notification_id', FILTER_VALIDATE_INT);

        if ($notificationId === false || $notificationId <= 0) {
            // ID notifica non valido
            header("Location: ../notifiche.php?error=invalid_notification_id");
            exit;
        }

        $isAdmin = isAdminLoggedIn();
        $success = false;

        if ($isAdmin) {
            // L'utente è un amministratore
            $success = $dbh->SetSeenAdminNotification($notificationId);
        } elseif (isUserLoggedIn()) {
            // L'utente è un cliente standard
            // Recupera l'ID utente se necessario per la logica interna di SetSeenNotification
            // o per futuri controlli di permesso più granulari.
            $userId = null;
            if (isset($_SESSION['user_id'])) {
                $userId = $_SESSION['user_id'];
            } elseif (isset($_SESSION['customer_id'])) {
                 $userId = $_SESSION['customer_id'];
            } elseif (isset($_SESSION['username'])) {
                $user = $dbh->getCustomerByUsername($_SESSION['username']);
                if ($user && isset($user['Id'])) {
                    $userId = $user['Id'];
                }
            }
            
            if (!$userId) {
                 error_log("User is logged in but user_id could not be determined for notification update.");
                 header("Location: ../notifiche.php?error=session_error_user_id");
                 exit;
            }

            $success = $dbh->SetSeenNotification($notificationId); 
        } else {
            // Caso imprevisto, l'utente non è né admin né utente loggato (dovrebbe essere già gestito sopra)
            header("Location: ../notifiche.php?error=authentication_error");
            exit;
        }

        if ($success) {
            header("Location: ../notifiche.php?success=marked_as_seen");
            exit;
        } else {
            // Errore durante l'aggiornamento (es. notifica non trovata, permesso negato, errore DB)
            $error_reason = $isAdmin ? "admin_update_failed" : "user_update_failed";
            header("Location: ../notifiche.php?error=" . $error_reason);
            exit;
        }
    } else {
        // notification_id non inviato
        header("Location: ../notifiche.php?error=missing_notification_id");
        exit;
    }
} else {
    // Metodo non POST, reindirizza o mostra errore
    header("Location: ../notifiche.php?error=invalid_request_method");
    exit;
}
?>