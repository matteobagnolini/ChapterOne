<?php
require_once 'bootstrap.php';

if (!isAdminLoggedIn()) {
    if (isUserLoggedIn()) {
        header("location: index.php"); 
    } else {
        header("location: login.php"); 
    }
    exit; 
}

$templateParams["titolo"] = "ChapterOne - Crea Categoria";
$templateParams["nome"] = "crea-categoriepage.php";
$templateParams["categorie"] = $dbh->getCategories();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $nome = trim($_POST['nome']);

    $success = $dbh->insertCategory($nome);
    if ($success) {
        header("Location: lista-categorie.php");
        exit;
    }
}

require 'template/base.php';
?>