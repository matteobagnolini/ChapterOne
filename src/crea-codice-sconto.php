<?php
require_once 'bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Crea Codice Sconto";
$templateParams["nome"] = "crea-codice-scontopage.php";
$templateParams["categorie"] = $dbh->getCategories();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $code = trim($_POST['code']);
    $type = trim($_POST['type']);
    $value = filter_var(str_replace(',', '.', trim($_POST['value'])), FILTER_VALIDATE_FLOAT);
    $startDate = trim($_POST['start_date']);
    $endDate = trim($_POST['end_date']);
    $singleUse = isset($_POST['single_use']) ? 1 : 0;
    $active = isset($_POST['active']) ? 1 : 0;

    $errors = [];
    if (empty($code)) {
        $errors[] = "Il campo 'Codice Sconto' è obbligatorio.";
    } elseif (strlen($code) > 50) {
        $errors[] = "Il codice sconto non può superare i 50 caratteri.";
    }
    if ($type !== 'percentage' && $type !== 'fixed') {
        $errors[] = "Tipo sconto non valido.";
    }
    if ($value === false || $value < 0) {
        $errors[] = "Valore sconto non valido o negativo.";
    } elseif ($type === 'percentage' && $value > 100) {
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
        $success = $dbh->insertDiscountCode($code, $type, $value, $startDate, $endDate, $singleUse, $active);
        if ($success) {
            $_SESSION['success_message'] = "Codice sconto creato con successo!";
            header("Location: lista-codicisconto.php");
            exit;
        } else {
            $_SESSION['form_error_message'] = "Errore durante la creazione del codice sconto. Il codice potrebbe già esistere.";
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        // Ripopola i campi in caso di errore
        $templateParams["codicesconto"] = [
            "Code" => $code,
            "Type" => $type,
            "Value" => $_POST['value'],
            "Start_date" => $startDate,
            "End_date" => $endDate,
            "Single_use" => $singleUse,
            "Active" => $active
        ];
    }
}

require 'template/base.php';
?>