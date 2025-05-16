<?php

require_once '../bootstrap.php';


// Controllo accesso Admin
if (!isAdminLoggedIn()) {
    header("location: ../login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['current_status'])) {
    $discountCodeId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    // current_status viene usato solo per determinare il nuovo stato, non per validare il record esistente
    $currentStatusForToggle = filter_var($_GET['current_status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);


    if ($discountCodeId === false || $discountCodeId <= 0 || $currentStatusForToggle === null) {
        $_SESSION['error_message'] = "ID codice sconto o stato corrente non valido per l'operazione.";
        header("location: ../lista-codicisconto.php");
        exit;
    }

    // 1. Recupera i dati esistenti del codice sconto
    $existingDiscountCode = $dbh->getDiscountCodeById($discountCodeId);

    if (!$existingDiscountCode) {
        $_SESSION['error_message'] = "Codice sconto non trovato.";
        header("location: ../lista-codicisconto.php");
        exit;
    }

    // 2. Determina il nuovo stato 'Active'
    // $currentStatusForToggle è lo stato visualizzato nella tabella, che vogliamo invertire.
    $newActiveStatus = !$currentStatusForToggle;

    // 3. Chiama updateDiscountCode con tutti i campi, modificando solo 'Active'
    // Assicurati che i nomi dei campi qui corrispondano esattamente ai parametri di updateDiscountCode
    // e ai campi restituiti da getDiscountCodeById.
    // Il valore di Single_use potrebbe essere 0 o 1 dal DB, assicurati che sia un booleano per la funzione se necessario.
    $success = $dbh->updateDiscountCode(
        $existingDiscountCode['Id'],
        $existingDiscountCode['Code'],
        $existingDiscountCode['Type'],
        $existingDiscountCode['Value'],
        $existingDiscountCode['Start_date'], // Formato YYYY-MM-DD come da DB
        $existingDiscountCode['End_date'],   // Formato YYYY-MM-DD come da DB
        (bool)$existingDiscountCode['Single_use'], // Converte in booleano se necessario
        $newActiveStatus // Il nuovo stato attivo
    );

    if ($success) {
        $_SESSION['success_message'] = "Stato del codice sconto aggiornato con successo utilizzando updateDiscountCode.";
    } else {
        $_SESSION['error_message'] = "Errore durante l'aggiornamento dello stato del codice sconto.";
    }

} else {
    $_SESSION['error_message'] = "Parametri mancanti per l'operazione.";
}

// Reindirizza sempre alla lista dei codici sconto
header("location: ../lista-codicisconto.php");
exit;
?>