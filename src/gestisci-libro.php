<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Modifica Libro";
$templateParams["nome"] = "gestisci-libripage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["autori"] = $dbh->getAuthors();
$templateParams["case_editrici"] = $dbh->getPublishers();


$libro = [
    "Id" => "",
    "Title" => "",
    "Author_id" => "",
    "Publisher_id" => "",
    "Category_id" => "",
    "Price" => "",
    "Description" => "",
    "Cover" => "",
    "Exceptr" => ""
];

// Se c'è un id in GET, carica i dati della casa editrice
if (isset($_GET['id'])) {
    $editMode = true;
    $book = $dbh->getBookById($_GET['id']);
    $templateParams["libro"] = $book;
    if (!$book) {
        header("Location: lista-libri.php");
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id = isset($_POST['idlibro']) ? intval($_POST['idlibro']) : null;
    $titolo = trim($_POST['titoloarticolo']);
    $autore = intval($_POST['autore']);
    $casaeditrice = intval($_POST['casaeditrice']);
    $categoria = intval($_POST['categoria']);
    $prezzo = floatval($_POST['prezzo']);
    $descrizione = trim($_POST['descrizione']);

    // Gestione copertina
    $copertina = $book['Cover'];
    if (isset($_FILES['copertina']) && $_FILES['copertina']['error'] == UPLOAD_ERR_OK) {
        list($result, $msg) = uploadImage("resources/images/", $_FILES['copertina']);
        if ($result) {
            $copertina = $msg;
        }
    }

    // Gestione estratto
    $estratto = $book['Exceptr'];
    if (isset($_FILES['estratto']) && $_FILES['estratto']['error'] == UPLOAD_ERR_OK) {
        list($result, $msg) = uploadFile("resources/exceptr/", $_FILES['estratto']);
        if ($result) {
            $estratto = $msg;
        }
    }

    $errors = [];
    if (empty($titolo)) $errors[] = "Il titolo è obbligatorio.";
    if (empty($autore)) $errors[] = "L'autore è obbligatorio.";
    if (empty($casaeditrice)) $errors[] = "La casa editrice è obbligatoria.";
    if (empty($categoria)) $errors[] = "La categoria è obbligatoria.";
    if ($prezzo <= 0) $errors[] = "Il prezzo deve essere maggiore di zero.";
    if (empty($descrizione)) $errors[] = "La descrizione è obbligatoria.";

    if (empty($errors) && empty($_SESSION['form_error_message'])) {
        if ($id) {
            // Modifica libro
            // updateBook($id, $title, $description, $price, $cover, $categoryId, $publisherId, $authorId)
            $success = $dbh->updateBook($id, $titolo, $descrizione, $prezzo, $copertina, $categoria, $casaeditrice, $autore);
            if ($success) {
                $_SESSION['success_message'] = "Libro aggiornato con successo!";
                header("Location: lista-libri.php");
                exit;
            } else {
                $_SESSION['form_error_message'] = "Errore durante l'aggiornamento del libro.";
            }
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        $libro = [
            "Id" => $id,
            "Title" => $titolo,
            "Author_id" => $autore,
            "Publisher_id" => $casaeditrice,
            "Category_id" => $categoria,
            "Price" => $prezzo,
            "Description" => $descrizione,
            "Cover" => $copertina,
            "Exceptr" => $estratto
        ];
    }
}

$templateParams["libro"] = $book;

require 'template/base.php';
?>