<?php
require_once 'bootstrap.php';

if (!isset($_GET['query']) || empty($_GET['query'])) {
    $templateParams["titolo"] = "ChapterOne - Errore";
    $templateParams["nome"] = "404.php";
    $templateParams["errore"] = "Nessuna ricerca specifica. Assicurati di aver inserito una ricerca e riprova.";
} else {
    $templateParams["nome"] = "carosello-libri.php";
    $templateParams["titolo"] = "ChapterOne - Ricerca Libro";
    $templateParams["intestazione"] = "Risultati Ricerca";
    $query = $_GET["query"];

    $book = $dbh->searchBooks($query);
    $templateParams["libri"] = $book;
    
    if ($book === null || count($book) === 0) {
        $templateParams["testo"] = "Spiacenti, la ricerca di '<b>" . $query . "</b>' non ha prodotto nessun risultato. Riprova con un altro libro.";
    } else {
        $templateParams["testo"] =  "Risultati per la ricerca di '<b>" . $query . "</b>' :"; 
    }
}

$templateParams["categorie"] = $dbh->getCategories();

require 'template/base.php';

?>