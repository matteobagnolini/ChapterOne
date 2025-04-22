<?php

require_once __DIR__ . '/BaseTest.php';

class BestSellersTest extends BaseTest {

    public function testBestSellersOrder(): void {
        $this->tearDown();

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 20.00, 'cover1.jpg', null, null, null);
        $book2Id = $this->db->insertBook('Book 2', 'Description 2', 30.00, 'cover2.jpg', null, null, null);
        $book3Id = $this->db->insertBook('Book 3', 'Description 3', 40.00, 'cover3.jpg', null, null, null);

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Aggiungi i libri al carrello e crea ordini
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];

        // Primo ordine: 2 copie di Book 1
        $this->db->insertBookInCart($cartId, $book1Id, 2);
        $this->db->insertOrder('2025-04-09 12:00:00', 40.00, $customerId, null);

        // Secondo ordine: 3 copie di Book 2
        $this->db->insertBookInCart($cartId, $book2Id, 3);
        $this->db->insertOrder('2025-04-10 12:00:00', 90.00, $customerId, null);

        // Terzo ordine: 1 copia di Book 3
        $this->db->insertBookInCart($cartId, $book3Id, 1);
        $this->db->insertOrder('2025-04-11 12:00:00', 40.00, $customerId, null);

        // Recupera i bestseller
        $bestSellers = $this->db->getBestSellers(10);

        // Verifica l'ordine dei bestseller
        $this->assertCount(3, $bestSellers, "Dovrebbero esserci 3 bestseller.");
        
        // Verifica che i dati completi dei libri siano presenti
        $this->assertEquals($book2Id, $bestSellers[0]['Id'], "Book 2 dovrebbe essere il bestseller più venduto.");
        $this->assertEquals('Book 2', $bestSellers[0]['Title'], "Il titolo dovrebbe essere 'Book 2'");
        $this->assertEquals(30.00, $bestSellers[0]['Price'], "Il prezzo dovrebbe essere 30.00");
        $this->assertEquals('cover2.jpg', $bestSellers[0]['Cover'], "La copertina dovrebbe essere 'cover2.jpg'");
        $this->assertEquals(3, $bestSellers[0]['Purchase_count'], "Il conteggio acquisti dovrebbe essere 3");
        
        $this->assertEquals($book1Id, $bestSellers[1]['Id'], "Book 1 dovrebbe essere il secondo bestseller.");
        $this->assertEquals('Book 1', $bestSellers[1]['Title'], "Il titolo dovrebbe essere 'Book 1'");
        $this->assertEquals(2, $bestSellers[1]['Purchase_count'], "Il conteggio acquisti dovrebbe essere 2");
        
        $this->assertEquals($book3Id, $bestSellers[2]['Id'], "Book 3 dovrebbe essere il terzo bestseller.");
        $this->assertEquals('Book 3', $bestSellers[2]['Title'], "Il titolo dovrebbe essere 'Book 3'");
        $this->assertEquals(1, $bestSellers[2]['Purchase_count'], "Il conteggio acquisti dovrebbe essere 1");
    }

    public function testBestSellerRemovedWhenBookDeleted(): void {
        $this->tearDown();

        // Aggiungi un libro
        $bookId = $this->db->insertBook('Book 1', 'Description 1', 20.00, 'cover1.jpg', null, null, null);

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('Jane', 'Smith', 'jane.smith@example.com', 'password123', '456 Elm St', '0987654321');

        // Aggiungi il libro al carrello e crea un ordine
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $bookId, 1);
        $this->db->insertOrder('2025-04-09 12:00:00', 20.00, $customerId, null);

        // Verifica che il libro sia nei bestseller
        $bestSellers = $this->db->getBestSellers(10);
        $this->assertCount(1, $bestSellers, "Dovrebbe esserci 1 bestseller.");
        $this->assertEquals($bookId, $bestSellers[0]['Id'], "Il libro dovrebbe essere nei bestseller.");
        $this->assertEquals('Book 1', $bestSellers[0]['Title'], "Il titolo dovrebbe essere 'Book 1'");
        $this->assertEquals(1, $bestSellers[0]['Purchase_count'], "Il conteggio acquisti dovrebbe essere 1");

        // Elimina il libro
        $this->db->deleteBook($bookId);

        // Verifica che il bestseller sia stato rimosso
        $bestSellersAfterDeletion = $this->db->getBestSellers(10);
        $this->assertCount(0, $bestSellersAfterDeletion, "Non dovrebbero esserci bestseller dopo l'eliminazione del libro.");
    }
}