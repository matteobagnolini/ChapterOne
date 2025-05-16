<?php
require_once 'bootstrap.php';

if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Crea Libro";
$templateParams["nome"] = "crea-libropage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["autori"] = $dbh->getAuthors();
$templateParams["case_editrici"] = $dbh->getPublishers();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $titolo = trim($_POST['titoloarticolo']);
    $descrizione = trim($_POST['descrizione']);
    $prezzo = trim($_POST['prezzo']);
    $categoria_id = intval($_POST['categoria']);
    $autore_id = intval($_POST['autore']);
    $casa_editrice_id = intval($_POST['casaeditrice']);

    $errors = [];

    if(isset($_FILES['copertina']) && $_FILES['copertina']['error'] == UPLOAD_ERR_OK){
        list($uploadOk, $copertina) = uploadImage("resources/images/", $_FILES['copertina']);
        if(!$uploadOk){
            $errors[] = $copertina; // $copertina contiene il messaggio di errore
        }
    } else {
        $errors[] = "Il campo 'Copertina' è obbligatorio.";
    }

    $estratto = "";
    if(isset($_FILES['estratto']) && $_FILES['estratto']['error'] == UPLOAD_ERR_OK){
        list($uploadOkestratto, $estrattoFile) = uploadFile("resources/exceptr/", $_FILES['estratto']);
        if($uploadOkestratto){
            $estratto = $estrattoFile;
        } else {
            $errors[] = $estrattoFile;
        }
    }



    if (empty($titolo)) $errors[] = "Il campo 'Titolo' è obbligatorio.";
    if (empty($prezzo) || !is_numeric($prezzo)) $errors[] = "Il campo 'Prezzo' è obbligatorio e deve essere un numero.";
    if (empty($categoria_id)) $errors[] = "Seleziona una categoria.";
    if (empty($autore_id)) $errors[] = "Seleziona un autore.";
    if (empty($casa_editrice_id)) $errors[] = "Seleziona una casa editrice.";

    if (empty($errors)) {
        $success = $dbh->insertBookWithExceptr($titolo, $descrizione, $prezzo, $copertina, $estratto, $categoria_id, $casa_editrice_id, $autore_id);
        if ($success) {
            $_SESSION['success_message'] = "Libro creato con successo!";
            header("Location: lista-libri.php");
            exit;
        } else {
            $_SESSION['form_error_message'] = "Errore durante la creazione del libro.";
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        $templateParams["book"] = [
            "Title" => $titolo,
            "Description" => $descrizione,
            "Price" => $prezzo,
            "Cover" => $copertina,
            "estratto" => $estratto,
            "Category_id" => $categoria_id,
            "Author_id" => $autore_id,
            "Publisher_id" => $casa_editrice_id
        ];
    }
}

require 'template/base.php';
?>