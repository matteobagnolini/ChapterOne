<?php
require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $dbh->deleteCategory($id);
}
header("Location: ../lista-categorie.php");
exit;
?>