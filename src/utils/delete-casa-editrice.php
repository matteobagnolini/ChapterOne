<?php
require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Prova a eliminare la casa editrice
    $success = $dbh->deletePublisher($id);
    if ($success) {
        $_SESSION['success_message'] = "Casa editrice eliminata con successo.";
    } else {
        $_SESSION['form_error_message'] = "Impossibile eliminare la casa editrice. Potrebbe essere associata a uno o più libri.";
    }
}
header("Location: ../lista-case-editrici.php");
exit;
?>