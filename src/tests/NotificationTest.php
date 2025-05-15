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
        $this->assertCount(1, $notifications);
        $this->assertEquals("Il tuo ordine è stato spedito!", $notifications[0]['Message']);
        $this->assertEquals("sent", $notifications[0]['Status']);
    
        // Aggiorna lo stato a 'arrived'
        $this->db->updateOrderStatus($orderId, 'arrived');
    
        // Verifica notifica per 'arrived'
        $notifications = $this->db->getOrderNotificationByOrderId($orderId);
        $this->assertCount(2, $notifications);
        $this->assertEquals("Il tuo ordine è arrivato!", $notifications[1]['Message']);
        $this->assertEquals("arrived", $notifications[1]['Status']);
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
        $this->assertCount(1, $notifications);
        $this->assertEquals("Il tuo ordine è stato spedito!", $notifications[0]['Message']);
        $this->assertEquals("sent", $notifications[0]['Status']);
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
        $this->assertCount(2, $notifications);
        $this->assertEquals("Il tuo ordine è arrivato!", $notifications[1]['Message']);
        $this->assertEquals("arrived", $notifications[1]['Status']);
        $this->assertEquals(0, $notifications[1]['Seen']); // Verifica che la nuova notifica non sia vista
    }
}