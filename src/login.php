<?php
require_once 'bootstrap.php';

$templateParams["categorie"] = $dbh->getCategories();
if(!isUserLoggedIn() && !isAdminLoggedIn()) {
    if(isset($_POST["email"]) && isset($_POST["password"])){
        $login_result = $dbh->checkLogin($_POST["email"], $_POST["password"]);
        if($login_result === null || empty($login_result)) {
            $templateParams["errorelogin"] = "Errore! Controllare email o password!";
        }
        else {
            registerLoggedUser($login_result);
            
            if(isAdminLoggedIn()) {
                header("location: accountadmin.php");
                exit;
            } else {
                header("location: account.php");
                exit;
            }
        }
    }

    if(isAdminLoggedIn()){
        $templateParams["titolo"] = "ChapterOne - Admin";
        $templateParams["nome"] = "accountadmin.php";
    }
    else if(isUserLoggedIn()){
        $templateParams["titolo"] = "ChapterOne - Account";
        $templateParams["nome"] = "account.php";
        $templateParams["accountInfo"] = $dbh->getCustomerById($_SESSION["id"]);
    } else {
        $templateParams["titolo"] = "ChapterOne - Login";
        $templateParams["nome"] = "login-form.php";
    }
    
} else {
    if (isAdminLoggedIn()){
        header("location: accountadmin.php");
        exit;
    } else if (isUserLoggedIn()){
        header("location: account.php");
        exit;
    }
}

require 'template/base.php';
?>