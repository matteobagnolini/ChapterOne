<?php
require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $dbh->deleteBook($_GET['id']);
}

header("Location: ../lista-libri.php");
exit;
?>