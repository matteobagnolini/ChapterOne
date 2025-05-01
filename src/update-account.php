<?php
// filepath: c:\Users\Giuseppe\Documents\Progetti\ChapterOne\src\update-account.php
require_once 'bootstrap.php';

if (!isUserLoggedIn()) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['action']) && $_POST['action'] === 'update_account') {
    $user = $dbh->getCustomerByUsername( $_SESSION['username']);  // Assicurati che 'id' sia la chiave corretta nella sessione
    $userId = $user['Id']; // ID dell'utente da aggiornare
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $submittedPassword = $_POST['password'] ?? ''; // Password inviata dal form
    $address = $_POST['address'] ?? '';
    $phone = $_POST['phone'] ?? '';

    $nameParts = explode(' ', trim($name), 2);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

    try {
        
        $result = $dbh->updateCustomer(
            $userId,
            $firstName,
            $lastName,
            $email,
            $submittedPassword, // Passa la nuova password o null
            $address,
            $phone
        );

        if ($result) {
            $_SESSION['update_message'] = 'Profilo aggiornato con successo!';
            $_SESSION['update_message_type'] = 'success';
            $_SESSION['username'] = $email; 
        } else {
            $_SESSION['update_message'] = 'Nessuna modifica rilevata.';
            $_SESSION['update_message_type'] = 'info';

        }
    } catch (Exception $e) {

        error_log("Errore aggiornamento account per utente " . $userId . ": " . $e->getMessage());
        $_SESSION['update_message'] = 'Si è verificato un errore tecnico durante l\'aggiornamento.';
        $_SESSION['update_message_type'] = 'danger';
    }
} else {
    $_SESSION['update_message'] = 'Richiesta non valida.';
    $_SESSION['update_message_type'] = 'warning';
}


header('Location: account.php');
exit;

?>