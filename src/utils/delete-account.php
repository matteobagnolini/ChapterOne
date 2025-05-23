<?php

require_once '../bootstrap.php';

$nomeutente = $_SESSION['username'] ?? null;
$customer = $dbh->getCustomerByUsername($nomeutente);
$id = $customer['Id'] ?? null;
if ($id) {
    
    $success = $dbh->deleteCustomer($id); 

    if ($success) {
        $_SESSION['success_message'] = "Cliente eliminato con successo.";
    } else {
        $_SESSION['error_message'] = "Impossibile eliminare il cliente. Potrebbe avere dati associati o l'ID non è valido.";
    }
} else {
    $_SESSION['error_message'] = "ID cliente non specificato per l'eliminazione.";
}

header("Location: ../logout.php"); 
exit;
?>