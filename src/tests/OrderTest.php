<?php

require_once __DIR__ . '/BaseTest.php';

class OrderTest extends BaseTest {

    public function testOrderProcess(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $book2Id = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 2); // 2 copie di Book 1
        $this->db->insertBookInCart($cartId, $book2Id, 1); // 1 copia di Book 2

        // Esegui l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 35.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Aggiorna un libro
        $this->db->updateBook($book1Id, 'Book 1 Updated', 'Updated Description', 12.00, 'cover1_updated.jpg', null, null, null);

        // Verifica i dettagli dell'ordine
        $orderDetails = $this->db->getOrderDetails($orderId);
        $this->assertCount(2, $orderDetails);

        $this->assertEquals($book1Id, $orderDetails[0]['Book_id']);
        $this->assertEquals(2, $orderDetails[0]['Quantity']);
        $this->assertEquals(20.00, $orderDetails[0]['Subtotal']); // Il prezzo originale rimane invariato nell'ordine

        $this->assertEquals($book2Id, $orderDetails[1]['Book_id']);
        $this->assertEquals(1, $orderDetails[1]['Quantity']);
        $this->assertEquals(15.00, $orderDetails[1]['Subtotal']);
    }

    public function testOrderWithEmptyCartFails(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Prova a eseguire un ordine con un carrello vuoto
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Il carrello è vuoto, impossibile completare l'ordine.");

        $this->db->insertOrder('2025-04-09 12:00:00', 0.00, $customerId, null);
    }

    public function testOrderTotalMatchesCartTotal(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $book2Id = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 2); // 2 copie di Book 1
        $this->db->insertBookInCart($cartId, $book2Id, 1); // 1 copia di Book 2

        // Esegui l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 35.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Verifica che il totale dell'ordine corrisponda al totale del carrello
        $order = $this->db->getOrderById($orderId);
        $this->assertEquals(35.00, $order['Total']);
    }

    public function testOrderDetailsPriceRemainsUnchangedAfterBookUpdate(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('Jane', 'Doe', 'jane.doe@example.com', 'password123', '456 Elm St', '0987654321');

        // Aggiungi un libro
        $bookId = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);

        // Aggiungi il libro al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $bookId, 1);

        // Esegui l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 10.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Aggiorna il libro
        $this->db->updateBook($bookId, 'Book 1 Updated', 'Updated Description', 12.00, 'cover1_updated.jpg', null, null, null);

        // Verifica che il prezzo nei dettagli dell'ordine rimanga invariato
        $orderDetails = $this->db->getOrderDetails($orderId);
        $this->assertEquals(10.00, $orderDetails[0]['Subtotal']);
    }

    public function testOrderDetailsSumMatchesOrderTotal(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $book2Id = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 2); // 2 copie di Book 1
        $this->db->insertBookInCart($cartId, $book2Id, 1); // 1 copia di Book 2

        // Esegui l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 35.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Verifica che la somma dei dettagli dell'ordine corrisponda al totale dell'ordine
        $orderDetails = $this->db->getOrderDetails($orderId);
        $totalFromDetails = array_reduce($orderDetails, function ($carry, $item) {
            return $carry + $item['Subtotal'];
        }, 0);

        $order = $this->db->getOrderById($orderId);
        $this->assertEquals($order['Total'], $totalFromDetails);
    }

    public function testOrderStatusFlow(): void {
        $this->tearDown();

        // Creazione del cliente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Creazione di un libro
        $bookId = $this->db->insertBook('Book Title', 'Book Description', 20.00, 'cover.jpg', null, null, null);
        $this->assertIsInt($bookId);

        // Aggiunta del libro al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $bookId, 1);

        // Creazione dell'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 20.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Verifica dello stato iniziale dell'ordine
        $order = $this->db->getOrderById($orderId);
        $this->assertEquals('pending', $order['Status'], "Lo stato iniziale dell'ordine dovrebbe essere 'pending'.");

        // Aggiorna lo stato a 'sent'
        $this->db->updateOrderStatus($orderId, 'sent');
        $order = $this->db->getOrderById($orderId);
        $this->assertEquals('sent', $order['Status'], "Lo stato dell'ordine dovrebbe essere aggiornato a 'sent'.");

        // Aggiorna lo stato a 'failed'
        $this->db->updateOrderStatus($orderId, 'arrived');
        $order = $this->db->getOrderById($orderId);
        $this->assertEquals('arrived', $order['Status'], "Lo stato dell'ordine dovrebbe essere aggiornato a 'failed'.");
    }
}