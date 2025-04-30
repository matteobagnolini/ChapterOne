<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Home";
$templateParams["nome"] = "homepage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["bestsellers"] = $dbh->getBooks();
//$templateParams["novità"] = $dbh->getNewBooks(10);
$templateParams["bestsellers"] = $dbh->getbestSellers(10);

require 'template/base.php';

?>