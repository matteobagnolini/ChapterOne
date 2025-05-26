<?php

require_once 'bootstrap.php';

if (!isAdminLoggedIn()) {
    if (isUserLoggedIn()) {
        header("location: index.php"); 
    } else {
        header("location: login.php"); 
    }
    exit; 
}

$templateParams["titolo"] = "ChapterOne - Gestisci Codice Sconto";
$templateParams["nome"] = "gestisci-codice-scontopage.php";
$templateParams["categorie"] = $dbh->getCategories();

$isEditing = false;
$discountCodeId = null;

if (isset($_GET['id'])) {
    $discountCodeId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($discountCodeId && $discountCodeId > 0) {
        $isEditing = true;
        $existingCode = $dbh->getDiscountCodeById($discountCodeId);
        if ($existingCode) {
            $templateParams["codicesconto"] = $existingCode;
        } else {

            $_SESSION['error_message'] = "Codice sconto non trovato.";
            header("Location: lista-codicisconto.php");
            exit;
        }
    } else {
 
        $_SESSION['error_message'] = "ID codice sconto non valido."; 
        header("Location: lista-codicisconto.php");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $code = trim($_POST['code']);
    $type = trim($_POST['type']);
    $value = filter_var(trim($_POST['value']), FILTER_VALIDATE_FLOAT);
    $startDate = trim($_POST['start_date']);
    $endDate = trim($_POST['end_date']);
    $singleUse = isset($_POST['single_use']) ? 1 : 0; 
    $active = isset($_POST['active']) ? 1 : 0;     
    $idToUpdate = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_VALIDATE_INT) : null;

    // --- Validazione base ---
    $errors = [];
    if (empty($code)) {
        $errors[] = "Il campo 'Codice Sconto' è obbligatorio.";
    }
    if (strlen($code) > 50) {
        $errors[] = "Il codice sconto non può superare i 50 caratteri.";
    }
    if ($type !== 'percentage' && $type !== 'fixed') {
        $errors[] = "Tipo sconto non valido.";
    }
    if ($value === false || $value < 0) {
        $errors[] = "Valore sconto non valido o negativo.";
    }
    if ($type === 'percentage' && $value > 100) {
        $errors[] = "Il valore percentuale non può superare 100.";
    }
    if (empty($startDate) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $startDate)) {
        $errors[] = "Data inizio non valida.";
    }
    if (empty($endDate) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $endDate)) {
        $errors[] = "Data fine non valida.";
    }
    if (!empty($startDate) && !empty($endDate) && strtotime($endDate) < strtotime($startDate)) {
        $errors[] = "La data di fine non può essere precedente alla data di inizio.";
    }


    if (empty($errors)) {
        if ($idToUpdate && $isEditing) { 
            $success = $dbh->updateDiscountCode($idToUpdate, $code, $type, $value, $startDate, $endDate, $singleUse, $active);
            if ($success) {
                $_SESSION['success_message'] = "Codice sconto aggiornato con successo!";
                header("Location: lista-codicisconto.php");
                exit;
            } else {
                $_SESSION['form_error_message'] = "Errore durante l'aggiornamento del codice sconto."; 
            }
        } else { 
            $success = $dbh->insertDiscountCode($code, $type, $value, $startDate, $endDate, $singleUse, $active);
            if ($success) {
                $_SESSION['success_message'] = "Codice sconto creato con successo!"; 
                header("Location: lista-codicisconto.php");
                exit;
            } else {
                $_SESSION['form_error_message'] = "Errore durante la creazione del codice sconto."; 
            }
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
    
        $templateParams["codicesconto_input"] = $_POST; 
        if ($isEditing && $idToUpdate) {
             $templateParams["codicesconto"] = $dbh->getDiscountCodeById($idToUpdate);
        }
    }
}


require 'template/base.php';
?>