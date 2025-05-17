<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne";
$templateParams["categorie"] = $dbh->getCategories();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $templateParams["nome"] = "404.php";
    $templateParams["errore"] = "Nessun libro specificato. Seleziona un libro per visualizzarne i dettagli.";
} else {
    $bookId = $_GET['id'];
    $book = $dbh->getBookDetailsById($bookId);
    
    if ($book === null) {
        $templateParams["nome"] = "404.php";
        $templateParams["errore"] = "Il libro richiesto non è stato trovato.";
    } else {
        $templateParams["nome"] = "bookdetails.php";
        $templateParams["libro"] = $book;
        $templateParams["titolo"] = "ChapterOne - " . $book["Title"];
        $templateParams["recensioni"] = $dbh->getBookReviews($bookId);
        $templateParams["librisimili"] = $dbh->getRelatedBooks($bookId);
        $templateParams["abilitarecensione"] = isUserLoggedIn() && $dbh->hasUserPurchaseBookId($_SESSION["id"], $bookId);
    }
}
require 'template/base.php';

?>