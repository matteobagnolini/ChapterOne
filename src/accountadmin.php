<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Account Admin";
$templateParams["nome"] = "accountadminpage.php";
$templateParams["categorie"] = $dbh->getCategories();

# Add upload of new books from this page

require 'template/base.php';

?>