<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne";
$templateParams["categorie"] = $dbh->getCategories();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Se l'ID non è presente, mostra la pagina di errore
    $templateParams["nome"] = "404.php";
    $templateParams["errore"] = "Nessun libro specificato. Seleziona un libro per visualizzarne i dettagli.";
} else {
    $bookId = $_GET['id'];
    $book = $dbh->getBookById($bookId);
    
    if ($book === null) {
        // Se il libro non esiste, mostra la pagina di errore
        $templateParams["nome"] = "404.php";
        $templateParams["errore"] = "Il libro richiesto non è stato trovato.";
    } else {
        // Se il libro esiste, mostra i dettagli
        $templateParams["nome"] = "bookdetails.php";
        $templateParams["libro"] = $book;
        // Opzionalmente, aggiungi altre informazioni correlate
        $templateParams["recensioni"] = $dbh->getBookReviews($bookId);
        $templateParams["librisimili"] = $dbh->getRelatedBooks($bookId);
    }
}

require 'template/base.php';

?>