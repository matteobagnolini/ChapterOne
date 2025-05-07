<?php
require_once 'bootstrap.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $templateParams["titolo"] = "ChapterOne - Errore";
    $templateParams["nome"] = "404.php";
    $templateParams["errore"] = "Nessuna categoria specificata. Seleziona una categoria per visualizzarne i libri disponibili.";
} else {
    $IdCategoria = $_GET["id"];

    $categoria = $dbh->getCategoryById($IdCategoria);
    
    if ($categoria === null) {
        $templateParams["nome"] = "404.php";
        $templateParams["errore"] = "La categoria cercata non esiste o non è disponibile.";
    } else {
        $templateParams["nome"] = "carosello-libri.php";
        $templateParams["categoria"] = $categoria;
        $templateParams["titolo"] = "ChapterOne - " . $categoria["Name"];
        $templateParams["libri"] = $dbh->getBooksFromCategoryId($IdCategoria);
    }
}

$templateParams["categorie"] = $dbh->getCategories();

require 'template/base.php';

?>