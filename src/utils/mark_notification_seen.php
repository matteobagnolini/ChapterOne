<?php
require_once '../bootstrap.php'; 


if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("Location: ../login.php?error=not_logged_in");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['notification_id'])) {
        $notificationId = filter_input(INPUT_POST, 'notification_id', FILTER_VALIDATE_INT);

        if ($notificationId === false || $notificationId <= 0) {
            header("Location: ../notifiche.php?error=invalid_notification_id");
            exit;
        }

        $isAdmin = isAdminLoggedIn();
        $success = false;

        if ($isAdmin) {
            $success = $dbh->SetSeenAdminNotification($notificationId);
        } elseif (isUserLoggedIn()) {
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
            header("Location: ../notifiche.php?error=authentication_error");
            exit;
        }

        if ($success) {
            header("Location: ../notifiche.php?success=marked_as_seen");
            exit;
        } else {

            $error_reason = $isAdmin ? "admin_update_failed" : "user_update_failed";
            header("Location: ../notifiche.php?error=" . $error_reason);
            exit;
        }
    } else {
        header("Location: ../notifiche.php?error=missing_notification_id");
        exit;
    }
}
?>