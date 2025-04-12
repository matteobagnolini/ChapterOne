<?php
require_once __DIR__ . '/db/database.php';

$db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

try {
    // Popola la tabella CUSTOMER
    $customerId1 = $db->insertCustomer('Mario', 'Rossi', 'mario.rossi@example.com', 'password123', 'Via Roma 1', '1234567890');
    $customerId2 = $db->insertCustomer('Luigi', 'Verdi', 'luigi.verdi@example.com', 'password123', 'Via Milano 2', '0987654321');

    // Popola la tabella AUTHOR
    $authorId1 = $db->insertAuthor('Giovanni', 'Bianchi');
    $authorId2 = $db->insertAuthor('Anna', 'Neri');

    // Popola la tabella CATEGORY
    $categoryId1 = $db->insertCategory('Romanzo');
    $categoryId2 = $db->insertCategory('Fantascienza');

    // Popola la tabella PUBLISHER
    $publisherId1 = $db->insertPublisher('Mondadori');
    $publisherId2 = $db->insertPublisher('Feltrinelli');

    // Popola la tabella BOOK
    $bookId1 = $db->insertBook('Il Grande Romanzo', 'Un romanzo epico', 19.99, 'cover1.jpg', $categoryId1, $publisherId1, $authorId1);
    $bookId2 = $db->insertBook('Viaggio nello Spazio', 'Un racconto di fantascienza', 15.99, 'cover2.jpg', $categoryId2, $publisherId2, $authorId2);

    // Popola la tabella CART
    $cart1 = $db->getCartByCustomerId($customerId1);
    $cart2 = $db->getCartByCustomerId($customerId2);

    // Popola la tabella BOOK_IN_CART
    $db->insertBookInCart($cart1['Id'], $bookId1, 1);
    $db->insertBookInCart($cart2['Id'], $bookId2, 2);



    echo "Database popolato con successo!";
} catch (Exception $e) {
    echo "Errore durante il popolamento del database: " . $e->getMessage();
}
?>