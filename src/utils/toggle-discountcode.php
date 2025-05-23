<?php

require_once '../bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: ../login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['current_status'])) {
    $discountCodeId = filter_var($_GET['id'], FILTER_VALIDATE_INT);

    $currentStatusForToggle = filter_var($_GET['current_status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    if ($discountCodeId === false || $discountCodeId <= 0 || $currentStatusForToggle === null) {
        $_SESSION['error_message'] = "ID codice sconto o stato corrente non valido per l'operazione.";
        header("location: ../lista-codicisconto.php");
        exit;
    }


    $existingDiscountCode = $dbh->getDiscountCodeById($discountCodeId);

    if (!$existingDiscountCode) {
        $_SESSION['error_message'] = "Codice sconto non trovato.";
        header("location: ../lista-codicisconto.php");
        exit;
    }

    $newActiveStatus = !$currentStatusForToggle;

    $success = $dbh->updateDiscountCode(
        $existingDiscountCode['Id'],
        $existingDiscountCode['Code'],
        $existingDiscountCode['Type'],
        $existingDiscountCode['Value'],
        $existingDiscountCode['Start_date'], 
        $existingDiscountCode['End_date'], 
        (bool)$existingDiscountCode['Single_use'],
        $newActiveStatus
    );

    if ($success) {
        $_SESSION['success_message'] = "Stato del codice sconto aggiornato con successo utilizzando updateDiscountCode.";
    } else {
        $_SESSION['error_message'] = "Errore durante l'aggiornamento dello stato del codice sconto.";
    }

} 


header("location: ../lista-codicisconto.php");
exit;
?>