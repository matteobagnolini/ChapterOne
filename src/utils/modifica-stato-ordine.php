<?php
require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: ../login.php");
    exit;
}

if (isset($_GET['id_order'], $_GET['next_status'])) {
    $id = intval($_GET['id_order']);
    $nextStatus = $_GET['next_status'];
    $allowed = ['sent', 'arrived'];
    if (in_array($nextStatus, $allowed)) {
        $dbh->updateOrderStatus($id, $nextStatus);
    }
}
header("Location: ../lista-ordini.php");
exit;
?>