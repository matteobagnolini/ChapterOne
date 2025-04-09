<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class CartTest extends TestCase {
    private MySqlDatabase $db;

    protected function setUp(): void {
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);
    }

    protected function tearDown(): void {
        // Pulizia completa del database dopo ogni test
        $this->db->db->query("DELETE FROM BOOK_IN_CART");
        $this->db->db->query("DELETE FROM CART");
        $this->db->db->query("DELETE FROM REVIEW");
        $this->db->db->query("DELETE FROM `ORDER`");
        $this->db->db->query("DELETE FROM ORDER_DETAIL");
        $this->db->db->query("DELETE FROM DISCOUNT_CODE_USAGE");
        $this->db->db->query("DELETE FROM DISCOUNT_CODE");
        $this->db->db->query("DELETE FROM POST");
        $this->db->db->query("DELETE FROM BOOK");
        $this->db->db->query("DELETE FROM CATEGORY");
        $this->db->db->query("DELETE FROM AUTHOR");
        $this->db->db->query("DELETE FROM PUBLISHER");
        $this->db->db->query("DELETE FROM CUSTOMER");
        $this->db->db->query("DELETE FROM ADMIN");
    }

    public function testCartCreationAndDeletion(): void {
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
        // Creare un cliente e libri
        $customerId = $this->db->insertCustomer('Jane', 'Doe', 'jane.doe@example.com', 'password123', '456 Elm St', '0987654321');
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id']; // Recuperare il Cart_id

        $bookId1 = $this->db->insertBook('Book 1', 'Description 1', 10.00, 'cover1.jpg', null, null, null);
        $bookId2 = $this->db->insertBook('Book 2', 'Description 2', 15.00, 'cover2.jpg', null, null, null);

        // Aggiungere un libro al carrello
        $this->db->insertBookInCart($cartId, $bookId1, 1);
        $cart = $this->db->getCartByCustomerId($CustomerId);
        $this->assertEquals(1, $cart['Item_count']);
        $this->assertEquals(10.00, $cart['Subtotal']);

        // Aggiungere un altro libro
        $this->db->insertBookInCart($cartId, $bookId2, 1);
        $cart = $this->db->getCartById($cartId);
        $this->assertEquals(2, $cart['Item_count']);
        $this->assertEquals(25.00, $cart['Subtotal']);

        // Aggiungere lo stesso libro
        $this->db->insertBookInCart($cartId, $bookId1, 1);
        $cart = $this->db->getCartById($cartId);
        $this->assertEquals(3, $cart['Item_count']);
        $this->assertEquals(35.00, $cart['Subtotal']);
    }

    public function testRemoveBookFromCart(): void {
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
        $cart = $this->db->getCartByCustomerId($CustomerId);
        $this->assertEquals(1, $cart['Item_count']);
        $this->assertEquals(15.00, $cart['Subtotal']);
    }

    public function testResetCartAfterOrder(): void {
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
        $cart = $this->db->getCartById($cartId);
        $this->assertEquals(0, $cart['Item_count']);
        $this->assertEquals(0.00, $cart['Subtotal']);
    }
}