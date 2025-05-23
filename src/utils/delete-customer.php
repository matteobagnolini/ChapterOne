<?php

require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: ../login.php"); 
    exit;
}

if (isset($_GET['id'])) {
    $customerId = $_GET['id'];
    
    $success = $dbh->deleteCustomer($customerId); 

    if ($success) {
        $_SESSION['success_message'] = "Cliente eliminato con successo.";
    } else {
        $_SESSION['error_message'] = "Impossibile eliminare il cliente. Potrebbe avere dati associati o l'ID non è valido.";
    }
} else {
    $_SESSION['error_message'] = "ID cliente non specificato per l'eliminazione.";
}

header("Location: ../lista-clienti.php"); 
exit;
?>