<?php
require_once 'bootstrap.php';

$templateParams["titolo"] = "ChapterOne - Account";
$templateParams["nome"] = "accountpage.php";
$templateParams["categorie"] = $dbh->getCategories();

if(isUserLoggedIn()){
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "update_account") {
        $nomeCompleto = trim($_POST["name"]);
        $email = trim($_POST["email"]);
        $password = trim($_POST["password"]);
        $indirizzo = trim($_POST["address"]);
        $telefono = trim($_POST["phone"]);

        $nomeArray = explode(' ', $nomeCompleto, 2);
        $firstName = $nomeArray[0];
        $lastName = isset($nomeArray[1]) ? $nomeArray[1] : '';

        $userId = $_SESSION["id"];

        if (empty($password)) {
            $currentUser = $dbh->getCustomerById($userId);
            $hashedPassword = $currentUser["Password"];
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        }
        error_log("Password ricevuta: " . $password);
        $result = $dbh->updateCustomer($userId, $firstName, $lastName, $email, $hashedPassword, $indirizzo, $telefono);

        if ($result) {
            $_SESSION['update_message'] = "Dati aggiornati con successo!";
            $_SESSION['update_message_type'] = "success";
            $_SESSION["username"] = $email;
        } else {
            $_SESSION['update_message'] = "Errore durante l'aggiornamento dei dati.";
            $_SESSION['update_message_type'] = "danger";
        }
        header("Location: account.php");
        exit;
    }

    $templateParams["accountInfo"] = $dbh->getAccountInfo($_SESSION["username"]);

} else {
    header("location: login.php");
} 

require 'template/base.php';

?>