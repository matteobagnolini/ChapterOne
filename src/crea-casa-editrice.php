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

$templateParams["titolo"] = "ChapterOne - Crea Casa Editrice";
$templateParams["nome"] = "crea-casa-editricepage.php";
$templateParams["categorie"] = $dbh->getCategories();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $indirizzo = trim($_POST['indirizzo']);

    $errors = [];
    if (empty($nome)) {
        $errors[] = "Il campo 'Nome' è obbligatorio.";
    }
    if (empty($indirizzo)) {
        $errors[] = "Il campo 'Indirizzo' è obbligatorio.";
    }

    if (empty($errors)) {
        $success = $dbh->insertPublisher($nome, $indirizzo);
        if ($success) {
            $_SESSION['success_message'] = "Casa editrice creata con successo!";
            header("Location: lista-case-editrici.php");
            exit;
        } else {
            $_SESSION['form_error_message'] = "Errore: esiste già una casa editrice con questo nome.";
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        $templateParams["publisher"] = [
            "Name" => $nome,
            "Address" => $indirizzo
        ];
    }
}

require 'template/base.php';
?>