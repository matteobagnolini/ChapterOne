<?php
require_once '../bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nomeCompleto = trim($_POST["nomeCompleto"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $indirizzo = trim($_POST["indirizzo"]);
    $telefono = trim($_POST["telefono"]);

    if (empty($nomeCompleto) || empty($email) || empty($password) || empty($indirizzo) || empty($telefono)) {
        die("Errore: Tutti i campi sono obbligatori.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Errore: Email non valida.");
    }

    $result = $dbh->registerUser($nomeCompleto, $email, $password, $indirizzo, $telefono);

    if ($result) {
        header("Location: ../login.php");
        exit;
    } else {
        die("Errore: Registrazione fallita. Riprova più tardi.");
    }
}
?>