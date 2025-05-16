<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn()){
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Modifica Autore";
$templateParams["nome"] = "gestisci-autoripage.php";
$templateParams["categorie"] = $dbh->getCategories();

$idautore = isset($_GET["id"]) ? intval($_GET["id"]) : -1;
$autore = $dbh->getAuthorById($idautore);

if(!$autore){
    $_SESSION['form_error_message'] = "Autore non trovato.";
    header("Location: lista-autori.php");
    exit;
}

// Se il form NON è stato inviato, carica i dati dell'autore per il form
$templateParams["autore"] = $autore;

// Se il form è stato inviato, aggiorna i dati e ripopola in caso di errore
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);

    $errors = [];
    if (empty($nome)) $errors[] = "Il nome è obbligatorio.";
    if (empty($cognome)) $errors[] = "Il cognome è obbligatorio.";
    var_dump($errors);  
    if (empty($errors)) {
        $success = $dbh->updateAuthor($idautore, $nome, $cognome);
        if ($success) {
            $_SESSION['success_message'] = "Autore aggiornato con successo!";
            header("Location: lista-autori.php");
            exit;
        } else {
            $_SESSION['form_error_message'] = "Esiste già un autore con questo nome e cognome.";
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
    }
    // In caso di errore, ripopola i dati nel form
    $templateParams["autore"][0]["First_name"] = $nome;
    $templateParams["autore"][0]["Last_name"] = $cognome;
}

require 'template/base.php';
?>