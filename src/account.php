<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Home";
$templateParams["nome"] = "accountpage.php";
$templateParams["categorie"] = $dbh->getCategories();

if(isUserLoggedIn()){
    $templateParams["accountInfo"] = $dbh->getAccountInfo($_SESSION["username"]); # TODO: Account info with logged user ID
}else{
    header("location: login.php");
} 

require 'template/base.php';


?>