<?php


require_once __DIR__ . '/BaseTest.php';

class CartTest extends BaseTest {
   

    public function testCartCreationAndDeletion(): void {
        $this->tearDown();
        // Creare un cliente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Verificare che il carrello sia stato creato automaticamente
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertNotNull($cart);

        // Eliminare il cliente e verificare che il carrello venga eliminato
        $this->db->deleteCustomer($customerId);
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertNull($cart);
    }

    public function testAddBooksToCart(): void {
        $this->tearDown();
        // Creare un cliente e libri
        $customerId = $this->db->insertCustomer('Jane', 'Doe', 'jane.doe@example.com', 'password123', '456 Elm St', '0987654321');
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id']; // Recuperare il Cart_id

        $bookId1 = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $bookId2 = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);

        // Aggiungere un libro al carrello
        $this->db->insertBookInCart($cartId, $bookId1, 1);
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(1, $cart['Item_count']);
        $this->assertEquals(10.00, $cart['Subtotal']);

        // Aggiungere un altro libro
        $this->db->insertBookInCart($cartId, $bookId2, 1);
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(2, $cart['Item_count']);
        $this->assertEquals(25.00, $cart['Subtotal']);

        // Aggiungere lo stesso libro
        $this->db->insertBookInCart($cartId, $bookId1, 1);
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(3, $cart['Item_count']);
        $this->assertEquals(35.00, $cart['Subtotal']);

    }

    public function testRemoveBookFromCart(): void {
        $this->tearDown();
        // Creare un cliente e libri
        $customerId = $this->db->insertCustomer('Alice', 'Smith', 'alice.smith@example.com', 'password123', '789 Oak St', '1234567890');
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id']; // Recuperare il Cart_id
    
        $bookId1 = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $bookId2 = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);
    
        // Aggiungere libri al carrello
        $this->db->insertBookInCart($cartId, $bookId1, 2);
        $this->db->insertBookInCart($cartId, $bookId2, 1);
    
        // Rimuovere un libro
        $this->db->deleteBookInCart($cartId, $bookId1);
        $cart = $this->db->getCartByCustomerId($customerId);
    
        // Verificare i valori aggiornati
        $this->assertEquals(1, $cart['Item_count']);
        $this->assertEquals(15.00, $cart['Subtotal']);
    }

    public function testResetCartAfterOrder(): void {
        $this->tearDown();
        // Creare un cliente e libri
        $customerId = $this->db->insertCustomer('Bob', 'Johnson', 'bob.johnson@example.com', 'password123', '321 Pine St', '9876543210');
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id']; // Recuperare il Cart_id

        $bookId1 = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $bookId2 = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);

        // Aggiungere libri al carrello
        $this->db->insertBookInCart($cartId, $bookId1, 1);
        $this->db->insertBookInCart($cartId, $bookId2, 1);

        // Creare un ordine
        $this->db->insertOrder('2025-04-09 12:00:00', 25.00, $customerId, null);

        // Verificare che il carrello sia stato resettato
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(0, $cart['Item_count']);
        $this->assertEquals(0.00, $cart['Subtotal']);
    }

    public function testBookRemovalFromStoreRemovesFromCart(): void {
        $this->tearDown();
        // Creare un cliente
        $customerId = $this->db->insertCustomer('Mark', 'Brown', 'mark.brown@example.com', 'password123', '789 Pine Rd', '5559876543');
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
    
        // Creare due libri
        $bookId1 = $this->db->insertBook('Science Book', 'Science description', 20.00, 'science.jpg', null, null, null);
        $bookId2 = $this->db->insertBook('Math Book', 'Math description', 15.00, 'math.jpg', null, null, null);
    
        // Aggiungere i libri al carrello
        $this->db->insertBookInCart($cartId, $bookId1, 1);
        $this->db->insertBookInCart($cartId, $bookId2, 1);
    
        // Verificare il contenuto del carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(2, $cart['Item_count']);
        $this->assertEquals(35.00, $cart['Subtotal']);
    
        // Eliminare un libro dal gestionale (tabella BOOK)
        $this->db->deleteBook($bookId1);
    
        // Verificare che il libro sia stato rimosso automaticamente dal carrello
        // tramite il vincolo di chiave esterna ON DELETE CASCADE
    
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(1, $cart['Item_count']); // Solo il secondo libro rimane
        $this->assertEquals(15.00, $cart['Subtotal']); // Solo il prezzo del secondo libro
    
        // Verifica ulteriore che il libro sia stato rimosso
        $stmt = $this->db->db->prepare("SELECT COUNT(*) as count FROM BOOK_IN_CART WHERE Book_id = ?");
        $stmt->bind_param('i', $bookId1);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $this->assertEquals(0, $result['count']); // Non ci dovrebbero essere righe con bookId1
    }

    public function testBookUpdateReflectedInCart(): void {
        $this->tearDown();
        // Creare un cliente
        $customerId = $this->db->insertCustomer('Laura', 'White', 'laura.white@example.com', 'password123', '456 Oak Ave', '5551234567');
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
    
        // Creare due libri con prezzi tondi
        $bookId1 = $this->db->insertBook('Novel 1', 'First novel description', 20.00, 'novel1.jpg', null, null, null);
        $bookId2 = $this->db->insertBook('Novel 2', 'Second novel description', 15.00, 'novel2.jpg', null, null, null);
    
        // Aggiungere i libri al carrello
        $this->db->insertBookInCart($cartId, $bookId1, 2);
        $this->db->insertBookInCart($cartId, $bookId2, 1);
    
        // Verificare che siano proprio quei libri specifici nel carrello
        $booksInCart = $this->db->getBooksInCart($cartId);
        $this->assertCount(2, $booksInCart);
        
        // Verificare il contenuto del carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(3, $cart['Item_count']); // 2 del primo libro + 1 del secondo
        $this->assertEquals(55.00, $cart['Subtotal']); // (20.00 * 2) + 15.00
        
        // Aggiornare il prezzo del libro 1
        $this->db->updateBook($bookId1, 'Novel 1', 'First novel description', 25.00, 'novel1.jpg', null, null, null);
        
        // Verificare che il carrello sia stato aggiornato automaticamente
        $cart = $this->db->getCartByCustomerId($customerId);
        $this->assertEquals(3, $cart['Item_count']); // La quantità non cambia
        $this->assertEquals(65.00, $cart['Subtotal']); // (25.00 * 2) + 15.00
    }

    
}