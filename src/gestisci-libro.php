<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Gestisci Libro";
$templateParams["nome"] = "gestisci-libripage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["autori"] = $dbh->getAuthors();
$templateParams["case_editrici"] = $dbh->getPublishers();

$defaultBookData = [
    "Id" => null,
    "Title" => "",
    "Author_id" => "",
    "Publisher_id" => "",
    "Category_id" => "",
    "Price" => "",
    "Product_count" => 0,
    "Description" => "",
    "Cover" => "",
    "Exceptr" => ""
];

$templateParams["libro"] = $defaultBookData;

if (isset($_GET['id'])) {
    $bookId = intval($_GET['id']);
    $bookFromDb = $dbh->getBookById($bookId);
    if ($bookFromDb) {
        $templateParams["libro"] = $bookFromDb;
    } else {
        $_SESSION['error_message'] = "Libro non trovato.";
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
    $quantita = isset($_POST['quantita']) ? intval($_POST['quantita']) : 0;
    $descrizione = trim($_POST['descrizione']);

    $copertinaToSave = $templateParams['libro']['Cover'];
    $estrattoToSave = $templateParams['libro']['Exceptr'];

    $errors = [];

    if (isset($_FILES['copertina']) && $_FILES['copertina']['error'] == UPLOAD_ERR_OK) {
        list($result, $msg) = uploadImage("resources/images/", $_FILES['copertina']);
        if ($result) {
            $copertinaToSave = $msg;
        } else {
            $errors[] = "Errore caricamento copertina: " . htmlspecialchars($msg);
        }
    } elseif (isset($_FILES['copertina']) && $_FILES['copertina']['error'] != UPLOAD_ERR_NO_FILE) {
        $errors[] = "Errore file copertina: codice " . $_FILES['copertina']['error'];
    }

    if (isset($_FILES['estratto']) && $_FILES['estratto']['error'] == UPLOAD_ERR_OK) {
        $titoloPerNomeFile = !empty($titolo) ? $titolo : 'estratto_libro';
        list($result, $msg) = uploadFile("resources/exceptr/", $_FILES['estratto'], $titoloPerNomeFile);
        if ($result) {
            $estrattoToSave = $msg;
        } else {
            $errors[] = "Errore caricamento estratto: " . htmlspecialchars($msg);
        }
    } elseif (isset($_FILES['estratto']) && $_FILES['estratto']['error'] != UPLOAD_ERR_NO_FILE) {
        $errors[] = "Errore file estratto: codice " . $_FILES['estratto']['error'];
    }

    if (empty($titolo)) $errors[] = "Il titolo è obbligatorio.";
    if (empty($autore)) $errors[] = "L'autore è obbligatorio.";
    if (empty($casaeditrice)) $errors[] = "La casa editrice è obbligatoria.";
    if (empty($categoria)) $errors[] = "La categoria è obbligatoria.";
    if ($prezzo <= 0) $errors[] = "Il prezzo deve essere maggiore di zero.";
    if ($quantita < 0) $errors[] = "La quantità non può essere negativa.";
    if (empty($descrizione)) $errors[] = "La descrizione è obbligatoria.";

    if (empty($errors)) {
        if ($id) {
            $successBook = $dbh->updateBook($id, $titolo, $descrizione, $prezzo, $copertinaToSave, $categoria, $casaeditrice, $autore, $estrattoToSave);
            $successQuantity = $dbh->updateBookQuantity($id, $quantita);

            if ($successBook && $successQuantity) {
                $_SESSION['success_message'] = "Libro aggiornato con successo!";
                header("Location: lista-libri.php");
                exit;
            } else {
                $_SESSION['form_error_message'] = "Errore durante l'aggiornamento del libro o della quantità.";
                $templateParams["libro"] = [
                    "Id" => $id, "Title" => $titolo, "Author_id" => $autore, "Publisher_id" => $casaeditrice,
                    "Category_id" => $categoria, "Price" => $prezzo, "Product_count" => $quantita,
                    "Description" => $descrizione, "Cover" => $copertinaToSave, "Exceptr" => $estrattoToSave
                ];
            }
        } else {
            $_SESSION['form_error_message'] = "Errore durante la creazione del libro.";
             $templateParams["libro"] = [
                "Id" => null, "Title" => $titolo, "Author_id" => $autore, "Publisher_id" => $casaeditrice,
                "Category_id" => $categoria, "Price" => $prezzo, "Product_count" => $quantita,
                "Description" => $descrizione, "Cover" => $copertinaToSave, "Exceptr" => $estrattoToSave
            ];
        }
    } else {
        $_SESSION['form_error_message'] = implode("<br>", $errors);
        $templateParams["libro"] = [
            "Id" => $id, "Title" => $titolo, "Author_id" => $autore, "Publisher_id" => $casaeditrice,
            "Category_id" => $categoria, "Price" => $prezzo, "Product_count" => $quantita,
            "Description" => $descrizione, "Cover" => $copertinaToSave, "Exceptr" => $estrattoToSave
        ];
    }
}

require 'template/base.php';
?>