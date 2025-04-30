<?php
require_once 'bootstrap.php';

if(!isAdminLoggedIn()){
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Account Gestisci autori";
$templateParams["nome"] = "gestisci-autoripage.php";
$templateParams["categorie"] = $dbh->getCategories();


$idautore  = -1;
if(isset($_GET["id"])){
    $idautore = $_GET["id"];
}

$templateParams["autore"] = $dbh->getAuthorById($idautore);

require 'template/base.php';

?>