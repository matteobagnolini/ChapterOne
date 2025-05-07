<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - About Us";
$templateParams["nome"] = "aboutuspage.php";
$templateParams["categorie"] = $dbh->getCategories();

require 'template/base.php';

?>