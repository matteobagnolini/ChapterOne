<?php
require_once 'bootstrap.php';

// Verifica che l'utente sia loggato
if (!isUserLoggedIn()) {
    header("location: login.php");
    exit;
}

// Verifica che siano stati inviati i dati del form
if (!isset($_POST['cart_id'])) {
    header("location: cart.php");
    exit;
}

// Recupera i dati del form
$cartId = $_POST['cart_id'];
$discountCode = isset($_POST['discount_code']) && !empty($_POST['discount_code']) ? $_POST['discount_code'] : null;

try {
    // Recupera l'ID del cliente dalla sessione
    $customerId =  $dbh->getAccountInfo($_SESSION["username"])['Id'];
   
    // Se è stato fornito un codice sconto, trova l'ID corrispondente
    $discountCodeId = null;
    if ($discountCode) {
        // Ottieni tutti i codici sconto
        $discountCodes = $dbh->getDiscountCodes();
        foreach ($discountCodes as $code) {
            if ($code['Code'] === $discountCode) {
                // Verifica se il codice è valido e attivo
                $currentDate = date('Y-m-d');
                if ($code['Active'] && $currentDate >= $code['Start_date'] && $currentDate <= $code['End_date']) {
                    $discountCodeId = $code['Id'];
                    break;
                }
            }
        }
        
        // Se il codice non è valido, imposta un messaggio di errore
        if (!$discountCodeId) {
            $_SESSION['error'] = "Codice sconto non valido o scaduto.";
            header("location: cart.php");
            exit;
        }
    }
    
    // Crea l'ordine
    $date = date('Y-m-d H:i:s'); // Data corrente
    
    // Recupera il carrello per calcolare il totale
    $cart = $dbh->getCartByCustomerId($customerId);
    $total = $cart['Subtotal'];
    
    // Inserisci l'ordine e ottieni l'ID
    $orderId = $dbh->insertOrder($date, $total, $customerId, $discountCodeId);
    
    if ($orderId) {
        // Imposta un messaggio di successo
        $_SESSION['success'] = "Ordine creato con successo! Il tuo ordine è in elaborazione.";
        
        // Reindirizza alla pagina dei dettagli dell'ordine
        header("location:  cart.php");
    } else {
        // Se la creazione dell'ordine fallisce, reindirizza al carrello con un messaggio di errore
        $_SESSION['error'] = "Errore durante la creazione dell'ordine. Riprova più tardi.";
        header("location: cart.php");
    }
    
} catch (Exception $e) {
    // Gestisci eventuali errori
    $_SESSION['error'] = "Si è verificato un errore: " . $e->getMessage();
    header("location: cart.php");
}
?>