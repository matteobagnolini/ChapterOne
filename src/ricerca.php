<?php
require_once 'bootstrap.php';


$templateParams["titolo"] = "ChapterOne - Risultati Ricerca";
$templateParams["nome"] = "risultati-ricercapage.php"; // Template per visualizzare i risultati
$templateParams["categorie"] = $dbh->getCategories(); // Per la navbar

$templateParams["risultatiLibri"] = [];
$templateParams["risultatiAutori"] = [];
$templateParams["termineRicerca"] = "";
$templateParams["tipoRisultato"] = ""; // 'libri', 'autori', o 'nessuno'

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $searchTerm = trim($_GET['query']);
    $templateParams["termineRicerca"] = htmlspecialchars($searchTerm);

    // 1. Cerca nei libri
    $risultatiLibri = $dbh->searchBooks($searchTerm);

    if (!empty($risultatiLibri)) {
        $templateParams["risultatiLibri"] = $risultatiLibri;
        $templateParams["tipoRisultato"] = "libri";
    } else {
        // 2. Se non trovi nulla nei libri, cerca negli autori
        $risultatiAutori = $dbh->searchAuthors($searchTerm);
        if (!empty($risultatiAutori)) {
            $templateParams["risultatiAutori"] = $risultatiAutori;
            $templateParams["tipoRisultato"] = "autori";
        } else {
            $templateParams["tipoRisultato"] = "nessuno";
        }
    }
} else {
    // Nessun termine di ricerca fornito o query vuota
    $templateParams["tipoRisultato"] = "query_mancante";
}

require 'template/base.php';

?>