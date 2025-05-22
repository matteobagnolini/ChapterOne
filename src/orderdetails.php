<?php
require_once 'bootstrap.php';

if (!isUserLoggedIn() && !isAdminLoggedIn()) {
    header("location: login.php");
    exit;
}

$templateParams["titolo"] = "ChapterOne - Dettagli Ordine";
$templateParams["nome"] = "Orderdetailspage.php";
$templateParams["categorie"] = $dbh->getCategories();
$templateParams["ordineInfo"] = null;     
$templateParams["dettagliordine"] = [];
$templateParams["libriOrdine"] = [];
$templateParams["totaleArticoli"] = 0;   
$templateParams["isAdminView"] = isAdminLoggedIn();

if (isset($_GET["id_order"])) {
    $orderId = $_GET["id_order"];
    $orderInfo = $dbh->getOrderById($orderId);


    $templateParams["ordineInfo"] = $orderInfo;

    $orderDetails = $dbh->getOrderDetailsByOrderId($orderId);

    if ($orderDetails) {
        $templateParams["dettagliordine"] = $orderDetails;
        $booksInOrder = [];
        $totalItems = 0;

        foreach ($orderDetails as $detail) {
            $totalItems += $detail['Quantity'];


            if (isset($detail['Book_id'])) {
                $bookId = $detail['Book_id'];
                $bookInfo = $dbh->getBookById($bookId);

                if ($bookInfo) {

                    $authorId = $bookInfo['Author_id'] ?? null;
                    if ($authorId) {
                        $authorInfo = $dbh->getAuthorById($authorId);
                        if ($authorInfo) {

                            $bookInfo['Author_First_name'] = $authorInfo['First_name'];
                            $bookInfo['Author_Last_name'] = $authorInfo['Last_name'];
                        } else {
 
                            $bookInfo['Author_First_name'] = 'Autore';
                            $bookInfo['Author_Last_name'] = 'Sconosciuto';
                            error_log("Autore con ID $authorId non trovato per il libro $bookId");
                        }
                    } else {
     
                        $bookInfo['Author_First_name'] = 'Autore';
                        $bookInfo['Author_Last_name'] = 'Non specificato';
                        error_log("ID autore non trovato per il libro $bookId");
                    }
            
                    $booksInOrder[$bookId] = $bookInfo;

                } else {
                    error_log("Libro con ID $bookId non trovato per l'ordine $orderId");
                }
            }
        }
        $templateParams["libriOrdine"] = $booksInOrder;
        $templateParams["totaleArticoli"] = $totalItems;
    }


} else {
    header("location: orders.php");
    exit;
}

require 'template/base.php';

?>