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


$templateParams["titolo"] = "ChapterOne - Modifica Casa Editrice";
$templateParams["nome"] = "gestisci-case-editricipage.php";
$templateParams["categorie"] = $dbh->getCategories();

$editMode = false;
$publisher = [
    "Id" => "",
    "Name" => "",
    "Address" => ""
];


if (isset($_GET['id'])) {
    $editMode = true;
    $publisher = $dbh->getPublisherById($_GET['id']);
    if (!$publisher) {
        header("Location: lista-case-editrici.php");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $nome = trim($_POST['nome']);
    $indirizzo = trim($_POST['indirizzo']);

    if ($id) {
        $success = $dbh->updatePublisher($id, $nome, $indirizzo);
        if ($success) {
            header("Location: lista-case-editrici.php");
            exit;
        }
    }
}

$templateParams["publisher"] = $publisher;
$templateParams["editMode"] = $editMode;

require 'template/base.php';
?>