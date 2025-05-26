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
        } else {
            $errors[] = $risultatoUpload; 
        }
    } else {

        if (isset($_FILES['copertina']['error']) && $_FILES['copertina']['error'] == UPLOAD_ERR_NO_FILE) {
            $errors[] = "Il campo 'Copertina' è obbligatorio.";
        } elseif (isset($_FILES['copertina']['error'])) {
            $errors[] = "Errore durante il caricamento della copertina (Codice: " . $_FILES['copertina']['error'] . "). Il campo è obbligatorio.";
        } else {
            $errors[] = "Il campo 'Copertina' è obbligatorio e non è stato fornito/caricato correttamente.";
        }
    }


    if (isset($_FILES['estratto']) && $_FILES['estratto']['error'] == UPLOAD_ERR_OK) {
        $titoloPerNomeFileEstratto = !empty($titolo) ? $titolo : 'estratto_default'; 
        list($uploadOkEstratto, $risultatoUploadEstratto) = uploadFile("resources/exceptr/", $_FILES['estratto'], $titoloPerNomeFileEstratto);
        if ($uploadOkEstratto) {
            $nomeFileEstratto = $risultatoUploadEstratto;
        } else {
            $errors[] = "Errore durante il caricamento dell'estratto: " . $risultatoUploadEstratto;
        }
    } elseif (isset($_FILES['estratto']) && $_FILES['estratto']['error'] != UPLOAD_ERR_NO_FILE) {
  
        $errors[] = "Problema con il file estratto fornito (Codice: " . $_FILES['estratto']['error'] . ").";
    }

 
    if (empty($titolo)) $errors[] = "Il campo 'Titolo' è obbligatorio.";
    if (empty($prezzo) || !is_numeric($prezzo) || floatval($prezzo) <= 0) $errors[] = "Il campo 'Prezzo' è obbligatorio, deve essere un numero maggiore di zero.";
    if (empty($categoria_id)) $errors[] = "Seleziona una categoria.";
    if (empty($autore_id)) $errors[] = "Seleziona un autore.";
    if (empty($casa_editrice_id)) $errors[] = "Seleziona una casa editrice.";
    if ($quantita < 0) $errors[] = "La quantità non può essere negativa.";



    if (empty($errors)) {
        $idLibroCreato = $dbh->insertBookWithExceptr($titolo, $descrizione, $prezzo, $nomeFileCopertina, $nomeFileEstratto, $categoria_id, $casa_editrice_id, $autore_id);
        
        if ($idLibroCreato) { 
            $successQuantity = $dbh->updateBookQuantity($idLibroCreato, $quantita);
            if ($successQuantity) {
                $_SESSION['success_message'] = "Libro creato con successo!";
                header("Location: lista-libri.php");
                exit;
            } else {
                $_SESSION['form_error_message'] = "Libro creato (ID: $idLibroCreato), ma si è verificato un errore durante l'aggiornamento della quantità. Contatta l'assistenza.";

            }
        } else {
            $_SESSION['form_error_message'] = "Errore durante la creazione del libro (fallito inserimento dati base).";
        }
    }
    
   
    if (!empty($errors) || isset($_SESSION['form_error_message'])) {
        if (!empty($errors) && !isset($_SESSION['form_error_message'])) { 
             $_SESSION['form_error_message'] = implode("<br>", $errors);
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
}

require 'template/base.php';
?>