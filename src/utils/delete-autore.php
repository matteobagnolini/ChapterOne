<?php
require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $dbh->deleteAuthor($_GET['id']);
}

header("Location: ../lista-autori.php");
exit;
?>