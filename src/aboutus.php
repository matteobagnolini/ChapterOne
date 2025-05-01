<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - About Us";
$templateParams["nome"] = "aboutuspage.php";
$templateParams["categorie"] = $dbh->getCategories();
// $templateParams["articolicasuali"] = $dbh->getRandomPosts(2);
//Home Template
// $templateParams["articoli"] = $dbh->getPosts(2);

require 'template/base.php';

?>