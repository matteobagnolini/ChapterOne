<?php
require_once __DIR__ . '/db/database.php';

$db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

try {
    // Eliminazione di tutti i dati esistenti
    $db->db->query("DELETE FROM BOOK_IN_CART");
    $db->db->query("DELETE FROM CART");
    $db->db->query("DELETE FROM REVIEW");
    $db->db->query("DELETE FROM ORDER_DETAIL");
    $db->db->query("DELETE FROM `ORDER`");
    $db->db->query("DELETE FROM DISCOUNT_CODE_USAGE");
    $db->db->query("DELETE FROM DISCOUNT_CODE");
    $db->db->query("DELETE FROM ORDER_NOTIFICATION");
    $db->db->query("DELETE FROM BEST_SELLER");
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
    $db->db->query("ALTER TABLE ORDER_NOTIFICATION AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE BEST_SELLER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE POST AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE BOOK AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CATEGORY AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE AUTHOR AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE PUBLISHER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE CUSTOMER AUTO_INCREMENT = 1");
    $db->db->query("ALTER TABLE ADMIN AUTO_INCREMENT = 1");
    
    // Popola la tabella CUSTOMER
    $customerId1 = $db->insertCustomer('Mario', 'Rossi', 'prova@example.com', 'password123', 'Via Roma 1', '1234567890');
    $customerId2 = $db->insertCustomer('Luigi', 'Verdi', 'luigi.verdi@example.com', 'password123', 'Via Milano 2', '0987654321');
    $customerId3 = $db->insertCustomer('Anna', 'Bianchi', 'anna.bianchi@example.com', 'password123', 'Via Napoli 3', '3456789012');

    // Popola la tabella ADMIN
    $adminId = $db->insertAdmin('Admin', 'User', 'admin@example.com', 'admin123');

    // Popola la tabella AUTHOR
    $authorId1 = $db->insertAuthor('Giovanni', 'Bianchi');
    $authorId2 = $db->insertAuthor('Anna', 'Neri');
    $authorId3 = $db->insertAuthor('Marco', 'Verdi');
    $authorId4 = $db->insertAuthor('Laura', 'Rossi');
    $authorId5 = $db->insertAuthor('Paolo', 'Gialli');

    // Popola la tabella CATEGORY
    $categoryId1 = $db->insertCategory('Romanzo');
    $categoryId2 = $db->insertCategory('Fantascienza');
    $categoryId3 = $db->insertCategory('Giallo');
    $categoryId4 = $db->insertCategory('Fantasy');
    $categoryId5 = $db->insertCategory('Biografia');

    // Popola la tabella PUBLISHER
    $publisherId1 = $db->insertPublisher('Mondadori');
    $publisherId2 = $db->insertPublisher('Feltrinelli');
    $publisherId3 = $db->insertPublisher('Einaudi');
    $publisherId4 = $db->insertPublisher('Rizzoli');

    // Popola la tabella BOOK con 9 libri
    $bookId1 = $db->insertBookWithExceptr('Il Grande Romanzo', 'Un romanzo epico che racconta la storia di una famiglia attraverso tre generazioni', 19.99, 'images/deepwork.jpg', 'exceptr/text.txt', $categoryId1, $publisherId1, $authorId1);
    $bookId2 = $db->insertBookWithExceptr('Viaggio nello Spazio', 'Un racconto di fantascienza ambientato nel 2150', 15.99, 'images/shining.jpg', 'exceptr/text.txt', $categoryId2, $publisherId2, $authorId2);
    $bookId3 = $db->insertBookWithExceptr('Il Mistero del Lago', 'Un giallo avvincente ambientato in un piccolo villaggio di montagna', 18.50, 'images/stevejobs.jpg', 'exceptr/text.txt', $categoryId3, $publisherId3, $authorId3);
    $bookId4 = $db->insertBookWithExceptr('La Terra di Mezzo', 'Un fantasy epico con draghi, elfi e antiche magie', 22.00, 'images/shining.jpg', '', $categoryId4, $publisherId1, $authorId4);
    $bookId5 = $db->insertBookWithExceptr('Vita di Einstein', 'La biografia del famoso scienziato', 24.99, 'images/5.jpg', 'exceptr/text.txt', $categoryId5, $publisherId4, $authorId5);
    $bookId6 = $db->insertBookWithExceptr('Il Ritorno', 'Sequel del Grande Romanzo, continua la saga familiare', 21.50, 'images/stevejobs.jpg', 'exceptr/text.txt', $categoryId1, $publisherId1, $authorId1);
    $bookId7 = $db->insertBookWithExceptr('Mondi Paralleli', 'Un viaggio tra dimensioni alternative', 17.99, 'images/deepwork.jpg', 'exceptr/text.txt', $categoryId2, $publisherId2, $authorId2);
    $bookId8 = $db->insertBookWithExceptr('Il Codice Segreto', 'Un mistero da risolvere in una corsa contro il tempo', 16.50, 'images/8.jpg', 'exceptr/text.txt', $categoryId3, $publisherId3, $authorId3);
    $bookId9 = $db->insertBookWithExceptr('Le Cronache del Regno', 'Una saga fantasy di avventura e magia', 23.99, 'images/deepwork.jpg', 'exceptr/text.txt', $categoryId4, $publisherId4, $authorId4);

    // Popola la tabella CART (i carrelli vengono creati automaticamente dal trigger after_insert_customer)
    $cart1 = $db->getCartByCustomerId($customerId1);
    $cart2 = $db->getCartByCustomerId($customerId2);
    $cart3 = $db->getCartByCustomerId($customerId3);

    // Popola la tabella BOOK_IN_CART
    $db->insertBookInCart($cart1['Id'], $bookId1, 1);
    $db->insertBookInCart($cart1['Id'], $bookId4, 2);
    $db->insertBookInCart($cart2['Id'], $bookId2, 1);
    $db->insertBookInCart($cart2['Id'], $bookId5, 1);
    $db->insertBookInCart($cart3['Id'], $bookId3, 3);

    // Popola la tabella DISCOUNT_CODE
    $discountCodeId1 = $db->insertDiscountCode('WELCOME10', 'percentage', 10.00, '2024-01-01', '2025-12-31', false, true);
    $discountCodeId2 = $db->insertDiscountCode('SUMMER25', 'percentage', 25.00, '2025-06-01', '2025-08-31', false, true);
    $discountCodeId3 = $db->insertDiscountCode('FIXED15', 'fixed', 15.00, '2024-01-01', '2025-12-31', false, true);
    
    // Crea alcuni ordini
    $orderId1 = $db->insertOrder('2025-03-15 10:30:00', 19.99, $customerId1, null);
    $orderId2 = $db->insertOrder('2025-03-20 14:45:00', 12.00, $customerId2, $discountCodeId1);
    $orderId3 = $db->insertOrder('2025-03-25 09:15:00', 55.50, $customerId3, null);
    
    
    // Popola la tabella REVIEW
    $db->insertReview('Libro fantastico, lo consiglio vivamente!', 5, $bookId1, $customerId1);
    $db->insertReview('Una buona lettura ma la trama è prevedibile.', 3, $bookId2, $customerId2);
    $db->insertReview('Mi ha tenuto sveglio tutta la notte. Eccellente!', 5, $bookId3, $customerId3);
    
    // Popola la tabella POST
    $db->insertPost('Nuovo romanzo di Giovanni Bianchi disponibile ora!', '2025-03-01 12:00:00', $authorId1, $bookId1);
    $db->insertPost('Incontro con l\'autore Anna Neri presso la libreria centrale', '2025-03-10 12:00:00', $authorId2, $bookId2);
    
    // Popola la tabella ORDER_NOTIFICATION
    $db->insertOrderNotification($orderId1, 'Il tuo ordine è stato spedito', 'sent');
    $db->insertOrderNotification($orderId2, 'Il tuo ordine è in elaborazione', 'pending');
    $db->insertOrderNotification($orderId3, 'Il tuo ordine è stato consegnato', 'arrived');


    // Popola la tabella BOOK_IN_CART
    $db->insertBookInCart($cart1['Id'], $bookId1, 1);
    $db->insertBookInCart($cart1['Id'], $bookId4, 2);
    $db->insertBookInCart($cart2['Id'], $bookId2, 1);
    $db->insertBookInCart($cart2['Id'], $bookId5, 1);
    $db->insertBookInCart($cart3['Id'], $bookId3, 3);

    echo "Database popolato con successo con 9 libri e relativi dati!";
} catch (Exception $e) {
    echo "Errore durante il popolamento del database: " . $e->getMessage();
}
?>