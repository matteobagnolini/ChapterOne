<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Home";
$templateParams["nome"] = "homepage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["novità"] = $dbh->getNewBooks(10);
$templateParams["bestsellers"] = $dbh->getbestSellers(10);

//var_dump($templateParams["bestsellers"]);
require 'template/base.php';

?>