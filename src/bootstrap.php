<?php
session_start();
define("UPLOAD_DIR", "resources/");
require_once("utils/functions.php");
require_once("db/database.php");
$dbh = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);


$templateParams['unread_notifications_count'] = 0;
$templateParams['cart_item_count'] = 0;


if (isUserLoggedIn() || isAdminLoggedIn()) {
    $userId = null;
    $is_admin_view = isAdminLoggedIn(); 

    if (!$is_admin_view && isset($_SESSION['username'])) {
        $customer = $dbh->getCustomerByUsername($_SESSION['username']);
        if ($customer && isset($customer['Id'])) {
            $userId = $customer['Id'];
        }
    }

  
    if ($is_admin_view) {
        $allAdminNotifications = $dbh->getAdminOrderNotifications(); 
        $unreadCount = 0;
        if (is_array($allAdminNotifications)) {
            foreach ($allAdminNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) { 
                    $unreadCount++;
                }
            }
        }
        $templateParams['unread_notifications_count'] = $unreadCount;
       
    } elseif ($userId) {
        $allUserNotifications = $dbh->getOrderNotificationByCustomerId($userId); 
        $unreadCount = 0;
        if (is_array($allUserNotifications)) {
            foreach ($allUserNotifications as $notification) {
                if (isset($notification['Seen']) && $notification['Seen'] == 0) { 
                    $unreadCount++;
                }
            }
        }
        $templateParams['unread_notifications_count'] = $unreadCount;
       
    }

    if (!$is_admin_view && $userId) {
        $cartDetails = $dbh->getCartByCustomerId($userId);
        if ($cartDetails && isset($cartDetails['Item_count'])) {
            $templateParams['cart_item_count'] = $cartDetails['Item_count'];
        }
    }
}


?>
