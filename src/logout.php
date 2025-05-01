<?php
require_once 'bootstrap.php';

// Elimina tutte le variabili di sessione
$_SESSION = array();

// Se è stato impostato un cookie di sessione, eliminalo
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Distruggi la sessione
session_destroy();

// Reindirizza alla pagina di login o alla homepage
header("Location: index.php");
exit();
?>