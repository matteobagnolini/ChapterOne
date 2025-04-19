<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Home";
$templateParams["nome"] = "homepage.php";
// $templateParams["categorie"] = $dbh->getCategories();
// $templateParams["articolicasuali"] = $dbh->getRandomPosts(2);
//Home Template
// $templateParams["articoli"] = $dbh->getPosts(2);

$templateParams["bestsellers"] = $dbh->getbestSellers(10);
require 'template/base.php';

?>