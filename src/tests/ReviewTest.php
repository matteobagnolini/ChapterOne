<?php

require_once __DIR__ . '/BaseTest.php';

class ReviewTest extends BaseTest {

    public function testAddReviewAfterPurchase(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Aggiungi un libro
        $bookId = $this->db->insertBook('Book Title', 'Book Description', 20.00, 'cover.jpg', null, null, null);

        // Aggiungi il libro al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $bookId, 1);

        // Esegui l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 20.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Aggiungi una recensione per il libro acquistato
        $reviewId = $this->db->insertReview('Great book!', 5, $bookId, $customerId);
        $this->assertIsInt($reviewId);

        // Verifica che la recensione sia stata aggiunta correttamente
        $review = $this->db->getReviewById($reviewId);
        $this->assertEquals('Great book!', $review['Text']);
        $this->assertEquals(5, $review['Rating']);
        $this->assertEquals($bookId, $review['Book_id']);
        $this->assertEquals($customerId, $review['Customer_id']);
    }

    public function testAddReviewWithoutPurchaseFails(): void {
        $this->tearDown();

        // Aggiungi un utente
        $customerId = $this->db->insertCustomer('Jane', 'Doe', 'jane.doe@example.com', 'password123', '456 Elm St', '0987654321');

        // Aggiungi un libro
        $bookId = $this->db->insertBook('Another Book', 'Another Description', 15.00, 'cover2.jpg', null, null, null);

        // Prova ad aggiungere una recensione senza aver acquistato il libro
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Il cliente non ha acquistato questo libro e non può lasciare una recensione.");
        $this->db->insertReview('Not purchased book review', 3, $bookId, $customerId);
    }
}