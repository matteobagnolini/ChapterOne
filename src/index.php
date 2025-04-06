<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Home";

// $templateParams["nome"] = "lista-articoli.php";
// $templateParams["categorie"] = $dbh->getCategories();
// $templateParams["articolicasuali"] = $dbh->getRandomPosts(2);
//Home Template
// $templateParams["articoli"] = $dbh->getPosts(2);

require 'template/base.php';

?>