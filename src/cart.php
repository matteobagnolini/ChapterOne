<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn()){
    header("location: login.php");
}

$templateParams["titolo"] = "ChapterOne - Cart";
$templateParams["nome"] = "cartpage.php";

$customer = $dbh->getCustomerByUsername($_SESSION["username"]);
$templateParams["carrello"] = $dbh->getCartByCustomerId($customer["Id"]); #
$templateParams["libricarrello"] = $dbh->getBooksInCart($templateParams["carrello"]["Id"]);
var_dump($templateParams["carrello"]);
var_dump($templateParams["libricarrello"]);
require 'template/base.php';

?>
