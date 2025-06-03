<?php
require_once __DIR__ . '/BaseTest.php';

class NotificationTest extends BaseTest {

    public function testOrderStatusNotifications(): void {
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
    
        // Aggiorna lo stato a 'sent'
        $this->db->updateOrderStatus($orderId, 'sent');
    
        // Verifica notifica per 'sent'
        $notifications = $this->db->getOrderNotificationByOrderId($orderId);
        $this->assertCount(2, $notifications);
        $this->assertEquals("Il tuo ordine è in elaborazione!", $notifications[0]['Message']);
        $this->assertEquals("pending", $notifications[0]['Status']);
    
        // Aggiorna lo stato a 'arrived'
        $this->db->updateOrderStatus($orderId, 'arrived');
    
        // Verifica notifica per 'arrived'
        $notifications = $this->db->getOrderNotificationByOrderId($orderId);
        $this->assertCount(3, $notifications);
        $this->assertEquals("Il tuo ordine è stato spedito!", $notifications[1]['Message']);
        $this->assertEquals("sent", $notifications[1]['Status']);
    }

    public function testNotificationSeen(): void {
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
    
        // Aggiorna lo stato a 'sent'
        $this->db->updateOrderStatus($orderId, 'sent');
    
        // Verifica notifica per 'sent'
        $notifications = $this->db->getOrderNotificationByOrderId($orderId);
        $this->assertCount(2, $notifications);
        $this->assertEquals("Il tuo ordine è in elaborazione!", $notifications[0]['Message']);
        $this->assertEquals("pending", $notifications[0]['Status']);
        $this->assertEquals(0, $notifications[0]['Seen']); // Verifica che la notifica non sia vista
    
        // Imposta la notifica come vista
        $this->db->SetSeenNotification($notifications[0]['Id']);
    
        // Verifica che la notifica sia stata vista
        $notification = $this->db->getOrderNotificationById($notifications[0]['Id']);
        $this->assertEquals(1, $notification['Seen']); // Verifica che il campo Seen sia aggiornato a 1
    
        // Aggiorna lo stato a 'arrived'
        $this->db->updateOrderStatus($orderId, 'arrived');
    
        // Verifica notifica per 'arrived'
        $notifications = $this->db->getOrderNotificationByOrderId($orderId);
        $this->assertCount(3, $notifications);
        $this->assertEquals("Il tuo ordine è stato spedito!", $notifications[1]['Message']);
        $this->assertEquals("sent", $notifications[1]['Status']);
        $this->assertEquals(0, $notifications[1]['Seen']); // Verifica che la nuova notifica non sia vista
    }


    public function testAdminNotificationOnBookOutOfStock(): void {
    $this->tearDown();

    // Crea un admin (se serve)
    $adminId = $this->db->insertAdmin('Admin', 'User', 'admin@example.com', password_hash('admin123', PASSWORD_DEFAULT));
    $this->assertIsInt($adminId);

    // Crea un cliente
    $customerId = $this->db->insertCustomer('Mario', 'Rossi', 'mario.rossi@example.com', 'password123', 'Via Roma 1', '3331234567');
    $this->assertIsInt($customerId);

    // Crea un libro con 1 copia
    $bookId = $this->db->insertBook('Libro Unico', 'Descrizione', 10.00, 'cover.jpg', null, null, null, 1);
    $this->assertIsInt($bookId);
    $this->db->updateBookQuantity($bookId, 1); // default is 10 for now
    $book = $this->db->getBookById($bookId);
    $this->assertEquals(1, $book['Product_count']);

    // Aggiungi il libro al carrello
    $cart = $this->db->getCartByCustomerId($customerId);
    $cartId = $cart['Id'];
    $this->db->insertBookInCart($cartId, $bookId, 1);

    // Crea l'ordine
    $orderId = $this->db->insertOrder(date('Y-m-d H:i:s'), 10.00, $customerId, null);
    $this->assertIsInt($orderId);



    // Controlla che il libro sia esaurito
    $book = $this->db->getBookById($bookId);
    $this->assertEquals(0, $book['Product_count']);

    // Controlla che sia stata creata una notifica per l'admin
    $adminNotifications = $this->db->getAdminOrderNotificationByOrderId($orderId);
    $this->assertNotEmpty($adminNotifications);
}
}