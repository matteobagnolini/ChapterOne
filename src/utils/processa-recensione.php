<?php
require_once '../bootstrap.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
    } catch (Exception $e) {
        die("Errore: " . $e->getMessage());
    }
}
?>