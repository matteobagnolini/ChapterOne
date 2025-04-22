<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Account Gestisci autori";
$templateParams["nome"] = "gestisci-case-editricipage.php";
$templateParams["categorie"] = $dbh->getCategories();
// $templateParams["articolicasuali"] = $dbh->getRandomPosts(2);
//Home Template
// $templateParams["articoli"] = $dbh->getPosts(2);

require 'template/base.php';

?>