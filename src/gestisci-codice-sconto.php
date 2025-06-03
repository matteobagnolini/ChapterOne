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

$templateParams["titolo"] = "ChapterOne - Gestisci Codice Sconto";
$templateParams["nome"] = "gestisci-codice-scontopage.php";
$templateParams["categorie"] = $dbh->getCategories();

$isEditing = false;
$discountCodeId = null;

if (isset($_GET['id'])) {
    $discountCodeId = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($discountCodeId && $discountCodeId > 0) {
        $isEditing = true;
        $existingCode = $dbh->getDiscountCodeById($discountCodeId);
        if ($existingCode) {
            $templateParams["codicesconto"] = $existingCode;
        } else {
            header("Location: lista-codicisconto.php");
            exit;
        }
    } else {
        header("Location: lista-codicisconto.php");
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

    $code = trim($_POST['code']);
    $type = trim($_POST['type']);
    $value = filter_var(trim($_POST['value']), FILTER_VALIDATE_FLOAT);
    $startDate = trim($_POST['start_date']);
    $endDate = trim($_POST['end_date']);
    $singleUse = isset($_POST['single_use']) ? 1 : 0; 
    $active = isset($_POST['active']) ? 1 : 0;     
    $idToUpdate = isset($_POST['id']) ? filter_var($_POST['id'], FILTER_VALIDATE_INT) : null;

    if ($idToUpdate && $isEditing) { 
        $success = $dbh->updateDiscountCode($idToUpdate, $code, $type, $value, $startDate, $endDate, $singleUse, $active);
        if ($success) {
            header("Location: lista-codicisconto.php");
            exit;
        } 
    } else { 
        $success = $dbh->insertDiscountCode($code, $type, $value, $startDate, $endDate, $singleUse, $active);
        if ($success) {
            header("Location: lista-codicisconto.php");
            exit;
        }
    }

}


require 'template/base.php';
?>