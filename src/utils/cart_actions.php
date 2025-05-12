<?php
require_once '../bootstrap.php';

if (!isUserLoggedIn()) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $cartId = isset($_POST['cart_id']) ? (int)$_POST['cart_id'] : null;
        $bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; // Default a 1 se non specificato

        if (!$cartId) {
            $_SESSION['error'] = "ID carrello mancante.";
            header("Location: cart.php");
            exit;
        }

        switch ($action) {
            case 'remove':
                if ($bookId) {
                    $result = $dbh->deleteBookInCart($cartId, $bookId);
                    if ($result) {
                        $_SESSION['success'] = "Libro rimosso dal carrello con successo.";
                    } else {
                        $_SESSION['error'] = "Errore durante la rimozione del libro dal carrello.";
                    }
                } else {
                    $_SESSION['error'] = "ID libro mancante per la rimozione.";
                }
                break;

            case 'add':
                if ($bookId) {
                    // Prima controlla se il libro è già nel carrello per aggiornare la quantità
                    // o inserirlo se non presente. Questa logica potrebbe essere in insertBookInCart
                    // o gestita qui. Per semplicità, assumiamo che insertBookInCart gestisca i duplicati
                    // aggiornando la quantità o che si voglia sempre aggiungere la quantità specificata.
                    $result = $dbh->insertBookInCart($cartId, $bookId, $quantity);
                    if ($result) {
                        $_SESSION['success'] = "Libro aggiunto al carrello.";
                    } else {
                        $_SESSION['error'] = "Errore durante l'aggiunta del libro al carrello.";
                    }
                } else {
                    $_SESSION['error'] = "ID libro mancante per l'aggiunta.";
                }
                break;

            case 'update':
                if ($bookId && $quantity > 0) {
                    $result = $dbh->updateBookInCart($cartId, $bookId, $quantity);
                    if ($result) {
                        $_SESSION['success'] = "Quantità aggiornata nel carrello.";
                    } else {
                        $_SESSION['error'] = "Errore durante l'aggiornamento della quantità.";
                    }
                } elseif ($bookId && $quantity <= 0) {
                    // Se la quantità è 0 o meno, rimuovi il libro
                    $result = $dbh->deleteBookInCart($cartId, $bookId);
                     if ($result) {
                        $_SESSION['success'] = "Libro rimosso dal carrello (quantità zero).";
                    } else {
                        $_SESSION['error'] = "Errore durante la rimozione del libro dal carrello.";
                    }
                } else {
                    $_SESSION['error'] = "Dati mancanti o non validi per l'aggiornamento.";
                }
                break;

            default:
                $_SESSION['error'] = "Azione non valida.";
                break;
        }
    } else {
        $_SESSION['error'] = "Nessuna azione specificata.";
    }
} else {
    $_SESSION['error'] = "Metodo di richiesta non valido.";
}

header("Location: ../cart.php");
exit;
?>