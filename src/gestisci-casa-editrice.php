<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Modifica Casa Editrice";
$templateParams["nome"] = "gestisci-case-editricipage.php";
$templateParams["categorie"] = $dbh->getCategories();

$editMode = false;
$publisher = [
    "Id" => "",
    "Name" => "",
    "Address" => ""
];

// Se c'è un id in GET, carica i dati della casa editrice
if (isset($_GET['id'])) {
    $editMode = true;
    $publisher = $dbh->getPublisherById($_GET['id']);
    if (!$publisher) {
        $_SESSION['form_error_message'] = "Casa editrice non trovata.";
        header("Location: lista-case-editrici.php");
        exit;
    }
}

// Gestione submit del form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nome = trim($_POST['nome']);
    $indirizzo = trim($_POST['indirizzo']);

    $errors = [];
    if (empty($nome)) $errors[] = "Il nome è obbligatorio.";
    if (empty($indirizzo)) $errors[] = "L'indirizzo è obbligatorio.";

    if (empty($errors)) {
        if ($id) {
            // Modifica
            $success = $dbh->updatePublisher($id, $nome, $indirizzo);
            if ($success) {
                $_SESSION['success_message'] = "Casa editrice aggiornata con successo!";
                header("Location: lista-case-editrici.php");
                exit;
            } else {
                $_SESSION['form_error_message'] = "Errore durante l'aggiornamento. Il nome potrebbe già esistere.";
            }
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        $publisher = [
            "Id" => $id,
            "Name" => $nome,
            "Address" => $indirizzo
        ];
    }
}

$templateParams["publisher"] = $publisher;
$templateParams["editMode"] = $editMode;

require 'template/base.php';
?>