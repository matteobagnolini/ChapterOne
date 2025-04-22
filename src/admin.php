<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Admin";
$templateParams["nome"] = "adminpage.php";
// $templateParams["categorie"] = $dbh->getCategories();
// $templateParams["articolicasuali"] = $dbh->getRandomPosts(2);
//Home Template
// $templateParams["articoli"] = $dbh->getPosts(2);

require 'template/base.php';

?>