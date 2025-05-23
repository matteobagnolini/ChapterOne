<?php
require_once '../bootstrap.php';


if (!isUserLoggedIn()) {
    header("Location: ../login.php");
    exit;
}

$redirectTo = '../cart.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $user = $dbh->getCustomerByUsername($_SESSION["username"]);
        
        $cartDetails = $dbh->getCartByCustomerId($user['Id']);
        $cartId = $cartDetails['Id'];
     
        $bookId = isset($_POST['book_id']) ? (int)$_POST['book_id'] : null;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1; 

        switch ($action) {
            case 'add':
                if ($bookId && $cartId) {
                    $bookDetails = $dbh->getBookById($bookId);
                    if (!$bookDetails) {
                        $_SESSION['error'] = "Libro non trovato.";
                        header("Location: " . ($bookId ? "../libro.php?id=" . $bookId : $redirectTo));
                        exit;
                    }

                    $isPurchasable = $bookDetails['Product_count'] > 0; 

                    if (!$isPurchasable) {
                        $_SESSION['error'] = "Il libro '{$bookDetails['Title']}' non è attualmente disponibile per l'acquisto.";
                        $redirectTo = "../libro.php?id=" . $bookId;
                    } else {
                        $books = $dbh->getBooksInCart($cartId);
                        $existingItem = null;
                        foreach ($books as $item) {
                            if ($item['Book_id'] == $bookId) {
                                $existingItem = $item;
                                break;
                            }
                        }

                        $requestedTotalQuantity = $existingItem ? $existingItem['Quantity'] + $quantity : $quantity;

                        if ($requestedTotalQuantity > $bookDetails['Product_count']) {
                            $_SESSION['error'] = "Quantità richiesta per '{$bookDetails['Title']}' ({$requestedTotalQuantity}) supera la disponibilità in magazzino ({$bookDetails['Product_count']}).";
                            if ($existingItem) {
                                 $_SESSION['error'] .= " Hai già {$existingItem['Quantity']} unità nel carrello.";
                            }
                            $redirectTo = "../libro.php?id=" . $bookId;
                        } else {
                            if ($existingItem) {
                                $result = $dbh->updateBookInCart($cartId, $bookId, $requestedTotalQuantity);
                                $_SESSION['success'] = $result ? "Quantità aggiornata nel carrello." : "Errore durante l'aggiornamento della quantità.";
                                if(!$result && !isset($_SESSION['error'])) $_SESSION['error'] = "Errore generico aggiornamento quantità.";
                            } else {
                                $result = $dbh->insertBookInCart($cartId, $bookId, $quantity);
                                $_SESSION['success'] = $result ? "Libro aggiunto al carrello." : "Errore durante l'aggiunta del libro.";
                                 if(!$result && !isset($_SESSION['error'])) $_SESSION['error'] = "Errore generico aggiunta libro.";
                            }
                        }
                    }
                } else {
                    $_SESSION['error'] = "ID libro o carrello mancante per l'aggiunta.";
                }
                break;

            case 'update':
                if ($bookId && $cartId) {
                    if ($quantity < 0) { 
                        $_SESSION['error'] = "La quantità non può essere negativa.";
                    } elseif ($quantity == 0) { // Se la quantità è 0, rimuovi il libro
                        $result = $dbh->deleteBookInCart($cartId, $bookId);
                        if ($result) {
                            $_SESSION['success'] = "Libro rimosso dal carrello (quantità zero).";
                        } else {
                            $_SESSION['error'] = "Errore durante la rimozione del libro dal carrello.";
                        }
                    } else if ($quantity > 0) { 
                        $bookDetails = $dbh->getBookById($bookId);
                        if (!$bookDetails) {
                            $_SESSION['error'] = "Libro non trovato per l'aggiornamento.";
                        } elseif ($quantity > $bookDetails['Product_count']) {
                            $_SESSION['error'] = "La quantità richiesta per '{$bookDetails['Title']}' ({$quantity}) supera la disponibilità in magazzino ({$bookDetails['Product_count']}).";
                        } else {
                            $result = $dbh->updateBookInCart($cartId, $bookId, $quantity);
                            if ($result) {
                                $_SESSION['success'] = "Quantità aggiornata nel carrello.";
                            } else {
                                $_SESSION['error'] = "Errore durante l'aggiornamento della quantità.";
                            }
                        }
                    }
                } else {
                    $_SESSION['error'] = "Dati mancanti o non validi per l'aggiornamento (ID libro/carrello).";
                }
                break;

            case 'remove':
                if ($bookId && $cartId) {
                    $result = $dbh->deleteBookInCart($cartId, $bookId);
                    if ($result) {
                        $_SESSION['success'] = "Libro rimosso dal carrello con successo.";
                    } else {
                        $_SESSION['error'] = "Errore durante la rimozione del libro dal carrello.";
                    }
                } else {
                    $_SESSION['error'] = "ID libro o carrello mancante per la rimozione.";
                }
                break;

            default:
                $_SESSION['error'] = "Azione non valida.";
                break;
        }
    } else {
        $_SESSION['error'] = "Nessuna azione specificata.";
    }
}

header("Location: " . $redirectTo);
exit;
?>