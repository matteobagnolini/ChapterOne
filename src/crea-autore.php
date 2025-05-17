<?php
require_once 'bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Crea Autore";
$templateParams["nome"] = "crea-autorepage.php";
$templateParams["categorie"] = $dbh->getCategories();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    $errors = [];
    if (empty($first_name)) {
        $errors[] = "Il campo 'Nome' è obbligatorio.";
    }
    if (empty($last_name)) {
        $errors[] = "Il campo 'Cognome' è obbligatorio.";
    }

    if (empty($errors)) {
        $success = $dbh->insertAuthor($first_name, $last_name);
        if ($success) {
            $_SESSION['success_message'] = "Autore creato con successo!";
            header("Location: lista-autori.php");
            exit;
        } else {
            $_SESSION['form_error_message'] = "Errore: esiste già un autore con questo nome e cognome.";
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        // Ripopola i campi in caso di errore
        $templateParams["author"] = [
            "First_name" => $first_name,
            "Last_name" => $last_name
        ];
    }
}

require 'template/base.php';
?>