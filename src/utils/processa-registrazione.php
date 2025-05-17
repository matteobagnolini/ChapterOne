<?php
require_once '../bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nomeCompleto = trim($_POST["nomeCompleto"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $indirizzo = trim($_POST["indirizzo"]);
    $telefono = trim($_POST["telefono"]);

  if (empty($nomeCompleto) || empty($email) || empty($password) || empty($indirizzo) || empty($telefono)) {
        $_SESSION['error_message'] = "Errore: Tutti i campi sono obbligatori.";
        header("Location: ../registrazione.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Errore: Email non valida.";
        header("Location: ../registrazione.php");
        exit;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $nomeArray = explode(' ', $nomeCompleto, 2);
    $firstName = $nomeArray[0];
    $lastName = isset($nomeArray[1]) ? $nomeArray[1] : '';

    $result = $dbh->insertCustomer($firstName, $lastName, $email, $hashedPassword, $indirizzo, $telefono);

    if ($result) {
        header("Location: ../login.php");
        exit;
    } else {
        $_SESSION['error_message'] = "Errore: Registrazione fallita. L'email potrebbe essere già in uso o si è verificato un problema.";
        header("Location: ../registrazione.php");
    }
} else 
{
    $_SESSION['error_message'] = "Errore: Metodo di richiesta non valido.";
    header("Location: ../registrazione.php");
}
?>