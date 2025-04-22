<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn()){
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Cart";
$templateParams["nome"] = "cartpage.php";
$templateParams["categorie"] = $dbh->getCategories();

$templateParams["cartItems"] = $dbh->getCartItems($_SESSION["username"]);  # Cart items from current user ID
$templateParams["cartPrice"] = $dbh->getCartPrice();
$templateParams["cartNumber"] = $dbh->getCartItemsNumber();

require 'template/base.php';

?>
