<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Home";
$templateParams["nome"] = "accountpage.php";
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["accountInfo"] = $dbh->getAccountInfo($_SESSION["username"]); # TODO: Account info with logged user ID
# Add upload of new values from form


require 'template/base.php';


?>