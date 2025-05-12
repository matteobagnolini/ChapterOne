<?php
require_once 'bootstrap.php';

// Solo admin può accedere
if (!isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Gestione Ordini";
$templateParams["nome"] = "lista-ordinipage.php"; // Nome del file template
$templateParams["categorie"] = $dbh->getCategories(); // Per la navbar, se necessario

// Recupera tutti gli ordini. Assumendo che il metodo getOrders() esista e restituisca tutti gli ordini
// Potresti voler aggiungere paginazione o filtri in futuro
$templateParams["ordini"] = $dbh->getOrders(); 

// Potrebbe essere utile recuperare anche i nomi dei clienti per visualizzarli
if (!empty($templateParams["ordini"])) {
    foreach ($templateParams["ordini"] as $key => $ordine) {
        $customer = $dbh->getCustomerById($ordine["Customer_id"]);
        $templateParams["ordini"][$key]["Customer_Name"] = $customer ? ($customer["First_name"] . " " . $customer["Last_name"]) : "Sconosciuto";
        $templateParams["ordini"][$key]["Customer_Email"] = $customer ? $customer["Email"] : "N/D";
    }
}

require 'template/base.php';
?>