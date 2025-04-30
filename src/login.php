<?php
require_once 'bootstrap.php';

if(!isUserLoggedIn() && !isAdminLoggedIn()) {
    if(isset($_POST["username"]) && isset($_POST["password"])){
        $login_result = $dbh->checkLogin($_POST["username"], $_POST["password"]);
        if(count($login_result)==0){
            //Login fallito
            $templateParams["errorelogin"] = "Errore! Controllare username o password!";
        }
        else{
         
            registerLoggedUser($login_result);
        }
    }

    if(isAdminLoggedIn()){
        $templateParams["titolo"] = "Blog TW - Admin";
        $templateParams["nome"] = "accountadmin.php";
        # TODO: add info about admin page e.g. links to manage books, authors and publishers
    }
    else if(isUserLoggedIn()){
        $templateParams["titolo"] = "ChapterOne - Home";
        $templateParams["nome"] = "account.php";
        $templateParams["categorie"] = $dbh->getCategories();
        $templateParams["novità"] = $dbh->getNewBooks(10);
        $templateParams["bestsellers"] = $dbh->getbestSellers(10);
    } else{
        $templateParams["titolo"] = "ChapterOne - Login";
        $templateParams["nome"] = "login-form.php";
    }
    
} else{
    if (isAdminLoggedIn()){
        header("location: accountadmin.php");
    } else if (isUserLoggedIn()){
            header("location: account.php");
    }
  
}

require 'template/base.php';
?>