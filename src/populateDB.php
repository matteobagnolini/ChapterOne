<?php
require_once __DIR__ . '/db/database.php';

$db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

try {

    $db->db->query("DELETE FROM BOOK_IN_CART");
    $db->db->query("DELETE FROM CART");
    $db->db->query("DELETE FROM REVIEW");
    $db->db->query("DELETE FROM `ORDER`");
    $db->db->query("DELETE FROM ORDER_DETAIL");
    $db->db->query("DELETE FROM DISCOUNT_CODE_USAGE");
    $db->db->query("DELETE FROM DISCOUNT_CODE");
    $db->db->query("DELETE FROM POST");
    $db->db->query("DELETE FROM BOOK");
    $db->db->query("DELETE FROM CATEGORY");
    $db->db->query("DELETE FROM AUTHOR");
    $db->db->query("DELETE FROM PUBLISHER");
    $db->db->query("DELETE FROM CUSTOMER");
    $db->db->query("DELETE FROM ADMIN");

    // Resetta l'AUTO_INCREMENT per ogni tabella
    $db->db->query("ALTER TABLE BOOK_IN_CART AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CART AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE REVIEW AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE `ORDER` AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE ORDER_DETAIL AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE DISCOUNT_CODE_USAGE AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE DISCOUNT_CODE AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE POST AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE BOOK AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CATEGORY AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE AUTHOR AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE PUBLISHER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CUSTOMER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE ADMIN AUTO_INCREMENT = 1");
    


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
    $bookId1 = $db->insertBook('Il Grande Romanzo', 'Un romanzo epico', 19.99, '/resources/shining.jpg', $categoryId1, $publisherId1, $authorId1);
    $bookId2 = $db->insertBook('Viaggio nello Spazio', 'Un racconto di fantascienza', 15.99, '/resources/deepwork.jpg', $categoryId2, $publisherId2, $authorId2);

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