<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn()){
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Modifica Autore";
$templateParams["nome"] = "gestisci-autoripage.php";
$templateParams["categorie"] = $dbh->getCategories();

$idautore = isset($_GET["id"]) ? intval($_GET["id"]) : (isset($_POST["id"]) ? intval($_POST["id"]) : -1);
$author = $dbh->getAuthorById($idautore);

if(!$author){
    $_SESSION['form_error_message'] = "Autore non trovato.";
    header("Location: lista-autori.php");
    exit;
}

// Se il form NON è stato inviato, carica i dati dell'autore per il form
$templateParams["author"] = $author;

// Se il form è stato inviato, aggiorna i dati e ripopola in caso di errore
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);

    $errors = [];
    if (empty($first_name)) $errors[] = "Il nome è obbligatorio.";
    if (empty($last_name)) $errors[] = "Il cognome è obbligatorio.";

    if (empty($errors)) {
        $success = $dbh->updateAuthor($idautore, $first_name, $last_name);
        if ($success) {
            $_SESSION['success_message'] = "Autore aggiornato con successo!";
            header("Location: lista-autori.php");
            exit;
        } else {
            $_SESSION['form_error_message'] = "Esiste già un autore con questo nome e cognome.";
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);

        $templateParams["author"]["First_name"] = $first_name;
        $templateParams["author"]["Last_name"] = $last_name;
    }
}

require 'template/base.php';
?>