<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Catalogo";
$templateParams["nome"] = "carosello-libri.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["libri"] = $dbh->getBooks();
$templateParams["intestazione"] = "Il Nostro Catalogo Completo";
$templateParams["testo"] = "Esplora la nostra vasta selezione di libri. Troverai sicuramente la tua prossima lettura preferita tra i titoli disponibili.";

require 'template/base.php';

?>