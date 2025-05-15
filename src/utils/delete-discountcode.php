<?php

require_once '../bootstrap.php';


if (!isAdminLoggedIn()) {
  
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $discountCodeId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    if ($discountCodeId === false || $discountCodeId <= 0) {
        header("location: ../lista-codicisconto.php");
        exit;
    }

    $success = $dbh->deleteDiscountCode($discountCodeId);
} 

// Reindirizza sempre alla lista dei codici sconto
header("location: ../lista-codicisconto.php");
exit;
?>