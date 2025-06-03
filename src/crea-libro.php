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

$templateParams["titolo"] = "ChapterOne - Crea Libro";
$templateParams["nome"] = "crea-libropage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["autori"] = $dbh->getAuthors();
$templateParams["case_editrici"] = $dbh->getPublishers();


$templateParams["book_input"] = [
    "Title" => "", 
    "Description" => "", 
    "Price" => "", 
    "Cover" => null,
    "Exceptr" => null, 
    "Category_id" => "", 
    "Author_id" => "",
    "Publisher_id" => "", 
    "Product_count" => 0
];


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $titolo = trim($_POST['titoloarticolo']);
    $descrizione = trim($_POST['descrizione']);
    $prezzo = trim($_POST['prezzo']);
    $categoria_id = intval($_POST['categoria']);
    $autore_id = intval($_POST['autore']);
    $quantita = isset($_POST['quantita']) ? intval($_POST['quantita']) : 0;
    $casa_editrice_id = intval($_POST['casaeditrice']);

    $errors = [];
    $nomeFileCopertina = null;
    $nomeFileEstratto = null;

 
    if (isset($_FILES['copertina']) && $_FILES['copertina']['error'] == UPLOAD_ERR_OK) {
        list($uploadOk, $risultatoUpload) = uploadImage("resources/images/", $_FILES['copertina']);
        if ($uploadOk) {
            $nomeFileCopertina = $risultatoUpload;
        }
    }

    if (isset($_FILES['estratto']) && $_FILES['estratto']['error'] == UPLOAD_ERR_OK) {
        $titoloPerNomeFileEstratto = !empty($titolo) ? $titolo : 'estratto_default'; 
        list($uploadOkEstratto, $risultatoUploadEstratto) = uploadFile("resources/exceptr/", $_FILES['estratto'], $titoloPerNomeFileEstratto);
        if ($uploadOkEstratto) {
            $nomeFileEstratto = $risultatoUploadEstratto;
        }
    }

    $idLibroCreato = $dbh->insertBookWithExceptr($titolo, $descrizione, $prezzo, $nomeFileCopertina, $nomeFileEstratto, $categoria_id, $casa_editrice_id, $autore_id);
    if ($idLibroCreato) { 
        $successQuantity = $dbh->updateBookQuantity($idLibroCreato, $quantita);
        if ($successQuantity) {
            header("Location: lista-libri.php");
            exit;
        }
    }

    $templateParams["book_input"] = [
        "Title" => $titolo,
        "Description" => $descrizione,
        "Price" => $prezzo,
        "Cover" => $nomeFileCopertina, 
        "Exceptr" => $nomeFileEstratto, 
        "Category_id" => $categoria_id,
        "Author_id" => $autore_id,
        "Publisher_id" => $casa_editrice_id,
        "Product_count" => $quantita
    ];
}

require 'template/base.php';
?>