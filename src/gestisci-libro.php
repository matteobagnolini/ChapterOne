<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Account Gestisci Libri";
$templateParams["nome"] = "gestisci-libripage.php";
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["autori"] = $dbh->getAuthors();
$templateParams["case_editrici"] = $dbh->getPublishers();

require 'template/base.php';

?>