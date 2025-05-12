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

$templateParams["titolo"] = "ChapterOne - Gestione Libri"; // Titolo corretto per la pagina libri
$templateParams["nome"] = "lista-libripage.php";
$templateParams["categorie"] = $dbh->getCategories(); // Array di tutte le categorie [Id, Name]

$libriDaDb = $dbh->getBooks();
$templateParams["libri"] = [];

$categoryMap = [];
if (!empty($templateParams["categorie"])) {
    foreach ($templateParams["categorie"] as $categoria) {
        $categoryMap[$categoria['Id']] = $categoria['Name'];
    }
}

if (!empty($libriDaDb)) {
    foreach ($libriDaDb as $libro) {
  
        if (isset($libro["Author_id"])) {
            $author = $dbh->getAuthorById($libro["Author_id"]);
            $libro["Author_name"] = $author ? ($author["First_name"] . " " . $author["Last_name"]) : "N/D";
        } else {
            $libro["Author_name"] = "N/D";
        }

        if (isset($libro["Category_id"]) && isset($categoryMap[$libro["Category_id"]])) {
            $libro["Category_name"] = $categoryMap[$libro["Category_id"]];
        } else {
            $libro["Category_name"] = "N/D"; // O "Non categorizzato"
        }
        
        $templateParams["libri"][] = $libro;
    }
}

require 'template/base.php';
?>