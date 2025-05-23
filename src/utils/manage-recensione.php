<?php
require_once '../bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["action"]) && $_POST["action"] === "delete"){
       $reviewId = intval($_POST["review_id"]);
       $bookId = intval($_POST["book_id"]); 
       $customerId = $_SESSION["id"]; 
       
       if (empty($reviewId) || empty($bookId)) {
            die("Errore: Dati mancanti per l'eliminazione della recensione.");
        }
        try {
            $recensione = $dbh->getReviewById($reviewId);

            if ($recensione && $recensione["Customer_id"] == $customerId) {
                $dbh->deleteReview($reviewId);
                header("Location: ../book.php?id=$bookId");
                exit;
            } else {
                die("Errore: Non sei autorizzato a eliminare questa recensione.");
            }
        } catch (Exception $e) {
            die("Errore: " . $e->getMessage());
        }
    }

    if (isset($_POST["action"]) && $_POST["action"] === "add") {
        $bookId = intval($_POST["book_id"]);
        $rating = intval($_POST["rating"]);
        $reviewText = trim($_POST["review_text"]);
        $customerId = $_SESSION["id"];

        if (empty($bookId) || empty($rating) || empty($reviewText)) {
            die("Errore: Tutti i campi sono obbligatori.");
        }
        try {
            $dbh->addReview($reviewText, $rating, $bookId, $customerId);
            header("Location: ../book.php?id=$bookId");
            exit;
        } catch (Exception $e) {
            die("Errore: " . $e->getMessage());
        }
    }
}
?>