<?php
// Utilizzo del metodo getBooks
$books = $dbh->getBooks(); // Ottieni i libri dal database

if (!empty($books)) {
    echo '<div class="container mt-4">';
    echo '<h2>Books</h2>';
    echo '<div class="row">';

    foreach ($books as $book) {
        // Converte le chiavi in minuscolo per evitare problemi di maiuscole/minuscole
        $book = array_change_key_case($book, CASE_LOWER);

        echo '<div class="col-md-4">';
        echo '<div class="card mb-4">';
        echo '<img src="' . htmlspecialchars($book["cover"] ?? 'default_cover.jpg') . '" class="card-img-top" alt="Book Cover">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . htmlspecialchars($book["title"] ?? 'Untitled') . '</h5>';
        echo '<ul>';
        echo '<li><strong>Description:</strong> ' . htmlspecialchars($book["description"] ?? 'No description available') . '</li>';
        echo '<li><strong>Price:</strong> $' . htmlspecialchars($book["price"] ?? '0.00') . '</li>';
        echo '<li><strong>Category ID:</strong> ' . htmlspecialchars($book["category_id"] ?? 'N/A') . '</li>';
        echo '<li><strong>Publisher ID:</strong> ' . htmlspecialchars($book["publisher_id"] ?? 'N/A') . '</li>';
        echo '<li><strong>Author ID:</strong> ' . htmlspecialchars($book["author_id"] ?? 'N/A') . '</li>';
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo '<p class="text-center mt-4">No books available.</p>';
}
?>